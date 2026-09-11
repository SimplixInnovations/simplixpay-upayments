from pathlib import Path

# Extend real finalized-order economics with late pre-dispatch mutation and tax-mode representations.
p = Path('tests/integration/OrderEconomicsRuntimeTest.php')
s = p.read_text()
marker = "supcheckout_cert_note('real Woo order-economics certification complete');"
if s.count(marker) != 1:
    raise SystemExit(f'expected one economics insertion marker, found {s.count(marker)}')

block = r'''
// Address/rate changes can mutate shipping immediately before payment dispatch.
// SUPCheckout must read the newest finalized Woo order, never an earlier checkout
// estimate or catalog value.
$late_shipping_product = supcheckout_e2_product('E2 Late Shipping Mutation Product', '10.000');
$order = supcheckout_e2_order(array(array($late_shipping_product, 1)));
$late_shipping = new WC_Order_Item_Shipping();
$late_shipping->set_method_title('E2 Initial Address Rate');
$late_shipping->set_method_id('e2_address_rate_initial');
$late_shipping->set_total('2.000');
$order->add_item($late_shipping);
$order->set_shipping_country('KW');
$order->set_shipping_postcode('13001');
$order->calculate_totals(false);
$order->save();
supcheckout_cert_assert(12.0 === (float) $order->get_total(), 'late-shipping fixture starts with the initial finalized address rate');
$shipping_items = $order->get_items('shipping');
$late_shipping = reset($shipping_items);
supcheckout_cert_assert($late_shipping instanceof WC_Order_Item_Shipping, 'late-shipping fixture reloads the shipping line before mutation');
$late_shipping->set_method_title('E2 Recalculated Address Rate');
$late_shipping->set_method_id('e2_address_rate_recalculated');
$late_shipping->set_total('4.500');
$late_shipping->save();
$order->set_shipping_postcode('15000');
$order->calculate_totals(false);
$order->save();
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order, 'late-shipping mutated order reloads through Woo CRUD');
supcheckout_cert_assert('15000' === $order->get_shipping_postcode(), 'late-shipping fixture persists the changed destination before Charge');
$shipping_items = $order->get_items('shipping');
$late_shipping = reset($shipping_items);
supcheckout_cert_assert($late_shipping instanceof WC_Order_Item_Shipping && 'e2_address_rate_recalculated' === $late_shipping->get_method_id(), 'late-shipping fixture persists the recalculated rate identity');
supcheckout_cert_assert(14.5 === (float) $order->get_total(), 'late-shipping fixture finalizes the recalculated rate before Charge');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'late address/shipping mutation order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'late address/shipping mutation leaves products[] descriptive only');
supcheckout_e2_delete_order_and_products($order, array($late_shipping_product));

// Inclusive-tax representation: Woo's order flag and tax item are descriptive to
// the gateway; the finalized Woo grand total remains payment truth.
$inclusive_tax_product = supcheckout_e2_product('E2 Inclusive Tax Product', '10.000');
$order = supcheckout_e2_order(array(array($inclusive_tax_product, 1)));
$inclusive_lines = $order->get_items('line_item');
$inclusive_line = reset($inclusive_lines);
supcheckout_cert_assert($inclusive_line instanceof WC_Order_Item_Product, 'inclusive-tax fixture has a real Woo line item');
$inclusive_line->set_subtotal('9.500');
$inclusive_line->set_total('9.500');
$inclusive_line->save();
$inclusive_tax = new WC_Order_Item_Tax();
$inclusive_tax->set_rate_id(0);
$inclusive_tax->set_label('E2 Included Tax');
$inclusive_tax->set_tax_total('0.500');
$inclusive_tax->set_shipping_tax_total('0');
$order->add_item($inclusive_tax);
$order->set_prices_include_tax(true);
$order->set_cart_tax('0.500');
$order->set_total('10.000');
$order->save();
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order && true === $order->get_prices_include_tax(), 'inclusive-tax flag persists through Woo CRUD');
supcheckout_cert_assert(10.0 === (float) $order->get_total(), 'inclusive-tax fixture persists the finalized tax-inclusive grand total');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'inclusive-tax order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'inclusive-tax order keeps line descriptor separate from grand-total authority');
supcheckout_e2_delete_order_and_products($order, array($inclusive_tax_product));

// Explicit exclusive-tax representation mirrors the common Woo model: product
// line economics plus a separate tax component produce the finalized total.
$exclusive_tax_product = supcheckout_e2_product('E2 Exclusive Tax Product', '10.000');
$order = supcheckout_e2_order(array(array($exclusive_tax_product, 1)));
$exclusive_tax = new WC_Order_Item_Tax();
$exclusive_tax->set_rate_id(0);
$exclusive_tax->set_label('E2 Exclusive Tax');
$exclusive_tax->set_tax_total('0.500');
$exclusive_tax->set_shipping_tax_total('0');
$order->add_item($exclusive_tax);
$order->set_prices_include_tax(false);
$order->set_cart_tax('0.500');
$order->set_total('10.500');
$order->save();
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order && false === $order->get_prices_include_tax(), 'exclusive-tax flag persists through Woo CRUD');
supcheckout_cert_assert(10.5 === (float) $order->get_total(), 'exclusive-tax fixture persists the finalized tax-exclusive grand total');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'exclusive-tax order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'exclusive-tax order keeps product descriptor descriptive');
supcheckout_e2_delete_order_and_products($order, array($exclusive_tax_product));

// Generic tax-exempt/reverse-charge style outcome: the external tax/VAT engine may
// record exemption provenance, but SUPCheckout must charge only Woo's finalized
// zero-tax balance. This is not named VAT-provider certification.
$exempt_product = supcheckout_e2_product('E2 Tax Exempt Product', '10.000');
$order = supcheckout_e2_order(array(array($exempt_product, 1)));
$order->set_prices_include_tax(false);
$order->set_cart_tax('0');
$order->update_meta_data('E2 Tax Exemption Outcome', 'validated-exempt');
$order->set_total('10.000');
$order->save();
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order && 'validated-exempt' === $order->get_meta('E2 Tax Exemption Outcome', true), 'generic exemption provenance persists separately from payment authority');
supcheckout_cert_assert(10.0 === (float) $order->get_total(), 'generic tax-exempt outcome persists the finalized zero-tax balance');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'generic tax-exempt/reverse-charge outcome');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'generic exemption outcome leaves products[] descriptive');
supcheckout_e2_delete_order_and_products($order, array($exempt_product));

'''
p.write_text(s.replace(marker, block + marker, 1))

# Add a real Woo checkout validation boundary for shippable carts with no rates.
validation = Path('tests/integration/CheckoutValidationBoundaryRuntimeTest.php')
if validation.exists():
    raise SystemExit('CheckoutValidationBoundaryRuntimeTest.php already exists')
validation.write_text(r'''<?php
/**
 * Real WooCommerce checkout finalization boundary certification.
 *
 * Shipping-rate availability is owned by Woo checkout validation. SUPCheckout
 * must not invent a parallel shipping engine; payment processing occurs only
 * after Woo accepts the checkout.
 */

require_once __DIR__ . '/bootstrap.php';

if (! WC()->session) {
    WC()->session = new WC_Session_Handler();
    WC()->session->init();
}
if (! WC()->customer) {
    WC()->customer = new WC_Customer(0, true);
}
if (! WC()->cart) {
    WC()->cart = new WC_Cart();
}

WC()->cart->empty_cart();
WC()->session->set('chosen_shipping_methods', array());

$product = new WC_Product_Simple();
$product->set_name('E2 No Shipping Rate Product');
$product->set_regular_price('8.000');
$product->set_price('8.000');
$product->set_virtual(false);
$product_id = $product->save();
supcheckout_cert_assert(is_int($product_id) && $product_id > 0, 'no-rate checkout product persists');

$cart_key = WC()->cart->add_to_cart($product_id, 1);
supcheckout_cert_assert(is_string($cart_key) && '' !== $cart_key, 'physical product enters real Woo cart');
WC()->customer->set_billing_country('KW');
WC()->customer->set_shipping_country('KW');
WC()->customer->set_billing_postcode('13001');
WC()->customer->set_shipping_postcode('13001');

$force_no_rates = static function ($rates, $package) {
    return array();
};
$skip_payment_validation = static function ($needs_payment, $cart) {
    return false;
};
add_filter('woocommerce_package_rates', $force_no_rates, PHP_INT_MAX, 2);
add_filter('woocommerce_cart_needs_payment', $skip_payment_validation, PHP_INT_MAX, 2);

try {
    WC()->shipping()->reset_shipping();
    WC()->cart->calculate_totals();
    $packages = WC()->shipping()->get_packages();
    supcheckout_cert_assert(! empty($packages), 'physical cart produces at least one Woo shipping package');
    foreach ($packages as $package) {
        supcheckout_cert_assert(isset($package['rates']) && array() === $package['rates'], 'certification filter leaves the Woo shipping package with no available rate');
    }
    supcheckout_cert_assert(true === WC()->cart->needs_shipping(), 'physical cart requires shipping before payment');
    supcheckout_cert_assert(false === WC()->cart->needs_payment(), 'payment validation is isolated out of this shipping-boundary probe');

    $checkout = new class extends WC_Checkout {
        public function supcheckout_validate_checkout(&$data, &$errors) {
            return parent::validate_checkout($data, $errors);
        }
    };

    $data = array(
        'billing_first_name' => 'Shipping',
        'billing_last_name' => 'Boundary',
        'billing_country' => 'KW',
        'billing_address_1' => 'Certification Address',
        'billing_city' => 'Kuwait City',
        'billing_postcode' => '13001',
        'billing_phone' => '50000000',
        'billing_email' => 'shipping-boundary@example.invalid',
        'shipping_country' => 'KW',
        'ship_to_different_address' => false,
        'payment_method' => 'upayments',
    );
    $errors = new WP_Error();
    $checkout->supcheckout_validate_checkout($data, $errors);
    supcheckout_cert_assert('' !== $errors->get_error_message('shipping'), 'Woo checkout rejects a shippable cart when no chosen package rate exists');
    supcheckout_cert_note('no-rate shipping checkout is rejected by Woo before gateway payment processing');
} finally {
    remove_filter('woocommerce_package_rates', $force_no_rates, PHP_INT_MAX);
    remove_filter('woocommerce_cart_needs_payment', $skip_payment_validation, PHP_INT_MAX);
    WC()->cart->empty_cart();
    WC()->session->set('chosen_shipping_methods', array());
    wp_delete_post($product_id, true);
}
''')

# Wire the checkout-finalization boundary into every compatibility runtime cell.
workflow = Path('.github/workflows/compatibility-certification.yml')
w = workflow.read_text()
old = '''      - name: Verify finalized Woo order economics authority
        run: /tmp/wp-cli.phar eval-file "$GITHUB_WORKSPACE/tests/integration/OrderEconomicsRuntimeTest.php" --path="$RUNNER_TEMP/wordpress"

      - name: Verify saved-card and tokenization runtime boundaries
'''
new = '''      - name: Verify finalized Woo order economics authority
        run: /tmp/wp-cli.phar eval-file "$GITHUB_WORKSPACE/tests/integration/OrderEconomicsRuntimeTest.php" --path="$RUNNER_TEMP/wordpress"

      - name: Verify checkout shipping finalization boundary
        run: /tmp/wp-cli.phar eval-file "$GITHUB_WORKSPACE/tests/integration/CheckoutValidationBoundaryRuntimeTest.php" --path="$RUNNER_TEMP/wordpress"

      - name: Verify saved-card and tokenization runtime boundaries
'''
if w.count(old) != 1:
    raise SystemExit(f'expected one compatibility insertion point, found {w.count(old)}')
workflow.write_text(w.replace(old, new, 1))
