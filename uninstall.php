<?php

defined('WP_UNINSTALL_PLUGIN') || exit;

/**
 * SUCheckout for UPayments intentionally preserves merchant/payment data on
 * uninstall. Settings, historical compatibility options, subscription state,
 * payment/token identity, and legacy tables are not silently erased.
 *
 * A future explicit cleanup/erasure tool must define its own confirmation,
 * retention, migration, and rollback contract before destructive deletion is
 * permitted.
 */
