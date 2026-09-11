# SUPCheckout for UPayments — Project Status

**Status document:** canonical living engineering state
**Last reconciled:** 2026-09-11
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
| Product family | **SUPCheckout** |
| Technical slug / text domain | `supcheckout` |
| PHP namespace | `Simplixi\SUPCheckout` |
| Package root | `supcheckout/` |
| First-stable bootstrap | `supcheckout/UPayments.php` — intentional compatibility exception |
| Provider scope | **UPayments only** |
| Approach 2 | **DONE / VERIFIED / OWNER ACCEPTED** |
| Final pre-clone runtime/QA closure | **DONE / VERIFIED — PR #75** |
| Quality Platform Q1-Q19 | **DONE / VERIFIED — permanently closed at Q19** |
| Approach 3 T1 | **DONE / VERIFIED** |
| Approach 3 T2 | **DONE / VERIFIED — runtime-bearing** |
| Approach 3 T3 | **DONE / VERIFIED — runtime-neutral** |
| Latest merged Approach 3 main coordinate | **`a7a8bbfc3a1dc551127b7ead897c964e95c7cec9`** |
| Active successor program | **`post-t3-ecosystem-hardening` — PR #108** |
| Latest fully certified runtime-content PR checkpoint | **`5d8d954ffb8c2e6646dadc4aa1f994b5d56c462e`** |
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

Latest fully certified generic/core E2 checkpoint:

`5d8d954ffb8c2e6646dadc4aa1f994b5d56c462e`

At that exact SHA:

- Quality Gates / H12 — **SUCCESS**;
- Compatibility Certification — **20/20 runtime cells + Compatibility Gate SUCCESS**;
- Provider Sandbox Certification — **SUCCESS**;
- WordPress.org Submission Check — **SUCCESS**;
- Release Artifact — **SUCCESS**;
- CodeQL — **SUCCESS**;
- deterministic installable package — **55 files**, SHA-256 `470db11afb2869bc187f0e920aeae4de02e92f6c1f7ca478ef5cb2ac62151b74`.

R0, R1 and E1 are **DONE / VERIFIED on the PR #108 branch**. Generic/core E2 is **CERTIFIED** at this exact checkpoint. The earlier `b06976ac689cfd0d469bd96b0d6a2b925c863747` accessibility checkpoint remains historical evidence inside this descendant.

That earlier checkpoint contains bounded post-T3 correctness work plus the accessibility markup slice. Accessibility followed genuine TDD:

- RED test `435f9ef1fddd6d54a7a066d1d764ce24bd77ba11`;
- RED failed at the intended toast live-region assertion;
- minimal markup implementation `b06976ac689cfd0d469bd96b0d6a2b925c863747`;
- exact-head full-stack GREEN followed.

## R0 anti-staleness evidence

R0 deliberately introduced permanent current-state regression rather than silently editing prose.

- Initial RED `f57518db74fa3c35c313cb3c1997663b85168d3b`: 194 tests / 1,183 assertions / 3 intended stale-state failures.
- Expanded RED `55f9c8511ed7d322b6fb31358277e760140f834b`: 196 tests / 1,189 assertions / 5 intended failures after adding `AGENTS.md` and `START-HERE.md` to the living-authority coverage.
- Reconciled candidate `98c8032a36bbae80648dc6271fcee811e2d6cad2`: full exact-head Quality/H12, Compatibility, Provider Sandbox, WordPress.org, Release Artifact and CodeQL stack succeeded; artifact inspection then exposed the stale b069 file-count claim.
- Package-evidence RED `6db89738b861a7f98094dc4d87bda101e1730d55`: 198 tests / 1,228 assertions / exactly 1 intended failure against the stale 51-file b069 statement while Governance and syntax lanes remained clean.

The permanent anti-staleness/package-evidence regression must remain GREEN after reconciliation. Live PR #108 exact head and checks determine current state; this document must not substitute for live evidence.

## Remaining post-T3 program

The overall post-T3 program is **not finished**.

Current executable gate: **E2 residual ecosystem economics / extension-generated contracts**.

1. **E2 residual ecosystem economics:** real Woo coupon variants; payable fully-discounted product lines with shipping/tax; add-on/options and parent/child composition shells; gift-card/store-credit partial redemption; deposits/initial partial payment; broader shipping/tax/VAT recalculation variants; persisted high-precision economics; post-dispatch economic mutation; partial-refund semantics; explicit fee-only/non-product-line policy. Paid/licensed named extensions remain external until legally available.
2. **E3 — theme/cache/analytics compatibility:** behavioral qualification across available Classic/block themes, optimizer/cache/CDN behavior, fragment/defer/delay interactions and analytics return/replay semantics. Paid themes/plugins remain external where packages are unavailable.
3. **R2 — callback portability/cache safety:** Woo API URL abstraction, `home_url` vs `site_url`, subdirectories, permalink/index/proxy behavior, no-cache callback/public-status semantics and replay freshness.
4. **R3 — subscription safety:** exact auto-deduct amount/currency/parent/cycle/provider binding, removal of first-card fallback, paid-parent discovery beyond `completed`, customer control policy, token-retention contract and held-cycle reconciliation.
5. **R4 — scalability/operations:** due-work scheduling/Action Scheduler, bounded batches, durable idempotency/journal semantics, observability, load/concurrency/failure injection and queue health.
6. **R5 / T4 — callback consolidation decision:** **separately gated architecture decision**. T3 characterization is evidence, not implementation authorization.
7. **R6 — final release qualification:** exact-head full gates, browser/device/manual/external qualification, fresh owner re-acceptance, explicit version/publication decision.

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
