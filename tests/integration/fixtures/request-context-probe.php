<?php
/**
 * HTTP request-context probe used only by Compatibility Certification.
 *
 * This file is copied into wp-content/mu-plugins by CI. It deliberately
 * exercises WooCommerce's public payment-gateway registry from real frontend,
 * wc-ajax, admin-ajax and REST requests without adding production hooks.
 */

defined('ABSPATH') || exit;

/**
 * Return the observable gateway/context contract for the current HTTP request.
 *
 * @return array<string, mixed>
 */
function supcheckout_cert_http_context_payload() {
    $wc = function_exists('WC') ? WC() : null;
    $force_sessionless = isset($_REQUEST['sessionless']) && '1' === (string) $_REQUEST['sessionless']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only CI fixture.

    if ($force_sessionless && $wc) {
        $wc->session = null;
    }

    $available = array();
    if ($wc && $wc->payment_gateways()) {
        $available = $wc->payment_gateways()->get_available_payment_gateways();
    }

    return array(
        'gateway_present' => isset($available['upayments']),
        'cod_present'     => isset($available['cod']),
        'context'         => array(
            'is_admin'       => is_admin(),
            'doing_ajax'     => wp_doing_ajax(),
            'wc_doing_ajax'  => defined('WC_DOING_AJAX') && WC_DOING_AJAX,
            'rest_request'   => defined('REST_REQUEST') && REST_REQUEST,
            'session_present'=> (bool) ($wc && $wc->session),
        ),
    );
}

/**
 * Emit a JSON probe response and terminate the request.
 *
 * @return void
 */
function supcheckout_cert_http_context_send_json() {
    wp_send_json(supcheckout_cert_http_context_payload());
}

add_action('wp_ajax_supcheckout_context_probe', 'supcheckout_cert_http_context_send_json');
add_action('wp_ajax_nopriv_supcheckout_context_probe', 'supcheckout_cert_http_context_send_json');
add_action('wc_ajax_supcheckout_context_probe', 'supcheckout_cert_http_context_send_json');

add_action(
    'rest_api_init',
    static function () {
        register_rest_route(
            'supcheckout-cert/v1',
            '/context',
            array(
                'methods'             => 'GET',
                'callback'            => static function () {
                    return rest_ensure_response(supcheckout_cert_http_context_payload());
                },
                'permission_callback' => '__return_true',
            )
        );
    }
);

add_action(
    'template_redirect',
    static function () {
        // Read-only CI fixture: no state change and no privileged action.
        if (!isset($_GET['supcheckout_context_probe']) || '1' !== (string) $_GET['supcheckout_context_probe']) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            return;
        }

        supcheckout_cert_http_context_send_json();
    }
);
