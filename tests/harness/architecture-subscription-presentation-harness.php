<?php

namespace UPayments\Subscription\Helpers {
    class Utils {
        public static $custom = false;
        public static $normal = false;
        public static function cartHasCustomType() { return self::$custom; }
        public static function cartHasNormalProduct() { return self::$normal; }
    }
}

namespace UPayments\Subscription\Cron {
    class Scheduler {
        public static function getNextBillingDate($started_at, $plan, $interval) {
            return new \DateTime('2030-02-03 04:05:06', new \DateTimeZone('UTC'));
        }
    }
}

namespace {
use Simplix\Pay\UPayments\Subscription\Composition;
use Simplix\Pay\UPayments\Subscription\Presentation;
use UPayments\Subscription\Helpers\Utils;

$a4_hooks = array();
$a4_meta = array();
$a4_notices = array();
$a4_nonce_valid = false;
$a4_can_edit = false;
$a4_current_user = 7;
$a4_product = null;
$a4_wc = (object) array('cart' => (object) array());

class WooCommerce {}
class WC_Product {
    public $type = 'simple';
    public $id = 12;
    public function get_type() { return $this->type; }
    public function is_type($type) { return $this->type === $type; }
    public function get_id() { return $this->id; }
}
class WC_Product_Simple extends WC_Product {}
class WC_Order {}

function __($text, $domain = null) { return $text; }
function esc_html($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function esc_attr($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function esc_url($value) { return esc_attr($value); }
function esc_js($value) { return addslashes((string) $value); }
function esc_html_e($text, $domain = null) { echo esc_html($text); }
function wp_kses_post($value) { return (string) $value; }
function sanitize_text_field($value) { return trim(strip_tags((string) $value)); }
function wp_unslash($value) { return $value; }
function absint($value) { return abs((int) $value); }
function wp_verify_nonce($nonce, $action) { global $a4_nonce_valid; return $a4_nonce_valid; }
function current_user_can($capability, $post_id = null) { global $a4_can_edit; return $a4_can_edit; }
function update_post_meta($post_id, $key, $value) { global $a4_meta; $a4_meta[] = array($post_id, $key, $value); }
function get_post_meta($post_id, $key, $single = false) { global $a4_meta_values; return isset($a4_meta_values[$post_id][$key]) ? $a4_meta_values[$post_id][$key] : ''; }
function get_post_type() { return 'product'; }
function woocommerce_wp_text_input($args) { echo 'FIELD:' . esc_attr(json_encode($args)); }
function add_action($hook, $callback, $priority = 10, $accepted_args = 1) { global $a4_hooks; $a4_hooks[] = array('action', $hook, $callback, $priority, $accepted_args); }
function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) { global $a4_hooks; $a4_hooks[] = array('filter', $hook, $callback, $priority, $accepted_args); }
function WC() { global $a4_wc; return $a4_wc; }
function wc_get_product($product_id) { global $a4_product; return $a4_product; }
function wc_add_notice($message, $type) { global $a4_notices; $a4_notices[] = array($message, $type); }
function is_user_logged_in() { return true; }
function get_current_user_id() { global $a4_current_user; return $a4_current_user; }
function wp_timezone() { return new DateTimeZone('UTC'); }
function wc_get_account_endpoint_url($endpoint) { return 'https://example.test/account/' . $endpoint . '/'; }
function wp_nonce_field($action, $name, $referer = true) { echo 'NONCE:' . esc_html($action) . ':' . esc_html($name); }
function add_query_arg($key = null, $value = null) { return 'https://example.test/account/orders/'; }
function selected($selected, $current = true, $echo = true) { $out = (string) $selected === (string) $current ? ' selected="selected"' : ''; if ($echo) { echo $out; } return $out; }

require_once dirname(__DIR__, 2) . '/src/Subscription/Presentation.php';
require_once dirname(__DIR__, 2) . '/src/Subscription/Composition.php';

$pass = 0;
$fail = 0;
function a4_assert($condition, $message) { global $pass, $fail; if ($condition) { $pass++; echo "PASS: {$message}\n"; } else { $fail++; echo "FAIL: {$message}\n"; } }
function a4_same($expected, $actual, $message) { a4_assert($expected === $actual, $message); }
function a4_output($callback) { ob_start(); call_user_func($callback); return ob_get_clean(); }
function a4_git_blob_sha($path) { $bytes = file_get_contents($path); return sha1('blob ' . strlen($bytes) . "\0" . $bytes); }

// Exact hook topology is owned by one composition boundary.
Composition::register_presentation_hooks();
$p = Presentation::class;
$expected_hooks = array(
    array('action', 'init', array($p, 'register_product_class'), 10, 1),
    array('filter', 'product_type_selector', 'addCustomProductType', 10, 1),
    array('filter', 'woocommerce_product_class', 'mapCustomProductClass', 10, 2),
    array('action', 'woocommerce_custom_type_add_to_cart', 'woocommerce_simple_add_to_cart', 30, 1),
    array('action', 'admin_footer', 'customProductTypes', 10, 1),
    array('filter', 'woocommerce_product_data_tabs', 'addCustomDataTab', 10, 1),
    array('action', 'woocommerce_product_data_panels', 'addCustomDataPanel', 10, 1),
    array('action', 'woocommerce_process_product_meta', 'saveCustomFieldData', 10, 1),
    array('action', 'woocommerce_single_product_summary', 'displayCustomFieldOnFrontend', 10, 1),
    array('filter', 'woocommerce_get_item_data', 'displayCustomDataInCart', 10, 2),
    array('action', 'woocommerce_checkout_create_order_line_item', 'saveCustomDataToOrderItems', 10, 4),
    array('action', 'woocommerce_order_details_after_order_table', array($p, 'render_account_order_details'), 10, 1),
    array('action', 'woocommerce_before_account_orders', array($p, 'render_account_orders_filter'), 10, 1),
    array('filter', 'woocommerce_my_account_my_orders_query', array($p, 'filter_account_orders_query'), 10, 1),
    array('filter', 'woocommerce_my_account_my_orders_columns', array($p, 'filter_account_orders_columns'), 10, 1),
    array('action', 'woocommerce_my_account_my_orders_column_order_type', array($p, 'render_account_order_type'), 10, 1),
    array('action', 'woocommerce_my_account_my_orders_column_order_status', array($p, 'render_account_subscription_status'), 10, 1),
    array('action', 'woocommerce_admin_order_data_after_billing_address', array($p, 'render_admin_order_summary'), 10, 1),
);
a4_same($expected_hooks, $a4_hooks, 'complete product/account/admin presentation hook topology is frozen');

// Product type and admin schema remain exact.
Presentation::register_product_class();
a4_assert(class_exists('WCProductCustomType'), 'legacy global custom product class remains loadable');
a4_same('custom_type', (new WCProductCustomType())->get_type(), 'legacy custom product type identity is preserved');
a4_same(array('simple' => 'Simple', 'custom_type' => 'Subscription Product'), Presentation::add_custom_product_type(array('simple' => 'Simple')), 'product selector label and key are preserved');
a4_same('WCProductCustomType', Presentation::map_custom_product_class('WC_Product_Simple', 'custom_type'), 'custom product class mapping is preserved');
a4_same('OtherClass', Presentation::map_custom_product_class('OtherClass', 'simple'), 'unrelated product classes remain unchanged');
a4_same(array(
    'label' => 'Custom Settings', 'target' => 'custom_product_data_panel',
    'class' => array('show_if_custom_type'), 'priority' => 25,
), Presentation::add_custom_data_tab(array())['custom_settings'], 'complete custom product tab schema is preserved');

// Product-meta authorization stays fail-closed and retains the exact identity.
$_POST = array('woocommerce_meta_nonce' => 'n', 'post_ID' => '12', '_custom_field_id' => ' <b>value</b> ');
$a4_meta = array(); $a4_nonce_valid = false; $a4_can_edit = true;
Presentation::save_custom_field_data(12);
a4_same(array(), $a4_meta, 'invalid product nonce performs no write');
$a4_nonce_valid = true; $a4_can_edit = false;
Presentation::save_custom_field_data(12);
a4_same(array(), $a4_meta, 'missing edit_post authorization performs no write');
$a4_can_edit = true; $_POST['post_ID'] = '13';
Presentation::save_custom_field_data(12);
a4_same(array(), $a4_meta, 'posted product mismatch performs no write');
$_POST['post_ID'] = '12';
Presentation::save_custom_field_data(12);
a4_same(array(array(12, '_custom_field_id', 'value')), $a4_meta, 'authorized product meta preserves key and sanitized value');

// Product/cart/order-item presentation preserves the historical meta surface.
$a4_meta_values = array(12 => array('_custom_field_id' => '<b>Gold</b>'));
$product = new WC_Product(); $product->type = 'custom_type';
$html = a4_output(array(Presentation::class, 'display_custom_field_on_frontend'));
a4_assert(strpos($html, '&lt;b&gt;Gold&lt;/b&gt;') !== false && strpos($html, '<b>Gold</b>') === false, 'product custom value remains HTML-escaped');
$item_data = Presentation::display_custom_data_in_cart(array(), array('product_id' => 12));
a4_same(array(array('key' => 'Special Feature', 'value' => '<b>Gold</b>', 'display' => '')), $item_data, 'cart presentation retains exact label/value shape');

// My Account filters/columns and outputs retain their established identities.
$_GET = array();
a4_same(array('limit' => 10), Presentation::filter_account_orders_query(array('limit' => 10)), 'empty account filter leaves query unchanged');
$_GET['subscription_filter'] = '<b>paused</b>';
a4_same(array('meta_query' => array(array('key' => '_upay_subscription_status', 'value' => 'paused'))), Presentation::filter_account_orders_query(array()), 'account filter retains sanitized subscription-status meta query');
$columns = Presentation::filter_account_orders_columns(array('order-number' => 'Order', 'order-status' => 'Woo Status', 'order-total' => 'Total'));
a4_same(array('order-number' => 'Order', 'order-status' => 'Woo Status', 'order_type' => 'Type', 'order_status' => 'Status', 'order-total' => 'Total'), $columns, 'account Type/Status columns retain exact insertion order');

$regular = new class extends WC_Order { public function get_meta($key) { return $key === 'UPayments_AutoDeduction' ? 'no' : ''; } };
$auto = new class extends WC_Order { public function get_meta($key) { return $key === 'UPayments_AutoDeduction' ? 'yes' : ''; } };
a4_same('Regular', a4_output(function () use ($regular) { Presentation::render_account_order_type($regular); }), 'manual subscription type label is preserved');
a4_same('Auto Deduction', a4_output(function () use ($auto) { Presentation::render_account_order_type($auto); }), 'auto-deduction type label is preserved');
$hostile = new class extends WC_Order { public function get_meta($key) { return $key === '_upay_subscription_status' ? 'active"><script>x</script>' : ''; } };
$status_html = a4_output(function () use ($hostile) { Presentation::render_account_subscription_status($hostile); });
a4_assert(strpos($status_html, '<script>') === false && strpos($status_html, '&lt;script&gt;') !== false, 'account subscription status remains escaped in class and text contexts');

// Gateway compatibility wrappers and high-risk boundaries remain in place.
$root = dirname(__DIR__, 2);
$gateway = file_get_contents($root . '/UPayments.php');
$composition = file_get_contents($root . '/src/Subscription/Composition.php');
$presentation = file_get_contents($root . '/src/Subscription/Presentation.php');
foreach (array('initializeSubscriptionModule', 'render_subscription_summary', 'restrictMixedCartProducts', 'renderSubscriptionBadgeInProductList') as $method) {
    a4_assert((bool) preg_match('/public\s+function\s+' . preg_quote($method, '/') . '\s*\(/', $gateway), "legacy public {$method} seam remains callable");
}
foreach (array('addCustomProductType', 'mapCustomProductClass', 'customProductTypes', 'addCustomDataTab', 'addCustomDataPanel', 'saveCustomFieldData', 'displayCustomFieldOnFrontend', 'displayCustomDataInCart', 'saveCustomDataToOrderItems') as $function) {
    a4_assert((bool) preg_match('/function\s+' . preg_quote($function, '/') . '\s*\(/', $gateway), "legacy global {$function} compatibility seam remains callable");
}
a4_assert(strpos($gateway, 'SubscriptionComposition::register_presentation_hooks();') !== false, 'main bootstrap activates the subscription composition boundary');
a4_assert(strpos($gateway, 'SubscriptionComposition::initialize_legacy_modules();') !== false, 'public subscription initializer delegates legacy checkout/storage composition');
a4_assert(strpos($gateway, 'SubscriptionPresentation::render_admin_summary($order);') !== false, 'admin summary wrapper delegates presentation');
a4_assert(strpos($gateway, "add_action('woocommerce_init', function ()") !== false && strpos($gateway, 'Scheduler::init();') !== false, 'protected scheduler bootstrap remains outside presentation boundary');
a4_assert(strpos($gateway, "update_meta_data('_upay_subscription_status', 'cancelled')") !== false, 'hardened customer mutation handler remains outside presentation boundary');
foreach (array('process_payment', 'auto-deduct', 'upay_process_subscriptions', 'upayments_billing_attempts', 'CURLOPT_', "update_meta_data('_upay_subscription_status'") as $forbidden) {
    a4_assert(strpos($presentation, $forbidden) === false, "presentation module excludes {$forbidden} ownership");
}
a4_assert(strpos($composition, 'register_presentation_hooks') !== false && strpos($composition, 'register_gateway_hooks') !== false, 'composition explicitly owns global and gateway hook registration');

// Exact protected scheduler/cycle-claim blobs remain unchanged from H12.
a4_same('5251866d4df2d1326e7c09f0c8ec1d146c0bb325', a4_git_blob_sha($root . '/includes/Subscription/Cron/Scheduler.php'), 'protected Scheduler blob remains exact');
a4_same('c34d83e2d77cc65024fe663e4c378cecb2b17347', a4_git_blob_sha($root . '/includes/Subscription/Cron/CycleClaim.php'), 'protected CycleClaim blob remains exact');

echo "\nArchitecture Subscription Presentation: {$pass} PASS / {$fail} FAIL\n";
exit($fail === 0 ? 0 : 1);
}
