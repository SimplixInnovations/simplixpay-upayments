<?php

defined( 'ABSPATH' ) || exit;

// Global WooCommerce compatibility identity retained from the legacy module.
if (!class_exists('WCProductCustomType') && class_exists('WC_Product_Simple')) {
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound -- Protected WooCommerce product-type compatibility class retained for existing hooks/data.
    class WCProductCustomType extends WC_Product_Simple {
        public function get_type() {
            return 'custom_type';
        }
    }
}
