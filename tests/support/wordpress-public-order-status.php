<?php

final class SimplixPay_Test_Json_Response extends RuntimeException {
    public $payload;
    public $status_code;

    public function __construct(array $payload, $status_code) {
        parent::__construct('JSON response captured');
        $this->payload = $payload;
        $this->status_code = (int) $status_code;
    }
}

final class SimplixPay_Test_Status_Order {
    private $payment_method;
    private $user_id;
    private $order_key;
    private $status;

    public function __construct($payment_method, $user_id, $order_key, $status = 'wait') {
        $this->payment_method = $payment_method;
        $this->user_id = $user_id;
        $this->order_key = $order_key;
        $this->status = $status;
    }

    public function get_payment_method() { return $this->payment_method; }
    public function get_user_id() { return $this->user_id; }
    public function get_order_key() { return $this->order_key; }
    public function get_meta($key) { return $key === 'UPayments_WHS' ? $this->status : ''; }
}

function simplixpay_test_reset_public_order_status() {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_GET = array();
    $GLOBALS['simplixpay_test_status_orders'] = array();
    $GLOBALS['simplixpay_test_status_logged_in'] = false;
    $GLOBALS['simplixpay_test_status_user_id'] = 0;
    $GLOBALS['simplixpay_test_wc_get_order_calls'] = array();
}

function wc_get_order($order_id) {
    $GLOBALS['simplixpay_test_wc_get_order_calls'][] = $order_id;
    $order_id = (int) $order_id;
    return isset($GLOBALS['simplixpay_test_status_orders'][$order_id])
        ? $GLOBALS['simplixpay_test_status_orders'][$order_id]
        : false;
}

function is_user_logged_in() {
    return $GLOBALS['simplixpay_test_status_logged_in'];
}

function get_current_user_id() {
    return $GLOBALS['simplixpay_test_status_user_id'];
}

function wp_unslash($value) {
    return is_string($value) ? stripslashes($value) : $value;
}

function wp_send_json($response, $status_code = null, $flags = 0) {
    throw new SimplixPay_Test_Json_Response($response, $status_code);
}

function status_header($code, $description = '') {
    $GLOBALS['simplixpay_test_status_header'] = (int) $code;
}

function wp_json_encode($data, $options = 0, $depth = 512) {
    return json_encode($data, $options, $depth);
}

simplixpay_test_reset_public_order_status();
