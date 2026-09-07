<?php
/**
 * Machine-readable SUCheckout retired-identity residue contract.
 *
 * This gate distinguishes current/shippable identity from historical,
 * test-only and explicitly bounded migration compatibility evidence.
 */

$root = dirname(__DIR__, 2);
$pass = 0;
$fail = 0;

function sur_assert($condition, $message) {
    global $pass, $fail;
    if ($condition) {
        ++$pass;
        echo "PASS: {$message}\n";
        return;
    }
    ++$fail;
    echo "FAIL: {$message}\n";
}

function sur_read($root, $path) {
    $value = @file_get_contents($root . '/' . $path);
    return is_string($value) ? $value : '';
}

function sur_is_text_path($path) {
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    return in_array($extension, array('php','js','css','md','txt','json','yml','yaml','xml','sh'), true)
        || basename($path) === 'AGENTS.md';
}

$tracked_raw = shell_exec('cd ' . escapeshellarg($root) . ' && git ls-files -z');
sur_assert(is_string($tracked_raw) && $tracked_raw !== '', 'tracked-file inventory is available');
$tracked = array_values(array_filter(explode("\0", (string) $tracked_raw), 'strlen'));

/*
 * Tests deliberately exercise legacy names and old package roots. Immutable
 * historical engineering records also preserve then-current identifiers.
 * Neither surface is a current product-identity declaration.
 */
$historical_prefixes = array(
    'docs/history/',
    'docs/superpowers/',
    'tests/',
);
$historical_files = array(
    'CHANGELOG.md',
    'docs/project/ARCHITECTURE-CODE-QUALITY.md',
    'docs/project/BASELINE-H12.md',
    'docs/project/MASTER-ENGINEERING-PLAYBOOK.md',
    'docs/project/PHASE-0-RELEASE-IDENTITY.md',
    'docs/project/PHASE-9I-MIGRATION.md',
    'docs/project/PROVIDER-PAYMENT-LIFECYCLE.md',
    'docs/project/QUALITY-PLATFORM.md',
    'docs/project/REPOSITORY-AUDIT.md',
    'docs/project/REPOSITORY-READINESS.md',
    'docs/project/SECURITY-THREAT-MODEL.md',
);

/*
 * The pre-rename repository coordinate and legacy package root are allowed
 * only where a current migration, rollback, updater, workflow or provenance
 * contract must refer to them explicitly. OWNER-HANDOFF is included because
 * an executable rename/cleanup guide must name the old repository coordinate,
 * obsolete branches and legacy pre-release package root that the owner removes
 * or migrates.
 */
$legacy_slug_files = array(
    '.github/ISSUE_TEMPLATE/config.yml',
    '.github/workflows/compatibility-certification.yml',
    '.github/workflows/provider-sandbox-certification.yml',
    '.github/workflows/quality-gates.yml',
    '.github/workflows/release-artifact.yml',
    '.github/workflows/wordpress-org-submission-check.yml',
    'AGENTS.md',
    'NOTICE.md',
    'README.md',
    'UPSTREAM.md',
    'docs/COMPATIBILITY.md',
    'docs/ENGINEERING-ROADMAP.md',
    'docs/project/ENTERPRISE-CERTIFICATION.md',
    'docs/project/NEW-CHAT-HANDOFF.md',
    'docs/project/OWNER-HANDOFF.md',
    'docs/project/PROJECT-STATUS.md',
    'docs/project/RELEASE-ENGINEERING.md',
    'scripts/install-wp-test-environment.sh',
    'src/Migration/MigrationAdmin.php',
    'src/Migration/MigrationBootstrap.php',
);

$unexpected = array();
foreach ($tracked as $path) {
    if (!is_file($root . '/' . $path) || !sur_is_text_path($path)) {
        continue;
    }

    $is_historical = in_array($path, $historical_files, true);
    foreach ($historical_prefixes as $prefix) {
        if (strpos($path, $prefix) === 0) {
            $is_historical = true;
            break;
        }
    }
    if ($is_historical) {
        continue;
    }

    $source = sur_read($root, $path);

    // Retired human product names must not survive on current/live surfaces.
    foreach (array('SimplixPay for UPayments', 'SimplixPay UPayments') as $retired_human) {
        if (strpos($source, $retired_human) !== false) {
            $unexpected[] = $path . ' :: ' . $retired_human;
        }
    }

    // Retired first-party PHP namespace must not survive outside history/tests.
    if (strpos($source, 'Simplix\\Pay\\UPayments') !== false) {
        $unexpected[] = $path . ' :: Simplix\\Pay\\UPayments';
    }

    // Pre-rebrand first-party constant prefixes are branding residue, not
    // provider/persisted compatibility identifiers.
    if (strpos($source, 'SIMPLIXPAY_UPAYMENTS_') !== false) {
        $unexpected[] = $path . ' :: SIMPLIXPAY_UPAYMENTS_';
    }

    // The forbidden "for" technical form may appear only in the naming
    // standard that declares it forbidden and in this regression harness.
    if (strpos($source, 'sucheckout-for-upayments') !== false
        && $path !== 'docs/project/NAMING-IDENTITY-STANDARD.md'
        && $path !== 'tests/harness/sucheckout-residue-harness.php'
    ) {
        $unexpected[] = $path . ' :: sucheckout-for-upayments';
    }

    if (strpos($source, 'simplixpay-upayments') !== false
        && !in_array($path, $legacy_slug_files, true)
    ) {
        $unexpected[] = $path . ' :: simplixpay-upayments';
    }
}

$unexpected = array_values(array_unique($unexpected));
foreach ($unexpected as $item) {
    echo "UNEXPLAINED: {$item}\n";
}
sur_assert($unexpected === array(), 'no unexplained retired identity remains on live/shippable surfaces');

$current_identity_contracts = array(
    'README.md' => array('SUCheckout for UPayments', 'sucheckout-upayments'),
    'AGENTS.md' => array('SUCheckout for UPayments', 'Simplixi\\SUCheckout\\UPayments'),
    'docs/project/PROJECT-STATUS.md' => array('SUCheckout for UPayments', 'sucheckout-upayments'),
    'docs/project/NAMING-IDENTITY-STANDARD.md' => array('SUCheckout for UPayments', 'Simplixi\\SUCheckout\\UPayments'),
    'UPayments.php' => array(
        "define('SUCHECKOUT_UPAYMENTS_VERSION', Identity::VERSION);",
        "define('SUCHECKOUT_UPAYMENTS_SLUG', Identity::SLUG);",
        "define('SUCHECKOUT_UPAYMENTS_PLUGIN_FILE', __FILE__);",
        "define('SUCHECKOUT_UPAYMENTS_UPDATE_CHANNEL', Identity::UPDATE_CHANNEL);",
    ),
    'composer.json' => array('simplix-innovations/sucheckout-upayments', 'Simplixi\\\\SUCheckout\\\\UPayments\\\\'),
);
foreach ($current_identity_contracts as $contract_path => $needles) {
    $source = sur_read($root, $contract_path);
    sur_assert($source !== '', 'current identity source readable: ' . $contract_path);
    foreach ($needles as $needle) {
        sur_assert(strpos($source, $needle) !== false, $contract_path . ' contains canonical identity: ' . $needle);
    }
}

echo "\nSUCheckout Residue: {$pass} PASS / {$fail} FAIL\n";
exit($fail === 0 ? 0 : 1);
