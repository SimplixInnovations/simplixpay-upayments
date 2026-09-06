<?php
/**
 * Deterministic release-artifact certification harness.
 *
 * RED until canonical build/verify tooling and distribution exclusions exist.
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
    $path = $root . '/' . $relative;
    if (!is_file($path)) {
        return '';
    }
    $value = file_get_contents($path);
    return is_string($value) ? $value : '';
}


function release_inspect_zip_independently($zip_path, $checksum_path, $manifest_path, $version) {
    release_assert(class_exists('ZipArchive'), 'PHP ZipArchive is available for independent artifact inspection');
    if (!class_exists('ZipArchive')) {
        return;
    }

    $zip = new ZipArchive();
    $opened = $zip->open($zip_path);
    release_assert($opened === true, 'independent inspector opens the built ZIP');
    if ($opened !== true) {
        return;
    }

    $prefix = 'simplixpay-upayments/';
    $names = array();
    for ($i = 0; $i < $zip->numFiles; ++$i) {
        $name = $zip->getNameIndex($i);
        if (is_string($name)) {
            $names[] = $name;
        }
    }

    release_assert(count($names) > 0, 'independent inspector sees non-empty ZIP contents');
    release_assert(count($names) === count(array_unique($names)), 'independent inspector rejects duplicate ZIP paths');

    $sorted = $names;
    sort($sorted, SORT_STRING);
    release_assert($names === $sorted, 'independent inspector confirms deterministic sorted ZIP paths');

    $safe = true;
    $forbidden = false;
    foreach ($names as $name) {
        if (strpos($name, $prefix) !== 0 || substr($name, -1) === '/' || strpos($name, '\\') !== false) {
            $safe = false;
            break;
        }
        $relative = substr($name, strlen($prefix));
        if ($relative === '' || $relative === '.' || $relative === '..' || strpos($relative, '../') === 0 || strpos($relative, '/..') !== false) {
            $safe = false;
            break;
        }

        $forbidden_exact = array(
            '.distignore', '.editorconfig', '.gitattributes', '.gitignore',
            'AGENTS.md', 'composer.json', 'composer.lock', 'phpcs.xml.dist',
            'phpstan.neon.dist', 'phpunit.xml.dist', 'CONTRIBUTING.md',
            'MAINTAINERS.md', 'SUPPORT.md', 'UPSTREAM.md',
        );
        $forbidden_prefixes = array('.github/', '.cache/', '.phpunit.cache/', 'tests/', 'vendor/', 'docs/', 'scripts/');

        if (in_array($relative, $forbidden_exact, true)) {
            $forbidden = true;
        }
        foreach ($forbidden_prefixes as $blocked_prefix) {
            if (strpos($relative, $blocked_prefix) === 0) {
                $forbidden = true;
            }
        }
    }
    release_assert($safe, 'independent inspector enforces one safe canonical package root');
    release_assert(!$forbidden, 'independent inspector finds no forbidden development/control paths');

    $relative_names = array_map(
        function ($name) use ($prefix) {
            return substr($name, strlen($prefix));
        },
        $names
    );

    $required = array(
        'UPayments.php',
        'index.php',
        'uninstall.php',
        'LICENSE',
        'README.md',
        'CHANGELOG.md',
        'NOTICE.md',
        'SECURITY.md',
        'src/Release/Identity.php',
        'includes/class-wc-gateway-upayments-blocks.php',
    );
    $required_ok = true;
    foreach ($required as $required_path) {
        if (!in_array($required_path, $relative_names, true)) {
            $required_ok = false;
        }
    }
    foreach (array('src/', 'includes/', 'assets/', 'templates/') as $required_prefix) {
        $present = false;
        foreach ($relative_names as $relative_name) {
            if (strpos($relative_name, $required_prefix) === 0) {
                $present = true;
                break;
            }
        }
        if (!$present) {
            $required_ok = false;
        }
    }
    release_assert($required_ok, 'independent inspector confirms required runtime files and subtrees');

    $plugin_source = $zip->getFromName($prefix . 'UPayments.php');
    $identity_source = $zip->getFromName($prefix . 'src/Release/Identity.php');
    release_assert(
        is_string($plugin_source)
            && strpos($plugin_source, 'Plugin Name: SimplixPay for UPayments') !== false
            && strpos($plugin_source, 'Version: ' . $version) !== false
            && strpos($plugin_source, 'Text Domain: upayments') !== false,
        'independent inspector confirms packaged plugin name/version/text-domain identity'
    );
    release_assert(
        is_string($identity_source)
            && strpos($identity_source, "public const LEGACY_MAIN_FILE = 'UPayments.php';") !== false,
        'independent inspector confirms transitional packaged main-file identity'
    );

    $actual_zip_hash = hash_file('sha256', $zip_path);
    $checksum_text = is_file($checksum_path) ? file_get_contents($checksum_path) : false;
    release_assert(
        is_string($actual_zip_hash)
            && is_string($checksum_text)
            && $checksum_text === $actual_zip_hash . '  ' . basename($zip_path) . "\n",
        'independent inspector validates ZIP checksum sidecar'
    );

    $manifest_text = is_file($manifest_path) ? file_get_contents($manifest_path) : false;
    $manifest = array();
    $manifest_valid = is_string($manifest_text);
    if ($manifest_valid) {
        foreach (preg_split('/\r?\n/', trim($manifest_text)) as $line) {
            if ($line === '') {
                continue;
            }
            if (preg_match('/^([0-9a-f]{64})  (.+)$/', $line, $matches) !== 1 || isset($manifest[$matches[2]])) {
                $manifest_valid = false;
                break;
            }
            $manifest[$matches[2]] = $matches[1];
        }
    }

    $manifest_names = array_keys($manifest);
    sort($manifest_names, SORT_STRING);
    $zip_names = $names;
    sort($zip_names, SORT_STRING);
    if ($manifest_names !== $zip_names) {
        $manifest_valid = false;
    }

    if ($manifest_valid) {
        foreach ($names as $name) {
            $bytes = $zip->getFromName($name);
            if (!is_string($bytes) || !isset($manifest[$name]) || hash('sha256', $bytes) !== $manifest[$name]) {
                $manifest_valid = false;
                break;
            }
        }
    }
    release_assert($manifest_valid, 'independent inspector validates manifest paths and every per-file digest');

    $zip->close();
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

$build_relative = 'scripts/build-release.sh';
$verify_relative = 'scripts/verify-release.sh';
$workflow_relative = '.github/workflows/release-artifact.yml';

$build = release_read($root, $build_relative);
$verify = release_read($root, $verify_relative);
$workflow = release_read($root, $workflow_relative);
$installer = release_read($root, 'scripts/install-wp-test-environment.sh');
$distignore = release_read($root, '.distignore');
$identity = release_read($root, 'src/Release/Identity.php');

release_assert($build !== '', 'canonical release builder exists');
release_assert($verify !== '', 'canonical release verifier exists');
release_assert($workflow !== '', 'release-artifact workflow exists');
release_assert($installer !== '', 'real WordPress/WooCommerce installer exists');
release_assert($distignore !== '', 'distribution exclusion contract exists');

$required_exclusions = array(
    '/.cache/',
    '/.github/',
    '/.phpunit.cache/',
    '/tests/',
    '/vendor/',
    '/composer.json',
    '/composer.lock',
    '/phpcs.xml.dist',
    '/phpstan.neon.dist',
    '/phpunit.xml.dist',
    '/docs/',
    '/scripts/',
    '/AGENTS.md',
    '/.distignore',
    '/.editorconfig',
    '/.gitattributes',
    '/.gitignore'
);
foreach ($required_exclusions as $excluded) {
    release_assert(
        strpos($distignore, $excluded) !== false,
        'distribution excludes development/control path: ' . $excluded
    );
}

release_assert(
    strpos($identity, "public const LEGACY_MAIN_FILE = 'UPayments.php';") !== false,
    'release artifact retains transitional main-file identity'
);
release_assert(
    strpos($identity, "public const TARGET_MAIN_FILE = 'simplixpay-upayments.php';") !== false,
    'future main-file target remains distinct and is not silently migrated'
);

$version = null;
if (preg_match("/public const VERSION = '([^']+)';/", $identity, $matches) === 1) {
    $version = $matches[1];
}
release_assert(is_string($version) && $version !== '', 'release version is readable from canonical identity');

release_assert(
    strpos($workflow, 'php tests/harness/release-artifact-harness.php') !== false,
    'release workflow invokes the permanent artifact harness'
);
release_assert(
    strpos($installer, 'SIMPLIXPAY_PLUGIN_ZIP') !== false
        && strpos($installer, 'plugin install') !== false,
    'runtime installer supports a real packaged ZIP instead of source symlink'
);
release_assert(
    strpos($workflow, 'actions/upload-artifact@ea165f8d65b6e75b540449e92b4886f43607fa02') !== false,
    'release workflow uploads the exact built artifact with an immutable action pin'
);
release_assert(
    strpos($workflow, "github.event_name == 'pull_request' && github.event.pull_request.head.sha || github.sha") !== false
        && substr_count($workflow, 'ref: ${{ env.RELEASE_SOURCE_SHA }}') === 2,
    'release artifact checkout is pinned to the exact candidate source SHA instead of a synthetic PR merge ref'
);
release_assert(
    strpos($workflow, 'actions/download-artifact@d3f86a106a0bac45b974a628896c90dbdf5c8093') !== false,
    'packaged runtime jobs download the exact built artifact with an immutable action pin'
);
release_assert(
    strpos($workflow, 'storage: [legacy, hpos]') !== false,
    'packaged runtime smoke covers legacy and HPOS authoritative storage'
);
release_assert(
    strpos($workflow, 'Verify packaged activation and Classic registration') !== false
        && strpos($workflow, 'Verify packaged release support metadata') !== false
        && strpos($workflow, 'Verify packaged Blocks registration and availability') !== false
        && strpos($workflow, 'Verify packaged order CRUD') !== false,
    'packaged runtime smoke exercises activation, metadata, Blocks and order CRUD'
);

if ($build !== '' && $verify !== '' && is_string($version) && $version !== '') {
    $python = trim((string) shell_exec('command -v python3 2>/dev/null'));
    release_assert($python !== '', 'Python 3 is available for deterministic ZIP tooling');

    $tmp = sys_get_temp_dir() . '/simplixpay-release-' . getmypid() . '-' . substr(hash('sha256', __FILE__), 0, 8);
    $first = $tmp . '/first';
    $second = $tmp . '/second';
    @mkdir($first, 0777, true);
    @mkdir($second, 0777, true);

    $build_path = $root . '/' . $build_relative;
    $verify_path = $root . '/' . $verify_relative;
    $zip_name = 'simplixpay-upayments-' . $version . '.zip';

    $command_one = 'cd ' . escapeshellarg($root)
        . ' && bash ' . escapeshellarg($build_path) . ' ' . escapeshellarg($first);
    exec($command_one, $output_one, $code_one);
    release_assert($code_one === 0, 'first deterministic release build exits zero');

    $first_zip = $first . '/' . $zip_name;
    $first_checksum = $first_zip . '.sha256';
    $first_manifest = $first . '/simplixpay-upayments-' . $version . '.manifest.sha256';

    release_assert(is_file($first_zip), 'first build emits canonical versioned ZIP');
    release_assert(is_file($first_checksum), 'first build emits ZIP SHA-256');
    release_assert(is_file($first_manifest), 'first build emits sorted per-file manifest');

    if (is_file($first_zip)) {
        $verify_command = 'cd ' . escapeshellarg($root)
            . ' && bash ' . escapeshellarg($verify_path) . ' ' . escapeshellarg($first_zip);
        exec($verify_command, $verify_output, $verify_code);
        release_assert($verify_code === 0, 'release verifier accepts the built ZIP');

        release_inspect_zip_independently($first_zip, $first_checksum, $first_manifest, $version);
    }

    $command_two = 'cd ' . escapeshellarg($root)
        . ' && bash ' . escapeshellarg($build_path) . ' ' . escapeshellarg($second);
    exec($command_two, $output_two, $code_two);
    release_assert($code_two === 0, 'second deterministic release build exits zero');

    $second_zip = $second . '/' . $zip_name;
    $second_checksum = $second_zip . '.sha256';
    $second_manifest = $second . '/simplixpay-upayments-' . $version . '.manifest.sha256';

    release_assert(is_file($second_zip), 'second build emits canonical versioned ZIP');
    release_assert(is_file($second_checksum), 'second build emits ZIP SHA-256');
    release_assert(is_file($second_manifest), 'second build emits sorted per-file manifest');

    if (is_file($first_zip) && is_file($second_zip)) {
        release_assert(
            hash_file('sha256', $first_zip) === hash_file('sha256', $second_zip),
            'same source commit builds byte-identical ZIP twice'
        );
    }
    if (is_file($first_checksum) && is_file($second_checksum)) {
        release_assert(
            file_get_contents($first_checksum) === file_get_contents($second_checksum),
            'same source commit emits identical ZIP checksum evidence twice'
        );
    }
    if (is_file($first_manifest) && is_file($second_manifest)) {
        release_assert(
            file_get_contents($first_manifest) === file_get_contents($second_manifest),
            'same source commit emits identical sorted manifest twice'
        );
    }

    release_rm_tree($tmp);
}

echo "\nRelease Artifact: {$pass} PASS / {$fail} FAIL\n";
exit($fail === 0 ? 0 : 1);
