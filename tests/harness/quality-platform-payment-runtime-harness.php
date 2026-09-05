<?php

$pass = 0;
$fail = 0;
$root = dirname(__DIR__, 2);

function q17_assert($condition, $label) {
    global $pass, $fail;
    if ($condition) {
        ++$pass;
        echo "PASS: " . $label . "\n";
        return;
    }
    ++$fail;
    echo "FAIL: " . $label . "\n";
}

function q17_read($root, $relative) {
    $value = file_get_contents($root . '/' . $relative);
    q17_assert(is_string($value), 'source readable: ' . $relative);
    return is_string($value) ? $value : '';
}

function q17_has($source, $needle) {
    return strpos($source, $needle) !== false;
}

function q17_blob($path) {
    $bytes = file_get_contents($path);
    return is_string($bytes) ? sha1('blob ' . strlen($bytes) . "\0" . $bytes) : '';
}

$phpstan = q17_read($root, 'phpstan.neon.dist');
$phpcs = q17_read($root, 'phpcs.xml.dist');
$checkout = q17_read($root, 'src/Payment/CheckoutOrchestrator.php');
$lifecycle = q17_read($root, 'src/Payment/PaymentLifecycle.php');
$checkout_tests = q17_read($root, 'tests/unit/Payment/CheckoutOrchestratorTest.php');
$lifecycle_tests = q17_read($root, 'tests/unit/Payment/PaymentLifecycleTest.php');
$runtime_fixture = q17_read($root, 'tests/support/wordpress-payment-runtime.php');
$runtime_stubs = q17_read($root, 'tests/phpstan/payment-runtime-stubs.php');
$bootstrap = q17_read($root, 'tests/bootstrap.php');
$workflow = q17_read($root, '.github/workflows/quality-gates.yml');
$quality = q17_read($root, 'docs/project/QUALITY-PLATFORM.md');
$status = q17_read($root, 'docs/project/PROJECT-STATUS.md');
$readme = q17_read($root, 'README.md');
$agents = q17_read($root, 'AGENTS.md');
$playbook = q17_read($root, 'docs/project/MASTER-ENGINEERING-PLAYBOOK.md');
$audit = q17_read($root, 'docs/project/REPOSITORY-AUDIT.md');
$handoff = q17_read($root, 'docs/project/NEW-CHAT-HANDOFF.md');

foreach (array('src/Payment/CheckoutOrchestrator.php', 'src/Payment/PaymentLifecycle.php') as $path) {
    q17_assert(q17_has($phpstan, $path), 'PHPStan owns payment runtime: ' . $path);
    q17_assert(q17_has($phpcs, $path), 'PHPCS owns payment runtime: ' . $path);
}
q17_assert(q17_has($phpstan, 'tests/phpstan/payment-runtime-stubs.php'), 'PHPStan loads bounded payment-runtime stubs');
q17_assert(!q17_has($phpstan, 'baseline'), 'Q17 remains baseline-free');
q17_assert(!q17_has($phpstan, 'ignoreErrors'), 'Q17 has no ignored analyzer errors');

q17_assert(q17_has($checkout, "preg_match('/^[1-9][0-9]*\\\\z/', \$value)"), 'checkout order IDs use absolute end anchor');
q17_assert(q17_has($checkout, "preg_match('/^[A-Z]{3}\\\\z/', \$currency)"), 'provider currency uses absolute end anchor');
q17_assert(q17_has($checkout, "preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{11,30}\\\\z/', \$iban)"), 'provider IBAN uses absolute end anchor');
q17_assert(q17_has($checkout, 'CheckoutPayload::build_amount_json_token($amount_str)'), 'order amount retains exact JSON-number validator');
q17_assert(q17_has($checkout, 'CustomerTokenIdentity::clear_stale_attempt_metadata($order)'), 'new Charge attempt clears stale attempt state');
q17_assert(
    q17_has($checkout, 'delete_meta_data("UPayments_order_id")')
    && q17_has($checkout, 'add_meta_data("UPayments_order_id", $unique_order_id)'),
    'successful Charge rotates provider order identity'
);
q17_assert(q17_has($checkout, 'Nonce ownership is upstream in Woo checkout'), 'Classic checkout nonce ownership is documented narrowly');
q17_assert(!q17_has($bootstrap, "support/wordpress-payment-runtime.php"), 'heavy payment-runtime fixture stays isolated from global PHPUnit bootstrap');

foreach (array(
    'test_process_rejects_noncanonical_order_ids_before_woo_lookup',
    'test_process_keeps_positive_integer_order_ids_compatible',
    'test_currency_with_terminal_newline_is_rejected_before_provider_request',
    'test_iban_with_terminal_newline_is_rejected_before_provider_request',
    'test_multimerchant_charge_newline_already_fails_closed_before_provider_request'
) as $name) {
    q17_assert(q17_has($checkout_tests, $name), 'checkout runtime test: ' . $name);
}
foreach (array(
    'test_reconcile_rejects_noncanonical_order_ids_before_woo_lookup',
    'test_reconcile_keeps_positive_integer_order_ids_compatible',
    'test_request_merge_is_presence_aware_and_conflict_safe'
) as $name) {
    q17_assert(q17_has($lifecycle_tests, $name), 'lifecycle runtime test: ' . $name);
}
q17_assert(
    q17_has($checkout_tests, 'RunTestsInSeparateProcesses')
    && q17_has($lifecycle_tests, 'RunTestsInSeparateProcesses'),
    'runtime characterization remains process isolated'
);
q17_assert(
    q17_has($runtime_fixture, 'SimplixPay_Test_Payment_Runtime_WC')
    && q17_has($checkout_tests, '$requests = array();'),
    'runtime fixture exposes deterministic Woo/provider observation'
);
q17_assert(q17_has($runtime_stubs, 'function wc_get_checkout_url'), 'payment-runtime stubs model checkout URL boundary');

q17_assert(q17_has($lifecycle, "preg_match('/^[1-9][0-9]*\\\\z/', \$value)"), 'lifecycle order IDs use absolute end anchor');
q17_assert(q17_has($lifecycle, 'merge_request_value('), 'callback preserves presence-aware GET/POST merge');
q17_assert(
    q17_has($lifecycle, 'OrderLock::acquire($order_id)')
    && q17_has($lifecycle, 'OrderLock::release($order_id, $lock_token)'),
    'payment truth remains lock bounded'
);
q17_assert(q17_has($lifecycle, 'StatusVerifier::bind_transaction('), 'locked path rebinds authenticated provider status');
q17_assert(q17_has($lifecycle, "private const TRUSTED_TRACK_META = '_simplixpay_upayments_status_track_v1';"), 'trusted track identity remains exact');
q17_assert(q17_has($lifecycle, "private const TRUSTED_REQUESTED_META = '_simplixpay_upayments_status_requested_v1';"), 'trusted requested identity remains exact');
q17_assert(q17_has($lifecycle, "private const UNVERIFIED_TRACK_META = '_simplixpay_upayments_unverified_track_v1';"), 'unverified track identity remains exact');
q17_assert(q17_has($lifecycle, "private const UNVERIFIED_REQUESTED_META = '_simplixpay_upayments_unverified_requested_v1';"), 'unverified requested identity remains exact');
q17_assert(q17_has($lifecycle, 'private const MAX_RECONCILE_ATTEMPTS = 4;'), 'reconciliation cap remains exact');
q17_assert(
    q17_has($lifecycle, 'reset_attempt_cursor_state($fresh_order)')
    && q17_has($lifecycle, 'reset_attempt_cursor_state($order)'),
    'stale/new Charge attempts reset cursor state'
);
q17_assert(q17_has($lifecycle, 'if (self::is_refunded($order) || self::is_verified_capture($order))'), 'captured path blocks refunded/verified resurrection');
q17_assert(q17_has($lifecycle, "self::log('transaction_id_conflict', 'warning')"), 'captured path fails closed on transaction conflict');
q17_assert(q17_has($lifecycle, '$order->payment_complete($payment_id)'), 'captured path keeps canonical Woo payment completion');
q17_assert(q17_has($lifecycle, "self::log('payment_complete_postcondition_failed', 'warning')"), 'capture requires payment-complete postcondition');
q17_assert(
    q17_has($lifecycle, 'Provider callbacks cannot carry a WordPress nonce; authority comes only from authenticated status binding.'),
    'callback nonce exception documents provider-auth authority'
);

q17_assert(
    q17_blob($root . '/includes/Subscription/Cron/Scheduler.php') === '5251866d4df2d1326e7c09f0c8ec1d146c0bb325',
    'protected Scheduler blob remains exact'
);
q17_assert(
    q17_blob($root . '/includes/Subscription/Cron/CycleClaim.php') === 'c34d83e2d77cc65024fe663e4c378cecb2b17347',
    'protected CycleClaim blob remains exact'
);

foreach (array(
    'provider-payment-lifecycle-harness.php',
    'provider-payment-amount-binding-harness.php',
    'architecture-checkout-orchestration-harness.php',
    'quality-platform-migration-core-harness.php'
) as $name) {
    q17_assert(q17_has($workflow, 'run: php tests/harness/' . $name), 'historical runtime regression remains mandatory: ' . $name);
}
q17_assert(q17_has($workflow, 'run: php tests/harness/quality-platform-payment-runtime-harness.php'), 'Q17 harness is mandatory');
q17_assert(q17_has($workflow, 'if: ${{ always() }}'), 'H12 aggregator always runs');
q17_assert(q17_has($agents, 'quality-platform-payment-runtime-harness.php'), 'AGENTS keeps Q17 mandatory');

foreach (array(
    '3cff2fcc64053d79be7427696c86039f1b52bbfd',
    'b9cc6eafb3c7f8df36b9c5db8b2e45bb330688d2',
    'Quality Gates run #315',
    '160 tests / 987 assertions',
    'Q16 **120/0**',
    'CodeQL PR scan #83',
    '06a9ebd732c7cc3f062d4bb361aaef4054a1dfa3',
    'Quality Gates run #316',
    'main security run #84',
    'implementation branch deleted'
) as $evidence) {
    q17_assert(q17_has($quality, $evidence), 'Q16 closure evidence pinned: ' . $evidence);
}

q17_assert(q17_has($quality, '**Status:** Q17 / IMPLEMENTATION'), 'quality record advances to Q17');
q17_assert(q17_has($status, '| Quality Platform Q16 migration-core analysis | **DONE / VERIFIED** |'), 'project status preserves Q16 completion row');
q17_assert(q17_has($status, '| Current program gate | **Full Automated Quality Platform — Q17** |'), 'project status advances to Q17');
q17_assert(q17_has($status, '## Latest verified milestone — Quality Platform Q16 migration-core analysis'), 'project status names Q16 as latest verified milestone');
q17_assert(q17_has($readme, '| Quality Platform Q1-Q16 | **DONE / VERIFIED** |'), 'README completion table includes Q16');
q17_assert(q17_has($readme, 'The current program gate is **Full Automated Quality Platform — Q17**.'), 'README advances to Q17');
q17_assert(q17_has($playbook, '**Q17 / CURRENT GATE — PAYMENT-RUNTIME CLOSEOUT**'), 'playbook has Q17 as current closeout');
q17_assert(!q17_has($playbook, '**Q17 / PLANNED PAYMENT-RUNTIME CLOSEOUT**'), 'playbook removes stale planned-Q17 marker');
q17_assert(q17_has($audit, 'any later Q gate requires a concrete separately bounded enterprise-critical risk'), 'repository audit preserves bounded post-Q17 extension policy');
q17_assert(q17_has($handoff, 'Quality Platform Q16 migration-core analysis: **DONE / VERIFIED**'), 'handoff preserves Q16 completion');
q17_assert(q17_has($handoff, 'Full Automated Quality Platform — Q17'), 'handoff advances to Q17');

echo "\nQ17 Payment Runtime Analysis: " . $pass . " PASS / " . $fail . " FAIL\n";
exit($fail === 0 ? 0 : 1);
