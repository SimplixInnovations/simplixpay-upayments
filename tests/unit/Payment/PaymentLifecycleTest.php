<?php

namespace Simplix\Pay\UPayments\Tests\Payment;

use PHPUnit\Framework\TestCase;
use Simplix\Pay\UPayments\Payment\PaymentLifecycle;

final class PaymentLifecycleTest extends TestCase {
    protected function setUp(): void {
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
