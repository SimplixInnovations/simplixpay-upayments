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

/**
 * Persist an option without firing update_option hooks.
 *
 * This is used only to characterize malformed storage that may already exist
 * before WooCommerce/SimplixPay boots. It intentionally bypasses observers so
 * the certification target is the plugin's read boundary, not Woo's settings
 * change hook.
 *
 * @param string $name  Option name.
 * @param mixed  $value Raw option value.
 * @return void
 */
function simplixpay_cert_store_option_raw($name, $value) {
    global $wpdb;

    $exists = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT option_id FROM {$wpdb->options} WHERE option_name = %s",
            $name
        )
    );

    $serialized = maybe_serialize($value);

    if (null === $exists) {
        $result = $wpdb->insert(
            $wpdb->options,
            array(
                'option_name'  => $name,
                'option_value' => $serialized,
                'autoload'     => 'no',
            ),
            array('%s', '%s', '%s')
        );
    } else {
        $result = $wpdb->update(
            $wpdb->options,
            array('option_value' => $serialized),
            array('option_name' => $name),
            array('%s'),
            array('%s')
        );
    }

    simplixpay_cert_assert(false !== $result, 'raw certification option persistence succeeds: ' . $name);
    wp_cache_delete($name, 'options');
    wp_cache_delete('alloptions', 'options');

    // Direct SQL intentionally bypasses update_option(), so WordPress does not
    // remove a previously cached "option does not exist" entry for us. This
    // matters when a real plugin has already read a missing option earlier in
    // the request (for example an active gateway reading fresh-install
    // settings). Clear that negative-cache entry explicitly so the raw fixture
    // becomes observable immediately without relying on another request.
    $notoptions = wp_cache_get('notoptions', 'options');
    if (is_array($notoptions) && isset($notoptions[$name])) {
        unset($notoptions[$name]);
        wp_cache_set('notoptions', $notoptions, 'options');
    }
}
