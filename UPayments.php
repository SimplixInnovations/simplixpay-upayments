<?php
/**
 * Plugin Name: SUPCheckout for UPayments
 * Plugin URI: https://github.com/SimplixInnovations/supcheckout
 * Description: Independently engineered UPayments payment integration for WooCommerce by Simplix Innovations.
 * Version: 0.1.0
 * Author: Simplix Innovations
 * Author URI: https://simplixi.com
 * Requires at least: 6.9
 * Tested up to: 7.1
 * Requires PHP: 7.4
 * WC requires at least: 10.8
 * WC tested up to: 11.1
 * License: MIT
 * Text Domain: supcheckout
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define("UP_PLUGIN_URL", plugin_dir_url(__FILE__));
define("UP_PLUGIN_PATH", plugin_dir_path(__FILE__));
define('UPAYMENTS_PLUGIN_FILE', __FILE__ );

require_once __DIR__ . '/src/Release/Identity.php';
require_once __DIR__ . '/src/Admin/GatewaySettings.php';
require_once __DIR__ . '/src/Provider/EndpointResolver.php';
require_once __DIR__ . '/src/Provider/PaymentMethodAvailability.php';
require_once __DIR__ . '/src/Payment/CheckoutPayload.php';
require_once __DIR__ . '/src/Payment/CheckoutOrchestrator.php';
require_once __DIR__ . '/src/Payment/SavedCardSelection.php';
require_once __DIR__ . '/src/Payment/SavedCardPresentation.php';
require_once __DIR__ . '/src/Subscription/Presentation.php';
require_once __DIR__ . '/src/Subscription/Composition.php';
require_once __DIR__ . '/includes/Token/CustomerTokenIdentity.php';
require_once __DIR__ . '/src/Migration/MigrationBootstrap.php';

use Simplixi\SUPCheckout\Release\Identity;
use Simplixi\SUPCheckout\Admin\GatewaySettings;
use Simplixi\SUPCheckout\Provider\EndpointResolver;
use Simplixi\SUPCheckout\Provider\PaymentMethodAvailability;
use Simplixi\SUPCheckout\Payment\CheckoutPayload;
use Simplixi\SUPCheckout\Payment\CheckoutOrchestrator;
use Simplixi\SUPCheckout\Subscription\Composition as SubscriptionComposition;
use Simplixi\SUPCheckout\Subscription\Presentation as SubscriptionPresentation;
use UPayments\Subscription\Cron\Scheduler;
use UPayments\Token\CustomerTokenIdentity;

define('SUPCHECKOUT_VERSION', Identity::VERSION);
define('SUPCHECKOUT_SLUG', Identity::SLUG);
define('SUPCHECKOUT_PLUGIN_FILE', __FILE__);
define('SUPCHECKOUT_UPDATE_CHANNEL', Identity::UPDATE_CHANNEL);

add_action( 'plugins_loaded', 'woocommerceUpaymentsInit' );
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- Legacy WooCommerce bootstrap callback retained for upgrade/runtime compatibility.
function woocommerceUpaymentsInit() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', 'upaymentsMissingWcNotice' );
        return;
    }
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound -- Protected legacy WooCommerce gateway class identity retained for compatibility.
    class WC_Upayments extends WC_Payment_Gateway {
        public $domain = 'upayments';
        public $debug;
        public $apiKey;
        public $testMode;
        public $isOrderComplete;
        public $fromPluginEnabled;
        public $paymentData;

        public $multiMerchant;
        public $ibanNumber;
        public $knetCharge;
        public $knetChargeType;
        public $ccCharge;
        public $ccChargeType;
        public $saveCardEnabled;
        public $charge;
        public $autoDeduction;

        /** Checkout payload compatibility delegates. */
        private static function field_present($source, $key) {
            return CheckoutPayload::field_present($source, $key);
        }

        private static function parse_save_card_strict($value) {
            return CheckoutPayload::parse_save_card_strict($value);
        }

        private static function parse_payment_source_strict($value) {
            return CheckoutPayload::parse_payment_source_strict($value);
        }

        private static function compare_nonnegative_decimal_strings($a, $b) {
            return CheckoutPayload::compare_nonnegative_decimal_strings($a, $b);
        }

        private static function build_amount_json_token($amount_str) {
            return CheckoutPayload::build_amount_json_token($amount_str);
        }

        private static function inject_amount_token_into_payload_json($payload_json, array $token_map, array $extra_sentinels = array()) {
            return CheckoutPayload::inject_amount_token_into_payload_json($payload_json, $token_map, $extra_sentinels);
        }

        private static function get_max_length_for_sentinel($placeholder) {
            return CheckoutPayload::get_max_length_for_sentinel($placeholder);
        }

        private static function is_valid_subscription_plan(string $plan): bool {
            return CheckoutPayload::is_valid_subscription_plan($plan);
        }

        private static function parse_subscription_plan_strict($value) {
            return CheckoutPayload::parse_subscription_plan_strict($value);
        }

        private static function parse_interval($value): int {
            return CheckoutPayload::parse_interval($value);
        }

        private static function is_valid_subscription_interval(string $plan, int $interval): bool {
            return CheckoutPayload::is_valid_subscription_interval($plan, $interval);
        }

        private function normalize_upayments_redirect_url($value) {
            return CheckoutPayload::normalize_upayments_redirect_url($value);
        }

        private static function normalize_store_api_route($uri) {
            return CheckoutPayload::normalize_store_api_route($uri);
        }

        public static function classify_checkout_request_context($is_rest_request, $normalized_route, $method) {
            return CheckoutPayload::classify_checkout_request_context($is_rest_request, $normalized_route, $method);
        }

        /**
         * Single canonical inlet for raw request-body access.
         *
         * Kept protected so existing gateway subclasses can supply deterministic
         * Store API bodies without bypassing the checkout orchestrator.
         */
        protected function get_request_body_raw() {
            $raw = file_get_contents('php://input');
            return (is_string($raw)) ? $raw : '';
        }

        public static function validate_provider_positive_decimal($value, $field_name = '') {
            return CheckoutPayload::validate_provider_positive_decimal($value, $field_name);
        }

        public static function validate_provider_nonnegative_decimal($value, $field_name = '') {
            return CheckoutPayload::validate_provider_nonnegative_decimal($value, $field_name);
        }

        public static function compute_provider_unit_price_decimal($line_total, $qty) {
            return CheckoutPayload::compute_provider_unit_price_decimal($line_total, $qty);
        }

        private static function digit_long_divide($numer_str, $denom) {
            return CheckoutPayload::digit_long_divide($numer_str, $denom);
        }

        private static function digit_long_divide_remainder($numer_str, $denom) {
            return CheckoutPayload::digit_long_divide_remainder($numer_str, $denom);
        }

        public static function canonicalize_provider_decimal_string($value) {
            return CheckoutPayload::canonicalize_provider_decimal_string($value);
        }

        private static function is_store_api_checkout_request() {
            return CheckoutPayload::is_store_api_checkout_request();
        }

        /**
         * Legacy private compatibility seam for the H12 cache validator.
         *
         * @param mixed $cached Cached availability value.
         * @return string|bool
         */
        private function is_valid_cached_availability($cached) {
            return PaymentMethodAvailability::classify_cached($cached);
        }

        public function __construct() {
            // Define ID, title, description, and settings.
            $this->id                 = 'upayments';
            $this->icon = UP_PLUGIN_URL . "assets/images/upayment.png";
            $this->method_title       = __("UPayments", 'supcheckout');
            $this->method_description = __("UPayments payment integration for WooCommerce. Available payment methods depend on your UPayments account and provider configuration.
            Supports Classic and Block Checkout. Subscription auto-deduction requires separately validated provider setup.", 'supcheckout');
            $this->has_fields         = true; // Required for custom forms like Save Card/Design variations.

            // Define user set variables
            $this->title = '';
            $this->description = $this->get_option("description");
            $this->debug = $this->get_option("debug");
            $this->apiKey = $this->get_option("api_key");
            $this->isOrderComplete = $this->get_option('is_order_complete');
            $this->testMode = $this->get_option("test_mode");
            $this->charge = $this->get_option('charge');
            $this->fromPluginEnabled = false;
            $this->paymentData = array();

            //MultimerchantData
            $this->multiMerchant = $this->get_option("enable_multimerchant");
            $this->ibanNumber = $this->get_option("iban_number");
            $this->ccCharge = $this->get_option("cc_charge");
            $this->ccChargeType = $this->get_option("cc_charge_type");
            $this->knetCharge = $this->get_option("knet_charge");
            $this->knetChargeType = $this->get_option("knet_charge_type");
            $this->saveCardEnabled = $this->get_option("enable_save_card");
            $this->autoDeduction = $this->get_option("enable_subscriptions");

            // Load settings and hooks
            $this->init_form_fields();
            $this->init_settings();

            // Register action hook for saving settings (critical for all new toggles)
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
            
            // Custom hooks for front-end rendering, scripts, etc.
            add_filter("woocommerce_get_order_item_totals", [$this, "add_order_item_totals"], 10, 3);
            add_action("woocommerce_api_" . strtolower("WC_UPayments") , [$this, "check_ipn_response", ]);
            add_filter("woocommerce_gateway_icon", [$this, "custom_payment_gateway_icons"], 10, 2);
            add_action("woocommerce_admin_order_data_after_order_details", [$this, "admin_order_details"], 10, 3);
            add_action("admin_enqueue_scripts", [$this, "admin_enqueue_scripts"]);
            add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
            
            // Handlers to Display Thankyou Page after successful payment
            add_action("woocommerce_thankyou_" . $this->id, function ($order_id) {
                $this->thankyou_page($order_id);
            });
            
            // Handlers for Subscription Module
            $this->initializeSubscriptionModule();
            
            // My Account link for Login users to view their orders and saved cards
            add_action('woocommerce_before_checkout_form', function () {

                if (!function_exists('WC') || !WC()->session) {
                    return;
                }

                $account_url = wc_get_page_permalink('myaccount');

                echo '<div class="checkout-my-account-link">';
                echo '<a href="' . esc_url($account_url) . '" target="_blank">';
                esc_html_e('Go to My Account', 'supcheckout');
                echo '</a>';
                echo '</div>';
                
                $gateways = WC()->payment_gateways()->get_available_payment_gateways();
                
                if (!isset($gateways['upayments'])) {
                    return;
                }
                
                $upay = $gateways['upayments'];
                
                if (WC()->session->get('chosen_payment_method') === 'upayments' && $upay->get_option('make_default_gateway') === 'no') {
                    WC()->session->set('chosen_payment_method', null);
                }
            }, 5);
            // Save Card & Subscriptions validation
            add_filter('woocommerce_settings_api_sanitized_fields_upayments', function ($settings) {
                $normalized = GatewaySettings::normalize_dependencies($settings);
                if ($normalized['forced_save_card']) {
                    wc_add_notice(
                        __('Save Card must be enabled when Subscriptions are enabled.', 'supcheckout'),
                        'error'
                    );
                }

                return $normalized['settings'];
            });

            add_filter('woocommerce_default_gateway', function ($default) {
                if ($this->get_option('make_default_gateway') === 'yes') {
                    return 'upayments';
                }

                return $default;
            });

            SubscriptionComposition::register_gateway_hooks($this);
        }

        public function init_form_fields() {
            $this->form_fields = GatewaySettings::fields(
                $this->domain,
                $this->method_title,
                $this->method_description
            );
        }

        public function get_logged_in_user_phone_number() {
            
            // Check if the user is logged in
            if (is_user_logged_in()) {
                // Get the current user ID
                $user_id = get_current_user_id();
                $billing_phone = get_user_meta($user_id, 'billing_phone', true);
                
                if ($billing_phone) {
                    $phone = str_replace(' ', '', $billing_phone); // Replaces all spaces with hyphens.
                    $phone = preg_replace('/[^A-Za-z0-9\-]/','',$phone);
                    if (substr($phone, 0, 1) === '0') {
                        $phone = '1' . substr($phone, 1);
                    }
                    if($phone) {
                        return ['success' => true, 'phone' => $phone];
                    }
                }
                return ['success' => true, 'phone' => ''];
            }
            if (function_exists('WC') && WC()->customer) {
                $billing_phone = WC()->customer->get_billing_phone();
                
                if (!empty($billing_phone)) {
                    $phone = str_replace(' ', '', $billing_phone); // Replaces all spaces with hyphens.
                    $phone = preg_replace('/[^A-Za-z0-9\-]/','',$phone);
                    if (substr($phone, 0, 1) === '0') {
                        $phone = '1' . substr($phone, 1);
                    }
                    return ['success' => true, 'phone' => $phone];
                }
            }
            return ['success' => false, 'phone' => ''];
        }

        public function add_order_item_totals($total_rows, $order, $tax_display)
        {
            $payment_status = $order->get_meta('UPayments_Result');
            $upayment_id = $order->get_meta('UPayments_PaymentID');

            $new_total_rows = [];

            foreach ($total_rows as $key => $total)
            {
                $new_total_rows[$key] = $total;
                if ("payment_method" === $key)
                {
                    $new_total_rows["payment_status"] = ["label" => "Payment Status:", "value" => $payment_status, ];
                    if (!empty($upayment_id))
                    {
                        $new_total_rows["upayment_id"] = ["label" => "UPayment ID:", "value" => $upayment_id, ];
                    }
                }
            }

            return $new_total_rows;
        }

        /**
         * Output for the order received page.
         *
         * Display-only. No payment-state mutation is performed here.
         * The verified WooCommerce order status and authoritative UPayments
         * metadata are read from the order; no $_GET parameter is trusted to
         * alter payment state.
         */
        public function thankyou_page($order_id) {
            if (!$order_id) {
                return;
            }

            $order = wc_get_order($order_id);
            if (!$order instanceof WC_Order) {
                return;
            }

            $status = (string) $order->get_status();
            $state = 'pending';

            if (method_exists($order, 'is_paid') && $order->is_paid()) {
                $state = 'success';
                $message = __('Your payment is successful with UPayments.', 'supcheckout');
            } elseif ($status === 'failed') {
                $state = 'failed';
                $message = __('Your payment was not completed with UPayments.', 'supcheckout');
            } elseif ($status === 'cancelled') {
                $state = 'failed';
                $message = __('Your payment was cancelled.', 'supcheckout');
            } else {
                $message = __('Your payment is being verified. Please refresh this page shortly.', 'supcheckout');
            }

            echo '<div class="upayment-status">';
            echo '<div class="upayment-status-icon upayment-status-icon-' . esc_attr($state) . '" aria-hidden="true"></div>';
            echo '<h2>' . esc_html($message) . '</h2>';
            $payment_id = (string) $order->get_meta('UPayments_PaymentID', true);
            if ($payment_id !== '') {
                echo '<p>' . esc_html__('Payment ID:', 'supcheckout') . ' <strong>' . esc_html($payment_id) . '</strong></p>';
            }
            echo '</div>';
        }

        public function admin_order_details($order) {
            SubscriptionPresentation::render_admin_order_summary($order);
        }

        public function initializeSubscriptionModule() {
            // Implementation delegated to SubscriptionComposition boundary.
        }

        public function enqueue_scripts() {
            if ( is_checkout() ) {
                $plugin_url = plugin_dir_url(__FILE__);
                wp_enqueue_style('supcheckout-customer', $plugin_url . 'assets/css/customer.css', array(), SUPCHECKOUT_VERSION);

                if ($this->get_option('checkout_design') === 'new') {
                    wp_enqueue_style('supcheckout-checkout-new-style', $plugin_url . 'assets/css/new-design.css', array(), SUPCHECKOUT_VERSION);
                    wp_enqueue_script('supcheckout-checkout-new-script', $plugin_url . 'assets/js/new-upay.js', array('jquery'), SUPCHECKOUT_VERSION, true );
                }

                if ($this->autoDeduction === 'yes'
                    && \UPayments\Subscription\Helpers\Utils::cartHasCustomType()
                ) {
                    wp_enqueue_script('supcheckout-subscription-checkout', $plugin_url . 'assets/js/subscription-checkout.js', array('jquery'), SUPCHECKOUT_VERSION, true);
                    wp_localize_script('supcheckout-subscription-checkout', 'wcUser', array(
                        'isLoggedIn' => is_user_logged_in(),
                    ));
                }
            }            
        }

        public function admin_enqueue_scripts() {
            $screen = get_current_screen();
            $query = array();
            // phpcs:disable WordPress.Security.NonceVerification.Recommended -- These sanitized values select admin assets only and perform no state change.
            foreach (array('page', 'tab', 'section') as $query_key) {
                if (isset($_GET[$query_key]) && is_string($_GET[$query_key])) {
                    $query[$query_key] = sanitize_key(wp_unslash($_GET[$query_key]));
                }
            }
            // phpcs:enable WordPress.Security.NonceVerification.Recommended
            GatewaySettings::enqueue_assets($screen, $query, plugin_dir_url(__FILE__));
        }

        public function process_admin_options() {
            $prepared = GatewaySettings::prepare_post_data($_POST, $this->domain); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- WooCommerce Settings API verifies its own save nonce before invoking the gateway callback.
            if (!$prepared['success']) {
                WC_Admin_Settings::add_error($prepared['message']);
                return false;
            }

            $original_post = $_POST;
            $_POST = $prepared['post_data'];
            try {
                return parent::process_admin_options();
            } finally {
                $_POST = $original_post;
            }
        }

        public function getMode()
        {
            $mode = true;
            if ($this->testMode == 'no') {
                $mode = false;
            }
            return $mode;
        }
        
        public function getAPIUrl($apiRoute = "")
        {
            return (new EndpointResolver($this->getMode()))->resolve($apiRoute);
        }

        public function getAPIUrlForCreateToken()
        {
            return (new EndpointResolver($this->getMode()))->create_customer_token();
        }

        public function getAPIUrlForCheckPaymentButtonStatus() {
            return (new EndpointResolver($this->getMode()))->check_payment_button_status();
        }

        public function getAPIUrlForRetreiveCards() {
            return (new EndpointResolver($this->getMode()))->retrieve_customer_cards();
        }

        public function getUserAgent(){
            $userAgent = 'UpaymentsWoocommercePlugin/2.2.1';
            if ($this->getMode()) {
                $userAgent = 'SandboxUpaymentsWoocommercePlugin/2.2.1';
            }
            return $userAgent;
        }
        
        public function getCurrencyCode($code)
        {
            return $code;
        }

        public function encrypt($param)
        {
            return base64_encode($param);
        }

        public function decrypt($param)
        {
            return base64_decode($param);
        }

        public function getCustomerUniqueToken($phone)
        {
            return '';
        }

        public function getUpayPaymentMethods()
        {
            $availability = new PaymentMethodAvailability(
                $this->getMode(),
                $this->apiKey,
                function () {
                    return $this->execute_upayments_request('check-payment-button-status', 'GET');
                }
            );
            $result = $availability->fetch();

            if (is_array($result)
                && isset($result['result'])
                && $result['result'] === 'failure'
            ) {
                wc_clear_notices();
                wc_add_notice(__('Payment methods could not be loaded. Please try again.', 'supcheckout'), 'error');
                return array('result' => 'failure', 'redirect' => wc_get_checkout_url());
            }

            return $result;
        }

        public function getSavedCards($customer_token)
        {
            $api_key = $this->apiKey;
            if (empty($api_key) || !is_string($customer_token) || $customer_token === '') {
                return null;
            }

            if (!preg_match('/^[0-9]{8,18}$/', $customer_token)) {
                return null;
            }

            $params = wp_json_encode(array('customerUniqueToken' => $customer_token));
            $transport = $this->execute_upayments_request('retrieve-customer-cards', 'POST', $params);

            if (!is_array($transport)
                || !isset($transport['transport_ok'])
                || $transport['transport_ok'] !== true
                || !isset($transport['http_status'])
                || $transport['http_status'] !== 201
                || !isset($transport['curl_errno'])
                || $transport['curl_errno'] !== 0
                || !isset($transport['body'])
                || !is_string($transport['body'])
                || $transport['body'] === ''
            ) {
                return null;
            }

            $decoded = json_decode($transport['body'], true);
            if (!is_array($decoded)
                || !array_key_exists('status', $decoded)
                || $decoded['status'] !== true
                || !isset($decoded['data'])
                || !is_array($decoded['data'])
                || !isset($decoded['data']['customerCards'])
                || !is_array($decoded['data']['customerCards'])
            ) {
                return null;
            }

            return array(
                'result' => 'success',
                'data' => $decoded['data']['customerCards'],
            );
        }

        public function getSavedCardsForCurrentUser($payment_data)
        {
            $user_id = get_current_user_id();
            if ($user_id <= 0 || $this->saveCardEnabled !== 'yes' || !is_array($payment_data)) {
                return null;
            }

            if (!array_key_exists('whitelabled', $payment_data)
                || $payment_data['whitelabled'] !== true
                || !array_key_exists('payment', $payment_data)
                || !is_array($payment_data['payment'])
                || !array_key_exists('cc', $payment_data['payment'])
                || !is_string($payment_data['payment']['cc'])
                || $payment_data['payment']['cc'] === ''
            ) {
                return null;
            }

            $gateway = $this;
            return CustomerTokenIdentity::get_saved_cards_for_current_user(
                $user_id,
                $this->apiKey,
                $this->getMode(),
                function($token) use ($gateway) {
                    return $gateway->getSavedCards($token);
                }
            );
        }

        public function getPaymentIcons()
        {
            $data = $this->getUpayPaymentMethods();
            if (!is_array($data)
                || !isset($data['result'])
                || $data['result'] !== 'success'
                || !array_key_exists('whitelabled', $data)
                || !is_bool($data['whitelabled'])
                || !isset($data['payment'])
                || !is_array($data['payment'])
            ) {
                return null;
            }

            return $data;
        }

        public function payment_fields() {
            if ($this->get_option('checkout_design') === 'new') {
                $gateway = $this;
                $save_card_enabled = $this->saveCardEnabled === 'yes';
                include __DIR__ . '/templates/new-design-form.php';
                return;
            }

            $gateway = $this;
            $save_card_enabled = $this->saveCardEnabled === 'yes';
            include __DIR__ . '/templates/old-design-form.php';
        }

        public function process_payment($order_id) {
            $gateway = $this;
            $orchestrator = new CheckoutOrchestrator(
                $gateway,
                function () {
                    return $this->get_request_body_raw();
                },
                function($apiRoute, $method, $body = null) {
                    return $this->execute_upayments_request($apiRoute, $method, $body);
                }
            );
            return $orchestrator->process($order_id);
        }

        public function execute_upayments_request($apiRoute, $method = 'GET', $body = null)
        {
            $api_key = $this->apiKey;
            if (!is_string($api_key) || $api_key === '') {
                return null;
            }

            $method = strtoupper((string) $method);
            if ($method !== 'GET' && $method !== 'POST') {
                return null;
            }

            $args = array(
                'method' => $method,
                'timeout' => 15,
                'redirection' => 0,
                'sslverify' => true,
                'limit_response_size' => 1048576,
                'user-agent' => $this->getUserAgent(),
                'headers' => array(
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $api_key,
                ),
            );

            if ($method === 'POST') {
                $args['body'] = $body;
            }

            $response = wp_remote_request($this->getAPIUrl($apiRoute), $args);
            if (is_wp_error($response)) {
                return array(
                    'transport_ok' => false,
                    'http_status' => 0,
                    'curl_errno' => 1,
                    'body' => null,
                );
            }

            $http_status = wp_remote_retrieve_response_code($response);
            $response_body = wp_remote_retrieve_body($response);
            if (!is_string($response_body) || strlen($response_body) >= 1048576) {
                return array(
                    'transport_ok' => false,
                    'http_status' => (int) $http_status,
                    'curl_errno' => 0,
                    'body' => null,
                );
            }

            return array(
                'transport_ok' => ((int) $http_status >= 200 && (int) $http_status < 300),
                'http_status' => (int) $http_status,
                'curl_errno' => 0,
                'body' => $response_body,
            );
        }

        public function check_ipn_response() {
            \Simplixi\SUPCheckout\Payment\PaymentLifecycle::handle_provider_callback();
        }

        public function get_payment_status_by_order_id() {
            \Simplixi\SUPCheckout\Security\PublicOrderStatus::handle();
        }

        public function custom_payment_gateway_icons($icon, $gateway_id) {
            if ($gateway_id !== $this->id) {
                return $icon;
            }
            return '<img src="' . esc_url(UP_PLUGIN_URL . 'assets/images/upayment.png') . '" alt="' . esc_attr__('UPayments', 'supcheckout') . '" />';
        }

        public function extraMerchantDataJson() {
            return GatewaySettings::render_multi_merchant_json_field($this);
        }

        public function validate_multimerchant_account() {
            return true;
        }
    }
}

function enableUpaymentsGateway($methods) {
    $methods[] = 'WC_Upayments';
    return $methods;
}
add_filter('woocommerce_payment_gateways', 'enableUpaymentsGateway');

function upaymentsMissingWcNotice() {
    echo '<div class="error"><p>' . esc_html__('SUPCheckout for UPayments requires WooCommerce to be installed and active.', 'supcheckout') . '</p></div>';
}

\Simplixi\SUPCheckout\Payment\PaymentLifecycle::boot();
\Simplixi\SUPCheckout\Migration\MigrationBootstrap::boot();
