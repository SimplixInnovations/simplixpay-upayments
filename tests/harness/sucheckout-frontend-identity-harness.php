<?php
/**
 * SUCheckout first-party frontend identity contract.
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
$new_template = sufi_read($root, 'templates/new-design-form.php');
$old_template = sufi_read($root, 'templates/old-design-form.php');
$new_js = sufi_read($root, 'assets/js/new-upay.js');

foreach (array(
    'sucheckout-upayments-customer',
    'sucheckout-upayments-checkout-new-style',
    'sucheckout-upayments-checkout-new-script',
    'sucheckout-upayments-checkout-legacy-style',
    'sucheckout-upayments-checkout-legacy-script',
    'sucheckout-upayments-subscription-checkout',
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

sufi_assert(strpos($blocks, "'sucheckout-upayments-block-checkout'") !== false, 'Blocks script handle is SUCheckout-owned');
sufi_assert(strpos($blocks, "return [ 'sucheckout-upayments-block-checkout' ];") !== false, 'Blocks returns canonical SUCheckout script handle');

sufi_assert(strpos($new_template, 'sucheckout-upayments') !== false, 'new checkout template exposes canonical SUCheckout root');
sufi_assert(strpos($old_template, 'sucheckout-upayments') !== false, 'legacy checkout template exposes canonical SUCheckout root');

sufi_assert(strpos($new_js, 'window.suCheckoutUpayments') !== false, 'classic checkout JS exposes canonical SUCheckout namespace');
foreach (array('function submitUpayButton', 'function submitSavedCard', 'function toggleSaveCard', 'function showToast') as $global) {
    sufi_assert(strpos($new_js, $global) === false, 'classic checkout JS does not expose legacy generic global: ' . $global);
}
sufi_assert(strpos($new_template, 'suCheckoutUpayments.') !== false, 'new checkout template invokes canonical JS namespace');

sufi_assert(!is_file($root . '/assets/screenshots/7-Upayments-Payment- Interface-Form.png'), 'invalid screenshot filename with spaces is absent');
sufi_assert(is_file($root . '/assets/screenshots/7-Upayments-Payment-Interface-Form.png'), 'normalized screenshot filename is present');

echo "\nSUCheckout Frontend Identity: {$pass} PASS / {$fail} FAIL\n";
exit($fail === 0 ? 0 : 1);
