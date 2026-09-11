# Approach 3 coding handoff

## Goal

Begin Approach 3 with a runtime-neutral safety tranche that freezes the actual active callback topology and provider-egress/security boundaries before any payment-authority code is modified.

## Required baseline

- Live main at architecture record time: `0b153c661314669924c81e89fd71bfd2c734310b`
- Frozen owner-accepted Approach 2 baseline: `0c883d609906676966002eb022a82a9656eeacc5`
- Accepted package SHA-256: `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`
- Accepted package file count: `51`
- Historical T1 `UPayments.php`: exactly `87,995` bytes

The accepted Approach 2 source/package remains the regression authority until a fresh explicit Approach 3 closeout acceptance.

## T1 — architecture guardrails and active callback characterization

### Scope

Control-plane/tests only.

Expected touched files:

- add a durable dependency-boundary harness under `tests/harness/`;
- add an executable active-callback characterization harness under `tests/harness/`;
- register them in `.github/workflows/quality-gates.yml`;
- update architecture/living docs only if required to make the new permanent controls discoverable.

### Explicit non-scope

Do not modify:

- `UPayments.php`;
- `src/Payment/PaymentLifecycle.php`;
- `src/Payment/StatusVerifier.php`;
- `src/Payment/CheckoutOrchestrator.php`;
- `src/Payment/CheckoutPayload.php`;
- `src/Payment/OrderLock.php`;
- `includes/Token/CustomerTokenIdentity.php`;
- `includes/Subscription/Cron/Scheduler.php`;
- `includes/Subscription/Cron/CycleClaim.php`;
- any provider/persisted identity;
- Composer runtime dependencies.

### Required characterization

1. `UPayments.php` loads `Release\Identity`.
2. `Release\Identity` bootstraps `PaymentLifecycle` when hooks are available.
3. `PaymentLifecycle` registers `woocommerce_api_wc_upayments` at priority `5`.
4. `WC_Upayments::check_ipn_response()` remains registered at default priority `10`.
5. Public `get_order_status` routing goes through `PublicOrderStatus` and terminates.
6. Browser callback routing goes through `PaymentLifecycle::process_order_status()`.
7. Webhook callback routing goes through `PaymentLifecycle::process_order_status()`.
8. `PaymentLifecycle::finish_callback()` terminates the request so the legacy priority-10 callback is normally shadowed on the canonical route.
9. Active lifecycle status verification uses `StatusVerifier::verify()`.
10. Active lifecycle concurrency protection uses `OrderLock`.
11. Legacy gateway return/webhook/private verification method identities remain present as compatibility surfaces and are inventoried separately; their direct behavior is not characterized by T1 and must be characterized before those methods are modified. They are not described as the primary callback runtime path.
12. Provider HTTP egress locations remain exactly the accepted set: gateway transport, `StatusVerifier`, protected Scheduler renewal dispatch.

### Test design

The dependency harness should be token-aware/static where practical and must not depend on harmless formatting. The callback characterization harness must execute behavior using controlled WordPress/WooCommerce doubles/stubs and go RED if routing priority, termination semantics, authority path, or expected state/redirect behavior breaks.

### Package invariants

Because T1 created no packaged runtime file:

- ZIP file count remained `51`;
- ZIP SHA-256 remained `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`;
- `UPayments.php` remained exactly `87,995` bytes.

### T1 completion evidence

**Status: DONE / VERIFIED.**

- PR #102 certified exact head `aed57ca4ce3402362de65f604756bde5c256385c` with 40/40 successful checks.
- Dependency-boundary harness: 11 PASS / 0 FAIL.
- Active-callback characterization harness: 41 PASS / 0 FAIL.
- Squash-merged main: `beb89ac0c4d8c0e9b7c8b2de1e13c237bbd37b15`.
- PR-head and merged-main Git trees were identical.
- Runtime/package source changes in T1: zero.

## Historical next decision after T1 — satisfied by T2/T3

The approved direction was to compare retaining compatibility methods as thin delegates, consolidating legacy verification only where evidence justified it, and avoiding parallel Return/Webhook controller architecture. Provider transport extraction remained unapproved.

## T2 — legacy callback routing consolidation

**Status: DONE / VERIFIED.**

- Base main: `97bc88518550d02e4c9f38f766b583dd78e88986`.
- PR #104 exact certified head: `4cff2dc6e6d11a4b3232a6d3d70d6280a59741c4`.
- TDD RED head: `09b2c1758652ce57fb5ef39008b168dad347e927`; the unchanged gateway reached the browser/webhook/public-status legacy sentinels with empty stderr.
- GREEN implementation: only `WC_Upayments::check_ipn_response()` changed to delegate to `PaymentLifecycle::handle_callback()`, retaining terminal `exit()`.
- Direct `return_from_upayments()`, `web_hook_handler()` and private `verify_payment_status()` were not modified.
- Exact-head PR certification: **42/42 SUCCESS**.
- T1 dependency/provider-egress guardrail: **11 PASS / 0 FAIL**.
- T1 active callback characterization: **41 PASS / 0 FAIL**.
- T2 direct-entrypoint characterization: **25 PASS / 0 FAIL**.
- Gateway architecture ratchet: **87,724 bytes** from historical 87,995.
- Candidate package: **51 files**, canonical/Linux/Windows SHA-256 `368aaa5cb1a75e6df41ff17bb2e2126431b49da5e6b8dfe508c718433009fc04`.
- Squash-merged main: `047cc86060efb97761d7a0cc4a3806f971ab6fe1`.
- Fresh merged-main certification: **41/41 SUCCESS**.
- Frozen owner-accepted Approach 2 baseline/package remained unchanged.
- Public release remained unauthorized.

## Historical next decision after T2 — satisfied by T3

T2 explicitly required direct behavioral characterization of `return_from_upayments()`, `web_hook_handler()` and the private legacy verifier before any further consolidation. T3 completed that evidence requirement without changing runtime behavior.

## T3 — legacy direct callback/private-verifier characterization

**Status: DONE / VERIFIED — RUNTIME-NEUTRAL.**

- Original base: `348fd1e7493b97dee37e9839569ba2a7f7a3ab32`.
- PR #107 exact certified head: `f7c7d596dc4a2c8464d1acdf13dfe51028a5f9e0`.
- Squash-merged main: `a7a8bbfc3a1dc551127b7ead897c964e95c7cec9`.
- Permanent characterization executes the real direct browser-return method, direct webhook method and private historical verifier against controlled WooCommerce/provider doubles.
- It covers local-preflight failures, provider/transport/binding/economic failures, captured and non-captured outcomes, failed Woo status transitions, verified-capture replay and refunded orders.
- T3 changed no production runtime file and therefore retained the T2 deterministic candidate package at 51 files / SHA-256 `368aaa5cb1a75e6df41ff17bb2e2126431b49da5e6b8dfe508c718433009fc04`.
- Fresh merged-main Quality Gates, Compatibility Certification and CodeQL succeeded.

### T3 compatibility constraint

A direct caller of `return_from_upayments()` does not necessarily arrive through the canonical WC-API router with a GET `page` marker. `PaymentLifecycle::handle_callback()` currently infers browser mode from that marker. Therefore T3 does **not** authorize replacing the direct legacy browser method with an unqualified call to `PaymentLifecycle::handle_callback()`.

## Current successor — post-T3 ecosystem hardening

The current approved successor is:

**`post-t3-ecosystem-hardening`**

Canonical plan:

`docs/superpowers/plans/2026-09-10-post-t3-ecosystem-hardening.md`

Active integration PR: #108 / `audit/post-t3-ecosystem-hardening`.

Execution state:

- R0 — **DONE / VERIFIED** on PR #108 branch.
- R1 — **DONE / VERIFIED** on PR #108 branch.
- E1 — **DONE / VERIFIED** on PR #108 branch.
- E2 generic/core — **CERTIFIED**.

Generic/core E2 exact-head checkpoint: `5d8d954ffb8c2e6646dadc4aa1f994b5d56c462e`.

At that exact head, Quality/H12, all 20 Compatibility cells + Compatibility Gate, Provider Sandbox, WordPress.org Submission Check, Release Artifact and CodeQL were successful; deterministic candidate package: 55 files / SHA-256 `470db11afb2869bc187f0e920aeae4de02e92f6c1f7ca478ef5cb2ac62151b74`.

Current executable gate: **E2 residual ecosystem economics / extension-generated contracts**. Then execute E3 theme/cache/minification/defer/consent/analytics interaction evidence; R2 callback URL portability/no-cache safety; R3 subscription selected-card/parent/cancellation/token/economic/held-cycle safety; R4 Action Scheduler/idempotency/journal/observability/load/concurrency/failure-injection hardening; separately gate R5/T4 callback consolidation; and finish with R6 exact-head qualification plus fresh owner re-acceptance.

## Runtime implementation discipline

For every runtime correction:

1. reproduce/characterize;
2. add a meaningful RED test;
3. prove the RED fails for the intended reason;
4. implement the smallest correction;
5. run focused GREEN;
6. run the exact-head primary workflow stack plus CodeQL;
7. reconcile living records before closure claims.

Do not weaken payment/security assertions, create provider-special-case architecture without evidence, or broaden compatibility claims past the tested matrix.

## T4 gate

T4 callback consolidation is **not pre-approved by T3**. Before implementation:

- relevant E1/E2/R2 request-shape and callback evidence must be stable;
- compare preserving thin public adapters versus lifecycle consolidation;
- explicitly solve direct-browser request-shape normalization;
- preserve payment authority, idempotency and cache semantics;
- record/approve the architecture decision;
- then use TDD and full recertification.

## Approach 3 closeout

Approach 3 closes only after all approved tranches pass exact-head gates, protected identities remain intact, manual/external gaps are explicitly classified, a fresh-clone owner technical re-acceptance is completed, and a new accepted source/package coordinate is recorded.

Public release authorization remains separate and false until explicitly granted.
