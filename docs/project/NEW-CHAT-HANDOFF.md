# SUPCheckout for UPayments — Continuation Handoff

**Mandatory first step for every new chat/session:** read [`START-HERE.md`](START-HERE.md) and verify live GitHub/source/check state before relying on this compact handoff.

Use this file with root [`AGENTS.md`](../../AGENTS.md), [`PROJECT-STATUS.md`](PROJECT-STATUS.md), [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md), [`NAMING-IDENTITY-STANDARD.md`](NAMING-IDENTITY-STANDARD.md), [`../COMPATIBILITY.md`](../COMPATIBILITY.md) and [`RELEASE-ENGINEERING.md`](RELEASE-ENGINEERING.md).

Chat memory is never authority. If this file conflicts with live evidence or another living authority, stop and reconcile current truth first.

## Identity

- Product: **SUPCheckout for UPayments**
- Short name: **SUPCheckout**
- Maintainer: **Simplix Innovations**
- Provider: **UPayments**
- Repository: `SimplixInnovations/supcheckout`
- Technical slug / text domain: `supcheckout`
- PHP namespace: `Simplixi\SUPCheckout`
- Package root: `supcheckout/`
- First-stable bootstrap: `supcheckout/UPayments.php`
- Development version: `0.1.0`

`for` is human-facing relationship wording only. Never encode it into repository, package, WordPress.org, namespace, REST, CSS/JS or artifact identifiers.

## Current state

Pre-release engineering is mature and the repository-side pre-acceptance blocker set is closed through PR #97.

- Repository Foundation / Phase 0 / Phase 9I — **DONE / VERIFIED**
- Provider lifecycle / Security threat model — **DONE / VERIFIED**
- Architecture A1-A5 — **DONE / VERIFIED**
- Quality Platform Q1-Q19 — **DONE / VERIFIED; permanently closed**
- Enterprise Tasks 1-8 — **DONE / VERIFIED**
- Approach 2 — **DONE / VERIFIED**
- PRs #91-#97 bounded pre-acceptance hardening — **DONE / VERIFIED / MERGED**
- Owner technical acceptance — **NOT ACCEPTED; fresh-clone re-acceptance required**
- Accepted owner baseline — **NONE**
- Approach 3 — **BLOCKED**
- Full UI/UX/branding/broad launch testing — **DEFERRED until after Approach 3**
- Public GitHub Release / WordPress.org publication — **NOT PERFORMED**

Do not invent Q20. New work uses named, bounded tasks.

## Latest runtime-bearing CI-certified main

Runtime-bearing merge:

`82d1fdaee91ee6bde6c26dfcc7ceb974d0d59847`

PR #97 exact certified head:

`1f2a0b8d35f96008be6ccfeb10c67fffcd3be5c0`

Evidence on merged `main`:

- **41/41 check-runs SUCCESS**;
- H12 — **1936/0 PHP + 150/0 Blocks**;
- compatibility — **20/20 runtime cells + aggregate gate SUCCESS**;
- Release Artifact — **69/0 + Release Gate SUCCESS**;
- WordPress.org readiness — **31/0 + official packaged Plugin Check SUCCESS**;
- Provider Sandbox and CodeQL — **SUCCESS**;
- canonical package — **51 files**, SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`;
- canonical/Linux/Windows package evidence — **byte-identical**;
- closed topology after duplicate #98 cleanup — **main only**.

Owner acceptance is still **NOT ACCEPTED**. The next gate is fresh-clone technical acceptance against the final reconciled `origin/main`; documentation-only descendants may advance its SHA while retaining the same certified distributable package.

## Protected compatibility identities

Never mechanically rename:

- gateway/payment ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks / Store API identity `upayments`;
- callback `wc_upayments`;
- `_upay_*` historical metadata;
- provider order identity such as `UPayments_order_id`;
- token/provenance/scope/generation identities;
- `upay_process_subscriptions` and billing-attempt state;
- historical order payment-method values;
- frozen Phase 9I migration identities;
- public compatibility wrapper `getAPIUrlForRetreiveCards()`;
- normalized `whitelabled` compatibility shape.

Changing one requires an approved migration with precedence, upgrade, rollback/failure semantics and regression evidence.

## First-stable bootstrap

`UPayments.php` is intentionally retained.

Real WordPress upgrade qualification proved that physically renaming an already-active plugin main file can strand WordPress's stored plugin basename. A future `supcheckout.php` migration is a separate release-sensitive project, not unfinished first-release cleanup.

## Permanent engineering controls

Do not weaken or bypass:

- Quality/H12 gates;
- 20-cell compatibility certification, including PHP 8.2, 8.3, 8.4 and 8.5 current-stack lanes plus the PHP 7.4 compatibility floor;
- deterministic release artifact/verifier;
- packaged legacy + HPOS smoke;
- historical package-root migration/rollback matrix;
- strict official WordPress Plugin Check;
- CodeQL/security analysis;
- architecture, provider, security, Quality Platform and SUPCheckout-specific regression harnesses.

Payment/security ambiguity fails closed. Browser redirect/callback payload alone is never financial truth. Non-idempotent operations are not blindly retried.

## Supported and unsupported boundaries

Repository evidence includes Classic checkout, Blocks registration/availability, HPOS/legacy storage, authenticated provider status binding, saved-card/token provenance boundaries, subscription eligibility/pre-dispatch safeguards and one additional merchant allocation.

External/manual qualification remains required for production payment completion, real wallet/device completion, WPML/WCML/multilingual/multicurrency/RTL, broad browser/theme/accessibility, performance/load, penetration/PCI/legal evidence and live subscription auto-deduction.

Automatic WooCommerce refunds and arbitrary marketplace multi-split are unsupported.

## Repository state rule

The verified closed repository topology after PR #97 and superseded #98 cleanup is `main` only. Temporary work branches must be scoped, reviewed and removed after merge. Open PR/issue/tag/release state must still be verified live at each session/release boundary.

The Main Rule must continue to require:

- `Governance`
- `H12 Regression Harness`
- `Compatibility Gate`
- `Release Gate`

with squash-only merging, linear history, deletion/non-fast-forward protection and no bypass actors.

## Current program sequence

The approved sequence is:

`Approach 2 closed → fresh-clone owner technical acceptance → accepted baseline → Approach 3 architecture modernization → Approach 3 re-certification → full UI/UX/branding/accessibility/broad launch testing → explicit release decision.`

Do **not** start Approach 3 before owner technical acceptance. Do **not** require final UI/UX or branding completion before Approach 3; those remain post-Approach-3 launch work unless fresh evidence proves an earlier blocker.

## Next owner action

Before beginning owner acceptance, confirm the current `main` is fully green and the repository has returned to the required closed topology. Then:

1. create a completely fresh clone from `https://github.com/SimplixInnovations/supcheckout.git`;
2. follow [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md) exactly;
3. run local Composer/standalone/H12 acceptance;
4. build and verify the deterministic ZIP from final `origin/main`;
5. perform bounded disposable/staging WooCommerce smoke;
6. send complete command output and manual-smoke findings back for owner-acceptance review;
7. if accepted, begin Approach 3 as a new bounded architecture program;
8. defer final UI/UX/branding/broad launch qualification until Approach 3 is re-certified;
9. choose the first public version and authorize publication separately.

## Historical evidence rule

Old SimplixPay/SUCheckout names, old repository coordinates and old SHAs may remain in historical phase/quality/spec/plan records when they were true at that milestone. Do not rewrite historical evidence into current branding.

Live evidence and living authority documents control current decisions.
