<?php

/**
 * Deterministic WordPress HTTP fixture for authenticated status-verifier tests.
 *
 * This file is development-only and is excluded from plugin distribution.
 */

if (!class_exists('SimplixPay_Test_WP_Error')) {
    final class SimplixPay_Test_WP_Error {}
}

if (!function_exists('simplixpay_test_reset_wp_http')) {
    function simplixpay_test_reset_wp_http() {
        $GLOBALS['simplixpay_test_http_calls'] = array();
        $GLOBALS['simplixpay_test_http_mutator'] = null;
        $GLOBALS['simplixpay_test_http_response'] = array(
            'response' => array('code' => 201),
            'body'     => '',
        );
    }
}

if (!function_exists('wp_remote_get')) {
    function wp_remote_get($url, $args = array()) {
        $GLOBALS['simplixpay_test_http_calls'][] = array(
            'url'  => $url,
            'args' => $args,
        );

        if (is_callable($GLOBALS['simplixpay_test_http_mutator'])) {
            call_user_func($GLOBALS['simplixpay_test_http_mutator']);
            $GLOBALS['simplixpay_test_http_mutator'] = null;
        }

        return $GLOBALS['simplixpay_test_http_response'];
    }
}

if (!function_exists('is_wp_error')) {
    function is_wp_error($value) {
        return $value instanceof SimplixPay_Test_WP_Error;
    }
}

if (!function_exists('wp_remote_retrieve_response_code')) {
    function wp_remote_retrieve_response_code($response) {
        return is_array($response)
            && isset($response['response'])
            && is_array($response['response'])
            && isset($response['response']['code'])
                ? (int) $response['response']['code']
                : 0;
    }
}

if (!function_exists('wp_remote_retrieve_body')) {
    function wp_remote_retrieve_body($response) {
        return is_array($response) && isset($response['body'])
            ? $response['body']
            : '';
    }
}

simplixpay_test_reset_wp_http();
