<?php

namespace Simplix\Pay\UPayments\Payment;

function time() {
    return isset($GLOBALS['simplixpay_test_payment_runtime_time'])
        ? (int) $GLOBALS['simplixpay_test_payment_runtime_time']
        : \time();
}

function wp_generate_uuid4() {
    $next = isset($GLOBALS['simplixpay_test_payment_runtime_uuid_sequence'])
        ? (int) $GLOBALS['simplixpay_test_payment_runtime_uuid_sequence'] + 1
        : 1;
    $GLOBALS['simplixpay_test_payment_runtime_uuid_sequence'] = $next;

    return sprintf('00000000-0000-4000-8000-%012x', $next);
}

namespace Simplix\Pay\UPayments\Tests\Payment;

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use Simplix\Pay\UPayments\Payment\CheckoutOrchestrator;

final class CheckoutOrchestratorGateway {
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

    public function test_classic_rejects_explicitly_opted_out_subscription_product_before_provider_request(): void {
        $_POST = array(
            'upay_subscription_plan' => 'monthly',
            'upay_subscription_interval' => '1',
        );

        $gateway = new CheckoutOrchestratorGateway();
        $gateway->paymentData = null;
        $product = new \WC_Product('custom_type', 789);
        $order = new \WC_Order(
            42,
            'KWD',
            '10.000',
            array(new \WC_Order_Item_Product($product))
        );
        $GLOBALS['simplixpay_test_status_orders'][42] = $order;
        $GLOBALS['simplixpay_test_subscription_presentation']['meta'][789]['_upay_disable_subscription'] = 'yes';

        $request_body_calls = 0;
        $provider_requests = array();
        $orchestrator = new CheckoutOrchestrator(
            $gateway,
            static function () use (&$request_body_calls) {
                $request_body_calls++;
                return '';
            },
            static function ($route, $method, $body) use (&$provider_requests) {
                $provider_requests[] = array($route, $method, $body);
                return array();
            }
        );

        $result = $orchestrator->process(42);

        self::assertSame('failure', $result['result']);
        self::assertSame(0, $request_body_calls);
        self::assertSame(0, $gateway->paymentIconCalls);
        self::assertSame(array(), $provider_requests);
        self::assertContains(
            array('warning', 'Subscription plan rejected: product-level opt-out.'),
            $gateway->logs
        );
    }

    public function test_store_api_rejects_explicitly_opted_out_subscription_product_before_provider_request(): void {
        if (!defined('REST_REQUEST')) {
            define('REST_REQUEST', true);
        }
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/wp-json/wc/store/v1/checkout';

        $gateway = new CheckoutOrchestratorGateway();
        $gateway->paymentData = null;
        $product = new \WC_Product('custom_type', 789);
        $order = new \WC_Order(
            42,
            'KWD',
            '10.000',
            array(new \WC_Order_Item_Product($product))
        );
        $GLOBALS['simplixpay_test_status_orders'][42] = $order;
        $GLOBALS['simplixpay_test_subscription_presentation']['meta'][789]['_upay_disable_subscription'] = 'yes';

        $request_body_calls = 0;
        $provider_requests = array();
        $orchestrator = new CheckoutOrchestrator(
            $gateway,
            static function () use (&$request_body_calls) {
                $request_body_calls++;
                return json_encode(array(
                    'extensions' => array(
                        'upayments' => array(
                            'upay_subscription_plan' => 'monthly',
                            'upay_subscription_interval' => '1',
                        ),
                    ),
                ));
            },
            static function ($route, $method, $body) use (&$provider_requests) {
                $provider_requests[] = array($route, $method, $body);
                return array();
            }
        );

        $result = $orchestrator->process(42);

        self::assertSame('failure', $result['result']);
        self::assertSame(1, $request_body_calls);
        self::assertSame(0, $gateway->paymentIconCalls);
        self::assertSame(array(), $provider_requests);
        self::assertContains(
            array('warning', 'Subscription plan rejected: product-level opt-out.'),
            $gateway->logs
        );
    }

    public function test_same_second_retries_use_distinct_provider_order_ids(): void {
        $GLOBALS['simplixpay_test_payment_runtime_time'] = 1700000000;
        $gateway = new CheckoutOrchestratorGateway();
        $order = new \WC_Order(
            42,
            'KWD',
            '10.000',
            array(new \WC_Order_Item_Product(new \WC_Product('simple')))
        );
        $GLOBALS['simplixpay_test_status_orders'][42] = $order;
        $provider_order_ids = array();

        $orchestrator = new CheckoutOrchestrator(
            $gateway,
            static function () { return ''; },
            static function ($route, $method, $body) use (&$provider_order_ids) {
                if ($route === 'charge' && $method === 'POST' && is_string($body)) {
                    $payload = json_decode($body, true);
                    if (is_array($payload) && isset($payload['order']['id'])) {
                        $provider_order_ids[] = $payload['order']['id'];
                    }
                }
                return array();
            }
        );

        $first = $orchestrator->process(42);
        $second = $orchestrator->process(42);

        self::assertSame('failure', $first['result']);
        self::assertSame('failure', $second['result']);
        self::assertCount(2, $provider_order_ids);
        self::assertIsString($provider_order_ids[0]);
        self::assertIsString($provider_order_ids[1]);
        self::assertMatchesRegularExpression('/^[0-9a-f]{32}$/', $provider_order_ids[0]);
        self::assertMatchesRegularExpression('/^[0-9a-f]{32}$/', $provider_order_ids[1]);
        self::assertNotSame($provider_order_ids[0], $provider_order_ids[1]);
    }
}
