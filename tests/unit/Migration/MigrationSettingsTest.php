<?php

namespace Simplix\Pay\UPayments\Tests\Migration;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Simplix\Pay\UPayments\Migration\MigrationSettings;

final class MigrationSettingsTest extends TestCase {
    protected function setUp(): void {
        \simplixpay_test_reset_wp_options();
    }

    public function test_resolver_reads_only_the_historical_gateway_option(): void {
        $reads = array();
        $GLOBALS['simplixpay_test_get_option_filter'] = function ($name, $value, $default) use (&$reads) {
            $reads[] = array($name, $default);
            return $value;
        };

        self::assertSame('woocommerce_upayments_settings', MigrationSettings::OPTION_KEY);
        self::assertSame($this->failure('settings_missing'), MigrationSettings::resolve());
        self::assertSame(array(array('woocommerce_upayments_settings', null)), $reads);
        self::assertSame(array(), $GLOBALS['simplixpay_test_option_calls']);
    }

    public function test_missing_or_malformed_api_keys_fail_closed(): void {
        foreach (array(
            null,
            false,
            'not-an-array',
        ) as $settings) {
            $GLOBALS['simplixpay_test_options'][MigrationSettings::OPTION_KEY] = $settings;
            self::assertSame($this->failure('settings_missing'), MigrationSettings::resolve());
        }

        foreach (array(
            array(),
            array('api_key' => null),
            array('api_key' => false),
            array('api_key' => 123),
            array('api_key' => ''),
            array('api_key' => " \t\n"),
        ) as $settings) {
            $GLOBALS['simplixpay_test_options'][MigrationSettings::OPTION_KEY] = $settings;
            self::assertSame($this->failure('api_key_missing'), MigrationSettings::resolve());
        }
    }

    public function test_test_mode_accepts_only_exact_woocommerce_checkbox_states(): void {
        foreach (array('maybe', 'YES', 'No', ' yes', 'yes ', true, false, 1, 0, null, array('yes')) as $mode) {
            $GLOBALS['simplixpay_test_options'][MigrationSettings::OPTION_KEY] = array(
                'api_key'  => 'secret-api',
                'test_mode' => $mode,
            );
            self::assertSame($this->failure('test_mode_invalid'), MigrationSettings::resolve());
        }
    }

    public function test_absent_no_and_yes_modes_resolve_exactly_without_mutation(): void {
        $cases = array(
            array(null, false, 'live'),
            array('no', false, 'live'),
            array('yes', true, 'test'),
        );

        foreach ($cases as $case) {
            list($stored_mode, $is_test_mode, $mode) = $case;
            $settings = array('api_key' => '  exact-secret  ');
            if ($stored_mode !== null) {
                $settings['test_mode'] = $stored_mode;
            }
            $GLOBALS['simplixpay_test_options'][MigrationSettings::OPTION_KEY] = $settings;

            self::assertSame(array(
                'ok'           => true,
                'reason'       => 'settings_resolved',
                'api_key'      => '  exact-secret  ',
                'is_test_mode' => $is_test_mode,
                'mode'         => $mode,
            ), MigrationSettings::resolve());
            self::assertSame($settings, $GLOBALS['simplixpay_test_options'][MigrationSettings::OPTION_KEY]);
        }
        self::assertSame(array(), $GLOBALS['simplixpay_test_option_calls']);
    }

    public function test_redaction_never_returns_the_api_key_or_unbounded_fields(): void {
        self::assertSame(
            array('ok' => false, 'reason' => 'settings_malformed'),
            MigrationSettings::redact(null)
        );
        self::assertSame(
            array('ok' => true, 'reason' => 'settings_resolved', 'mode' => 'test'),
            MigrationSettings::redact(array(
                'ok' => true,
                'reason' => 'settings_resolved',
                'mode' => 'test',
                'api_key' => 'must-never-escape',
                'extra' => 'must-never-escape',
            ))
        );
        self::assertSame(
            array('ok' => false, 'reason' => 'settings_malformed', 'mode' => null),
            MigrationSettings::redact(array('ok' => 0, 'reason' => array('bad'), 'mode' => false))
        );
    }

    public function test_settings_boundary_is_final_and_non_instantiable(): void {
        $reflection = new ReflectionClass(MigrationSettings::class);
        self::assertTrue($reflection->isFinal());
        self::assertNotNull($reflection->getConstructor());
        self::assertTrue($reflection->getConstructor()->isPrivate());
    }

    private function failure($reason): array {
        return array(
            'ok'           => false,
            'reason'       => $reason,
            'api_key'      => null,
            'is_test_mode' => null,
            'mode'         => null,
        );
    }
}
