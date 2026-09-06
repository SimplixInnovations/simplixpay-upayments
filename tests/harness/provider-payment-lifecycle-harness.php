<?php

namespace Simplixi\SUCheckout\UPayments\Payment {
    \define('ABSPATH', __DIR__ . '/');

    $GLOBALS['splx_state'] = array();

    function &state() { return $GLOBALS['splx_state']; }

    function reset_state() {
        $GLOBALS['splx_state'] = array(
            'options' => array(),
            'orders' => array(),
            'gateway' => null,
            'scheduled' => array(),
            'filters' => array(),
            'actions' => array(),
            'remote_get_calls' => 0,
            'remote_response' => array('code' => 201, 'body' => ''),
            'remote_mutator' => null,
            'db_query_mutator' => null,
            'logs' => array(),
        );
        $GLOBALS['wpdb'] = new FakeWpdb();
    }

    function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
        state()['actions'][] = array($hook, $callback, $priority, $accepted_args);
        return true;
    }
    function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) {
        if (!isset(state()['filters'][$hook])) { state()['filters'][$hook] = array(); }
        state()['filters'][$hook][] = array($callback, $priority, $accepted_args);
        return true;
    }
    function remove_filter($hook, $callback, $priority = 10) {
        if (empty(state()['filters'][$hook])) { return false; }
        foreach (state()['filters'][$hook] as $i => $entry) {
            if ($entry[0] === $callback && $entry[1] === $priority) {
                unset(state()['filters'][$hook][$i]);
                return true;
            }
        }
        return false;
    }
    function apply_test_filter($hook, $value) {
        $args = func_get_args();
        array_shift($args);
        if (empty(state()['filters'][$hook])) { return $value; }
        usort(state()['filters'][$hook], function ($a, $b) { return $a[1] <=> $b[1]; });
        foreach (state()['filters'][$hook] as $entry) {
            $call_args = array_slice($args, 0, $entry[2]);
            $value = call_user_func_array($entry[0], $call_args);
            $args[0] = $value;
        }
        return $value;
    }

    function get_option($name, $default = false) {
        return array_key_exists($name, state()['options']) ? state()['options'][$name] : $default;
    }
    function add_option($name, $value = '', $deprecated = '', $autoload = 'yes') {
        if (array_key_exists($name, state()['options'])) { return false; }
        state()['options'][$name] = $value;
        return true;
    }
    function update_option($name, $value, $autoload = null) {
        state()['options'][$name] = $value;
        return true;
    }
    function delete_option($name) {
        unset(state()['options'][$name]);
        return true;
    }
    function wp_salt($scheme = 'auth') { return 'unit-test-wordpress-salt'; }
    function wp_parse_url($url, $component = -1) { return \parse_url((string) $url, $component); }

    function wp_remote_get($url, $args = array()) {
        state()['remote_get_calls']++;
        if (is_callable(state()['remote_mutator'])) {
            call_user_func(state()['remote_mutator']);
            state()['remote_mutator'] = null;
        }
        return array(
            'response' => array('code' => (int) state()['remote_response']['code']),
            'body' => (string) state()['remote_response']['body'],
            'request_url' => $url,
            'request_args' => $args,
        );
    }
    function is_wp_error($value) { return $value instanceof FakeWpError; }
    function wp_remote_retrieve_response_code($response) { return isset($response['response']['code']) ? (int) $response['response']['code'] : 0; }
    function wp_remote_retrieve_body($response) { return isset($response['body']) ? (string) $response['body'] : ''; }

    function wc_get_price_decimals() { return 3; }
    function wc_format_decimal($value, $decimals = false) {
        if (!is_string($value) && !is_int($value) && !is_float($value)) { return ''; }
        $decimals = ($decimals === false) ? 3 : (int) $decimals;
        return number_format((float) $value, $decimals, '.', '');
    }
    function wc_get_order($id) {
        $id = (int) $id;
        return isset(state()['orders'][$id]) ? state()['orders'][$id] : false;
    }

    function wp_next_scheduled($hook, $args = array()) {
        $key = $hook . '|' . json_encode(array_values($args));
        return isset(state()['scheduled'][$key]) ? state()['scheduled'][$key] : false;
    }
    function wp_schedule_single_event($timestamp, $hook, $args = array()) {
        $key = $hook . '|' . json_encode(array_values($args));
        if (isset(state()['scheduled'][$key])) { return false; }
        state()['scheduled'][$key] = (int) $timestamp;
        return true;
    }
    function wp_unschedule_event($timestamp, $hook, $args = array()) {
        $key = $hook . '|' . json_encode(array_values($args));
        unset(state()['scheduled'][$key]);
        return true;
    }
    function clear_scheduled_for_order($order_id) {
        $key = 'simplixpay_upayments_reconcile_order|' . json_encode(array((int) $order_id));
        unset(state()['scheduled'][$key]);
    }

    function __($text, $domain = 'default') { return $text; }
    function wc_get_logger() { return new FakeLogger(); }

    class FakeWpError {}
    class FakeLogger {
        public function info($message, $context = array()) { state()['logs'][] = array('info', $message); }
        public function warning($message, $context = array()) { state()['logs'][] = array('warning', $message); }
    }

    class FakeWpdb {
        public $options = 'wp_options';
        public function prepare($query) {
            $args = func_get_args();
            array_shift($args);
            return array('query' => $query, 'args' => $args);
        }
        public function query($prepared) {
            if (is_callable(state()['db_query_mutator'])) {
                call_user_func(state()['db_query_mutator']);
                state()['db_query_mutator'] = null;
            }
            if (!is_array($prepared) || !isset($prepared['query'], $prepared['args'])) { return false; }
            $query = ltrim((string) $prepared['query']);
            $args = $prepared['args'];
            if (stripos($query, 'UPDATE ') === 0 && count($args) === 3) {
                list($replacement, $name, $expected) = $args;
                if (array_key_exists($name, state()['options']) && state()['options'][$name] === $expected) {
                    state()['options'][$name] = $replacement;
                    return 1;
                }
                return 0;
            }
            if (stripos($query, 'DELETE FROM ') === 0 && count($args) === 2) {
                list($name, $expected) = $args;
                if (array_key_exists($name, state()['options']) && state()['options'][$name] === $expected) {
                    unset(state()['options'][$name]);
                    return 1;
                }
                return 0;
            }
            return false;
        }
    }

    class FakeGateway {
        public $apiKey = 'test-api-key';
        public $test_mode = true;
        public $force_complete = false;
        public $host = 'sandboxapi.upayments.com';
        public $url_suffix = '';
        public function getMode() { return $this->test_mode; }
        public function getAPIUrl($route = '') { return 'https://' . $this->host . '/api/v1/' . $route . $this->url_suffix; }
        public function getCurrencyCode($currency) { return strtoupper((string) $currency); }
        public function getIsOrderComplete() { return $this->force_complete; }
    }

    class FakePaymentGateways {
        public function payment_gateways() {
            return is_object(state()['gateway']) ? array('upayments' => state()['gateway']) : array();
        }
    }
    class FakeWooRuntime {
        public $cart = null;
        public function payment_gateways() { return new FakePaymentGateways(); }
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
            if ($transaction_id !== '') { $this->transaction_id = (string) $transaction_id; }
            $this->status = (string) apply_test_filter('woocommerce_payment_complete_order_status', 'processing', $this->id, $this);
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
    function WC() { return new \Simplixi\SUCheckout\UPayments\Payment\FakeWooRuntime(); }
    function wp_cache_delete($key, $group = '') { return true; }
    function wc_get_price_decimals() { return 3; }

    require_once __DIR__ . '/../../src/Payment/ProviderResult.php';
    require_once __DIR__ . '/../../src/Payment/StatusRateGate.php';
    require_once __DIR__ . '/../../src/Payment/OrderLock.php';
    require_once __DIR__ . '/../../src/Payment/StatusVerifier.php';
    require_once __DIR__ . '/../../src/Payment/PaymentLifecycle.php';

    use Simplixi\SUCheckout\UPayments\Payment\FakeGateway;
    use Simplixi\SUCheckout\UPayments\Payment\FakeOrder;
    use Simplixi\SUCheckout\UPayments\Payment\OrderLock;
    use Simplixi\SUCheckout\UPayments\Payment\PaymentLifecycle;
    use Simplixi\SUCheckout\UPayments\Payment\ProviderResult;
    use Simplixi\SUCheckout\UPayments\Payment\StatusRateGate;
    use Simplixi\SUCheckout\UPayments\Payment\StatusVerifier;

    $pass = 0;
    $fail = 0;

    function ok($condition, $description) {
        global $pass, $fail;
        if ($condition) { $pass++; echo "PASS: $description\n"; }
        else { $fail++; echo "FAIL: $description\n"; }
    }
    function same($actual, $expected, $description) {
        ok($actual === $expected, $description . ' expected=' . var_export($expected, true) . ' got=' . var_export($actual, true));
    }
    function reset_fixture($id = 501) {
        \Simplixi\SUCheckout\UPayments\Payment\reset_state();
        $order = new FakeOrder($id, 'merchant-order-' . $id);
        $gateway = new FakeGateway();
        \Simplixi\SUCheckout\UPayments\Payment\state()['orders'][$id] = $order;
        \Simplixi\SUCheckout\UPayments\Payment\state()['gateway'] = $gateway;
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
        if ($payment_id !== null) { $tx['payment_id'] = $payment_id; }
        return $tx;
    }
    function set_provider_transaction(array $tx, $code = 201) {
        \Simplixi\SUCheckout\UPayments\Payment\state()['remote_response'] = array(
            'code' => $code,
            'body' => json_encode(array('status' => true, 'data' => array('transaction' => $tx))),
        );
    }
    function private_call($class, $method, array $args = array()) {
        $r = new \ReflectionMethod($class, $method);
        $r->setAccessible(true);
        return $r->invokeArgs(null, $args);
    }

    // Exact provider result table, including fail-closed future values.
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
        'Processing' => ProviderResult::INDETERMINATE,
        'captured' => ProviderResult::INDETERMINATE,
        'FUTURE_STATUS' => ProviderResult::INDETERMINATE,
    );
    foreach ($class_cases as $input => $expected) { same(ProviderResult::classify($input), $expected, 'classifier ' . $input); }
    same(ProviderResult::classify(null), ProviderResult::INDETERMINATE, 'classifier NULL fail closed');
    same(ProviderResult::classify(''), ProviderResult::INDETERMINATE, 'classifier empty fail closed');

    // Conflict-safe GET/POST merge.
    same(PaymentLifecycle::merge_request_value(array('x' => '1'), array(), 'x')['value'], '1', 'GET-only request value');
    same(PaymentLifecycle::merge_request_value(array(), array('x' => '2'), 'x')['value'], '2', 'POST-only request value');
    ok(PaymentLifecycle::merge_request_value(array('x' => '1'), array('x' => '1'), 'x')['valid'], 'identical GET/POST accepted');
    ok(!PaymentLifecycle::merge_request_value(array('x' => '1'), array('x' => '2'), 'x')['valid'], 'conflicting GET/POST rejected');
    ok(!PaymentLifecycle::merge_request_value(array('x' => array('1')), array(), 'x')['valid'], 'array GET rejected');
    ok(!PaymentLifecycle::merge_request_value(array(), array('x' => array('1')), 'x')['valid'], 'array POST rejected');
    ok(!PaymentLifecycle::merge_request_value(array(), array(), 'x')['present'], 'missing request field remains missing');

    // Binding contract and null/processing semantics.
    list($gateway, $order) = reset_fixture(510);
    $base_tx = transaction_for($order);
    $bound = StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $base_tx);
    ok($bound['bound'], 'captured transaction binds');
    same($bound['classification'], ProviderResult::CAPTURED, 'captured classification after bind');
    $variants = array(
        'track_id' => array('different', 'binding_track_id'),
        'merchant_requested_order_id' => array('different', 'binding_merchant_requested_order_id'),
        'reference' => array('999', 'binding_reference'),
        'currency_type' => array('USD', 'binding_currency'),
        'total_price' => array('9.000', 'binding_amount'),
    );
    foreach ($variants as $field => $case) {
        $tx = $base_tx; $tx[$field] = $case[0];
        $r = StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $tx);
        ok(!$r['bound'], 'binding mismatch rejects ' . $field);
        same($r['reason'], $case[1], 'binding mismatch reason ' . $field);
    }
    $tx = $base_tx; unset($tx['payment_id']);
    same(StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $tx)['reason'], 'captured_payment_id_missing', 'CAPTURED requires payment id');
    $pending = transaction_for($order, 'PENDING', 'track-abc', null);
    $r = StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $pending);
    ok($r['bound'], 'PENDING binds without payment id');
    same($r['classification'], ProviderResult::PENDING, 'PENDING remains pending');
    $null_result = transaction_for($order, null, 'track-abc', null);
    $r = StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $null_result);
    ok($r['bound'], 'documented NULL result still binds identity');
    same($r['classification'], ProviderResult::INDETERMINATE, 'documented NULL result is indeterminate');
    $processing = transaction_for($order, 'Processing', 'track-abc', null);
    $r = StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $processing);
    ok($r['bound'], 'Processing result binds identity');
    same($r['classification'], ProviderResult::INDETERMINATE, 'Processing remains indeterminate');
    $bad_result = $base_tx; $bad_result['result'] = array('CAPTURED');
    same(StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $bad_result)['reason'], 'result_not_string_or_null', 'non-scalar result rejected');
    $missing_result = $base_tx; unset($missing_result['result']);
    same(StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $missing_result)['reason'], 'missing_field_result', 'missing result rejected');
    $exp = $base_tx; $exp['total_price'] = '1e1';
    same(StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $exp)['reason'], 'amount_invalid', 'exponent amount rejected');

    // Exact provider host/path validation and 30/min gate.
    list($gateway, $order) = reset_fixture(520);
    $gateway->host = 'attacker.example'; set_provider_transaction(transaction_for($order));
    $r = StatusVerifier::verify($gateway, $order, 'track-abc');
    same($r['reason'], 'status_url_invalid', 'non-UPayments host rejected');
    same(\Simplixi\SUCheckout\UPayments\Payment\state()['remote_get_calls'], 0, 'invalid host makes zero HTTP calls');
    same(count(\Simplixi\SUCheckout\UPayments\Payment\state()['options']), 0, 'invalid host consumes zero rate slots');
    list($gateway, $order) = reset_fixture(521);
    $gateway->url_suffix = '?leak=1'; set_provider_transaction(transaction_for($order));
    same(StatusVerifier::verify($gateway, $order, 'track-abc')['reason'], 'status_url_invalid', 'query-bearing status URL rejected');
    list($gateway, $order) = reset_fixture(522);
    $acquired = 0; for ($i = 0; $i < 31; $i++) { if (StatusRateGate::acquire($gateway)) { $acquired++; } }
    same($acquired, 30, 'status rate gate allows exactly 30 slots');
    same(StatusRateGate::limit_per_minute(), 30, 'status rate contract is 30/min');

    // CAPTURED canonical Woo completion + replay barrier.
    list($gateway, $order) = reset_fixture(530);
    set_provider_transaction(transaction_for($order));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['state'], 'captured', 'CAPTURED outcome');
    same($order->payment_complete_calls, 1, 'CAPTURED calls payment_complete once');
    same($order->update_status_calls, 0, 'CAPTURED does not direct-update paid status');
    same($order->get_transaction_id(), 'pay-123', 'Woo transaction ID is provider payment ID');
    same($order->get_status(), 'processing', 'Woo default paid status retained');
    same((string) $order->get_meta('_upay_verified_capture'), '1', 'verified capture flag set');
    same($order->get_meta('UPayments_PaymentID'), 'pay-123', 'legacy payment ID retained');
    same($order->get_meta('UPayments_TrackID'), 'track-abc', 'legacy track retained');
    same($order->get_meta('_simplixpay_upayments_status_track_v1'), 'track-abc', 'trusted cursor retained');
    $calls = \Simplixi\SUCheckout\UPayments\Payment\state()['remote_get_calls'];
    $out2 = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out2['state'], 'captured', 'duplicate CAPTURED sees verified state');
    same($order->payment_complete_calls, 1, 'duplicate does not re-fire payment_complete');
    same(\Simplixi\SUCheckout\UPayments\Payment\state()['remote_get_calls'], $calls, 'duplicate makes zero provider calls');

    // Merchant force-complete still uses Woo filter.
    list($gateway, $order) = reset_fixture(531); $gateway->force_complete = true;
    set_provider_transaction(transaction_for($order));
    PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'browser');
    same($order->get_status(), 'completed', 'force-complete uses Woo completion filter');
    same($order->update_status_calls, 0, 'force-complete avoids direct paid update_status');

    // Existing paid state and transaction conflict.
    list($gateway, $order) = reset_fixture(532); $order->status = 'processing';
    set_provider_transaction(transaction_for($order));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['state'], 'captured', 'existing paid order recognizes authenticated capture');
    same($order->payment_complete_calls, 0, 'existing paid order does not re-fire completion');
    same($order->get_transaction_id(), 'pay-123', 'existing paid order receives transaction ID');
    same((string) $order->get_meta('_upay_verified_capture'), '1', 'existing paid order gains verified barrier');
    list($gateway, $order) = reset_fixture(533); $order->status = 'processing'; $order->transaction_id = 'other-payment';
    set_provider_transaction(transaction_for($order));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['state'], 'unchanged', 'transaction conflict fails closed');
    same((string) $order->get_meta('_upay_verified_capture'), '', 'transaction conflict never sets verified barrier');

    // Terminal and unresolved states.
    list($gateway, $order) = reset_fixture(540); set_provider_transaction(transaction_for($order, 'FAILED'));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['state'], 'failed', 'authenticated FAILED becomes Woo failed');
    same($order->get_status(), 'failed', 'Woo failed status');
    same($order->payment_complete_calls, 0, 'FAILED never completes payment');
    list($gateway, $order) = reset_fixture(541); set_provider_transaction(transaction_for($order, 'CANCELED'));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['state'], 'cancelled', 'authenticated CANCELED becomes Woo cancelled');
    same($order->get_status(), 'cancelled', 'Woo cancelled status');
    list($gateway, $order) = reset_fixture(542); set_provider_transaction(transaction_for($order, 'PENDING', 'track-pending', null));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-pending', 'webhook');
    same($out['state'], 'pending', 'PENDING remains unresolved');
    same($order->get_status(), 'pending', 'PENDING stays unpaid');
    same((int) $order->get_meta('_simplixpay_upayments_reconcile_attempt_v1'), 1, 'PENDING schedules first reconciliation');
    ok(\Simplixi\SUCheckout\UPayments\Payment\wp_next_scheduled('simplixpay_upayments_reconcile_order', array(542)) !== false, 'PENDING event scheduled');
    \Simplixi\SUCheckout\UPayments\Payment\clear_scheduled_for_order(542);
    set_provider_transaction(transaction_for($order, 'CAPTURED', 'track-pending', 'pay-final'));
    PaymentLifecycle::reconcile_order(542);
    same($order->get_status(), 'processing', 'reconciliation CAPTURED reaches paid state');
    same($order->get_transaction_id(), 'pay-final', 'reconciliation stores final payment ID');
    same((string) $order->get_meta('_upay_verified_capture'), '1', 'reconciliation sets verified capture');
    ok(\Simplixi\SUCheckout\UPayments\Payment\wp_next_scheduled('simplixpay_upayments_reconcile_order', array(542)) === false, 'terminal capture clears reconciliation');

    // NULL result is persisted as non-terminal evidence and reconciled.
    list($gateway, $order) = reset_fixture(543); set_provider_transaction(transaction_for($order, null, 'track-null', null));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-null', 'webhook');
    same($out['state'], 'pending', 'NULL result remains unresolved');
    same($order->get_status(), 'pending', 'NULL result stays unpaid');
    same($order->get_meta('_simplixpay_upayments_provider_result_v1'), 'NULL', 'NULL evidence is explicit');
    same((int) $order->get_meta('_simplixpay_upayments_reconcile_attempt_v1'), 1, 'NULL result schedules reconciliation');

    // Paid/refunded orders cannot be downgraded/resurrected.
    list($gateway, $order) = reset_fixture(544); $order->status = 'processing'; $order->transaction_id = 'pay-existing';
    set_provider_transaction(transaction_for($order, 'FAILED', 'track-abc', 'pay-existing'));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['state'], 'unchanged', 'terminal result cannot downgrade paid order');
    same($order->get_status(), 'processing', 'paid state preserved');
    list($gateway, $order) = reset_fixture(545); $order->status = 'refunded';
    set_provider_transaction(transaction_for($order));
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['reason'], 'refunded', 'refunded preflight result');
    same(\Simplixi\SUCheckout\UPayments\Payment\state()['remote_get_calls'], 0, 'refunded order makes zero provider calls');

    // Initial transient status failure survives via separate unverified cursor.
    list($gateway, $order) = reset_fixture(546);
    ok(private_call(PaymentLifecycle::class, 'remember_unverified_cursor', array($order, 'track-transient', $order->get_meta('UPayments_order_id'))), 'locally preflighted callback cursor can be remembered');
    \Simplixi\SUCheckout\UPayments\Payment\state()['remote_response'] = array('code' => 500, 'body' => '');
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-transient', 'webhook');
    same($out['reason'], 'unexpected_http_500', 'initial transient status failure remains unpaid');
    same($order->get_meta('_simplixpay_upayments_unverified_track_v1'), 'track-transient', 'unverified cursor retained for retry');
    same($order->get_meta('_simplixpay_upayments_unverified_requested_v1'), $order->get_meta('UPayments_order_id'), 'unverified cursor is paired to current provider order identity');
    same((int) $order->get_meta('_simplixpay_upayments_reconcile_attempt_v1'), 1, 'transient failure schedules reconciliation');
    ok(\Simplixi\SUCheckout\UPayments\Payment\wp_next_scheduled('simplixpay_upayments_reconcile_order', array(546)) !== false, 'transient failure has scheduled retry');
    \Simplixi\SUCheckout\UPayments\Payment\clear_scheduled_for_order(546);
    set_provider_transaction(transaction_for($order, 'CAPTURED', 'track-transient', 'pay-recovered'));
    PaymentLifecycle::reconcile_order(546);
    same($order->get_status(), 'processing', 'unverified cursor reconciliation can recover capture');
    same($order->get_transaction_id(), 'pay-recovered', 'recovered capture stores payment ID');
    same($order->get_meta('_simplixpay_upayments_unverified_track_v1'), '', 'unverified cursor erased after authenticated bind');
    same($order->get_meta('_simplixpay_upayments_status_track_v1'), 'track-transient', 'cursor promoted to trusted after bind');
    same($order->get_meta('_simplixpay_upayments_status_requested_v1'), $order->get_meta('UPayments_order_id'), 'trusted cursor is paired to provider order identity');

    // Authenticated binding mismatch discards untrusted retry cursor.
    list($gateway, $order) = reset_fixture(547);
    private_call(PaymentLifecycle::class, 'remember_unverified_cursor', array($order, 'track-bad', $order->get_meta('UPayments_order_id')));
    $bad = transaction_for($order, 'PENDING', 'track-bad', null); $bad['reference'] = '999';
    set_provider_transaction($bad);
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-bad', 'webhook');
    same($out['reason'], 'binding_reference', 'authenticated binding mismatch rejected');
    same($order->get_meta('_simplixpay_upayments_unverified_track_v1'), '', 'binding mismatch clears unverified cursor');
    ok(\Simplixi\SUCheckout\UPayments\Payment\wp_next_scheduled('simplixpay_upayments_reconcile_order', array(547)) === false, 'binding mismatch leaves no retry event');

    // A new Charge attempt on the same Woo order rotates provider order identity.
    // Stale unpaid cursor state must not pin the new attempt to the old track.
    list($gateway, $order) = reset_fixture(548);
    set_provider_transaction(transaction_for($order, 'PENDING', 'track-old', null));
    PaymentLifecycle::process_order_status($gateway, $order, 'track-old', 'webhook');
    same($order->get_meta('_simplixpay_upayments_status_track_v1'), 'track-old', 'old attempt establishes trusted cursor');
    same($order->get_meta('_simplixpay_upayments_status_requested_v1'), 'merchant-order-548', 'old attempt trusted requested identity stored');
    $order->update_meta_data('UPayments_order_id', 'merchant-order-548-new');
    $order->save();
    ok(private_call(PaymentLifecycle::class, 'remember_unverified_cursor', array($order, 'track-new', 'merchant-order-548-new')), 'new Charge identity can rotate stale unpaid cursor state');
    same($order->get_meta('_simplixpay_upayments_status_track_v1'), '', 'old trusted track cleared for new Charge attempt');
    same($order->get_meta('_simplixpay_upayments_unverified_track_v1'), 'track-new', 'new attempt owns unverified cursor');
    $new_tx = transaction_for($order, 'CAPTURED', 'track-new', 'pay-new');
    set_provider_transaction($new_tx);
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-new', 'webhook');
    same($out['state'], 'captured', 'new same-order Charge attempt can capture');
    same($order->get_transaction_id(), 'pay-new', 'new attempt payment ID becomes canonical Woo transaction ID');

    // TOCTOU: provider binds original snapshot, fresh order changes under lock.
    list($gateway, $order) = reset_fixture(550); set_provider_transaction(transaction_for($order));
    \Simplixi\SUCheckout\UPayments\Payment\state()['remote_mutator'] = function () use ($order) {
        $fresh = clone $order; $fresh->total = '11.000';
        \Simplixi\SUCheckout\UPayments\Payment\state()['orders'][$order->get_id()] = $fresh;
    };
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['reason'], 'binding_changed_under_lock', 'fresh-order rebind catches TOCTOU total change');
    same($order->payment_complete_calls, 0, 'TOCTOU change never completes original order');

    // Atomic lock contention and stale-lock CAS recovery.
    list($gateway, $order) = reset_fixture(551); set_provider_transaction(transaction_for($order));
    $lock_record = private_call(OrderLock::class, 'encode_record', array(str_repeat('a', 32), time() + 30));
    \Simplixi\SUCheckout\UPayments\Payment\state()['options']['simplixpay_upay_order_lock_v1_551'] = $lock_record;
    $out = PaymentLifecycle::process_order_status($gateway, $order, 'track-abc', 'webhook');
    same($out['reason'], 'order_lock_contention', 'live order lock contention fails closed');
    same($order->payment_complete_calls, 0, 'lock contention prevents completion');

    \Simplixi\SUCheckout\UPayments\Payment\reset_state();
    $stale_name = 'simplixpay_upay_order_lock_v1_570';
    $stale_record = private_call(OrderLock::class, 'encode_record', array(str_repeat('b', 32), time() - 5));
    \Simplixi\SUCheckout\UPayments\Payment\state()['options'][$stale_name] = $stale_record;
    $token = OrderLock::acquire(570);
    ok(is_string($token) && $token !== '', 'stale lock is recovered atomically');
    OrderLock::release(570, $token);
    ok(!array_key_exists($stale_name, \Simplixi\SUCheckout\UPayments\Payment\state()['options']), 'owner releases exact recovered lock');

    \Simplixi\SUCheckout\UPayments\Payment\reset_state();
    $race_name = 'simplixpay_upay_order_lock_v1_571';
    $old = private_call(OrderLock::class, 'encode_record', array(str_repeat('c', 32), time() - 5));
    $new = private_call(OrderLock::class, 'encode_record', array(str_repeat('d', 32), time() + 30));
    \Simplixi\SUCheckout\UPayments\Payment\state()['options'][$race_name] = $old;
    \Simplixi\SUCheckout\UPayments\Payment\state()['db_query_mutator'] = function () use ($race_name, $new) {
        \Simplixi\SUCheckout\UPayments\Payment\state()['options'][$race_name] = $new;
    };
    same(OrderLock::acquire(571), null, 'stale recovery loses CAS when newer owner wins');
    same(\Simplixi\SUCheckout\UPayments\Payment\state()['options'][$race_name], $new, 'stale recovery never deletes newer owner lock');

    // Bounded retry/exhaustion: initial schedule + four cron opportunities max.
    list($gateway, $order) = reset_fixture(560); set_provider_transaction(transaction_for($order, 'PENDING', 'track-retry', null));
    PaymentLifecycle::process_order_status($gateway, $order, 'track-retry', 'webhook');
    for ($attempt = 1; $attempt <= 4; $attempt++) {
        \Simplixi\SUCheckout\UPayments\Payment\clear_scheduled_for_order(560);
        PaymentLifecycle::reconcile_order(560);
    }
    same((int) $order->get_meta('_simplixpay_upayments_reconcile_attempt_v1'), 4, 'reconciliation attempts capped at four');
    same((string) $order->get_meta('_simplixpay_upayments_reconcile_exhausted_v1'), '1', 'reconciliation exhaustion is durable');
    ok(count($order->notes) === 1, 'reconciliation exhaustion note emitted once');
    ok(\Simplixi\SUCheckout\UPayments\Payment\wp_next_scheduled('simplixpay_upayments_reconcile_order', array(560)) === false, 'no event remains after exhaustion');

    // Static architecture/safety guards.
    $root = dirname(__DIR__, 2);
    $identity_source = @file_get_contents($root . '/src/Release/Identity.php');
    $lifecycle_source = file_get_contents($root . '/src/Payment/PaymentLifecycle.php');
    $verifier_source = file_get_contents($root . '/src/Payment/StatusVerifier.php');
    $rate_source = file_get_contents($root . '/src/Payment/StatusRateGate.php');
    $lock_source = file_get_contents($root . '/src/Payment/OrderLock.php');
    $gateway_source = @file_get_contents($root . '/UPayments.php');

    // In local isolated execution the repository-only files may be absent; CI has them.
    if (is_string($identity_source)) { ok(strpos($identity_source, 'PaymentLifecycle.php') !== false, 'release foothold loads payment lifecycle'); }
    ok(strpos($lifecycle_source, "add_action(self::CALLBACK_HOOK, array(__CLASS__, 'handle_callback'), 5)") !== false, 'new callback runs before inherited priority 10');
    ok(strpos($lifecycle_source, '$_REQUEST') === false, 'new lifecycle never uses $_REQUEST');
    ok(strpos($lifecycle_source, 'payment_complete($payment_id)') !== false, 'captured path uses Woo payment_complete');
    ok(strpos($lifecycle_source, "execute_upayments_request('charge'") === false && strpos($lifecycle_source, "getAPIUrl('charge") === false, 'reconciliation never dispatches Charge');
    ok(strpos($lifecycle_source, 'UNVERIFIED_TRACK_META') !== false, 'separate unverified callback cursor exists');
    ok(strpos($lifecycle_source, 'TRUSTED_REQUESTED_META') !== false && strpos($lifecycle_source, 'UNVERIFIED_REQUESTED_META') !== false, 'cursor state is scoped to provider Charge attempt identity');
    ok(strpos($verifier_source, "'redirection' => 0") !== false, 'status lookup disables redirects');
    ok(strpos($verifier_source, "'sslverify'   => true") !== false, 'status lookup enforces TLS');
    ok(strpos($verifier_source, "'timeout'     => 15") !== false, 'status lookup has finite timeout');
    ok(strpos($verifier_source, "result_not_string_or_null") !== false, 'NULL result contract is explicit');
    ok(strpos($rate_source, 'LIMIT_PER_MINUTE = 30') !== false, 'status query ceiling is 30/min');
    ok(strpos($lock_source, 'replace_if_current') !== false && strpos($lock_source, 'delete_if_current') !== false, 'order lock uses conditional stale recovery/release');
    ok(strpos($lock_source, 'delete_option($name)') === false, 'order lock never blindly deletes contested lock');
    if (is_string($gateway_source)) {
        ok(strpos($gateway_source, 'woocommerce_api_') !== false, 'historical wc_upayments route remains');
        ok(strpos($gateway_source, 'function process_refund') === false, 'automatic gateway refund remains unsupported');
        ok(strpos($gateway_source, "'refunds'") === false && strpos($gateway_source, '"refunds"') === false, 'gateway does not advertise refunds');
    }

    echo "\n--- Provider Payment Lifecycle Report ---\n";
    echo "PASS: $pass\n";
    echo "FAIL: $fail\n";
    exit($fail === 0 ? 0 : 1);
}
