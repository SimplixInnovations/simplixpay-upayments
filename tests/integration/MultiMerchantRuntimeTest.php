<?php
/**
 * Real-runtime single additional-merchant allocation certification.
 */

require_once __DIR__ . '/bootstrap.php';

use Simplix\Pay\UPayments\Payment\CheckoutOrchestrator;

if (!WC()->session) {
    WC()->session = new WC_Session_Handler();
    WC()->session->init();
}

function simplixpay_cert_mm_order() {
    $product = new WC_Product_Simple();
    $product->set_name('Multi-merchant Certification Product');
    $product->set_regular_price('10.00');
    $product->set_price('10.00');
    $product_id = $product->save();
    simplixpay_cert_assert(is_int($product_id) && $product_id > 0, 'multi-merchant certification product persists');

    $order = wc_create_order();
    simplixpay_cert_assert($order instanceof WC_Order, 'multi-merchant certification order is created');
    $order->add_product($product, 1);
    $order->set_payment_method('upayments');
    $order->set_billing_first_name('Certification');
    $order->set_billing_last_name('Merchant');
    $order->set_billing_email('merchant@example.invalid');
    $order->set_billing_phone('50000000');
    $order->set_billing_country('KW');
    $order->set_currency('KWD');
    $order->calculate_totals();
    $order->save();

    return array($order, $product);
}

function simplixpay_cert_mm_gateway($iban) {
    $gateway = new WC_Upayments();
    $gateway->domain = 'upayments';
    $gateway->apiKey = 'certification-api-key';
    $gateway->testMode = 'yes';
    $gateway->autoDeduction = 'no';
    $gateway->saveCardEnabled = 'yes';
    $gateway->paymentData = array('whitelabled' => false, 'payment' => array());
    $gateway->multiMerchant = 'yes';
    $gateway->ibanNumber = $iban;
    $gateway->knetCharge = '0.900';
    $gateway->knetChargeType = 'fixed';
    $gateway->ccCharge = '0.750';
    $gateway->ccChargeType = 'percentage';
    return $gateway;
}

function simplixpay_cert_run_mm($order, $gateway, &$calls) {
    $_POST = array();
    wc_clear_notices();
    $calls = array();

    $orchestrator = new CheckoutOrchestrator(
        $gateway,
        function () {
            return '';
        },
        function ($route, $method, $body = null) use (&$calls) {
            $calls[] = array('route' => $route, 'method' => $method, 'body' => $body);
            return array(
                'transport_ok' => false,
                'http_status' => 0,
                'curl_errno' => 7,
                'body' => '',
            );
        }
    );

    return $orchestrator->process($order->get_id());
}

list($order, $product) = simplixpay_cert_mm_order();

$calls = array();
$gateway = simplixpay_cert_mm_gateway('KW81CBKU0000000000001234560101');
$result = simplixpay_cert_run_mm($order, $gateway, $calls);
simplixpay_cert_assert('failure' === $result['result'], 'bounded multi-merchant probe fails after deliberately unavailable Charge transport');
simplixpay_cert_assert(1 === count($calls), 'valid single-allocation configuration reaches exactly one provider request');
simplixpay_cert_assert('charge' === $calls[0]['route'], 'valid single-allocation configuration reaches only Charge');
simplixpay_cert_assert('POST' === $calls[0]['method'], 'multi-merchant Charge uses POST');
simplixpay_cert_assert(is_string($calls[0]['body']) && '' !== $calls[0]['body'], 'multi-merchant Charge body is captured for runtime inspection');

$payload = json_decode($calls[0]['body'], true);
simplixpay_cert_assert(is_array($payload), 'multi-merchant provider payload is valid JSON');
simplixpay_cert_assert(
    isset($payload['extraMerchantData']) && is_array($payload['extraMerchantData']) && 1 === count($payload['extraMerchantData']),
    'runtime payload contains exactly one additional-merchant allocation'
);
$allocation = $payload['extraMerchantData'][0];
simplixpay_cert_assert('KW81CBKU0000000000001234560101' === $allocation['ibanNumber'], 'additional-merchant IBAN is exact');
simplixpay_cert_assert('fixed' === $allocation['knetChargeType'], 'KNET charge type is exact');
simplixpay_cert_assert('percentage' === $allocation['ccChargeType'], 'credit-card charge type is exact');
simplixpay_cert_assert(
    isset($payload['order']['amount'], $allocation['amount']) && $payload['order']['amount'] === $allocation['amount'],
    'single additional-merchant allocation amount equals the exact order amount'
);

$calls = array();
$invalid_gateway = simplixpay_cert_mm_gateway('invalid iban');
$result = simplixpay_cert_run_mm($order, $invalid_gateway, $calls);
simplixpay_cert_assert('failure' === $result['result'], 'invalid multi-merchant configuration fails closed');
simplixpay_cert_assert(array() === $calls, 'invalid multi-merchant configuration rejects before provider transport');

$order->delete(true);
wp_delete_post($product->get_id(), true);
$_POST = array();
wc_clear_notices();

simplixpay_cert_note('single additional-merchant runtime certification complete');
