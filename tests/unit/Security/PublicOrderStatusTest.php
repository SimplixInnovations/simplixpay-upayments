<?php

namespace Simplix\Pay\UPayments\Tests\Security;

use PHPUnit\Framework\TestCase;
use Simplix\Pay\UPayments\Security\PublicOrderStatus;

final class PublicOrderStatusTest extends TestCase {
    protected function setUp(): void {
        \simplixpay_test_reset_public_order_status();
    }

    public function test_order_id_parser_accepts_only_bounded_positive_decimal_strings(): void {
        self::assertSame(1, PublicOrderStatus::parse_order_id('1'));
        self::assertSame(987654, PublicOrderStatus::parse_order_id('987654'));
        foreach (array(null, false, 1, array('1'), '', '0', '-1', '+1', '01', '1e3', ' 1', '1 ', "1\n", str_repeat('9', 19)) as $value) {
            self::assertNull(PublicOrderStatus::parse_order_id($value));
        }
    }

    public function test_status_normalization_preserves_only_the_narrow_public_allowlist(): void {
        foreach (array('wait', 'pending', 'failed', 'completed', 'cancelled') as $status) {
            self::assertSame($status, PublicOrderStatus::normalize_status($status));
        }
        foreach (array(null, false, 1, array('completed'), '', 'processing', 'refunded', '<script>') as $value) {
            self::assertSame('wait', PublicOrderStatus::normalize_status($value));
        }
    }

    public function test_authorization_requires_upayments_and_exact_owner_or_order_key(): void {
        $owned = $this->order('upayments', 42, 'wc_order_secret');
        $guest = $this->order('upayments', 0, 'wc_order_guest_secret');

        self::assertTrue(PublicOrderStatus::authorize_order($owned, null, 42, true));
        self::assertFalse(PublicOrderStatus::authorize_order($owned, null, 43, true));
        self::assertFalse(PublicOrderStatus::authorize_order($owned, null, 0, false));
        self::assertTrue(PublicOrderStatus::authorize_order($owned, 'wc_order_secret', 0, false));
        self::assertTrue(PublicOrderStatus::authorize_order($guest, 'wc_order_guest_secret', 0, false));
        self::assertFalse(PublicOrderStatus::authorize_order($owned, 'wc_order_wrong', 42, false));
        self::assertFalse(PublicOrderStatus::authorize_order($this->order('cod', 42, 'wc_order_secret'), 'wc_order_secret', 42, true));
        self::assertFalse(PublicOrderStatus::authorize_order(false, 'wc_order_secret', 42, true));
        self::assertFalse(PublicOrderStatus::authorize_order(new \stdClass(), 'wc_order_secret', 42, true));
    }

    public function test_order_key_authorization_rejects_empty_oversized_control_and_non_string_values(): void {
        $order = $this->order('upayments', 0, 'wc_order_secret');
        foreach (array(null, false, 1, array('wc_order_secret'), '', "wc order", "wc\torder", "wc\x7Forder", str_repeat('a', 129)) as $key) {
            self::assertFalse(PublicOrderStatus::authorize_order($order, $key, 0, false));
        }
        self::assertFalse(PublicOrderStatus::authorize_order($order, 'WC_ORDER_SECRET', 0, false));
        self::assertTrue(PublicOrderStatus::authorize_order($order, 'wc_order_secret', 0, false));
    }

    public function test_handle_rejects_non_get_and_invalid_or_missing_order_identifiers(): void {
        $GLOBALS['simplixpay_test_status_orders'][42] = $this->order('upayments', 0, 'wc_order_secret', 'completed');
        foreach (array('POST', 'G ET', 'G\\ET', "GET\n", '<GET>') as $method) {
            $_SERVER['REQUEST_METHOD'] = $method;
            $_GET = array('wc_order_id' => '42', 'key' => 'wc_order_secret');
            $this->assert_response(404, array('status' => 'error', 'message' => 'Order status unavailable.'));
        }

        foreach (array(array(), array('wc_order_id' => '0'), array('wc_order_id' => array('42')), array('wc_order_id' => '999')) as $query) {
            \simplixpay_test_reset_public_order_status();
            $_GET = $query;
            $this->assert_response(404, array('status' => 'error', 'message' => 'Order status unavailable.'));
        }
    }

    public function test_handle_rejects_non_upayments_and_unauthorized_orders(): void {
        $GLOBALS['simplixpay_test_status_orders'][42] = $this->order('cod', 42, 'wc_order_secret', 'completed');
        $_GET = array('wc_order_id' => '42', 'key' => 'wc_order_secret');
        $this->assert_response(404, array('status' => 'error', 'message' => 'Order status unavailable.'));

        \simplixpay_test_reset_public_order_status();
        $GLOBALS['simplixpay_test_status_orders'][42] = $this->order('upayments', 42, 'wc_order_secret', 'completed');
        $_GET = array('wc_order_id' => '42', 'key' => 'wrong');
        $this->assert_response(404, array('status' => 'error', 'message' => 'Order status unavailable.'));
    }

    public function test_handle_allows_exact_logged_in_owner_and_returns_only_narrow_status_payload(): void {
        $GLOBALS['simplixpay_test_status_orders'][42] = $this->order('upayments', 42, 'wc_order_secret', 'completed');
        $GLOBALS['simplixpay_test_status_logged_in'] = true;
        $GLOBALS['simplixpay_test_status_user_id'] = 42;
        $_GET = array('wc_order_id' => '42');

        $this->assert_response(200, array('status' => 'completed', 'message' => ''));
    }

    public function test_handle_allows_exact_guest_key_after_unslashing_and_unknown_status_fails_closed(): void {
        $GLOBALS['simplixpay_test_status_orders'][43] = $this->order('upayments', 0, "wc_order_'secret", 'CAPTURED');
        $_GET = array('wc_order_id' => '43', 'key' => "wc_order_\\'secret");

        $this->assert_response(200, array('status' => 'wait', 'message' => ''));
    }

    private function order($payment_method, $user_id, $order_key, $status = 'wait') {
        return new \SimplixPay_Test_Status_Order($payment_method, $user_id, $order_key, $status);
    }

    private function assert_response($status_code, array $payload): void {
        try {
            PublicOrderStatus::handle();
            self::fail('Expected captured JSON response.');
        } catch (\SimplixPay_Test_Json_Response $response) {
            self::assertSame($status_code, $response->status_code);
            self::assertSame($payload, $response->payload);
            self::assertSame(array('status', 'message'), array_keys($response->payload));
        }
    }
}
