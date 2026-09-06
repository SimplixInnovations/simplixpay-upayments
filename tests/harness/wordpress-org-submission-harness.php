<?php

$wporg_pass = 0;
$wporg_fail = 0;
$wporg_root = dirname(__DIR__, 2);

function wporg_assert($condition, $label) {
    global $wporg_pass, $wporg_fail;
    if ($condition) {
        ++$wporg_pass;
        echo "PASS: {$label}\n";
        return;
    }
    ++$wporg_fail;
    echo "FAIL: {$label}\n";
}

function wporg_read($root, $relative) {
    $path = $root . '/' . $relative;
    $contents = is_file($path) ? file_get_contents($path) : false;
    wporg_assert(is_string($contents) && $contents !== '', "submission control file exists: {$relative}");
    return is_string($contents) ? $contents : '';
}

function wporg_has($source, $needle) {
    return is_string($source) && strpos($source, $needle) !== false;
}

$readme = wporg_read($wporg_root, 'readme.txt');
$plugin = wporg_read($wporg_root, 'UPayments.php');
$identity = wporg_read($wporg_root, 'src/Release/Identity.php');
$verify = wporg_read($wporg_root, 'scripts/verify-release.sh');
$release_harness = wporg_read($wporg_root, 'tests/harness/release-artifact-harness.php');
$release_workflow = wporg_read($wporg_root, '.github/workflows/release-artifact.yml');
$submission_workflow = wporg_read($wporg_root, '.github/workflows/wordpress-org-submission-check.yml');
$quality_workflow = wporg_read($wporg_root, '.github/workflows/quality-gates.yml');

$version = '';
if (preg_match("/const VERSION = '([^']+)'/", $identity, $matches) === 1) {
    $version = $matches[1];
}
wporg_assert($version !== '', 'submission harness reads canonical release version');

wporg_assert(wporg_has($readme, '=== SimplixPay for UPayments ==='), 'WordPress.org readme uses the canonical product name');
wporg_assert(strlen($readme) > 0 && strlen($readme) < 10000, 'WordPress.org readme remains below the 10 KB directory guidance');
wporg_assert($version !== '' && wporg_has($readme, 'Stable tag: ' . $version), 'readme Stable Tag matches the canonical plugin version');
wporg_assert(wporg_has($readme, 'Requires at least: 6.9'), 'readme preserves the verified WordPress floor');
wporg_assert(wporg_has($readme, 'Tested up to: 7.1'), 'readme preserves the verified WordPress tested ceiling');
wporg_assert(wporg_has($readme, 'Requires PHP: 7.4'), 'readme preserves the verified PHP floor');
wporg_assert(wporg_has($readme, 'License: MIT'), 'readme declares the repository license without silent relicensing');
wporg_assert(wporg_has($readme, 'https://github.com/SimplixInnovations/simplixpay-upayments'), 'readme publishes the maintained source location');
wporg_assert(wporg_has($readme, 'https://upayments.com/en/terms-of-service'), 'readme documents the external UPayments service terms/policies');
wporg_assert(wporg_has($readme, 'Available payment methods depend on your UPayments account and provider configuration.'), 'readme preserves provider/account-bounded payment-method claims');
wporg_assert(wporg_has($readme, 'Subscription auto-deduction requires separately validated provider setup.'), 'readme preserves the external validation boundary for auto-deduction');
wporg_assert(!wporg_has($readme, 'Stable tag: trunk'), 'new-plugin readme never uses the discouraged trunk stable tag');
wporg_assert(!wporg_has($readme, 'guaranteed PCI') && !wporg_has($readme, 'PCI compliant plugin'), 'readme makes no plugin-level PCI compliance guarantee');

wporg_assert(wporg_has($verify, '"readme.txt"'), 'release verifier allowlists/requires readme.txt');
wporg_assert(wporg_has($release_harness, "'readme.txt'"), 'independent release harness requires readme.txt in the package');
wporg_assert(wporg_has($release_workflow, "- 'readme.txt'"), 'release artifact workflow is invalidated by readme.txt changes');
wporg_assert(wporg_has($quality_workflow, 'wordpress-org-submission-harness.php'), 'submission harness remains mandatory in Quality/H12');

wporg_assert(
    wporg_has($submission_workflow, 'WordPress/plugin-check-action@10857da14b6c2246d15402b3e69f777edcf8c12e')
        && wporg_has($submission_workflow, 'build-dir: ${{ runner.temp }}/plugin-check/simplixpay-upayments')
        && wporg_has($submission_workflow, 'categories: plugin_repo')
        && wporg_has($submission_workflow, 'slug: simplixpay-upayments'),
    'official Plugin Check runs against the unpacked canonical package with immutable action pin'
);
wporg_assert(
    wporg_has($submission_workflow, 'bash scripts/build-release.sh submission-output')
        && wporg_has($submission_workflow, 'bash scripts/verify-release.sh "$ZIP"'),
    'WordPress.org submission check consumes the deterministic verified release artifact'
);

echo "\nWordPress.org Submission Readiness: {$wporg_pass} PASS / {$wporg_fail} FAIL\n";
exit($wporg_fail === 0 ? 0 : 1);
