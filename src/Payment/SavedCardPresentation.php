<?php

namespace Simplixi\SUPCheckout\Payment;

final class SavedCardPresentation {
    /**
     * Return only the final four display digits from a provider card shape.
     *
     * Explicit provider last4 is preferred. Provider number is accepted only as
     * a fallback source from which all non-digits and all but the final four
     * digits are discarded immediately.
     *
     * @param array<string, mixed> $card Provider saved-card shape.
     */
    public static function last_four(array $card): string {
        foreach (array('last4', 'number') as $key) {
            if (!array_key_exists($key, $card) || !is_scalar($card[$key])) {
                continue;
            }

            $digits = preg_replace('/\D+/', '', (string) $card[$key]);
            if (!is_string($digits) || strlen($digits) < 4) {
                continue;
            }

            return substr($digits, -4);
        }

        return '';
    }

    /**
     * Build a bounded saved-card display label containing at most last four.
     *
     * @param array<string, mixed> $card Provider saved-card shape.
     */
    public static function label(array $card): string {
        $last_four = self::last_four($card);

        return $last_four === '' ? 'Saved card' : '•••• ' . $last_four;
    }
}
