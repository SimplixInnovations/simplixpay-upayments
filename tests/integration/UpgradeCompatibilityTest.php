<?php
/**
 * Existing-install upgrade / physical-basename certification.
 */

require_once __DIR__ . '/bootstrap.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

$phase = getenv('SIMPLIXPAY_UPGRADE_PHASE');

$old_basename = 'simplixpay-upayments/UPayments.php';
$target_basename = 'simplixpay-upayments/simplixpay-upayments.php';
$duplicate_basename = 'upayments-legacy/UPayments.php';

$settings_key = 'woocommerce_upayments_settings';
$settings_snapshot_key = '_simplixpay_upgrade_settings_snapshot';
$order_key = '_simplixpay_upgrade_order_id';
$product_key = '_simplixpay_upgrade_product_id';
$cron_key = '_simplixpay_upgrade_cron_timestamp';
$recovery_key = '_simplixpay_upgrade_rename_recovery_required';

function simplixpay_upgrade_active_plugins() {
    $active = get_option('active_plugins', array());
    return is_array($active) ? array_values($active) : array();
}

function simplixpay_upgrade_verify_data($settings_key, $settings_snapshot_key, $order_key, $cron_key) {
    $snapshot = get_option($settings_snapshot_key);
    $settings = get_option($settings_key);

    simplixpay_cert_assert(
        is_string($snapshot) && $snapshot !== '',
        'upgrade settings snapshot exists'
    );
    simplixpay_cert_assert(
        is_string($snapshot) && hash_equals($snapshot, maybe_serialize($settings)),
        'merchant settings survive package transition byte-for-byte'
    );

    $order_id = (int) get_option($order_key);
    $order = wc_get_order($order_id);
    simplixpay_cert_assert($order instanceof WC_Order, 'historical payment order survives package transition');
    simplixpay_cert_assert('upayments' === $order->get_payment_method(), 'historical gateway ID remains upayments');
    simplixpay_cert_assert(
        'upgrade-provider-order' === $order->get_meta('UPayments_order_id'),
        'historical provider order identity survives package transition'
    );
    simplixpay_cert_assert(
        'upgrade-customer-token' === $order->get_meta('_upay_customer_unique_token'),
        'historical customer-token metadata survives package transition'
    );
    simplixpay_cert_assert(
        'monthly' === $order->get_meta('_upay_subscription_plan'),
        'historical subscription plan metadata survives package transition'
    );
    simplixpay_cert_assert(
        1 === (int) $order->get_meta('_upay_subscription_interval'),
        'historical subscription interval metadata survives package transition'
    );

    $expected_cron = (int) get_option($cron_key);
    $actual_cron = wp_next_scheduled('upay_process_subscriptions');
    simplixpay_cert_assert($expected_cron > 0, 'upgrade snapshot contains canonical subscription cron timestamp');
    simplixpay_cert_assert(
        is_int($actual_cron) && $actual_cron === $expected_cron,
        'canonical subscription cron schedule survives package transition unchanged'
    );
}

function simplixpay_upgrade_assert_active_contract($old_basename, $target_basename) {
    simplixpay_cert_assert(
        file_exists(WP_PLUGIN_DIR . '/simplixpay-upayments/UPayments.php'),
        'transitional main file UPayments.php exists'
    );
    simplixpay_cert_assert(
        !file_exists(WP_PLUGIN_DIR . '/simplixpay-upayments/simplixpay-upayments.php'),
        'target renamed main file is absent in retained-identity package'
    );
    simplixpay_cert_assert(is_plugin_active($old_basename), 'transitional plugin basename remains active');
    simplixpay_cert_assert(!is_plugin_active($target_basename), 'target renamed basename is not separately active');
    simplixpay_cert_assert(class_exists('WC_Upayments'), 'SimplixPay runtime loads after package transition');

    $gateways = WC()->payment_gateways()->payment_gateways();
    simplixpay_cert_assert(
        isset($gateways['upayments']) && $gateways['upayments'] instanceof WC_Upayments,
        'WooCommerce gateway ID upayments remains registered after package transition'
    );
    simplixpay_cert_assert(
        false !== has_action('woocommerce_api_wc_upayments'),
        'historical WooCommerce API callback hook remains registered'
    );

    $headers = get_file_data(
        WP_PLUGIN_DIR . '/simplixpay-upayments/UPayments.php',
        array('text_domain' => 'Text Domain')
    );
    simplixpay_cert_assert(
        isset($headers['text_domain']) && $headers['text_domain'] === 'upayments',
        'retained package header text domain remains upayments'
    );

    $translation_hits = 0;
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(
            WP_PLUGIN_DIR . '/simplixpay-upayments',
            FilesystemIterator::SKIP_DOTS
        )
    );
    foreach ($iterator as $file_info) {
        if (!$file_info->isFile() || strtolower($file_info->getExtension()) !== 'php') {
            continue;
        }
        $source = file_get_contents($file_info->getPathname());
        if (!is_string($source)) {
            continue;
        }
        $translation_hits += preg_match_all(
            '/(?:__|_e|esc_html__|esc_html_e|esc_attr__|esc_attr_e)\s*\([^)]*[\'\"]upayments[\'\"]/s',
            $source,
            $matches
        );
    }
    simplixpay_cert_assert(
        $translation_hits > 0,
        'runtime source still contains explicit translation calls bound to text domain upayments'
    );
    simplixpay_cert_note('legacy text-domain translation call count=' . $translation_hits);
}

if ('seed-existing' === $phase) {
    simplixpay_upgrade_assert_active_contract($old_basename, $target_basename);

    $settings = array(
        'enabled' => 'yes',
        'api_key' => 'upgrade-certification-secret',
        'test_mode' => 'yes',
        'enable_save_card' => 'yes',
        'enable_subscriptions' => 'yes',
        'enable_multimerchant' => 'no',
    );
    simplixpay_cert_store_option_raw($settings_key, $settings);
    simplixpay_cert_store_option_raw($settings_snapshot_key, maybe_serialize($settings));

    $product = new WC_Product_Simple();
    $product->set_name('Upgrade Certification Product');
    $product->set_regular_price('10.00');
    $product->set_price('10.00');
    $product_id = $product->save();
    simplixpay_cert_assert(is_int($product_id) && $product_id > 0, 'upgrade certification product persists');
    update_option($product_key, $product_id, false);

    $order = wc_create_order();
    simplixpay_cert_assert($order instanceof WC_Order, 'upgrade certification order is created');
    $order->set_payment_method('upayments');
    $order->add_product($product, 1);
    $order->update_meta_data('UPayments_order_id', 'upgrade-provider-order');
    $order->update_meta_data('_upay_customer_unique_token', 'upgrade-customer-token');
    $order->update_meta_data('_upay_subscription_plan', 'monthly');
    $order->update_meta_data('_upay_subscription_interval', 1);
    $order->calculate_totals();
    $order->save();
    update_option($order_key, $order->get_id(), false);

    $cron = wp_next_scheduled('upay_process_subscriptions');
    simplixpay_cert_assert(is_int($cron) && $cron > 0, 'canonical subscription cron exists on existing active install');
    update_option($cron_key, $cron, false);

    simplixpay_upgrade_verify_data($settings_key, $settings_snapshot_key, $order_key, $cron_key);
    simplixpay_cert_note('existing active UPayments.php installation seeded');
    return;
}

if ('verify-active' === $phase) {
    simplixpay_upgrade_assert_active_contract($old_basename, $target_basename);
    simplixpay_upgrade_verify_data($settings_key, $settings_snapshot_key, $order_key, $cron_key);
    simplixpay_cert_note('same-basename upgrade/rollback active identity verified');
    return;
}

if ('verify-inactive' === $phase) {
    simplixpay_cert_assert(!is_plugin_active($old_basename), 'explicit deactivation removes transitional basename from active set');
    simplixpay_cert_assert(!is_plugin_active($target_basename), 'target renamed basename remains inactive');
    simplixpay_cert_assert(!class_exists('WC_Upayments', false), 'SimplixPay runtime is not loaded while explicitly inactive');
    simplixpay_upgrade_verify_data($settings_key, $settings_snapshot_key, $order_key, $cron_key);
    simplixpay_cert_note('deactivation preserves settings/order/cron state');
    return;
}

if ('verify-duplicate' === $phase) {
    simplixpay_upgrade_assert_active_contract($old_basename, $target_basename);
    simplixpay_upgrade_verify_data($settings_key, $settings_snapshot_key, $order_key, $cron_key);

    $plugins = get_plugins();
    simplixpay_cert_assert(isset($plugins[$old_basename]), 'canonical installed package is visible to WordPress');
    simplixpay_cert_assert(isset($plugins[$duplicate_basename]), 'duplicate-root package is visible as a distinct WordPress plugin');
    simplixpay_cert_assert(!is_plugin_active($duplicate_basename), 'duplicate-root package remains inactive');
    simplixpay_cert_assert(
        hash_file('sha256', WP_PLUGIN_DIR . '/simplixpay-upayments/UPayments.php')
            === hash_file('sha256', WP_PLUGIN_DIR . '/upayments-legacy/UPayments.php'),
        'duplicate package owns byte-identical historical bootstrap code under a distinct basename'
    );
    simplixpay_cert_assert(
        $plugins[$old_basename]['Name'] === $plugins[$duplicate_basename]['Name'],
        'duplicate package presents the same plugin identity under a distinct basename'
    );

    simplixpay_cert_note(
        'duplicate package basenames=' . wp_json_encode(array($old_basename, $duplicate_basename))
    );
    return;
}

if ('verify-renamed-red' === $phase) {
    simplixpay_upgrade_verify_data($settings_key, $settings_snapshot_key, $order_key, $cron_key);

    simplixpay_cert_assert(
        !file_exists(WP_PLUGIN_DIR . '/simplixpay-upayments/UPayments.php'),
        'hypothetical renamed package removed transitional main file'
    );
    simplixpay_cert_assert(
        file_exists(WP_PLUGIN_DIR . '/simplixpay-upayments/simplixpay-upayments.php'),
        'hypothetical renamed package contains target main file'
    );

    $active = simplixpay_upgrade_active_plugins();
    simplixpay_cert_note('renamed candidate active_plugins=' . wp_json_encode($active));
    simplixpay_cert_note(
        'renamed candidate old_active=' . (is_plugin_active($old_basename) ? 'yes' : 'no')
        . ' target_active=' . (is_plugin_active($target_basename) ? 'yes' : 'no')
        . ' runtime_loaded=' . (class_exists('WC_Upayments', false) ? 'yes' : 'no')
    );

    // RED migration-safety assertion: a safe physical rename would have to
    // transfer activation to the new exact plugin basename and load runtime
    // without merchant intervention. This is intentionally expected to fail
    // if WordPress preserves the historical active basename instead.
    simplixpay_cert_assert(
        is_plugin_active($target_basename) && class_exists('WC_Upayments', false),
        'RED: physical main-file migration transfers active identity to simplixpay-upayments.php without intervention'
    );
    return;
}

if ('verify-restored' === $phase) {
    simplixpay_upgrade_assert_active_contract($old_basename, $target_basename);
    simplixpay_upgrade_verify_data($settings_key, $settings_snapshot_key, $order_key, $cron_key);

    $recovery = get_option($recovery_key, '');
    simplixpay_cert_assert(
        in_array($recovery, array('yes', 'no'), true),
        'rename rollback recovery requirement was recorded'
    );
    simplixpay_cert_note('rename rollback required explicit reactivation=' . $recovery);
    return;
}

if ('cleanup' === $phase) {
    $order_id = (int) get_option($order_key);
    $order = wc_get_order($order_id);
    if ($order instanceof WC_Order) {
        $order->delete(true);
    }
    $product_id = (int) get_option($product_key);
    if ($product_id > 0) {
        wp_delete_post($product_id, true);
    }
    foreach (array(
        $settings_snapshot_key,
        $order_key,
        $product_key,
        $cron_key,
        $recovery_key,
    ) as $key) {
        delete_option($key);
    }
    simplixpay_cert_note('upgrade certification fixtures cleaned');
    return;
}

throw new RuntimeException('Unknown SIMPLIXPAY_UPGRADE_PHASE.');
