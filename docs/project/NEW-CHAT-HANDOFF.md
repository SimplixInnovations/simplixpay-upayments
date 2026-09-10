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

Pre-release engineering is mature and the repository-side pre-acceptance blocker set is closed through PR #97. Owner technical acceptance has been completed and the Approach 2 baseline is frozen.

- Repository Foundation / Phase 0 / Phase 9I — **DONE / VERIFIED**
- Provider lifecycle / Security threat model — **DONE / VERIFIED**
- Architecture A1-A5 — **DONE / VERIFIED**
- Quality Platform Q1-Q19 — **DONE / VERIFIED; permanently closed at Q19**
- Enterprise Tasks 1-8 — **DONE / VERIFIED**
- Approach 2 — **DONE / VERIFIED**
- PRs #91-#97 bounded pre-acceptance hardening — **DONE / VERIFIED / MERGED**
- Owner technical acceptance — **ACCEPTED**
- Accepted Approach 2 baseline — **`0c883d609906676966002eb022a82a9656eeacc5`**
- Accepted package — **`supcheckout-0.1.0.zip` — 51 files / SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`**
- Approach 3 — **ARCHITECTURE APPROVED / T1 DONE / VERIFIED / T2 DONE / VERIFIED / RUNTIME MODERNIZATION IN PROGRESS**
- Full UI/UX/branding/broad launch testing — **DEFERRED until after Approach 3**
- Public GitHub Release / WordPress.org publication — **NOT PERFORMED**

Do not invent Q20. New work uses named, bounded tasks.

## Latest runtime-bearing CI-certified main

Runtime-bearing T2 merge:

`047cc86060efb97761d7a0cc4a3806f971ab6fe1`

PR #104 exact certified head:

`4cff2dc6e6d11a4b3232a6d3d70d6280a59741c4`

Evidence:

- PR head — **42/42 checks SUCCESS**;
- merged main — **41/41 checks SUCCESS**;
- T1 dependency/provider-egress guardrail — **11/0**;
- T1 active callback characterization — **41/0**;
- T2 direct legacy-fallback characterization — **25/0**;
- full compatibility matrix + Compatibility Gate — **SUCCESS**;
- Release Gate, Provider Sandbox, WordPress.org packaged Plugin Check and CodeQL/security — **SUCCESS**;
- current deterministic candidate package — **51 files**, SHA-256 `368aaa5cb1a75e6df41ff17bb2e2126431b49da5e6b8dfe508c718433009fc04`, canonical/Linux/Windows byte-identical;
- closed runtime topology after T2 — **main only**.

Owner technical acceptance is **ACCEPTED** for this repository against the frozen Approach 2 baseline `0c883d609906676966002eb022a82a9656eeacc5` and accepted package SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`. Approach 3 T1 is **DONE / VERIFIED** on merged main `beb89ac0c4d8c0e9b7c8b2de1e13c237bbd37b15`. Runtime-bearing T2 is **DONE / VERIFIED** on merged main `047cc86060efb97761d7a0cc4a3806f971ab6fe1`; fresh main checks were 41/41 SUCCESS and its deterministic non-accepted candidate package is 51 files / SHA-256 `368aaa5cb1a75e6df41ff17bb2e2126431b49da5e6b8dfe508c718433009fc04`. The next gate is direct characterization of the legacy return/webhook/private verification surfaces before any further consolidation.

The pre-acceptance B-X1 malformed-settings rejection closed by PR #84 remains historical evidence; the current owner technical acceptance verdict supersedes it but does not erase it.

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

The verified closed repository topology after Approach 3 T2 PR #104 is `main` only. Temporary work branches must be scoped, reviewed and removed after merge. Open PR/issue/tag/release state must still be verified live at each session/release boundary.

The Main Rule must continue to require:

- `Governance`
- `H12 Regression Harness`
- `Compatibility Gate`
- `Release Gate`

with squash-only merging, linear history, deletion/non-fast-forward protection and no bypass actors.

## Current program sequence

The approved sequence is:

`Approach 2 closed → fresh-clone owner technical acceptance → accepted baseline → Approach 3 architecture modernization → Approach 3 re-certification → full UI/UX/branding/accessibility/broad launch testing → explicit release decision.`

Owner technical acceptance has been completed and the Approach 2 baseline is accepted. Approach 3 architecture is **APPROVED / RECORDED**; T1 and runtime-bearing T2 are **DONE / VERIFIED**. Approach 3 runtime modernization is now in progress. The approved style remains incremental strangler modernization around A1-A5 and the already-active `PaymentLifecycle` callback strangler. Do not require final UI/UX or branding completion before Approach 3; those remain post-Approach-3 launch work unless fresh evidence proves an earlier blocker.

## Next owner action

Before beginning an Approach 3 implementation tranche, confirm:

1. the live repository topology is `main` only with no unintended open PRs/issues/tags/releases;
2. the frozen accepted Approach 2 baseline is still **`0c883d609906676966002eb022a82a9656eeacc5`** and its accepted package SHA-256 is still **`58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`**.

Then:

1. read `.ai-architect/` and execute the approved bounded tranche against the frozen baseline;
2. branch, implement and re-certify Approach 3 against the accepted baseline;
3. defer final UI/UX/branding/broad launch qualification until Approach 3 is re-certified;
4. choose the first public version and authorize publication separately.

Do **not** redo Approach 2 unless fresh evidence invalidates the accepted baseline.

## Historical evidence rule

Old SimplixPay/SUCheckout names, old repository coordinates and old SHAs may remain in historical phase/quality/spec/plan records when they were true at that milestone. Do not rewrite historical evidence into current branding.

Live evidence and living authority documents control current decisions.
