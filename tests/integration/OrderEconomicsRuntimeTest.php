<?php
/**
 * Real WooCommerce E2 certification for order economics authority.
 *
 * The finalized Woo order total/currency are Charge truth. Provider products[]
 * is descriptive only and must be omitted wholesale when exact line-level
 * representation is impossible.
 */

require_once __DIR__ . '/bootstrap.php';

use Simplixi\SUPCheckout\Payment\CheckoutOrchestrator;

if (! WC()->session) {
    WC()->session = new WC_Session_Handler();
    WC()->session->init();
}

function supcheckout_e2_product($name, $price, $virtual = false, $downloadable = false) {
    $product = new WC_Product_Simple();
    $product->set_name($name);
    $product->set_regular_price($price);
    $product->set_price($price);
    $product->set_virtual((bool) $virtual);
    $product->set_downloadable((bool) $downloadable);
    $product_id = $product->save();
    supcheckout_cert_assert(is_int($product_id) && $product_id > 0, 'E2 product persists: ' . $name);
    return $product;
}

function supcheckout_e2_order($items, $currency = 'KWD') {
    $order = wc_create_order();
    supcheckout_cert_assert($order instanceof WC_Order, 'E2 Woo order is created');

    foreach ($items as $item) {
        $product = $item[0];
        $quantity = isset($item[1]) ? $item[1] : 1;
        $order->add_product($product, $quantity);
    }

    $order->set_payment_method('upayments');
    $order->set_billing_first_name('Economics');
    $order->set_billing_last_name('Certification');
    $order->set_billing_email('economics@example.invalid');
    $order->set_billing_phone('50000000');
    $order->set_billing_country('KW');
    $order->set_currency($currency);
    $order->calculate_totals(false);
    $order->save();
    return $order;
}

function supcheckout_e2_gateway() {
    $gateway = new WC_Upayments();
    $gateway->domain = 'upayments';
    $gateway->apiKey = 'certification-api-key';
    $gateway->testMode = 'yes';
    $gateway->autoDeduction = 'no';
    $gateway->saveCardEnabled = 'no';
    $gateway->paymentData = array('whitelabled' => false, 'payment' => array());
    $gateway->multiMerchant = 'no';
    return $gateway;
}

function supcheckout_e2_run($order, &$calls) {
    $_POST = array();
    wc_clear_notices();
    $calls = array();

    $orchestrator = new CheckoutOrchestrator(
        supcheckout_e2_gateway(),
        static function () {
            return '';
        },
        static function ($route, $method, $body = null) use (&$calls) {
            $calls[] = array('route' => $route, 'method' => $method, 'body' => $body);
            return array(
                'transport_ok' => false,
                'http_status' => 0,
                'curl_errno' => 7,
                'body' => '',
            );
        }
    );

    return $orchestrator->process($order->get_id());
}

function supcheckout_e2_assert_charge($order, $calls, $label) {
    supcheckout_cert_assert(1 === count($calls), $label . ': exactly one provider request reaches Charge');
    supcheckout_cert_assert('charge' === $calls[0]['route'], $label . ': provider request is Charge');
    supcheckout_cert_assert('POST' === $calls[0]['method'], $label . ': Charge uses POST');
    supcheckout_cert_assert(is_string($calls[0]['body']) && '' !== $calls[0]['body'], $label . ': Charge body is captured');

    $amount = (string) $order->get_total();
    $currency = (string) $order->get_currency();
    supcheckout_cert_assert(
        1 === preg_match('/"order":\{[^}]*"currency":"' . preg_quote($currency, '/') . '"[^}]*"amount":' . preg_quote($amount, '/') . '(?=[,}])/', $calls[0]['body']),
        $label . ': raw Charge JSON preserves finalized Woo amount/currency'
    );

    $payload = json_decode($calls[0]['body'], true);
    supcheckout_cert_assert(is_array($payload), $label . ': Charge payload is valid JSON');
    supcheckout_cert_assert(isset($payload['order']['currency']) && $currency === $payload['order']['currency'], $label . ': decoded currency matches Woo order');
    return $payload;
}

function supcheckout_e2_delete_order_and_products($order, $products) {
    if ($order instanceof WC_Order) {
        $order->delete(true);
    }
    foreach ($products as $product) {
        if ($product instanceof WC_Product && $product->get_id() > 0) {
            wp_delete_post($product->get_id(), true);
        }
    }
    $_POST = array();
    wc_clear_notices();
}

// Baseline simple product: exact descriptors are retained.
$simple = supcheckout_e2_product('E2 Simple Product', '5.000');
$order = supcheckout_e2_order(array(array($simple, 2)));
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'simple order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'simple order: exact products[] descriptor is retained');
supcheckout_e2_delete_order_and_products($order, array($simple));

// Multiple ordinary products remain descriptive and never define Charge total.
$first = supcheckout_e2_product('E2 First Product', '3.000');
$second = supcheckout_e2_product('E2 Second Product', '7.000');
$order = supcheckout_e2_order(array(array($first, 1), array($second, 1)));
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'multiple-product order');
supcheckout_cert_assert(isset($payload['products']) && 2 === count($payload['products']), 'multiple-product order: both exact descriptors are retained');
supcheckout_e2_delete_order_and_products($order, array($first, $second));

// Virtual/downloadable products use normal authoritative order economics.
$digital = supcheckout_e2_product('E2 Digital Product', '4.250', true, true);
$digital_reloaded = wc_get_product($digital->get_id());
supcheckout_cert_assert(
    $digital_reloaded instanceof WC_Product && $digital_reloaded->is_virtual() && $digital_reloaded->is_downloadable(),
    'virtual/downloadable fixture persists both Woo product capabilities'
);
$order = supcheckout_e2_order(array(array($digital_reloaded, 1)));
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'virtual/downloadable order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'virtual/downloadable order: exact descriptor is retained');
supcheckout_e2_delete_order_and_products($order, array($digital));

// Dynamic-pricing style line: 3 units with a captured line total of 10 cannot
// produce an exact unit descriptor. The order remains payable; products[] vanishes.
$dynamic = supcheckout_e2_product('E2 Dynamic Pricing Product', '4.000');
$order = supcheckout_e2_order(array(array($dynamic, 3)));
$line_items = $order->get_items('line_item');
$line = reset($line_items);
supcheckout_cert_assert($line instanceof WC_Order_Item_Product, 'dynamic pricing fixture has a real Woo line item');
$line->set_quantity(3);
$line->set_subtotal('10.000');
$line->set_total('10.000');
$line->save();
$order->calculate_totals(false);
$order->save();
supcheckout_cert_assert((float) $order->get_total() > 0, 'dynamic pricing fixture has positive finalized Woo total');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'non-divisible dynamic-pricing order');
supcheckout_cert_assert(! array_key_exists('products', $payload), 'non-divisible dynamic-pricing order: products[] is omitted wholesale');
supcheckout_e2_delete_order_and_products($order, array($dynamic));

// Positive fee: order total is authoritative even though products[] describes
// only the product line and therefore does not sum to the final amount.
$fee_product = supcheckout_e2_product('E2 Fee Product', '10.000');
$order = supcheckout_e2_order(array(array($fee_product, 1)));
$fee = new WC_Order_Item_Fee();
$fee->set_name('E2 Certification Fee');
$fee->set_amount('2.500');
$fee->set_total('2.500');
$fee->set_tax_status('none');
$order->add_item($fee);
$order->calculate_totals(false);
$order->save();
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'fee-adjusted order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'fee-adjusted order: product descriptor remains descriptive');
supcheckout_e2_delete_order_and_products($order, array($fee_product));

// Negative fee/credit: where Woo accepts a negative fee item, the persisted
// positive grand total remains authoritative and the product line stays descriptive.
$credit_product = supcheckout_e2_product('E2 Credit Product', '10.000');
$order = supcheckout_e2_order(array(array($credit_product, 1)));
$credit = new WC_Order_Item_Fee();
$credit->set_name('E2 Certification Credit');
$credit->set_amount('-2.000');
$credit->set_total('-2.000');
$credit->set_tax_status('none');
$order->add_item($credit);
$order->calculate_totals(false);
$order->save();
supcheckout_cert_assert((float) $order->get_total() > 0 && (float) $order->get_total() < 10.0, 'negative-fee fixture produces a positive reduced Woo total');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'negative-fee adjusted order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'negative-fee order: product descriptor remains descriptive');
supcheckout_e2_delete_order_and_products($order, array($credit_product));

// Shipping: same rule as fees; Woo total is payment truth.
$shipping_product = supcheckout_e2_product('E2 Shipped Product', '10.000');
$order = supcheckout_e2_order(array(array($shipping_product, 1)));
$shipping = new WC_Order_Item_Shipping();
$shipping->set_method_title('E2 Certification Shipping');
$shipping->set_method_id('flat_rate');
$shipping->set_total('1.750');
$order->add_item($shipping);
$order->calculate_totals(false);
$order->save();
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'shipping-adjusted order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'shipping-adjusted order: product descriptor remains descriptive');
supcheckout_e2_delete_order_and_products($order, array($shipping_product));

// Split/multi-package style persisted shipping is represented by multiple shipping
// items. Their aggregate affects Woo's total only; products[] remains descriptive.
$split_product = supcheckout_e2_product('E2 Split Shipping Product', '10.000');
$order = supcheckout_e2_order(array(array($split_product, 1)));
foreach (array('1.250', '0.750') as $index => $shipping_total) {
    $split_shipping = new WC_Order_Item_Shipping();
    $split_shipping->set_method_title('E2 Split Shipping ' . ($index + 1));
    $split_shipping->set_method_id('flat_rate');
    $split_shipping->set_total($shipping_total);
    $order->add_item($split_shipping);
}
$order->calculate_totals(false);
$order->save();
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'split-shipping order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'split-shipping order: product descriptor remains descriptive');
supcheckout_e2_delete_order_and_products($order, array($split_product));

// A free promotional line beside a paid line is valid. Zero-priced descriptors are
// representable and the paid Woo grand total, not descriptor sums, remains authority.
$paid = supcheckout_e2_product('E2 Paid Product', '9.000');
$promo = supcheckout_e2_product('E2 Promotional Product', '0');
$order = supcheckout_e2_order(array(array($paid, 1), array($promo, 1)));
supcheckout_cert_assert((float) $order->get_total() > 0, 'free-promotion fixture retains a positive Woo grand total');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'free-promotion plus paid order');
supcheckout_cert_assert(isset($payload['products']) && 2 === count($payload['products']), 'free-promotion plus paid order: both exact descriptors are retained');
supcheckout_e2_delete_order_and_products($order, array($paid, $promo));

// Real Woo coupon APIs: all three core discount types must reduce the persisted
// order economics while Charge continues to use only Woo's finalized grand total.
foreach (array(
    array('percent', '25', 7.5),
    array('fixed_product', '2.500', 7.5),
    array('fixed_cart', '2.500', 7.5),
) as $coupon_case) {
    $coupon_product = supcheckout_e2_product('E2 Core Coupon ' . $coupon_case[0], '10.000');
    $order = supcheckout_e2_order(array(array($coupon_product, 1)));
    $coupon = new WC_Coupon();
    $coupon->set_code('e2-' . str_replace('_', '-', $coupon_case[0]) . '-' . wp_generate_uuid4());
    $coupon->set_discount_type($coupon_case[0]);
    $coupon->set_amount($coupon_case[1]);
    $coupon_id = $coupon->save();
    supcheckout_cert_assert(is_int($coupon_id) && $coupon_id > 0, $coupon_case[0] . ' coupon persists through Woo CRUD');
    $applied = $order->apply_coupon($coupon);
    supcheckout_cert_assert(true === $applied, $coupon_case[0] . ' coupon applies through WC_Order::apply_coupon');
    $order->save();
    $order = wc_get_order($order->get_id());
    supcheckout_cert_assert($order instanceof WC_Order, $coupon_case[0] . ' coupon order reloads through Woo CRUD');
    supcheckout_cert_assert($coupon_case[2] === (float) $order->get_total(), $coupon_case[0] . ' coupon finalizes expected Woo total');
    $calls = array();
    supcheckout_e2_run($order, $calls);
    $payload = supcheckout_e2_assert_charge($order, $calls, $coupon_case[0] . ' coupon order');
    supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), $coupon_case[0] . ' coupon order retains one exact descriptive line');
    $coupon->delete(true);
    supcheckout_e2_delete_order_and_products($order, array($coupon_product));
}

// A product may be discounted to zero while shipping keeps the finalized Woo order
// payable. This is not a zero-total order: Charge must use the shipping-only balance.
$shipping_due_product = supcheckout_e2_product('E2 Shipping Due After Full Discount', '10.000');
$order = supcheckout_e2_order(array(array($shipping_due_product, 1)));
$shipping_due = new WC_Order_Item_Shipping();
$shipping_due->set_method_title('E2 Payable Shipping');
$shipping_due->set_method_id('flat_rate');
$shipping_due->set_total('2.500');
$order->add_item($shipping_due);
$order->calculate_totals(false);
$order->save();
$full_coupon = new WC_Coupon();
$full_coupon->set_code('e2-full-line-' . wp_generate_uuid4());
$full_coupon->set_discount_type('fixed_product');
$full_coupon->set_amount('10.000');
$full_coupon_id = $full_coupon->save();
supcheckout_cert_assert(is_int($full_coupon_id) && $full_coupon_id > 0, 'full-line coupon persists through Woo CRUD');
$applied = $order->apply_coupon($full_coupon);
supcheckout_cert_assert(true === $applied, 'full-line coupon applies through Woo order API');
$order->save();
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order, 'shipping-due full-discount order reloads through Woo CRUD');
supcheckout_cert_assert(2.5 === (float) $order->get_total(), 'fully discounted line leaves shipping-only positive Woo total');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'fully discounted line with payable shipping');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'shipping-only balance keeps zero-priced product descriptor descriptive');
$full_coupon->delete(true);
supcheckout_e2_delete_order_and_products($order, array($shipping_due_product));

// Persisted core shipping method identities must not alter payment authority. These
// fixtures intentionally certify the order state SUPCheckout consumes, not live rates.
foreach (array(
    array('free_shipping', 'E2 Free Shipping', '0', 8.0),
    array('local_pickup', 'E2 Local Pickup', '1.250', 9.25),
) as $shipping_case) {
    $method_product = supcheckout_e2_product('E2 ' . $shipping_case[1] . ' Product', '8.000');
    $order = supcheckout_e2_order(array(array($method_product, 1)));
    $method_shipping = new WC_Order_Item_Shipping();
    $method_shipping->set_method_title($shipping_case[1]);
    $method_shipping->set_method_id($shipping_case[0]);
    $method_shipping->set_total($shipping_case[2]);
    $order->add_item($method_shipping);
    $order->calculate_totals(false);
    $order->save();
    $order = wc_get_order($order->get_id());
    supcheckout_cert_assert($order instanceof WC_Order, $shipping_case[0] . ' order reloads through Woo CRUD');
    $shipping_items = $order->get_items('shipping');
    $shipping_item = reset($shipping_items);
    supcheckout_cert_assert($shipping_item instanceof WC_Order_Item_Shipping && $shipping_case[0] === $shipping_item->get_method_id(), $shipping_case[0] . ' method identity persists');
    supcheckout_cert_assert($shipping_case[3] === (float) $order->get_total(), $shipping_case[0] . ' final Woo total is authoritative');
    $calls = array();
    supcheckout_e2_run($order, $calls);
    $payload = supcheckout_e2_assert_charge($order, $calls, $shipping_case[0] . ' order');
    supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), $shipping_case[0] . ' product descriptor remains descriptive');
    supcheckout_e2_delete_order_and_products($order, array($method_product));
}

// Product add-on/custom-option style economics are represented by the captured line
// total plus metadata. Metadata must not become a parallel payment ledger.
$addon_product = supcheckout_e2_product('E2 Add-on Product', '10.000');
$order = supcheckout_e2_order(array(array($addon_product, 1)));
$addon_lines = $order->get_items('line_item');
$addon_line = reset($addon_lines);
supcheckout_cert_assert($addon_line instanceof WC_Order_Item_Product, 'add-on fixture has a real Woo line item');
$addon_line->set_subtotal('12.750');
$addon_line->set_total('12.750');
$addon_line->add_meta_data('E2 Add-on', 'Gift wrap', true);
$addon_line->save();
$order->calculate_totals(false);
$order->save();
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order, 'add-on order reloads through Woo CRUD');
$addon_lines = $order->get_items('line_item');
$addon_line = reset($addon_lines);
supcheckout_cert_assert($addon_line instanceof WC_Order_Item_Product && 'Gift wrap' === $addon_line->get_meta('E2 Add-on', true), 'add-on metadata persists independently of payment authority');
supcheckout_cert_assert(12.75 === (float) $order->get_total(), 'add-on adjusted line total becomes finalized Woo total');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'add-on adjusted order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'add-on adjusted order retains exact captured line descriptor');
supcheckout_e2_delete_order_and_products($order, array($addon_product));

// Coupon/discount style persisted economics: the captured order-item total is lower
// than catalog/subtotal economics and a coupon item records the discount. Charge uses
// only the finalized Woo total; products[] describes the discounted line exactly.
$discounted = supcheckout_e2_product('E2 Discounted Product', '10.000');
$order = supcheckout_e2_order(array(array($discounted, 1)));
$line_items = $order->get_items('line_item');
$line = reset($line_items);
supcheckout_cert_assert($line instanceof WC_Order_Item_Product, 'discount fixture has a real Woo line item');
$line->set_subtotal('10.000');
$line->set_total('8.000');
$line->save();
$coupon = new WC_Order_Item_Coupon();
$coupon->set_code('e2-certification');
$coupon->set_discount('2.000');
$coupon->set_discount_tax('0');
$order->add_item($coupon);
$order->calculate_totals(false);
$order->save();
supcheckout_cert_assert(8.0 === (float) $order->get_total(), 'discount/coupon fixture finalizes the reduced Woo total');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'discount/coupon order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'discount/coupon order: discounted product descriptor is retained');
supcheckout_e2_delete_order_and_products($order, array($discounted));

// Tax-bearing persisted order: E2 certifies the actual state the gateway reads.
// A Woo tax item records the tax component while the finalized order total remains
// authoritative; products[] continues to describe only the product line.
$taxed = supcheckout_e2_product('E2 Taxed Product', '10.000');
$order = supcheckout_e2_order(array(array($taxed, 1)));
$tax_item = new WC_Order_Item_Tax();
$tax_item->set_rate_id(0);
$tax_item->set_label('E2 Certification Tax');
$tax_item->set_tax_total('0.500');
$tax_item->set_shipping_tax_total('0');
$order->add_item($tax_item);
$order->set_total('10.500');
$order->save();
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order, 'tax fixture reloads through Woo CRUD');
supcheckout_cert_assert(10.5 === (float) $order->get_total(), 'tax fixture persists finalized Woo total including tax delta');
$tax_items = $order->get_items('tax');
$reloaded_tax = reset($tax_items);
supcheckout_cert_assert($reloaded_tax instanceof WC_Order_Item_Tax && 0.5 === (float) $reloaded_tax->get_tax_total(), 'tax fixture persists real Woo tax-item evidence');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'tax-bearing order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'tax-bearing order: product descriptor remains descriptive');
supcheckout_e2_delete_order_and_products($order, array($taxed));

// Product variation: the actual variation order line is a normal payable line and
// should retain a faithful descriptor without inventing parent-product economics.
$variable = new WC_Product_Variable();
$variable->set_name('E2 Variable Parent');
$variable_id = $variable->save();
supcheckout_cert_assert(is_int($variable_id) && $variable_id > 0, 'variable parent persists');
$variation = new WC_Product_Variation();
$variation->set_parent_id($variable_id);
$variation->set_regular_price('6.500');
$variation->set_price('6.500');
$variation_id = $variation->save();
supcheckout_cert_assert(is_int($variation_id) && $variation_id > 0, 'variation persists');
$order = supcheckout_e2_order(array(array($variation, 2)));
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'variation order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'variation order: variation descriptor is retained');
supcheckout_e2_delete_order_and_products($order, array($variation, $variable));

// Grouped catalog structures persist child products as order lines. The provider
// descriptor must reflect the purchased child only, not fabricate the grouped parent.
$group_child = supcheckout_e2_product('E2 Group Child', '4.000');
$group_parent = new WC_Product_Grouped();
$group_parent->set_name('E2 Group Parent');
$group_parent->set_children(array($group_child->get_id()));
$group_parent_id = $group_parent->save();
supcheckout_cert_assert(is_int($group_parent_id) && $group_parent_id > 0, 'grouped parent persists');
$order = supcheckout_e2_order(array(array($group_child, 2)));
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'grouped-child order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'grouped-child order: purchased child descriptor is retained once');
supcheckout_e2_delete_order_and_products($order, array($group_child, $group_parent));

// Measurement/fractional quantity: Woo may persist non-integer quantities. They are
// authoritative order economics but are not representable by UPayments products[].
$measured = supcheckout_e2_product('E2 Measured Product', '5.000');
$order = supcheckout_e2_order(array(array($measured, 1)));
$line_items = $order->get_items('line_item');
$line = reset($line_items);
supcheckout_cert_assert($line instanceof WC_Order_Item_Product, 'fractional-quantity fixture has a real Woo line item');
$fractional_stock_amount = static function ($quantity) {
    return is_numeric($quantity) ? (float) $quantity : $quantity;
};
$core_integer_stock_filter_removed = remove_filter('woocommerce_stock_amount', 'intval', 10);
supcheckout_cert_assert(
    true === $core_integer_stock_filter_removed,
    'fractional-quantity fixture replaces Woo default integer stock normalization'
);
add_filter('woocommerce_stock_amount', $fractional_stock_amount, 10, 1);
try {
    $line->set_quantity(1.5);
    $line->set_subtotal('7.500');
    $line->set_total('7.500');
    $line->save();
    supcheckout_cert_assert(
        1.5 === (float) get_metadata('order_item', $line->get_id(), '_qty', true),
        'fractional-quantity fixture persists 1.5 in Woo order-item storage'
    );
    $order->calculate_totals(false);
    $order->save();
    $order = wc_get_order($order->get_id());
    supcheckout_cert_assert($order instanceof WC_Order, 'fractional-quantity fixture reloads through Woo CRUD');
    $reloaded_lines = $order->get_items('line_item');
    $reloaded_line = reset($reloaded_lines);
    supcheckout_cert_assert(
        $reloaded_line instanceof WC_Order_Item_Product && 1.5 === (float) $reloaded_line->get_quantity(),
        'fractional-quantity fixture preserves extension-filtered 1.5 units through Woo persistence'
    );
    $calls = array();
    supcheckout_e2_run($order, $calls);
    $payload = supcheckout_e2_assert_charge($order, $calls, 'fractional-quantity order');
    supcheckout_cert_assert(! array_key_exists('products', $payload), 'fractional-quantity order: products[] is omitted wholesale');
} finally {
    remove_filter('woocommerce_stock_amount', $fractional_stock_amount, 10);
    add_filter('woocommerce_stock_amount', 'intval', 10, 1);
}
supcheckout_e2_delete_order_and_products($order, array($measured));

// A 100%-discounted order has no payment to initialize even if a normal product
// and coupon history remain present in the order.
$fully_discounted = supcheckout_e2_product('E2 Fully Discounted Product', '10.000');
$order = supcheckout_e2_order(array(array($fully_discounted, 1)));
$line_items = $order->get_items('line_item');
$line = reset($line_items);
supcheckout_cert_assert($line instanceof WC_Order_Item_Product, '100%-discount fixture has a real Woo line item');
$line->set_subtotal('10.000');
$line->set_total('0');
$line->save();
$coupon = new WC_Order_Item_Coupon();
$coupon->set_code('e2-full-discount');
$coupon->set_discount('10.000');
$coupon->set_discount_tax('0');
$order->add_item($coupon);
$order->calculate_totals(false);
$order->save();
supcheckout_cert_assert(0.0 === (float) $order->get_total(), '100%-discount fixture finalizes at zero');
$calls = array();
$result = supcheckout_e2_run($order, $calls);
supcheckout_cert_assert('failure' === $result['result'], '100%-discount order fails before provider Charge');
supcheckout_cert_assert(array() === $calls, '100%-discount order emits no provider request');
supcheckout_e2_delete_order_and_products($order, array($fully_discounted));

// Deleted/unloadable catalog product: unlike descriptor-only incompatibility, this
// prevents trusted product/subscription/opt-out classification and therefore fails
// closed before provider transport.
$deleted = supcheckout_e2_product('E2 Deleted Product', '5.000');
$order = supcheckout_e2_order(array(array($deleted, 1)));
$deleted_id = $deleted->get_id();
wp_delete_post($deleted_id, true);
clean_post_cache($deleted_id);
$calls = array();
$result = supcheckout_e2_run($order, $calls);
supcheckout_cert_assert('failure' === $result['result'], 'deleted product order fails closed before Charge');
supcheckout_cert_assert(array() === $calls, 'deleted product order emits no provider request');
if ($order instanceof WC_Order) {
    $order->delete(true);
}
$_POST = array();
wc_clear_notices();

// Zero-grand-total orders remain outside Charge regardless of product shape.
$free = supcheckout_e2_product('E2 Free Product', '0');
$order = supcheckout_e2_order(array(array($free, 1)));
supcheckout_cert_assert(0.0 === (float) $order->get_total(), 'zero-total fixture is finalized at zero');
$calls = array();
$result = supcheckout_e2_run($order, $calls);
supcheckout_cert_assert('failure' === $result['result'], 'zero-total order fails before provider Charge');
supcheckout_cert_assert(array() === $calls, 'zero-total order emits no provider request');
supcheckout_e2_delete_order_and_products($order, array($free));

supcheckout_cert_note('real Woo order-economics certification complete');
