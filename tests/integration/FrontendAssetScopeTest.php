<?php
/**
 * Real-runtime regression for SUPCheckout frontend asset scope.
 *
 * Customer-facing gateway assets must not be loaded on unrelated frontend
 * requests or when the gateway is unavailable. When checkout is renderable
 * and the gateway is available, the existing customer/new-design assets must
 * still be enqueued. A gateway that is actually rendered by an embedded or
 * custom checkout outside the canonical WooCommerce checkout page must also
 * self-provision its required presentation assets.
 */

require_once __DIR__ . '/bootstrap.php';

supcheckout_cert_assert(class_exists('WC_Upayments'), 'SUPCheckout Classic gateway class is available for asset-scope certification');

class SUPCheckout_FrontendAssetScopeProbe extends WC_Upayments {
    public $availability_checks = 0;
    public $available = true;

    public function is_available() {
        ++$this->availability_checks;
        return $this->available;
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

$reset_assets = static function () use ($style_handles, $script_handles) {
    foreach ($style_handles as $handle) {
        wp_dequeue_style($handle);
        wp_deregister_style($handle);
    }
    foreach ($script_handles as $handle) {
        wp_dequeue_script($handle);
        wp_deregister_script($handle);
    }
};

$reset_assets();

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

$fixture = __DIR__ . '/fixtures/payment-fields-probe.php';
supcheckout_cert_assert(is_file($fixture), 'Embedded checkout payment-fields fixture exists');
add_filter(
    'wc_get_template',
    static function ($template, $template_name) use ($fixture) {
        if ($template_name === 'new-design-form.php' || $template_name === 'old-design-form.php') {
            return $fixture;
        }
        return $template;
    },
    999,
    2
);

$gateway->settings['use_new_design'] = 'yes';
ob_start();
$gateway->payment_fields();
ob_end_clean();

supcheckout_cert_assert(
    wp_style_is('supcheckout-customer', 'enqueued'),
    'Embedded rendered SUPCheckout gateway self-provisions customer CSS outside canonical checkout page'
);
supcheckout_cert_assert(
    wp_style_is('supcheckout-checkout-new-style', 'enqueued'),
    'Embedded rendered modern gateway self-provisions new-design CSS outside canonical checkout page'
);
supcheckout_cert_assert(
    wp_script_is('supcheckout-checkout-new-script', 'enqueued'),
    'Embedded rendered modern gateway self-provisions checkout JS outside canonical checkout page'
);

remove_filter('wc_get_template', '__return_false', 999);
remove_all_filters('wc_get_template');
$reset_assets();

add_filter('woocommerce_is_checkout', '__return_true', PHP_INT_MAX);
supcheckout_cert_assert(is_checkout(), 'Asset-scope checkout fixture forces WooCommerce checkout context');

$gateway->available = false;
$gateway->enqueue_scripts();

supcheckout_cert_assert(
    $gateway->availability_checks === 1,
    'Unavailable checkout evaluates gateway availability exactly once'
);
supcheckout_cert_assert(
    !wp_style_is('supcheckout-customer', 'enqueued'),
    'Unavailable checkout does not enqueue SUPCheckout customer CSS'
);
supcheckout_cert_assert(
    !wp_style_is('supcheckout-checkout-new-style', 'enqueued'),
    'Unavailable checkout does not enqueue SUPCheckout new-design CSS'
);
supcheckout_cert_assert(
    !wp_script_is('supcheckout-checkout-new-script', 'enqueued'),
    'Unavailable checkout does not enqueue SUPCheckout new-design script'
);

$gateway->available = true;
$gateway->enqueue_scripts();

supcheckout_cert_assert(
    $gateway->availability_checks === 2,
    'Available checkout performs one additional gateway availability evaluation'
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
$reset_assets();

fwrite(STDOUT, "CERT: frontend asset-scope certification complete\n");
