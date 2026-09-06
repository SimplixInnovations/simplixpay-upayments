<?php

namespace Simplixi\SUCheckout\UPayments\Tests\Payment;

use PHPUnit\Framework\TestCase;
use Simplixi\SUCheckout\UPayments\Payment\StatusRateGate;

final class StatusRateGateTest extends TestCase {
    protected function setUp(): void {
        \simplixpay_test_reset_wp_options();
    }

    public function test_invalid_gateway_or_empty_salt_fails_before_option_mutation(): void {
        self::assertFalse(StatusRateGate::acquire(null));
        self::assertFalse(StatusRateGate::acquire(new \stdClass()));

        $GLOBALS['simplixpay_test_wp_salt'] = '';
        self::assertFalse(StatusRateGate::acquire($this->gateway('secret', false)));
        self::assertSame(array(), $GLOBALS['simplixpay_test_options']);
    }

    public function test_exactly_thirty_atomic_slots_are_available_per_minute(): void {
        $gateway = $this->gateway('credential-a', false);

        for ($slot = 0; $slot < 30; $slot++) {
            self::assertTrue(StatusRateGate::acquire($gateway));
        }

        self::assertFalse(StatusRateGate::acquire($gateway));
        self::assertSame(30, StatusRateGate::limit_per_minute());

        $slot_names = array_filter(array_keys($GLOBALS['simplixpay_test_options']), function ($name) {
            return strpos($name, 'simplixpay_upay_status_v1_') === 0
                && substr($name, -7) !== '_bucket';
        });
        self::assertCount(30, $slot_names);
    }

    public function test_credential_and_mode_scopes_are_isolated_without_leaking_secrets(): void {
        $live_a = $this->gateway('credential-a', false);
        $test_a = $this->gateway('credential-a', true);
        $live_b = $this->gateway('credential-b', false);

        self::assertTrue(StatusRateGate::acquire($live_a));
        self::assertTrue(StatusRateGate::acquire($test_a));
        self::assertTrue(StatusRateGate::acquire($live_b));

        $names = implode('\n', array_keys($GLOBALS['simplixpay_test_options']));
        self::assertStringNotContainsString('credential-a', $names);
        self::assertStringNotContainsString('credential-b', $names);

        $markers = array_filter(array_keys($GLOBALS['simplixpay_test_options']), function ($name) {
            return substr($name, -7) === '_bucket';
        });
        self::assertCount(3, $markers);
    }

    public function test_previous_valid_bucket_slots_are_deleted_before_acquisition(): void {
        $gateway = $this->gateway('credential-a', false);
        self::assertTrue(StatusRateGate::acquire($gateway));

        $marker = null;
        foreach (array_keys($GLOBALS['simplixpay_test_options']) as $name) {
            if (substr($name, -7) === '_bucket') {
                $marker = $name;
                break;
            }
        }
        self::assertIsString($marker);

        $scope = substr($marker, strlen('simplixpay_upay_status_v1_'), 16);
        $old_bucket = '200001010000';
        $GLOBALS['simplixpay_test_options'][$marker] = $old_bucket;
        for ($slot = 0; $slot < 30; $slot++) {
            $old_name = 'simplixpay_upay_status_v1_' . $scope . '_' . $old_bucket . '_' . $slot;
            $GLOBALS['simplixpay_test_options'][$old_name] = 1;
        }

        self::assertTrue(StatusRateGate::acquire($gateway));
        for ($slot = 0; $slot < 30; $slot++) {
            $old_name = 'simplixpay_upay_status_v1_' . $scope . '_' . $old_bucket . '_' . $slot;
            self::assertArrayNotHasKey($old_name, $GLOBALS['simplixpay_test_options']);
        }
    }

    private function gateway($api_key, $test_mode) {
        return new class($api_key, $test_mode) {
            public $apiKey;
            private $testMode;

            public function __construct($api_key, $test_mode) {
                $this->apiKey = $api_key;
                $this->testMode = $test_mode;
            }

            public function getMode() {
                return $this->testMode;
            }
        };
    }
}
