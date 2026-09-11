<?php

namespace Simplixi\SUPCheckout\Payment;

function time() {
    return isset($GLOBALS['supcheckout_test_payment_runtime_time'])
        ? (int) $GLOBALS['supcheckout_test_payment_runtime_time']
        : \time();
}

function wp_generate_uuid4() {
    $next = isset($GLOBALS['supcheckout_test_payment_runtime_uuid_sequence'])
        ? (int) $GLOBALS['supcheckout_test_payment_runtime_uuid_sequence'] + 1
        : 1;
    $GLOBALS['supcheckout_test_payment_runtime_uuid_sequence'] = $next;

    return sprintf('00000000-0000-4000-8000-%012x', $next);
}
