<?php

$q13_pass = 0;
$q13_fail = 0;
$q13_root = dirname(__DIR__, 2);

function q13_assert($condition, $label) {
    global $q13_pass, $q13_fail;
    if ($condition) { ++$q13_pass; echo "PASS: {$label}\n"; return; }
    ++$q13_fail; echo "FAIL: {$label}\n";
}

function q13_read($root, $relative) {
    $contents = file_get_contents($root . '/' . $relative);
    q13_assert(is_string($contents), "source readable: {$relative}");
    return is_string($contents) ? $contents : '';
}

function q13_contains($source, $needle) { return strpos($source, $needle) !== false; }

function q13_git_blob_sha($path) {
    $bytes = file_get_contents($path);
    return is_string($bytes) ? sha1('blob ' . strlen($bytes) . "\0" . $bytes) : '';
}

$phpstan = q13_read($q13_root, 'phpstan.neon.dist');
$phpcs = q13_read($q13_root, 'phpcs.xml.dist');
$source = q13_read($q13_root, 'src/Migration/MigrationCliCommand.php');
$tests = q13_read($q13_root, 'tests/unit/Migration/MigrationCliCommandTest.php');
$fixture = q13_read($q13_root, 'tests/support/wordpress-migration-bootstrap.php');
$stubs = q13_read($q13_root, 'tests/phpstan/migration-bootstrap-stubs.php');
$workflow = q13_read($q13_root, '.github/workflows/quality-gates.yml');
$quality = q13_read($q13_root, 'docs/project/QUALITY-PLATFORM.md');
$status = q13_read($q13_root, 'docs/project/PROJECT-STATUS.md');
$readme = q13_read($q13_root, 'README.md');
$handoff = q13_read($q13_root, 'docs/project/NEW-CHAT-HANDOFF.md');
$playbook = q13_read($q13_root, 'docs/project/MASTER-ENGINEERING-PLAYBOOK.md');

q13_assert(q13_contains($phpstan, 'src/Migration/MigrationCliCommand.php'), 'PHPStan owns migration CLI adapter');
q13_assert(q13_contains($phpcs, 'src/Migration/MigrationCliCommand.php'), 'PHPCS owns migration CLI adapter');
q13_assert(!q13_contains($phpstan, 'baseline'), 'Q13 remains baseline-free');
q13_assert(!q13_contains($phpstan, 'ignoreErrors'), 'Q13 introduces no ignored analyzer errors');

q13_assert(q13_contains($source, "if (!is_array(\$assoc_args) || !array_key_exists('yes', \$assoc_args))"), 'execute keeps explicit confirmation before parsing');
q13_assert(q13_contains($source, "array_key_exists('resume', \$assoc_args)"), 'resume flag remains explicit');
q13_assert(q13_contains($source, "if (\$resume && array_key_exists('offset', \$assoc_args))"), 'resume and offset remain mutually exclusive');
q13_assert(q13_contains($source, "preg_match('/^(?:0|[1-9][0-9]*)$/', \$value) === 1"), 'integer text parser remains canonical decimal only');
q13_assert(q13_contains($source, 'MigrationBatch::DEFAULT_LIMIT'), 'default batch limit remains centralized');
q13_assert(q13_contains($source, 'MigrationBatch::MAX_LIMIT'), 'maximum batch limit remains centralized');
q13_assert(q13_contains($source, 'MigrationSettings::resolve()'), 'credentials remain sourced only from existing settings');
q13_assert(q13_contains($source, 'MigrationSettings::redact($settings)'), 'CLI output keeps settings redaction');
q13_assert(q13_contains($source, "self::cliError('output_encode_failed')"), 'encoding failure remains fail closed');
q13_assert(q13_contains($source, "'SimplixPay UPayments migration: ' . \$reason"), 'CLI error prefix remains exact');
q13_assert(!q13_contains($source, "['api-key']"), 'CLI adapter exposes no API-key input');
q13_assert(!q13_contains($source, "array_key_exists('api-key'"), 'CLI adapter never parses API-key input');
foreach (array('wp_remote_', 'curl_', 'CURLOPT_', 'update_user_meta', 'process_payment', 'Scheduler', 'CycleClaim') as $forbidden) {
    q13_assert(!q13_contains($source, $forbidden), "CLI adapter excludes unrelated ownership: {$forbidden}");
}

foreach (array(
    'preflight_rejects_invalid_requests_with_exact_cli_error',
    'execute_requires_explicit_yes_before_request_parsing',
    'valid_request_parsing_preserves_exact_defaults_and_bounds',
    'strict_integer_parser_rejects_noncanonical_and_overflow_values',
    'emit_outputs_redacted_json_without_credentials',
    'cli_boundary_is_final_with_only_two_public_commands',
) as $name) {
    q13_assert(q13_contains($tests, $name), "migration CLI test exists: {$name}");
}
q13_assert(q13_contains($tests, "'resume with offset'"), 'invalid-request matrix covers resume/offset conflict');
q13_assert(q13_contains($tests, "'secret-api-key'"), 'redaction test uses a detectable secret fixture');
q13_assert(q13_contains($fixture, 'public static $lines'), 'CLI fixture records output lines');
q13_assert(q13_contains($fixture, 'public static $errors'), 'CLI fixture records errors');
q13_assert(q13_contains($stubs, 'static function line('), 'analysis stub declares CLI output boundary');
q13_assert(q13_contains($stubs, 'static function error('), 'analysis stub declares CLI error boundary');

q13_assert(q13_git_blob_sha($q13_root . '/includes/Subscription/Cron/Scheduler.php') === '5251866d4df2d1326e7c09f0c8ec1d146c0bb325', 'protected Scheduler blob remains exact');
q13_assert(q13_git_blob_sha($q13_root . '/includes/Subscription/Cron/CycleClaim.php') === 'c34d83e2d77cc65024fe663e4c378cecb2b17347', 'protected CycleClaim blob remains exact');

q13_assert(q13_contains($workflow, 'quality-platform-migration-cli-harness.php'), 'Q13 harness is mandatory in Quality Gates');
q13_assert(q13_contains($workflow, 'if: ${{ always() }}'), 'protected H12 aggregator still always runs');
foreach (array(
    '4396b83ef67a90d6d12d1d761e6c071e601c235c',
    'b8a9f956e304fa9dba7658809207ddae14b1f4e1',
    'Quality Gates run #231',
    '6dc53bdaf60f12774d7516294d7004974be3874f',
    'Quality Gates run #232',
    'implementation branch deleted',
) as $evidence) {
    q13_assert(q13_contains($quality, $evidence), "Q12 closure evidence is pinned: {$evidence}");
}
q13_assert(q13_contains($quality, '**Status:** Q13 / IMPLEMENTATION'), 'quality record advances to Q13');
q13_assert(q13_contains($status, '| Current program gate | **Full Automated Quality Platform — Q13** |'), 'project status advances to Q13');
q13_assert(q13_contains($readme, 'The current program gate is **Full Automated Quality Platform — Q13**.'), 'README advances to Q13');
q13_assert(q13_contains($playbook, 'Last verified implementation main SHA: 6dc53bdaf60f12774d7516294d7004974be3874f'), 'playbook pins Q12 merge');
q13_assert(q13_contains($playbook, 'Canonical implementation tree: b8a9f956e304fa9dba7658809207ddae14b1f4e1'), 'playbook pins Q12 tree');
q13_assert(!q13_contains($handoff, 'CURRENT / Q12'), 'handoff rejects stale current-Q12 marker');
q13_assert(!q13_contains($playbook, 'CURRENT / Q12'), 'playbook rejects stale current-Q12 marker');
q13_assert(q13_contains($workflow, "reject_across_live_records 'CURRENT / Q12'"), 'Governance rejects stale current-Q12 markers');

echo "\nQ13 Migration CLI Analysis: {$q13_pass} PASS / {$q13_fail} FAIL\n";
exit($q13_fail === 0 ? 0 : 1);
