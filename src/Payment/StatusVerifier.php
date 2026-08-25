<?php

namespace Simplix\Pay\UPayments\Payment;

defined('ABSPATH') || exit;

/**
 * Bearer-authenticated Get Payment Status client + strict order binding.
 */
final class StatusVerifier {
    public static function verify($gateway, $order, $track_id) {
        $result = self::base_result('invalid_request');

        if (!is_object($gateway)
            || !is_object($order)
            || !method_exists($gateway, 'getAPIUrl')
            || !method_exists($gateway, 'getCurrencyCode')
            || !method_exists($order, 'get_id')
            || !method_exists($order, 'get_currency')
            || !method_exists($order, 'get_total')
            || !method_exists($order, 'get_meta')
        ) {
            return $result;
        }

        $track_id = self::normalize_track_id($track_id);
        if ($track_id === null) {
            return self::base_result('invalid_track_id');
        }

        if (!isset($gateway->apiKey) || !is_string($gateway->apiKey) || $gateway->apiKey === '') {
            return self::base_result('credentials_missing');
        }

        // Validate the exact provider destination before consuming a provider
        // rate-limit slot. This also prevents a compromised/extended gateway
        // object from turning the Bearer token into a cross-host credential leak.
        $url = $gateway->getAPIUrl('get-payment-status/' . rawurlencode($track_id));
        if (!self::is_allowed_status_url($url, $track_id)) {
            return self::base_result('status_url_invalid');
        }

        if (!StatusRateGate::acquire($gateway)) {
            return self::base_result('status_rate_limited');
        }

        $response = wp_remote_get($url, array(
            'timeout'     => 15,
            'redirection' => 0,
            'sslverify'   => true,
            'headers'     => array(
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $gateway->apiKey,
            ),
        ));

        if (is_wp_error($response)) {
            return self::base_result('network_error');
        }

        $http_status = (int) wp_remote_retrieve_response_code($response);
        if ($http_status !== 201) {
            return self::base_result('unexpected_http_' . $http_status);
        }

        $body = wp_remote_retrieve_body($response);
        if (!is_string($body) || $body === '') {
            return self::base_result('empty_response');
        }

        $decoded = json_decode($body, true);
        if (!is_array($decoded)
            || !array_key_exists('status', $decoded)
            || $decoded['status'] !== true
            || !isset($decoded['data'])
            || !is_array($decoded['data'])
            || !isset($decoded['data']['transaction'])
            || !is_array($decoded['data']['transaction'])
        ) {
            return self::base_result('invalid_status_response');
        }

        $bound = self::bind_transaction($gateway, $order, $track_id, $decoded['data']['transaction']);
        $bound['authenticated'] = true;
        return $bound;
    }

    /**
     * Pure binding/classification seam used by the executable harness.
     */
    public static function bind_transaction($gateway, $order, $track_id, $transaction) {
        $result = self::base_result('binding_invalid');
        $result['authenticated'] = true;

        if (!is_object($gateway)
            || !is_object($order)
            || !is_array($transaction)
            || !method_exists($gateway, 'getCurrencyCode')
            || !method_exists($order, 'get_id')
            || !method_exists($order, 'get_currency')
            || !method_exists($order, 'get_total')
            || !method_exists($order, 'get_meta')
        ) {
            return $result;
        }

        $track_id = self::normalize_track_id($track_id);
        if ($track_id === null) {
            $result['reason'] = 'binding_track_id_invalid';
            return $result;
        }

        // UPayments explicitly documents NULL/processing-style result states as
        // non-terminal. Result must therefore be present, but null is a valid
        // fail-closed INDETERMINATE value. All identity/binding fields remain
        // mandatory and non-empty.
        if (!array_key_exists('result', $transaction)) {
            $result['reason'] = 'missing_field_result';
            return $result;
        }
        if ($transaction['result'] !== null && !is_string($transaction['result'])) {
            $result['reason'] = 'result_not_string_or_null';
            return $result;
        }

        $required = array(
            'track_id',
            'merchant_requested_order_id',
            'total_price',
            'currency_type',
            'reference',
        );
        foreach ($required as $field) {
            if (!array_key_exists($field, $transaction)
                || !is_scalar($transaction[$field])
                || (string) $transaction[$field] === ''
            ) {
                $result['reason'] = 'missing_field_' . $field;
                return $result;
            }
        }

        if ((string) $transaction['track_id'] !== $track_id) {
            $result['reason'] = 'binding_track_id';
            return $result;
        }

        $local_upay_order_id = $order->get_meta('UPayments_order_id');
        if (!is_string($local_upay_order_id) || $local_upay_order_id === '') {
            $result['reason'] = 'missing_local_upay_order_id';
            return $result;
        }
        if ((string) $transaction['merchant_requested_order_id'] !== $local_upay_order_id) {
            $result['reason'] = 'binding_merchant_requested_order_id';
            return $result;
        }

        if ((string) $transaction['reference'] !== (string) $order->get_id()) {
            $result['reason'] = 'binding_reference';
            return $result;
        }

        $local_currency = $gateway->getCurrencyCode($order->get_currency());
        $expected_currency = is_scalar($local_currency) ? strtoupper(trim((string) $local_currency)) : '';
        $verified_currency = strtoupper(trim((string) $transaction['currency_type']));
        if ($expected_currency === '' || $verified_currency !== $expected_currency) {
            $result['reason'] = 'binding_currency';
            return $result;
        }

        $verified_amount = self::normalize_decimal($transaction['total_price']);
        $local_amount = self::normalize_decimal($order->get_total());
        if ($verified_amount === null || $local_amount === null) {
            $result['reason'] = 'amount_invalid';
            return $result;
        }

        $decimals = function_exists('wc_get_price_decimals') ? (int) wc_get_price_decimals() : 2;
        $expected_amount = wc_format_decimal($local_amount, $decimals);
        $normalized_amount = wc_format_decimal($verified_amount, $decimals);
        if (!is_string($expected_amount)
            || !is_string($normalized_amount)
            || $normalized_amount !== $expected_amount
        ) {
            $result['reason'] = 'binding_amount';
            return $result;
        }

        $classification = ProviderResult::classify($transaction['result']);
        if ($classification === ProviderResult::CAPTURED) {
            if (!array_key_exists('payment_id', $transaction)
                || !is_scalar($transaction['payment_id'])
                || (string) $transaction['payment_id'] === ''
            ) {
                $result['reason'] = 'captured_payment_id_missing';
                return $result;
            }
        }

        $result['bound'] = true;
        $result['classification'] = $classification;
        $result['transaction'] = $transaction;
        $result['reason'] = strtolower($classification);
        return $result;
    }

    private static function normalize_track_id($track_id) {
        if (!is_string($track_id) || $track_id === '' || strlen($track_id) > 255) {
            return null;
        }
        if (preg_match('/[\x00-\x20\x7F]/', $track_id)) {
            return null;
        }
        return $track_id;
    }

    private static function normalize_decimal($value) {
        if (!is_int($value) && !is_float($value) && !is_string($value)) {
            return null;
        }
        if (is_float($value) && !is_finite($value)) {
            return null;
        }
        $value = (string) $value;
        if ($value === '' || strlen($value) > 22) {
            return null;
        }
        if (!preg_match('/^(?:0|[1-9][0-9]*)(?:\.[0-9]+)?$/', $value)) {
            return null;
        }
        return $value;
    }

    private static function is_allowed_status_url($url, $track_id) {
        if (!is_string($url) || $url === '' || strlen($url) > 500 || !is_string($track_id)) {
            return false;
        }
        $parts = parse_url($url);
        if (!is_array($parts)
            || !isset($parts['scheme'], $parts['host'], $parts['path'])
            || strtolower((string) $parts['scheme']) !== 'https'
            || isset($parts['user'])
            || isset($parts['pass'])
            || isset($parts['port'])
            || isset($parts['query'])
            || isset($parts['fragment'])
        ) {
            return false;
        }
        $host = strtolower((string) $parts['host']);
        if ($host !== 'sandboxapi.upayments.com' && $host !== 'apiv2api.upayments.com') {
            return false;
        }
        $expected_path = '/api/v1/get-payment-status/' . rawurlencode($track_id);
        return (string) $parts['path'] === $expected_path;
    }

    private static function base_result($reason) {
        return array(
            'authenticated' => false,
            'bound' => false,
            'classification' => ProviderResult::INDETERMINATE,
            'transaction' => null,
            'reason' => (string) $reason,
        );
    }

    private function __construct() {}
}
