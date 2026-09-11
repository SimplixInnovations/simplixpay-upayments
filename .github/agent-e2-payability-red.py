from pathlib import Path

source_path = Path('src/Payment/CheckoutOrchestrator.php')
source = source_path.read_text()
source_old = """            $order = wc_get_order($order_id);\n            if (!$order || !($order instanceof \\WC_Order)) {\n                wc_add_notice(__('Payment request could not be completed. Please try again.', 'supcheckout'), 'error');\n                return array('result' => 'failure', 'redirect' => wc_get_checkout_url());\n            }\n\n            $whitelabled = false;\n"""
source_new = """            $order = wc_get_order($order_id);\n            if (!$order || !($order instanceof \\WC_Order)) {\n                wc_add_notice(__('Payment request could not be completed. Please try again.', 'supcheckout'), 'error');\n                return array('result' => 'failure', 'redirect' => wc_get_checkout_url());\n            }\n\n            // Woo owns the canonical payability decision (status + positive total,\n            // including extension filters). process_payment() can be replayed or\n            // called outside the normal checkout UI, so reject non-payable orders\n            // before availability lookup, token work, or non-idempotent Charge.\n            if (!$order->needs_payment()) {\n                wc_add_notice(__('Payment request could not be completed. Please try again.', 'supcheckout'), 'error');\n                return array('result' => 'failure', 'redirect' => wc_get_checkout_url());\n            }\n\n            $whitelabled = false;\n"""
if source.count(source_old) != 1:
    raise SystemExit(f'expected one source insertion point, found {source.count(source_old)}')
source_path.write_text(source.replace(source_old, source_new, 1))

support_path = Path('tests/support/wordpress-payment-runtime.php')
support = support_path.read_text()
support_old = """    public function get_total() { return $this->total; }\n    public function get_items($type = '') { return $this->items; }\n"""
support_new = """    public function get_total() { return $this->total; }\n    public function needs_payment() { return (float) $this->total > 0; }\n    public function get_items($type = '') { return $this->items; }\n"""
if support.count(support_old) != 1:
    raise SystemExit(f'expected one support insertion point, found {support.count(support_old)}')
support_path.write_text(support.replace(support_old, support_new, 1))

phpstan_path = Path('tests/phpstan/subscription-presentation-stubs.php')
phpstan = phpstan_path.read_text()
phpstan_old = """        /** @return mixed */\n        public function get_total() {}\n        /** @return mixed */\n        public function get_billing_phone() {}\n"""
phpstan_new = """        /** @return mixed */\n        public function get_total() {}\n        /** @return bool */\n        public function needs_payment() { return false; }\n        /** @return mixed */\n        public function get_billing_phone() {}\n"""
if phpstan.count(phpstan_old) != 1:
    raise SystemExit(f'expected one PHPStan WC_Order insertion point, found {phpstan.count(phpstan_old)}')
phpstan_path.write_text(phpstan.replace(phpstan_old, phpstan_new, 1))

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
