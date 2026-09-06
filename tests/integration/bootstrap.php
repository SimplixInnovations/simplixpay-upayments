<?php
/**
 * SimplixPay real WordPress/WooCommerce certification assertions.
 */

if (!defined('ABSPATH')) {
    throw new RuntimeException('Integration assertions must run inside WordPress.');
}

/**
 * @param bool   $condition Assertion condition.
 * @param string $message   Failure message.
 * @return void
 */
function simplixpay_cert_assert($condition, $message) {
    if ($condition) {
        if (defined('WP_CLI') && WP_CLI) {
            WP_CLI::log('PASS: ' . $message);
        }
        return;
    }

    throw new RuntimeException('FAIL: ' . $message);
}

/**
 * @param string $message Evidence note.
 * @return void
 */
function simplixpay_cert_note($message) {
    if (defined('WP_CLI') && WP_CLI) {
        WP_CLI::log('CERT: ' . $message);
    }
}
