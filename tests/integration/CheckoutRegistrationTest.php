<?php
/**
 * Real WooCommerce Blocks payment-method registration/availability certification.
 */

require_once __DIR__ . '/bootstrap.php';

simplixpay_cert_assert(
    class_exists('Automattic\\WooCommerce\\Blocks\\Payments\\PaymentMethodRegistry'),
    'WooCommerce Blocks PaymentMethodRegistry is available'
);
simplixpay_cert_assert(
    did_action('woocommerce_blocks_loaded') > 0,
    'WooCommerce Blocks loaded hook fired in the real runtime'
);
simplixpay_cert_assert(
    false !== has_action('woocommerce_blocks_payment_method_type_registration'),
    'SimplixPay registered a server-side Blocks payment-method callback'
);

$original_settings = get_option('woocommerce_upayments_settings');

$cases = array(
    'enabled' => array(
        'settings' => array('enabled' => 'yes'),
        'active'   => true,
    ),
    'disabled' => array(
        'settings' => array('enabled' => 'no'),
        'active'   => false,
    ),
    'fresh-default' => array(
        'settings' => array(),
        'active'   => true,
    ),
    'malformed-object' => array(
        'settings' => (object) array('enabled' => 'yes'),
        'active'   => false,
    ),
);

foreach ($cases as $label => $case) {
    update_option('woocommerce_upayments_settings', $case['settings']);

    $registry = new Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry();
    $registry->initialize();

    simplixpay_cert_assert($registry->is_registered('upayments'), 'Blocks registry contains upayments for case ' . $label);

    $integration = $registry->get_registered('upayments');
    simplixpay_cert_assert($integration instanceof WCGatewayUPaymentsBlocks, 'Blocks registry returns SimplixPay integration for case ' . $label);
    simplixpay_cert_assert($integration->is_active() === $case['active'], 'Blocks availability is exact for case ' . $label);
    simplixpay_cert_assert(
        array('products') === $integration->get_supported_features(),
        'Blocks supported-feature contract remains products-only for case ' . $label
    );
}

update_option('woocommerce_upayments_settings', $original_settings);
simplixpay_cert_note('Blocks registration and availability certification complete');
