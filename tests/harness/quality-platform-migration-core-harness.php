<?php

$pass = 0;
$fail = 0;
$root = dirname(__DIR__, 2);

function q16_assert($condition, $label) {
    global $pass, $fail;
    if ($condition) { ++$pass; echo "PASS: " . $label . "\n"; return; }
    ++$fail; echo "FAIL: " . $label . "\n";
}
function q16_read($root, $relative) {
    $value = file_get_contents($root . '/' . $relative);
    q16_assert(is_string($value), 'source readable: ' . $relative);
    return is_string($value) ? $value : '';
}
function q16_has($source, $needle) { return strpos($source, $needle) !== false; }
function q16_blob($path) {
    $bytes = file_get_contents($path);
    return is_string($bytes) ? sha1('blob ' . strlen($bytes) . "\0" . $bytes) : '';
}

$phpstan = q16_read($root, 'phpstan.neon.dist');
$phpcs = q16_read($root, 'phpcs.xml.dist');
$preflight = q16_read($root, 'src/Migration/MigrationPreflight.php');
$batch = q16_read($root, 'src/Migration/MigrationBatch.php');
$executor = q16_read($root, 'src/Migration/MigrationExecutor.php');
$preflight_tests = q16_read($root, 'tests/unit/Migration/MigrationPreflightTest.php');
$batch_tests = q16_read($root, 'tests/unit/Migration/MigrationBatchTest.php');
$executor_tests = q16_read($root, 'tests/unit/Migration/MigrationExecutorTest.php');
$fixture = q16_read($root, 'tests/support/wordpress-migration-core.php');
$stubs = q16_read($root, 'tests/phpstan/migration-core-stubs.php');
$bootstrap = q16_read($root, 'tests/bootstrap.php');
$workflow = q16_read($root, '.github/workflows/quality-gates.yml');
$quality = q16_read($root, 'docs/project/QUALITY-PLATFORM.md');
$status = q16_read($root, 'docs/project/PROJECT-STATUS.md');
$readme = q16_read($root, 'README.md');
$agents = q16_read($root, 'AGENTS.md');
$playbook = q16_read($root, 'docs/project/MASTER-ENGINEERING-PLAYBOOK.md');
$audit = q16_read($root, 'docs/project/REPOSITORY-AUDIT.md');
$handoff = q16_read($root, 'docs/project/NEW-CHAT-HANDOFF.md');

foreach (array('src/Migration/MigrationPreflight.php','src/Migration/MigrationBatch.php','src/Migration/MigrationExecutor.php') as $path) {
    q16_assert(q16_has($phpstan, $path), 'PHPStan owns migration core: ' . $path);
    q16_assert(q16_has($phpcs, $path), 'PHPCS owns migration core: ' . $path);
}
q16_assert(q16_has($phpstan, 'tests/phpstan/migration-core-stubs.php'), 'PHPStan loads migration-core stubs');
q16_assert(!q16_has($phpstan, 'baseline'), 'Q16 remains baseline-free');
q16_assert(!q16_has($phpstan, 'ignoreErrors'), 'Q16 has no ignored analyzer errors');

q16_assert(q16_has($preflight, 'const MAX_ORDERS = 200;'), 'preflight scan remains bounded');
q16_assert(q16_has($preflight, 'const GLOBAL_PROVENANCE_LIMIT = 200;'), 'provenance scan remains bounded');
q16_assert(q16_has($preflight, "preg_match('/^(?:0|[1-9][0-9]*)\\z/', \$value)"), 'preflight numeric IDs use absolute end anchor');
q16_assert(q16_has($preflight, "preg_match('/^[0-9a-f]{32}\\z/', \$value)"), 'preflight generation uses absolute end anchor');
q16_assert(q16_has($preflight, 'LIMIT %d'), 'provenance scan limit is prepared as data');
q16_assert(q16_has($preflight, 'KIND_LEGACY_COMPAT') && q16_has($preflight, 'SOURCE_LEGACY_VERIFIED_CAPTURE'), 'preflight preserves legacy-only migration provenance');
q16_assert(!q16_has($preflight, 'wp_remote_') && !q16_has($preflight, 'curl_'), 'preflight performs no provider transport');

q16_assert(q16_has($batch, 'const MAX_INPUT_USERS = 500;'), 'batch input cap remains exact');
q16_assert(q16_has($batch, 'const MAX_LIMIT = 50;'), 'batch page cap remains exact');
q16_assert(q16_has($batch, "RESULT_LEDGER_KEY = '_simplixpay_upayments_migration_result_v1'"), 'operations ledger identity remains separate');
q16_assert(q16_has($batch, "hash_hmac('sha256'"), 'resume fingerprint remains HMAC scoped');
q16_assert(q16_has($batch, "preg_match('/^[a-z0-9_]+\\z/', \$reason)"), 'durable reason tokens use absolute end anchor');
q16_assert(q16_has($batch, 'operations_result_ledger_write_failed') && q16_has($batch, 'batch_checkpoint_failed'), 'checkpoint uncertainty remains fail closed');

q16_assert(q16_has($executor, 'MigrationPreflight::inspect('), 'executor reuses preflight contract');
q16_assert(q16_has($executor, 'SELECT GET_LOCK(%s, 5)') && q16_has($executor, 'SELECT RELEASE_LOCK(%s)'), 'executor lock contract remains exact');
q16_assert(q16_has($executor, 'KIND_LEGACY_COMPAT') && q16_has($executor, 'SOURCE_LEGACY_VERIFIED_CAPTURE'), 'executor preserves legacy provenance contract');
q16_assert(!q16_has($executor, 'SOURCE_CREATE_201'), 'executor cannot fabricate Create-201 provenance');
foreach (array('wp_remote_','curl_','process_payment','add_meta_data(','save_meta_data(','delete_meta_data(','Scheduler','CycleClaim') as $needle) {
    q16_assert(!q16_has($executor, $needle), 'executor excludes unrelated ownership: ' . $needle);
}

foreach (array('invalid_inputs_fail_closed_before_history_access','fresh_user_is_clean_and_unscoped_legacy_history_is_migratable','cross_user_token_conflict_remains_blocked','terminal_newline_order_identifier_is_rejected_as_indeterminate','terminal_newline_generation_is_not_accepted_as_identity_context') as $name) q16_assert(q16_has($preflight_tests, $name), 'preflight test: ' . $name);
foreach (array('user_id_parser_preserves_exact_canonical_contract','bounded_clean_page_writes_redacted_checkpoint_and_resumes','trailing_newline_reason_cannot_be_reused_as_durable_checkpoint','invalid_windows_fail_before_execution_or_checkpoint_mutation') as $name) q16_assert(q16_has($batch_tests, $name), 'batch test: ' . $name);
foreach (array('clean_user_is_idempotent_without_lock_or_mutation','dry_run_migratable_user_performs_no_identity_mutation','existing_secret_legacy_orphan_migrates_to_exact_legacy_provenance','invalid_inputs_fail_closed_before_preflight') as $name) q16_assert(q16_has($executor_tests, $name), 'executor test: ' . $name);
q16_assert(q16_has($fixture, 'SimplixPay_Test_Migration_Core_WPDB'), 'fixture models deterministic DB boundary');
q16_assert(q16_has($stubs, 'namespace UPayments\\Token'), 'stub models H12 namespace');
q16_assert(q16_has($bootstrap, "require __DIR__ . '/support/wordpress-migration-core.php';"), 'PHPUnit bootstrap loads migration fixture');

q16_assert(q16_blob($root . '/includes/Subscription/Cron/Scheduler.php') === '5251866d4df2d1326e7c09f0c8ec1d146c0bb325', 'protected Scheduler blob remains exact');
q16_assert(q16_blob($root . '/includes/Subscription/Cron/CycleClaim.php') === 'c34d83e2d77cc65024fe663e4c378cecb2b17347', 'protected CycleClaim blob remains exact');
foreach (array('phase-9i-preflight-harness.php','phase-9i-executor-harness.php','phase-9i-operations-harness.php') as $name) q16_assert(q16_has($workflow, 'run: php tests/harness/' . $name), 'Phase 9I regression remains mandatory: ' . $name);
q16_assert(q16_has($workflow, 'run: php tests/harness/quality-platform-migration-core-harness.php'), 'Q16 harness is mandatory');
q16_assert(q16_has($workflow, 'if: ${{ always() }}'), 'H12 aggregator always runs');
q16_assert(q16_has($agents, 'quality-platform-migration-core-harness.php'), 'AGENTS keeps Q16 mandatory');

foreach (array('01a06d45fcc0bc3d08da8d58f6be177b232bb1d4','ea5b0b3880a99999577d51a9ed5f6a8c77a52cf0','Quality Gates run #253','144 tests / 899 assertions','Q15 **107/0**','a4bbb05021dbded73072c0ba108a18245b60ad88','Quality Gates run #254','implementation branch deleted') as $evidence) q16_assert(q16_has($quality, $evidence), 'Q15 closure evidence pinned: ' . $evidence);
q16_assert(q16_has($quality, '**Status:** Q16 / IMPLEMENTATION'), 'quality record advances to Q16');
q16_assert(q16_has($status, '| Quality Platform Q15 subscription-presentation analysis | **DONE / VERIFIED** |'), 'project status preserves Q15 completion row');
q16_assert(q16_has($status, '## Latest verified milestone — Quality Platform Q15 subscription-presentation analysis'), 'project status names Q15 as latest verified milestone');
foreach (array('01a06d45fcc0bc3d08da8d58f6be177b232bb1d4','ea5b0b3880a99999577d51a9ed5f6a8c77a52cf0','a4bbb05021dbded73072c0ba108a18245b60ad88','Quality Gates run #253','Quality Gates run #254','144 tests / 899 assertions','Q15 Subscription Presentation Analysis: **107/0**','implementation branch `quality/subscription-presentation-analysis`: **deleted after verified merge**') as $evidence) q16_assert(q16_has($status, $evidence), 'project status pins Q15 closure evidence: ' . $evidence);
q16_assert(q16_has($playbook, 'Quality Platform Q15: DONE / VERIFIED; PR #41; merge a4bbb05021dbded73072c0ba108a18245b60ad88; tree ea5b0b3880a99999577d51a9ed5f6a8c77a52cf0; Q15 107/0; post-merge Quality Gates #254 SUCCESS'), 'playbook restart snapshot records Q15 closure');
q16_assert(q16_has($playbook, 'Last verified implementation main SHA: a4bbb05021dbded73072c0ba108a18245b60ad88'), 'playbook restart snapshot uses Q15 main SHA');
q16_assert(q16_has($playbook, 'Canonical implementation tree: ea5b0b3880a99999577d51a9ed5f6a8c77a52cf0'), 'playbook restart snapshot uses Q15 tree');
q16_assert(q16_has($handoff, '- Quality Platform Q15 subscription-presentation analysis: **DONE / VERIFIED**'), 'handoff preserves Q15 completion row');
q16_assert(q16_has($handoff, '## Latest verified milestone — Quality Platform Q15'), 'handoff names Q15 as latest verified milestone');
foreach (array('01a06d45fcc0bc3d08da8d58f6be177b232bb1d4','ea5b0b3880a99999577d51a9ed5f6a8c77a52cf0','a4bbb05021dbded73072c0ba108a18245b60ad88','Quality Gates run #253','Quality Gates run #254','144 tests / 899 assertions','Q15 was **107/0**') as $evidence) q16_assert(q16_has($handoff, $evidence), 'handoff pins Q15 closure evidence: ' . $evidence);
q16_assert(q16_has($readme, '| Quality Platform Q1-Q15 | **DONE / VERIFIED** |'), 'README completion table includes Q15');
q16_assert(q16_has($audit, '`src/Migration/MigrationPreflight.php`') && q16_has($audit, '`src/Migration/MigrationBatch.php`') && q16_has($audit, '`src/Migration/MigrationExecutor.php`'), 'repository audit current Q16 tranche names all migration-core owners');
q16_assert(q16_has($audit, 'permanent Q1/Q2/Q3/Q4/Q5/Q6/Q7/Q8/Q9/Q10/Q11/Q12/Q13/Q14/Q15/Q16 and historical regression gates'), 'repository audit current Q16 tranche requires Q1-Q16 regressions');
q16_assert(!q16_has($audit, 'PHPUnit characterization of product/admin schema, product-meta authorization, malformed cart/order payloads, account ownership/actions, exact filter allowlists, dates and escaped output'), 'repository audit removes stale Q15 presentation tranche bullet');
q16_assert(
    q16_has($status, '| Current program gate | **Full Automated Quality Platform — Q16** |')
    || q16_has($status, '| Quality Platform Q16 migration-core analysis | **DONE / VERIFIED** |'),
    'project status preserves Q16 current-or-closed state'
);
q16_assert(q16_has($readme, 'The current program gate is **Full Automated Quality Platform — Q16**.'), 'README advances to Q16');
q16_assert(q16_has($playbook, '- [x] Full Automated Quality Platform — **Q15 / DONE / VERIFIED** through PR #41 and post-merge Quality Gates #254.'), 'playbook preserves Q15 as completed');
q16_assert(q16_has($playbook, '- [ ] Full Automated Quality Platform — **Q16 / CURRENT GATE**.'), 'playbook has the single current Q16 ledger entry');
q16_assert(!q16_has($playbook, '**Q16 / PLANNED MIGRATION CORE**'), 'playbook has no contradictory planned-Q16 entry');
q16_assert(q16_has($playbook, '**Q17 / PLANNED PAYMENT-RUNTIME CLOSEOUT**'), 'playbook keeps Q17 as the next planned payment-runtime closeout');
q16_assert(!q16_has($audit, 'No Q18 is planned or authorized'), 'repository audit does not contradict the enterprise-risk extension policy');
q16_assert(q16_has($audit, 'any later Q gate requires a concrete separately bounded enterprise-critical risk'), 'repository audit preserves the bounded enterprise-risk extension policy');

echo "\nQ16 Migration Core Analysis: " . $pass . " PASS / " . $fail . " FAIL\n";
exit($fail === 0 ? 0 : 1);
