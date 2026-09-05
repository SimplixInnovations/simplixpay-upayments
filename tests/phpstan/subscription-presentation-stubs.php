<?php

namespace UPayments\Subscription\Helpers {
    class Utils {
        public static function cartHasCustomType() { return false; }
        public static function cartHasNormalProduct() { return false; }
    }
}

namespace UPayments\Subscription\Cron {
    class Scheduler {
        /** @return \DateTime|null */
        public static function getNextBillingDate(?\DateTime $started_at, string $plan, int $interval): ?\DateTime { return null; }
    }
}

namespace {
    class WooCommerce {}

    class WC_Product {
        /** @return string */
        public function get_type() { return ''; }
        /** @return bool */
        public function is_type($type) { return false; }
        /** @return int */
        public function get_id() { return 0; }
    }

    class WC_Order {
        /** @return mixed */
        public function get_meta($key) {}
        /** @return int */
        public function get_user_id() { return 0; }
        /** @return int */
        public function get_id() { return 0; }
        /** @return array<int, mixed> */
        public function get_items($type = '') { return array(); }
        /** @return mixed */
        public function get_date_paid() {}
        /** @return mixed */
        public function get_date_completed() {}
        /** @return mixed */
        public function get_date_created() {}
    }

    class WC_Upayments {
        public function render_subscription_summary($order) {}
    }

    function absint($value) { return 0; }
    function wp_verify_nonce($nonce, $action) { return false; }
    function update_post_meta($post_id, $key, $value) { return false; }
    function get_post_meta($post_id, $key, $single = false) {}
    function get_post_type() { return ''; }
    function woocommerce_wp_text_input($args) {}
    /** @return object{cart:mixed} */
    function WC() { return (object) array('cart' => null); }
    /** @return WC_Product|false */
    function wc_get_product($product_id) { return false; }
    function wc_add_notice($message, $type) {}
    function wp_timezone() { return new DateTimeZone('UTC'); }
    function wc_get_account_endpoint_url($endpoint) { return ''; }
    function add_query_arg($key = null, $value = null) { return ''; }
    function esc_url($value) { return ''; }
    function esc_js($value) { return ''; }
}
