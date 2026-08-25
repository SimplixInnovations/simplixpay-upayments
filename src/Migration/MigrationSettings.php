<?php
namespace Simplix\Pay\UPayments\Migration;

defined('ABSPATH') || exit;

/**
 * Read-only resolver for the existing WooCommerce UPayments settings.
 *
 * Migration tools never accept or persist API keys of their own. They consume
 * the exact existing gateway credential/mode so scope derivation remains
 * identical to runtime H12 behavior.
 */
final class MigrationSettings {
    const OPTION_KEY = 'woocommerce_upayments_settings';

    public static function resolve() {
        $settings = get_option(self::OPTION_KEY, null);
        if (!is_array($settings)) {
            return self::failure('settings_missing');
        }

        $api_key = isset($settings['api_key']) ? $settings['api_key'] : null;
        if (!is_string($api_key) || $api_key === '' || trim($api_key) === '') {
            return self::failure('api_key_missing');
        }

        $test_mode = array_key_exists('test_mode', $settings) ? $settings['test_mode'] : 'no';
        if ($test_mode !== 'yes' && $test_mode !== 'no') {
            return self::failure('test_mode_invalid');
        }

        return array(
            'ok' => true,
            'reason' => 'settings_resolved',
            'api_key' => $api_key,
            'is_test_mode' => ($test_mode === 'yes'),
            'mode' => ($test_mode === 'yes') ? 'test' : 'live',
        );
    }

    public static function redact($resolved) {
        if (!is_array($resolved)) {
            return array('ok' => false, 'reason' => 'settings_malformed');
        }
        return array(
            'ok' => !empty($resolved['ok']),
            'reason' => isset($resolved['reason']) && is_string($resolved['reason']) ? $resolved['reason'] : 'settings_malformed',
            'mode' => isset($resolved['mode']) && is_string($resolved['mode']) ? $resolved['mode'] : null,
        );
    }

    private static function failure($reason) {
        return array(
            'ok' => false,
            'reason' => $reason,
            'api_key' => null,
            'is_test_mode' => null,
            'mode' => null,
        );
    }

    private function __construct() {
    }
}
