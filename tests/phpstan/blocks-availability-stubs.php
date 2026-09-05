<?php

namespace Automattic\WooCommerce\Blocks\Payments\Integrations {
    abstract class AbstractPaymentMethodType {
        /** @var array<string,mixed> */
        protected $settings = array();
    }
}

namespace UPayments\Token {
    final class CustomerTokenIdentity {
        /** @return array<string,mixed>|null */
        public static function read_existing_identity_context($api_key, $is_test_mode) { return null; }
        /** @return array<string,mixed> */
        public static function read_provenance($user_id, $scope, $generation_id) { return array(); }
    }
}

namespace {
    final class WC_Upayments {
        /** @var string */
        public $apiKey = '';

        /** @return mixed */
        public function get_option($key) { return ''; }

        /** @return bool */
        public function getMode() { return false; }

        /** @return array<string,mixed>|null */
        public function getPaymentIcons() { return array(); }

        /** @return array<string,mixed>|null */
        public function getSavedCardsForCurrentUser($availability) { return array(); }
    }

    final class SimplixPay_Q18_Product {
        /** @return string */
        public function get_type() { return 'simple'; }
    }

    final class SimplixPay_Q18_Cart {
        /** @return array<string,array{data:SimplixPay_Q18_Product}> */
        public function get_cart() { return array(); }

        /** @return string */
        public function get_total($context = '') { return '0.00'; }
    }

    final class SimplixPay_Q18_WC {
        /** @var SimplixPay_Q18_Cart|null */
        public $cart;

        public function __construct() {
            $this->cart = new SimplixPay_Q18_Cart();
        }
    }

    /** @return mixed */
    function get_option($name, $default = false) { return $default; }

    /** @return mixed */
    function wp_register_script($handle, $src, $deps = array(), $ver = false, $in_footer = false) { return true; }

    /** @return string */
    function plugins_url($path = '', $plugin = '') { return ''; }

    /** @return int */
    function get_current_user_id() { return 0; }

    /** @return SimplixPay_Q18_WC */
    function WC() { return new SimplixPay_Q18_WC(); }

    /** @return string */
    function get_locale() { return 'en_US'; }

    /** @return string */
    function get_woocommerce_currency() { return 'KWD'; }

    /** @return string */
    function get_woocommerce_currency_symbol($currency = '') { return 'KD'; }

    /** @return string */
    function plugin_dir_url($file) { return ''; }

    /** @return string */
    function __($text, $domain = 'default') { return $text; }
}
