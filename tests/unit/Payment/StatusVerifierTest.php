<?php

namespace Simplix\Pay\UPayments\Tests\Payment;

use PHPUnit\Framework\TestCase;
use Simplix\Pay\UPayments\Payment\ProviderResult;
use Simplix\Pay\UPayments\Payment\StatusVerifier;

final class StatusVerifierGateway {
    public $apiKey = 'test-api-key-secret';
    public $test_mode = true;
    public $host = 'sandboxapi.upayments.com';
    public $url_suffix = '';

    public function getMode() {
        return $this->test_mode;
    }

    public function getAPIUrl($route = '') {
        return 'https://' . $this->host . '/api/v1/' . $route . $this->url_suffix;
    }

    public function getCurrencyCode($currency) {
        return strtoupper((string) $currency);
    }
}

final class StatusVerifierOrder {
    private $id;
    private $requested_order_id;

    public $currency = 'KWD';
    public $total = '10.000';

    public function __construct($id, $requested_order_id) {
        $this->id = (int) $id;
        $this->requested_order_id = (string) $requested_order_id;
    }

    public function get_id() {
        return $this->id;
    }

    public function get_currency() {
        return $this->currency;
    }

    public function get_total() {
        return $this->total;
    }

    public function get_meta($key) {
        return $key === 'UPayments_order_id' ? $this->requested_order_id : '';
    }
}

final class StatusVerifierTest extends TestCase {
    protected function setUp(): void {
        \simplixpay_test_reset_wp_options();
        \simplixpay_test_reset_wp_http();
    }

    public function test_invalid_boundaries_fail_before_rate_or_http_mutation(): void {
        $gateway = new StatusVerifierGateway();
        $order = new StatusVerifierOrder(42, 'merchant-42');

        self::assertSame('invalid_request', StatusVerifier::verify(null, $order, 'track-abc')['reason']);
        self::assertSame('invalid_request', StatusVerifier::verify($gateway, null, 'track-abc')['reason']);
        self::assertSame('invalid_track_id', StatusVerifier::verify($gateway, $order, "bad\ntrack")['reason']);
        $gateway->apiKey = '';
        self::assertSame('credentials_missing', StatusVerifier::verify($gateway, $order, 'track-abc')['reason']);
        self::assertSame(array(), $GLOBALS['simplixpay_test_http_calls']);
        self::assertSame(array(), $GLOBALS['simplixpay_test_options']);
    }

    public function test_disallowed_destination_is_rejected_before_bearer_or_rate_slot(): void {
        $gateway = new StatusVerifierGateway();
        $order = new StatusVerifierOrder(42, 'merchant-42');

        $gateway->host = 'attacker.example';
        self::assertSame('status_url_invalid', StatusVerifier::verify($gateway, $order, 'track-abc')['reason']);

        $gateway->host = 'sandboxapi.upayments.com';
        $gateway->url_suffix = '?redirect=attacker.example';
        self::assertSame('status_url_invalid', StatusVerifier::verify($gateway, $order, 'track-abc')['reason']);
        self::assertSame(array(), $GLOBALS['simplixpay_test_http_calls']);
        self::assertSame(array(), $GLOBALS['simplixpay_test_options']);
    }

    public function test_hardened_transport_binds_an_exact_captured_transaction(): void {
        $gateway = new StatusVerifierGateway();
        $order = new StatusVerifierOrder(42, 'merchant-42');
        $this->respond_with_transaction($this->transaction($order));

        $result = StatusVerifier::verify($gateway, $order, 'track-abc');

        self::assertTrue($result['authenticated']);
        self::assertTrue($result['bound']);
        self::assertSame(ProviderResult::CAPTURED, $result['classification']);
        self::assertSame('captured', $result['reason']);
        self::assertCount(1, $GLOBALS['simplixpay_test_http_calls']);
        $call = $GLOBALS['simplixpay_test_http_calls'][0];
        self::assertSame('https://sandboxapi.upayments.com/api/v1/get-payment-status/track-abc', $call['url']);
        self::assertSame(15, $call['args']['timeout']);
        self::assertSame(0, $call['args']['redirection']);
        self::assertTrue($call['args']['sslverify']);
        self::assertSame('application/json', $call['args']['headers']['Accept']);
        self::assertSame('Bearer test-api-key-secret', $call['args']['headers']['Authorization']);
        self::assertStringNotContainsString('test-api-key-secret', implode('|', array_keys($GLOBALS['simplixpay_test_options'])));
    }

    public function test_network_and_protocol_failures_remain_unauthenticated(): void {
        $gateway = new StatusVerifierGateway();
        $order = new StatusVerifierOrder(42, 'merchant-42');

        $GLOBALS['simplixpay_test_http_response'] = new \SimplixPay_Test_WP_Error();
        self::assertSame('network_error', StatusVerifier::verify($gateway, $order, 'track-network')['reason']);

        $this->reset_fixtures();
        $GLOBALS['simplixpay_test_http_response'] = array('response' => array('code' => 200), 'body' => '{}');
        self::assertSame('unexpected_http_200', StatusVerifier::verify($gateway, $order, 'track-http')['reason']);

        $this->reset_fixtures();
        $GLOBALS['simplixpay_test_http_response'] = array('response' => array('code' => 201), 'body' => '');
        self::assertSame('empty_response', StatusVerifier::verify($gateway, $order, 'track-empty')['reason']);

        $this->reset_fixtures();
        $GLOBALS['simplixpay_test_http_response'] = array('response' => array('code' => 201), 'body' => '{bad-json');
        self::assertSame('invalid_status_response', StatusVerifier::verify($gateway, $order, 'track-json')['reason']);

        $this->reset_fixtures();
        $GLOBALS['simplixpay_test_http_response'] = array(
            'response' => array('code' => 201),
            'body'     => json_encode(array('status' => false, 'data' => array())),
        );
        $result = StatusVerifier::verify($gateway, $order, 'track-status');
        self::assertSame('invalid_status_response', $result['reason']);
        self::assertFalse($result['authenticated']);
        self::assertFalse($result['bound']);
    }

    public function test_binding_rejects_identity_currency_and_amount_mismatches(): void {
        $gateway = new StatusVerifierGateway();
        $order = new StatusVerifierOrder(42, 'merchant-42');
        $base = $this->transaction($order);
        $variants = array(
            'track_id'                    => array('different', 'binding_track_id'),
            'merchant_requested_order_id' => array('different', 'binding_merchant_requested_order_id'),
            'reference'                   => array('99', 'binding_reference'),
            'currency_type'               => array('USD', 'binding_currency'),
            'total_price'                 => array('9.000', 'binding_amount'),
        );

        foreach ($variants as $field => $case) {
            $transaction = $base;
            $transaction[$field] = $case[0];
            $result = StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $transaction);
            self::assertTrue($result['authenticated'], $field);
            self::assertFalse($result['bound'], $field);
            self::assertSame($case[1], $result['reason'], $field);
        }
    }

    public function test_capture_requires_payment_id_while_nonterminal_results_bind_fail_closed(): void {
        $gateway = new StatusVerifierGateway();
        $order = new StatusVerifierOrder(42, 'merchant-42');

        $captured = $this->transaction($order);
        unset($captured['payment_id']);
        self::assertSame(
            'captured_payment_id_missing',
            StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $captured)['reason']
        );

        foreach (array('PENDING', null, 'Processing') as $provider_result) {
            $transaction = $this->transaction($order, $provider_result);
            unset($transaction['payment_id']);
            $result = StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $transaction);
            self::assertTrue($result['authenticated']);
            self::assertTrue($result['bound']);
            self::assertNotSame(ProviderResult::CAPTURED, $result['classification']);
        }
    }

    public function test_exact_decimal_binding_accepts_only_numeric_equality(): void {
        $gateway = new StatusVerifierGateway();
        $order = new StatusVerifierOrder(42, 'merchant-42');
        $order->total = '10.00';

        $trailing = $this->transaction($order);
        $trailing['total_price'] = '10.0000';
        self::assertTrue(StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $trailing)['bound']);

        $fraction = $this->transaction($order);
        $fraction['total_price'] = '10.004';
        self::assertSame(
            'binding_amount',
            StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $fraction)['reason']
        );

        $exponent = $this->transaction($order);
        $exponent['total_price'] = '1e1';
        self::assertSame(
            'amount_invalid',
            StatusVerifier::bind_transaction($gateway, $order, 'track-abc', $exponent)['reason']
        );
    }

    private function reset_fixtures(): void {
        \simplixpay_test_reset_wp_options();
        \simplixpay_test_reset_wp_http();
    }

    private function transaction(StatusVerifierOrder $order, $result = 'CAPTURED'): array {
        return array(
            'result'                      => $result,
            'track_id'                    => 'track-abc',
            'merchant_requested_order_id' => $order->get_meta('UPayments_order_id'),
            'total_price'                 => $order->get_total(),
            'currency_type'               => $order->get_currency(),
            'reference'                   => (string) $order->get_id(),
            'payment_id'                  => 'payment-123',
        );
    }

    private function respond_with_transaction(array $transaction): void {
        $GLOBALS['simplixpay_test_http_response'] = array(
            'response' => array('code' => 201),
            'body'     => json_encode(array(
                'status' => true,
                'data'   => array('transaction' => $transaction),
            )),
        );
    }
}
