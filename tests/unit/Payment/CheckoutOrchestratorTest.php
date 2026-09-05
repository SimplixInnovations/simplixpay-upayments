<?php

namespace Simplix\Pay\UPayments\Tests\Payment;

use PHPUnit\Framework\TestCase;
use Simplix\Pay\UPayments\Payment\CheckoutOrchestrator;

final class CheckoutOrchestratorGateway {
    public $domain = 'upayments';
}

final class CheckoutOrchestratorTest extends TestCase {
    protected function setUp(): void {
        \simplixpay_test_reset_payment_runtime();
    }

    public function test_process_rejects_noncanonical_order_ids_before_woo_lookup(): void {
        $orchestrator = new CheckoutOrchestrator(
            new CheckoutOrchestratorGateway(),
            static function () { return ''; },
            static function () { return array(); }
        );

        foreach (array("42\n", '42 ', '+42', '42.0', '4e1', '042') as $invalid) {
            \simplixpay_test_reset_payment_runtime();

            $result = $orchestrator->process($invalid);

            self::assertSame('failure', $result['result'], var_export($invalid, true));
            self::assertSame(array(), $GLOBALS['simplixpay_test_wc_get_order_calls'], var_export($invalid, true));
        }
    }

    public function test_process_keeps_positive_integer_order_ids_compatible(): void {
        $orchestrator = new CheckoutOrchestrator(
            new CheckoutOrchestratorGateway(),
            static function () { return ''; },
            static function () { return array(); }
        );

        $result = $orchestrator->process(42);

        self::assertSame('failure', $result['result']);
        self::assertSame(array(42), $GLOBALS['simplixpay_test_wc_get_order_calls']);
    }
}
