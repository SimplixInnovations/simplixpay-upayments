# Approach 3 coding handoff

## Goal

Begin Approach 3 with a runtime-neutral safety tranche that freezes the actual active callback topology and provider-egress/security boundaries before any payment-authority code is modified.

## Required baseline

- Live main at architecture record time: `0b153c661314669924c81e89fd71bfd2c734310b`
- Frozen owner-accepted Approach 2 baseline: `0c883d609906676966002eb022a82a9656eeacc5`
- Accepted package SHA-256: `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`
- Accepted package file count: `51`
- `UPayments.php`: exactly `87,995` bytes

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
12. Provider HTTP egress locations remain exactly the accepted set:
    - gateway transport;
    - StatusVerifier;
    - protected Scheduler renewal dispatch.

### Test design

The dependency harness should be token-aware/static where practical and must not depend on harmless formatting.

The callback characterization harness must execute behavior using controlled WordPress/WooCommerce doubles/stubs. It must go RED if routing priority, termination semantics, authority path, or expected state/redirect behavior is broken.

### Package invariants

Because T1 creates no packaged runtime file:

- ZIP file count remains `51`;
- ZIP SHA-256 remains `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`;
- `UPayments.php` remains exactly `87,995` bytes.

### Verification

Require all repository gates triggered by the PR and inspect each required check individually. Do not merge with failures, pending required checks, unresolved valid review threads, package drift, or unexpected runtime-file changes.

## T1 completion evidence

**Status: DONE / VERIFIED.**

- PR #102 certified exact head `aed57ca4ce3402362de65f604756bde5c256385c` with 40/40 successful checks.
- Dependency-boundary harness: 11 PASS / 0 FAIL.
- Active-callback characterization harness: 41 PASS / 0 FAIL.
- Squash-merged main: `beb89ac0c4d8c0e9b7c8b2de1e13c237bbd37b15`, GitHub signature verified.
- PR-head and merged-main Git trees are identical: `d158ec92ab83e849e5325cfa1dad4a01b5842efd`.
- All 28 checks triggered on merged main succeeded.
- Canonical/Linux/Windows release evidence remained exactly 51 files with SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`.
- Runtime/package source changes in T1: zero.

## Next runtime decision after T1

Do not automatically create new Return/Webhook controllers.

First compare:

- retaining compatibility gateway methods as thin delegates to `PaymentLifecycle`;
- consolidating legacy private verification onto the active `StatusVerifier`/lifecycle path;
- only then, if evidence justifies it, extracting a concrete UPayments transport without erasing endpoint-specific status security policy.

The default desired result is **legacy callback consolidation behind preserved public compatibility methods**, not a parallel controller architecture.


## T2 — legacy callback routing consolidation

**Status: DONE / VERIFIED.**

- Base main: `97bc88518550d02e4c9f38f766b583dd78e88986`.
- PR #104 exact certified head: `4cff2dc6e6d11a4b3232a6d3d70d6280a59741c4`.
- TDD RED head: `09b2c1758652ce57fb5ef39008b168dad347e927`; the unchanged gateway reached the browser/webhook/public-status legacy sentinels with empty stderr.
- GREEN implementation: only `WC_Upayments::check_ipn_response()` was changed to delegate to `PaymentLifecycle::handle_callback()`, retaining terminal `exit()`.
- Direct legacy public methods `return_from_upayments()`, `web_hook_handler()` and private `verify_payment_status()` were not modified.
- Exact-head PR certification: **42/42 SUCCESS**.
- T1 dependency/provider-egress guardrail: **11 PASS / 0 FAIL**.
- T1 active callback characterization: **41 PASS / 0 FAIL**.
- T2 direct-entrypoint characterization: **25 PASS / 0 FAIL**.
- Gateway architecture ratchet: **87,724 bytes** (from historical Approach 2/T1 87,995 bytes).
- Candidate package: **51 files**, canonical/Linux/Windows SHA-256 `368aaa5cb1a75e6df41ff17bb2e2126431b49da5e6b8dfe508c718433009fc04`.
- Squash-merged main: `047cc86060efb97761d7a0cc4a3806f971ab6fe1`; merge commit verified and Git-tree identical to the certified PR head.
- Fresh merged-main certification: **41/41 SUCCESS**.
- External Codex review was requested but unavailable because the account code-review quota was exhausted; no independent Codex approval is claimed. GitHub had zero unresolved review conversations and a separate exact-diff coordinator audit found exactly one production file changed and no protected identity migration.
- Frozen owner-accepted Approach 2 baseline/package remain unchanged until Approach 3 closeout owner re-acceptance.
- Public release remains not authorized.

## Next decision after T2

Do not delete or rewrite the direct public legacy callback methods by assumption. The next tranche must first characterize their direct compatibility behavior and call surface, including the old private verification path, then decide whether any further consolidation has positive risk-adjusted value. Provider transport extraction remains unapproved.
