<?php

$q9_pass = 0;
$q9_fail = 0;
$q9_root = dirname(__DIR__, 2);

function q9_assert($condition, $label) {
    global $q9_pass, $q9_fail;
    if ($condition) { ++$q9_pass; echo "PASS: {$label}\n"; return; }
    ++$q9_fail; echo "FAIL: {$label}\n";
}

function q9_read($root, $relative) {
    $contents = file_get_contents($root . '/' . $relative);
    q9_assert(is_string($contents), "source readable: {$relative}");
    return is_string($contents) ? $contents : '';
}

function q9_contains($source, $needle) { return strpos($source, $needle) !== false; }

$phpstan = q9_read($q9_root, 'phpstan.neon.dist');
$phpcs = q9_read($q9_root, 'phpcs.xml.dist');
$source = q9_read($q9_root, 'src/Migration/MigrationSettings.php');
$tests = q9_read($q9_root, 'tests/unit/Migration/MigrationSettingsTest.php');
$fixture = q9_read($q9_root, 'tests/support/wordpress-option-store.php');
$stubs = q9_read($q9_root, 'tests/phpstan/wordpress-option-stubs.php');
$workflow = q9_read($q9_root, '.github/workflows/quality-gates.yml');
$quality = q9_read($q9_root, 'docs/project/QUALITY-PLATFORM.md');
$status = q9_read($q9_root, 'docs/project/PROJECT-STATUS.md');
$readme = q9_read($q9_root, 'README.md');
$handoff = q9_read($q9_root, 'docs/project/NEW-CHAT-HANDOFF.md');
$playbook = q9_read($q9_root, 'docs/project/MASTER-ENGINEERING-PLAYBOOK.md');

q9_assert(q9_contains($phpstan, 'src/Migration/MigrationSettings.php'), 'PHPStan owns migration settings');
q9_assert(q9_contains($phpcs, 'src/Migration/MigrationSettings.php'), 'PHPCS owns migration settings');
q9_assert(!q9_contains($phpstan, 'baseline'), 'Q9 remains baseline-free');
q9_assert(!q9_contains($phpstan, 'ignoreErrors'), 'Q9 introduces no ignored analyzer errors');

q9_assert(q9_contains($source, "OPTION_KEY = 'woocommerce_upayments_settings'"), 'resolver retains the historical Woo gateway option');
q9_assert(q9_contains($source, 'get_option(self::OPTION_KEY, null)'), 'resolver reads only its exact option with a null default');
q9_assert(!q9_contains($source, 'update_option('), 'resolver never updates settings');
q9_assert(!q9_contains($source, 'add_option('), 'resolver never creates settings');
q9_assert(!q9_contains($source, 'delete_option('), 'resolver never deletes settings');
q9_assert(q9_contains($source, "!is_string(\$api_key) || \$api_key === '' || trim(\$api_key) === ''"), 'API key boundary rejects non-strings and blank values');
q9_assert(q9_contains($source, "array_key_exists('test_mode', \$settings) ? \$settings['test_mode'] : 'no'"), 'absent Woo checkbox defaults to live mode');
q9_assert(q9_contains($source, "\$test_mode !== 'yes' && \$test_mode !== 'no'"), 'mode parser accepts only exact Woo checkbox states');
q9_assert(q9_contains($source, "'api_key' => \$api_key"), 'successful internal result preserves the exact in-memory credential');
q9_assert(q9_contains($source, "'mode' => (\$test_mode === 'yes') ? 'test' : 'live'"), 'successful result derives the exact bounded mode');
q9_assert(q9_contains($source, 'public static function redact($resolved)'), 'redaction remains an explicit reporting boundary');
q9_assert(q9_contains($source, "in_array(\$reason, array('settings_missing', 'api_key_missing', 'test_mode_invalid'), true)"), 'redaction allowlists exact failure reasons');
q9_assert(q9_contains($source, "(\$mode === 'test' || \$mode === 'live')"), 'redaction allowlists exact reportable modes');
$redact_start = strpos($source, 'public static function redact');
$failure_start = strpos($source, 'private static function failure');
$redact_source = ($redact_start !== false && $failure_start !== false && $failure_start > $redact_start)
    ? substr($source, $redact_start, $failure_start - $redact_start)
    : '';
q9_assert($redact_source !== '' && !q9_contains($redact_source, "'api_key' =>"), 'redaction return path never includes the API key');

q9_assert(substr_count($tests, 'public function test_') >= 6, 'MigrationSettings has focused PHPUnit characterization');
foreach (array(
    'resolver_reads_only_the_historical_gateway_option',
    'missing_or_malformed_api_keys_fail_closed',
    'test_mode_accepts_only_exact_woocommerce_checkbox_states',
    'absent_no_and_yes_modes_resolve_exactly_without_mutation',
    'redaction_never_returns_the_api_key_or_unbounded_fields',
    'settings_boundary_is_final_and_non_instantiable',
) as $name) {
    q9_assert(q9_contains($tests, $name), "migration-settings test exists: {$name}");
}
q9_assert(q9_contains($tests, "'  exact-secret  '"), 'credential byte preservation is executable regression evidence');
q9_assert(q9_contains($tests, "'must-never-escape'"), 'redaction explicitly tests secret and extra-field exclusion');
q9_assert(q9_contains($tests, "'must-never-escape-' . str_repeat('x', 4096)"), 'redaction tests secret-bearing unbounded retained fields');
q9_assert(q9_contains($tests, 'assertStringNotContainsString($sentinel, serialize($redacted))'), 'redaction proves retained fields cannot emit the sentinel');
q9_assert(q9_contains($fixture, 'function get_option('), 'unit fixture provides deterministic option reads');
q9_assert(q9_contains($stubs, 'function get_option('), 'analysis stubs declare the bounded option read');

q9_assert(q9_contains($workflow, 'quality-platform-migration-settings-harness.php'), 'Q9 harness is mandatory in Quality Gates');
q9_assert(q9_contains($workflow, 'if: ${{ always() }}'), 'protected H12 aggregator still always runs');
foreach (array(
    '458bf35b0cc60d78dc8f32d28605d1f60cbc501c',
    '109415fa6a4bc04bba60bb23275bc192dd232559',
    'Quality Gates run #218',
    'b59eb2d50b86a38d8ea130de63c38a672db86d32',
    'Quality Gates run #219',
    'implementation branch deleted',
) as $evidence) {
    q9_assert(q9_contains($quality, $evidence), "Q8 closure evidence is pinned: {$evidence}");
}
q9_assert(q9_contains($quality, '**Status:** Q9 / IMPLEMENTATION'), 'quality record advances to Q9');
q9_assert(q9_contains($status, '| Current program gate | **Full Automated Quality Platform — Q9** |'), 'project status advances to Q9');
q9_assert(q9_contains($readme, 'The current program gate is **Full Automated Quality Platform — Q9**.'), 'README advances to Q9');
q9_assert(q9_contains($playbook, 'Last verified implementation main SHA: b59eb2d50b86a38d8ea130de63c38a672db86d32'), 'playbook pins Q8 merge');
q9_assert(q9_contains($playbook, 'Canonical implementation tree: 109415fa6a4bc04bba60bb23275bc192dd232559'), 'playbook pins Q8 tree');
q9_assert(!q9_contains($handoff, 'CURRENT / Q8'), 'handoff rejects stale current-Q8 marker');
q9_assert(!q9_contains($playbook, 'CURRENT / Q8'), 'playbook rejects stale current-Q8 marker');
q9_assert(q9_contains($workflow, "reject_across_live_records 'CURRENT / Q8'"), 'Governance rejects stale current-Q8 markers');

echo "\nQ9 Migration Settings Analysis: {$q9_pass} PASS / {$q9_fail} FAIL\n";
exit($q9_fail === 0 ? 0 : 1);
