# SimplixPay for UPayments — Compatibility & Certification Matrix

This document is the public compatibility truth. A capability is **Verified** only when exact reproducible evidence exists; external/manual requirements and unsupported features are named explicitly.

**Current posture:** Enterprise release-candidate qualification — **DONE / VERIFIED**. No public stable release has been published.

## Platform matrix

Every row below runs in a fresh real WordPress/WooCommerce/MySQL installation with both legacy posts/order storage and HPOS authoritative storage.

| WordPress | WooCommerce | PHP | Legacy storage | HPOS |
|---|---|---:|---|---|
| 7.1 | 11.1.0 | 8.4 | **Verified** | **Verified** |
| 7.1 | 11.1.0 | 8.3 | **Verified** | **Verified** |
| 7.0.4 | 11.1.0 | 8.3 | **Verified** | **Verified** |
| 7.0.4 | 11.0.1 | 8.3 | **Verified** | **Verified** |
| 7.0.4 | 10.8.1 | 8.3 | **Verified** | **Verified** |
| 7.1 | 10.8.1 | 8.3 | **Verified** | **Verified** |
| 6.9.7 | 10.8.1 | 8.3 | **Verified** | **Verified** |
| 6.9.7 | 10.8.1 | 7.4 | **Verified** | **Verified** |

WooCommerce 11.1 requires WordPress 7.0+, so WordPress 6.9 / WooCommerce 11.1 is intentionally excluded as upstream-invalid. PHP 7.4 is an ecosystem compatibility floor, not a recommended production runtime.

Public metadata derived from the matrix:

- WordPress `Requires at least`: **6.9**;
- WordPress `Tested up to`: **7.1**;
- WooCommerce `WC requires at least`: **10.8**;
- WooCommerce `WC tested up to`: **11.1**;
- PHP minimum: **7.4**;
- `cart_checkout_blocks`: **declared compatible**;
- `custom_order_tables`: **declared compatible**.

## Capability matrix

| Area | Simplix status | Evidence / boundary |
|---|---|---|
| Classic checkout registration/runtime | **Verified** | Real Woo gateway registry contains exact ID `upayments` in every matrix cell. |
| Cart/Checkout Blocks registration & availability | **Verified** | Real Blocks registry + enabled/disabled/fresh-default/malformed-settings contract in every matrix cell. |
| HPOS | **Verified / declared compatible** | Real legacy + HPOS Woo CRUD with protected payment metadata in all cells. |
| Provider Charge initialization | **Verified — bounded public sandbox** | One public-test-token Charge initialization; exact HTTPS endpoint, HTTP 201/schema, normalized HTTPS payment link; no completion. |
| Payment status financial truth | **Verified local lifecycle contract** | Authenticated provider-status binding, amount/reference/order identity, bounded reconciliation and Woo payment semantics. Production merchant execution remains external. |
| Saved-card/token identity | **Verified — bounded runtime** | Guest rejection, canonical provenance, exact membership, foreign-card rejection, malformed-provenance fail-closed. Live provider card lifecycle remains external. |
| Subscription checkout eligibility/pre-dispatch | **Verified — bounded runtime** | Opt-out, mixed-order, guest, strict plan/interval, token-preflight ordering. Live recurring mutation remains external. |
| Multi-merchant | **Verified — one additional merchant only** | One `extraMerchantData` allocation with exact amount/IBAN/charge-type boundaries. Arbitrary multi-split is unsupported. |
| Activation/deactivation/reactivation | **Verified** | Protected settings/payment/token data preserved. |
| WordPress uninstall hook | **Verified non-destructive** | Merchant/payment/token state retained by default. |
| Deterministic installable ZIP | **Verified** | HEAD-bound source bytes, deterministic archive, checksum/manifest, independent verification, packaged runtime smoke. |
| Existing-install upgrade/rollback | **Verified** | Current + floor cells, same-basename upgrade, rollback, data/callback/cron continuity. |
| Physical basename migration | **Not safe for first stable** | Direct `UPayments.php` → `simplixpay-upayments.php` loses active runtime identity; first stable retains historical basename. |
| Text-domain migration | **Deferred** | Package has 70 explicit PHP translation calls bound to `upayments`; no coordinated WPML/String Translation migration certified. |
| Webhook payment updates | **Non-authoritative** | Browser/webhook payload cannot establish paid state. Stable provider signature contract remains unresolved. |
| Automatic Woo refunds | **Unsupported** | Intentionally withheld pending durable idempotency/reconciliation design. |
| Arbitrary marketplace multi-split | **Unsupported** | Current contract is one additional merchant only. |
| Live subscription auto-deduction | **External/manual** | Non-idempotent provider mutation is not executed automatically by repository certification. |
| Wallet payment completion | **External/manual** | Apple Pay / Google Pay / Samsung Pay require provider/account/device evidence. |
| WPML / String Translation | **External/manual certification required** | Source remediation/history exists, but no current commercial-plugin matrix justifies a public compatibility claim. |
| WCML / multicurrency | **External/manual certification required** | Display/charge/provider currency semantics require dedicated runtime evidence. |
| RTL / Arabic | **External/manual certification required** | Real checkout/admin/account/provider-return UI validation required. |
| Browser/device/theme interoperability | **External/manual certification required** | Server-side CI does not prove browser rendering/input/focus behavior. |
| Accessibility | **External/manual certification required** | Keyboard/focus/screen-reader/contrast/error-state evidence required. |
| Performance/stability | **Store-specific evidence required** | Universal thresholds are not inferred from unit/server-side CI. |
| Penetration test / PCI / compliance | **External organizational evidence** | Not produced by repository CI. |

## Release identity for the first stable

Task 7 proves the first stable distribution must retain:

- package root: `simplixpay-upayments/`;
- main file: `UPayments.php`;
- plugin basename: `simplixpay-upayments/UPayments.php`;
- text domain: `upayments`;
- gateway/payment ID: `upayments`.

The eventual filename/text-domain targets remain future migration goals and must not be applied by search/replace.

## Permanent regression evidence

- Quality Platform Q1-Q19: **DONE / VERIFIED**;
- H12 PHP: **1927 PASS / 0 FAIL** baseline;
- H12 Blocks: **144 PASS / 0 FAIL** baseline;
- real compatibility matrix: **16/16** cells;
- deterministic release-artifact workflow includes packaged legacy/HPOS and current/floor upgrade cells;
- bounded provider-sandbox workflow is separately permanent.

These controls are complementary. None substitutes for external/manual evidence explicitly classified above.

## Evidence definitions

- **Verified** — exact reproducible environment and reviewed evidence exists.
- **Verified — bounded** — the stated boundary is verified; broader provider/user/device completion is not implied.
- **External/manual** — requires an external account, commercial plugin, browser/device, production-like store or organizational evidence not safely generated by repository automation.
- **Deferred** — future migration/capability intentionally postponed until its prerequisite evidence exists.
- **Unsupported** — intentionally not implemented/advertised.

## Public-claim rule

Do not broaden platform, provider, feature, multilingual, browser, accessibility, performance or compliance claims beyond this matrix. A neighboring green version, static analyzer, unit harness or provider marketing page is not Simplix certification.
