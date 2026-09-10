<?php
/**
 * Real-runtime gateway presentation checkbox certification.
 *
 * Characterizes WooCommerce checkbox semantics at the gateway/template seam
 * without executing provider transport: wc_get_template is redirected to a
 * test-only fixture that records the selected template and extracted argument.
 */

require_once __DIR__ . '/bootstrap.php';

$settings_key = 'woocommerce_upayments_settings';
$settings_before_probe = get_option($settings_key);
$fixture = __DIR__ . '/fixtures/payment-fields-probe.php';

supcheckout_cert_assert(is_file($fixture), 'payment-fields probe fixture exists');

add_filter(
    'wc_get_template',
    function ($template, $template_name) use ($fixture) {
        if ($template_name === 'new-design-form.php' || $template_name === 'old-design-form.php') {
            $GLOBALS['supcheckout_payment_fields_probe']['template'] = $template_name;
            return $fixture;
        }
        return $template;
    },
    999,
    2
);

$gateway = new WC_Upayments();

$cases = array(
    'explicit-enabled' => array(
        'settings' => array('enable_save_card' => 'yes', 'use_new_design' => 'yes'),
        'template' => 'new-design-form.php',
        'save_card_enabled' => true,
    ),
    'explicit-disabled' => array(
        'settings' => array('enable_save_card' => 'no', 'use_new_design' => 'no'),
        'template' => 'old-design-form.php',
        'save_card_enabled' => false,
    ),
    'missing-preserves-declared-defaults' => array(
        'settings' => array(),
        'template' => 'new-design-form.php',
        'save_card_enabled' => true,
    ),
    'malformed-boolean-true' => array(
        'settings' => array('enable_save_card' => true, 'use_new_design' => true),
        'template' => 'old-design-form.php',
        'save_card_enabled' => false,
    ),
    'malformed-boolean-false' => array(
        'settings' => array('enable_save_card' => false, 'use_new_design' => false),
        'template' => 'old-design-form.php',
        'save_card_enabled' => false,
    ),
    'malformed-string-one' => array(
        'settings' => array('enable_save_card' => '1', 'use_new_design' => '1'),
        'template' => 'old-design-form.php',
        'save_card_enabled' => false,
    ),
    'malformed-integer-one' => array(
        'settings' => array('enable_save_card' => 1, 'use_new_design' => 1),
        'template' => 'old-design-form.php',
        'save_card_enabled' => false,
    ),
    'malformed-blank' => array(
        'settings' => array('enable_save_card' => '', 'use_new_design' => ''),
        'template' => 'old-design-form.php',
        'save_card_enabled' => false,
    ),
);

foreach ($cases as $name => $case) {
    $gateway->settings = $case['settings'];
    $GLOBALS['supcheckout_payment_fields_probe'] = array();

    ob_start();
    $gateway->payment_fields();
    ob_end_clean();

    supcheckout_cert_assert(
        isset($GLOBALS['supcheckout_payment_fields_probe']['template'])
            && $GLOBALS['supcheckout_payment_fields_probe']['template'] === $case['template'],
        'payment template selection uses exact checkbox semantics for case ' . $name
    );
    supcheckout_cert_assert(
        array_key_exists('save_card_enabled', $GLOBALS['supcheckout_payment_fields_probe'])
            && $GLOBALS['supcheckout_payment_fields_probe']['save_card_enabled'] === $case['save_card_enabled'],
        'save-card template argument uses exact checkbox semantics for case ' . $name
    );
}

remove_all_filters('wc_get_template');
supcheckout_cert_store_option_raw($settings_key, $settings_before_probe);
unset($GLOBALS['supcheckout_payment_fields_probe']);

supcheckout_cert_note('gateway presentation checkbox certification complete');
