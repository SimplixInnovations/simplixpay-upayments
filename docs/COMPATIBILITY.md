# SUPCheckout for UPayments — Compatibility & Certification Matrix

This document is the public compatibility source of truth. A capability is **Verified** only when exact reproducible evidence exists. Green CI is never interpreted beyond the boundary it actually exercises.

**Current posture:** pre-release, runtime/payment hardening certified, publication not yet authorized.

## Current runtime certification anchor

Latest runtime-bearing certified merge:

`1354b8e6f801a847a5fa9b5b657e77647384bdbc`

Certified PR #75 head:

`9474955e2d5438ccc9c0334b52dc0f72be557a86`

Fresh post-merge evidence:

- Quality #958 — **SUCCESS**;
- H12 Regression Harness — **SUCCESS**;
- Compatibility #486 — **16/16 SUCCESS**;
- Release Artifact #434 — **SUCCESS**, including packaged legacy/HPOS plus historical package-root migration/rollback;
- Provider Sandbox #386 — **SUCCESS**;
- WordPress.org Submission Check #289 — **SUCCESS / strict packaged Plugin Check**;
- CodeQL/main-security #780 — **SUCCESS**.

Later documentation/presentation-only descendants may advance `main` without changing this runtime baseline. Release decisions must always verify the exact current candidate.

### Latest current-stack compatibility hardening

The runtime-bearing baseline above remains unchanged because PR #80 changed no production plugin runtime/package-source file. PR #80 specifically strengthened current-stable PHP acceptance and the evidence required to support the matrix below:

- final exact PR head: `717c34d16a5fdc5548b045751bdf53dbdb936a76`;
- final PR result: **35/35 SUCCESS**, including Quality + H12 on PHP 8.5, **18/18 compatibility**, Release Gate and CodeQL;
- deterministic package: **56 files**, SHA-256 `32776f23f02de2fa7be14a5c84ebdcddb9b2f2d348366deade826bca86e58da3`;
- squash merge: `65e39c5da4fee6462e219bbb0ec21038f831c6b1`;
- merged Git tree: **identical** to the certified PR-head tree;
- post-merge `main`: **26/26 triggered checks SUCCESS**, including PHP 8.5 Quality/H12, Compatibility Gate and CodeQL.

Release Gate did not trigger again on the post-merge push; its successful evidence belongs to the identical final PR tree. One PR migration job initially failed before migration execution because GitHub's artifact service returned HTTP 403; the same job was rerun successfully, after which Release Gate passed. No plugin or migration failure was waived.

## Platform matrix

Every certified row uses a real WordPress/WooCommerce installation and exercises both legacy order storage and HPOS authoritative storage.

| WordPress | WooCommerce | PHP | Legacy storage | HPOS |
|---|---|---:|---|---|
| 7.1 | 11.1.0 | 8.5 | **Verified** | **Verified** |
| 7.1 | 11.1.0 | 8.4 | **Verified** | **Verified** |
| 7.1 | 11.1.0 | 8.3 | **Verified** | **Verified** |
| 7.0.4 | 11.1.0 | 8.3 | **Verified** | **Verified** |
| 7.0.4 | 11.0.1 | 8.3 | **Verified** | **Verified** |
| 7.0.4 | 10.8.1 | 8.3 | **Verified** | **Verified** |
| 7.1 | 10.8.1 | 8.3 | **Verified** | **Verified** |
| 6.9.7 | 10.8.1 | 8.3 | **Verified** | **Verified** |
| 6.9.7 | 10.8.1 | 7.4 | **Verified** | **Verified** |

WooCommerce 11.1 requires WordPress 7.0+, so WordPress 6.9 / WooCommerce 11.1 is intentionally excluded as upstream-invalid. PHP 7.4 is the supported compatibility floor, not a recommendation for new production deployments. The current-stable PHP branch is also certified on the current WordPress/WooCommerce stack; compatibility-only code required for the floor must not execute deprecated behavior on current PHP.

Public metadata derived from this matrix:

- WordPress `Requires at least`: **6.9**;
- WordPress `Tested up to`: **7.1**;
- WooCommerce `WC requires at least`: **10.8**;
- WooCommerce `WC tested up to`: **11.1**;
- PHP minimum: **7.4**;
- `cart_checkout_blocks`: **declared compatible**;
- `custom_order_tables`: **declared compatible**.

## Identity compatibility

| Surface | Current contract |
|---|---|
| Product | **SUPCheckout for UPayments** |
| Short name | **SUPCheckout** |
| Repository / package slug | `supcheckout` |
| Text domain | `supcheckout` |
| PHP namespace | `Simplixi\SUPCheckout` |
| First-stable physical bootstrap | `UPayments.php` retained |
| Canonical package basename | `supcheckout/UPayments.php` |
| Gateway/payment ID | `upayments` preserved |
| Settings option | `woocommerce_upayments_settings` preserved |
| Blocks / Store API ID | `upayments` preserved |
| Callback | `wc_upayments` preserved |
| Historical payment/meta/token/subscription identities | preserved |

The retained `UPayments.php` filename is an explicit compatibility decision. Real installation qualification showed that directly renaming an active plugin main file can strand WordPress's stored plugin basename. A future physical rename to `supcheckout.php` requires a separately tested migration.

Historical package-root movement is also explicitly certified: the permanent release matrix covers both pre-stable `simplixpay-upayments` and `sucheckout-upayments` roots moving to canonical `supcheckout`, including protected data continuity and rollback.

## Capability matrix

| Area | Status | Evidence boundary |
|---|---|---|
| Classic checkout registration/runtime | **Verified** | Real WooCommerce gateway registry, protected ID `upayments`. |
| Cart / Checkout Blocks registration & availability | **Verified** | Real Blocks registry plus enabled/disabled/default/malformed-settings behavior. |
| HPOS | **Verified / declared compatible** | Real legacy + HPOS WooCommerce CRUD with protected payment metadata. |
| Provider Charge initialization | **Verified — bounded sandbox** | Public test Charge initialization; not production completion. |
| Payment-status financial truth | **Verified lifecycle contract** | Authenticated provider-status binding plus exact order/transaction/economic checks. |
| Saved-card/token identity | **Verified — bounded runtime** | Ownership/provenance/scope checks and fail-closed malformed/foreign identity behavior. |
| Subscription checkout eligibility/pre-dispatch | **Verified — bounded runtime** | Guest/mixed-order/plan/interval/token-preflight safeguards. |
| Multi-merchant | **Verified — one additional merchant only** | One additional allocation; arbitrary multi-split unsupported. |
| Activation/deactivation/reactivation | **Verified** | Protected settings/payment/token state preserved. |
| Uninstall | **Verified non-destructive** | Merchant/payment/token state retained by default. |
| Deterministic canonical ZIP | **Permanent exact-head gate** | HEAD-bound bytes, checksum/manifest, reproducibility and tamper rejection. |
| Historical package-root migration | **Permanent exact-head gate** | Both pre-stable roots → `supcheckout`, continuity + rollback. |
| Official WordPress Plugin Check | **Permanent packaged-artifact gate** | Runs against the unpacked deterministic package with `strict: true`. |
| Browser/callback payment updates | **Non-authoritative alone** | Cannot establish paid state without trusted provider verification. |
| Automatic WooCommerce refunds | **Unsupported** | Withheld pending a durable idempotency/reconciliation design. |
| Arbitrary marketplace multi-split | **Unsupported** | Current boundary is one additional merchant only. |
| Live subscription auto-deduction | **External/manual** | Non-idempotent provider mutation is not executed merely for CI. |
| Wallet payment completion | **External/manual** | Requires eligible provider account/device evidence. |
| WPML / WCML / multicurrency | **External/manual** | Requires dedicated real-environment qualification. |
| RTL / Arabic | **External/manual** | Requires real admin/checkout/account/return UI validation. |
| Browser/device/theme interoperability | **External/manual** | Server-side CI does not prove rendering/input behavior. |
| Accessibility | **External/manual** | Keyboard/focus/screen-reader/contrast/error-state evidence required. |
| Performance/load | **Store-specific evidence required** | Universal thresholds are not inferred from CI. |
| Penetration test / PCI / legal compliance | **External organizational evidence** | Not produced by repository automation. |

## Permanent regression controls

The permanent evidence stack is intentionally layered:

- Quality Platform Q1-Q19 historical regressions — **closed / retained**;
- H12 PHP and Blocks regressions;
- real 18-cell compatibility matrix, including current-stable PHP 8.5 on legacy storage and HPOS;
- deterministic release artifact builder/verifier;
- packaged legacy/HPOS smoke;
- historical package-root migration/rollback certification;
- official packaged WordPress Plugin Check;
- bounded provider-sandbox certification;
- CodeQL/security analysis.

No one layer substitutes for the others or for explicitly external/manual qualification.

## Evidence definitions

- **Verified** — exact reproducible environment and reviewed evidence exists.
- **Permanent exact-head gate** — must pass on the exact candidate and again after merge before a release claim is authorized.
- **Verified — bounded** — only the stated boundary is proven.
- **External/manual** — requires an external account, commercial plugin, browser/device, production-like store or organizational evidence not safely generated by repository automation.
- **Unsupported** — intentionally not implemented/advertised.

## Public-claim rule

Do not broaden platform, provider, feature, multilingual, browser, accessibility, performance, security or compliance claims beyond this matrix. Neighboring green versions, static analysis, unit tests or provider marketing material are not SUPCheckout certification.
