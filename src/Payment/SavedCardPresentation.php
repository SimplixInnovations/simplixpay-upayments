<?php

namespace Simplixi\SUPCheckout\Payment;

final class SavedCardPresentation {
    /**
     * Return only the final four display digits from a provider card shape.
     *
     * Explicit provider last4 is preferred only when it resolves to exactly
     * four digits. A malformed longer last4 value is never truncated because
     * that would allow a mislabeled PAN to take precedence over number.
     *
     * Provider number is a fallback source only; all non-digits and all but
     * the final four digits are discarded immediately.
     *
     * @param array<string, mixed> $card Provider saved-card shape.
     */
    public static function last_four(array $card): string {
        if (array_key_exists('last4', $card) && is_scalar($card['last4'])) {
            $digits = preg_replace('/\D+/', '', (string) $card['last4']);
            if (is_string($digits) && strlen($digits) === 4) {
                return $digits;
            }
        }

        if (array_key_exists('number', $card) && is_scalar($card['number'])) {
            $digits = preg_replace('/\D+/', '', (string) $card['number']);
            if (is_string($digits) && strlen($digits) >= 4) {
                return substr($digits, -4);
            }
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

    /**
     * Convert provider card data to the only shape permitted in Blocks
     * localized settings. Card number sources are intentionally omitted.
     *
     * @param array<string, mixed> $card Provider saved-card shape.
     * @return array{token:string,label:string,brand:string}|null
     */
    public static function for_blocks(array $card): ?array {
        if (!isset($card['token']) || !is_string($card['token']) || $card['token'] === '') {
            return null;
        }

        return array(
            'token' => $card['token'],
            'label' => self::label($card),
            'brand' => isset($card['brand']) && is_scalar($card['brand']) ? (string) $card['brand'] : '',
        );
    }
}
