<?php

/** Development-only WooCommerce availability and render-asset symbols for bounded PHPStan scope. */
class SUPCheckoutPhpstanSession {
    /** @return mixed */
    public function get($key) {}

    /** @return void */
    public function set($key, $value) {}
}

class SUPCheckoutPhpstanWooContainer {
    /** @var SUPCheckoutPhpstanSession|null */
    public $session;
}

class WC_Upayments {
    /** @var string */
    public $autoDeduction;
}

/** @return bool */
function is_admin() {}

/** @return bool */
function wp_doing_ajax() {}

/** @return bool */
function is_checkout() {}

/** @return SUPCheckoutPhpstanWooContainer|null */
function WC() {}

/** @return void */
function add_filter($hook_name, $callback, $priority = 10, $accepted_args = 1) {}

/** @return string */
function plugin_dir_url($file) {}

/** @return void */
function wp_enqueue_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all') {}

/** @return void */
function wp_enqueue_script($handle, $src = '', $deps = array(), $ver = false, $args = array()) {}

/** @return bool */
function wp_script_is($handle, $status = 'enqueued') {}

/** @return bool */
function wp_localize_script($handle, $object_name, $l10n) {}

/** @return bool */
function is_user_logged_in() {}
