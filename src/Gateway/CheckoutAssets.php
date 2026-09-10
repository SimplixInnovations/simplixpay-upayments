<?php

namespace Simplixi\SUPCheckout\Gateway;

use Simplixi\SUPCheckout\Release\Identity;

defined('ABSPATH') || exit;

/**
 * Assets required by a rendered Classic SUPCheckout payment-fields template.
 *
 * Canonical checkout requests are still pre-enqueued by the legacy gateway.
 * This boundary closes the embedded/custom-checkout gap by reacting only when
 * WooCommerce actually resolves one of SUPCheckout's payment-field templates.
 */
final class CheckoutAssets {
    /** @var bool */
    private static $bootstrapped = false;

    /** Register the render-scoped template observer exactly once. */
    public static function bootstrap() {
        if (self::$bootstrapped || ! function_exists('add_filter')) {
            return;
        }

        add_filter('wc_get_template', array(self::class, 'filter_template'), 5, 5);
        self::$bootstrapped = true;
    }

    /**
     * Provision only the assets required by an actually rendered SUPCheckout
     * Classic payment-fields template. The located path is never changed, so
     * theme/template overrides keep normal WooCommerce precedence.
     *
     * @param mixed  $located       Located template path.
     * @param mixed  $template_name Requested template name.
     * @param mixed  $args          Template arguments.
     * @param mixed  $template_path WooCommerce template path.
     * @param mixed  $default_path  Plugin fallback path.
     * @return mixed
     */
    public static function filter_template($located, $template_name, $args, $template_path, $default_path) {
        unset($template_path, $default_path);

        if ($template_name !== 'new-design-form.php' && $template_name !== 'old-design-form.php') {
            return $located;
        }

        if (! is_array($args)
            || ! isset($args['gateway'])
            || ! is_object($args['gateway'])
            || ! is_a($args['gateway'], 'WC_Upayments')
        ) {
            return $located;
        }

        self::enqueue_for_template($template_name);
        return $located;
    }

    /**
     * Enqueue render-owned assets without widening frontend scope.
     *
     * @param string $template_name Exact SUPCheckout template name.
     * @return void
     */
    private static function enqueue_for_template($template_name) {
        $plugin_url = plugin_dir_url(dirname(__DIR__, 2) . '/UPayments.php');

        wp_enqueue_style(
            'supcheckout-customer',
            $plugin_url . 'assets/css/customer.css',
            array(),
            Identity::VERSION
        );

        if ($template_name !== 'new-design-form.php') {
            return;
        }

        wp_enqueue_style(
            'supcheckout-checkout-new-style',
            $plugin_url . 'assets/css/new-design.css',
            array(),
            Identity::VERSION
        );
        wp_enqueue_script(
            'supcheckout-checkout-new-script',
            $plugin_url . 'assets/js/new-upay.js',
            array('jquery'),
            Identity::VERSION,
            true
        );
    }

    private function __construct() {}
}
