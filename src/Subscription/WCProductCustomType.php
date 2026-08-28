<?php

// Global WooCommerce compatibility identity retained from the legacy module.
if (!class_exists('WCProductCustomType') && class_exists('WC_Product_Simple')) {
    class WCProductCustomType extends WC_Product_Simple {
        public function get_type() {
            return 'custom_type';
        }
    }
}
