<?php
/**
 * Approach 3 T3 characterization of direct historical callback methods.
 *
 * Runtime-neutral: this harness executes the real WC_Upayments legacy verifier,
 * browser-return and webhook methods against deterministic WordPress/WooCommerce
 * doubles. It intentionally records existing behavior; it does not prescribe the
 * next implementation.
 */

define('ABSPATH', __DIR__ . '/');

$GLOBALS['t3_actions'] = array();
$GLOBALS['t3_filters'] = array();
$GLOBALS['t3_order'] = null;
$GLOBALS['t3_transport'] = array();
$GLOBALS['t3_transport_calls'] = array();
$GLOBALS['t3_logs'] = array();
$GLOBALS['t3_cart_clears'] = 0;

function plugin_dir_url($file) { return 'https://merchant.example.test/wp-content/plugins/supcheckout/'; }
function plugin_dir_path($file) { return dirname((string) $file) . DIRECTORY_SEPARATOR; }
function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
    $GLOBALS['t3_actions'][] = array($hook, $callback, (int) $priority, (int) $accepted_args);
    return true;
}
function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) {
    $GLOBALS['t3_filters'][] = array($hook, $callback, (int) $priority, (int) $accepted_args);
    return true;
}
function register_activation_hook($file, $callback) { return true; }
function sanitize_text_field($value) { return is_scalar($value) ? trim((string) $value) : ''; }
function wp_unslash($value) { return $value; }
function absint($value) { return max(0, (int) $value); }
function is_user_logged_in() { return false; }
function home_url($path = '/') { return 'https://merchant.example.test' . ($path === '' ? '/' : (string) $path); }
function wc_get_page_permalink($page) { return 'https://merchant.example.test/' . rawurlencode((string) $page) . '/'; }
function add_query_arg($key, $value, $url) {
    return (string) $url . (strpos((string) $url, '?') === false ? '?' : '&')
        . rawurlencode((string) $key) . '=' . rawurlencode((string) $value);
}
function wp_safe_redirect($url) { echo 'REDIRECT:' . (string) $url . "\n"; return true; }
function __($text, $domain = null) { return (string) $text; }
function wc_get_price_decimals() { return 3; }
function wc_format_decimal($number, $dp = false) {
    $dp = $dp === false ? 3 : (int) $dp;
    if (!is_numeric($number)) { return ''; }
    return number_format((float) $number, $dp, '.', '');
}
function wc_get_order($id) {
    $order = $GLOBALS['t3_order'];
    return is_object($order) && (int) $order->get_id() === (int) $id ? $order : false;
}
function WC() {
    static $wc = null;
    if ($wc === null) {
        $wc = new stdClass();
        $wc->cart = new T3Cart();
    }
    return $wc;
}
function status_header($code) { echo 'STATUS:' . (int) $code . "\n"; }

class WooCommerce {}
class WC_Order {
    public $id = 42;
    public $currency = 'KWD';
    public $total = '10.000';
    public $payment_method = 'upayments';
    public $status = 'pending';
    public $meta = array('UPayments_order_id' => 'merchant-42');
    public $updates = array();
    public $status_updates = array();
    public $save_count = 0;
    public $update_status_result = true;

    public function get_id() { return $this->id; }
    public function get_currency() { return $this->currency; }
    public function get_total() { return $this->total; }
    public function get_payment_method() { return $this->payment_method; }
    public function get_status() { return $this->status; }
    public function get_meta($key, $single = true) { return array_key_exists($key, $this->meta) ? $this->meta[$key] : ''; }
    public function has_status($status) { return $this->status === (string) $status; }
    public function update_meta_data($key, $value) {
        $this->meta[$key] = $value;
        $this->updates[$key] = $value;
        echo 'META:' . $key . '=' . (is_scalar($value) ? (string) $value : '[complex]') . "\n";
    }
    public function update_status($status, $note = '') {
        $this->status_updates[] = array((string) $status, (string) $note);
        echo 'ORDER_STATUS_ATTEMPT:' . (string) $status . "\n";
        if ($this->update_status_result) {
            $this->status = (string) $status;
            echo 'ORDER_STATUS_APPLIED:' . (string) $status . "\n";
            return true;
        }
        echo "ORDER_STATUS_REJECTED\n";
        return false;
    }
    public function save() { $this->save_count++; echo "ORDER_SAVED\n"; return $this->id; }
}
class WC_Payment_Gateway {
    public function get_return_url($order = null) {
        return 'https://merchant.example.test/order-received/42/?key=wc_order_key_test';
    }
}
class T3Cart {
    public function empty_cart() { $GLOBALS['t3_cart_clears']++; echo "CART_CLEARED\n"; }
}

require_once dirname(__DIR__, 2) . '/UPayments.php';
woocommerceUpaymentsInit();

class T3LegacyGatewayProbe extends WC_Upayments {
    protected function execute_upayments_request($route, $method, $body = null) {
        $GLOBALS['t3_transport_calls'][] = array('route' => $route, 'method' => $method, 'body' => $body);
        echo 'TRANSPORT:' . $method . ':' . $route . "\n";
        if (isset($GLOBALS['t3_transport']['throw']) && $GLOBALS['t3_transport']['throw']) {
            throw new RuntimeException('synthetic provider exception');
        }
        return $GLOBALS['t3_transport'];
    }
    public function log($content, $level = 'debug') {
        $GLOBALS['t3_logs'][] = array((string) $level, (string) $content);
        echo 'LOG:' . (string) $level . ':' . (string) $content . "\n";
    }
    public function getIsOrderComplete() { return false; }
}

function t3_gateway() {
    $r = new ReflectionClass(T3LegacyGatewayProbe::class);
    $gateway = $r->newInstanceWithoutConstructor();
    $gateway->id = 'upayments';
    $gateway->apiKey = 'public-test-key';
    $gateway->debug = 'no';
    $gateway->testMode = 'yes';
    $gateway->isOrderComplete = 'no';
    return $gateway;
}
function t3_order() { return new WC_Order(); }
function t3_transaction($overrides = array()) {
    return array_merge(array(
        'result' => 'CAPTURED',
        'track_id' => 'track-abc',
        'merchant_requested_order_id' => 'merchant-42',
        'total_price' => '10.000',
        'currency_type' => 'KWD',
        'payment_id' => 'payment-xyz',
        'payment_type' => 'cc',
        'reference' => '42',
    ), $overrides);
}
function t3_transport_for($transaction, $http = 201, $status = true) {
    return array(
        'transport_ok' => $http >= 200 && $http < 300,
        'body' => json_encode(array('status' => $status, 'data' => array('transaction' => $transaction))),
        'http_status' => (int) $http,
        'curl_errno' => 0,
    );
}
function t3_reset() {
    $GLOBALS['t3_order'] = null;
    $GLOBALS['t3_transport'] = array('transport_ok' => false, 'body' => null, 'http_status' => 0, 'curl_errno' => 1);
    $GLOBALS['t3_transport_calls'] = array();
    $GLOBALS['t3_logs'] = array();
    $GLOBALS['t3_cart_clears'] = 0;
    $_GET = array();
    $_POST = array();
    $_REQUEST = array();
    $_SERVER = array('REQUEST_METHOD' => 'GET');
}
function t3_verify($gateway, $order, $track) {
    $m = new ReflectionMethod(WC_Upayments::class, 'verify_payment_status');
    $m->setAccessible(true);
    return $m->invoke($gateway, $order, $track);
}
function t3_assert($condition, $label) {
    global $t3_pass, $t3_fail;
    if ($condition) { echo 'PASS: ' . $label . "\n"; $t3_pass++; return; }
    echo 'FAIL: ' . $label . "\n"; $t3_fail++;
}
function t3_contains($haystack, $needle) { return strpos((string) $haystack, (string) $needle) !== false; }
function t3_child($scenario) {
    t3_reset();
    $gateway = t3_gateway();
    $order = t3_order();
    $GLOBALS['t3_order'] = $order;

    if ($scenario === 'browser-preflight-missing') {
        $_GET = array();
        $gateway->return_from_upayments();
    }
    if ($scenario === 'browser-not-captured') {
        $_GET = array('wc_order_id' => '42', 'track_id' => 'track-abc', 'requested_order_id' => 'merchant-42');
        $_REQUEST = $_GET;
        $GLOBALS['t3_transport'] = t3_transport_for(t3_transaction(array('result' => 'DECLINED')));
        $gateway->return_from_upayments();
    }
    if ($scenario === 'browser-captured') {
        $_GET = array('wc_order_id' => '42', 'track_id' => 'track-abc', 'requested_order_id' => 'merchant-42');
        $_REQUEST = $_GET;
        $GLOBALS['t3_transport'] = t3_transport_for(t3_transaction());
        $gateway->return_from_upayments();
    }
    if ($scenario === 'browser-status-rejected') {
        $order->update_status_result = false;
        $_GET = array('wc_order_id' => '42', 'track_id' => 'track-abc', 'requested_order_id' => 'merchant-42');
        $_REQUEST = $_GET;
        $GLOBALS['t3_transport'] = t3_transport_for(t3_transaction());
        $gateway->return_from_upayments();
    }
    if ($scenario === 'browser-replay') {
        $order->meta['_upay_verified_capture'] = '1';
        $_GET = array('wc_order_id' => '42', 'track_id' => 'track-abc', 'requested_order_id' => 'merchant-42');
        $_REQUEST = $_GET;
        $GLOBALS['t3_transport'] = t3_transport_for(t3_transaction());
        $gateway->return_from_upayments();
    }
    if ($scenario === 'browser-refunded') {
        $order->status = 'refunded';
        $_GET = array('wc_order_id' => '42', 'track_id' => 'track-abc', 'requested_order_id' => 'merchant-42');
        $_REQUEST = $_GET;
        $GLOBALS['t3_transport'] = t3_transport_for(t3_transaction());
        $gateway->return_from_upayments();
    }
    if ($scenario === 'webhook-preflight-missing') {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $gateway->web_hook_handler();
    }
    if ($scenario === 'webhook-binding-failure') {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_REQUEST = array('wc_order_id' => '42', 'track_id' => 'track-abc', 'requested_order_id' => 'merchant-42');
        $GLOBALS['t3_transport'] = t3_transport_for(t3_transaction(array('reference' => '99')));
        $gateway->web_hook_handler();
    }
    if ($scenario === 'webhook-captured') {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_REQUEST = array('wc_order_id' => '42', 'track_id' => 'track-abc', 'requested_order_id' => 'merchant-42');
        $GLOBALS['t3_transport'] = t3_transport_for(t3_transaction());
        $gateway->web_hook_handler();
    }
    if ($scenario === 'webhook-status-rejected') {
        $order->update_status_result = false;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_REQUEST = array('wc_order_id' => '42', 'track_id' => 'track-abc', 'requested_order_id' => 'merchant-42');
        $GLOBALS['t3_transport'] = t3_transport_for(t3_transaction());
        $gateway->web_hook_handler();
    }
    if ($scenario === 'webhook-replay') {
        $order->meta['_upay_verified_capture'] = '1';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_REQUEST = array('wc_order_id' => '42', 'track_id' => 'track-abc', 'requested_order_id' => 'merchant-42');
        $GLOBALS['t3_transport'] = t3_transport_for(t3_transaction());
        $gateway->web_hook_handler();
    }
    if ($scenario === 'webhook-refunded') {
        $order->status = 'refunded';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_REQUEST = array('wc_order_id' => '42', 'track_id' => 'track-abc', 'requested_order_id' => 'merchant-42');
        $GLOBALS['t3_transport'] = t3_transport_for(t3_transaction());
        $gateway->web_hook_handler();
    }

    echo "UNEXPECTED_RETURN\n";
    exit(90);
}
function t3_run_child($scenario) {
    $cmd = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__FILE__) . ' --child ' . escapeshellarg($scenario);
    $pipes = array();
    $process = proc_open($cmd, array(1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $pipes);
    if (!is_resource($process)) { return array('exit' => 127, 'stdout' => '', 'stderr' => 'proc_open failed'); }
    $stdout = stream_get_contents($pipes[1]); fclose($pipes[1]);
    $stderr = stream_get_contents($pipes[2]); fclose($pipes[2]);
    return array('exit' => proc_close($process), 'stdout' => (string) $stdout, 'stderr' => (string) $stderr);
}

if (isset($argv[1]) && $argv[1] === '--child') {
    t3_child(isset($argv[2]) ? (string) $argv[2] : '');
}

$t3_pass = 0;
$t3_fail = 0;

// Private legacy verifier characterization.
t3_reset();
$g = t3_gateway();
$r = t3_verify($g, new stdClass(), 'track-abc');
t3_assert($r['reason'] === 'invalid_order' && empty($GLOBALS['t3_transport_calls']), 'legacy verifier rejects non-WC_Order before transport');

$o = t3_order();
$r = t3_verify($g, $o, '');
t3_assert($r['reason'] === 'missing_track_id' && empty($GLOBALS['t3_transport_calls']), 'legacy verifier rejects missing track before transport');

$o = t3_order(); unset($o->meta['UPayments_order_id']);
$r = t3_verify($g, $o, 'track-abc');
t3_assert($r['reason'] === 'missing_local_upay_order_id' && empty($GLOBALS['t3_transport_calls']), 'legacy verifier requires local provider order identity');

t3_reset(); $g = t3_gateway(); $o = t3_order();
$GLOBALS['t3_transport'] = array('transport_ok' => false, 'body' => null, 'http_status' => 0, 'curl_errno' => 1);
$r = t3_verify($g, $o, 'track-abc');
t3_assert($r['reason'] === 'network_error' && count($GLOBALS['t3_transport_calls']) === 1, 'legacy verifier fails closed on transport failure');
t3_assert($GLOBALS['t3_transport_calls'][0]['route'] === 'get-payment-status/track-abc' && $GLOBALS['t3_transport_calls'][0]['method'] === 'GET', 'legacy verifier uses general gateway GET status transport');

t3_reset(); $g = t3_gateway(); $o = t3_order();
$GLOBALS['t3_transport'] = t3_transport_for(t3_transaction(), 200);
$r = t3_verify($g, $o, 'track-abc');
t3_assert($r['reason'] === 'unexpected_http_200', 'legacy verifier requires HTTP 201');

t3_reset(); $g = t3_gateway(); $o = t3_order();
$GLOBALS['t3_transport'] = array('transport_ok' => true, 'body' => '{bad-json', 'http_status' => 201, 'curl_errno' => 0);
$r = t3_verify($g, $o, 'track-abc');
t3_assert($r['reason'] === 'invalid_top_level', 'legacy verifier rejects malformed provider JSON');

t3_reset(); $g = t3_gateway(); $o = t3_order();
$GLOBALS['t3_transport'] = t3_transport_for(t3_transaction(array('reference' => '99')));
$r = t3_verify($g, $o, 'track-abc');
t3_assert($r['reason'] === 'binding_reference' && $r['verified'] === false, 'legacy verifier rejects provider reference mismatch');

t3_reset(); $g = t3_gateway(); $o = t3_order();
$GLOBALS['t3_transport'] = t3_transport_for(t3_transaction(array('total_price' => 'not-a-number')));
$r = t3_verify($g, $o, 'track-abc');
t3_assert($r['reason'] === 'amount_not_numeric', 'legacy verifier rejects nonnumeric provider amount');

t3_reset(); $g = t3_gateway(); $o = t3_order();
$GLOBALS['t3_transport'] = t3_transport_for(t3_transaction(array('total_price' => '9.000')));
$r = t3_verify($g, $o, 'track-abc');
t3_assert($r['reason'] === 'binding_amount', 'legacy verifier rejects amount mismatch');

t3_reset(); $g = t3_gateway(); $o = t3_order();
$GLOBALS['t3_transport'] = t3_transport_for(t3_transaction(array('result' => 'DECLINED')));
$r = t3_verify($g, $o, 'track-abc');
t3_assert($r['reason'] === 'not_captured' && $r['verified'] === false, 'legacy verifier authenticates but does not authorize non-captured result');

t3_reset(); $g = t3_gateway(); $o = t3_order();
$GLOBALS['t3_transport'] = t3_transport_for(t3_transaction());
$r = t3_verify($g, $o, 'track-abc');
t3_assert($r['reason'] === 'captured' && $r['verified'] === true && $r['transaction']['payment_id'] === 'payment-xyz', 'legacy verifier authorizes fully bound CAPTURED transaction');

t3_reset(); $g = t3_gateway(); $o = t3_order();
$GLOBALS['t3_transport'] = array('throw' => true);
$r = t3_verify($g, $o, 'track-abc');
t3_assert($r['reason'] === 'verification_exception' && $r['verified'] === false, 'legacy verifier converts unexpected transport exception to fail-closed result');

// Source-level ownership evidence: direct legacy verifier remains independent of modern verifier/lock/rate path.
$source = file_get_contents(dirname(__DIR__, 2) . '/UPayments.php');
t3_assert(is_string($source), 'gateway source readable');
if (is_string($source)) {
    $start = strpos($source, 'private function verify_payment_status');
    $end = strpos($source, 'private function get_payment_verification_fallback_url', $start === false ? 0 : $start);
    $segment = ($start !== false && $end !== false) ? substr($source, $start, $end - $start) : '';
    t3_assert(t3_contains($segment, 'execute_upayments_request('), 'legacy verifier owns general gateway transport delegation');
    t3_assert(!t3_contains($segment, 'StatusVerifier::'), 'legacy verifier does not call modern StatusVerifier');
    t3_assert(!t3_contains($segment, 'OrderLock'), 'legacy verifier does not use OrderLock');
    t3_assert(!t3_contains($segment, 'StatusRateGate'), 'legacy verifier does not use StatusRateGate');
}

$cases = array(
    'browser-preflight-missing' => array('must' => array('REDIRECT:https://merchant.example.test/?upayments_verification=pending'), 'must_not' => array('TRANSPORT:', 'META:', 'ORDER_STATUS_ATTEMPT:', 'CART_CLEARED')),
    'browser-not-captured' => array('must' => array('TRANSPORT:GET:get-payment-status/track-abc', 'REDIRECT:https://merchant.example.test/?upayments_verification=pending'), 'must_not' => array('META:_upay_verified_capture=1', 'ORDER_STATUS_APPLIED:', 'CART_CLEARED')),
    'browser-captured' => array('must' => array('TRANSPORT:GET:get-payment-status/track-abc', 'META:UPayments_PaymentID=payment-xyz', 'ORDER_STATUS_APPLIED:processing', 'META:_upay_verified_capture=1', 'ORDER_SAVED', 'CART_CLEARED', 'REDIRECT:https://merchant.example.test/order-received/42/?key=wc_order_key_test'), 'must_not' => array('ORDER_STATUS_REJECTED')),
    'browser-status-rejected' => array('must' => array('ORDER_STATUS_REJECTED', 'REDIRECT:https://merchant.example.test/?upayments_verification=pending'), 'must_not' => array('META:_upay_verified_capture=1', 'ORDER_SAVED', 'CART_CLEARED')),
    'browser-replay' => array('must' => array('REDIRECT:https://merchant.example.test/?upayments_verification=pending'), 'must_not' => array('TRANSPORT:', 'ORDER_STATUS_ATTEMPT:', 'ORDER_SAVED', 'CART_CLEARED')),
    'browser-refunded' => array('must' => array('REDIRECT:https://merchant.example.test/?upayments_verification=pending'), 'must_not' => array('TRANSPORT:', 'ORDER_STATUS_ATTEMPT:', 'ORDER_SAVED', 'CART_CLEARED')),
    'webhook-preflight-missing' => array('must' => array('LOG:debug:Webhook received; verifying payment status.'), 'must_not' => array('TRANSPORT:', 'META:', 'ORDER_STATUS_ATTEMPT:')),
    'webhook-binding-failure' => array('must' => array('TRANSPORT:GET:get-payment-status/track-abc'), 'must_not' => array('META:_upay_verified_capture=1', 'ORDER_STATUS_APPLIED:', 'ORDER_SAVED')),
    'webhook-captured' => array('must' => array('TRANSPORT:GET:get-payment-status/track-abc', 'META:UPayments_PaymentID=payment-xyz', 'ORDER_STATUS_APPLIED:processing', 'META:_upay_verified_capture=1', 'ORDER_SAVED'), 'must_not' => array('CART_CLEARED', 'REDIRECT:')),
    'webhook-status-rejected' => array('must' => array('ORDER_STATUS_REJECTED'), 'must_not' => array('META:_upay_verified_capture=1', 'ORDER_SAVED', 'CART_CLEARED')),
    'webhook-replay' => array('must' => array('LOG:debug:Webhook received; verifying payment status.'), 'must_not' => array('TRANSPORT:', 'ORDER_STATUS_ATTEMPT:', 'ORDER_SAVED')),
    'webhook-refunded' => array('must' => array('LOG:debug:Webhook received; verifying payment status.'), 'must_not' => array('TRANSPORT:', 'ORDER_STATUS_ATTEMPT:', 'ORDER_SAVED')),
);
foreach ($cases as $scenario => $expect) {
    $result = t3_run_child($scenario);
    if ($result['stderr'] !== '') { echo '  child stderr [' . $scenario . ']: ' . trim($result['stderr']) . "\n"; }
    t3_assert($result['stderr'] === '', $scenario . ' emits no PHP/runtime stderr');
    t3_assert((int) $result['exit'] === 0, $scenario . ' terminates through the real legacy method');
    foreach ($expect['must'] as $needle) { t3_assert(t3_contains($result['stdout'], $needle), $scenario . ' contains: ' . $needle); }
    foreach ($expect['must_not'] as $needle) { t3_assert(!t3_contains($result['stdout'], $needle), $scenario . ' excludes: ' . $needle); }
}

echo "\n--- Approach 3 T3 Legacy Direct Callback Characterization Report ---\n";
echo 'PASS: ' . $t3_pass . "\n";
echo 'FAIL: ' . $t3_fail . "\n";
exit($t3_fail === 0 ? 0 : 1);
