<?php
/**
 * Q19 Subscription Product Eligibility Consistency.
 *
 * Permanent behavior guard for explicit product-level subscription opt-out.
 */

define('ABSPATH', __DIR__ . '/');

final class Q19Cart {
    private $items;

    public function __construct($items) {
        $this->items = $items;
    }

    public function get_cart() {
        return $this->items;
    }
}

final class Q19Woo {
    public $cart;

    public function __construct($items) {
        $this->cart = new Q19Cart($items);
    }
}

$GLOBALS['q19_items'] = array();
$GLOBALS['q19_meta'] = array();

function WC() {
    return new Q19Woo($GLOBALS['q19_items']);
}

function get_post_meta($product_id, $key, $single = false) {
    return isset($GLOBALS['q19_meta'][$product_id][$key])
        ? $GLOBALS['q19_meta'][$product_id][$key]
        : '';
}

require_once dirname(__DIR__, 2) . '/includes/Subscription/Helpers/Utils.php';

$pass = 0;
$fail = 0;

function q19_assert($condition, $description) {
    global $pass, $fail;
    if ($condition) {
        $pass++;
        echo "PASS: " . $description . "\n";
    } else {
        $fail++;
        echo "FAIL: " . $description . "\n";
    }
}

function q19_set_cart($product_ids) {
    $GLOBALS['q19_items'] = array();
    foreach ($product_ids as $product_id) {
        $GLOBALS['q19_items'][] = array('product_id' => $product_id);
    }
}

use UPayments\Subscription\Helpers\Utils;

q19_set_cart(array());
$GLOBALS['q19_meta'] = array();
q19_assert(Utils::cartHasRestrictedProducts() === false, 'empty cart is not restricted');

q19_set_cart(array(123));
$GLOBALS['q19_meta'] = array();
q19_assert(Utils::cartHasRestrictedProducts() === false, 'ordinary product ID 123 is eligible without explicit opt-out');

q19_set_cart(array(456));
$GLOBALS['q19_meta'] = array();
q19_assert(Utils::cartHasRestrictedProducts() === false, 'ordinary product ID 456 is eligible without explicit opt-out');

q19_set_cart(array(789));
$GLOBALS['q19_meta'] = array();
q19_assert(Utils::cartHasRestrictedProducts() === false, 'ordinary product remains eligible without explicit opt-out');

q19_set_cart(array(789));
$GLOBALS['q19_meta'] = array(
    789 => array('_upay_disable_subscription' => 'yes'),
);
q19_assert(Utils::cartHasRestrictedProducts() === true, 'explicit product-level opt-out remains restrictive');

q19_set_cart(array(123));
$GLOBALS['q19_meta'] = array(
    123 => array('_upay_disable_subscription' => 'yes'),
);
q19_assert(Utils::cartHasRestrictedProducts() === true, 'explicit opt-out also restricts product ID 123');

q19_set_cart(array(123, 789));
$GLOBALS['q19_meta'] = array(
    789 => array('_upay_disable_subscription' => 'yes'),
);
q19_assert(Utils::cartHasRestrictedProducts() === true, 'one explicitly opted-out product restricts the mixed cart');

echo "\nQ19 Subscription Product Eligibility: " . $pass . " PASS / " . $fail . " FAIL\n";
exit($fail === 0 ? 0 : 1);
