<?php

/**
 * Development-only WordPress option/database symbols for bounded PHPStan scope.
 */

class wpdb {
    /** @var string */
    public $options;

    public function prepare($query, ...$args) {}
    public function query($query) {}
}

/** @var wpdb $wpdb */
$wpdb = new wpdb();

function wp_salt($scheme = 'auth') {}
function add_option($name, $value = '', $deprecated = '', $autoload = 'yes') {}
function get_option($name, $default = false) {}
function update_option($name, $value, $autoload = null) {}
function delete_option($name) {}
function wp_cache_delete($key, $group = '') {}
