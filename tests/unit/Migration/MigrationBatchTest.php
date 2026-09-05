<?php

namespace Simplix\Pay\UPayments\Tests\Migration;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use Simplix\Pay\UPayments\Migration\MigrationBatch;

final class MigrationBatchTest extends TestCase {
    protected function setUp(): void {
        \simplixpay_test_reset_migration_core();
    }

    public function test_user_id_parser_preserves_exact_canonical_contract(): void {
        self::assertSame(
            array('ok' => true, 'reason' => 'user_ids_parsed', 'user_ids' => array(1, 2, 3, 4)),
            MigrationBatch::parseUserIds("1, 2\n3\t4")
        );
        self::assertSame('user_id_invalid', MigrationBatch::parseUserIds('01,2')['reason']);
        self::assertSame('duplicate_user_id', MigrationBatch::parseUserIds('1,1')['reason']);
        self::assertSame('user_id_invalid', MigrationBatch::parseUserIds('1e2')['reason']);
        self::assertSame('user_ids_missing', MigrationBatch::parseUserIds('')['reason']);
        self::assertSame('user_ids_missing', MigrationBatch::parseUserIds(" , \n\t, ")['reason']);
    }

    public function test_bounded_clean_page_writes_redacted_checkpoint_and_resumes(): void {
        $result = MigrationBatch::run(array(1, 2), 'secret-api', false, true, 0, 1);

        self::assertTrue($result['success']);
        self::assertSame('batch_page_complete', $result['reason']);
        self::assertSame(1, $result['processed']);
        self::assertSame(1, $result['next_offset']);
        self::assertSame(1, $result['checkpoint_offset']);
        self::assertTrue($result['results'][0]['operations_ledger_written']);
        self::assertSame('already_clean', $result['results'][0]['reason']);

        $resume = MigrationBatch::resumeOffset(array(1, 2), 'secret-api', false, true);
        self::assertTrue($resume['ok']);
        self::assertSame('durable_checkpoint_recovered', $resume['reason']);
        self::assertSame(1, $resume['offset']);

        $encoded = json_encode(array(
            'result' => $result,
            'meta' => $GLOBALS['simplixpay_test_migration_core']['user_meta'],
        ));
        self::assertIsString($encoded);
        self::assertStringNotContainsString('secret-api', $encoded);
    }

    public function test_trailing_newline_reason_cannot_be_reused_as_durable_checkpoint(): void {
        $digest = hash_hmac(
            'sha256',
            'phase9i-operations-v1|live|dry-run|1',
            'api-key'
        );
        $GLOBALS['simplixpay_test_migration_core']['user_meta'][1][MigrationBatch::RESULT_LEDGER_KEY] = array(
            'version' => MigrationBatch::RESULT_LEDGER_VERSION,
            'batch_digest' => $digest,
            'position' => 0,
            'next_offset' => 1,
            'input_count' => 1,
            'dry_run' => true,
            'mode' => 'live',
            'success' => true,
            'reason' => "already_clean\n",
            'classification' => 'CLEAN',
            'migrated' => false,
            'idempotent' => true,
            'executor_ledger_written' => false,
            'token_digest' => null,
            'processed_at_gmt' => time(),
        );

        $resume = MigrationBatch::resumeOffset(array(1), 'api-key', false, true);

        self::assertTrue($resume['ok']);
        self::assertSame('no_durable_checkpoint', $resume['reason']);
        self::assertSame(0, $resume['offset']);
    }

    public function test_invalid_windows_fail_before_execution_or_checkpoint_mutation(): void {
        self::assertSame('invalid_offset', MigrationBatch::run(array(1), 'api-key', false, true, -1, 1)['reason']);
        self::assertSame(
            'invalid_limit',
            MigrationBatch::run(array(1), 'api-key', false, true, 0, MigrationBatch::MAX_LIMIT + 1)['reason']
        );
        self::assertSame(array(), $GLOBALS['simplixpay_test_migration_core']['user_meta']);
    }

    public function test_public_boundary_remains_final_and_static(): void {
        $reflection = new ReflectionClass(MigrationBatch::class);
        $public = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        $names = array_map(static function (ReflectionMethod $method) {
            return $method->getName();
        }, $public);
        sort($names);

        self::assertTrue($reflection->isFinal());
        self::assertSame(array('parseUserIds', 'resumeOffset', 'run'), $names);
        foreach ($public as $method) {
            self::assertTrue($method->isStatic(), $method->getName());
        }
        self::assertFalse($reflection->isInstantiable());
    }
}
