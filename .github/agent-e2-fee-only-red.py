from pathlib import Path

order_path = Path('tests/integration/OrderEconomicsRuntimeTest.php')
order_text = order_path.read_text()
order_marker = "supcheckout_cert_note('real Woo order-economics certification complete');"
if order_text.count(order_marker) != 1:
    raise SystemExit(f'expected one order marker, found {order_text.count(order_marker)}')
order_block = r'''// Fee-only payable order: Woo can persist a positive grand total without product
// lines. Since products[] is descriptive rather than payment authority, SUPCheckout
// must initialize Charge from the finalized Woo total and omit products[].
$order = supcheckout_e2_order(array());
$fee_only = new WC_Order_Item_Fee();
$fee_only->set_name('E2 Fee-only Payable Amount');
$fee_only->set_amount('4.250');
$fee_only->set_total('4.250');
$fee_only->set_tax_status('none');
$order->add_item($fee_only);
$order->calculate_totals(false);
$order->save();
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order, 'fee-only order reloads through Woo CRUD');
supcheckout_cert_assert(array() === $order->get_items('line_item'), 'fee-only order has no product line items');
supcheckout_cert_assert(1 === count($order->get_items('fee')), 'fee-only order persists one fee item');
supcheckout_cert_assert(4.25 === (float) $order->get_total(), 'fee-only order has a positive finalized Woo total');
$calls = array();
supcheckout_e2_run($order, $calls);
$payload = supcheckout_e2_assert_charge($order, $calls, 'fee-only payable order');
supcheckout_cert_assert(! array_key_exists('products', $payload), 'fee-only payable order omits descriptive products[]');
supcheckout_e2_delete_order_and_products($order, array());

'''
order_path.write_text(order_text.replace(order_marker, order_block + order_marker, 1))

status_path = Path('tests/unit/Payment/StatusVerifierTest.php')
status_text = status_path.read_text()
status_marker = "    public function test_capture_requires_payment_id_while_nonterminal_results_bind_fail_closed(): void {"
if status_text.count(status_marker) != 1:
    raise SystemExit(f'expected one status marker, found {status_text.count(status_marker)}')
status_block = r'''    public function test_dispatched_capture_is_rejected_if_authoritative_order_amount_changes_before_binding(): void {
        $gateway = new StatusVerifierGateway();
        $order = new StatusVerifierOrder(42, 'merchant-42');
        $dispatched_transaction = $this->transaction($order);

        // The provider result reflects the originally dispatched 10.000 amount,
        // but Woo's authoritative economics changed before callback reconciliation.
        $order->total = '12.000';
        $result = StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $dispatched_transaction);

        self::assertTrue($result['authenticated']);
        self::assertFalse($result['bound']);
        self::assertSame('binding_amount', $result['reason']);
        self::assertNotSame(ProviderResult::CAPTURED, $result['classification']);
    }

'''
status_path.write_text(status_text.replace(status_marker, status_block + status_marker, 1))
