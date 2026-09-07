<?php
/**
 * Deterministic canonical SUCheckout release-artifact certification.
 */

$root = dirname(__DIR__, 2);
$pass = 0;
$fail = 0;

function release_assert($condition, $message) {
    global $pass, $fail;
    if ($condition) {
        ++$pass;
        echo "PASS: {$message}\n";
        return;
    }
    ++$fail;
    echo "FAIL: {$message}\n";
}

function release_read($root, $relative) {
    $value = @file_get_contents($root . '/' . $relative);
    return is_string($value) ? $value : '';
}

function release_rm_tree($path) {
    if (!is_dir($path)) {
        if (is_file($path) || is_link($path)) {
            @unlink($path);
        }
        return;
    }
    $items = scandir($path);
    if (!is_array($items)) {
        return;
    }
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        release_rm_tree($path . '/' . $item);
    }
    @rmdir($path);
}

function release_run($command, &$output = null) {
    $lines = array();
    exec($command . ' 2>&1', $lines, $code);
    $output = implode("\n", $lines);
    return $code;
}

function release_rewrite_evidence_for_zip($zip_path, $checksum_path, $manifest_path) {
    if (!class_exists('ZipArchive')) {
        return false;
    }
    $zip = new ZipArchive();
    if ($zip->open($zip_path) !== true) {
        return false;
    }
    $names = array();
    for ($i = 0; $i < $zip->numFiles; ++$i) {
        $name = $zip->getNameIndex($i);
        if (is_string($name) && substr($name, -1) !== '/') {
            $names[] = $name;
        }
    }
    sort($names, SORT_STRING);
    $manifest = '';
    foreach ($names as $name) {
        $bytes = $zip->getFromName($name);
        if (!is_string($bytes)) {
            $zip->close();
            return false;
        }
        $manifest .= hash('sha256', $bytes) . '  ' . $name . "\n";
    }
    $zip->close();
    $zip_hash = hash_file('sha256', $zip_path);
    if (!is_string($zip_hash)) {
        return false;
    }
    return file_put_contents($manifest_path, $manifest) !== false
        && file_put_contents($checksum_path, $zip_hash . '  ' . basename($zip_path) . "\n") !== false;
}

$build = release_read($root, 'scripts/build-release.sh');
$verify = release_read($root, 'scripts/verify-release.sh');
$installer = release_read($root, 'scripts/install-wp-test-environment.sh');
$workflow = release_read($root, '.github/workflows/release-artifact.yml');
$distignore = release_read($root, '.distignore');
$identity = release_read($root, 'src/Release/Identity.php');

release_assert($build !== '', 'canonical release builder exists');
release_assert($verify !== '', 'canonical release verifier exists');
release_assert($installer !== '', 'real WordPress/WooCommerce installer exists');
release_assert($workflow !== '', 'release workflow exists');
release_assert($distignore !== '', 'distribution exclusion contract exists');

$version = '';
if (preg_match("/public const VERSION = '([^']+)';/", $identity, $matches) === 1) {
    $version = $matches[1];
}
release_assert($version !== '', 'release version is readable from canonical identity');
release_assert(strpos($identity, "public const LEGACY_MAIN_FILE = 'UPayments.php';") !== false, 'qualified UPayments.php bootstrap is retained');
release_assert(strpos($identity, "public const TARGET_MAIN_FILE = 'sucheckout-upayments.php';") !== false, 'unsafe physical main-file rename remains an explicit future target');

foreach (array('/.github/', '/tests/', '/vendor/', '/composer.json', '/composer.lock', '/docs/', '/scripts/') as $excluded) {
    release_assert(strpos($distignore, $excluded) !== false, 'distribution excludes control/development path: ' . $excluded);
}

release_assert(substr_count($build, 'sucheckout-upayments') >= 2, 'builder owns canonical SUCheckout ZIP/root slug');
release_assert(strpos($build, 'slug = "simplixpay-upayments"') === false, 'builder contains no retired package-root slug');
release_assert(substr_count($verify, 'sucheckout-upayments') >= 2, 'verifier owns canonical SUCheckout ZIP/root slug');
release_assert(strpos($verify, 'slug = "simplixpay-upayments"') === false, 'verifier contains no retired package-root slug');
release_assert(
    strpos($build, '"ls-tree", "-r", "-z", "HEAD"') !== false
        && strpos($build, 'HEAD:.distignore') !== false
        && strpos($build, 'cat-file", "blob"') !== false,
    'builder derives distribution paths and bytes from Git HEAD'
);
release_assert(
    strpos($verify, 'ZIP bytes do not match Git HEAD source') !== false
        && strpos($verify, 'HEAD:.distignore') !== false,
    'verifier binds packaged bytes to Git HEAD'
);
release_assert(strpos($installer, 'SUCHECKOUT_PLUGIN_SLUG:-sucheckout-upayments') !== false, 'real installer defaults to canonical SUCheckout root');

release_assert(strpos($workflow, "-name 'sucheckout-upayments-*.zip'") !== false, 'release workflow selects canonical SUCheckout artifacts');
release_assert(strpos($workflow, 'name: sucheckout-release-${{ env.RELEASE_SOURCE_SHA }}') !== false, 'release workflow evidence is keyed by exact candidate SHA');
release_assert(strpos($workflow, 'ref: ${{ env.RELEASE_SOURCE_SHA }}') !== false, 'release workflow checks out exact candidate source SHA');
release_assert(strpos($workflow, 'storage: [legacy, hpos]') !== false, 'packaged runtime covers legacy and HPOS storage');
release_assert(strpos($workflow, 'plugin activate sucheckout-upayments') !== false, 'packaged runtime activates canonical plugin slug');
release_assert(strpos($workflow, 'SUCHECKOUT_PLUGIN_SLUG=simplixpay-upayments') !== false, 'migration job seeds a real legacy-root installation');
release_assert(strpos($workflow, 'plugin deactivate simplixpay-upayments') !== false, 'migration job explicitly deactivates legacy root');
release_assert(strpos($workflow, 'plugin activate sucheckout-upayments') !== false, 'migration job explicitly activates canonical root');
release_assert(strpos($workflow, 'SUCHECKOUT_UPGRADE_PHASE=verify-legacy-rollback') !== false, 'migration job proves legacy rollback is non-destructive');
release_assert(strpos($workflow, 'plugin delete simplixpay-upayments') !== false, 'migration job ends with legacy package removed');
release_assert(strpos($workflow, 'actions/upload-artifact@ea165f8d65b6e75b540449e92b4886f43607fa02') !== false, 'artifact upload action is immutably pinned');
release_assert(strpos($workflow, 'actions/download-artifact@d3f86a106a0bac45b974a628896c90dbdf5c8093') !== false, 'artifact download action is immutably pinned');

if ($version !== '') {
    $tmp = sys_get_temp_dir() . '/sucheckout-release-' . getmypid() . '-' . substr(hash('sha256', __FILE__), 0, 8);
    $first = $tmp . '/first';
    $second = $tmp . '/second';
    @mkdir($first, 0777, true);
    @mkdir($second, 0777, true);

    $build_command = 'cd ' . escapeshellarg($root) . ' && bash scripts/build-release.sh ';
    $first_output = '';
    $first_code = release_run($build_command . escapeshellarg($first), $first_output);
    release_assert($first_code === 0, 'first deterministic canonical build exits zero');

    $zip_name = 'sucheckout-upayments-' . $version . '.zip';
    $manifest_name = 'sucheckout-upayments-' . $version . '.manifest.sha256';
    $first_zip = $first . '/' . $zip_name;
    $first_checksum = $first_zip . '.sha256';
    $first_manifest = $first . '/' . $manifest_name;
    release_assert(is_file($first_zip), 'first build emits canonical SUCheckout ZIP');
    release_assert(is_file($first_checksum), 'first build emits ZIP checksum');
    release_assert(is_file($first_manifest), 'first build emits per-file manifest');

    if (is_file($first_zip)) {
        $verify_output = '';
        $verify_code = release_run('cd ' . escapeshellarg($root) . ' && bash scripts/verify-release.sh ' . escapeshellarg($first_zip), $verify_output);
        release_assert($verify_code === 0, 'canonical verifier accepts exact built artifact');

        release_assert(class_exists('ZipArchive'), 'ZipArchive is available for independent inspection');
        if (class_exists('ZipArchive')) {
            $zip = new ZipArchive();
            $opened = $zip->open($first_zip);
            release_assert($opened === true, 'independent inspector opens canonical ZIP');
            if ($opened === true) {
                $names = array();
                for ($i = 0; $i < $zip->numFiles; ++$i) {
                    $name = $zip->getNameIndex($i);
                    if (is_string($name)) {
                        $names[] = $name;
                    }
                }
                $prefix = 'sucheckout-upayments/';
                $safe = count($names) > 0;
                foreach ($names as $name) {
                    if (strpos($name, $prefix) !== 0 || strpos($name, 'simplixpay-upayments/') === 0 || substr($name, -1) === '/') {
                        $safe = false;
                    }
                }
                release_assert($safe, 'independent inspector sees one canonical SUCheckout package root');
                release_assert(in_array($prefix . 'UPayments.php', $names, true), 'canonical package retains qualified UPayments.php bootstrap');
                release_assert(in_array($prefix . 'readme.txt', $names, true), 'canonical package contains WordPress.org readme');
                release_assert(!in_array($prefix . 'composer.json', $names, true), 'canonical package excludes Composer controls');

                $plugin = $zip->getFromName($prefix . 'UPayments.php');
                release_assert(
                    is_string($plugin)
                        && strpos($plugin, 'Plugin Name: SUCheckout for UPayments') !== false
                        && strpos($plugin, 'Text Domain: sucheckout-upayments') !== false
                        && strpos($plugin, 'Version: ' . $version) !== false,
                    'independent inspector confirms packaged SUCheckout metadata'
                );
                $zip->close();
            }
        }

        $tampered_dir = $tmp . '/tampered';
        @mkdir($tampered_dir, 0777, true);
        $tampered_zip = $tampered_dir . '/' . $zip_name;
        $tampered_checksum = $tampered_zip . '.sha256';
        $tampered_manifest = $tampered_dir . '/' . $manifest_name;
        $tampered_ready = copy($first_zip, $tampered_zip);
        if ($tampered_ready && class_exists('ZipArchive')) {
            $tampered = new ZipArchive();
            if ($tampered->open($tampered_zip) === true) {
                $target = 'sucheckout-upayments/assets/css/customer.css';
                $bytes = $tampered->getFromName($target);
                $tampered_ready = is_string($bytes)
                    && $tampered->addFromString($target, $bytes . "\n/* git-head-mismatch-probe */\n");
                $tampered->close();
            } else {
                $tampered_ready = false;
            }
        }
        $tampered_ready = $tampered_ready
            && release_rewrite_evidence_for_zip($tampered_zip, $tampered_checksum, $tampered_manifest);
        release_assert($tampered_ready, 'negative probe creates self-consistent tampered evidence');
        if ($tampered_ready) {
            $tampered_output = '';
            $tampered_code = release_run('cd ' . escapeshellarg($root) . ' && bash scripts/verify-release.sh ' . escapeshellarg($tampered_zip), $tampered_output);
            release_assert($tampered_code !== 0, 'verifier rejects self-consistent artifact bytes that differ from Git HEAD');
        }
    }

    $second_output = '';
    $second_code = release_run($build_command . escapeshellarg($second), $second_output);
    release_assert($second_code === 0, 'second deterministic canonical build exits zero');
    $second_zip = $second . '/' . $zip_name;
    $second_checksum = $second_zip . '.sha256';
    $second_manifest = $second . '/' . $manifest_name;
    release_assert(is_file($second_zip) && is_file($second_checksum) && is_file($second_manifest), 'second build emits complete canonical evidence');
    if (is_file($first_zip) && is_file($second_zip)) {
        release_assert(hash_file('sha256', $first_zip) === hash_file('sha256', $second_zip), 'same Git HEAD builds byte-identical canonical ZIP twice');
        release_assert(file_get_contents($first_checksum) === file_get_contents($second_checksum), 'same Git HEAD emits identical checksum twice');
        release_assert(file_get_contents($first_manifest) === file_get_contents($second_manifest), 'same Git HEAD emits identical manifest twice');
    }

    release_rm_tree($tmp);
}

echo "\nRelease Artifact: {$pass} PASS / {$fail} FAIL\n";
exit($fail === 0 ? 0 : 1);
