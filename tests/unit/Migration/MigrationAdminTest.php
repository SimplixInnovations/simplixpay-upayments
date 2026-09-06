<?php

namespace Simplixi\SUCheckout\UPayments\Tests\Migration;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;
use Simplixi\SUCheckout\UPayments\Migration\MigrationAdmin;
use Simplixi\SUCheckout\UPayments\Migration\MigrationBatch;
use Simplixi\SUCheckout\UPayments\Migration\MigrationSettings;

final class MigrationAdminTest extends TestCase {
    protected function setUp(): void {
        \simplixpay_test_reset_migration_admin();
        \simplixpay_test_reset_wp_options();
    }

    public static function invalidIntegerProvider(): array {
        $max = (string) PHP_INT_MAX;
        $overflow = substr($max, 0, -1) . ((int) substr($max, -1) + 1);

        return array(
            'negative offset' => array('offset', '-1', 'invalid_offset'),
            'leading-zero offset' => array('offset', '01', 'invalid_offset'),
            'signed offset' => array('offset', '+1', 'invalid_offset'),
            'exponent offset' => array('offset', '1e2', 'invalid_offset'),
            'overflow offset' => array('offset', $overflow, 'invalid_offset'),
            'zero limit' => array('limit', '0', 'invalid_limit'),
            'negative limit' => array('limit', '-1', 'invalid_limit'),
            'leading-zero limit' => array('limit', '01', 'invalid_limit'),
            'signed limit' => array('limit', '+1', 'invalid_limit'),
            'exponent limit' => array('limit', '1e2', 'invalid_limit'),
            'over-limit' => array('limit', (string) (MigrationBatch::MAX_LIMIT + 1), 'invalid_limit'),
            'overflow limit' => array('limit', $overflow, 'invalid_limit'),
        );
    }

    public static function noncanonicalRequestMethodProvider(): array {
        return array(
            'embedded whitespace' => array('P OST'),
            'embedded backslash' => array('P\\OST'),
        );
    }

    public function test_register_uses_exact_woocommerce_submenu_contract(): void {
        MigrationAdmin::register();

        self::assertSame(array(array(
            'woocommerce',
            'SimplixPay UPayments Migration',
            'SimplixPay Migration',
            MigrationAdmin::CAPABILITY,
            MigrationAdmin::PAGE_SLUG,
            array(MigrationAdmin::class, 'render'),
        )), $GLOBALS['simplixpay_test_migration_admin']['submenu_calls']);
    }

    public function test_render_denies_missing_capability_before_request_processing(): void {
        $GLOBALS['simplixpay_test_migration_admin']['capability_allowed'] = false;
        $_SERVER['REQUEST_METHOD'] = 'POST';

        ob_start();
        try {
            MigrationAdmin::render();
            self::fail('Unauthorized render must terminate.');
        } catch (RuntimeException $exception) {
            self::assertSame('You do not have permission to run SimplixPay migration tools.', $exception->getMessage());
        } finally {
            $output = ob_get_clean();
        }

        self::assertSame('', $output);
        self::assertSame(array(MigrationAdmin::CAPABILITY), $GLOBALS['simplixpay_test_migration_admin']['capability_calls']);
        self::assertSame(array(), $GLOBALS['simplixpay_test_migration_admin']['nonce_checks']);
    }

    public function test_get_render_exposes_only_the_bounded_credential_free_form(): void {
        ob_start();
        MigrationAdmin::render();
        $output = ob_get_clean();

        foreach (array('user_ids', 'offset', 'resume', 'limit', 'migration_action', 'confirm_execute') as $field) {
            self::assertStringContainsString('name="' . $field . '"', $output);
        }
        self::assertStringNotContainsString('name="api_key"', $output);
        self::assertStringContainsString('value="0"', $output);
        self::assertStringContainsString('max="' . MigrationBatch::MAX_LIMIT . '"', $output);
        self::assertSame(array(), $GLOBALS['simplixpay_test_migration_admin']['nonce_checks']);
        self::assertSame(array(array(MigrationAdmin::NONCE_ACTION, MigrationAdmin::NONCE_FIELD)), $GLOBALS['simplixpay_test_migration_admin']['nonce_fields']);
    }

    #[DataProvider('noncanonicalRequestMethodProvider')]
    public function test_noncanonical_request_method_cannot_enter_post_path(string $request_method): void {
        $_SERVER['REQUEST_METHOD'] = $request_method;
        $_POST = array(
            'user_ids' => '7',
            'offset' => '0',
            'limit' => '1',
            'migration_action' => 'preflight',
        );
        $GLOBALS['simplixpay_test_migration_admin']['nonce_valid'] = false;

        ob_start();
        MigrationAdmin::render();
        $output = ob_get_clean();

        self::assertSame(array(), $GLOBALS['simplixpay_test_migration_admin']['nonce_checks']);
        self::assertStringNotContainsString('Migration request rejected:', $output);
        self::assertStringNotContainsString('<h2>Result</h2>', $output);
    }

    public function test_post_requires_exact_nonce_and_escapes_rejected_form_values(): void {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = array(
            'user_ids' => '1<script>alert(1)</script>',
            'offset' => '0',
            'limit' => '20',
            'migration_action' => 'preflight',
        );

        ob_start();
        MigrationAdmin::render();
        $output = ob_get_clean();

        self::assertSame(array(array(MigrationAdmin::NONCE_ACTION, MigrationAdmin::NONCE_FIELD)), $GLOBALS['simplixpay_test_migration_admin']['nonce_checks']);
        self::assertStringContainsString('Migration request rejected: user_id_invalid', $output);
        self::assertStringNotContainsString('<script>', $output);
        self::assertStringContainsString('&lt;script&gt;', $output);
    }

    public function test_invalid_nonce_terminates_before_form_or_settings_processing(): void {
        $GLOBALS['simplixpay_test_migration_admin']['nonce_valid'] = false;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = array(
            'user_ids' => 'invalid',
            'migration_action' => 'execute',
            'confirm_execute' => 'yes',
        );
        $GLOBALS['simplixpay_test_get_option_filter'] = static function () {
            throw new RuntimeException('settings_must_not_be_read');
        };

        ob_start();
        try {
            MigrationAdmin::render();
            self::fail('Invalid nonce must terminate the request.');
        } catch (RuntimeException $exception) {
            self::assertSame('nonce_invalid', $exception->getMessage());
        } finally {
            $output = ob_get_clean();
        }

        self::assertSame('', $output);
        self::assertSame(array(array(MigrationAdmin::NONCE_ACTION, MigrationAdmin::NONCE_FIELD)), $GLOBALS['simplixpay_test_migration_admin']['nonce_checks']);
    }

    public function test_noncanonical_action_fails_closed_before_settings_resolution(): void {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = array(
            'user_ids' => '7',
            'offset' => '0',
            'limit' => '1',
            'migration_action' => 'exec ute',
            'confirm_execute' => 'yes',
        );
        $GLOBALS['simplixpay_test_get_option_filter'] = static function () {
            throw new RuntimeException('settings_must_not_be_read');
        };

        ob_start();
        MigrationAdmin::render();
        $output = ob_get_clean();

        self::assertStringContainsString('Migration request rejected: action_invalid', $output);
        self::assertStringNotContainsString('settings_must_not_be_read', $output);
        self::assertStringNotContainsString('<h2>Result</h2>', $output);
    }

    public function test_successful_preflight_renders_redacted_and_escaped_result_without_execution(): void {
        $GLOBALS['simplixpay_test_options'][MigrationSettings::OPTION_KEY] = array(
            'api_key' => 'secret-api-key<script>',
            'test_mode' => 'yes',
        );
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = array(
            'user_ids' => '7',
            'offset' => '1',
            'limit' => '1',
            'migration_action' => 'preflight',
        );

        ob_start();
        MigrationAdmin::render();
        $output = ob_get_clean();

        self::assertStringNotContainsString('secret-api-key', $output);
        self::assertStringNotContainsString('&quot;api_key&quot;', $output);
        self::assertStringNotContainsString('"settings"', $output);
        self::assertStringContainsString('&quot;settings&quot;', $output);
        self::assertStringContainsString('&quot;mode&quot;: &quot;test&quot;', $output);
        self::assertStringContainsString('&quot;reason&quot;: &quot;batch_complete&quot;', $output);
        self::assertStringContainsString('<h2>Result</h2>', $output);
    }

    public function test_execute_requires_explicit_confirmation_before_settings_resolution(): void {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = array(
            'user_ids' => '7',
            'offset' => '0',
            'limit' => '1',
            'migration_action' => 'execute',
        );
        $GLOBALS['simplixpay_test_get_option_filter'] = static function () {
            throw new RuntimeException('settings_must_not_be_read');
        };

        ob_start();
        MigrationAdmin::render();
        $output = ob_get_clean();

        self::assertStringContainsString('Migration request rejected: explicit_execute_confirmation_required', $output);
        self::assertStringNotContainsString('settings_must_not_be_read', $output);
    }

    public function test_form_parser_preserves_exact_defaults_bounds_and_resume_exclusion(): void {
        $valid = $this->invokePrivate('parseForm', array(
            'user_ids' => "3, 5\n8",
            'offset' => '0',
            'limit' => (string) MigrationBatch::MAX_LIMIT,
            'migration_action' => 'preflight',
            'resume' => 'yes',
        ));
        self::assertSame(array(
            'ok' => true,
            'reason' => 'valid',
            'user_ids' => array(3, 5, 8),
            'offset' => 0,
            'limit' => MigrationBatch::MAX_LIMIT,
            'resume' => true,
        ), $valid);

        self::assertSame('resume_with_offset_invalid', $this->invokePrivate('parseForm', array(
            'user_ids' => '3',
            'offset' => '1',
            'limit' => '1',
            'migration_action' => 'preflight',
            'resume' => 'yes',
        ))['reason']);
        self::assertSame('invalid_offset', $this->invokePrivate('parseForm', array(
            'user_ids' => '3',
            'offset' => "1\n",
            'limit' => '1',
            'migration_action' => 'preflight',
        ))['reason']);
        self::assertSame('invalid_limit', $this->invokePrivate('parseForm', array(
            'user_ids' => '3',
            'offset' => '0',
            'limit' => "1\n",
            'migration_action' => 'preflight',
        ))['reason']);
    }

    #[DataProvider('invalidIntegerProvider')]
    public function test_form_parser_rejects_noncanonical_and_out_of_range_integers(string $field, string $value, string $reason): void {
        $form = array(
            'user_ids' => '3',
            'offset' => '0',
            'limit' => '1',
            'migration_action' => 'preflight',
        );
        $form[$field] = $value;

        self::assertSame($reason, $this->invokePrivate('parseForm', $form)['reason']);
    }

    public function test_boundary_is_final_with_only_register_and_render_public(): void {
        $reflection = new ReflectionClass(MigrationAdmin::class);
        $public = array_map(static function (ReflectionMethod $method) {
            return $method->getName();
        }, $reflection->getMethods(ReflectionMethod::IS_PUBLIC));
        sort($public);

        self::assertTrue($reflection->isFinal());
        self::assertSame(array('register', 'render'), $public);
    }

    private function invokePrivate(string $name, ...$arguments) {
        $method = new ReflectionMethod(MigrationAdmin::class, $name);
        $method->setAccessible(true);
        return $method->invokeArgs(null, $arguments);
    }
}
