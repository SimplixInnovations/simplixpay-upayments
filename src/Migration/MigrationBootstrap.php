<?php
namespace Simplixi\SUCheckout\UPayments\Migration;

defined('ABSPATH') || exit;

/** Register Phase 9I operational tools only in admin/CLI contexts. */
final class MigrationBootstrap {
    public static function boot() {
        $is_cli = defined('WP_CLI') && WP_CLI;
        $is_admin = function_exists('is_admin') && is_admin();

        self::bootForContext($is_cli, $is_admin);
    }

    private static function bootForContext($is_cli, $is_admin) {
        if (!$is_cli && !$is_admin) {
            return;
        }

        require_once __DIR__ . '/MigrationPreflight.php';
        require_once __DIR__ . '/MigrationExecutor.php';
        require_once __DIR__ . '/MigrationSettings.php';
        require_once __DIR__ . '/MigrationBatch.php';

        if ($is_admin) {
            require_once __DIR__ . '/MigrationAdmin.php';
            add_action('admin_menu', array(MigrationAdmin::class, 'register'));
        }

        if ($is_cli && class_exists('WP_CLI')) {
            require_once __DIR__ . '/MigrationCliCommand.php';
            \WP_CLI::add_command('simplixpay-upayments migration', MigrationCliCommand::class);
        }
    }

    private function __construct() {
    }
}

MigrationBootstrap::boot();
