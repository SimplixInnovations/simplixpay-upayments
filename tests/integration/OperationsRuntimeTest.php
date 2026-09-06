<?php
/**
 * Real-runtime activation/deactivation/uninstall/boot-boundary certification.
 */

require_once __DIR__ . '/bootstrap.php';

$phase = getenv('SIMPLIXPAY_CERT_PHASE');
$settings_key = 'woocommerce_upayments_settings';
$snapshot_key = '_simplixpay_feature_ops_snapshot';
$order_key = '_simplixpay_feature_ops_order_id';
$secret = 'ops-certification-secret-sentinel';

function simplixpay_cert_ops_verify_persistence($settings_key, $snapshot_key, $order_key, $secret) {
    $snapshot = get_option($snapshot_key);
    $settings = get_option($settings_key);
    simplixpay_cert_assert(is_string($snapshot) && '' !== $snapshot, 'operations settings snapshot exists');
    simplixpay_cert_assert(hash_equals($snapshot, maybe_serialize($settings)), 'merchant gateway settings survive lifecycle operation byte-for-byte');

    $order_id = (int) get_option($order_key);
    $order = wc_get_order($order_id);
    simplixpay_cert_assert($order instanceof WC_Order, 'payment order survives lifecycle operation');
    simplixpay_cert_assert('upayments' === $order->get_payment_method(), 'payment-method identity survives lifecycle operation');
    simplixpay_cert_assert('ops-provider-order' === $order->get_meta('UPayments_order_id'), 'provider order identity survives lifecycle operation');
    simplixpay_cert_assert('ops-customer-token' === $order->get_meta('_upay_customer_unique_token'), 'historical token metadata survives lifecycle operation');

    $serialized = maybe_serialize($settings);
    simplixpay_cert_assert(false !== strpos($serialized, $secret), 'credential sentinel remains stored rather than silently erased');
}

if ('seed' === $phase) {
    $settings = array(
        'enabled' => 'yes',
        'api_key' => $secret,
        'test_mode' => 'yes',
        'enable_save_card' => 'yes',
        'enable_subscriptions' => 'no',
    );
    update_option($settings_key, $settings, false);
    update_option($snapshot_key, maybe_serialize($settings), false);

    $order = wc_create_order();
    simplixpay_cert_assert($order instanceof WC_Order, 'operations certification order is created');
    $order->set_payment_method('upayments');
    $order->update_meta_data('UPayments_order_id', 'ops-provider-order');
    $order->update_meta_data('_upay_customer_unique_token', 'ops-customer-token');
    $order->update_meta_data('_upay_subscription_status', 'paused');
    $order->save();
    update_option($order_key, $order->get_id(), false);

    simplixpay_cert_assert(
        class_exists('Simplix\\Pay\\UPayments\\Migration\\MigrationCliCommand'),
        'migration CLI module boots in WP-CLI context'
    );
    simplixpay_cert_assert(
        !class_exists('Simplix\\Pay\\UPayments\\Migration\\MigrationAdmin'),
        'migration admin module does not boot in non-admin WP-CLI context'
    );

    ob_start();
    set_current_screen('dashboard');
    \Simplix\Pay\UPayments\Migration\MigrationBootstrap::boot();
    $boot_output = ob_get_clean();
    simplixpay_cert_assert(
        class_exists('Simplix\\Pay\\UPayments\\Migration\\MigrationAdmin'),
        'migration admin module boots only after an explicit admin context exists'
    );
    simplixpay_cert_assert(
        false === strpos((string) $boot_output, $secret),
        'migration bootstrap emits no merchant credential material'
    );

    simplixpay_cert_ops_verify_persistence($settings_key, $snapshot_key, $order_key, $secret);
    simplixpay_cert_note('operations seed and context-bound migration boot certification complete');
    return;
}

if (in_array($phase, array('deactivated', 'reactivated', 'uninstalled', 'final'), true)) {
    simplixpay_cert_ops_verify_persistence($settings_key, $snapshot_key, $order_key, $secret);

    if ('deactivated' === $phase || 'uninstalled' === $phase) {
        simplixpay_cert_assert(!class_exists('WC_Upayments'), 'SimplixPay runtime class is absent while plugin is inactive');
    } else {
        simplixpay_cert_assert(class_exists('WC_Upayments'), 'SimplixPay runtime class is present after reactivation');
    }

    if ('final' === $phase) {
        $order_id = (int) get_option($order_key);
        $order = wc_get_order($order_id);
        if ($order instanceof WC_Order) {
            $order->delete(true);
        }
        delete_option($snapshot_key);
        delete_option($order_key);
        update_option($settings_key, array(
            'enabled' => 'yes',
            'enable_block_checkout' => 'no',
        ), false);
    }

    simplixpay_cert_note('operations lifecycle phase complete: ' . $phase);
    return;
}

throw new RuntimeException('Unknown SIMPLIXPAY_CERT_PHASE for operations certification.');
