<?php

namespace Simplix\Pay\UPayments\Tests\Payment;

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use Simplix\Pay\UPayments\Payment\PaymentLifecycle;

final class PaymentLifecycleCompletionFailureOrder {
    private $id = 42;
    private $status = 'pending';
    private $transaction_id = '';
    private $pending_meta = array();
    private $durable_meta = array();

    public function get_id() { return $this->id; }
    public function get_status() { return $this->status; }
    public function has_status($status) { return $this->status === $status; }
    public function is_paid() { return false; }
    public function get_transaction_id() { return $this->transaction_id; }
    public function set_transaction_id($id) { $this->transaction_id = (string) $id; }
    public function get_meta($key) {
        if (array_key_exists($key, $this->pending_meta)) {
            return $this->pending_meta[$key];
        }
        return array_key_exists($key, $this->durable_meta) ? $this->durable_meta[$key] : '';
    }
    public function update_meta_data($key, $value) { $this->pending_meta[$key] = $value; }
    public function delete_meta_data($key) {
        unset($this->pending_meta[$key], $this->durable_meta[$key]);
    }
    public function payment_complete($transaction_id = '') {
        $this->transaction_id = (string) $transaction_id;
        $this->save();
    }
    public function save() {
        foreach ($this->pending_meta as $key => $value) {
            $this->durable_meta[$key] = $value;
        }
        $this->pending_meta = array();
        return $this->id;
    }
    public function durable_meta($key) {
        return array_key_exists($key, $this->durable_meta) ? $this->durable_meta[$key] : null;
    }
}

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class PaymentLifecycleTest extends TestCase {
    protected function setUp(): void {
        require_once dirname(__DIR__, 2) . '/support/wordpress-payment-runtime.php';
        \simplixpay_test_reset_payment_runtime();
    }

    public function test_reconcile_rejects_noncanonical_order_ids_before_woo_lookup(): void {
        foreach (array("42\n", '42 ', '+42', '42.0', '4e1', '042', 42.0) as $invalid) {
            \simplixpay_test_reset_payment_runtime();

            PaymentLifecycle::reconcile_order($invalid);

            self::assertSame(array(), $GLOBALS['simplixpay_test_wc_get_order_calls'], var_export($invalid, true));
        }
    }

    public function test_reconcile_keeps_positive_integer_order_ids_compatible(): void {
        PaymentLifecycle::reconcile_order(42);

        self::assertSame(array(42), $GLOBALS['simplixpay_test_wc_get_order_calls']);
    }

    public function test_failed_payment_complete_postcondition_does_not_leave_durable_capture_metadata(): void {
        $order = new PaymentLifecycleCompletionFailureOrder();
        $transaction = array(
            'payment_id' => 'pay-123',
            'track_id' => 'track-abc',
            'payment_type' => 'KNET',
            'reference' => '42',
        );

        $method = new \ReflectionMethod(PaymentLifecycle::class, 'apply_captured');
        $method->setAccessible(true);
        $result = $method->invoke(null, new \stdClass(), $order, $transaction);

        self::assertFalse($result);
        self::assertNull($order->durable_meta('UPayments_Result'));
        self::assertNull($order->durable_meta('UPayments_PaymentID'));
        self::assertNull($order->durable_meta('UPayments_TrackID'));
        self::assertNull($order->durable_meta('UPayments_payment_type'));
        self::assertNull($order->durable_meta('UPayments_Ref'));
        self::assertNull($order->durable_meta('_payment_method_title'));
        self::assertNull($order->durable_meta('_upay_verified_capture'));
        self::assertNull($order->durable_meta('UPayments_webhook_triggered'));
    }

    public function test_request_merge_is_presence_aware_and_conflict_safe(): void {
        self::assertSame(
            array('valid' => true, 'present' => false, 'value' => null),
            PaymentLifecycle::merge_request_value(array(), array(), 'track_id')
        );
        self::assertSame(
            array('valid' => true, 'present' => true, 'value' => 'track-a'),
            PaymentLifecycle::merge_request_value(array('track_id' => 'track-a'), array(), 'track_id')
        );
        self::assertFalse(
            PaymentLifecycle::merge_request_value(
                array('track_id' => 'track-a'),
                array('track_id' => 'track-b'),
                'track_id'
            )['valid']
        );
    }
}
