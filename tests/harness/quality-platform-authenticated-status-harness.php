<?php

$q4_pass = 0;
$q4_fail = 0;
$q4_root = dirname(__DIR__, 2);

function q4_assert($condition, $label) {
    global $q4_pass, $q4_fail;

    if ($condition) {
        ++$q4_pass;
        echo "PASS: {$label}\n";
        return;
    }

    ++$q4_fail;
    echo "FAIL: {$label}\n";
}

function q4_read($root, $relative) {
    $contents = file_get_contents($root . '/' . $relative);
    q4_assert(is_string($contents), "source readable: {$relative}");
    return is_string($contents) ? $contents : '';
}

function q4_contains($source, $needle) {
    return strpos($source, $needle) !== false;
}

$phpstan = q4_read($q4_root, 'phpstan.neon.dist');
$phpcs = q4_read($q4_root, 'phpcs.xml.dist');
$verifier = q4_read($q4_root, 'src/Payment/StatusVerifier.php');
$tests = q4_read($q4_root, 'tests/unit/Payment/StatusVerifierTest.php');
$http_fixture = q4_read($q4_root, 'tests/support/wordpress-http.php');
$analysis_stubs = q4_read($q4_root, 'tests/phpstan/wordpress-option-stubs.php');
$workflow = q4_read($q4_root, '.github/workflows/quality-gates.yml');
$quality_record = q4_read($q4_root, 'docs/project/QUALITY-PLATFORM.md');
$status = q4_read($q4_root, 'docs/project/PROJECT-STATUS.md');
$readme = q4_read($q4_root, 'README.md');
$audit = q4_read($q4_root, 'docs/project/REPOSITORY-AUDIT.md');
$handoff = q4_read($q4_root, 'docs/project/NEW-CHAT-HANDOFF.md');
$playbook = q4_read($q4_root, 'docs/project/MASTER-ENGINEERING-PLAYBOOK.md');

q4_assert(q4_contains($phpstan, 'src/Payment/StatusVerifier.php'), 'PHPStan owns authenticated status verification');
q4_assert(q4_contains($phpcs, 'src/Payment/StatusVerifier.php'), 'PHPCS owns authenticated status verification');
q4_assert(!q4_contains($phpstan, 'baseline'), 'Q4 remains baseline-free');
q4_assert(!q4_contains($phpstan, 'ignoreErrors'), 'Q4 introduces no ignored analyzer errors');

q4_assert(q4_contains($verifier, '@param mixed $gateway'), 'verifier documents its defensive gateway boundary');
q4_assert(q4_contains($verifier, '@param mixed $order'), 'verifier documents its defensive order boundary');
q4_assert(q4_contains($verifier, "'sandboxapi.upayments.com'"), 'sandbox status host remains exact');
q4_assert(q4_contains($verifier, "'apiv2api.upayments.com'"), 'live status host remains exact');
q4_assert(q4_contains($verifier, "'/api/v1/get-payment-status/'"), 'status path remains exact');
q4_assert(
    q4_contains($verifier, "strtolower((string) \$parts['scheme']) !== 'https'")
        && q4_contains($tests, "\$gateway->scheme = 'http';"),
    'HTTPS-only destination enforcement has executable characterization'
);
q4_assert(q4_contains($verifier, "'redirection' => 0"), 'status transport forbids redirects');
q4_assert(q4_contains($verifier, "'sslverify'   => true"), 'status transport retains TLS verification');
q4_assert(q4_contains($verifier, "'Authorization' => 'Bearer ' . \$gateway->apiKey"), 'Bearer credential remains status-only transport state');
q4_assert(q4_contains($verifier, '$http_status !== 201'), 'status transport requires exact HTTP 201');
q4_assert(q4_contains($verifier, "\$decoded['status'] !== true"), 'provider envelope requires strict true status');

$url_check = strpos($verifier, 'if (!self::is_allowed_status_url($url, $track_id))');
$rate_check = strpos($verifier, 'if (!StatusRateGate::acquire($gateway))');
$http_call = strpos($verifier, '$response = wp_remote_get($url');
q4_assert(
    $url_check !== false && $rate_check !== false && $http_call !== false
        && $url_check < $rate_check && $rate_check < $http_call,
    'destination validation precedes rate consumption and Bearer transport'
);

foreach (array(
    'track_id',
    'merchant_requested_order_id',
    'total_price',
    'currency_type',
    'reference',
) as $binding_field) {
    q4_assert(q4_contains($verifier, "'{$binding_field}'"), "strict binding field remains required: {$binding_field}");
}
q4_assert(q4_contains($verifier, "hash_equals(\$local_canonical, \$verified_canonical)"), 'amount binding remains exact and float-free');
q4_assert(q4_contains($verifier, "'captured_payment_id_missing'"), 'captured status still requires a provider payment ID');

q4_assert(substr_count($tests, 'public function test_') >= 7, 'StatusVerifier has focused PHPUnit characterization');
q4_assert(substr_count($tests, 'assert_unauthenticated_failure') === 6, 'every transport/envelope failure asserts unauthenticated and unbound state');
foreach (array(
    'invalid_boundaries_fail_before_rate_or_http_mutation',
    'disallowed_destination_is_rejected_before_bearer_or_rate_slot',
    'hardened_transport_binds_an_exact_captured_transaction',
    'network_and_protocol_failures_remain_unauthenticated',
    'binding_rejects_identity_currency_and_amount_mismatches',
    'capture_requires_payment_id_while_nonterminal_results_bind_fail_closed',
    'exact_decimal_binding_accepts_only_numeric_equality',
) as $test_name) {
    q4_assert(q4_contains($tests, $test_name), "status-verifier test exists: {$test_name}");
}

q4_assert(q4_contains($http_fixture, 'simplixpay_test_http_calls'), 'HTTP fixture records exact outbound calls');
q4_assert(q4_contains($http_fixture, 'SimplixPay_Test_WP_Error'), 'HTTP fixture exposes deterministic network failure');
q4_assert(q4_contains($analysis_stubs, 'function wp_remote_get('), 'analysis stubs declare bounded WordPress HTTP transport');
q4_assert(q4_contains($analysis_stubs, 'function is_wp_error('), 'analysis stubs declare WordPress error classification');

q4_assert(q4_contains($workflow, 'quality-platform-authenticated-status-harness.php'), 'Q4 harness is mandatory in Quality Gates');
q4_assert(q4_contains($workflow, 'if: ${{ always() }}'), 'protected H12 aggregator still always runs');
q4_assert(q4_contains($workflow, 'QUALITY_PLATFORM_RESULT: ${{ needs.quality-platform.result }}'), 'protected H12 aggregator still reads quality result');
q4_assert(q4_contains($workflow, 'PHP_SYNTAX_RESULT: ${{ needs.php-syntax-compatibility.result }}'), 'protected H12 aggregator still reads syntax result');

foreach (array(
    'e08be468b5453524996c525860c12d5619081132',
    '703a56c03e95862b8b4807d9a1ea28e2e3e201dd',
    'Quality Gates run #188',
    '30e99a6a456b72709c87e442b8437301ba64e99b',
    'Quality Gates run #189',
    'implementation branch deleted',
) as $closure_evidence) {
    q4_assert(q4_contains($quality_record, $closure_evidence), "Q3 closure evidence is pinned: {$closure_evidence}");
}
q4_assert(q4_contains($quality_record, '**Status:** Q4 / IMPLEMENTATION'), 'quality record advances to Q4');
q4_assert(q4_contains($status, '| Current program gate | **Full Automated Quality Platform — Q4** |'), 'project status advances to Quality Platform Q4');
q4_assert(q4_contains($readme, 'The current program gate is **Full Automated Quality Platform — Q4**.'), 'README advances to Quality Platform Q4');
q4_assert(
    q4_contains($audit, 'deterministic PHPUnit characterization of StatusVerifier destination-before-rate/Bearer validation')
        && q4_contains($audit, 'PHPCS ownership of StatusVerifier beside the Q1-Q3 modules')
        && !q4_contains($audit, 'characterization of the exact 30-slot rate gate'),
    'repository audit assigns the exact Q4 authenticated-status scope'
);
q4_assert(!q4_contains($handoff, 'CURRENT / Q3'), 'handoff rejects the stale current-Q3 marker');
q4_assert(!q4_contains($playbook, 'CURRENT / Q3'), 'master playbook rejects the stale current-Q3 marker');
q4_assert(q4_contains($workflow, "reject_across_live_records 'CURRENT / Q3'"), 'Governance rejects stale current-Q3 markers');

echo "\nQ4 Authenticated Status Analysis: {$q4_pass} PASS / {$q4_fail} FAIL\n";
exit($q4_fail === 0 ? 0 : 1);
