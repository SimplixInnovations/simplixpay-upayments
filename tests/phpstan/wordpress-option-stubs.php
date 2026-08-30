<?php

/**
 * Development-only WordPress option/database symbols for bounded PHPStan scope.
 */

class wpdb {
    /** @var string */
    public $options;

    /** @var string */
    public $prefix;

    /** @return string */
    public function prepare($query, ...$args) {}

    /** @return int|bool */
    public function query($query) {}

    /**
     * @param string $query
     * @return mixed
     */
    public function get_var($query) {}
}

/** @var wpdb $wpdb */
$wpdb = new wpdb();

/** @return string */
function wp_salt($scheme = 'auth') {}

/** @return bool */
function add_option($name, $value = '', $deprecated = '', $autoload = 'yes') {}

/** @return mixed */
function get_option($name, $default = false) {}

/** @return bool */
function update_option($name, $value, $autoload = null) {}

/** @return bool */
function delete_option($name) {}

/** @return bool */
function wp_cache_delete($key, $group = '') {}
function wp_remote_get($url, $args = array()) {}
function is_wp_error($value) {}
function wp_remote_retrieve_response_code($response) {}
function wp_remote_retrieve_body($response) {}

/**
 * @param string $transient
 * @return mixed
 */
function get_transient($transient) {}

/**
 * @param string $transient
 * @param mixed $value
 * @param int $expiration
 * @return bool
 */
function set_transient($transient, $value, $expiration = 0) {}

/** @return int */
function get_current_blog_id() {}
