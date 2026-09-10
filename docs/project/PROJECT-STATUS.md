# SUPCheckout for UPayments — Project Status

**Status document:** canonical living engineering state
**Last reconciled:** 2026-09-10
**Canonical repository:** `SimplixInnovations/supcheckout`
**Development version:** `0.1.0`
**Owner technical acceptance:** **ACCEPTED for frozen Approach 2**
**Accepted Approach 2 baseline:** **`0c883d609906676966002eb022a82a9656eeacc5`**
**Accepted package:** `supcheckout-0.1.0.zip` — 51 files / SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`

> Fresh repository/source/CI/provider evidence wins over this record. Historical milestone documents remain historical evidence; current-state records must be reconciled when live evidence advances.

## Executive status

| Area | Current state |
|---|---|
| Product | **SUPCheckout for UPayments** |
| Technical slug / text domain | `supcheckout` |
| PHP namespace | `Simplixi\SUPCheckout` |
| Provider scope | **UPayments only** |
| Approach 2 | **DONE / VERIFIED / OWNER ACCEPTED** |
| Quality Platform Q1-Q19 | **DONE / VERIFIED — permanently closed at Q19** |
| Approach 3 T1 | **DONE / VERIFIED** |
| Approach 3 T2 | **DONE / VERIFIED — runtime-bearing** |
| Approach 3 T3 | **DONE / VERIFIED — runtime-neutral** |
| Latest merged Approach 3 main coordinate | **`a7a8bbfc3a1dc551127b7ead897c964e95c7cec9`** |
| Active successor program | **`post-t3-ecosystem-hardening` — PR #108** |
| Latest fully certified runtime-content PR checkpoint | **`b06976ac689cfd0d469bd96b0d6a2b925c863747`** |
| Public GitHub Release | **NOT CREATED** |
| WordPress.org publication | **NOT PERFORMED / NOT AUTHORIZED** |

Historical Quality Platform Q1-Q19 is permanently closed. **No Q20 is justified.** New work uses named, bounded engineering/release tasks.

## Current Approach 3 coordinate

T3 was squash-merged through PR #107 to:

`a7a8bbfc3a1dc551127b7ead897c964e95c7cec9`

T3 exact certified PR head:

`f7c7d596dc4a2c8464d1acdf13dfe51028a5f9e0`

T3 is runtime-neutral. It permanently characterizes:

- `WC_Upayments::return_from_upayments()`;
- `WC_Upayments::web_hook_handler()`;
- private `WC_Upayments::verify_payment_status()`.

It preserves the compatibility trap that direct callers of `return_from_upayments()` may not carry the WC-API router GET `page` marker used by `PaymentLifecycle::handle_callback()` for browser-mode inference. T3 therefore did **not** authorize naïve T4 delegation.

Fresh merged-main evidence for `a7a8bbfc3a1dc551127b7ead897c964e95c7cec9` includes successful Quality Gates, Compatibility Certification and CodeQL. Because T3 changed no distributable runtime files, its candidate package remained the T2 51-file package / SHA-256 `368aaa5cb1a75e6df41ff17bb2e2126431b49da5e6b8dfe508c718433009fc04`.

## Frozen regression authority

| Field | Value |
|---|---|
| Owner-accepted Approach 2 baseline | `0c883d609906676966002eb022a82a9656eeacc5` |
| Accepted package | `supcheckout-0.1.0.zip` |
| Accepted package SHA-256 | `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655` |
| Accepted package files | `51` |

Approach 3 descendants do not silently redefine owner acceptance. A new explicit owner acceptance event is required at Approach 3 closeout.

## Active post-T3 hardening

Canonical plan: [`../superpowers/plans/2026-09-10-post-t3-ecosystem-hardening.md`](../superpowers/plans/2026-09-10-post-t3-ecosystem-hardening.md).

Active integration: draft PR #108 / `audit/post-t3-ecosystem-hardening`.

Latest fully certified runtime-content checkpoint before R0 control-plane reconciliation:

`b06976ac689cfd0d469bd96b0d6a2b925c863747`

At that exact SHA:

- Quality Gates — **SUCCESS**;
- Compatibility Certification — **20/20 runtime cells + Compatibility Gate SUCCESS**;
- Provider Sandbox Certification — **SUCCESS**;
- WordPress.org Submission Check — **SUCCESS**;
- Release Artifact — **SUCCESS**;
- CodeQL — **SUCCESS**;
- deterministic installable package — **51 files**, SHA-256 `a343a028e42f17a8d111de4e1a271c4e4bd51ba8cbbc1f0de89652b0d2480455`.

That checkpoint contains bounded post-T3 correctness work plus the accessibility markup slice. Accessibility followed genuine TDD:

- RED test `435f9ef1fddd6d54a7a066d1d764ce24bd77ba11`;
- RED failed at the intended toast live-region assertion;
- minimal markup implementation `b06976ac689cfd0d469bd96b0d6a2b925c863747`;
- exact-head full-stack GREEN followed.

## R0 anti-staleness evidence

R0 deliberately introduced a permanent current-state regression rather than silently editing prose.

- Initial RED `f57518db74fa3c35c313cb3c1997663b85168d3b`: 194 tests / 1,183 assertions / 3 intended stale-state failures.
- Expanded RED `55f9c8511ed7d322b6fb31358277e760140f834b`: 196 tests / 1,189 assertions / 5 intended failures after adding `AGENTS.md` and `START-HERE.md` to the living-authority coverage.

The permanent anti-staleness regression must remain GREEN after reconciliation. The live PR #108 exact head and checks determine whether R0 is under certification or closed; this document must not substitute for live evidence.

## Remaining post-T3 program

The remaining program is not finished. Sequence it under the canonical post-T3 plan:

1. **R0 — control-plane reconciliation:** keep living handoffs/architecture/PR ledger aligned to T3/current work and clean only proven-safe stale branches.
2. **R1 — bounded local correctness:** finish remaining raw-input/zero-charge characterization and focused correctness/presentation/accessibility issues without broad architecture changes.
3. **E1 — interactive checkout compatibility:** Classic, Store API, `wc-ajax`, `admin-ajax`, REST/sessionless, embedded/custom checkout, reactive Blocks state, fragment replacement, notice/session isolation, duplicate registration/ID safety.
4. **E2 — economics/product compatibility:** finalized Woo order amount/currency authority, zero-total behavior, coupons, fees, taxes, shipping, dynamic pricing, add-ons, bundles/composites/mix-and-match/measurements/gift cards/deposits.
5. **E3 — theme/cache/analytics compatibility:** theme, cache/minification/defer/consent/analytics interactions with evidence-based scope.
6. **R2 — callback portability/cache safety:** WC API URL abstraction, `home_url` vs `site_url`, subdirectories, permalink/proxy behavior and no-cache semantics.
7. **R3 — subscription safety:** explicit selected-card binding, no first-card fallback, parent/cancellation/token/held-cycle/economic invariants.
8. **R4 — scalability/operations:** Action Scheduler due work, durable idempotency/journal semantics, observability, load/concurrency/failure injection.
9. **R5 / T4 — callback consolidation decision:** **separately gated architecture decision**. T3 characterization is evidence, not implementation authorization.
10. **R6 — final release qualification:** exact-head full gates, broad/manual qualification, fresh owner re-acceptance, explicit version/publication decision.

## Permanent payment/security invariants

1. Routing input is never financial truth.
2. Charge initialization is not capture.
3. Captured/paid state requires authenticated provider status bound to the correct attempt/order/economics.
4. Finalized WooCommerce order amount/currency is authoritative; provider `products[]` is descriptive.
5. Shipping, tax, fees, coupons and order-bump economics must be finalized before Charge; no post-dispatch economic mutation may silently alter authority.
6. Non-idempotent charge/refund/auto-deduct operations are never blindly retried.
7. Identity ambiguity fails closed.
8. Protected provider/persisted identities do not change without an approved migration.
9. Named third-party checkout/product compatibility is behaviorally characterized before special-case code is introduced.
10. Runtime corrections require TDD and exact-head recertification.

## Protected compatibility identities

Protected contracts include gateway/payment identity `upayments`, `woocommerce_upayments_settings`, Blocks identity `upayments`, callback `wc_upayments`, historical `_upay_*` metadata, `UPayments_order_id`, H12 token/provenance/scope/generation state, subscription/billing-attempt identities, historical order-payment values, frozen Phase 9I identities, `getAPIUrlForRetreiveCards()`, and normalized `whitelabled` compatibility shape.

## Repository/release governance

The Main Rule must continue to require squash-only linear history, review-thread resolution, deletion/non-fast-forward protection, no bypass actors, and strict checks:

- `Governance`
- `H12 Regression Harness`
- `Compatibility Gate`
- `Release Gate`

CodeQL/security, Provider Sandbox, WordPress.org Submission Check and deterministic release evidence remain part of exact-head qualification where applicable.

Tags, GitHub Releases and WordPress.org publication remain prohibited until explicit owner authorization. Live branch/PR/issue/tag/release state must be rechecked at every release boundary.

## Evidence boundaries

Repository certification does not replace real production payment completion, real wallet/device completion, WPML/WCML/multilingual/multicurrency/RTL qualification, broad browser/theme/accessibility testing, representative load testing, penetration/PCI/legal attestation, or live non-idempotent subscription auto-deduction evidence.

Automatic WooCommerce refunds and arbitrary marketplace multi-split remain unsupported.

See [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md), [`NEW-CHAT-HANDOFF.md`](NEW-CHAT-HANDOFF.md), [`../COMPATIBILITY.md`](../COMPATIBILITY.md), and [`RELEASE-ENGINEERING.md`](RELEASE-ENGINEERING.md) for operational detail.
