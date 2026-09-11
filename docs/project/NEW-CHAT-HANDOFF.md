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

`for` is human-facing relationship wording only. Never encode it into repository, package, WordPress.org, namespace, REST, CSS/JS or artifact identifiers.

Protected compatibility identities are contracts. Do not mechanically rename `upayments`, `woocommerce_upayments_settings`, `wc_upayments`, `_upay_*`, `UPayments_order_id`, H12 token/provenance identities, subscription/billing-attempt identities, historical payment-method values, frozen Phase 9I identities, `getAPIUrlForRetreiveCards()`, or `whitelabled`.

## Frozen regression baseline

Owner-accepted Approach 2:

- source SHA: `0c883d609906676966002eb022a82a9656eeacc5`;
- package: `supcheckout-0.1.0.zip`;
- files: 51;
- SHA-256: `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`.

Do not silently move this acceptance anchor. A fresh explicit owner acceptance is required at Approach 3 closeout.

## Historical closed milestones retained as regression evidence

- Quality Platform Q1-Q19 — **DONE / VERIFIED; permanently closed at Q19**
- Enterprise Tasks 1-8 — **DONE / VERIFIED**
- Final pre-clone runtime/QA closure — **DONE / VERIFIED — PR #75**

These remain historical closure facts while the active post-T3 program advances. Do not rewrite or delete them when reconciling current coordinates.

## Current Approach 3 state

- T1 — **DONE / VERIFIED**.
- T2 — **DONE / VERIFIED / runtime-bearing**, merged main `047cc86060efb97761d7a0cc4a3806f971ab6fe1`.
- T3 — **DONE / VERIFIED / runtime-neutral**, certified PR #107 head `f7c7d596dc4a2c8464d1acdf13dfe51028a5f9e0`, merged main `a7a8bbfc3a1dc551127b7ead897c964e95c7cec9`.
- Current post-T3 program — **IN PROGRESS**, plan `docs/superpowers/plans/2026-09-10-post-t3-ecosystem-hardening.md`, active draft PR #108 on `audit/post-t3-ecosystem-hardening`.
- Public tag / GitHub Release / WordPress.org publication — **NOT AUTHORIZED**.

T3 characterized the direct legacy return/webhook/private verifier. It also proved a direct `return_from_upayments()` caller may not carry the GET `page` marker used by `PaymentLifecycle::handle_callback()` for browser-mode inference. **T4 consolidation is therefore a separate architecture gate, not an automatic next refactor.**

## Latest fully certified repository-executable generic E2 checkpoint

`27e90d5a0cd2ba4f7c38889dbef15d1be851efb6`

At that exact SHA:

- Quality Gates / H12 — **SUCCESS**;
- Compatibility Certification — **20/20 runtime cells + Compatibility Gate SUCCESS**;
- Provider Sandbox Certification — **SUCCESS**;
- WordPress.org Submission Check — **SUCCESS**;
- Release Artifact — **SUCCESS**;
- CodeQL — **SUCCESS**;
- deterministic package — 55 files / SHA-256 `dc31c02a8047f9e5120650a46b9c16413383d6644b29f569573739a08f3e7a2d`.

R0, R1 and E1 are **DONE / VERIFIED on PR #108 branch**. Repository-executable generic E2 is **DONE / CERTIFIED** at this checkpoint, including current-runtime legacy+HPOS Ecosystem Certification. Named paid/licensed vendor integrations remain external/unverified until separately qualified. The earlier b069 accessibility checkpoint remains retained historical evidence inside this certified descendant.

Accessibility TDD evidence at that earlier checkpoint:

- RED test `435f9ef1fddd6d54a7a066d1d764ce24bd77ba11` failed at the intended toast live-region assertion;
- minimal markup implementation `b06976ac689cfd0d469bd96b0d6a2b925c863747`;
- exact-head full-stack GREEN followed across the real-runtime matrix.

## R0 control-plane evidence

R0 deliberately introduced permanent anti-staleness regression coverage.

- Initial RED: `f57518db74fa3c35c313cb3c1997663b85168d3b` — 194 tests / 1,183 assertions / 3 intended stale-state failures.
- Expanded RED: `55f9c8511ed7d322b6fb31358277e760140f834b` — 196 tests / 1,189 assertions / 5 intended failures after adding `START-HERE.md` and `AGENTS.md` to the living-authority ratchet.
- Runtime-bearing-coordinate RED: `c4a57a9740db382654f0c5f26225bad88293ddf9` — 197 tests / 1,223 assertions / exactly 1 intended current-coordinate failure; a separate whitespace defect was also caught by Governance and repaired without weakening the gate.
- Reconciled candidate `98c8032a36bbae80648dc6271fcee811e2d6cad2` — full exact-head stack GREEN; artifact inspection then exposed the stale b069 file-count claim.
- Package-evidence RED: `6db89738b861a7f98094dc4d87bda101e1730d55` — 198 tests / 1,228 assertions / exactly 1 intended failure against the stale 51-file b069 statement.

The permanent regression must remain green after reconciliation. Do not remove or weaken it. Live PR #108/head/check state determines whether R0 is currently under certification or closed.

## Remaining execution order

Current executable gate: **E3 theme/cache/CDN/optimizer/analytics compatibility**.

1. Execute **E3** repository-owned theme/cache/CDN/optimizer/analytics behavior against legally available runtimes; keep unavailable paid/licensed named products explicitly external/unverified.
2. Execute **R2** callback URL portability/no-cache safety.
3. Execute **R3** subscription safety: exact cycle economics/identity, selected-card authority, no first-card fallback, parent discovery, cancellation/control and held-cycle reconciliation.
4. Execute **R4** due-work scheduling, idempotency/journal, observability, load/concurrency and failure-injection hardening.
5. Gate **R5/T4** architecture separately; do not implement merely because characterization exists.
6. Execute **R6** exact-head release qualification, fresh owner re-acceptance and version decision; publication requires separate explicit authorization.

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

Do not claim repository automation proves production merchant payment completion, real wallet/device behavior, WPML/WCML/multilingual/multicurrency/RTL, broad browser/theme/accessibility, representative load, penetration/PCI/legal attestation, or live non-idempotent subscription auto-deduction. Record those honestly at R6.

Do not invent Q20. Quality Platform Q1-Q19 is closed permanently.
