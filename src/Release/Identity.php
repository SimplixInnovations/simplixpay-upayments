<?php

namespace Simplixi\SUCheckout\UPayments\Release;

defined('ABSPATH') || exit;

/**
 * Canonical product/release identity for SUCheckout for UPayments.
 *
 * The class remains under the pre-migration PHP namespace for this first
 * identity tranche. NAMESPACE_ROOT records the approved destination; Task 2
 * migrates the first-party PSR-4 namespace under its own regression gate.
 *
 * Persisted payment/provider identities are explicit compatibility contracts,
 * not product branding. They must not be destroyed by a rebrand.
 */
final class Identity {
    public const PRODUCT_NAME = 'SUCheckout for UPayments';
    public const SHORT_NAME = 'SUCheckout';
    public const VERSION = '0.1.0';
    public const SLUG = 'sucheckout-upayments';
    public const REPOSITORY = 'SimplixInnovations/sucheckout-upayments';
    public const TEXT_DOMAIN = 'sucheckout-upayments';
    public const NAMESPACE_ROOT = 'Simplixi\\SUCheckout\\UPayments';

    /** External self-updates stay disabled until release authority is explicitly enabled. */
    public const UPDATE_CHANNEL = 'disabled';

    /** Historical install/runtime identities retained for compatibility. */
    public const LEGACY_MAIN_FILE = 'UPayments.php';
    public const LEGACY_TEXT_DOMAIN = 'upayments';
    public const LEGACY_GATEWAY_ID = 'upayments';
    public const LEGACY_SETTINGS_OPTION = 'woocommerce_upayments_settings';
    public const LEGACY_CALLBACK_ROUTE = 'wc_upayments';
    public const LEGACY_SUBSCRIPTION_HOOK = 'upay_process_subscriptions';
    public const LEGACY_TOKEN_SECRET_OPTION = 'upayments_token_identity_secret_v2';
    public const LEGACY_BILLING_ATTEMPT_TABLE_SUFFIX = 'upayments_billing_attempts';

    /** Canonical packaging/i18n targets; bootstrap migration is qualified separately. */
    public const TARGET_MAIN_FILE = 'sucheckout-upayments.php';
    public const TARGET_TEXT_DOMAIN = 'sucheckout-upayments';

    private function __construct() {
    }
}

// Incremental payment-lifecycle bootstrap. The identity harness loads this file
// in deliberate isolation, outside a WordPress hook environment.
if (function_exists('add_action')) {
    require_once dirname(__DIR__) . '/Payment/PaymentLifecycle.php';
    \Simplixi\SUCheckout\UPayments\Payment\PaymentLifecycle::bootstrap();
}
