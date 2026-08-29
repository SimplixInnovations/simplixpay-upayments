<?php

namespace Simplix\Pay\UPayments\Tests\Payment;

use PHPUnit\Framework\TestCase;
use Simplix\Pay\UPayments\Payment\CheckoutPayload;

final class CheckoutPayloadTest extends TestCase {
    public function test_decimal_comparison_preserves_exact_provider_economics(): void {
        self::assertSame(-1, CheckoutPayload::compare_nonnegative_decimal_strings('9.99', '10'));
        self::assertSame(0, CheckoutPayload::compare_nonnegative_decimal_strings('1.0', '1.000'));
        self::assertSame(1, CheckoutPayload::compare_nonnegative_decimal_strings('1.01', '1.001'));
    }

    public function test_amount_token_rejects_ambiguous_or_nonpositive_values(): void {
        self::assertSame('0.125', CheckoutPayload::build_amount_json_token('0.125'));
        self::assertNull(CheckoutPayload::build_amount_json_token('0.00'));
        self::assertNull(CheckoutPayload::build_amount_json_token('01.00'));
        self::assertNull(CheckoutPayload::build_amount_json_token('1e3'));
        self::assertNull(CheckoutPayload::build_amount_json_token(1.0));
    }

    public function test_json_number_injection_never_quotes_provider_amount(): void {
        $sentinel = '__UPAY_ORDER_AMOUNT_SENTINEL__';
        $encoded = '{"order":{"amount":"' . $sentinel . '"}}';

        self::assertSame(
            '{"order":{"amount":10.500}}',
            CheckoutPayload::inject_amount_token_into_payload_json($encoded, array($sentinel => '10.500'))
        );
        self::assertNull(
            CheckoutPayload::inject_amount_token_into_payload_json(
                '{"a":"' . $sentinel . '","b":"' . $sentinel . '"}',
                array($sentinel => '10.500')
            )
        );
    }

    public function test_checkout_context_and_redirects_fail_closed(): void {
        self::assertSame(
            '/wc/store/v1/checkout',
            CheckoutPayload::normalize_store_api_route('/shop/wp-json/wc/store/v1/checkout')
        );
        self::assertTrue(
            CheckoutPayload::classify_checkout_request_context(true, '/wc/store/v1/checkout/', 'post')
        );
        self::assertFalse(
            CheckoutPayload::classify_checkout_request_context(true, '/wc/store/v1/cart', 'POST')
        );
        self::assertSame(
            'https://pay.example/redirect',
            CheckoutPayload::normalize_upayments_redirect_url(' https://pay.example/redirect ')
        );
        self::assertNull(CheckoutPayload::normalize_upayments_redirect_url('javascript:alert(1)'));
        self::assertNull(
            CheckoutPayload::normalize_upayments_redirect_url("https://pay.example/ok\r\nX-Test: bad")
        );
    }
}
