# SUPCheckout for UPayments — Compatibility & Certification Matrix

This document is the public compatibility source of truth. A capability is **Verified** only when exact reproducible evidence exists. Green CI is never interpreted beyond the boundary it actually exercises.

**Current posture:** pre-release, runtime/payment hardening certified, publication not yet authorized.

## Current runtime certification anchor

Latest runtime-bearing certified merge:

`82d1fdaee91ee6bde6c26dfcc7ceb974d0d59847`

Certified PR #97 head:

`1f2a0b8d35f96008be6ccfeb10c67fffcd3be5c0`

Fresh post-merge evidence:

- exact merged-main check-runs — **41/41 SUCCESS**;
- H12 PHP — **1936 PASS / 0 FAIL**;
- H12 Blocks — **150 PASS / 0 FAIL**;
- Compatibility Certification — **20/20 runtime cells SUCCESS** + Compatibility Gate;
- Release Artifact — **69 PASS / 0 FAIL** + Release Gate;
- Provider Sandbox — **SUCCESS**;
- WordPress.org readiness — **31 PASS / 0 FAIL** + official packaged Plugin Check;
- CodeQL — **SUCCESS**;
- deterministic package — **51 files**, SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`, byte-identical across canonical/Linux/Windows evidence.

Owner technical acceptance is **ACCEPTED** for the frozen Approach 2 baseline 0c883d609906676966002eb022a82a9656eeacc5 and accepted package SHA-256 58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655. Owner technical acceptance does not authorize publication; the Approach 3 architecture decision is APPROVED / RECORDED and runtime-neutral T1 is NOT STARTED against the frozen accepted baseline.

### Current-stack and pre-acceptance hardening

The current real matrix includes PHP **8.2, 8.3, 8.4 and 8.5** on WordPress 7.1 / WooCommerce 11.1.0, plus the supported PHP **7.4 compatibility floor** on WordPress 6.9.7 / WooCommerce 10.8.1. PHP 8.0 and 8.1 are intentionally not added merely to enlarge the matrix.

Post-owner-rejection hardening relevant to public compatibility and checkout safety:

- PR #91 aligned Blocks runtime availability with Classic fail-closed eligibility;
- PR #93 added PHP 8.2 legacy + HPOS current-stack runtime certification, expanding the matrix to 20 cells;
- PR #94 removed repository-only documentation from the installable package;
- PR #95 enforced last-four-only saved-card presentation;
- PR #96 replaced provider saved-card browser token exposure with opaque server-resolved handles;
- PR #97 removed the unused numeric WordPress user ID from subscription browser localization.

These changes do not broaden SUPCheckout beyond the UPayments-specific feature/support boundary documented below.

## Platform matrix

Every certified row uses a real WordPress/WooCommerce installation and exercises both legacy order storage and HPOS authoritative storage.

| WordPress | WooCommerce | PHP | Legacy storage | HPOS |
|---|---|---:|---|---|
| 7.1 | 11.1.0 | 8.5 | **Verified** | **Verified** |
| 7.1 | 11.1.0 | 8.4 | **Verified** | **Verified** |
| 7.1 | 11.1.0 | 8.3 | **Verified** | **Verified** |
| 7.1 | 11.1.0 | 8.2 | **Verified** | **Verified** |
| 7.0.4 | 11.1.0 | 8.3 | **Verified** | **Verified** |
| 7.0.4 | 11.0.1 | 8.3 | **Verified** | **Verified** |
| 7.0.4 | 10.8.1 | 8.3 | **Verified** | **Verified** |
| 7.1 | 10.8.1 | 8.3 | **Verified** | **Verified** |
| 6.9.7 | 10.8.1 | 8.3 | **Verified** | **Verified** |
| 6.9.7 | 10.8.1 | 7.4 | **Verified** | **Verified** |

WooCommerce 11.1 requires WordPress 7.0+, so WordPress 6.9 / WooCommerce 11.1 is intentionally excluded as upstream-invalid. PHP 7.4 is the supported compatibility floor, not a recommendation for new production deployments. Current PHP branches are certified on the current WordPress/WooCommerce stack; compatibility-only code required for the floor must not execute deprecated behavior on current PHP.

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
- real 20-cell compatibility matrix, including PHP 8.2, 8.3, 8.4 and 8.5 current-stack lanes plus the PHP 7.4 compatibility floor;
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
