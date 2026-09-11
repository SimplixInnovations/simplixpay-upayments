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
$order = supcheckout_e2_order(array(array($digital, 1)));
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
