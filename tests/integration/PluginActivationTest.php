<?php
/**
 * Real-runtime activation and Classic gateway certification.
 */

require_once __DIR__ . '/bootstrap.php';

$phase = getenv('SIMPLIXPAY_CERT_PHASE');

if ('seed' === $phase) {
    $sentinel = (object) array(
        'enabled'               => 'yes',
        'api_key'               => 'certification-sentinel',
        'enable_block_checkout' => 'no',
    );

    simplixpay_cert_store_option_raw('woocommerce_upayments_settings', $sentinel);

    $stored = get_option('woocommerce_upayments_settings');
    simplixpay_cert_assert(is_object($stored), 'malformed object-valued gateway settings are persisted before activation');
    simplixpay_cert_assert(isset($stored->api_key) && 'certification-sentinel' === $stored->api_key, 'pre-activation settings sentinel is exact');
    simplixpay_cert_note('activation seed complete');
    return;
}

if ('verify' === $phase) {
    $stored = get_option('woocommerce_upayments_settings');

    simplixpay_cert_assert(is_object($stored), 'activation does not rewrite malformed persisted gateway settings');
    simplixpay_cert_assert(isset($stored->api_key) && 'certification-sentinel' === $stored->api_key, 'activation preserves protected settings bytes');
    simplixpay_cert_assert(class_exists('WooCommerce'), 'WooCommerce is active in the certification runtime');
    simplixpay_cert_assert(class_exists('WC_Upayments'), 'SimplixPay Classic gateway class loads in the real WooCommerce runtime');

    $gateways = WC()->payment_gateways()->payment_gateways();
    simplixpay_cert_assert(is_array($gateways), 'WooCommerce returns a real payment gateway registry');
    simplixpay_cert_assert(isset($gateways['upayments']), 'Classic gateway ID upayments is registered');
    simplixpay_cert_assert($gateways['upayments'] instanceof WC_Upayments, 'registered Classic gateway is the SimplixPay gateway instance');

    simplixpay_cert_note('activation and Classic gateway certification complete');
    return;
}

throw new RuntimeException('Unknown SIMPLIXPAY_CERT_PHASE.');
