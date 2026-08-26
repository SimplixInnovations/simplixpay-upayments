<?php
/**
 * Architecture & Code-Quality Foundation characterization harness.
 *
 * Static-only by design: it must run without WordPress/WooCommerce bootstrap.
 */

$root = dirname(__DIR__, 2);
$pass = 0;
$fail = 0;

function arch_assert($condition, $message)
{
    global $pass, $fail;
    if ($condition) {
        $pass++;
        echo "PASS: {$message}\n";
        return;
    }

    $fail++;
    echo "FAIL: {$message}\n";
}

function arch_read($root, $path)
{
    $full = $root . '/' . $path;
    if (!is_file($full)) {
        return '';
    }
    $contents = file_get_contents($full);
    return is_string($contents) ? $contents : '';
}

function arch_contains($haystack, $needle)
{
    return is_string($haystack) && strpos($haystack, $needle) !== false;
}

function arch_matches($haystack, $pattern)
{
    return is_string($haystack) && preg_match($pattern, $haystack) === 1;
}

$architecture = arch_read($root, 'docs/project/ARCHITECTURE-CODE-QUALITY.md');
$gateway = arch_read($root, 'UPayments.php');
$status = arch_read($root, 'docs/project/PROJECT-STATUS.md');
$naming = arch_read($root, 'docs/project/NAMING-IDENTITY-STANDARD.md');
$paymentLifecycle = arch_read($root, 'src/Payment/PaymentLifecycle.php');
$securityStatus = arch_read($root, 'src/Security/PublicOrderStatus.php');
$tokenIdentity = arch_read($root, 'includes/Token/CustomerTokenIdentity.php');
$scheduler = arch_read($root, 'includes/Subscription/Cron/Scheduler.php');

arch_assert($architecture !== '', 'architecture control record exists');
arch_assert(arch_contains($architecture, '**Status:** DISCOVERY / CHARACTERIZATION'), 'architecture record is discovery/characterization');
arch_assert(arch_contains($architecture, 'Architecture & Code-Quality Foundation'), 'architecture gate is named explicitly');

$stageHeadings = array(
    'A1' => '### A1 — provider endpoint/mode resolution — first safe runtime seam',
    'A2' => '### A2 — payment-method availability client/cache',
    'A3' => '### A3 — gateway settings/admin/multi-merchant presentation',
    'A4' => '### A4 — subscription product/account presentation',
    'A5' => '### A5 — checkout payload/orchestration core',
);
$stagePositions = array();
foreach ($stageHeadings as $stage => $heading) {
    $position = strpos($architecture, $heading);
    $stagePositions[$stage] = $position;
    arch_assert($position !== false, "{$stage} extraction stage is present");
}
$ordered = true;
$previous = -1;
foreach ($stagePositions as $position) {
    if ($position === false || $position <= $previous) {
        $ordered = false;
        break;
    }
    $previous = $position;
}
arch_assert($ordered, 'A1-A5 extraction stages remain in frozen order');

arch_assert(arch_contains($architecture, 'no production runtime behavior changes in the discovery PR'), 'discovery tranche forbids runtime behavior changes');
arch_assert(arch_contains($architecture, 'This is not permission for a big-bang rewrite'), 'big-bang rewrite is prohibited');
arch_assert(arch_contains($architecture, 'exact accepted `UPayments.php` byte size for the current architecture milestone'), 'monolith ratchet update contract is explicit');
arch_assert(arch_contains($architecture, 'Composer only with an explicit distribution rule'), 'Composer introduction is gated by distribution contract');
arch_assert(arch_contains($architecture, 'PHPCS/WPCS and PHPStan incrementally'), 'static-analysis rollout is incremental');

arch_assert(arch_contains($status, '| Current program gate | **Architecture & Code-Quality Foundation — DISCOVERY** |'), 'project status keeps Architecture as current gate');
arch_assert(arch_contains($naming, '**Canonical slug:** `simplixpay-upayments`'), 'canonical slug remains protected');

$gatewayPath = $root . '/UPayments.php';
$gatewaySize = is_file($gatewayPath) ? filesize($gatewayPath) : false;
$acceptedGatewayBytes = 257832;
arch_assert(is_int($gatewaySize) && $gatewaySize === $acceptedGatewayBytes, 'UPayments.php matches current exact architecture ratchet');
arch_assert(arch_contains($gateway, 'class WC_Upayments extends WC_Payment_Gateway'), 'legacy WC_Upayments gateway compatibility class remains');
arch_assert(arch_contains($gateway, "add_filter(\"woocommerce_payment_gateways\", \"addUpaymentsGatewayClass\")"), 'WooCommerce gateway registration remains characterized');
arch_assert(arch_contains($gateway, 'public function process_payment'), 'process_payment compatibility entry point remains');
arch_assert(arch_contains($gateway, 'public function process_admin_options'), 'gateway settings save entry point remains');
arch_assert(arch_contains($gateway, 'public function payment_fields'), 'classic checkout payment_fields entry point remains');
arch_assert(arch_contains($gateway, 'public function return_from_upayments'), 'browser return compatibility entry point remains');
arch_assert(arch_contains($gateway, 'public function web_hook_handler'), 'legacy webhook compatibility entry point remains');
arch_assert(arch_contains($gateway, 'public function check_ipn_response'), 'wc_upayments callback dispatcher remains');
arch_assert(arch_contains($gateway, 'public function get_payment_staus'), 'historical public status-poll method remains as compatibility wrapper');
arch_assert(arch_contains($gateway, '\\Simplix\\Pay\\UPayments\\Security\\PublicOrderStatus::handle();'), 'public status polling delegates to Security boundary');
arch_assert(arch_contains($gateway, 'public function getAPIUrl('), 'public generic provider URL helper remains');
arch_assert(arch_contains($gateway, 'public function getAPIUrlForCreateToken'), 'public token endpoint helper remains');
arch_assert(arch_contains($gateway, 'public function getAPIUrlForCheckPaymentButtonStatus'), 'public payment-button endpoint helper remains');
arch_assert(arch_contains($gateway, 'public function getAPIUrlForRetreiveCards'), 'public saved-card endpoint helper remains');
arch_assert(arch_contains($gateway, 'public function getUpayPaymentMethods'), 'payment-method discovery responsibility remains characterized');
arch_assert(arch_contains($gateway, 'public function getSavedCards('), 'saved-card discovery responsibility remains characterized');
arch_assert(arch_contains($gateway, 'public function initializeSubscriptionModule'), 'subscription composition entry remains characterized');
arch_assert(arch_contains($gateway, 'public function generate_multimerchant_repeater_html'), 'multi-merchant admin responsibility remains characterized');
arch_assert(arch_contains($gateway, "add_action( 'woocommerce_process_product_meta', 'saveCustomFieldData' )"), 'subscription product-meta hook remains characterized');

arch_assert(is_file($root . '/src/Release/Identity.php'), 'Release module exists');
arch_assert(is_dir($root . '/src/Migration'), 'Migration module exists');
arch_assert(is_dir($root . '/src/Payment'), 'Payment module exists');
arch_assert(is_dir($root . '/src/Security'), 'Security module exists');
arch_assert(is_file($root . '/src/Payment/OrderLock.php'), 'Payment OrderLock boundary exists');
arch_assert(is_file($root . '/src/Payment/ProviderResult.php'), 'Payment ProviderResult boundary exists');
arch_assert(is_file($root . '/src/Payment/StatusRateGate.php'), 'Payment StatusRateGate boundary exists');
arch_assert(is_file($root . '/src/Payment/StatusVerifier.php'), 'Payment StatusVerifier boundary exists');
arch_assert(arch_contains($paymentLifecycle, 'namespace Simplix\\Pay\\UPayments\\Payment;'), 'Payment lifecycle uses Simplix namespace');
arch_assert(arch_contains($securityStatus, 'namespace Simplix\\Pay\\UPayments\\Security;'), 'Security boundary uses Simplix namespace');

arch_assert(is_file($root . '/includes/Token/CustomerTokenIdentity.php'), 'protected H12 token identity module exists');
arch_assert(arch_contains($tokenIdentity, 'CustomerTokenIdentity'), 'H12 token identity implementation remains readable');
arch_assert(is_file($root . '/includes/Subscription/Cron/Scheduler.php'), 'protected subscription scheduler exists');
arch_assert(is_file($root . '/includes/Subscription/Cron/CycleClaim.php'), 'protected subscription cycle-claim module exists');
arch_assert(arch_contains($scheduler, 'class Scheduler'), 'subscription Scheduler class remains characterized');
arch_assert(is_file($root . '/includes/class-wc-gateway-upayments-blocks.php'), 'Checkout Blocks gateway integration exists');

arch_assert(arch_matches($gateway, '~\$this->id\s*=\s*\'upayments\'\s*;~'), 'gateway ID remains bound to upayments');
arch_assert(arch_matches($gateway, '~add_action\s*\(\s*"woocommerce_api_"\s*\.\s*strtolower\s*\(\s*"WC_UPayments"\s*\)\s*,\s*\[\s*\$this\s*,\s*"check_ipn_response"\s*,?\s*\]\s*\)\s*;~'), 'wc_upayments callback hook remains bound to check_ipn_response');
arch_assert(arch_matches($gateway, '~\$settings\s*=\s*get_option\s*\(\s*"woocommerce_upayments_settings"\s*\)\s*;~'), 'legacy WooCommerce settings option remains a concrete runtime read');
arch_assert(arch_matches($gateway, '~\$order->add_meta_data\s*\(\s*"UPayments_order_id"\s*,\s*\$unique_order_id\s*\)\s*;~'), 'UPayments_order_id remains persisted from the local provider-order identity');

arch_assert(!is_dir($root . '/src/Provider'), 'discovery tranche has not prematurely created Provider runtime module');

printf("\nArchitecture Foundation: %d PASS / %d FAIL\n", $pass, $fail);
exit($fail === 0 ? 0 : 1);