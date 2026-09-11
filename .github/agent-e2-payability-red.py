from pathlib import Path

p = Path('tests/integration/OrderEconomicsRuntimeTest.php')
s = p.read_text()
marker = "supcheckout_cert_note('real Woo order-economics certification complete');"
if s.count(marker) != 1:
    raise SystemExit(f'expected one insertion marker, found {s.count(marker)}')
block = r'''// Woo's needs_payment() contract is the authoritative payability boundary. A
// processing order has already left pending/failed payment state and must never
// initialize another non-idempotent Charge even if process_payment() is replayed.
$paid_replay_product = supcheckout_e2_product('E2 Non-payable Replay Product', '6.000');
$order = supcheckout_e2_order(array(array($paid_replay_product, 1)));
$order->set_status('processing');
$order->save();
$order = wc_get_order($order->get_id());
supcheckout_cert_assert($order instanceof WC_Order, 'non-payable replay order reloads through Woo CRUD');
supcheckout_cert_assert(false === $order->needs_payment(), 'processing order is non-payable under Woo needs_payment');
$calls = array();
$result = supcheckout_e2_run($order, $calls);
supcheckout_cert_assert('failure' === $result['result'], 'non-payable processing order is rejected before Charge');
supcheckout_cert_assert(array() === $calls, 'non-payable processing order emits no provider request');
supcheckout_e2_delete_order_and_products($order, array($paid_replay_product));

'''
p.write_text(s.replace(marker, block + marker, 1))
