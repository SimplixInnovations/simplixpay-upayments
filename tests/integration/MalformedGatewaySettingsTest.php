<?php
/**
 * Real-runtime regression for the malformed gateway-settings fault-tolerance boundary.
 *
 * Reproduces the B-X1 defect class against the live woocommerce_available_payment_gateways
 * filter callback enableUpaymentsGateway():
 *
 *   Case 1 — stdClass settings: an object-valued woocommerce_upayments_settings
 *            (the shape PluginActivationTest deliberately seeds) must not crash
 *            the front-end; the gateway is removed from the available set.
 *   Case 2 — scalar/otherwise malformed option (string, int, bool, missing):
 *            the front-end must not crash; the gateway is removed from the
 *            available set.
 *   Case 3 — valid array settings regression: a fully configured array with
 *            api_key and make_default_gateway=yes keeps the gateway available
 *            and preserves the chosen payment method.
 *
 * The persisted option is intentionally NOT rewritten by this test. SUPCheckout
 * tolerates the persisted shape and reads defensively.
 *
 * Note on Case 3 cod-removal: the enable_autodeduction branch is gated by
 * is_checkout(), which is true only on a real /checkout request and is not
 * satisfiable inside wp eval-file. The cod-removal behaviour is covered by
 * the existing checkout / B6 acceptance evidence and the legacy
 * enableUpaymentsGateway() filter's static-analysis harness; this test
 * exercises the make_default_gateway branch (which is NOT gated by
 * is_checkout) and the api_key preservation (which is unconditional).
 */

require_once __DIR__ . '/bootstrap.php';

if (!defined('ABSPATH')) {
    throw new RuntimeException('Integration assertions must run inside WordPress.');
}

if (!function_exists('enableUpaymentsGateway')) {
    throw new RuntimeException('FAIL: enableUpaymentsGateway is not registered in the real runtime.');
}

$phase = getenv('SUPCHECKOUT_CERT_PHASE');
if (!in_array($phase, array('seed', 'verify'), true)) {
    throw new RuntimeException('Unknown SUPCHECKOUT_CERT_PHASE.');
}

$settings_key = 'woocommerce_upayments_settings';
$original_settings = get_option($settings_key);

/**
 * Build the available-gateways payload the WooCommerce front-end would pass
 * to the enableUpaymentsGateway filter. We inject an inert WC_UPayments
 * stub so the filter receives a real "upayments" key without requiring the
 * full gateway bootstrap.
 *
 * @param bool $with_cod Include a "cod" sibling so the autodeduction branch
 *                       is reachable when is_checkout() is true.
 * @return array<string,object>
 */
function supcheckout_malformed_test_gateways($with_cod = false) {
    $gateways = array(
        'upayments' => (object) array(
            'id'           => 'upayments',
            'enabled'      => 'yes',
            'title'        => 'UPayments',
            'method_title' => 'UPayments',
        ),
    );
    if ($with_cod) {
        $gateways['cod'] = (object) array(
            'id'           => 'cod',
            'enabled'      => 'yes',
            'title'        => 'Cash on delivery',
            'method_title' => 'Cash on delivery',
        );
    }
    return $gateways;
}

/**
 * Run the available-gateways filter exactly once and capture any PHP error
 * emitted. Errors are converted into RuntimeException so a TypeError,
 * Warning or Notice surfaces as a hard fail rather than being swallowed.
 *
 * @param array<string,object> $gateways Input gateways payload.
 * @return array<string,object>
 */
function supcheckout_malformed_test_apply_filter($gateways) {
    set_error_handler(static function ($severity, $message, $file, $line) {
        if (!(error_reporting() & $severity)) {
            return false;
        }
        throw new RuntimeException(
            sprintf('PHP error during filter application: [%d] %s in %s:%d', $severity, $message, $file, $line)
        );
    });
    try {
        $result = apply_filters('woocommerce_available_payment_gateways', $gateways);
    } finally {
        restore_error_handler();
    }
    if (!is_array($result)) {
        throw new RuntimeException('FAIL: filter did not return an array.');
    }
    return $result;
}

/**
 * Delete an option row directly and clear the WP options caches so the next
 * get_option() returns the legacy default `false` (NOT NULL, since
 * wp_options.option_value is NOT NULL).
 *
 * @param string $name Option name to delete.
 * @return void
 */
function supcheckout_malformed_test_delete_option_raw($name) {
    global $wpdb;
    $deleted = $wpdb->delete($wpdb->options, array('option_name' => $name), array('%s'));
    supcheckout_cert_assert(false !== $deleted, 'raw certification option delete succeeds: ' . $name);
    wp_cache_delete($name, 'options');
    wp_cache_delete('alloptions', 'options');
    $notoptions = wp_cache_get('notoptions', 'options');
    if (is_array($notoptions) && isset($notoptions[$name])) {
        unset($notoptions[$name]);
        wp_cache_set('notoptions', $notoptions, 'options');
    }
}

if ('seed' === $phase) {
    // Case 1 — stdClass settings (mirrors the existing PluginActivationTest
    // sentinel shape, but explicitly includes the surface fields used by the
    // availability filter).
    $std_class_settings = (object) array(
        'enabled'               => 'yes',
        'api_key'               => 'certification-sentinel-object',
        'enable_block_checkout' => 'no',
    );
    supcheckout_cert_store_option_raw($settings_key, $std_class_settings);

    // Case 2 — scalar/otherwise malformed option.
    supcheckout_cert_store_option_raw($settings_key . '__scalar', 'not-an-array-sentinel');
    supcheckout_cert_store_option_raw($settings_key . '__int', 42);
    supcheckout_cert_store_option_raw($settings_key . '__bool', true);

    // Case 2d — missing option. The legacy get_option() default for a missing
    // option is `false`, which is also not an array. We model that state by
    // deleting the option row directly (wp_options.option_value is NOT NULL,
    // so a literal NULL cannot be stored). The fix's is_array($settings)
    // guard must also tolerate this default-false state.
    supcheckout_malformed_test_delete_option_raw($settings_key . '__missing');

    // Case 3 — valid array settings regression.
    supcheckout_cert_store_option_raw(
        $settings_key . '__valid',
        array(
            'enabled'              => 'yes',
            'api_key'              => 'certification-valid-array',
            'enable_autodeduction' => 'yes',
            'make_default_gateway' => 'yes',
        )
    );

    supcheckout_cert_note('malformed-gateway-settings regression seed complete');
    return;
}

// Verify phase.

/**
 * @param string $name      Sub-test label.
 * @param mixed  $payload   Settings payload to persist (or null to delete).
 * @param bool   $delete    If true, delete the option row instead of storing.
 * @param bool   $expect_upayments Whether 'upayments' must remain available.
 * @return void
 */
function supcheckout_malformed_test_assert_availability($name, $payload, $delete, $expect_upayments) {
    if ($delete) {
        supcheckout_malformed_test_delete_option_raw('woocommerce_upayments_settings');
    } else {
        supcheckout_cert_store_option_raw('woocommerce_upayments_settings', $payload);
    }
    $result = supcheckout_malformed_test_apply_filter(supcheckout_malformed_test_gateways(true));
    $has_upayments = array_key_exists('upayments', $result);
    supcheckout_cert_assert(
        $has_upayments === $expect_upayments,
        sprintf(
            '%s (expected %s, got %s)',
            $name,
            $expect_upayments ? 'present' : 'removed',
            $has_upayments ? 'present' : 'removed'
        )
    );
}

// Case 1 — stdClass settings.
// Pre-fix this case throws a fatal "Cannot use object of type stdClass as array"
// on every non-admin front-end request when woocommerce_upayments_settings is a
// stdClass (the shape PluginActivationTest deliberately seeds). The fix must
// remove 'upayments' from the available set without crashing.
supcheckout_malformed_test_assert_availability(
    'stdClass settings fail closed without crashing the front-end',
    (object) array(
        'enabled'               => 'yes',
        'api_key'               => 'certification-sentinel-object',
        'enable_block_checkout' => 'no',
    ),
    false,
    false
);

// Case 2 — scalar string.
supcheckout_malformed_test_assert_availability(
    'scalar string settings fail closed without crashing the front-end',
    'not-an-array-sentinel',
    false,
    false
);

// Case 2b — integer.
supcheckout_malformed_test_assert_availability(
    'integer settings fail closed without crashing the front-end',
    42,
    false,
    false
);

// Case 2c — boolean.
supcheckout_malformed_test_assert_availability(
    'boolean settings fail closed without crashing the front-end',
    true,
    false,
    false
);

// Case 2d — missing option (legacy default `false`).
supcheckout_malformed_test_assert_availability(
    'missing-option default-false state fails closed without crashing the front-end',
    null,
    true,
    false
);

// Case 3 — valid array settings regression.
// A fully configured array with api_key set must keep 'upayments' available.
// The make_default_gateway=yes branch is NOT gated by is_checkout(), so it
// is exercisable inside wp eval-file. With make_default_gateway=yes and the
// chosen payment method set to 'upayments', the filter must preserve the
// chosen method.
supcheckout_cert_store_option_raw(
    'woocommerce_upayments_settings',
    array(
        'enabled'              => 'yes',
        'api_key'              => 'certification-valid-array',
        'enable_autodeduction' => 'yes',
        'make_default_gateway' => 'yes',
    )
);

// Boot a WC session so the chosen-payment-method branch is reachable.
if (!WC()->session) {
    WC()->session = new WC_Session_Handler();
    WC()->session->init();
}
WC()->session->set('chosen_payment_method', 'upayments');

$valid_result = supcheckout_malformed_test_apply_filter(supcheckout_malformed_test_gateways(true));
supcheckout_cert_assert(
    array_key_exists('upayments', $valid_result),
    'valid array settings keep upayments available'
);
supcheckout_cert_assert(
    'upayments' === WC()->session->get('chosen_payment_method'),
    'make_default_gateway=yes still preserves the chosen payment method for valid array settings'
);

// Restore the original settings (test must not pollute the option).
supcheckout_cert_store_option_raw($settings_key, $original_settings);

supcheckout_cert_note('malformed-gateway-settings regression certification complete');
