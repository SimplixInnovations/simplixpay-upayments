<?php
/**
 * Real-runtime regression for SUPCheckout frontend asset scope.
 *
 * Customer-facing gateway assets must not be loaded on unrelated frontend
 * requests. When checkout is renderable and the gateway is available, the
 * existing customer/new-design assets must still be enqueued.
 */

require_once __DIR__ . '/bootstrap.php';

supcheckout_cert_assert(class_exists('WC_Upayments'), 'SUPCheckout Classic gateway class is available for asset-scope certification');

class SUPCheckout_FrontendAssetScopeProbe extends WC_Upayments {
    public $availability_checks = 0;

    public function is_available() {
        ++$this->availability_checks;
        return true;
    }
}

$style_handles = array(
    'supcheckout-customer',
    'supcheckout-checkout-new-style',
);
$script_handles = array(
    'supcheckout-checkout-new-script',
    'supcheckout-subscription-checkout',
);

foreach ($style_handles as $handle) {
    wp_dequeue_style($handle);
    wp_deregister_style($handle);
}
foreach ($script_handles as $handle) {
    wp_dequeue_script($handle);
    wp_deregister_script($handle);
}

supcheckout_cert_assert(!is_checkout(), 'Asset-scope negative fixture executes outside WooCommerce checkout context');

$gateway = new SUPCheckout_FrontendAssetScopeProbe();
$gateway->enqueue_scripts();

supcheckout_cert_assert(
    !wp_style_is('supcheckout-customer', 'enqueued'),
    'Unrelated frontend request does not enqueue SUPCheckout customer CSS'
);
supcheckout_cert_assert(
    $gateway->availability_checks === 0,
    'Unrelated frontend request short-circuits before gateway availability evaluation'
);

add_filter('woocommerce_is_checkout', '__return_true', PHP_INT_MAX);
supcheckout_cert_assert(is_checkout(), 'Asset-scope positive fixture forces WooCommerce checkout context');

$gateway->enqueue_scripts();

supcheckout_cert_assert(
    $gateway->availability_checks === 1,
    'Checkout asset scope evaluates gateway availability exactly once'
);
supcheckout_cert_assert(
    wp_style_is('supcheckout-customer', 'enqueued'),
    'Renderable checkout enqueues SUPCheckout customer CSS'
);
supcheckout_cert_assert(
    wp_style_is('supcheckout-checkout-new-style', 'enqueued'),
    'Renderable default checkout enqueues SUPCheckout new-design CSS'
);
supcheckout_cert_assert(
    wp_script_is('supcheckout-checkout-new-script', 'enqueued'),
    'Renderable default checkout enqueues SUPCheckout new-design script'
);

remove_filter('woocommerce_is_checkout', '__return_true', PHP_INT_MAX);

foreach ($style_handles as $handle) {
    wp_dequeue_style($handle);
    wp_deregister_style($handle);
}
foreach ($script_handles as $handle) {
    wp_dequeue_script($handle);
    wp_deregister_script($handle);
}

fwrite(STDOUT, "CERT: frontend asset-scope certification complete\n");
