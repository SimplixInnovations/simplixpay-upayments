<?php

namespace Simplix\Pay\UPayments\Subscription;

use UPayments\Subscription\Checkout\Fields;
use UPayments\Subscription\Manager;

/**
 * Composition boundary for the inherited subscription presentation surfaces.
 *
 * Scheduler/dispatch, payment payloads and customer action mutations remain
 * outside this class. This boundary owns hook registration only.
 */
final class Composition {
    /**
     * Register hooks that are independent of a gateway instance.
     *
     * @return void
     */
    public static function register_presentation_hooks() {
        add_action('init', array(Presentation::class, 'register_product_class'));
        add_filter('product_type_selector', array(Presentation::class, 'add_custom_product_type'));
        add_filter('woocommerce_product_class', array(Presentation::class, 'map_custom_product_class'), 10, 2);
        add_action('woocommerce_custom_type_add_to_cart', 'woocommerce_simple_add_to_cart', 30);
        add_action('admin_footer', array(Presentation::class, 'custom_product_types'));
        add_filter('woocommerce_product_data_tabs', array(Presentation::class, 'add_custom_data_tab'));
        add_action('woocommerce_product_data_panels', array(Presentation::class, 'add_custom_data_panel'));
        add_action('woocommerce_process_product_meta', array(Presentation::class, 'save_custom_field_data'));
        add_action('woocommerce_single_product_summary', array(Presentation::class, 'display_custom_field_on_frontend'), 10);
        add_filter('woocommerce_get_item_data', array(Presentation::class, 'display_custom_data_in_cart'), 10, 2);
        add_action('woocommerce_checkout_create_order_line_item', array(Presentation::class, 'save_custom_data_to_order_items'), 10, 4);
        add_action('woocommerce_order_details_after_order_table', array(Presentation::class, 'render_account_order_details'));
        add_action('woocommerce_before_account_orders', array(Presentation::class, 'render_account_orders_filter'));
        add_filter('woocommerce_my_account_my_orders_query', array(Presentation::class, 'filter_account_orders_query'));
        add_filter('woocommerce_my_account_my_orders_columns', array(Presentation::class, 'filter_account_orders_columns'));
        add_action('woocommerce_my_account_my_orders_column_order_type', array(Presentation::class, 'render_account_order_type'));
        add_action('woocommerce_my_account_my_orders_column_order_status', array(Presentation::class, 'render_account_subscription_status'));
        add_action('woocommerce_admin_order_data_after_billing_address', array(Presentation::class, 'render_admin_order_summary'), 10, 1);
    }

    /**
     * Initialize existing checkout/storage modules behind the public gateway seam.
     *
     * @return void
     */
    public static function initialize_legacy_modules() {
        $root = dirname(__DIR__, 2);
        require_once $root . '/includes/Subscription/Checkout/Fields.php';
        require_once $root . '/includes/Subscription/Manager.php';
        require_once $root . '/includes/Subscription/Helpers/Utils.php';
        Fields::init();
        Manager::init();
    }

    /**
     * Register the two inherited gateway-instance presentation hooks.
     *
     * @param object $gateway WC_Upayments instance.
     * @return void
     */
    public static function register_gateway_hooks($gateway) {
        add_filter('woocommerce_add_to_cart_validation', array($gateway, 'restrictMixedCartProducts'), 10, 3);
        add_action('woocommerce_before_shop_loop_item_title', array($gateway, 'renderSubscriptionBadgeInProductList'), 9);
    }
}
