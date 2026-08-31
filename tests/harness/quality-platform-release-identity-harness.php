<?php

$q8_pass = 0;
$q8_fail = 0;
$q8_root = dirname(__DIR__, 2);

function q8_assert($condition, $label) {
    global $q8_pass, $q8_fail;
    if ($condition) { ++$q8_pass; echo "PASS: {$label}\n"; return; }
    ++$q8_fail; echo "FAIL: {$label}\n";
}

function q8_read($root, $relative) {
    $contents = file_get_contents($root . '/' . $relative);
    q8_assert(is_string($contents), "source readable: {$relative}");
    return is_string($contents) ? $contents : '';
}

function q8_contains($source, $needle) { return strpos($source, $needle) !== false; }

$phpstan = q8_read($q8_root, 'phpstan.neon.dist');
$phpcs = q8_read($q8_root, 'phpcs.xml.dist');
$source = q8_read($q8_root, 'src/Release/Identity.php');
$tests = q8_read($q8_root, 'tests/unit/Release/IdentityTest.php');
$workflow = q8_read($q8_root, '.github/workflows/quality-gates.yml');
$quality = q8_read($q8_root, 'docs/project/QUALITY-PLATFORM.md');
$status = q8_read($q8_root, 'docs/project/PROJECT-STATUS.md');
$readme = q8_read($q8_root, 'README.md');
$handoff = q8_read($q8_root, 'docs/project/NEW-CHAT-HANDOFF.md');
$playbook = q8_read($q8_root, 'docs/project/MASTER-ENGINEERING-PLAYBOOK.md');

q8_assert(q8_contains($phpstan, 'src/Release/Identity.php'), 'PHPStan owns release identity');
q8_assert(q8_contains($phpcs, 'src/Release/Identity.php'), 'PHPCS owns release identity');
q8_assert(!q8_contains($phpstan, 'baseline'), 'Q8 remains baseline-free');
q8_assert(!q8_contains($phpstan, 'ignoreErrors'), 'Q8 introduces no ignored analyzer errors');

foreach (array(
    "PRODUCT_NAME = 'SimplixPay for UPayments'",
    "SHORT_NAME = 'SimplixPay UPayments'",
    "VERSION = '0.1.0'",
    "SLUG = 'simplixpay-upayments'",
    "REPOSITORY = 'SimplixInnovations/simplixpay-upayments'",
    "UPDATE_CHANNEL = 'disabled'",
    "LEGACY_MAIN_FILE = 'UPayments.php'",
    "LEGACY_TEXT_DOMAIN = 'upayments'",
    "TARGET_MAIN_FILE = 'simplixpay-upayments.php'",
    "TARGET_TEXT_DOMAIN = 'simplixpay-upayments'",
) as $identity) {
    q8_assert(q8_contains($source, $identity), "release identity remains exact: {$identity}");
}
q8_assert(q8_contains($source, "function_exists('add_action')"), 'isolated identity loading retains conditional runtime bootstrap');
q8_assert(q8_contains($source, 'PaymentLifecycle::bootstrap()'), 'WordPress runtime still reaches the payment lifecycle bootstrap');
q8_assert(substr_count($tests, 'public function test_') >= 6, 'release identity has focused PHPUnit characterization');
q8_assert(q8_contains($tests, 'test_historical_install_identities_remain_exact'), 'historical install identities are unit protected');
q8_assert(q8_contains($tests, 'test_future_targets_are_frozen_but_not_current_identity'), 'future targets remain distinct from current identities');
q8_assert(q8_contains($tests, 'test_external_update_channel_is_explicitly_disabled'), 'disabled updater is unit protected');

q8_assert(q8_contains($workflow, 'quality-platform-release-identity-harness.php'), 'Q8 harness is mandatory in Quality Gates');
q8_assert(q8_contains($workflow, 'if: ${{ always() }}'), 'protected H12 aggregator still always runs');
foreach (array(
    '48de59414c952d6f90ce90c4f462dde67fcbdabc',
    '6ef43632a4868a1114b5468a38ad45138e41c393',
    'Quality Gates run #212',
    'e00a80147d4f6267d137e1bdfa0b2d1211e00f6a',
    'Quality Gates run #213',
    'implementation branch deleted',
) as $evidence) {
    q8_assert(q8_contains($quality, $evidence), "Q7 closure evidence is pinned: {$evidence}");
}
q8_assert(q8_contains($quality, '**Status:** Q8 / IMPLEMENTATION'), 'quality record advances to Q8');
q8_assert(q8_contains($status, '| Current program gate | **Full Automated Quality Platform — Q8** |'), 'project status advances to Q8');
q8_assert(q8_contains($readme, 'The current program gate is **Full Automated Quality Platform — Q8**.'), 'README advances to Q8');
q8_assert(q8_contains($playbook, 'Last verified implementation main SHA: e00a80147d4f6267d137e1bdfa0b2d1211e00f6a'), 'playbook pins Q7 merge');
q8_assert(q8_contains($playbook, 'Canonical implementation tree: 6ef43632a4868a1114b5468a38ad45138e41c393'), 'playbook pins Q7 tree');
q8_assert(!q8_contains($handoff, 'CURRENT / Q7'), 'handoff rejects stale current-Q7 marker');
q8_assert(!q8_contains($playbook, 'CURRENT / Q7'), 'playbook rejects stale current-Q7 marker');
q8_assert(q8_contains($workflow, "reject_across_live_records 'CURRENT / Q7'"), 'Governance rejects stale current-Q7 markers');

echo "\nQ8 Release Identity Analysis: {$q8_pass} PASS / {$q8_fail} FAIL\n";
exit($q8_fail === 0 ? 0 : 1);
