<?php
namespace Simplix\Pay\UPayments\Migration;

defined('ABSPATH') || exit;

/**
 * WP-CLI operational surface for Phase 9I.
 *
 * Credentials are resolved from the existing WooCommerce gateway settings;
 * there is deliberately no --api-key argument.
 */
final class MigrationCliCommand {
    /**
     * Read-only preflight for an explicit bounded list of users.
     *
     * ## OPTIONS
     *
     * --user-ids=<ids>
     * : Comma/whitespace separated positive WooCommerce customer IDs.
     *
     * [--offset=<n>]
     * : Zero-based resume offset. Default 0.
     *
     * [--limit=<n>]
     * : Users processed this invocation. Default 20, maximum 50.
     */
    public function preflight($args, $assoc_args) {
        $request = self::parseRequest($assoc_args);
        if (!$request['ok']) {
            self::cliError($request['reason']);
            return;
        }

        $settings = MigrationSettings::resolve();
        if (empty($settings['ok'])) {
            self::cliError(isset($settings['reason']) ? $settings['reason'] : 'settings_unavailable');
            return;
        }

        $result = MigrationBatch::run(
            $request['user_ids'],
            $settings['api_key'],
            $settings['is_test_mode'],
            true,
            $request['offset'],
            $request['limit']
        );
        self::emit($result, $settings);
        if (!$result['success']) {
            self::cliError('preflight_completed_with_failures');
        }
    }

    /**
     * Execute migration for an explicit bounded list of users.
     *
     * ## OPTIONS
     *
     * --user-ids=<ids>
     * : Comma/whitespace separated positive WooCommerce customer IDs.
     *
     * --yes
     * : Required explicit confirmation for write mode.
     *
     * [--offset=<n>]
     * : Zero-based resume offset. Default 0.
     *
     * [--limit=<n>]
     * : Users processed this invocation. Default 20, maximum 50.
     */
    public function execute($args, $assoc_args) {
        if (!is_array($assoc_args) || !array_key_exists('yes', $assoc_args)) {
            self::cliError('explicit_yes_required');
            return;
        }

        $request = self::parseRequest($assoc_args);
        if (!$request['ok']) {
            self::cliError($request['reason']);
            return;
        }

        $settings = MigrationSettings::resolve();
        if (empty($settings['ok'])) {
            self::cliError(isset($settings['reason']) ? $settings['reason'] : 'settings_unavailable');
            return;
        }

        $result = MigrationBatch::run(
            $request['user_ids'],
            $settings['api_key'],
            $settings['is_test_mode'],
            false,
            $request['offset'],
            $request['limit']
        );
        self::emit($result, $settings);
        if (!$result['success']) {
            self::cliError('execute_completed_with_failures');
        }
    }

    private static function parseRequest($assoc_args) {
        if (!is_array($assoc_args) || !isset($assoc_args['user-ids']) || !is_string($assoc_args['user-ids'])) {
            return array('ok' => false, 'reason' => 'user_ids_missing');
        }
        $parsed = MigrationBatch::parseUserIds($assoc_args['user-ids']);
        if (!$parsed['ok']) {
            return array('ok' => false, 'reason' => $parsed['reason']);
        }

        $offset = 0;
        if (array_key_exists('offset', $assoc_args)) {
            $offset = self::strictInt($assoc_args['offset'], true);
            if ($offset === null) {
                return array('ok' => false, 'reason' => 'invalid_offset');
            }
        }

        $limit = MigrationBatch::DEFAULT_LIMIT;
        if (array_key_exists('limit', $assoc_args)) {
            $limit = self::strictInt($assoc_args['limit'], false);
            if ($limit === null || $limit > MigrationBatch::MAX_LIMIT) {
                return array('ok' => false, 'reason' => 'invalid_limit');
            }
        }

        return array(
            'ok' => true,
            'reason' => 'valid',
            'user_ids' => $parsed['user_ids'],
            'offset' => $offset,
            'limit' => $limit,
        );
    }

    private static function strictInt($value, $allow_zero) {
        if (is_int($value)) {
            $parsed = $value;
        } elseif (is_string($value) && preg_match('/^(?:0|[1-9][0-9]*)$/', $value) === 1) {
            if (strlen($value) > strlen((string) PHP_INT_MAX)
                || (strlen($value) === strlen((string) PHP_INT_MAX) && strcmp($value, (string) PHP_INT_MAX) > 0)
            ) {
                return null;
            }
            $parsed = (int) $value;
        } else {
            return null;
        }
        if ($allow_zero) {
            return $parsed >= 0 ? $parsed : null;
        }
        return $parsed > 0 ? $parsed : null;
    }

    private static function emit($result, $settings) {
        $payload = array(
            'settings' => MigrationSettings::redact($settings),
            'batch' => $result,
        );
        $json = function_exists('wp_json_encode') ? wp_json_encode($payload, JSON_PRETTY_PRINT) : json_encode($payload, JSON_PRETTY_PRINT);
        if (!is_string($json)) {
            self::cliError('output_encode_failed');
            return;
        }
        \WP_CLI::line($json);
    }

    private static function cliError($reason, $exit = true) {
        $message = 'SimplixPay UPayments migration: ' . $reason;
        \WP_CLI::error($message, $exit);
    }
}
