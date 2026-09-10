<?php

/** Development-only WooCommerce availability symbols for bounded PHPStan scope. */
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

/** @return bool */
function is_admin() {}

/** @return bool */
function wp_doing_ajax() {}

/** @return bool */
function is_checkout() {}

/** @return SUPCheckoutPhpstanWooContainer|null */
function WC() {}
