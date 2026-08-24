<?php

namespace Simplix\Pay\UPayments\Release;

defined('ABSPATH') || exit;

/**
 * Canonical product/release identity for the Simplix-maintained integration.
 *
 * Persisted payment identity is deliberately not defined here. Historical
 * gateway IDs, options, metadata, callbacks, cron hooks, tables, and H12
 * provenance remain compatibility contracts under the naming standard.
 */
final class Identity {
    public const PRODUCT_NAME = 'SimplixPay for UPayments';
    public const SHORT_NAME = 'SimplixPay UPayments';
    public const VERSION = '0.1.0';
    public const SLUG = 'simplixpay-upayments';
    public const REPOSITORY = 'SimplixInnovations/simplixpay-upayments';

    /** External self-updates stay disabled until package/basename migration is proven. */
    public const UPDATE_CHANNEL = 'disabled';

    /** Transitional install identities retained during Phase 0. */
    public const LEGACY_MAIN_FILE = 'UPayments.php';
    public const LEGACY_TEXT_DOMAIN = 'upayments';

    /** Frozen eventual targets; changing to them requires an upgrade migration. */
    public const TARGET_MAIN_FILE = 'simplixpay-upayments.php';
    public const TARGET_TEXT_DOMAIN = 'simplixpay-upayments';

    private function __construct() {
    }
}
