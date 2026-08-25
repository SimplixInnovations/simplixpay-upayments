<?php
namespace Simplix\Pay\UPayments\Migration;

defined('ABSPATH') || exit;

/**
 * Bounded, resumable Phase 9I batch orchestration.
 *
 * The batch is intentionally stateless. Resume is explicit through the
 * returned next_offset; per-user durable state belongs to MigrationExecutor's
 * Simplix-owned ledger. No credentials or raw tokens are persisted here.
 */
final class MigrationBatch {
    const MAX_INPUT_USERS = 500;
    const DEFAULT_LIMIT = 20;
    const MAX_LIMIT = 50;

    /**
     * Strictly parse a comma/whitespace separated user-id list.
     *
     * @param mixed $raw
     * @return array{ok:bool,reason:string,user_ids:array}
     */
    public static function parseUserIds($raw) {
        if (!is_string($raw) || $raw === '') {
            return array('ok' => false, 'reason' => 'user_ids_missing', 'user_ids' => array());
        }

        $parts = preg_split('/[\s,]+/', $raw, -1, PREG_SPLIT_NO_EMPTY);
        if (!is_array($parts) || count($parts) === 0) {
            return array('ok' => false, 'reason' => 'user_ids_missing', 'user_ids' => array());
        }
        if (count($parts) > self::MAX_INPUT_USERS) {
            return array('ok' => false, 'reason' => 'user_ids_over_cap', 'user_ids' => array());
        }

        $ids = array();
        $seen = array();
        foreach ($parts as $part) {
            if (!is_string($part) || preg_match('/^[1-9][0-9]*$/', $part) !== 1) {
                return array('ok' => false, 'reason' => 'user_id_invalid', 'user_ids' => array());
            }
            if (strlen($part) > strlen((string) PHP_INT_MAX)
                || (strlen($part) === strlen((string) PHP_INT_MAX) && strcmp($part, (string) PHP_INT_MAX) > 0)
            ) {
                return array('ok' => false, 'reason' => 'user_id_invalid', 'user_ids' => array());
            }
            $id = (int) $part;
            if ($id <= 0) {
                return array('ok' => false, 'reason' => 'user_id_invalid', 'user_ids' => array());
            }
            if (isset($seen[$id])) {
                return array('ok' => false, 'reason' => 'duplicate_user_id', 'user_ids' => array());
            }
            $seen[$id] = true;
            $ids[] = $id;
        }

        return array('ok' => true, 'reason' => 'user_ids_parsed', 'user_ids' => $ids);
    }

    /**
     * Run one bounded page of explicit user IDs.
     *
     * @param array  $user_ids
     * @param string $api_key
     * @param bool   $is_test_mode
     * @param bool   $dry_run
     * @param int    $offset
     * @param int    $limit
     * @return array
     */
    public static function run($user_ids, $api_key, $is_test_mode, $dry_run = true, $offset = 0, $limit = self::DEFAULT_LIMIT) {
        $base = self::baseResult($dry_run, $offset, $limit);

        $validated = self::validateInputs($user_ids, $api_key, $is_test_mode, $dry_run, $offset, $limit);
        if (!$validated['ok']) {
            $base['reason'] = $validated['reason'];
            return $base;
        }
        $user_ids = $validated['user_ids'];
        $total = count($user_ids);
        $base['input_count'] = $total;

        if ($offset >= $total) {
            $base['success'] = true;
            $base['reason'] = 'batch_complete';
            $base['done'] = true;
            $base['next_offset'] = null;
            return $base;
        }

        $slice = array_slice($user_ids, $offset, $limit);
        foreach ($slice as $user_id) {
            try {
                $execution = MigrationExecutor::execute($user_id, $api_key, $is_test_mode, $dry_run);
            } catch (\Throwable $e) {
                $execution = array(
                    'success' => false,
                    'reason' => 'executor_exception',
                    'classification' => null,
                    'migrated' => false,
                    'idempotent' => false,
                    'ledger_written' => false,
                    'token_digest' => null,
                );
            }

            $safe = self::redactExecution($user_id, $execution);
            $base['results'][] = $safe;
            $base['processed']++;
            self::accumulate($base['summary'], $safe);
        }

        $next = $offset + $base['processed'];
        $base['done'] = ($next >= $total);
        $base['next_offset'] = $base['done'] ? null : $next;
        $base['success'] = ($base['summary']['failed'] === 0);
        $base['reason'] = $base['success'] ? ($base['done'] ? 'batch_complete' : 'batch_page_complete') : 'batch_completed_with_failures';
        return $base;
    }

    private static function validateInputs($user_ids, $api_key, $is_test_mode, $dry_run, $offset, $limit) {
        if (!is_array($user_ids) || count($user_ids) === 0) {
            return array('ok' => false, 'reason' => 'user_ids_missing', 'user_ids' => array());
        }
        if (count($user_ids) > self::MAX_INPUT_USERS) {
            return array('ok' => false, 'reason' => 'user_ids_over_cap', 'user_ids' => array());
        }
        if (!is_string($api_key) || $api_key === '') {
            return array('ok' => false, 'reason' => 'invalid_api_key', 'user_ids' => array());
        }
        if (!is_bool($is_test_mode) || !is_bool($dry_run)) {
            return array('ok' => false, 'reason' => 'invalid_mode', 'user_ids' => array());
        }
        if (!is_int($offset) || $offset < 0) {
            return array('ok' => false, 'reason' => 'invalid_offset', 'user_ids' => array());
        }
        if (!is_int($limit) || $limit <= 0 || $limit > self::MAX_LIMIT) {
            return array('ok' => false, 'reason' => 'invalid_limit', 'user_ids' => array());
        }

        $clean = array();
        $seen = array();
        foreach ($user_ids as $id) {
            if (!is_int($id) || $id <= 0) {
                return array('ok' => false, 'reason' => 'user_id_invalid', 'user_ids' => array());
            }
            if (isset($seen[$id])) {
                return array('ok' => false, 'reason' => 'duplicate_user_id', 'user_ids' => array());
            }
            $seen[$id] = true;
            $clean[] = $id;
        }
        return array('ok' => true, 'reason' => 'valid', 'user_ids' => $clean);
    }

    private static function redactExecution($user_id, $execution) {
        $safe = array(
            'user_id' => $user_id,
            'success' => false,
            'reason' => 'executor_malformed',
            'classification' => null,
            'migrated' => false,
            'idempotent' => false,
            'ledger_written' => false,
            'token_digest' => null,
        );
        if (!is_array($execution)) {
            return $safe;
        }

        $safe['success'] = !empty($execution['success']);
        if (isset($execution['reason']) && is_string($execution['reason'])) {
            $safe['reason'] = $execution['reason'];
        }
        if (isset($execution['classification']) && (is_string($execution['classification']) || $execution['classification'] === null)) {
            $safe['classification'] = $execution['classification'];
        }
        $safe['migrated'] = !empty($execution['migrated']);
        $safe['idempotent'] = !empty($execution['idempotent']);
        $safe['ledger_written'] = !empty($execution['ledger_written']);
        if (isset($execution['token_digest']) && is_string($execution['token_digest'])
            && preg_match('/^[0-9a-f]{64}$/', $execution['token_digest']) === 1
        ) {
            $safe['token_digest'] = $execution['token_digest'];
        }
        return $safe;
    }

    private static function accumulate(&$summary, $safe) {
        $summary['processed']++;
        if (!empty($safe['success'])) {
            $summary['succeeded']++;
        } else {
            $summary['failed']++;
        }
        if (!empty($safe['migrated'])) {
            $summary['migrated']++;
        }
        if (!empty($safe['idempotent'])) {
            $summary['idempotent']++;
        }

        $classification = isset($safe['classification']) && is_string($safe['classification']) ? $safe['classification'] : 'UNKNOWN';
        if (!isset($summary['classifications'][$classification])) {
            $summary['classifications'][$classification] = 0;
        }
        $summary['classifications'][$classification]++;

        $reason = isset($safe['reason']) && is_string($safe['reason']) ? $safe['reason'] : 'unknown';
        if (!isset($summary['reasons'][$reason])) {
            $summary['reasons'][$reason] = 0;
        }
        $summary['reasons'][$reason]++;
    }

    private static function baseResult($dry_run, $offset, $limit) {
        return array(
            'success' => false,
            'reason' => 'invalid_batch',
            'dry_run' => $dry_run,
            'offset' => $offset,
            'limit' => $limit,
            'input_count' => 0,
            'processed' => 0,
            'next_offset' => null,
            'done' => false,
            'summary' => array(
                'processed' => 0,
                'succeeded' => 0,
                'failed' => 0,
                'migrated' => 0,
                'idempotent' => 0,
                'classifications' => array(),
                'reasons' => array(),
            ),
            'results' => array(),
        );
    }

    private function __construct() {
    }
}
