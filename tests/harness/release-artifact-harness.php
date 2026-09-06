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
    }

    $command_two = 'cd ' . escapeshellarg($root)
        . ' && bash ' . escapeshellarg($build_path) . ' ' . escapeshellarg($second);
    exec($command_two, $output_two, $code_two);
    release_assert($code_two === 0, 'second deterministic release build exits zero');

    $second_zip = $second . '/' . $zip_name;
    $second_checksum = $second_zip . '.sha256';
    $second_manifest = $second . '/simplixpay-upayments-' . $version . '.manifest.sha256';

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
