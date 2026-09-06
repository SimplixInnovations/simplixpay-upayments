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

simplixpay_cert_note('release support metadata certification complete');
