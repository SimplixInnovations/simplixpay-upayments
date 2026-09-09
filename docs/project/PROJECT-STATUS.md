# SUPCheckout for UPayments — Project Status

**Status document:** canonical living engineering state
**Last reconciled:** 2026-09-09
**Canonical repository:** `SimplixInnovations/supcheckout`
**Development version:** `0.1.0`
**Owner technical acceptance:** **ACCEPTED**
**Accepted Approach 2 baseline:** **`0c883d609906676966002eb022a82a9656eeacc5`**
**Accepted package:** `supcheckout-0.1.0.zip` — 51 files, SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`

> Fresh repository/source/CI/provider evidence wins over recorded status. Historical documents preserve milestone truth and are not rewritten to match later branding. The pre-acceptance B-X1 malformed-settings rejection closed by PR #84 remains historical evidence that documented the gaps closed by PRs #91-#97; the current owner technical acceptance verdict supersedes it but does not erase it.

## Executive status

| Area | Current state |
|---|---|
| Product | **SUPCheckout for UPayments** |
| Product family | **SUPCheckout** |
| Technical slug / text domain | `supcheckout` |
| PHP namespace | `Simplixi\SUPCheckout` |
| Package root | `supcheckout/` |
| First-stable bootstrap | `supcheckout/UPayments.php` — intentional compatibility exception |
| Provider scope | **UPayments only** |
| Development version | `0.1.0` |
| Approach 2 / pre-acceptance engineering | **DONE / VERIFIED** |
| Final pre-clone runtime/QA closure | **DONE / VERIFIED — PR #75** |
| Quality Platform Q1-Q19 | **DONE / VERIFIED — permanently closed at Q19** |
| PRs #91-#97 bounded pre-acceptance hardening | **DONE / VERIFIED / MERGED** |
| Compatibility matrix | **20 runtime cells — VERIFIED** |
| Owner technical acceptance | **ACCEPTED** |
| Accepted Approach 2 baseline | **`0c883d609906676966002eb022a82a9656eeacc5`** |
| Accepted package | **`supcheckout-0.1.0.zip` — 51 files / SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`** |
| Approach 3 architecture modernization | **AUTHORIZED / NOT STARTED** |
| Full UI/UX / branding / broad launch testing | **DEFERRED until after Approach 3** |
| Public GitHub Release | **NOT CREATED** |
| WordPress.org publication | **NOT PERFORMED** |

Historical Quality Platform Q1-Q19 is permanently closed. **No Q20 is justified.** New work uses named, bounded engineering/release tasks.

## Current program sequence

`pre-acceptance engineering closed → fresh-clone owner technical acceptance → accept/freeze the exact baseline → Approach 3 architecture modernization → re-certify Approach 3 → full UI/UX/branding/accessibility/broad launch testing → explicit version/release decision.`

The owner gate is deliberately technical and bounded. It does not require final UI/UX, branding, broad accessibility certification or exhaustive launch testing.

## Current runtime-bearing CI-certified main

Historical runtime-bearing merge (pre-acceptance CI anchor):

`82d1fdaee91ee6bde6c26dfcc7ceb974d0d59847`

PR #97 exact certified head:

`1f2a0b8d35f96008be6ccfeb10c67fffcd3be5c0`

Fresh post-merge evidence on `82d1fdaee91ee6bde6c26dfcc7ceb974d0d59847`:

- **41/41 check-runs SUCCESS**;
- H12 PHP — **1936 PASS / 0 FAIL**;
- H12 Blocks — **150 PASS / 0 FAIL**;
- Compatibility Certification — **20/20 runtime cells SUCCESS** + Compatibility Gate;
- Release Artifact — **69 PASS / 0 FAIL** + Release Gate;
- WordPress.org readiness — **31 PASS / 0 FAIL** + official packaged Plugin Check;
- Provider Sandbox — **SUCCESS**;
- CodeQL — **SUCCESS**;
- canonical deterministic package — **51 files**, SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`;
- canonical/Linux/Windows ZIP and manifest evidence — **byte-identical**.

## Current owner-accepted Approach 2 baseline

| Field | Value |
|---|---|
| Accepted baseline SHA | `0c883d609906676966002eb022a82a9656eeacc5` |
| Accepted package | `supcheckout-0.1.0.zip` |
| Accepted package SHA-256 | `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655` |
| Accepted package file count | `51` |
| Owner technical acceptance verdict | **ACCEPTED** |

The accepted baseline is the exact runtime anchor that Approach 3 must use as its regression/reference coordinate. Documentation-only reconciliation may advance live `main` after this baseline without changing distributable bytes; any accepted-runtime change requires a new acceptance event. Live GitHub evidence remains authoritative.

## Repository state

After PR #97 merged, redundant PR #98 was closed as superseded and its leftover feature branch was removed through a branch-local cleanup workflow. Verified closed remote topology returned to:

```text
main
```

At the start of this living-state reconciliation there were **0 open PRs and 0 open issues**.

The active **Main Rule** is enforced on the default branch with deletion and non-fast-forward protection, required linear history, squash-only merging, required review-thread resolution, no bypass actors, and strict required checks: `Governance`, `H12 Regression Harness`, `Compatibility Gate`, `Release Gate`.

Public release state must still be verified live before publication; repository certification does not authorize a tag, GitHub Release or WordPress.org publication.

## Product and compatibility boundary

SUPCheckout is permanently UPayments-specific. This repository must not grow adapters for unrelated providers or become a generic payment-routing platform.

Protected compatibility identities include, by default:

- gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks / Store API identity `upayments`;
- callback `wc_upayments`;
- historical `_upay_*` metadata;
- provider-order identities such as `UPayments_order_id`;
- token/provenance/scope/generation state;
- `upay_process_subscriptions` and billing-attempt state;
- historical order payment-method values;
- frozen Phase 9I migration identities;
- compatibility wrapper `getAPIUrlForRetreiveCards()`;
- normalized `whitelabled` compatibility shape.

These are merchant/provider contracts, not branding residue. Changing one requires an explicitly approved migration with upgrade, rollback and regression evidence.

## Certified platform boundary

The public compatibility source of truth is [`../COMPATIBILITY.md`](../COMPATIBILITY.md).

Current exact-matrix evidence includes:

- WordPress 6.9.x, 7.0.x and 7.1 cells;
- WooCommerce 10.8.x, 11.0.x and 11.1.x cells;
- PHP 7.4 compatibility-floor cells plus PHP 8.2, 8.3, 8.4 and 8.5 current-stack cells;
- legacy order storage and HPOS;
- Classic checkout registration;
- Cart / Checkout Blocks registration/availability;
- historical package-root → canonical package-root migration/rollback.

Compatibility headers and public claims must not be broadened beyond real matrix evidence.

## Permanent release controls

Every release-sensitive candidate must preserve:

- deterministic Git-HEAD-bound ZIP construction;
- byte-identical canonical/Ubuntu/Windows ZIP output for the same exact Git tree;
- `ZIP_STORED` entries plus fixed timestamp/creator-system/file-mode metadata;
- ZIP SHA-256 sidecar;
- per-file SHA-256 manifest;
- source-byte/tamper verification;
- packaged WordPress/WooCommerce smoke;
- packaged HPOS and legacy-storage smoke;
- historical-root migration/rollback qualification;
- official packaged WordPress Plugin Check with `strict: true`;
- dependency audit where applicable;
- CodeQL/security analysis.

The exact package hash is generated from distributable bytes in the final Git `HEAD`. Repository-only living documents are excluded from the installable package; changes to distributable files can change the hash. For one exact distributable tree, the local owner artifact must match canonical/Ubuntu/Windows CI SHA-256. A platform-dependent hash difference is a release blocker.

## Security/payment invariants

- Browser redirects and callback/webhook request bodies are not financial truth by themselves.
- Paid state requires authenticated provider verification plus exact transaction/order/economic binding.
- Ambiguous payment/security identity fails closed.
- Non-idempotent Charge/refund/auto-deduct operations are not blindly retried.
- Merchant secrets, bearer tokens, card data, customer/card tokens, token-provenance secrets and unnecessary PII must not appear in diagnostics, browser output or CI logs.
- Uninstall remains non-destructive by default.

## External/manual or unsupported boundaries

Repository certification does not replace:

- production merchant payment completion;
- real wallet/account/device completion;
- WPML/WCML, multilingual, multicurrency and RTL validation;
- broad browser/device/theme/accessibility testing;
- representative-store performance/load testing;
- penetration testing, PCI or legal/compliance attestation;
- live non-idempotent subscription auto-deduction evidence;
- provider webhook-signature verification until a stable documented contract exists.

Automatic WooCommerce refunds and arbitrary marketplace multi-split remain unsupported.

## Current remaining program

The owner-accepted Approach 2 baseline is frozen. The remaining program is sequenced:

1. **Approach 3 architecture modernization** — AUTHORIZED, NOT STARTED. Must be independently scoped, branched, implemented and re-certified against the frozen baseline `0c883d609906676966002eb022a82a9656eeacc5` and its accepted package SHA-256.
2. **Approach 3 re-certification** — required before launch-facing work is treated as final.
3. **Post-Approach-3 launch program** — final UI/UX, branding, accessibility, broad browser/device/theme/manual qualification and other launch-facing work.
4. **Explicit version decision** — choose first public version (early `0.1.0` release or separately approved `1.0.0` first stable) under an explicit dedicated PR with the full release-sensitive gate stack re-run.
5. **Publication** — Tag, GitHub Release and WordPress.org submission only after explicit owner approval. Owner technical acceptance does not authorize publication.

The fresh-clone procedural source-of-truth for any future acceptance event remains [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md). Earlier 2026-09-08 B-X1 fresh-clone rejection is preserved as historical evidence; this current accepted baseline supersedes it for the purpose of identifying the regression reference coordinate going forward.

## Historical program anchors

Historical records remain available for audit and regression context:

| Program | State |
|---|---|
| Repository Foundation | DONE / VERIFIED |
| Phase 0 Release Identity | DONE / VERIFIED |
| Phase 9I migration | DONE / VERIFIED |
| Provider Payment Lifecycle | DONE / VERIFIED |
| Security Threat Model | DONE / VERIFIED |
| Architecture A1-A5 | DONE / VERIFIED |
| Quality Platform Q1-Q19 | DONE / VERIFIED |
| Enterprise Tasks 1-8 | DONE / VERIFIED |
| SUPCheckout identity/repository migration | DONE / VERIFIED |
| Final pre-clone runtime/QA closure | DONE / VERIFIED |
| Final enterprise repository audit | DONE / VERIFIED |

See [`README.md`](README.md) for document precedence and [`ENTERPRISE-CERTIFICATION.md`](ENTERPRISE-CERTIFICATION.md) for retained certification evidence.
