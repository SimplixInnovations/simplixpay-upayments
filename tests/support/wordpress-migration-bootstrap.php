<?php

$GLOBALS['simplixpay_test_admin_context'] = false;
$GLOBALS['simplixpay_test_action_calls'] = array();
$GLOBALS['simplixpay_test_filter_calls'] = array();
$GLOBALS['simplixpay_test_hook_calls'] = array();

function simplixpay_test_reset_migration_bootstrap() {
    $GLOBALS['simplixpay_test_admin_context'] = false;
    $GLOBALS['simplixpay_test_action_calls'] = array();
    $GLOBALS['simplixpay_test_filter_calls'] = array();
    $GLOBALS['simplixpay_test_hook_calls'] = array();
    WP_CLI::$commands = array();
}

function simplixpay_test_reset_subscription_composition() {
    $GLOBALS['simplixpay_test_action_calls'] = array();
    $GLOBALS['simplixpay_test_filter_calls'] = array();
    $GLOBALS['simplixpay_test_hook_calls'] = array();
}

function is_admin() {
    return $GLOBALS['simplixpay_test_admin_context'] === true;
}

function add_action($hook_name, $callback, $priority = 10, $accepted_args = 1) {
    $GLOBALS['simplixpay_test_action_calls'][] = array($hook_name, $callback, $priority, $accepted_args);
    $GLOBALS['simplixpay_test_hook_calls'][] = array('action', $hook_name, $callback, $priority, $accepted_args);
    return true;
}

function add_filter($hook_name, $callback, $priority = 10, $accepted_args = 1) {
    $GLOBALS['simplixpay_test_filter_calls'][] = array($hook_name, $callback, $priority, $accepted_args);
    $GLOBALS['simplixpay_test_hook_calls'][] = array('filter', $hook_name, $callback, $priority, $accepted_args);
    return true;
}

final class WP_CLI {
    public static $commands = array();

    public static function add_command($name, $callable) {
        self::$commands[] = array($name, $callable);
        return true;
    }

    private function __construct() {
    }
}
