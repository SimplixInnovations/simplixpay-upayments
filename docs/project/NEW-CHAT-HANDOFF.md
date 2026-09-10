# SUPCheckout for UPayments — Continuation Handoff

**Mandatory first step:** read [`START-HERE.md`](START-HERE.md), then verify live GitHub/source/check state. Chat memory is not authority.

Use this with [`AGENTS.md`](../../AGENTS.md), [`PROJECT-STATUS.md`](PROJECT-STATUS.md), [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md), [`NAMING-IDENTITY-STANDARD.md`](NAMING-IDENTITY-STANDARD.md), [`../COMPATIBILITY.md`](../COMPATIBILITY.md), and [`RELEASE-ENGINEERING.md`](RELEASE-ENGINEERING.md).

## Identity

- Product: **SUPCheckout for UPayments**
- Provider: **UPayments**
- Repository: `SimplixInnovations/supcheckout`
- Technical slug / text domain: `supcheckout`
- PHP namespace: `Simplixi\SUPCheckout`
- Package root/bootstrap: `supcheckout/UPayments.php`
- Development version: `0.1.0`

Protected compatibility identities are contracts. Do not mechanically rename `upayments`, `woocommerce_upayments_settings`, `wc_upayments`, `_upay_*`, `UPayments_order_id`, H12 token/provenance identities, subscription/billing-attempt identities, historical payment-method values, frozen Phase 9I identities, `getAPIUrlForRetreiveCards()`, or `whitelabled`.

## Frozen regression baseline

Owner-accepted Approach 2:

- source SHA: `0c883d609906676966002eb022a82a9656eeacc5`;
- package: `supcheckout-0.1.0.zip`;
- files: 51;
- SHA-256: `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`.

Do not silently move this acceptance anchor. A fresh explicit owner acceptance is required at Approach 3 closeout.

## Current Approach 3 state

- T1 — **DONE / VERIFIED**.
- T2 — **DONE / VERIFIED / runtime-bearing**, merged main `047cc86060efb97761d7a0cc4a3806f971ab6fe1`.
- T3 — **DONE / VERIFIED / runtime-neutral**, certified PR #107 head `f7c7d596dc4a2c8464d1acdf13dfe51028a5f9e0`, merged main `a7a8bbfc3a1dc551127b7ead897c964e95c7cec9`.
- Current post-T3 program — **IN PROGRESS**, plan `docs/superpowers/plans/2026-09-10-post-t3-ecosystem-hardening.md`, active draft PR #108 on `audit/post-t3-ecosystem-hardening`.
- Public tag / GitHub Release / WordPress.org publication — **NOT AUTHORIZED**.

T3 characterized the direct legacy return/webhook/private verifier. It also proved a direct `return_from_upayments()` caller may not carry the GET `page` marker used by `PaymentLifecycle::handle_callback()` for browser-mode inference. **T4 consolidation is therefore a separate architecture gate, not an automatic next refactor.**

## Latest fully certified runtime-content checkpoint

`b06976ac689cfd0d469bd96b0d6a2b925c863747`

At that exact SHA:

- Quality Gates — **SUCCESS**;
- Compatibility Certification — **20/20 runtime cells + Compatibility Gate SUCCESS**;
- Provider Sandbox Certification — **SUCCESS**;
- WordPress.org Submission Check — **SUCCESS**;
- Release Artifact — **SUCCESS**;
- CodeQL — **SUCCESS**;
- deterministic package — 51 files / SHA-256 `a343a028e42f17a8d111de4e1a271c4e4bd51ba8cbbc1f0de89652b0d2480455`.

Accessibility TDD evidence at that checkpoint:

- RED test `435f9ef1fddd6d54a7a066d1d764ce24bd77ba11` failed at the intended toast live-region assertion;
- minimal markup implementation `b06976ac689cfd0d469bd96b0d6a2b925c863747`;
- exact-head full-stack GREEN followed across the real-runtime matrix.

## R0 control-plane evidence

R0 deliberately introduced permanent anti-staleness regression coverage.

- Initial RED: `f57518db74fa3c35c313cb3c1997663b85168d3b` — 194 tests / 1,183 assertions / 3 intended stale-state failures.
- Expanded RED: `55f9c8511ed7d322b6fb31358277e760140f834b` — 196 tests / 1,189 assertions / 5 intended failures after adding `START-HERE.md` and `AGENTS.md` to the living-authority ratchet.

The permanent regression must remain green after reconciliation. Do not remove or weaken it. Live PR #108/head/check state determines whether R0 is currently under certification or closed.

## Remaining execution order

1. Finish/certify **R0** and clean only proven-safe stale branches.
2. Finish remaining **R1** bounded correctness characterization, including raw-input/zero-charge gaps where still unproven.
3. Execute **E1** interactive checkout compatibility: Classic, Store API, `wc-ajax`, `admin-ajax`, REST/sessionless, embedded/custom checkout, Blocks reactive state, fragments, notice/session isolation, duplicate-ID/registration prevention.
4. Execute **E2** economics/product compatibility with finalized Woo order amount/currency as authority and explicit zero-total handling.
5. Execute **E3** theme/cache/analytics interaction evidence.
6. Execute **R2** callback URL portability/no-cache safety.
7. Execute **R3** subscription safety under the approved post-T3 plan.
8. Execute **R4** scalability/idempotency/observability/load work.
9. Gate **R5/T4** architecture separately; do not implement merely because characterization exists.
10. Execute **R6** exact-head release qualification, fresh owner re-acceptance, version decision, then publication only with explicit authorization.

## Permanent invariants

- Browser/provider routing data is not payment truth.
- Charge creation is not capture.
- Paid state requires authenticated provider verification bound to the correct order/attempt/economics.
- Finalized Woo order amount/currency is authoritative; `products[]` is descriptive.
- No blind retry of non-idempotent Charge/refund/auto-deduct mutations.
- Ambiguous identity fails closed.
- Protected identities require explicit migration contracts.
- Named ecosystem integrations are behaviorally characterized before special-case code.
- Runtime fixes use RED → minimal GREEN → exact-head full recertification.

## Main-rule/release controls

Required protected-branch checks remain:

- `Governance`
- `H12 Regression Harness`
- `Compatibility Gate`
- `Release Gate`

Squash-only linear history, review-thread resolution, deletion/non-fast-forward protection and no bypass actors must remain enforced.

Fresh live checks must also account for CodeQL/security, Provider Sandbox, WordPress.org Submission Check and deterministic cross-platform release evidence.

## External/manual boundary

Do not claim repository automation proves production payment completion, real wallet/device behavior, WPML/WCML/multilingual/multicurrency/RTL, broad browser/theme/accessibility, representative load, penetration/PCI/legal attestation, or live non-idempotent subscription auto-deduction. Record those honestly at R6.

Do not invent Q20. Quality Platform Q1-Q19 is closed permanently.
