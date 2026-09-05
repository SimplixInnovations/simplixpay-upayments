<?php

final class SimplixPay_Test_Payment_Runtime_WC {
    public $cart;

    public function __construct() {
        $this->cart = (object) array();
    }

    public function payment_gateways() {
        return false;
    }
}

function simplixpay_test_reset_payment_runtime() {
    simplixpay_test_reset_public_order_status();
    simplixpay_test_reset_subscription_presentation();
    $GLOBALS['simplixpay_test_subscription_presentation']['wc'] = new SimplixPay_Test_Payment_Runtime_WC();
}

function wc_get_checkout_url() {
    return 'https://example.test/checkout';
}

simplixpay_test_reset_payment_runtime();
