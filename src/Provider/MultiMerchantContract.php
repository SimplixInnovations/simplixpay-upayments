<?php

namespace Simplixi\SUPCheckout\Provider;

/**
 * Pure UPayments multi-merchant lexical contract.
 *
 * This boundary owns only provider-facing value grammar shared by merchant
 * settings validation and checkout orchestration. It performs no transport,
 * persistence, WooCommerce lookup, sanitization, or value rewriting.
 */
final class MultiMerchantContract {
    private const MAX_COMMISSION_TOKEN_LENGTH = 22;

    /**
     * Validate the conservative UPayments IBAN boundary used by checkout.
     *
     * @param mixed $value Raw IBAN candidate.
     * @return bool
     */
    public static function is_valid_iban($value) {
        return is_string($value)
            && preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{11,30}\z/', $value) === 1;
    }

    /**
     * Validate a main-merchant commission token.
     *
     * Zero is provider-permitted. Signs, exponent notation, whitespace,
     * commas, leading-zero ambiguity and values over the existing 22-byte
     * provider token ceiling fail closed.
     *
     * @param mixed $value Raw commission candidate.
     * @return bool
     */
    public static function is_valid_commission($value) {
        return is_string($value)
            && strlen($value) <= self::MAX_COMMISSION_TOKEN_LENGTH
            && preg_match('/^(?:0|[1-9][0-9]*)(?:\.[0-9]+)?$/', $value) === 1;
    }

    /**
     * Validate the exact provider charge-type allowlist.
     *
     * @param mixed $value Raw charge-type candidate.
     * @return bool
     */
    public static function is_valid_charge_type($value) {
        return is_string($value)
            && in_array($value, array('fixed', 'percentage'), true);
    }

    private function __construct() {
    }
}
