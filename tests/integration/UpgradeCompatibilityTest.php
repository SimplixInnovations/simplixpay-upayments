<?php
/**
 * Existing-install physical basename upgrade characterization.
 *
 * The workflow drives filesystem/package transitions between phases; this file
 * inspects the resulting real WordPress/WooCommerce runtime and protected data.
 */

require_once __DIR__ . '/bootstrap.php';

$phase = getenv('SIMPLIXPAY_UPGRADE_PHASE');
$settings_key = 'woocommerce_upayments_settings';
$snapshot_key = '_simplixpay_upgrade_settings_snapshot';
$order_key = '_simplixpay_upgrade_order_id';

function simplixpay_upgrade_assert_protected_data($settings_key, $snapshot_key, $order_key) {
    $snapshot = get_option($snapshot_key);
    $settings = get_option($settings_key);

    simplixpay_cert_assert(is_string($snapshot) && '' !== $snapshot, 'upgrade settings snapshot exists');
    simplixpay_cert_assert(
        hash_equals($snapshot, maybe_serialize($settings)),
        'existing merchant settings survive package transition byte-for-byte'
    );

    $order_id = (int) get_option($order_key);
    $order = wc_get_order($order_id);
    simplixpay_cert_assert($order instanceof WC_Order, 'existing payment order survives package transition');
    simplixpay_cert_assert('upayments' === $order->get_payment_method(), 'historical payment-method identity remains upayments');
    simplixpay_cert_assert(
        'upgrade-provider-order' === $order->get_meta('UPayments_order_id'),
        'historical provider order identity survives package transition'
    );
}

if ('seed' === $phase) {
    simplixpay_cert_assert(class_exists('WC_Upayments'), 'legacy-basename plugin is active before upgrade');
    simplixpay_cert_assert(
        plugin_basename(SIMPLIXPAY_UPAYMENTS_PLUGIN_FILE) === 'simplixpay-upayments/UPayments.php',
        'existing active plugin basename is the historical UPayments.php path'
    );

    $settings = array(
        'enabled' => 'yes',
        'api_key' => 'upgrade-certification-sentinel',
        'test_mode' => 'yes',
        'enable_save_card' => 'yes',
    );
    update_option($settings_key, $settings, false);
    update_option($snapshot_key, maybe_serialize($settings), false);

    $order = wc_create_order();
    simplixpay_cert_assert($order instanceof WC_Order, 'upgrade certification order is created');
    $order->set_payment_method('upayments');
    $order->update_meta_data('UPayments_order_id', 'upgrade-provider-order');
    $order->save();
    update_option($order_key, $order->get_id(), false);

    simplixpay_upgrade_assert_protected_data($settings_key, $snapshot_key, $order_key);
    simplixpay_cert_note('upgrade baseline seeded under historical basename');
    return;
}

if ('renamed-red' === $phase) {
    $plugin_dir = WP_PLUGIN_DIR . '/simplixpay-upayments';
    simplixpay_cert_assert(!is_file($plugin_dir . '/UPayments.php'), 'renamed candidate removed the historical main file');
    simplixpay_cert_assert(is_file($plugin_dir . '/simplixpay-upayments.php'), 'renamed candidate installed the proposed canonical main file');
    simplixpay_upgrade_assert_protected_data($settings_key, $snapshot_key, $order_key);

    // RED migration requirement: an in-place package upgrade must preserve the
    // already-active gateway without requiring merchant intervention. If this
    // assertion fails, the physical basename migration is not safe for 1.0.
    simplixpay_cert_assert(
        class_exists('WC_Upayments'),
        'renamed in-place package upgrade preserves the already-active gateway without manual reactivation'
    );
    simplixpay_cert_note('renamed package preserved active runtime identity');
    return;
}

if ('rollback' === $phase) {
    simplixpay_cert_assert(class_exists('WC_Upayments'), 'historical package can be explicitly reactivated after rollback');
    simplixpay_cert_assert(
        plugin_basename(SIMPLIXPAY_UPAYMENTS_PLUGIN_FILE) === 'simplixpay-upayments/UPayments.php',
        'rollback restores the historical physical basename'
    );
    simplixpay_upgrade_assert_protected_data($settings_key, $snapshot_key, $order_key);
    simplixpay_cert_note('rollback compatibility verified');
    return;
}

if ('duplicate' === $phase) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    $plugins = get_plugins();
    simplixpay_cert_assert(
        isset($plugins['simplixpay-upayments/UPayments.php']),
        'historical package remains discoverable during duplicate-package characterization'
    );
    simplixpay_cert_assert(
        isset($plugins['simplixpay-upayments-next/simplixpay-upayments.php']),
        'renamed duplicate package is independently discoverable by WordPress'
    );
    simplixpay_upgrade_assert_protected_data($settings_key, $snapshot_key, $order_key);
    simplixpay_cert_note('duplicate-package discovery verified');
    return;
}

if ('cleanup' === $phase) {
    $order_id = (int) get_option($order_key);
    $order = wc_get_order($order_id);
    if ($order instanceof WC_Order) {
        $order->delete(true);
    }
    delete_option($snapshot_key);
    delete_option($order_key);
    update_option($settings_key, array('enabled' => 'yes', 'enable_block_checkout' => 'no'), false);
    simplixpay_cert_note('upgrade certification fixtures cleaned');
    return;
}

throw new RuntimeException('Unknown SIMPLIXPAY_UPGRADE_PHASE.');
