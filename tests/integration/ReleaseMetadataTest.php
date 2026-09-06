<?php
/**
 * Real-runtime release support metadata certification.
 */

require_once __DIR__ . '/bootstrap.php';

$plugin_file = SIMPLIXPAY_UPAYMENTS_PLUGIN_FILE;
$headers = get_file_data(
    $plugin_file,
    array(
        'requires_wp'    => 'Requires at least',
        'tested_wp'      => 'Tested up to',
        'requires_php'   => 'Requires PHP',
        'requires_wc'    => 'WC requires at least',
        'tested_wc'      => 'WC tested up to',
    )
);

simplixpay_cert_assert('6.9' === $headers['requires_wp'], 'WordPress minimum support series is matrix-proven 6.9');
simplixpay_cert_assert('7.1' === $headers['tested_wp'], 'WordPress tested series is matrix-proven 7.1');
simplixpay_cert_assert('7.4' === $headers['requires_php'], 'PHP runtime floor is matrix-proven 7.4');
simplixpay_cert_assert('10.8' === $headers['requires_wc'], 'WooCommerce minimum support series is matrix-proven 10.8');
simplixpay_cert_assert('11.1' === $headers['tested_wc'], 'WooCommerce tested series is matrix-proven 11.1');

simplixpay_cert_assert(did_action('woocommerce_init') > 0, 'WooCommerce initialized before feature compatibility inspection');

$plugin_name = plugin_basename($plugin_file);
$feature_compatibility = Automattic\WooCommerce\Utilities\FeaturesUtil::get_compatible_features_for_plugin($plugin_name);

simplixpay_cert_assert(
    isset($feature_compatibility['compatible']) && is_array($feature_compatibility['compatible']),
    'WooCommerce returns the compatible-feature registry for SimplixPay'
);
simplixpay_cert_assert(
    isset($feature_compatibility['incompatible']) && is_array($feature_compatibility['incompatible']),
    'WooCommerce returns the incompatible-feature registry for SimplixPay'
);
simplixpay_cert_assert(
    in_array('cart_checkout_blocks', $feature_compatibility['compatible'], true),
    'Cart and Checkout Blocks compatibility remains declared in the real WooCommerce registry'
);
simplixpay_cert_assert(
    !in_array('cart_checkout_blocks', $feature_compatibility['incompatible'], true),
    'Cart and Checkout Blocks are not simultaneously declared incompatible'
);
simplixpay_cert_assert(
    in_array('custom_order_tables', $feature_compatibility['compatible'], true),
    'HPOS custom_order_tables compatibility is declared in the real WooCommerce registry'
);
simplixpay_cert_assert(
    !in_array('custom_order_tables', $feature_compatibility['incompatible'], true),
    'HPOS custom_order_tables is not simultaneously declared incompatible'
);

simplixpay_cert_note('release support metadata and feature compatibility certification complete');
