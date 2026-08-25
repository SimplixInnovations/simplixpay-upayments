<?php
namespace {
    define('ABSPATH', __DIR__ . '/');

    $GLOBALS['p9o'] = array(
        'options' => array(),
        'executor_calls' => array(),
        'executor_results' => array(),
        'cli_lines' => array(),
        'cli_errors' => array(),
    );

    function get_option($name, $default = false) {
        return array_key_exists($name, $GLOBALS['p9o']['options']) ? $GLOBALS['p9o']['options'][$name] : $default;
    }
    function wp_json_encode($value, $flags = 0) { return json_encode($value, $flags); }

    class WP_CLI {
        public static $commands = array();
        public static function add_command($name, $class) { self::$commands[$name] = $class; return true; }
        public static function line($message) { $GLOBALS['p9o']['cli_lines'][] = $message; }
        public static function error($message, $exit = true) {
            $GLOBALS['p9o']['cli_errors'][] = $message;
            if ($exit) throw new \RuntimeException($message);
        }
    }
}

namespace Simplix\Pay\UPayments\Migration {
    final class MigrationExecutor {
        public static function execute($user_id, $api_key, $is_test_mode, $dry_run = false) {
            $GLOBALS['p9o']['executor_calls'][] = array(
                'user_id' => $user_id,
                'api_key' => $api_key,
                'is_test_mode' => $is_test_mode,
                'dry_run' => $dry_run,
            );
            if ($user_id === 4) {
                throw new \RuntimeException('synthetic executor failure');
            }
            if (isset($GLOBALS['p9o']['executor_results'][$user_id])) {
                return $GLOBALS['p9o']['executor_results'][$user_id];
            }
            return array(
                'success' => true,
                'reason' => 'already_clean',
                'classification' => 'CLEAN',
                'migrated' => false,
                'idempotent' => true,
                'ledger_written' => false,
                'token_digest' => null,
                'preflight' => array('migration' => array('token' => 'SHOULD_NEVER_ESCAPE')),
            );
        }
    }
}

namespace {
    $root = realpath(__DIR__ . '/../..');
    require_once $root . '/src/Migration/MigrationSettings.php';
    require_once $root . '/src/Migration/MigrationBatch.php';
    require_once $root . '/src/Migration/MigrationCliCommand.php';

    use Simplix\Pay\UPayments\Migration\MigrationSettings;
    use Simplix\Pay\UPayments\Migration\MigrationBatch;
    use Simplix\Pay\UPayments\Migration\MigrationCliCommand;

    $pass = 0; $fail = 0;
    function p9o_assert($condition, $label) {
        global $pass, $fail;
        if ($condition) { $pass++; echo "PASS: $label\n"; }
        else { $fail++; echo "FAIL: $label\n"; }
    }
    function p9o_eq($actual, $expected, $label) {
        p9o_assert($actual === $expected, $label . ' expected=' . var_export($expected, true) . ' got=' . var_export($actual, true));
    }
    function p9o_reset_runtime() {
        $GLOBALS['p9o']['executor_calls'] = array();
        $GLOBALS['p9o']['cli_lines'] = array();
        $GLOBALS['p9o']['cli_errors'] = array();
    }

    // 1. Strict user-id parser.
    $parsed = MigrationBatch::parseUserIds("1, 2\n3\t4");
    p9o_eq($parsed['ok'], true, 'P9O-01 parse valid');
    p9o_eq($parsed['user_ids'], array(1,2,3,4), 'P9O-01 IDs exact');
    p9o_eq(MigrationBatch::parseUserIds('01,2')['reason'], 'user_id_invalid', 'P9O-01 leading zero rejected');
    p9o_eq(MigrationBatch::parseUserIds('1,1')['reason'], 'duplicate_user_id', 'P9O-01 duplicate rejected');
    p9o_eq(MigrationBatch::parseUserIds('1e2')['reason'], 'user_id_invalid', 'P9O-01 exponent rejected');

    // 2. Settings are resolved from existing Woo gateway option only.
    $GLOBALS['p9o']['options'] = array();
    p9o_eq(MigrationSettings::resolve()['reason'], 'settings_missing', 'P9O-02 missing settings');
    $GLOBALS['p9o']['options'][MigrationSettings::OPTION_KEY] = array('api_key'=>'secret-api','test_mode'=>'maybe');
    p9o_eq(MigrationSettings::resolve()['reason'], 'test_mode_invalid', 'P9O-02 malformed mode rejected');
    $GLOBALS['p9o']['options'][MigrationSettings::OPTION_KEY] = array('api_key'=>'secret-api','test_mode'=>'yes');
    $settings = MigrationSettings::resolve();
    p9o_eq($settings['ok'], true, 'P9O-02 settings valid');
    p9o_eq($settings['is_test_mode'], true, 'P9O-02 test mode exact');
    $redacted = MigrationSettings::redact($settings);
    p9o_assert(!array_key_exists('api_key', $redacted), 'P9O-02 redaction removes API key');

    // 3. Bounded page + explicit resume offset.
    $GLOBALS['p9o']['executor_results'] = array(
        1 => array('success'=>true,'reason'=>'already_clean','classification'=>'CLEAN','migrated'=>false,'idempotent'=>true,'ledger_written'=>false,'token_digest'=>null),
        2 => array('success'=>true,'reason'=>'migrated','classification'=>'CLEAN','migrated'=>true,'idempotent'=>false,'ledger_written'=>true,'token_digest'=>str_repeat('a',64)),
        3 => array('success'=>false,'reason'=>'preflight_blocked','classification'=>'BLOCKED','migrated'=>false,'idempotent'=>false,'ledger_written'=>false,'token_digest'=>null),
    );
    p9o_reset_runtime();
    $r = MigrationBatch::run(array(1,2,3), 'secret-api', true, true, 0, 2);
    p9o_eq($r['processed'], 2, 'P9O-03 bounded processed');
    p9o_eq($r['next_offset'], 2, 'P9O-03 next offset');
    p9o_eq($r['done'], false, 'P9O-03 not done');
    p9o_eq($r['success'], true, 'P9O-03 page success');
    p9o_eq(count($GLOBALS['p9o']['executor_calls']), 2, 'P9O-03 exactly two executor calls');
    p9o_eq($GLOBALS['p9o']['executor_calls'][0]['dry_run'], true, 'P9O-03 dry-run propagated');
    $json = json_encode($r);
    p9o_assert(strpos($json, 'secret-api') === false, 'P9O-03 API key not returned');
    p9o_assert(strpos($json, 'SHOULD_NEVER_ESCAPE') === false, 'P9O-03 nested raw token not returned');

    // 4. Resume page surfaces per-user failure without losing progress.
    p9o_reset_runtime();
    $r = MigrationBatch::run(array(1,2,3), 'secret-api', true, false, 2, 2);
    p9o_eq($r['processed'], 1, 'P9O-04 resumed one');
    p9o_eq($r['done'], true, 'P9O-04 done');
    p9o_eq($r['next_offset'], null, 'P9O-04 no next offset');
    p9o_eq($r['success'], false, 'P9O-04 failure summarized');
    p9o_eq($r['summary']['failed'], 1, 'P9O-04 failed count');
    p9o_eq($r['summary']['classifications']['BLOCKED'], 1, 'P9O-04 blocked count');
    p9o_eq($GLOBALS['p9o']['executor_calls'][0]['dry_run'], false, 'P9O-04 execute propagated');

    // 5. Executor exception is isolated and redacted.
    p9o_reset_runtime();
    $r = MigrationBatch::run(array(4), 'secret-api', false, true, 0, 1);
    p9o_eq($r['results'][0]['reason'], 'executor_exception', 'P9O-05 exception classified');
    p9o_eq($r['success'], false, 'P9O-05 exception fails page');

    // 6. Invalid windows/caps fail before executor.
    p9o_reset_runtime();
    p9o_eq(MigrationBatch::run(array(1), 'secret-api', false, true, -1, 1)['reason'], 'invalid_offset', 'P9O-06 negative offset rejected');
    p9o_eq(MigrationBatch::run(array(1), 'secret-api', false, true, 0, MigrationBatch::MAX_LIMIT + 1)['reason'], 'invalid_limit', 'P9O-06 over-limit rejected');
    p9o_eq(count($GLOBALS['p9o']['executor_calls']), 0, 'P9O-06 invalid window never executes');

    // 7. CLI preflight resolves settings, emits safe JSON, and never exposes API key.
    p9o_reset_runtime();
    $cli = new MigrationCliCommand();
    $cli->preflight(array(), array('user-ids'=>'1,2','limit'=>'2','offset'=>'0'));
    p9o_eq(count($GLOBALS['p9o']['cli_lines']), 1, 'P9O-07 CLI emitted one payload');
    p9o_assert(strpos($GLOBALS['p9o']['cli_lines'][0], 'secret-api') === false, 'P9O-07 CLI hides API key');
    p9o_eq(count($GLOBALS['p9o']['cli_errors']), 0, 'P9O-07 CLI preflight no errors');

    // 8. CLI execute requires explicit --yes.
    p9o_reset_runtime();
    try { $cli->execute(array(), array('user-ids'=>'1')); } catch (RuntimeException $e) {}
    p9o_assert(count($GLOBALS['p9o']['cli_errors']) === 1 && strpos($GLOBALS['p9o']['cli_errors'][0], 'explicit_yes_required') !== false, 'P9O-08 CLI execute confirmation required');
    p9o_eq(count($GLOBALS['p9o']['executor_calls']), 0, 'P9O-08 no execute without yes');
    p9o_reset_runtime();
    $cli->execute(array(), array('user-ids'=>'1','yes'=>true));
    p9o_eq($GLOBALS['p9o']['executor_calls'][0]['dry_run'], false, 'P9O-08 confirmed execute writes enabled');

    // 9. Failed CLI batch emits redacted result, then exits non-zero.
    p9o_reset_runtime();
    $threw = false;
    try { $cli->preflight(array(), array('user-ids'=>'3')); } catch (RuntimeException $e) { $threw = true; }
    p9o_eq($threw, true, 'P9O-09 failed CLI batch exits nonzero');
    p9o_eq(count($GLOBALS['p9o']['cli_lines']), 1, 'P9O-09 failed CLI batch emits result first');
    p9o_assert(strpos($GLOBALS['p9o']['cli_lines'][0], 'secret-api') === false, 'P9O-09 failed output hides API key');
    p9o_assert(count($GLOBALS['p9o']['cli_errors']) === 1 && strpos($GLOBALS['p9o']['cli_errors'][0], 'preflight_completed_with_failures') !== false, 'P9O-09 failure exit reason exact');

    // 10. Static operational safety contracts.
    $batchSource = file_get_contents($root . '/src/Migration/MigrationBatch.php');
    $cliSource = file_get_contents($root . '/src/Migration/MigrationCliCommand.php');
    $adminSource = file_get_contents($root . '/src/Migration/MigrationAdmin.php');
    $bootstrapSource = file_get_contents($root . '/src/Migration/MigrationBootstrap.php');
    p9o_assert(strpos($cliSource, '--api-key') === false && strpos($adminSource, 'name="api_key"') === false, 'P9O-10 no credential input surface');
    p9o_assert(strpos($adminSource, "current_user_can(self::CAPABILITY)") !== false, 'P9O-10 admin capability enforced');
    p9o_assert(strpos($adminSource, 'check_admin_referer(self::NONCE_ACTION, self::NONCE_FIELD)') !== false, 'P9O-10 admin nonce enforced');
    p9o_assert(strpos($adminSource, 'explicit_execute_confirmation_required') !== false, 'P9O-10 admin execute confirmation enforced');
    p9o_assert(strpos($bootstrapSource, "add_command('simplixpay-upayments migration'") !== false, 'P9O-10 canonical CLI namespace');
    p9o_assert(strpos($bootstrapSource, "add_action('admin_menu'") !== false, 'P9O-10 admin menu registered');
    p9o_assert(strpos($bootstrapSource, 'wp_enqueue_scripts') === false && strpos($bootstrapSource, 'woocommerce_checkout') === false, 'P9O-10 no checkout/frontend hook');
    $opsSource = $batchSource . $cliSource . $adminSource . $bootstrapSource;
    p9o_assert(strpos($opsSource, 'curl_') === false && strpos($opsSource, 'wp_remote_') === false && strpos($opsSource, 'getSavedCards') === false, 'P9O-10 no provider transport path');
    p9o_assert(strpos($batchSource, 'MAX_LIMIT = 50') !== false && strpos($batchSource, 'MAX_INPUT_USERS = 500') !== false, 'P9O-10 hard batch bounds present');

    echo "\n--- Phase 9I Operations Report ---\nPASS: $pass\nFAIL: $fail\n";
    exit($fail === 0 ? 0 : 1);
}
