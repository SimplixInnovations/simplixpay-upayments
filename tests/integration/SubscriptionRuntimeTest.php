<?php
/**
 * Real-runtime subscription eligibility and pre-dispatch certification.
 */

require_once __DIR__ . '/bootstrap.php';

use Simplix\Pay\UPayments\Payment\CheckoutOrchestrator;
use Simplix\Pay\UPayments\Subscription\Presentation;
use UPayments\Token\CustomerTokenIdentity;

simplixpay_cert_assert(class_exists(CheckoutOrchestrator::class), 'checkout orchestrator is loaded for subscription certification');
Presentation::register_product_class();
simplixpay_cert_assert(class_exists('WCProductCustomType'), 'subscription product class is available in real WooCommerce');

if (!WC()->session) {
    WC()->session = new WC_Session_Handler();
    WC()->session->init();
}

function simplixpay_cert_subscription_product($name, $restricted) {
    $product = new WCProductCustomType();
    $product->set_name($name);
    $product->set_regular_price('10.00');
    $product->set_price('10.00');
    $product_id = $product->save();
    simplixpay_cert_assert(is_int($product_id) && $product_id > 0, 'subscription certification product persists: ' . $name);
    if ($restricted) {
        update_post_meta($product_id, '_upay_disable_subscription', 'yes');
    }
    return $product;
}

function simplixpay_cert_normal_product($name) {
    $product = new WC_Product_Simple();
    $product->set_name($name);
    $product->set_regular_price('5.00');
    $product->set_price('5.00');
    $product_id = $product->save();
    simplixpay_cert_assert(is_int($product_id) && $product_id > 0, 'normal certification product persists: ' . $name);
    return $product;
}

function simplixpay_cert_subscription_order($products, $user_id) {
    $order = wc_create_order(array('customer_id' => $user_id));
    simplixpay_cert_assert($order instanceof WC_Order, 'subscription certification order is created');
    foreach ($products as $product) {
        $order->add_product($product, 1);
    }
    $order->set_payment_method('upayments');
    $order->set_billing_first_name('Certification');
    $order->set_billing_last_name('User');
    $order->set_billing_email('subscription@example.invalid');
    $order->set_billing_phone('50000000');
    $order->set_billing_country('KW');
    $order->set_currency('KWD');
    $order->calculate_totals();
    $order->save();
    return $order;
}

function simplixpay_cert_subscription_gateway() {
    $gateway = new WC_Upayments();
    $gateway->domain = 'upayments';
    $gateway->apiKey = 'certification-api-key';
    $gateway->testMode = 'yes';
    $gateway->autoDeduction = 'yes';
    $gateway->saveCardEnabled = 'yes';
    $gateway->multiMerchant = 'no';
    $gateway->paymentData = array(
        'whitelabled' => true,
        'payment' => array('cc' => 'Credit Card'),
    );
    return $gateway;
}

function simplixpay_cert_run_subscription_case($order, $post, $user_id, &$routes) {
    wp_set_current_user($user_id);
    wc_clear_notices();
    $_POST = $post;
    $gateway = simplixpay_cert_subscription_gateway();
    $routes = array();

    $orchestrator = new CheckoutOrchestrator(
        $gateway,
        function () {
            return '';
        },
        function ($route, $method, $body = null) use (&$routes) {
            $routes[] = $route;
            return array(
                'transport_ok' => false,
                'http_status' => 0,
                'curl_errno' => 7,
                'body' => '',
            );
        }
    );

    return $orchestrator->process($order->get_id());
}

delete_option(CustomerTokenIdentity::SECRET_OPTION);

$user_id = wp_insert_user(array(
    'user_login' => 'simplixpay-cert-sub-' . wp_generate_password(12, false, false),
    'user_pass'  => wp_generate_password(24, true, true),
    'user_email' => 'subscription-' . wp_generate_password(8, false, false) . '@example.invalid',
));
simplixpay_cert_assert(!is_wp_error($user_id) && (int) $user_id > 0, 'subscription certification user is created');
$user_id = (int) $user_id;

$subscription = simplixpay_cert_subscription_product('Subscription Product', false);
$restricted = simplixpay_cert_subscription_product('Restricted Subscription Product', true);
$normal = simplixpay_cert_normal_product('Normal Product');

$base_post = array(
    'payment_method' => 'upayments',
    'upayment_payment_type' => 'cc',
    'save_card' => '1',
    'upay_subscription_plan' => 'monthly',
    'upay_subscription_interval' => '1',
);

$restricted_order = simplixpay_cert_subscription_order(array($restricted), $user_id);
$routes = array();
$result = simplixpay_cert_run_subscription_case($restricted_order, $base_post, $user_id, $routes);
simplixpay_cert_assert('failure' === $result['result'], 'product-level subscription opt-out rejects subscription checkout');
simplixpay_cert_assert(array() === $routes, 'product-level opt-out rejects before any provider transport');

$mixed_order = simplixpay_cert_subscription_order(array($subscription, $normal), $user_id);
$routes = array();
$result = simplixpay_cert_run_subscription_case($mixed_order, $base_post, $user_id, $routes);
simplixpay_cert_assert('failure' === $result['result'], 'mixed subscription/normal order is rejected');
simplixpay_cert_assert(array() === $routes, 'mixed-order rejection occurs before any provider transport');

$guest_order = simplixpay_cert_subscription_order(array($subscription), 0);
$routes = array();
$result = simplixpay_cert_run_subscription_case($guest_order, $base_post, 0, $routes);
simplixpay_cert_assert('failure' === $result['result'], 'guest subscription checkout is rejected');
simplixpay_cert_assert(array() === $routes, 'guest subscription rejection occurs before token or Charge transport');

$invalid_plan_post = $base_post;
$invalid_plan_post['upay_subscription_plan'] = 'monthly ';
$strict_order = simplixpay_cert_subscription_order(array($subscription), $user_id);
$routes = array();
$result = simplixpay_cert_run_subscription_case($strict_order, $invalid_plan_post, $user_id, $routes);
simplixpay_cert_assert('failure' === $result['result'], 'whitespace-mutated subscription plan is rejected');
simplixpay_cert_assert(array() === $routes, 'invalid subscription plan rejects before provider transport');

$invalid_interval_post = $base_post;
$invalid_interval_post['upay_subscription_interval'] = '4';
$routes = array();
$result = simplixpay_cert_run_subscription_case($strict_order, $invalid_interval_post, $user_id, $routes);
simplixpay_cert_assert('failure' === $result['result'], 'out-of-contract subscription interval is rejected');
simplixpay_cert_assert(array() === $routes, 'invalid subscription interval rejects before provider transport');

$bootstrap_history = CustomerTokenIdentity::inspect_bootstrap_history($user_id);
simplixpay_cert_assert(
    CustomerTokenIdentity::HISTORY_NONE === $bootstrap_history['classification'],
    'clean subscription customer is eligible for token bootstrap before the positive checkout probe; reason='
        . (isset($bootstrap_history['reason']) ? $bootstrap_history['reason'] : 'missing')
);

$routes = array();
$result = simplixpay_cert_run_subscription_case($strict_order, $base_post, $user_id, $routes);
simplixpay_cert_note('eligible subscription bounded route trace: ' . wp_json_encode($routes));
simplixpay_cert_note('eligible subscription notices: ' . wp_json_encode(wc_get_notices()));
simplixpay_cert_assert('failure' === $result['result'], 'eligible subscription remains failed when bounded token transport is deliberately unavailable');
simplixpay_cert_assert(
    array('create-customer-unique-token') === $routes,
    'eligible Classic subscription reaches token initialization only after all local preflight gates pass; actual=' . wp_json_encode($routes)
);

wp_set_current_user(0);
foreach (array($restricted_order, $mixed_order, $guest_order, $strict_order) as $order) {
    $order->delete(true);
}
wp_delete_post($subscription->get_id(), true);
wp_delete_post($restricted->get_id(), true);
wp_delete_post($normal->get_id(), true);
wp_delete_user($user_id);
delete_option(CustomerTokenIdentity::SECRET_OPTION);
$_POST = array();
wc_clear_notices();

simplixpay_cert_note('subscription pre-dispatch runtime certification complete');
