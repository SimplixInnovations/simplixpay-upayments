<?php
/**
 * Real WooCommerce Blocks payment-method registration/availability certification.
 */

require_once __DIR__ . '/bootstrap.php';

sucheckout_cert_assert(
    class_exists('Automattic\\WooCommerce\\Blocks\\Payments\\PaymentMethodRegistry'),
    'WooCommerce Blocks PaymentMethodRegistry is available'
);
sucheckout_cert_assert(
    did_action('woocommerce_blocks_loaded') > 0,
    'WooCommerce Blocks loaded hook fired in the real runtime'
);
sucheckout_cert_assert(
    false !== has_action('woocommerce_blocks_payment_method_type_registration'),
    'SUCheckout registered a server-side Blocks payment-method callback'
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
    sucheckout_cert_store_option_raw('woocommerce_upayments_settings', $case['settings']);

    $registry = new Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry();
    $registry->initialize();

    sucheckout_cert_assert($registry->is_registered('upayments'), 'Blocks registry contains upayments for case ' . $label);

    $integration = $registry->get_registered('upayments');
    sucheckout_cert_assert($integration instanceof WCGatewayUPaymentsBlocks, 'Blocks registry returns SUCheckout integration for case ' . $label);
    sucheckout_cert_assert($integration->is_active() === $case['active'], 'Blocks availability is exact for case ' . $label);
    sucheckout_cert_assert(
        array('products') === $integration->get_supported_features(),
        'Blocks supported-feature contract remains products-only for case ' . $label
    );
}

sucheckout_cert_store_option_raw('woocommerce_upayments_settings', $original_settings);
sucheckout_cert_note('Blocks registration and availability certification complete');
