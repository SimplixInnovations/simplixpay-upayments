<?php
/**
 * Real WooCommerce legacy/HPOS order CRUD certification.
 */

require_once __DIR__ . '/bootstrap.php';

$mode = getenv('SIMPLIXPAY_HPOS_MODE');
simplixpay_cert_assert(in_array($mode, array('legacy', 'hpos'), true), 'HPOS certification mode is explicit');
simplixpay_cert_assert(
    class_exists('Automattic\\WooCommerce\\Utilities\\OrderUtil'),
    'WooCommerce OrderUtil is available'
);

$hpos_enabled = Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
simplixpay_cert_assert(
    ('hpos' === $mode) === $hpos_enabled,
    'WooCommerce authoritative order store matches requested certification mode'
);

$product = new WC_Product_Simple();
$product->set_name('SimplixPay Certification Product');
$product->set_regular_price('10.00');
$product->set_price('10.00');
$product_id = $product->save();
simplixpay_cert_assert(is_int($product_id) && $product_id > 0, 'certification product persists');

$order = wc_create_order();
simplixpay_cert_assert($order instanceof WC_Order, 'WooCommerce creates a real order');

$order->set_payment_method('upayments');
$order->add_product($product, 1);
$order->update_meta_data('UPayments_order_id', 'certification-order-identity');
$order->update_meta_data('_simplixpay_certification_marker', $mode);
$order->calculate_totals();
$order_id = $order->save();

simplixpay_cert_assert(is_int($order_id) && $order_id > 0, 'order persists in requested authoritative store');

$reloaded = wc_get_order($order_id);
simplixpay_cert_assert($reloaded instanceof WC_Order, 'order reloads through WooCommerce CRUD');
simplixpay_cert_assert('upayments' === $reloaded->get_payment_method(), 'SimplixPay payment method identity survives order reload');
simplixpay_cert_assert(
    'certification-order-identity' === $reloaded->get_meta('UPayments_order_id'),
    'protected UPayments order metadata survives order reload'
);
simplixpay_cert_assert(
    $mode === $reloaded->get_meta('_simplixpay_certification_marker'),
    'certification metadata survives order reload'
);
simplixpay_cert_assert('10.00' === wc_format_decimal($reloaded->get_total(), 2), 'order economics survive order reload');

global $wpdb;

if ('hpos' === $mode) {
    $orders_table = $wpdb->prefix . 'wc_orders';
    $meta_table   = $wpdb->prefix . 'wc_orders_meta';

    $order_row = $wpdb->get_var(
        $wpdb->prepare("SELECT id FROM {$orders_table} WHERE id = %d", $order_id)
    );
    $meta_row = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT meta_value FROM {$meta_table} WHERE order_id = %d AND meta_key = %s",
            $order_id,
            'UPayments_order_id'
        )
    );

    simplixpay_cert_assert((int) $order_id === (int) $order_row, 'HPOS authoritative orders table contains the order');
    simplixpay_cert_assert('certification-order-identity' === $meta_row, 'HPOS authoritative meta table contains protected metadata');
} else {
    $post_type = $wpdb->get_var(
        $wpdb->prepare("SELECT post_type FROM {$wpdb->posts} WHERE ID = %d", $order_id)
    );
    $meta_row = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s",
            $order_id,
            'UPayments_order_id'
        )
    );

    simplixpay_cert_assert('shop_order' === $post_type, 'legacy authoritative posts table contains the shop order');
    simplixpay_cert_assert('certification-order-identity' === $meta_row, 'legacy authoritative postmeta contains protected metadata');
}

$reloaded->delete(true);
wp_delete_post($product_id, true);
simplixpay_cert_note('order CRUD certification complete: ' . $mode);
