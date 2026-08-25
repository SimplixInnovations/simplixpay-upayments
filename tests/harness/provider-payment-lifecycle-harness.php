<?php

namespace Simplix\Pay\UPayments\Payment {
    \define('ABSPATH', __DIR__ . '/');

    $GLOBALS['splx_state'] = array();

    function &state() {
        return $GLOBALS['splx_state'];
    }

    function reset_state() {
        $GLOBALS['splx_state'] = array(
            'options' => array(),
            'orders' => array(),
            'scheduled' => array(),
            'filters' => array(),
            'actions' => array(),
            'remote_get_calls' => 0,
            'remote_response' => array('code' => 201, 'body' => ''),
            'remote_mutator' => null,
            'logs' => array(),
        );
    }

    function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
        $s =& state();
        $s['actions'][] = array($hook, $callback, $priority, $accepted_args);
        return true;
    }
    function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) {
        $s =& state();
        if (!isset($s['filters'][$hook])) {
            $s['filters'][$hook] = array();
        }
        $s['filters'][$hook][] = array($callback, $priority, $accepted_args);
        return true;
    }
    function remove_filter($hook, $callback, $priority = 10) {
        $s =& state();
        if (!isset($s['filters'][$hook])) {
            return false;
        }
        foreach ($s['filters'][$hook] as $i => $entry) {
            if ($entry[0] === $callback && $entry[1] === $priority) {
                unset($s['filters'][$hook][$i]);
                return true;
            }
        }
        return false;
    }
    function apply_test_filter($hook, $value) {
        $args = func_get_args();
        array_shift($args);
        $s =& state();
        if (empty($s['filters'][$hook])) {
            return $value;
        }
        usort($s['filters'][$hook], function ($a, $b) { return $a[1] <=> $b[1]; });
        foreach ($s['filters'][$hook] as $entry) {
            $call_args = array_slice($args, 0, $entry[2]);
            $value = call_user_func_array($entry[0], $call_args);
            $args[0] = $value;
        }
        return $value;
    }

    function get_option($name, $default = false) {
        $s =& state();
        return array_key_exists($name, $s['options']) ? $s['options'][$name] : $default;
    }
    function add_option($name, $value = '', $deprecated = '', $autoload = 'yes') {
        $s =& state();
        if (array_key_exists($name, $s['options'])) {
            return false;
        }
        $s['options'][$name] = $value;
        return true;
    }
    function update_option($name, $value, $autoload = null) {
        $s =& state();
        $s['options'][$name] = $value;
        return true;
    }
    function delete_option($name) {
        $s =& state();
        unset($s['options'][$name]);
        return true;
    }
    function wp_salt($scheme = 'auth') { return 'unit-test-wordpress-salt'; }

    function wp_remote_get($url, $args = array()) {
        $s =& state();
        $s['remote_get_calls']++;
        if (is_callable($s['remote_mutator'])) {
            call_user_func($s['remote_mutator']);
            $s['remote_mutator'] = null;
        }
        return array(
            'response' => array('code' => (int) $s['remote_response']['code']),
            'body' => (string) $s['remote_response']['body'],
            'request_url' => $url,
            'request_args' => $args,
        );
    }
    function is_wp_error($value) { return $value instanceof FakeWpError; }
    function wp_remote_retrieve_response_code($response) {
        return isset($response['response']['code']) ? (int) $response['response']['code'] : 0;
    }
    function wp_remote_retrieve_body($response) {
        return isset($response['body']) ? (string) $response['body'] : '';
    }

    function wc_get_price_decimals() { return 3; }
    function wc_format_decimal($value, $decimals = false) {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            return '';
        }
        $decimals = ($decimals === false) ? 3 : (int) $decimals;
        return number_format((float) $value, $decimals, '.', '');
    }
    function wc_get_order($id) {
        $s =& state();
        $id = (int) $id;
        return isset($s['orders'][$id]) ? $s['orders'][$id] : false;
    }

    function wp_next_scheduled($hook, $args = array()) {
        $s =& state();
        $key = $hook . '|' . json_encode(array_values($args));
        return isset($s['scheduled'][$key]) ? $s['scheduled'][$key] : false;
    }
    function wp_schedule_single_event($timestamp, $hook, $args = array()) {
        $s =& state();
        $key = $hook . '|' . json_encode(array_values($args));
        if (isset($s['scheduled'][$key])) {
            return false;
        }
        $s['scheduled'][$key] = (int) $timestamp;
        return true;
    }
    function wp_unschedule_event($timestamp, $hook, $args = array()) {
        $s =& state();
        $key = $hook . '|' . json_encode(array_values($args));
        unset($s['scheduled'][$key]);
        return true;
    }
    function clear_scheduled_for_order($order_id) {
        $s =& state();
        $key = 'simplixpay_upayments_reconcile_order|' . json_encode(array((int) $order_id));
        unset($s['scheduled'][$key]);
    }

    function __($text, $domain = 'default') { return $text; }
    function wc_get_logger() { return new FakeLogger(); }

    class FakeWpError {}
    class FakeLogger {
        public function info($message, $context = array()) { state()['logs'][] = array('info', $message); }
        public function warning($message, $context = array()) { state()['logs'][] = array('warning', $message); }
    }

    class FakeGateway {
        public $apiKey = 'test-api-key';
        public $test_mode = true;
        public $force_complete = false;
        public $host = 'sandboxapi.upayments.com';

        public function getMode() { return $this->test_mode; }
        public function getAPIUrl($route = '') { return 'https://' . $this->host . '/api/v1/' . $route; }
        public function getCurrencyCode($currency) { return strtoupper((string) $currency); }
        public function getIsOrderComplete() { return $this->force_complete; }
    }

    class FakeOrder {
        public $id;
        public $status = 'pending';
        public $payment_method = 'upayments';
        public $currency = 'KWD';
        public $total = '10.000';
        public $meta = array();
        public $transaction_id = '';
        public $payment_complete_calls = 0;
        public $update_status_calls = 0;
        public $save_calls = 0;
        public $notes = array();

        public function __construct($id, $upay_order_id) {
            $this->id = (int) $id;
            $this->meta['UPayments_order_id'] = $upay_order_id;
        }
        public function get_id() { return $this->id; }
        public function get_status() { return $this->status; }
        public function get_payment_method() { return $this->payment_method; }
        public function get_currency() { return $this->currency; }
        public function get_total() { return $this->total; }
        public function get_meta($key) { return array_key_exists($key, $this->meta) ? $this->meta[$key] : ''; }
        public function update_meta_data($key, $value) { $this->meta[$key] = $value; }
        public function delete_meta_data($key) { unset($this->meta[$key]); }
        public function save() { $this->save_calls++; return $this->id; }
        public function has_status($status) { return $this->status === $status; }
        public function is_paid() { return in_array($this->status, array('processing', 'completed'), true); }
        public function get_transaction_id() { return $this->transaction_id; }
        public function set_transaction_id($id) { $this->transaction_id = (string) $id; }
        public function payment_complete($transaction_id = '') {
            $this->payment_complete_calls++;
            if ($transaction_id !== '') {
                $this->transaction_id = (string) $transaction_id;
            }
            $target = apply_test_filter('woocommerce_payment_complete_order_status', 'processing', $this->id, $this);
            $this->status = (string) $target;
            $this->save();
        }
        public function update_status($status, $note = '') {
            $this->update_status_calls++;
            $this->status = (string) $status;
            if ($note !== '') { $this->notes[] = (string) $note; }
            $this->save();
            return true;
        }
        public function add_order_note($note) { $this->notes[] = (string) $note; return true; }
    }

    reset_state();
}

namespace {
    require_once __DIR__ . '/../../src/Payment/ProviderResult.php';
    require_once __DIR__ . '/../../src/Payment/StatusRateGate.php';
    require_once __DIR__ . '/../../src/Payment/OrderLock.php';
    require_once __DIR__ . '/../../src/Payment/StatusVerifier.php';
    require_once __DIR__ . '/../../src/Payment/PaymentLifecycle.php';

    use Simplix\Pay\UPayments\Payment\FakeGateway;
    use Simplix\Pay\UPayments\Payment\FakeOrder;
    use Simplix\Pay\UPayments\Payment\ProviderResult;
    use Simplix\Pay\UPayments\Payment\StatusRateGate;
    use Simplix\Pay\UPayments\Payment\StatusVerifier;
    use Simplix\Pay\UPayments\Payment\PaymentLifecycle;

    $pass = 0;
    $fail = 0;

    function ok($condition, $description) {
        global $pass, $fail;
        if ($condition) {
            $pass++;
            echo "PASS: $description\n";
        } else {
            $fail++;
            echo "FAIL: $description\n";
        }
    }
    function same($actual, $expected, $description) {
        ok($actual === $expected, $description . ' expected=' . var_export($expected, true) . ' got=' . var_export($actual, true));
    }
    function reset_fixture($id = 501) {
        \Simplix\Pay\UPayments\Payment\reset_state();
        $order = new FakeOrder($id, 'merchant-order-' . $id);
        $gateway = new FakeGateway();
        \Simplix\Pay\UPayments\Payment\state()['orders'][$id] = $order;
        return array($gateway, $order);
    }
    function transaction_for(FakeOrder $order, $result = 'CAPTURED', $track = 'track-abc', $payment_id = 'pay-123') {
        $tx = array(
            'result' => $result,
            'track_id' => $track,
            'merchant_requested_order_id' => $order->get_meta('UPayments_order_id'),
            'total_price' => $order->get_total(),
            'currency_type' => $order->get_currency(),
            'reference' => (string) $order->get_id(),
            'payment_type' => 'KNET',
        );
        if ($payment_id !== null) {
            $tx['payment_id'] = $payment_id;
        }
        return $tx;
    }
    function set_provider_transaction(array $tx, $code = 201) {
        $s =& \Simplix\Pay\UPayments\Payment\state();
        $s['remote_response'] = array(
            'code' => $code,
            'body' => json_encode(array('status' => true, 'data' => array('transaction' => $tx))),
        );
    }

    // ---------------------------------------------------------------------
    // Exact provider result table.
    // ---------------------------------------------------------------------
    $class_cases = array(
        'CAPTURED' => ProviderResult::CAPTURED,
        'PENDING' => ProviderResult::PENDING,
        'AUTHORIZED' => ProviderResult::PENDING,
        'APPROVED' => ProviderResult::PENDING,
        'NOT CAPTURED' => ProviderResult::FAILED,
        'FAILED' => ProviderResult::FAILED,
        'ERROR' => ProviderResult::FAILED,
        'CANCELED' => ProviderResult::CANCELLED,
        'REFUND' => ProviderResult::INDETERMINATE,
        'VOIDED' => ProviderResult::INDETERMINATE,
        'captured' => ProviderResult::INDETERMINATE,
        'FUTURE_STATUS' => ProviderResult::INDETERMINATE,
    );
    foreach ($class_cases as $input => $expected) {
        same(ProviderResult::classify($input), $expected, 'classifier ' . $input);
    }
    same(ProviderResult::classify(null), ProviderResult::INDETERMINATE, 'classifier null fail closed');
    same(ProviderResult::classify(''), ProviderResult::INDETERMINATE, 'classifier empty fail closed');

    // ---------------------------------------------------------------------
    // Conflict-safe query/form merge.
    // ---------------------------------------------------------------------
    same(PaymentLifecycle::merge_request_value(array('x' => '1'), array(), 'x')['value'], '1', 'GET-only request value');
    same(PaymentLifecycle::merge_request_value(array(), array('x' => '2'), 'x')['value'], '2', 'POST-only request value');
    ok(PaymentLifecycle::merge_request_value(array('x' => '1'), array('x' => '1'), 'x')['valid'], 'identical GET/POST accepted');
    ok(!PaymentLifecycle::merge_request_value(array('x' => '1'), array('x' => '2'), 'x')['valid'], 'conflicting GET/POST rejected');
    ok(!PaymentLifecycle::merge_request_value(array('x' => array('1')), array(), 'x')['valid'], 'array GET rejected');
    ok(!PaymentLifecycle::merge_request_value(array(), array('x' => array('1')), 'x')['valid'], 'array POST rejected');
    ok(!PaymentLifecycle::merge_request_value(array(), array(), 'x')['present'], 'missing request field remains missing');

    // ---------------------------------------------------------------------
    // Binding contract and fail-closed variants.
    // ---------------------------------------------------------------------
    list($gateway, $order) = reset_fixture(510);
    $base_tx = transaction_for($order);
    $bound = StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $base_tx);
    ok($bound['bound'], 'captured transaction binds');
    same($bound['classification'], ProviderResult::CAPTURED, 'captured classification after bind');

    $variants = array(
        'track_id' => array('value' => 'different', 'reason' => 'binding_track_id'),
        'merchant_requested_order_id' => array('value' => 'different', 'reason' => 'binding_merchant_requested_order_id'),
        'reference' => array('value' => '999', 'reason' => 'binding_reference'),
        'currency_type' => array('value' => 'USD', 'reason' => 'binding_currency'),
        'total_price' => array('value' => '9.000', 'reason' => 'binding_amount'),
    );
    foreach ($variants as $field => $case) {
        $tx = $base_tx;
        $tx[$field] = $case['value'];
        $r = StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $tx);
        ok(!$r['bound'], 'binding mismatch rejects ' . $field);
        same($r['reason'], $case['reason'], 'binding mismatch reason ' . $field);
    }
    $tx = $base_tx;
    unset($tx['payment_id']);
    $r = StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $tx);
    same($r['reason'], 'captured_payment_id_missing', 'CAPTURED requires payment id');
    $pending_no_payment = transaction_for($order, 'PENDING', 'track-abc', null);
    $r = StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $pending_no_payment);
    ok($r['bound'], 'PENDING can bind without payment id');
    same($r['classification'], ProviderResult::PENDING, 'PENDING bound classification');
    $unknown = transaction_for($order, 'FUTURE_STATUS');
    $r = StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $unknown);
    ok($r['bound'], 'unknown authenticated result can bind identity');
    same($r['classification'], ProviderResult::INDETERMINATE, 'unknown result stays indeterminate');
    $exp = $base_tx;
    $exp['total_price'] = '1e1';
    same(StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $exp)['reason'], 'amount_invalid', 'exponent amount rejected');

    // ---------------------------------------------------------------------
    // Status endpoint host and atomic 30/min rate gate.
    // ---------------------------------------------------------------------
    list($gateway, $order) = reset_fixture(520);
    $gateway->host = 'attacker.example';
    set_provider_transaction(transaction_for($order));
    $r = StatusVerifier::verify($gateway, $order, 'track-abc');
    same($r['reason'], 'status_url_invalid', 'status verifier rejects non-UPayments host');
    same(\Simplix\Pay\UPayments\Payment\state()['remote_get_calls'], 0, 'invalid host makes zero HTTP calls');

    list($gateway, $order) = reset_fixture(521);
    $acquired = 0;
    for ($i = 0; $i < 31; $i++) {
        if (StatusRateGate::acquire($gateway)) { $acquired++; }
    }
    same($acquired, 30, 'status rate gate allows exactly 30 slots per minute');
    same(StatusRateGate::limit_per_minute(), 30, 'status rate contract is 30/min');

    // ---------------------------------------------------------------------
    // CAPTURED uses canonical Woo payment_complete and is idempotent.
    // ---------------------------------------------------------------------
    list($gateway, $order) = reset_fixture(530);
    set_provider_transaction(transaction_for($order));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['state'], 'captured', 'CAPTURED outcome');
    same($order->payment_complete_calls, 1, 'CAPTURED calls payment_complete exactly once');
    same($order->update_status_calls, 0, 'CAPTURED does not direct-update paid status');
    same($order->get_transaction_id(), 'pay-123', 'Woo transaction ID is provider payment ID');
    same($order->get_status(), 'processing', 'Woo default paid status retained when force-complete disabled');
    same((string) $order->get_meta('_upay_verified_capture'), '1', 'verified capture flag set after completion');
    same($order->get_meta('UPayments_PaymentID'), 'pay-123', 'legacy verified payment ID retained');
    same($order->get_meta('UPayments_TrackID'), 'track-abc', 'legacy verified track retained');
    same($order->get_meta('_simplixpay_upayments_status_track_v1'), 'track-abc', 'trusted reconciliation cursor retained');
    $calls_after_first = \Simplix\Pay\UPayments\Payment\state()['remote_get_calls'];
    $out2 = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out2['state'], 'captured', 'duplicate CAPTURED sees verified state');
    same($order->payment_complete_calls, 1, 'duplicate CAPTURED does not re-fire payment_complete');
    same(\Simplix\Pay\UPayments\Payment\state()['remote_get_calls'], $calls_after_first, 'duplicate verified capture makes zero extra provider calls');

    // Force-completed merchant setting is applied through Woo filter, not direct status.
    list($gateway, $order) = reset_fixture(531);
    $gateway->force_complete = true;
    set_provider_transaction(transaction_for($order));
    PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'browser');
    same($order->get_status(), 'completed', 'force-complete uses Woo payment-complete status filter');
    same($order->update_status_calls, 0, 'force-complete still avoids direct paid update_status');

    // Existing paid state does not re-fire payment_complete, but can gain verified transaction ID.
    list($gateway, $order) = reset_fixture(532);
    $order->status = 'processing';
    set_provider_transaction(transaction_for($order));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['state'], 'captured', 'authenticated capture recognizes existing paid order');
    same($order->payment_complete_calls, 0, 'existing paid order does not re-fire payment_complete');
    same($order->get_transaction_id(), 'pay-123', 'existing paid order receives missing standard transaction ID');
    same((string) $order->get_meta('_upay_verified_capture'), '1', 'existing paid order gains verified capture barrier');

    // Transaction-ID conflict fails closed.
    list($gateway, $order) = reset_fixture(533);
    $order->status = 'processing';
    $order->transaction_id = 'other-payment';
    set_provider_transaction(transaction_for($order));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['state'], 'unchanged', 'transaction ID conflict leaves state unchanged');
    same((string) $order->get_meta('_upay_verified_capture'), '', 'transaction ID conflict never sets verified capture');

    // ---------------------------------------------------------------------
    // Terminal and unresolved provider states.
    // ---------------------------------------------------------------------
    list($gateway, $order) = reset_fixture(540);
    set_provider_transaction(transaction_for($order, 'FAILED'));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['state'], 'failed', 'authenticated FAILED becomes Woo failed');
    same($order->get_status(), 'failed', 'Woo status failed');
    same($order->payment_complete_calls, 0, 'FAILED never calls payment_complete');

    list($gateway, $order) = reset_fixture(541);
    set_provider_transaction(transaction_for($order, 'CANCELED'));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['state'], 'cancelled', 'authenticated CANCELED becomes Woo cancelled');
    same($order->get_status(), 'cancelled', 'Woo status cancelled');

    list($gateway, $order) = reset_fixture(542);
    set_provider_transaction(transaction_for($order, 'PENDING', 'track-pending', null));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-pending', 'webhook');
    same($out['state'], 'pending', 'PENDING remains unresolved');
    same($order->get_status(), 'pending', 'PENDING does not change Woo unpaid state');
    same($order->payment_complete_calls, 0, 'PENDING never completes payment');
    same((int) $order->get_meta('_simplixpay_upayments_reconcile_attempt_v1'), 1, 'PENDING schedules first reconciliation attempt');
    ok(\Simplix\Pay\UPayments\Payment\wp_next_scheduled('simplixpay_upayments_reconcile_order', array(542)) !== false, 'PENDING has one scheduled reconciliation');

    // Scheduled reconciliation later captures the same trusted transaction.
    \Simplix\Pay\UPayments\Payment\clear_scheduled_for_order(542);
    set_provider_transaction(transaction_for($order, 'CAPTURED', 'track-pending', 'pay-final'));
    PaymentLifecycle::reconcile_order(542);
    same($order->get_status(), 'processing', 'reconciliation CAPTURED reaches paid state');
    same($order->get_transaction_id(), 'pay-final', 'reconciliation stores final payment ID');
    same((string) $order->get_meta('_upay_verified_capture'), '1', 'reconciliation sets verified capture');
    ok(\Simplix\Pay\UPayments\Payment\wp_next_scheduled('simplixpay_upayments_reconcile_order', array(542)) === false, 'terminal capture clears scheduled reconciliation');

    // Unknown future status remains pending/indeterminate, never failed.
    list($gateway, $order) = reset_fixture(543);
    set_provider_transaction(transaction_for($order, 'FUTURE_STATUS'));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['state'], 'pending', 'unknown status remains unresolved');
    same($order->get_status(), 'pending', 'unknown status does not become failure');

    // Terminal callback can never downgrade a paid order.
    list($gateway, $order) = reset_fixture(544);
    $order->status = 'processing';
    $order->transaction_id = 'pay-existing';
    set_provider_transaction(transaction_for($order, 'FAILED', 'track-abc', 'pay-existing'));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['state'], 'unchanged', 'terminal provider result cannot downgrade paid Woo order');
    same($order->get_status(), 'processing', 'paid Woo state preserved');

    // Refunded order makes zero provider calls.
    list($gateway, $order) = reset_fixture(545);
    $order->status = 'refunded';
    set_provider_transaction(transaction_for($order));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['reason'], 'refunded', 'refunded preflight result');
    same(\Simplix\Pay\UPayments\Payment\state()['remote_get_calls'], 0, 'refunded order makes zero provider calls');

    // ---------------------------------------------------------------------
    // Rebinding under lock prevents TOCTOU order-total changes.
    // ---------------------------------------------------------------------
    list($gateway, $order) = reset_fixture(550);
    set_provider_transaction(transaction_for($order));
    \Simplix\Pay\UPayments\Payment\state()['remote_mutator'] = function () use ($order) {
        $order->total = '11.000';
    };
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['reason'], 'binding_changed_under_lock', 'fresh-order rebind catches total change during provider call');
    same($order->payment_complete_calls, 0, 'TOCTOU binding change never completes payment');

    // ---------------------------------------------------------------------
    // Atomic order lock contention prevents lifecycle mutation.
    // ---------------------------------------------------------------------
    list($gateway, $order) = reset_fixture(551);
    set_provider_transaction(transaction_for($order));
    \Simplix\Pay\UPayments\Payment\state()['options']['simplixpay_upay_order_lock_v1_551'] = array('token' => 'other', 'expires' => time() + 30);
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['reason'], 'order_lock_contention', 'live order lock contention fails closed');
    same($order->payment_complete_calls, 0, 'lock contention makes zero lifecycle transitions');

    // ---------------------------------------------------------------------
    // Retry/exhaustion is bounded to four scheduled attempts.
    // ---------------------------------------------------------------------
    list($gateway, $order) = reset_fixture(560);
    set_provider_transaction(transaction_for($order, 'PENDING', 'track-retry', null));
    PaymentLifecycle::process_order_status($gateway, $order, 'track-retry', 'webhook');
    for ($attempt = 1; $attempt <= 4; $attempt++) {
        \Simplix\Pay\UPayments\Payment\clear_scheduled_for_order(560);
        PaymentLifecycle::reconcile_order(560);
    }
    same((int) $order->get_meta('_simplixpay_upayments_reconcile_attempt_v1'), 4, 'reconciliation attempts capped at four');
    same((string) $order->get_meta('_simplixpay_upayments_reconcile_exhausted_v1'), '1', 'reconciliation exhaustion is durable');
    ok(count($order->notes) === 1, 'reconciliation exhaustion note emitted once');
    ok(\Simplix\Pay\UPayments\Payment\wp_next_scheduled('simplixpay_upayments_reconcile_order', array(560)) === false, 'no event remains after exhaustion');

    // ---------------------------------------------------------------------
    // Static architecture/safety guards.
    // ---------------------------------------------------------------------
    $root = dirname(__DIR__, 2);
    $identity_source = file_get_contents($root . '/src/Release/Identity.php');
    $lifecycle_source = file_get_contents($root . '/src/Payment/PaymentLifecycle.php');
    $verifier_source = file_get_contents($root . '/src/Payment/StatusVerifier.php');
    $rate_source = file_get_contents($root . '/src/Payment/StatusRateGate.php');
    $gateway_source = file_get_contents($root . '/UPayments.php');

    ok(strpos($identity_source, "PaymentLifecycle.php") !== false, 'release foothold loads payment lifecycle');
    ok(strpos($lifecycle_source, "add_action(self::CALLBACK_HOOK, array(__CLASS__, 'handle_callback'), 5)") !== false, 'new callback runs before inherited priority 10 handler');
    ok(strpos($gateway_source, 'woocommerce_api_' . '" . strtolower("WC_UPayments")') !== false || strpos($gateway_source, 'woocommerce_api_') !== false, 'historical wc_upayments callback identity remains in gateway');
    ok(strpos($lifecycle_source, '$_REQUEST') === false, 'new lifecycle never uses mixed COOKIE/GET/POST $_REQUEST');
    ok(strpos($lifecycle_source, 'payment_complete($payment_id)') !== false, 'captured path uses Woo payment_complete');
    ok(strpos($lifecycle_source, "execute_upayments_request('charge'") === false, 'lifecycle reconciliation never dispatches Charge');
    ok(strpos($lifecycle_source, "getAPIUrl('charge") === false, 'lifecycle contains no Charge route');
    ok(strpos($verifier_source, "'redirection' => 0") !== false, 'status lookup disables redirects');
    ok(strpos($verifier_source, "'sslverify'   => true") !== false, 'status lookup enforces TLS verification');
    ok(strpos($verifier_source, "'timeout'     => 15") !== false, 'status lookup has finite timeout');
    ok(strpos($rate_source, 'LIMIT_PER_MINUTE = 30') !== false, 'status query ceiling is frozen at 30/min');
    ok(strpos($gateway_source, 'function process_refund') === false, 'automatic gateway refund remains unsupported');
    ok(strpos($gateway_source, "'refunds'") === false && strpos($gateway_source, '"refunds"') === false, 'gateway does not advertise unsupported automatic refunds');

    echo "\n--- Provider Payment Lifecycle Report ---\n";
    echo "PASS: $pass\n";
    echo "FAIL: $fail\n";
    exit($fail === 0 ? 0 : 1);
}
