<?php

/**
 * Development-only WordPress/WooCommerce runtime symbols for Q17 static analysis.
 */

/** @return string */
function wp_generate_uuid4() { return ''; }

/** @return string */
function site_url($path = '', $scheme = null) { return ''; }

/** @return string */
function wc_get_checkout_url() { return ''; }

/** @return mixed */
function wp_parse_url($url, $component = -1) {}

/** @return mixed */
function add_action($hook_name, $callback, $priority = 10, $accepted_args = 1) {}

/** @return mixed */
function add_filter($hook_name, $callback, $priority = 10, $accepted_args = 1) {}

/** @return mixed */
function remove_filter($hook_name, $callback, $priority = 10) {}

/** @return mixed */
function wp_next_scheduled($hook, $args = array()) {}

/** @return mixed */
function wp_schedule_single_event($timestamp, $hook, $args = array(), $wp_error = false) {}

/** @return mixed */
function wp_unschedule_event($timestamp, $hook, $args = array(), $wp_error = false) {}

/** @return mixed */
function wp_safe_redirect($location, $status = 302, $x_redirect_by = 'WordPress') {}

/** @return string */
function wc_get_page_permalink($page) { return ''; }

/** @return string */
function home_url($path = '', $scheme = null) { return ''; }

/** @return mixed */
function wc_get_logger() {}
