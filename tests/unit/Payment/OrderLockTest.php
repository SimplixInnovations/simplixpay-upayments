<?php

namespace Simplix\Pay\UPayments\Tests\Payment;

use PHPUnit\Framework\TestCase;
use Simplix\Pay\UPayments\Payment\OrderLock;

final class OrderLockTest extends TestCase {
    protected function setUp(): void {
        \simplixpay_test_reset_wp_options();
    }

    public function test_invalid_order_identity_fails_without_mutation(): void {
        self::assertNull(OrderLock::acquire(0));
        self::assertNull(OrderLock::acquire(-1));
        self::assertSame(array(), $GLOBALS['simplixpay_test_options']);
    }

    public function test_atomic_acquire_blocks_a_second_live_owner(): void {
        $token = OrderLock::acquire(42);

        self::assertIsString($token);
        self::assertMatchesRegularExpression('/^[a-f0-9]{32}$/', $token);
        self::assertNull(OrderLock::acquire(42));
        self::assertArrayHasKey('simplixpay_upay_order_lock_v1_42', $GLOBALS['simplixpay_test_options']);
    }

    public function test_release_requires_the_exact_owner_token(): void {
        $token = OrderLock::acquire(42);
        $name = 'simplixpay_upay_order_lock_v1_42';

        OrderLock::release(42, 'wrong-token');
        self::assertArrayHasKey($name, $GLOBALS['simplixpay_test_options']);

        OrderLock::release(42, $token);
        self::assertArrayNotHasKey($name, $GLOBALS['simplixpay_test_options']);
        self::assertContains(array($name, 'options'), $GLOBALS['simplixpay_test_cache_deletes']);
    }

    public function test_expired_record_is_replaced_only_by_compare_and_swap(): void {
        $name = 'simplixpay_upay_order_lock_v1_42';
        $GLOBALS['simplixpay_test_options'][$name] = '1:' . str_repeat('a', 32);

        $token = OrderLock::acquire(42);

        self::assertIsString($token);
        self::assertStringEndsWith(':' . $token, $GLOBALS['simplixpay_test_options'][$name]);
        self::assertContains(array($name, 'options'), $GLOBALS['simplixpay_test_cache_deletes']);
    }

    public function test_stale_takeover_cannot_replace_a_newer_competing_owner(): void {
        $name = 'simplixpay_upay_order_lock_v1_42';
        $newer = (string) (time() + 120) . ':' . str_repeat('b', 32);
        $GLOBALS['simplixpay_test_options'][$name] = '1:' . str_repeat('a', 32);
        $GLOBALS['wpdb']->before_query = function () use ($name, $newer) {
            $GLOBALS['simplixpay_test_options'][$name] = $newer;
        };

        self::assertNull(OrderLock::acquire(42));
        self::assertSame($newer, $GLOBALS['simplixpay_test_options'][$name]);
        self::assertSame(array(), $GLOBALS['simplixpay_test_cache_deletes']);
    }

    public function test_malformed_existing_record_fails_closed_without_deletion(): void {
        $name = 'simplixpay_upay_order_lock_v1_42';
        $GLOBALS['simplixpay_test_options'][$name] = 'malformed';

        self::assertNull(OrderLock::acquire(42));
        self::assertSame('malformed', $GLOBALS['simplixpay_test_options'][$name]);
    }
}
