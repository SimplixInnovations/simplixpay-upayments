<?php

namespace Simplix\Pay\UPayments\Tests\Payment;

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use Simplix\Pay\UPayments\Payment\CheckoutOrchestrator;

final class CheckoutOrchestratorGateway {
    public $domain = 'upayments';
    public $paymentData = array('whitelabled' => false);
    public $autoDeduction = 'no';
    public $saveCardEnabled = 'no';
    public $multiMerchant = 'no';
    public $ibanNumber = '';
    public $knetCharge = '1.000';
    public $ccCharge = '1.000';
    public $knetChargeType = 'fixed';
    public $ccChargeType = 'fixed';
    public $apiKey = 'test-api-key';
    public $logs = array();

    public function getPaymentIcons() {
        return $this->paymentData;
    }

    public function getCurrencyCode($currency) {
        return $currency;
    }

    public function log($message, $level = 'info') {
        $this->logs[] = array($level, $message);
    }
}

#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class CheckoutOrchestratorTest extends TestCase {
    protected function setUp(): void {
        require_once dirname(__DIR__, 2) . '/support/wordpress-payment-runtime.php';
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

    public function test_currency_with_terminal_newline_is_rejected_before_provider_request(): void {
        $gateway = new CheckoutOrchestratorGateway();
        $order = new \WC_Order(
            42,
            "KWD\n",
            '10.000',
            array(new \WC_Order_Item_Product(new \WC_Product('simple')))
        );
        $GLOBALS['simplixpay_test_status_orders'][42] = $order;
        $requests = array();

        $orchestrator = new CheckoutOrchestrator(
            $gateway,
            static function () { return ''; },
            static function ($route, $method, $body) use (&$requests) {
                $requests[] = array($route, $method, $body);
                return null;
            }
        );

        $result = $orchestrator->process(42);

        self::assertSame('failure', $result['result']);
        self::assertSame(array(), $requests);
    }

    public function test_iban_with_terminal_newline_is_rejected_before_provider_request(): void {
        $gateway = new CheckoutOrchestratorGateway();
        $gateway->multiMerchant = 'yes';
        $gateway->ibanNumber = "KW81CBKU0000000000001234560101\n";
        $order = new \WC_Order(
            42,
            'KWD',
            '10.000',
            array(new \WC_Order_Item_Product(new \WC_Product('simple')))
        );
        $GLOBALS['simplixpay_test_status_orders'][42] = $order;
        $requests = array();

        $orchestrator = new CheckoutOrchestrator(
            $gateway,
            static function () { return ''; },
            static function ($route, $method, $body) use (&$requests) {
                $requests[] = array($route, $method, $body);
                return null;
            }
        );

        $result = $orchestrator->process(42);

        self::assertSame('failure', $result['result']);
        self::assertSame(array(), $requests);
    }

    public function test_multimerchant_charge_newline_already_fails_closed_before_provider_request(): void {
        $gateway = new CheckoutOrchestratorGateway();
        $gateway->multiMerchant = 'yes';
        $gateway->ibanNumber = 'KW81CBKU0000000000001234560101';
        $gateway->knetCharge = "1.000\n";
        $order = new \WC_Order(
            42,
            'KWD',
            '10.000',
            array(new \WC_Order_Item_Product(new \WC_Product('simple')))
        );
        $GLOBALS['simplixpay_test_status_orders'][42] = $order;
        $requests = array();

        $orchestrator = new CheckoutOrchestrator(
            $gateway,
            static function () { return ''; },
            static function ($route, $method, $body) use (&$requests) {
                $requests[] = array($route, $method, $body);
                return null;
            }
        );

        $result = $orchestrator->process(42);

        self::assertSame('failure', $result['result']);
        self::assertSame(array(), $requests);
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
