<?php

$q7_pass = 0;
$q7_fail = 0;
$q7_root = dirname(__DIR__, 2);

function q7_assert($condition, $label) {
    global $q7_pass, $q7_fail;
    if ($condition) { ++$q7_pass; echo "PASS: {$label}\n"; return; }
    ++$q7_fail; echo "FAIL: {$label}\n";
}

function q7_read($root, $relative) {
    $contents = file_get_contents($root . '/' . $relative);
    q7_assert(is_string($contents), "source readable: {$relative}");
    return is_string($contents) ? $contents : '';
}

function q7_contains($source, $needle) { return strpos($source, $needle) !== false; }

$phpstan = q7_read($q7_root, 'phpstan.neon.dist');
$phpcs = q7_read($q7_root, 'phpcs.xml.dist');
$source = q7_read($q7_root, 'src/Security/PublicOrderStatus.php');
$tests = q7_read($q7_root, 'tests/unit/Security/PublicOrderStatusTest.php');
$fixture = q7_read($q7_root, 'tests/support/wordpress-public-order-status.php');
$stubs = q7_read($q7_root, 'tests/phpstan/wordpress-option-stubs.php');
$workflow = q7_read($q7_root, '.github/workflows/quality-gates.yml');
$quality = q7_read($q7_root, 'docs/project/QUALITY-PLATFORM.md');
$status = q7_read($q7_root, 'docs/project/PROJECT-STATUS.md');
$readme = q7_read($q7_root, 'README.md');
$handoff = q7_read($q7_root, 'docs/project/NEW-CHAT-HANDOFF.md');
$playbook = q7_read($q7_root, 'docs/project/MASTER-ENGINEERING-PLAYBOOK.md');

q7_assert(q7_contains($phpstan, 'src/Security/PublicOrderStatus.php'), 'PHPStan owns public order status');
q7_assert(q7_contains($phpcs, 'src/Security/PublicOrderStatus.php'), 'PHPCS owns public order status');
q7_assert(!q7_contains($phpstan, 'baseline'), 'Q7 remains baseline-free');
q7_assert(!q7_contains($phpstan, 'ignoreErrors'), 'Q7 introduces no ignored analyzer errors');

foreach (array('wait', 'pending', 'failed', 'completed', 'cancelled') as $allowed) {
    q7_assert(q7_contains($source, "'{$allowed}'"), "public status remains explicit: {$allowed}");
}
q7_assert(q7_contains($source, "\$method !== 'GET'"), 'public poll remains GET-only');
q7_assert(q7_contains($source, "array_key_exists('wc_order_id', \$get)"), 'order ID remains explicit request input');
q7_assert(q7_contains($source, "(string) \$order->get_payment_method() === 'upayments'"), 'only UPayments orders are eligible');
q7_assert(q7_contains($source, '\$current_user_id === $order_user_id'), 'logged-in ownership remains exact');
q7_assert(q7_contains($source, 'hash_equals($order_key, $provided_key)'), 'guest order-key comparison remains constant-time');
q7_assert(q7_contains($source, "strlen(\$value) > 18"), 'order ID length remains bounded');
q7_assert(q7_contains($source, "preg_match('/\\A[1-9][0-9]*\\z/', \$value)"), 'order ID uses absolute anchors for strict positive decimal input');
q7_assert(q7_contains($source, 'strlen($value) > 128'), 'order key length remains bounded');
q7_assert(q7_contains($source, "preg_match('/[\\x00-\\x20\\x7F]/', \$value)"), 'order key rejects controls and whitespace');
q7_assert(q7_contains($source, "\$order->get_meta('UPayments_WHS')"), 'public output reads only the protected narrow status');
q7_assert(q7_contains($source, "array('status' => \$status, 'message' => '')"), 'successful response remains two-field only');
q7_assert(q7_contains($source, "'Order status unavailable.'"), 'failure response remains generic');

q7_assert(substr_count($tests, 'public function test_') >= 8, 'PublicOrderStatus has focused PHPUnit characterization');
foreach (array(
    'order_id_parser_accepts_only_bounded_positive_decimal_strings',
    'status_normalization_preserves_only_the_narrow_public_allowlist',
    'authorization_requires_upayments_and_exact_owner_or_order_key',
    'order_key_authorization_rejects_empty_oversized_control_and_non_string_values',
    'handle_rejects_non_get_and_invalid_or_missing_order_identifiers',
    'handle_rejects_non_upayments_and_unauthorized_orders',
    'handle_allows_exact_logged_in_owner_and_returns_only_narrow_status_payload',
    'handle_allows_exact_guest_key_after_unslashing_and_unknown_status_fails_closed',
) as $name) {
    q7_assert(q7_contains($tests, $name), "public-status test exists: {$name}");
}
q7_assert(q7_contains($tests, "array('status', 'message')"), 'response tests reject expanded public payloads');
q7_assert(q7_contains($tests, "'CAPTURED'"), 'unknown provider-like state is tested fail closed');
q7_assert(q7_contains($fixture, 'class SimplixPay_Test_Json_Response'), 'fixture captures terminating JSON responses');
q7_assert(q7_contains($fixture, 'function wc_get_order('), 'fixture provides deterministic order lookup');
q7_assert(q7_contains($fixture, 'function is_user_logged_in('), 'fixture provides deterministic login state');
q7_assert(q7_contains($fixture, 'function wp_send_json('), 'fixture captures WordPress JSON dispatch');
q7_assert(q7_contains($stubs, 'function wp_send_json('), 'analysis stubs declare public JSON dispatch');
q7_assert(q7_contains($stubs, 'function wc_get_order('), 'analysis stubs declare bounded order lookup');

q7_assert(q7_contains($workflow, 'quality-platform-public-order-status-harness.php'), 'Q7 harness is mandatory in Quality Gates');
q7_assert(q7_contains($workflow, 'if: ${{ always() }}'), 'protected H12 aggregator still always runs');
foreach (array(
    '85de7a009205e6bb810fad8ab8a0634ca91d1fa8',
    '07f944a3adbbdbf6953ea96512555cb6b16286fe',
    'Quality Gates run #201',
    '651e604659d1891e0f7d05b8e684edb4aa31c2b1',
    'Quality Gates run #202',
    'implementation branch deleted',
) as $evidence) {
    q7_assert(q7_contains($quality, $evidence), "Q6 closure evidence is pinned: {$evidence}");
}
q7_assert(q7_contains($quality, '**Status:** Q7 / IMPLEMENTATION'), 'quality record advances to Q7');
q7_assert(q7_contains($status, '| Current program gate | **Full Automated Quality Platform — Q7** |'), 'project status advances to Q7');
q7_assert(q7_contains($readme, 'The current program gate is **Full Automated Quality Platform — Q7**.'), 'README advances to Q7');
q7_assert(q7_contains($playbook, 'Last verified implementation main SHA: 651e604659d1891e0f7d05b8e684edb4aa31c2b1'), 'playbook pins Q6 merge');
q7_assert(q7_contains($playbook, 'Canonical implementation tree: 07f944a3adbbdbf6953ea96512555cb6b16286fe'), 'playbook pins Q6 tree');
q7_assert(!q7_contains($handoff, 'CURRENT / Q6'), 'handoff rejects stale current-Q6 marker');
q7_assert(!q7_contains($playbook, 'CURRENT / Q6'), 'playbook rejects stale current-Q6 marker');
q7_assert(q7_contains($workflow, "reject_across_live_records 'CURRENT / Q6'"), 'Governance rejects stale current-Q6 markers');

echo "\nQ7 Public Order Status Analysis: {$q7_pass} PASS / {$q7_fail} FAIL\n";
exit($q7_fail === 0 ? 0 : 1);
