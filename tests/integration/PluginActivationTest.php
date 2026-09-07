<?php
/**
 * Real-runtime activation and Classic gateway certification.
 */

require_once __DIR__ . '/bootstrap.php';

$phase = getenv('SUCHECKOUT_CERT_PHASE');
$snapshot_key = '_sucheckout_certification_settings_snapshot';
$activation_marker = '<!-- sucheckout-activation-certification -->';

if ('seed' === $phase) {
    $sentinel = (object) array(
        'enabled'               => 'yes',
        'api_key'               => 'certification-sentinel',
        'enable_block_checkout' => 'no',
    );

    sucheckout_cert_store_option_raw('woocommerce_upayments_settings', $sentinel);

    $stored = get_option('woocommerce_upayments_settings');
    $serialized = maybe_serialize($stored);
    sucheckout_cert_assert(is_object($stored), 'malformed object-valued gateway settings are persisted before activation');
    sucheckout_cert_assert(isset($stored->api_key) && 'certification-sentinel' === $stored->api_key, 'pre-activation settings sentinel is exact');
    sucheckout_cert_assert(is_string($serialized) && '' !== $serialized, 'pre-activation protected settings serialize deterministically');
    sucheckout_cert_store_option_raw($snapshot_key, $serialized);

    $checkout_page_id = wc_get_page_id('checkout');
    sucheckout_cert_assert(is_int($checkout_page_id) && $checkout_page_id > 0, 'WooCommerce checkout page exists before SUCheckout activation');

    $updated = wp_update_post(
        array(
            'ID'           => $checkout_page_id,
            'post_content' => $activation_marker,
        ),
        true
    );
    sucheckout_cert_assert(!is_wp_error($updated) && (int) $updated === $checkout_page_id, 'checkout page is seeded with the activation marker');

    $checkout_page = get_post($checkout_page_id);
    sucheckout_cert_assert(
        $checkout_page instanceof WP_Post && $activation_marker === $checkout_page->post_content,
        'activation marker is exact before SUCheckout activation'
    );

    sucheckout_cert_note('activation seed complete');
    return;
}

if ('verify' === $phase) {
    $stored = get_option('woocommerce_upayments_settings');
    $snapshot = get_option($snapshot_key);

    sucheckout_cert_assert(is_object($stored), 'activation does not rewrite malformed persisted gateway settings');
    sucheckout_cert_assert(is_string($snapshot) && '' !== $snapshot, 'pre-activation protected settings snapshot is available');
    sucheckout_cert_assert(
        hash_equals($snapshot, maybe_serialize($stored)),
        'activation preserves the complete serialized protected settings option byte-for-byte'
    );
    sucheckout_cert_assert(class_exists('WooCommerce'), 'WooCommerce is active in the certification runtime');
    sucheckout_cert_assert(class_exists('WC_Upayments'), 'SUCheckout Classic gateway class loads in the real WooCommerce runtime');

    $checkout_page_id = wc_get_page_id('checkout');
    $checkout_page = $checkout_page_id > 0 ? get_post($checkout_page_id) : null;
    sucheckout_cert_assert(
        $checkout_page instanceof WP_Post && '[woocommerce_checkout]' === $checkout_page->post_content,
        'SUCheckout activation callback executed and replaced the marker with the Classic checkout shortcode'
    );

    $gateways = WC()->payment_gateways()->payment_gateways();
    sucheckout_cert_assert(is_array($gateways), 'WooCommerce returns a real payment gateway registry');
    sucheckout_cert_assert(isset($gateways['upayments']), 'Classic gateway ID upayments is registered');
    sucheckout_cert_assert($gateways['upayments'] instanceof WC_Upayments, 'registered Classic gateway is the SUCheckout gateway instance');

    sucheckout_cert_note('activation and Classic gateway certification complete');
    return;
}

throw new RuntimeException('Unknown SUCHECKOUT_CERT_PHASE.');
