from pathlib import Path

p = Path('tests/integration/OrderEconomicsRuntimeTest.php')
s = p.read_text()

old_comment = """// Characterize the maximum provider-supported plain-decimal amount through the
// actual Woo storage layer. Trailing storage zeros are numerically insignificant,
// but the provider-bound token must remain canonical and within the 22-byte limit.
"""
new_comment = """// Characterize an over-precision input through the actual Woo order API. Woo
// canonicalizes WC_Order totals to the configured store price precision before
// persistence; SUPCheckout must use that finalized persisted value exactly.
"""
if s.count(old_comment) != 1:
    raise SystemExit(f'expected one precision comment, found {s.count(old_comment)}')
s = s.replace(old_comment, new_comment, 1)

old = """$persisted_precision = (string) $order->get_total();
$normalize_decimal = static function ($value) {
    $value = (string) $value;
    if (false !== strpos($value, '.')) {
        $value = rtrim(rtrim($value, '0'), '.');
    }
    return $value;
};
supcheckout_cert_assert($normalize_decimal($precision_amount) === $normalize_decimal($persisted_precision), 'Woo storage round-trip preserves the maximum supported amount numerically');
"""
new = """$persisted_precision = (string) $order->get_total();
$woo_canonical_precision = wc_format_decimal($precision_amount, wc_get_price_decimals());
supcheckout_cert_assert(
    $woo_canonical_precision === $persisted_precision,
    'Woo order API canonicalizes over-precision input to configured store precision before payment'
);
"""
if s.count(old) != 1:
    raise SystemExit(f'expected one precision assertion block, found {s.count(old)}')
s = s.replace(old, new, 1)

p.write_text(s)
