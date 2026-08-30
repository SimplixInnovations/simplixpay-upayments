<?php

/**
 * Deterministic WordPress transient/advisory-lock fixture for provider
 * payment-method availability tests. Development-only; excluded from builds.
 */

if (!class_exists('SimplixPay_Test_Availability_WPDB')) {
    final class SimplixPay_Test_Availability_WPDB {
        public $prefix = 'wp_7_';

        public function prepare($query) {
            $args = func_get_args();
            array_shift($args);

            return serialize(array(
                'query' => (string) $query,
                'args'  => $args,
            ));
        }

        public function get_var($prepared) {
            $statement = @unserialize($prepared);
            if (!is_array($statement)
                || !isset($statement['query'], $statement['args'])
                || !is_string($statement['query'])
                || !is_array($statement['args'])
                || count($statement['args']) !== 1
            ) {
                return null;
            }

            $lock_name = (string) $statement['args'][0];
            if (strpos($statement['query'], 'SELECT GET_LOCK(') === 0) {
                ++$GLOBALS['simplixpay_test_availability']['lock_acquires'];
                $callback = $GLOBALS['simplixpay_test_availability']['populate_on_lock'];
                if (is_callable($callback)) {
                    call_user_func($callback);
                    $GLOBALS['simplixpay_test_availability']['populate_on_lock'] = null;
                }
                $result = $GLOBALS['simplixpay_test_availability']['lock_result'];
                if ($result === '1' || $result === 1) {
                    $GLOBALS['simplixpay_test_availability']['locks'][$lock_name] = true;
                }
                return $result;
            }

            if (strpos($statement['query'], 'SELECT RELEASE_LOCK(') === 0) {
                ++$GLOBALS['simplixpay_test_availability']['lock_releases'];
                unset($GLOBALS['simplixpay_test_availability']['locks'][$lock_name]);
                return '1';
            }

            return null;
        }
    }
}

if (!function_exists('simplixpay_test_reset_availability')) {
    function simplixpay_test_reset_availability() {
        simplixpay_test_reset_wp_options();
        $GLOBALS['simplixpay_test_availability'] = array(
            'transients'      => array(),
            'transient_ttls'  => array(),
            'lock_result'     => '1',
            'locks'           => array(),
            'lock_acquires'   => 0,
            'lock_releases'   => 0,
            'populate_on_lock' => null,
        );
        $GLOBALS['wpdb'] = new SimplixPay_Test_Availability_WPDB();
    }
}

if (!function_exists('get_transient')) {
    function get_transient($name) {
        return array_key_exists($name, $GLOBALS['simplixpay_test_availability']['transients'])
            ? $GLOBALS['simplixpay_test_availability']['transients'][$name]
            : false;
    }
}

if (!function_exists('set_transient')) {
    function set_transient($name, $value, $expiration = 0) {
        $GLOBALS['simplixpay_test_availability']['transients'][$name] = $value;
        $GLOBALS['simplixpay_test_availability']['transient_ttls'][$name] = $expiration;
        return true;
    }
}

if (!function_exists('get_current_blog_id')) {
    function get_current_blog_id() {
        return 7;
    }
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'simplixpay_test_database');
}

simplixpay_test_reset_availability();
