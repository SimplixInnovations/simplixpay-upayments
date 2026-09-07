# SUCheckout for UPayments — Compatibility & Certification Matrix

This document is the public compatibility truth. A capability is **Verified** only when exact reproducible evidence exists. External/manual requirements and unsupported features are named explicitly rather than inferred from green CI.

**Current posture:** pre-release SUCheckout engineering is merged and certified. Publication remains a separate owner decision.

## Certification anchors

### Runtime-bearing SUCheckout baseline

The product/runtime identity migration merged as:

`6aabc4fcb0606567a11637ea07fe081fed4c7f85`

Post-merge evidence on that runtime-bearing baseline:

- Quality Gates #764 — **SUCCESS**;
- Compatibility Certification #292 — **16/16 SUCCESS**;
- Release Artifact #243 — **SUCCESS**;
- Provider Sandbox #207 — **SUCCESS**;
- WordPress.org Submission Check #101 — **SUCCESS**;
- CodeQL/main-security #579 — **SUCCESS**;
- official packaged Plugin Check — **0 blocking errors**.

### Final control-plane closeout baseline

The documentation/owner-control closeout merged as:

`9591c431e1eb56fe40ca60147afdf9f3f909a212`

Fresh push-triggered evidence on that exact `main` SHA:

- Quality Gates #773 — **SUCCESS**;
- Compatibility Certification #301 — **SUCCESS, all 16 cells**;
- Release Artifact #252 — **SUCCESS**;
- Provider Sandbox #216 — **SUCCESS**;
- WordPress.org Submission Check #110 — **SUCCESS**;
- CodeQL/main-security #588 — **SUCCESS**.

Later documentation-only merges may advance `main` without changing the runtime-bearing baseline. Always verify live release-candidate evidence before publication.

## Platform matrix

Every certified row runs in a fresh real WordPress/WooCommerce/MySQL installation with both legacy posts/order storage and HPOS authoritative storage.

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

WooCommerce 11.1 requires WordPress 7.0+, so WordPress 6.9 / WooCommerce 11.1 is intentionally excluded as upstream-invalid. PHP 7.4 is a supported compatibility floor, not the recommended production runtime.

Public metadata derived from this matrix:

- WordPress `Requires at least`: **6.9**;
- WordPress `Tested up to`: **7.1**;
- WooCommerce `WC requires at least`: **10.8**;
- WooCommerce `WC tested up to`: **11.1**;
- PHP minimum: **7.4**;
- `cart_checkout_blocks`: **declared compatible**;
- `custom_order_tables`: **declared compatible**.

## Identity compatibility

| Identity | Current contract |
|---|---|
| Product name | **SUCheckout for UPayments** |
| Product family | **SUCheckout** |
| Package / WordPress.org slug | `sucheckout-upayments` |
| Text domain | `sucheckout-upayments` |
| Namespace | `Simplixi\SUCheckout\UPayments` |
| First-stable physical bootstrap | `UPayments.php` retained |
| Canonical package basename | `sucheckout-upayments/UPayments.php` |
| Gateway/payment ID | `upayments` preserved |
| Settings option | `woocommerce_upayments_settings` preserved |
| Blocks / Store API ID | `upayments` preserved |
| Callback | `wc_upayments` preserved |
| Historical payment/meta/token/cron identities | preserved |

The retained `UPayments.php` filename is a qualified compatibility contract. A future physical rename to `sucheckout-upayments.php` is not part of the first-stable release and requires a separately proven migration.

Changing the package root changes WordPress's stored plugin basename. Permanent release certification therefore treats legacy-root → canonical-root movement as an explicit pre-release migration: deactivate legacy, install/activate canonical, prove merchant/payment data continuity, prove rollback, return to canonical and remove the inactive legacy package.

## Capability matrix

| Area | SUCheckout status | Evidence / boundary |
|---|---|---|
| Classic checkout registration/runtime | **Verified** | Real Woo gateway registry contains exact protected ID `upayments`. |
| Cart / Checkout Blocks registration & availability | **Verified** | Real Blocks registry plus enabled/disabled/fresh-default/malformed-settings contract. |
| HPOS | **Verified / declared compatible** | Real legacy + HPOS Woo CRUD with protected payment metadata. |
| Provider Charge initialization | **Verified — bounded public sandbox** | Public-test-token Charge initialization; endpoint/HTTP/schema/payment-link boundary only. |
| Payment status financial truth | **Verified local lifecycle contract** | Authenticated provider-status binding and exact order/transaction/economics checks. Production merchant execution remains external. |
| Saved-card/token identity | **Verified — bounded runtime** | Guest rejection, provenance, membership and foreign-card/malformed-provenance fail-closed behavior. |
| Subscription checkout eligibility/pre-dispatch | **Verified — bounded runtime** | Opt-out, mixed-order, guest, strict plan/interval and token-preflight ordering. |
| Multi-merchant | **Verified — one additional merchant only** | One `extraMerchantData` allocation; arbitrary multi-split unsupported. |
| Activation/deactivation/reactivation | **Verified** | Protected settings/payment/token data preserved. |
| WordPress uninstall hook | **Verified non-destructive** | Merchant/payment/token state retained by default. |
| Deterministic canonical ZIP | **Permanent exact-head gate** | `sucheckout-upayments` ZIP/root, HEAD-bound bytes, checksum/manifest, reproducibility and tamper rejection. |
| Legacy package-root migration | **Permanent exact-head gate** | Legacy `simplixpay-upayments` → canonical `sucheckout-upayments`, protected data continuity + rollback. |
| Official WordPress Plugin Check | **Permanent packaged-artifact gate** | Runs against the unpacked deterministic `sucheckout-upayments/` package and fails on blocking findings. |
| Physical bootstrap rename | **Not adopted for first stable** | Real-install qualification showed an active-install filename rename can strand WordPress basename state. |
| Webhook/browser payment updates | **Non-authoritative** | Browser/webhook payload cannot establish paid state without trusted provider verification. |
| Automatic Woo refunds | **Unsupported** | Withheld pending durable idempotency/reconciliation design. |
| Arbitrary marketplace multi-split | **Unsupported** | Current contract is one additional merchant only. |
| Live subscription auto-deduction | **External/manual** | Non-idempotent provider mutation is not executed by repository CI. |
| Wallet payment completion | **External/manual** | Apple Pay / Google Pay / Samsung Pay require provider/account/device evidence. |
| WPML / String Translation | **External/manual certification required** | Source is i18n-ready under canonical text domain; no commercial-plugin matrix claim. |
| WCML / multicurrency | **External/manual certification required** | Display/charge/provider currency semantics require dedicated runtime evidence. |
| RTL / Arabic | **External/manual certification required** | Real checkout/admin/account/provider-return UI validation required. |
| Browser/device/theme interoperability | **External/manual certification required** | Server-side CI does not prove rendering/input/focus behavior. |
| Accessibility | **External/manual certification required** | Keyboard/focus/screen-reader/contrast/error-state evidence required. |
| Performance/stability | **Store-specific evidence required** | Universal thresholds are not inferred from unit/server-side CI. |
| Penetration test / PCI / compliance | **External organizational evidence** | Not produced by repository CI. |

## Permanent regression evidence

The following are complementary permanent controls:

- historical Quality Platform Q1-Q19 — **DONE / VERIFIED / closed**;
- H12 PHP and Blocks regressions;
- real 16-cell compatibility matrix;
- deterministic release artifact build/verifier;
- packaged legacy/HPOS runtime smoke;
- legacy-root migration and rollback certification;
- official WordPress.org Plugin Check on the exact packaged artifact;
- bounded provider-sandbox certification;
- CodeQL/security checks.

No one control substitutes for the others or for explicitly external/manual evidence.

## Evidence definitions

- **Verified** — exact reproducible environment and reviewed evidence exists.
- **Permanent exact-head gate** — must pass on the exact candidate and again after merge before release claims are authorized.
- **Verified — bounded** — only the stated boundary is proven; broader provider/user/device completion is not implied.
- **External/manual** — requires an external account, commercial plugin, browser/device, production-like store or organizational evidence not safely generated by repository automation.
- **Unsupported** — intentionally not implemented/advertised.

## Public-claim rule

Do not broaden platform, provider, feature, multilingual, browser, accessibility, performance, security or compliance claims beyond this matrix. A neighboring green version, static analyzer, unit harness or provider marketing page is not SUCheckout certification.
