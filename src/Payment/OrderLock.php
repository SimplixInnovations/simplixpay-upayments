<?php

namespace Simplix\Pay\UPayments\Payment;

defined('ABSPATH') || exit;

/**
 * Database-atomic, self-expiring per-order lifecycle lock.
 *
 * It prevents browser return, webhook and reconciliation workers from racing
 * the same WooCommerce payment state transition. The unique option name makes
 * acquisition atomic across PHP workers without requiring optional DB advisory
 * lock support.
 */
final class OrderLock {
    private const PREFIX = 'simplixpay_upay_order_lock_v1_';
    private const TTL = 45;

    /**
     * @param int $order_id
     * @return string|null Opaque owner token on success.
     */
    public static function acquire($order_id) {
        $order_id = (int) $order_id;
        if ($order_id <= 0) {
            return null;
        }

        $name = self::PREFIX . $order_id;
        $token = self::token();
        $record = array(
            'token' => $token,
            'expires' => time() + self::TTL,
        );

        if (add_option($name, $record, '', 'no')) {
            return $token;
        }

        $existing = get_option($name, null);
        if (!is_array($existing)
            || !isset($existing['expires'])
            || !is_numeric($existing['expires'])
            || (int) $existing['expires'] >= time()
        ) {
            return null;
        }

        // Stale lock recovery. Concurrent workers may all delete the stale row,
        // but the following add_option() uniqueness constraint still permits
        // exactly one new owner.
        delete_option($name);
        if (add_option($name, $record, '', 'no')) {
            return $token;
        }

        return null;
    }

    /**
     * @param int    $order_id
     * @param string $token Token returned by acquire().
     * @return void
     */
    public static function release($order_id, $token) {
        $order_id = (int) $order_id;
        if ($order_id <= 0 || !is_string($token) || $token === '') {
            return;
        }

        $name = self::PREFIX . $order_id;
        $existing = get_option($name, null);
        if (is_array($existing)
            && isset($existing['token'])
            && is_string($existing['token'])
            && hash_equals($existing['token'], $token)
        ) {
            delete_option($name);
        }
    }

    private static function token() {
        try {
            return bin2hex(random_bytes(16));
        } catch (\Throwable $e) {
            return hash('sha256', uniqid('simplixpay-upay-lock-', true) . '|' . microtime(true));
        }
    }

    private function __construct() {
    }
}
