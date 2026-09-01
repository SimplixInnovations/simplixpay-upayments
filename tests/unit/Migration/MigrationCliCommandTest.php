<?php

namespace Simplix\Pay\UPayments\Tests\Migration;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;
use Simplix\Pay\UPayments\Migration\MigrationBatch;
use Simplix\Pay\UPayments\Migration\MigrationCliCommand;

final class MigrationCliCommandTest extends TestCase {
    protected function setUp(): void {
        \simplixpay_test_reset_migration_bootstrap();
        \simplixpay_test_reset_wp_options();
    }

    public static function invalidRequestProvider(): array {
        return array(
            'missing IDs' => array(array(), 'user_ids_missing'),
            'non-string IDs' => array(array('user-ids' => array(1)), 'user_ids_missing'),
            'invalid ID list' => array(array('user-ids' => '01'), 'user_id_invalid'),
            'resume with offset' => array(array('user-ids' => '1', 'resume' => true, 'offset' => '0'), 'resume_with_offset_invalid'),
            'negative offset' => array(array('user-ids' => '1', 'offset' => '-1'), 'invalid_offset'),
            'leading-zero offset' => array(array('user-ids' => '1', 'offset' => '01'), 'invalid_offset'),
            'zero limit' => array(array('user-ids' => '1', 'limit' => '0'), 'invalid_limit'),
            'over-limit' => array(array('user-ids' => '1', 'limit' => (string) (MigrationBatch::MAX_LIMIT + 1)), 'invalid_limit'),
        );
    }

    #[DataProvider('invalidRequestProvider')]
    public function test_preflight_rejects_invalid_requests_with_exact_cli_error(array $request, string $reason): void {
        $command = new MigrationCliCommand();

        try {
            $command->preflight(array(), $request);
            self::fail('WP_CLI::error should terminate the invalid request.');
        } catch (RuntimeException $exception) {
            self::assertSame('SimplixPay UPayments migration: ' . $reason, $exception->getMessage());
        }

        self::assertSame(array(array('SimplixPay UPayments migration: ' . $reason, true)), \WP_CLI::$errors);
        self::assertSame(array(), \WP_CLI::$lines);
    }

    public function test_execute_requires_explicit_yes_before_request_parsing(): void {
        $command = new MigrationCliCommand();

        try {
            $command->execute(array(), array());
            self::fail('WP_CLI::error should require explicit confirmation.');
        } catch (RuntimeException $exception) {
            self::assertSame('SimplixPay UPayments migration: explicit_yes_required', $exception->getMessage());
        }

        self::assertSame(array(array('SimplixPay UPayments migration: explicit_yes_required', true)), \WP_CLI::$errors);
        self::assertSame(array(), \WP_CLI::$lines);
    }

    public function test_valid_request_parsing_preserves_exact_defaults_and_bounds(): void {
        $parsed = $this->invokePrivate('parseRequest', array(
            'user-ids' => "3, 5\n8",
            'offset' => '2',
            'limit' => (string) MigrationBatch::MAX_LIMIT,
        ));

        self::assertSame(array(
            'ok' => true,
            'reason' => 'valid',
            'user_ids' => array(3, 5, 8),
            'offset' => 2,
            'limit' => MigrationBatch::MAX_LIMIT,
            'resume' => false,
        ), $parsed);

        $defaults = $this->invokePrivate('parseRequest', array('user-ids' => '13', 'resume' => true));
        self::assertSame(0, $defaults['offset']);
        self::assertSame(MigrationBatch::DEFAULT_LIMIT, $defaults['limit']);
        self::assertTrue($defaults['resume']);
    }

    public function test_strict_integer_parser_rejects_noncanonical_and_overflow_values(): void {
        $max = (string) PHP_INT_MAX;
        $overflow = substr($max, 0, -1) . ((int) substr($max, -1) + 1);

        self::assertSame(0, $this->invokePrivate('strictInt', '0', true));
        self::assertSame(7, $this->invokePrivate('strictInt', 7, false));
        self::assertSame(PHP_INT_MAX, $this->invokePrivate('strictInt', $max, true));
        self::assertNull($this->invokePrivate('strictInt', '00', true));
        self::assertNull($this->invokePrivate('strictInt', '+1', true));
        self::assertNull($this->invokePrivate('strictInt', '1e2', true));
        self::assertNull($this->invokePrivate('strictInt', $overflow, true));
        self::assertNull($this->invokePrivate('strictInt', 0, false));
    }

    public function test_emit_outputs_redacted_json_without_credentials(): void {
        $this->invokePrivate('emit',
            array('success' => true, 'reason' => 'batch_complete'),
            array('ok' => true, 'reason' => 'settings_resolved', 'api_key' => 'secret-api-key', 'is_test_mode' => true, 'mode' => 'test')
        );

        self::assertCount(1, \WP_CLI::$lines);
        self::assertStringNotContainsString('secret-api-key', \WP_CLI::$lines[0]);
        self::assertSame(array(
            'settings' => array('ok' => true, 'reason' => 'settings_resolved', 'mode' => 'test'),
            'batch' => array('success' => true, 'reason' => 'batch_complete'),
        ), json_decode(\WP_CLI::$lines[0], true));
        self::assertSame(array(), \WP_CLI::$errors);
    }

    public function test_cli_boundary_is_final_with_only_two_public_commands(): void {
        $reflection = new ReflectionClass(MigrationCliCommand::class);
        $public = array_map(static function (ReflectionMethod $method) {
            return $method->getName();
        }, $reflection->getMethods(ReflectionMethod::IS_PUBLIC));
        sort($public);

        self::assertTrue($reflection->isFinal());
        self::assertSame(array('execute', 'preflight'), $public);
    }

    private function invokePrivate(string $name, ...$arguments) {
        $method = new ReflectionMethod(MigrationCliCommand::class, $name);
        $method->setAccessible(true);
        return $method->invokeArgs(null, $arguments);
    }
}
