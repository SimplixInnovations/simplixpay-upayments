<?php
/**
 * Real-runtime saved-card/token-identity certification.
 */

require_once __DIR__ . '/bootstrap.php';

use UPayments\Token\CustomerTokenIdentity;

sucheckout_cert_assert(class_exists(CustomerTokenIdentity::class), 'customer token identity boundary is loaded');

delete_option(CustomerTokenIdentity::SECRET_OPTION);

$create_calls = 0;
$guest = CustomerTokenIdentity::get_or_establish_token(
    0,
    'certification-api-key',
    true,
    function ($candidate) use (&$create_calls) {
        ++$create_calls;
        return null;
    }
);
sucheckout_cert_assert(false === $guest['success'], 'guest token establishment is rejected');
sucheckout_cert_assert('not_logged_in' === $guest['reason'], 'guest token rejection reason is exact');
sucheckout_cert_assert(0 === $create_calls, 'guest token rejection occurs before provider transport');
sucheckout_cert_assert(false === get_option(CustomerTokenIdentity::SECRET_OPTION, false), 'guest rejection does not initialize token identity');

$user_id = wp_insert_user(array(
    'user_login' => 'simplixpay-cert-card-' . wp_generate_password(12, false, false),
    'user_pass'  => wp_generate_password(24, true, true),
    'user_email' => 'saved-card-' . wp_generate_password(8, false, false) . '@example.invalid',
));
sucheckout_cert_assert(!is_wp_error($user_id) && (int) $user_id > 0, 'saved-card certification user is created');
$user_id = (int) $user_id;

$create_calls = 0;
$identity = CustomerTokenIdentity::get_or_establish_token(
    $user_id,
    'certification-api-key',
    true,
    function ($candidate) use (&$create_calls) {
        ++$create_calls;
        return array(
            'transport_ok' => true,
            'http_status'  => 201,
            'curl_errno'   => 0,
            'body'         => wp_json_encode(array(
                'status' => true,
                'data'   => array('customerUniqueToken' => $candidate),
            )),
        );
    }
);

sucheckout_cert_assert(true === $identity['success'], 'authenticated user can establish canonical token identity');
sucheckout_cert_assert(true === $identity['established'], 'canonical token is newly established in the real WordPress user store');
sucheckout_cert_assert(1 === $create_calls, 'canonical token creation performs one bounded provider-callback invocation');
sucheckout_cert_assert(
    is_string($identity['token']) && CustomerTokenIdentity::is_valid_canonical_token($identity['token']),
    'established customer token satisfies the canonical grammar'
);
sucheckout_cert_assert(
    CustomerTokenIdentity::KIND_CANONICAL === $identity['kind'],
    'established token provenance is canonical'
);

$customer_token = $identity['token'];
$card_token = 'card-certification-token-a';
$retrieve_calls = 0;
$cards = CustomerTokenIdentity::get_saved_cards_for_current_user(
    $user_id,
    'certification-api-key',
    true,
    function ($token) use (&$retrieve_calls, $customer_token, $card_token) {
        ++$retrieve_calls;
        sucheckout_cert_assert($customer_token === $token, 'saved-card retrieval uses the exact canonical customer token');
        return array(
            'result' => 'success',
            'data'   => array(array('token' => $card_token, 'last4' => '4242')),
        );
    }
);

sucheckout_cert_assert(is_array($cards) && 'success' === $cards['result'], 'saved cards load for valid current provenance');
sucheckout_cert_assert(1 === $retrieve_calls, 'valid saved-card retrieval performs exactly one callback');

$membership_calls = 0;
$membership_reader = function ($token) use (&$membership_calls, $customer_token, $card_token) {
    ++$membership_calls;
    sucheckout_cert_assert($customer_token === $token, 'card membership lookup is bound to the exact customer token');
    return array(
        'result' => 'success',
        'data'   => array(
            array('token' => $card_token),
            array('token' => 'card-certification-token-b'),
        ),
    );
};

sucheckout_cert_assert(
    CustomerTokenIdentity::verify_card_membership($card_token, $customer_token, $membership_reader),
    'exact selected-card membership is accepted'
);
sucheckout_cert_assert(
    !CustomerTokenIdentity::verify_card_membership('foreign-card-token', $customer_token, $membership_reader),
    'foreign selected-card token is rejected'
);
sucheckout_cert_assert(2 === $membership_calls, 'membership checks each use one fresh bounded retrieval');

$meta_key = CustomerTokenIdentity::get_user_meta_key((string) get_current_blog_id(), $identity['scope']);
sucheckout_cert_assert(is_string($meta_key) && '' !== $meta_key, 'canonical provenance meta key is derived for the active scope');
$original_record = get_user_meta($user_id, $meta_key, true);
sucheckout_cert_assert(is_array($original_record), 'canonical provenance is persisted as one structured user-meta record');

$invalid_record = $original_record;
$invalid_record['source'] = 'tampered-certification-source';
sucheckout_cert_assert(
    false !== update_user_meta($user_id, $meta_key, $invalid_record),
    'certification can inject malformed provenance for fail-closed runtime proof'
);

$blocked_calls = 0;
$blocked = CustomerTokenIdentity::get_saved_cards_for_current_user(
    $user_id,
    'certification-api-key',
    true,
    function ($token) use (&$blocked_calls) {
        ++$blocked_calls;
        return array('result' => 'success', 'data' => array());
    }
);
sucheckout_cert_assert(null === $blocked, 'invalid provenance fails closed before saved-card retrieval');
sucheckout_cert_assert(0 === $blocked_calls, 'invalid provenance never reaches provider-card retrieval');

update_user_meta($user_id, $meta_key, $original_record);
wp_delete_user($user_id);
delete_option(CustomerTokenIdentity::SECRET_OPTION);

sucheckout_cert_note('saved-card/tokenization runtime certification complete');
