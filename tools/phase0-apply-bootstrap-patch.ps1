$ErrorActionPreference = 'Stop'

$Branch = 'phase-0/release-identity-updater'
$File = 'UPayments.php'

Write-Host '=== Phase 0 guarded bootstrap patch ==='

git fetch origin --prune
if ($LASTEXITCODE -ne 0) { throw 'git fetch failed' }

$localBranchExists = $false
git show-ref --verify --quiet "refs/heads/$Branch"
if ($LASTEXITCODE -eq 0) { $localBranchExists = $true }

if ($localBranchExists) {
    git switch $Branch
} else {
    git switch -c $Branch --track "origin/$Branch"
}
if ($LASTEXITCODE -ne 0) { throw "Could not switch/create $Branch" }

git pull --ff-only origin $Branch
if ($LASTEXITCODE -ne 0) { throw 'Could not fast-forward Phase 0 branch' }

if ((git status --porcelain).Length -ne 0) {
    throw 'Working tree is not clean. Preserve/revert local work before running this helper.'
}

$source = [IO.File]::ReadAllText((Join-Path (Get-Location) $File)) -replace "`r`n", "`n"

$forbidden = @(
    'https://github.com/upaymentskwt/woocommerce',
    'PucFactory',
    'plugin-update-checker'
)

if ($source.Contains('Plugin Name: SimplixPay for UPayments')) {
    foreach ($needle in $forbidden) {
        if ($source.Contains($needle)) { throw "Patched bootstrap still contains forbidden updater authority: $needle" }
    }
    Write-Host 'Bootstrap is already patched; nothing to do.'
    exit 0
}

$old = @'
<?php
/**
 * Plugin Name: UPayments
 * Plugin URI: https://developers.upayments.com/reference/woocommerce
 * Description: UPayments Plugin with Unified payment gateway supporting Old/New design, Save Card, and Multimerchant. Supports Block Checkout, Auto Deduction for Subscriptions, Bookable Products.
 * Version: 3.1.1
 * Author: <a href="https://developers.upayments.com/reference/woocommerce" target="_blank">UPayments Company</a>
 * Author URI: https://developers.upayments.com/reference/woocommerce
 * Requires at least: 5.6
 * Requires PHP: 7.2+
 * License: MIT
 * Text Domain: upayments
 * Domain Path: /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define("UP_PLUGIN_URL", plugin_dir_url(__FILE__));
define("UP_PLUGIN_PATH", plugin_dir_path(__FILE__));
define('UPAYMENTS_PLUGIN_FILE', __FILE__ );

require_once __DIR__ . '/vendor/plugin-update-checker/plugin-update-checker.php';
require_once __DIR__ . '/includes/Token/CustomerTokenIdentity.php';

use UPayments\Subscription\Cron\Scheduler;
use UPayments\Subscription\Checkout\Fields;
use UPayments\Subscription\Manager;
use UPayments\Token\CustomerTokenIdentity;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$updateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/upaymentskwt/woocommerce',
    __FILE__,
    'upayments-V2.2.1'
);

// Optional: use releases instead of tags
$updateChecker->getVcsApi()->enableReleaseAssets();

'@
$old = $old -replace "`r`n", "`n"

$new = @'
<?php
/**
 * Plugin Name: SimplixPay for UPayments
 * Plugin URI: https://github.com/SimplixInnovations/simplixpay-upayments
 * Description: Independently engineered UPayments payment integration for WooCommerce by Simplix Innovations.
 * Version: 0.1.0
 * Author: Simplix Innovations
 * Author URI: https://simplixi.com
 * Requires at least: 5.6
 * Requires PHP: 7.2
 * License: MIT
 * Text Domain: upayments
 * Domain Path: /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define("UP_PLUGIN_URL", plugin_dir_url(__FILE__));
define("UP_PLUGIN_PATH", plugin_dir_path(__FILE__));
define('UPAYMENTS_PLUGIN_FILE', __FILE__ );

require_once __DIR__ . '/src/Release/Identity.php';
require_once __DIR__ . '/includes/Token/CustomerTokenIdentity.php';

use Simplix\Pay\UPayments\Release\Identity;
use UPayments\Subscription\Cron\Scheduler;
use UPayments\Subscription\Checkout\Fields;
use UPayments\Subscription\Manager;
use UPayments\Token\CustomerTokenIdentity;

define('SIMPLIXPAY_UPAYMENTS_VERSION', Identity::VERSION);
define('SIMPLIXPAY_UPAYMENTS_SLUG', Identity::SLUG);
define('SIMPLIXPAY_UPAYMENTS_PLUGIN_FILE', __FILE__);
define('SIMPLIXPAY_UPAYMENTS_UPDATE_CHANNEL', Identity::UPDATE_CHANNEL);

'@
$new = $new -replace "`r`n", "`n"

if (-not $source.StartsWith($old, [StringComparison]::Ordinal)) {
    throw 'Bootstrap prefix does not match the independently reviewed H12 source. No file was changed.'
}

if (($source.Split($old, [StringSplitOptions]::None).Count - 1) -ne 1) {
    throw 'Expected bootstrap prefix does not occur exactly once. No file was changed.'
}

$patched = $new + $source.Substring($old.Length)

foreach ($needle in $forbidden) {
    if ($patched.Contains($needle)) { throw "Forbidden updater authority remains: $needle" }
}

$required = @(
    'Plugin Name: SimplixPay for UPayments',
    'Version: 0.1.0',
    "define('UPAYMENTS_PLUGIN_FILE', __FILE__ );",
    'SIMPLIXPAY_UPAYMENTS_VERSION',
    '/src/Release/Identity.php',
    '?wc-api=wc_upayments'
)
foreach ($needle in $required) {
    if (-not $patched.Contains($needle)) { throw "Required compatibility/release marker missing: $needle" }
}

$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
[IO.File]::WriteAllText((Join-Path (Get-Location) $File), $patched, $utf8NoBom)

git diff --check -- $File
if ($LASTEXITCODE -ne 0) { throw 'git diff --check failed' }

Write-Host '=== Bootstrap diff ==='
git diff -- $File

Write-Host '=== Committing ==='
git add -- $File
git commit -m 'feat: take ownership of plugin release identity'
if ($LASTEXITCODE -ne 0) { throw 'git commit failed' }

git push origin $Branch
if ($LASTEXITCODE -ne 0) { throw 'git push failed' }

Write-Host '=== DONE ==='
Write-Host 'Bootstrap patch committed and pushed. CI/reviewer verification is still required; do not merge.'
