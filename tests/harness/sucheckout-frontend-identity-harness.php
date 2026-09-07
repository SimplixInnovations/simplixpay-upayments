<?php
/**
 * SUPCheckout first-party frontend identity contract.
 *
 * Provider/Woo compatibility IDs such as "upayments" are intentionally not
 * banned globally. This harness owns only first-party handles, DOM roots,
 * callable JS namespace, and release-facing asset names.
 */

$root = dirname(__DIR__, 2);
$pass = 0;
$fail = 0;

function sufi_assert($condition, $message) {
    global $pass, $fail;
    if ($condition) {
        ++$pass;
        echo "PASS: {$message}\n";
        return;
    }
    ++$fail;
    echo "FAIL: {$message}\n";
}

function sufi_read($root, $path) {
    $value = @file_get_contents($root . '/' . $path);
    return is_string($value) ? $value : '';
}

$gateway = sufi_read($root, 'UPayments.php');
$blocks = sufi_read($root, 'includes/class-wc-gateway-upayments-blocks.php');
$settings = sufi_read($root, 'src/Admin/GatewaySettings.php');
$new_template = sufi_read($root, 'templates/new-design-form.php');
$old_template = sufi_read($root, 'templates/old-design-form.php');
$new_js = sufi_read($root, 'assets/js/new-upay.js');

foreach (array(
    'supcheckout-customer',
    'supcheckout-checkout-new-style',
    'supcheckout-checkout-new-script',
    'supcheckout-checkout-legacy-script',
    'supcheckout-subscription-checkout',
) as $handle) {
    sufi_assert(strpos($gateway, "'" . $handle . "'") !== false, 'canonical first-party enqueue handle exists: ' . $handle);
}

foreach (array(
    'customer-new-style',
    'custom-checkout-new-style',
    'custom-checkout-script',
    'custom-checkout-old-style',
    'custom-checkout-old-script',
    'upayments-subscription-checkout',
) as $retired) {
    sufi_assert(strpos($gateway, "'" . $retired . "'") === false, 'retired/generic first-party enqueue handle absent: ' . $retired);
}

sufi_assert(strpos($blocks, "'supcheckout-block-checkout'") !== false, 'Blocks script handle is SUPCheckout-owned');
sufi_assert(strpos($blocks, "return [ 'supcheckout-block-checkout' ];") !== false, 'Blocks returns canonical SUPCheckout script handle');

sufi_assert(strpos($gateway, "'supcheckout-checkout-legacy-style'") === false, 'empty legacy stylesheet handle is absent');
sufi_assert(strpos($gateway, 'assets/css/old-design.css') === false, 'empty legacy stylesheet is not enqueued');
sufi_assert(strpos($gateway, 'includes/admin-footer.php') === false, 'empty admin-footer include is not registered');
sufi_assert(strpos($gateway, 'your-gateway-core') === false, 'nonexistent script handle is not localized');

foreach (array(
    'assets/css/old-design.css',
    'includes/admin-footer.php',
    'assets/js/upayments-blocks-integration.js',
    'assets/js/upayments-thankyou.js',
    'assets/js/checkout/constants.js',
    'assets/js/checkout/data.js',
    'assets/images/disabled.gif',
) as $dead_asset) {
    sufi_assert(!is_file($root . '/' . $dead_asset), 'proven dead runtime asset is absent: ' . $dead_asset);
}

foreach (array(
    'UPayments.php' => $gateway,
    'src/Admin/GatewaySettings.php' => $settings,
    'includes/class-wc-gateway-upayments-blocks.php' => $blocks,
) as $path => $source) {
    sufi_assert(strpos($source, "'3.0.0'") === false, 'first-party asset cache version is not frozen in ' . $path);
}
sufi_assert(strpos($gateway, 'SUPCHECKOUT_VERSION') !== false, 'classic checkout assets bind to canonical SUPCheckout version');
sufi_assert(strpos($settings, '\\Simplixi\\SUPCheckout\\Release\\Identity::VERSION') !== false, 'admin assets bind to canonical SUPCheckout release identity');
sufi_assert(strpos($blocks, '\\Simplixi\\SUPCheckout\\Release\\Identity::VERSION') !== false, 'Blocks asset binds to canonical SUPCheckout release identity');

sufi_assert(strpos($new_template, 'supcheckout') !== false, 'new checkout template exposes canonical SUPCheckout root');
sufi_assert(strpos($old_template, 'supcheckout') !== false, 'legacy checkout template exposes canonical SUPCheckout root');

sufi_assert(strpos($new_js, 'window.supCheckout') !== false, 'classic checkout JS exposes canonical SUPCheckout namespace');
foreach (array('function submitUpayButton', 'function submitSavedCard', 'function toggleSaveCard', 'function showToast') as $global) {
    sufi_assert(strpos($new_js, $global) === false, 'classic checkout JS does not expose legacy generic global: ' . $global);
}
sufi_assert(strpos($new_template, 'supCheckout.') !== false, 'new checkout template invokes canonical JS namespace');

sufi_assert(!is_file($root . '/assets/screenshots/7-Upayments-Payment- Interface-Form.png'), 'invalid screenshot filename with spaces is absent');
sufi_assert(is_file($root . '/assets/screenshots/7-Upayments-Payment-Interface-Form.png'), 'normalized screenshot filename is present');

echo "\nSUPCheckout Frontend Identity: {$pass} PASS / {$fail} FAIL\n";
exit($fail === 0 ? 0 : 1);
