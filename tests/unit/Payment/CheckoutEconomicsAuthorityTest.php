<?php

namespace Simplixi\SUPCheckout\Tests\Payment;

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use Simplixi\SUPCheckout\Payment\CheckoutOrchestrator;

final class CheckoutEconomicsAuthorityGateway {
    public $domain = 'upayments';
    public $paymentData = array('whitelabled' => false);
    public $paymentIconCalls = 0;
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
        $this->paymentIconCalls++;
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
final class CheckoutEconomicsAuthorityTest extends TestCase {
    protected function setUp(): void {
        require_once dirname(__DIR__, 2) . '/support/wordpress-payment-runtime.php';
        \supcheckout_test_reset_payment_runtime();
    }

    public function test_non_divisible_descriptive_line_does_not_block_authoritative_order_charge(): void {
        $gateway = new CheckoutEconomicsAuthorityGateway();
        $order = new \WC_Order(
            42,
            'KWD',
            '10.000',
            array(
                new \WC_Order_Item_Product(
                    new \WC_Product('simple', 501),
                    3,
                    '10.000',
                    'Three-for-ten extension bundle'
                ),
            )
        );
        $GLOBALS['supcheckout_test_status_orders'][42] = $order;
        $requests = array();

        $orchestrator = new CheckoutOrchestrator(
            $gateway,
            static function () { return ''; },
            static function ($route, $method, $body) use (&$requests) {
                $requests[] = array($route, $method, $body);
                return array();
            }
        );

        $orchestrator->process(42);

        self::assertCount(1, $requests, 'Descriptive product serialization must not suppress a valid Charge request.');
        self::assertSame('charge', $requests[0][0]);
        self::assertSame('POST', $requests[0][1]);
        self::assertIsString($requests[0][2]);

        $payload = json_decode($requests[0][2], true);
        self::assertIsArray($payload);
        self::assertSame('10.000', $payload['order']['amount'], 'Finalized Woo order total remains payment authority.');
        self::assertSame('KWD', $payload['order']['currency'], 'Finalized Woo order currency remains payment authority.');
        self::assertArrayNotHasKey(
            'products',
            $payload,
            'Unrepresentable descriptive line economics must be omitted rather than rounded or made payment-authoritative.'
        );
    }
}
