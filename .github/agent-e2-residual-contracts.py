from pathlib import Path

p = Path('tests/integration/OrderEconomicsRuntimeTest.php')
s = p.read_text()
marker = "supcheckout_cert_note('real Woo order-economics certification complete');"
if s.count(marker) != 1:
    raise SystemExit(f'expected one insertion marker, found {s.count(marker)}')

block = r'''
// Partial refunds are a separate Woo ledger from the original captured payment.
// They must never make a processing parent payable again or replay Charge.
$refund_product = supcheckout_e2_product('E2 Partial Refund Product', '10.000');
$order = supcheckout_e2_order(array(array($refund_product, 1)));
$order->set_status('processing');
$order->save();
$refund = wc_create_refund(array(
    'amount' => '2.500',
    'reason' => 'E2 partial refund ledger',
    'order_id' => $order->get_id(),
    'refund_payment' => false,
    'restock_items' => false,
));
supcheckout_cert_assert($refund instanceof WC_Order_Refund, 'partial refund persists a Woo refund object without gateway refund transport');
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order, 'partially refunded parent reloads through Woo CRUD');
supcheckout_cert_assert('processing' === $order->get_status(), 'partial refund does not rewrite processing parent to refunded');
supcheckout_cert_assert(2.5 === (float) $order->get_total_refunded(), 'partial refund ledger records the refunded amount');
supcheckout_cert_assert(false === $order->needs_payment(), 'partially refunded processing parent remains non-payable');
$calls = array();
$result = supcheckout_e2_run($order, $calls);
supcheckout_cert_assert('failure' === $result['result'], 'partially refunded processing parent is rejected before replay Charge');
supcheckout_cert_assert(array() === $calls, 'partially refunded processing parent emits no provider request');
$refund->delete(true);
supcheckout_e2_delete_order_and_products($order, array($refund_product));

// The payability guard delegates policy to Woo. Extensions may add a normally
// non-payable status to woocommerce_valid_order_statuses_for_payment.
$filtered_status_product = supcheckout_e2_product('E2 Filtered Payability Product', '6.000');
$order = supcheckout_e2_order(array(array($filtered_status_product, 1)));
$order->set_status('on-hold');
$order->save();
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order && false === $order->needs_payment(), 'on-hold order is non-payable before extension filter');
$payable_status_filter = static function ($statuses, $filtered_order) use ($order) {
    if ($filtered_order instanceof WC_Order && $filtered_order->get_id() === $order->get_id()) {
        $statuses[] = 'on-hold';
    }
    return array_values(array_unique($statuses));
};
add_filter('woocommerce_valid_order_statuses_for_payment', $payable_status_filter, 10, 2);
try {
    supcheckout_cert_assert(true === $order->needs_payment(), 'extension-filtered on-hold order becomes payable through Woo canonical API');
    $calls = array();
    supcheckout_e2_run($order, $calls);
    supcheckout_e2_assert_charge($order, $calls, 'extension-filtered payable-status order');
} finally {
    remove_filter('woocommerce_valid_order_statuses_for_payment', $payable_status_filter, 10);
}
supcheckout_e2_delete_order_and_products($order, array($filtered_status_product));

// Woo also exposes a final order-specific needs-payment filter. SUPCheckout must
// honor it instead of replacing Woo policy with its own status allowlist.
$needs_filter_product = supcheckout_e2_product('E2 Needs Payment Filter Product', '6.500');
$order = supcheckout_e2_order(array(array($needs_filter_product, 1)));
$order->set_status('on-hold');
$order->save();
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order && false === $order->needs_payment(), 'second on-hold fixture starts non-payable');
$needs_payment_filter = static function ($needs_payment, $filtered_order, $valid_statuses) use ($order) {
    if ($filtered_order instanceof WC_Order && $filtered_order->get_id() === $order->get_id()) {
        return true;
    }
    return $needs_payment;
};
add_filter('woocommerce_order_needs_payment', $needs_payment_filter, 10, 3);
try {
    supcheckout_cert_assert(true === $order->needs_payment(), 'order-specific Woo filter can make the fixture payable');
    $calls = array();
    supcheckout_e2_run($order, $calls);
    supcheckout_e2_assert_charge($order, $calls, 'order-specific needs-payment filter');
} finally {
    remove_filter('woocommerce_order_needs_payment', $needs_payment_filter, 10);
}
supcheckout_e2_delete_order_and_products($order, array($needs_filter_product));

// Shipping-only payable orders are valid finalized Woo economics without product
// lines. The shipping amount is Charge truth and products[] remains absent.
$order = supcheckout_e2_order(array());
$shipping_only = new WC_Order_Item_Shipping();
$shipping_only->set_method_title('E2 Shipping-only Charge');
$shipping_only->set_method_id('e2_shipping_only');
$shipping_only->set_total('3.250');
$order->add_item($shipping_only);
$order->calculate_totals(false);
$order->save();
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order, 'shipping-only order reloads through Woo CRUD');
supcheckout_cert_assert(array() === $order->get_items('line_item'), 'shipping-only order contains no product line items');
supcheckout_cert_assert(1 === count($order->get_items('shipping')), 'shipping-only order persists one shipping item');
supcheckout_cert_assert(3.25 === (float) $order->get_total(), 'shipping-only order finalizes at the shipping amount');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'shipping-only payable order');
supcheckout_cert_assert(! array_key_exists('products', $payload), 'shipping-only payable order omits descriptive products[]');
supcheckout_e2_delete_order_and_products($order, array());

// Generic deposit/initial-payment semantics: extension metadata may retain the
// original/scheduled economics, but only the finalized initial Woo amount is paid.
$deposit_product = supcheckout_e2_product('E2 Deposit Product', '100.000');
$order = supcheckout_e2_order(array(array($deposit_product, 1)));
$deposit_lines = $order->get_items('line_item');
$deposit_line = reset($deposit_lines);
supcheckout_cert_assert($deposit_line instanceof WC_Order_Item_Product, 'deposit fixture has a real Woo line item');
$deposit_line->set_subtotal('25.000');
$deposit_line->set_total('25.000');
$deposit_line->add_meta_data('E2 Deposit Original Total', '100.000', true);
$deposit_line->add_meta_data('E2 Deposit Phase', 'initial', true);
$deposit_line->save();
$order->calculate_totals(false);
$order->save();
$order = wc_get_order($order->get_id());
$deposit_lines = $order->get_items('line_item');
$deposit_line = reset($deposit_lines);
supcheckout_cert_assert($deposit_line instanceof WC_Order_Item_Product && 'initial' === $deposit_line->get_meta('E2 Deposit Phase', true), 'deposit semantics persist as metadata only');
supcheckout_cert_assert(25.0 === (float) $order->get_total(), 'deposit fixture finalizes only the initial payable amount');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'generic initial-deposit order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'generic initial-deposit order retains the captured initial line descriptor');
supcheckout_e2_delete_order_and_products($order, array($deposit_product));

// Generic store-credit/gift-card redemption: extension metadata may describe an
// external credit, but provider Charge is only the finalized remaining Woo balance.
$credit_product = supcheckout_e2_product('E2 Store Credit Product', '10.000');
$order = supcheckout_e2_order(array(array($credit_product, 1)));
$credit_lines = $order->get_items('line_item');
$credit_line = reset($credit_lines);
supcheckout_cert_assert($credit_line instanceof WC_Order_Item_Product, 'store-credit fixture has a real Woo line item');
$credit_line->set_subtotal('10.000');
$credit_line->set_total('7.000');
$credit_line->add_meta_data('E2 External Store Credit Applied', '3.000', true);
$credit_line->save();
$order->calculate_totals(false);
$order->save();
$order = wc_get_order($order->get_id());
$credit_lines = $order->get_items('line_item');
$credit_line = reset($credit_lines);
supcheckout_cert_assert($credit_line instanceof WC_Order_Item_Product && '3.000' === $credit_line->get_meta('E2 External Store Credit Applied', true), 'store-credit metadata persists independently of payment authority');
supcheckout_cert_assert(7.0 === (float) $order->get_total(), 'store-credit fixture finalizes the remaining payable balance');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'generic store-credit redemption order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'store-credit order descriptor follows captured remaining line economics');
supcheckout_e2_delete_order_and_products($order, array($credit_product));

// Generic parent/child composition topology: catalog parent value and metadata must
// never duplicate child economics. Purchased order lines remain the only descriptors.
$composition_parent = supcheckout_e2_product('E2 Composition Parent', '100.000');
$composition_child = supcheckout_e2_product('E2 Composition Child', '12.000');
$order = supcheckout_e2_order(array(array($composition_parent, 1), array($composition_child, 1)));
$composition_lines = $order->get_items('line_item');
$parent_line = null;
$child_line = null;
foreach ($composition_lines as $composition_line) {
    if ((int) $composition_line->get_product_id() === (int) $composition_parent->get_id()) {
        $parent_line = $composition_line;
    }
    if ((int) $composition_line->get_product_id() === (int) $composition_child->get_id()) {
        $child_line = $composition_line;
    }
}
supcheckout_cert_assert($parent_line instanceof WC_Order_Item_Product && $child_line instanceof WC_Order_Item_Product, 'composition fixture persists parent and child order lines');
$parent_line->set_subtotal('0');
$parent_line->set_total('0');
$parent_line->add_meta_data('E2 Composition Role', 'parent', true);
$parent_line->save();
$child_line->add_meta_data('E2 Composition Parent Product', (string) $composition_parent->get_id(), true);
$child_line->save();
$order->calculate_totals(false);
$order->save();
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order && 12.0 === (float) $order->get_total(), 'composition fixture charges child economics once without catalog-parent duplication');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'generic parent-child composition order');
supcheckout_cert_assert(isset($payload['products']) && 2 === count($payload['products']), 'composition order describes the two persisted purchased lines exactly once each');
supcheckout_e2_delete_order_and_products($order, array($composition_parent, $composition_child));

// Shipping-tax persistence exercises a distinct Woo tax component from product tax.
$shipping_tax_product = supcheckout_e2_product('E2 Shipping Tax Product', '10.000');
$order = supcheckout_e2_order(array(array($shipping_tax_product, 1)));
$taxed_shipping = new WC_Order_Item_Shipping();
$taxed_shipping->set_method_title('E2 Taxed Shipping');
$taxed_shipping->set_method_id('e2_taxed_shipping');
$taxed_shipping->set_total('2.000');
$order->add_item($taxed_shipping);
$shipping_tax_item = new WC_Order_Item_Tax();
$shipping_tax_item->set_rate_id(0);
$shipping_tax_item->set_label('E2 Shipping Tax');
$shipping_tax_item->set_tax_total('0');
$shipping_tax_item->set_shipping_tax_total('0.100');
$order->add_item($shipping_tax_item);
$order->set_shipping_tax('0.100');
$order->set_total('12.100');
$order->save();
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order && 12.1 === (float) $order->get_total(), 'shipping-tax fixture persists finalized product + shipping + shipping-tax total');
$shipping_tax_items = $order->get_items('tax');
$reloaded_shipping_tax = reset($shipping_tax_items);
supcheckout_cert_assert($reloaded_shipping_tax instanceof WC_Order_Item_Tax && 0.1 === (float) $reloaded_shipping_tax->get_shipping_tax_total(), 'real Woo tax item persists shipping-tax component');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'shipping-tax order');
supcheckout_cert_assert(isset($payload['products']) && 1 === count($payload['products']), 'shipping-tax order keeps product descriptor descriptive');
supcheckout_e2_delete_order_and_products($order, array($shipping_tax_product));

// Characterize the maximum provider-supported plain-decimal amount through the
// actual Woo storage layer. Trailing storage zeros are numerically insignificant,
// but the provider-bound token must remain canonical and within the 22-byte limit.
$precision_amount = '123456789012345.678901';
$order = supcheckout_e2_order(array());
$order->set_total($precision_amount);
$order->save();
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order, 'high-precision order reloads through Woo CRUD');
$persisted_precision = (string) $order->get_total();
$normalize_decimal = static function ($value) {
    $value = (string) $value;
    if (false !== strpos($value, '.')) {
        $value = rtrim(rtrim($value, '0'), '.');
    }
    return $value;
};
supcheckout_cert_assert($normalize_decimal($precision_amount) === $normalize_decimal($persisted_precision), 'Woo storage round-trip preserves the maximum supported amount numerically');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'real-Woo high-precision amount');
supcheckout_cert_assert(! array_key_exists('products', $payload), 'real-Woo high-precision fee-less order omits products[]');
supcheckout_e2_delete_order_and_products($order, array());

'''

p.write_text(s.replace(marker, block + marker, 1))
