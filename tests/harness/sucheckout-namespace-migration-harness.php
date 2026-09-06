<?php

/**
 * First-party PHP namespace/package migration guard for SUCheckout.
 */

$root = dirname(__DIR__, 2);
$pass = 0;
$fail = 0;

function sucheckout_ns_assert($condition, $message) {
    global $pass, $fail;
    if ($condition) {
        ++$pass;
        echo "PASS: {$message}\n";
        return;
    }
    ++$fail;
    echo "FAIL: {$message}\n";
}

$composer_path = $root . '/composer.json';
$composer = json_decode((string) file_get_contents($composer_path), true);
sucheckout_ns_assert(is_array($composer), 'composer.json decodes');

if (is_array($composer)) {
    sucheckout_ns_assert(
        isset($composer['name']) && $composer['name'] === 'simplix-innovations/sucheckout-upayments',
        'Composer package is canonical SUCheckout package'
    );
    sucheckout_ns_assert(
        isset($composer['autoload']['psr-4']['Simplixi\\SUCheckout\\UPayments\\'])
            && $composer['autoload']['psr-4']['Simplixi\\SUCheckout\\UPayments\\'] === 'src/',
        'Composer production PSR-4 root is canonical SUCheckout namespace'
    );
    sucheckout_ns_assert(
        isset($composer['autoload-dev']['psr-4']['Simplixi\\SUCheckout\\UPayments\\Tests\\'])
            && $composer['autoload-dev']['psr-4']['Simplixi\\SUCheckout\\UPayments\\Tests\\'] === 'tests/unit/',
        'Composer test PSR-4 root is canonical SUCheckout namespace'
    );
}

$scan_roots = array($root . '/src', $root . '/tests');
$legacy = 'Simplix\\Pay\\UPayments';
$canonical = 'Simplixi\\SUCheckout\\UPayments';
$legacy_files = array();
$canonical_files = 0;

foreach ($scan_roots as $scan_root) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($scan_root, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') {
            continue;
        }

        $source = file_get_contents($file->getPathname());
        if (!is_string($source)) {
            continue;
        }

        $relative = str_replace($root . '/', '', str_replace('\\', '/', $file->getPathname()));
        if (strpos($source, $legacy) !== false) {
            $legacy_files[] = $relative;
        }
        if (strpos($source, $canonical) !== false) {
            ++$canonical_files;
        }
    }
}

$bootstrap = (string) file_get_contents($root . '/UPayments.php');
if (strpos($bootstrap, $legacy) !== false) {
    $legacy_files[] = 'UPayments.php';
}
if (strpos($bootstrap, $canonical) !== false) {
    ++$canonical_files;
}

sort($legacy_files);
foreach ($legacy_files as $path) {
    sucheckout_ns_assert(false, "retired first-party namespace remains: {$path}");
}
sucheckout_ns_assert(count($legacy_files) === 0, 'no retired first-party namespace remains in executable/test PHP');
sucheckout_ns_assert($canonical_files >= 20, 'canonical SUCheckout namespace is established across first-party code');

echo "\nSUCheckout Namespace Migration: {$pass} PASS / {$fail} FAIL\n";
exit($fail === 0 ? 0 : 1);
