<?php
/**
 * Machine-readable SUCheckout legacy-identity residue contract.
 *
 * Old product identity is allowed only where it is explicit historical
 * evidence, the temporary pre-rename GitHub repository coordinate, or a
 * compatibility/migration fixture that intentionally proves the old package.
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

$patterns = array(
    'SimplixPay',
    'simplixpay-upayments',
    'Simplix\\Pay\\UPayments',
    'sucheckout-for-upayments',
);

$historical_prefixes = array(
    'docs/history/',
    'docs/superpowers/',
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
$compatibility_files = array(
    '.github/workflows/release-artifact.yml',
    'README.md',
    'docs/COMPATIBILITY.md',
    'docs/project/PROJECT-STATUS.md',
    'docs/project/RELEASE-ENGINEERING.md',
    'scripts/install-wp-test-environment.sh',
    'tests/integration/UpgradeCompatibilityTest.php',
);

$tracked = shell_exec('cd ' . escapeshellarg($root) . ' && git ls-files -z');
sur_assert(is_string($tracked) && $tracked !== '', 'tracked-file inventory is available');
$unexpected = array();

foreach (explode("\0", (string) $tracked) as $path) {
    if ($path === '' || !is_file($root . '/' . $path)) {
        continue;
    }

    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (!in_array($extension, array('php','js','css','md','txt','json','yml','yaml','xml','sh'), true)
        && basename($path) !== 'AGENTS.md'
    ) {
        continue;
    }

    $source = @file_get_contents($root . '/' . $path);
    if (!is_string($source)) {
        continue;
    }

    $is_historical = in_array($path, $historical_files, true);
    foreach ($historical_prefixes as $prefix) {
        if (strpos($path, $prefix) === 0) {
            $is_historical = true;
            break;
        }
    }
    $is_compatibility = in_array($path, $compatibility_files, true);

    foreach ($patterns as $pattern) {
        if (strpos($source, $pattern) === false) {
            continue;
        }
        if ($is_historical) {
            continue;
        }
        if ($is_compatibility && $pattern === 'simplixpay-upayments') {
            continue;
        }
        $unexpected[] = $path . ' :: ' . $pattern;
    }
}

$unexpected = array_values(array_unique($unexpected));
foreach ($unexpected as $item) {
    echo "UNEXPLAINED: {$item}\n";
}
sur_assert($unexpected === array(), 'no unexplained retired first-party identity remains');

echo "\nSUCheckout Residue: {$pass} PASS / {$fail} FAIL\n";
exit($fail === 0 ? 0 : 1);
