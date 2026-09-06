<?php
/**
 * Bounded UPayments public-sandbox Charge initialization certification.
 *
 * This script performs exactly one non-whitelabel Charge initialization with
 * UPayments' documented public sandbox token. It does not complete a payment,
 * follow the returned payment link, poll status, issue a refund, save a card,
 * or dispatch a recurring charge.
 */

declare(strict_types=1);

$root = dirname(__DIR__, 2);

require_once $root . '/src/Provider/EndpointResolver.php';
require_once $root . '/src/Payment/CheckoutPayload.php';

use Simplixi\SUCheckout\UPayments\Payment\CheckoutPayload;
use Simplixi\SUCheckout\UPayments\Provider\EndpointResolver;

function simplixpay_provider_assert(bool $condition, string $message): void {
    if ($condition) {
        echo "PASS: {$message}\n";
        return;
    }

    throw new RuntimeException("FAIL: {$message}");
}

function simplixpay_provider_env(string $name): string {
    $value = getenv($name);
    return is_string($value) ? $value : '';
}

$token = simplixpay_provider_env('SIMPLIXPAY_UPAYMENTS_SANDBOX_TOKEN');
simplixpay_provider_assert(
    hash_equals('jtest123', $token),
    'only the documented public non-whitelabel sandbox token is accepted'
);

$run_id = preg_replace('/[^0-9]/', '', simplixpay_provider_env('GITHUB_RUN_ID'));
$attempt = preg_replace('/[^0-9]/', '', simplixpay_provider_env('GITHUB_RUN_ATTEMPT'));

if (!is_string($run_id) || $run_id === '') {
    $run_id = '0';
}
if (!is_string($attempt) || $attempt === '') {
    $attempt = '1';
}

$order_id = substr('simplix-cert-' . $run_id . '-' . $attempt, 0, 40);
$reference_id = substr('simplix-' . $run_id . '-' . $attempt, 0, 35);

$resolver = new EndpointResolver(true);
$url = $resolver->resolve('charge');

simplixpay_provider_assert(
    $url === EndpointResolver::SANDBOX_BASE . 'charge',
    'Charge endpoint is derived from the Simplix sandbox resolver'
);

$endpoint_parts = parse_url($url);
simplixpay_provider_assert(
    is_array($endpoint_parts)
        && ($endpoint_parts['scheme'] ?? '') === 'https'
        && ($endpoint_parts['host'] ?? '') === 'sandboxapi.upayments.com'
        && ($endpoint_parts['path'] ?? '') === '/api/v1/charge',
    'sandbox Charge endpoint is exact HTTPS UPayments host/path'
);

$payload = array(
    'products' => array(
        array(
            'name'        => 'SimplixPay Certification',
            'description' => 'Bounded sandbox initialization only',
            'price'       => 1.0,
            'quantity'    => 1,
        ),
    ),
    'order' => array(
        'id'          => $order_id,
        'reference'   => $order_id,
        'description' => 'SimplixPay bounded sandbox initialization',
        'currency'    => 'KWD',
        'amount'      => 1.0,
    ),
    'language' => 'en',
    'tokens' => new stdClass(),
    'reference' => array(
        'id' => $reference_id,
    ),
    'returnUrl' => 'https://example.com/simplixpay-return',
    'cancelUrl' => 'https://example.com/simplixpay-cancel',
    'notificationUrl' => 'https://example.com/simplixpay-webhook',
    'plugin' => array(
        'src' => 'woocommerce',
    ),
    'paymentLinkExpiryInMinutes' => 1,
);

$json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION);
simplixpay_provider_assert(is_string($json) && $json !== '', 'sandbox request JSON encodes successfully');

$ch = curl_init();
simplixpay_provider_assert($ch !== false, 'cURL handle initializes');

curl_setopt_array(
    $ch,
    array(
        CURLOPT_URL            => $url,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $json,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_MAXREDIRS      => 0,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_USERAGENT      => 'SimplixPay-Enterprise-Certification/0.1.0',
        CURLOPT_HTTPHEADER     => array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ),
    )
);

$body = curl_exec($ch);
$curl_errno = curl_errno($ch);
$http_status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
$ch = null;

simplixpay_provider_assert($curl_errno === 0, 'sandbox Charge transport completes without cURL error');
simplixpay_provider_assert(is_string($body) && $body !== '', 'sandbox Charge returns a response body');
simplixpay_provider_assert($http_status === 201, 'sandbox Charge returns exact HTTP 201');

$decoded = json_decode($body, true);
simplixpay_provider_assert(is_array($decoded), 'sandbox Charge response is valid JSON object');
simplixpay_provider_assert(
    array_key_exists('status', $decoded) && $decoded['status'] === true,
    'sandbox Charge response has strict status=true'
);
simplixpay_provider_assert(
    isset($decoded['data']) && is_array($decoded['data']),
    'sandbox Charge response contains structured data'
);

$redirect = null;
if (isset($decoded['data']['link']) && is_string($decoded['data']['link'])) {
    $redirect = $decoded['data']['link'];
} elseif (
    isset($decoded['data']['transactionData'])
    && is_array($decoded['data']['transactionData'])
    && isset($decoded['data']['transactionData']['redirect_url'])
    && is_string($decoded['data']['transactionData']['redirect_url'])
) {
    $redirect = $decoded['data']['transactionData']['redirect_url'];
}

$normalized = CheckoutPayload::normalize_upayments_redirect_url($redirect);
simplixpay_provider_assert($normalized !== null, 'provider payment link passes the production redirect normalizer');

$redirect_parts = parse_url($normalized);
$redirect_host = is_array($redirect_parts) && isset($redirect_parts['host'])
    ? strtolower((string) $redirect_parts['host'])
    : '';
$redirect_scheme = is_array($redirect_parts) && isset($redirect_parts['scheme'])
    ? strtolower((string) $redirect_parts['scheme'])
    : '';

simplixpay_provider_assert($redirect_scheme === 'https', 'provider payment link uses HTTPS');
simplixpay_provider_assert(
    in_array($redirect_host, array('sandbox.upayments.com', 'sandboxapi.upayments.com'), true),
    'provider payment link stays on the bounded UPayments sandbox host allowlist'
);

echo "CERT: UPayments public-sandbox Charge initialization verified; no payment completion attempted.\n";
