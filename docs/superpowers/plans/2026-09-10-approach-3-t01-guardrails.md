# Approach 3 T1 Guardrails Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [x]`) syntax for tracking.

**Goal:** Add permanent, runtime-neutral architecture guardrails and executable characterization for SUPCheckout's already-active `PaymentLifecycle` callback strangler.

**Architecture:** Preserve the owner-accepted runtime unchanged. T1 records and tests the real priority-5 `PaymentLifecycle` callback path, the shadowed priority-10 gateway compatibility path, and the three accepted provider HTTP egress implementations. No packaged runtime source may change.

**Tech Stack:** PHP 7.4-compatible harnesses, WordPress/WooCommerce test doubles, GitHub Actions Quality Gates.

**Spec:** `.ai-architect/decisions/ADR-001-approach-3-strangler-modernization.md`

## Global Constraints

- Frozen owner-accepted Approach 2 baseline: `0c883d609906676966002eb022a82a9656eeacc5`.
- Accepted package SHA-256: `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`.
- Accepted package file count: `51`.
- `UPayments.php` must remain exactly `87,995` bytes.
- PHP floor remains `>= 7.4`.
- No runtime source changes.
- No protected identity changes.
- No new provider HTTP egress.
- No Q20.

## Task 1 — Add dependency-boundary ratchet

- [x] Create `tests/harness/architecture-dependency-boundaries-harness.php`.
- [x] Assert the accepted provider HTTP egress set exactly: gateway transport, `StatusVerifier::verify`, Scheduler renewal dispatch.
- [x] Assert no additional production `wp_remote_*` provider egress appears outside the explicit exception set.
- [x] Assert `PaymentLifecycle` remains bootstrapped from `Release\Identity`.
- [x] Assert `woocommerce_api_wc_upayments` priority-5 lifecycle registration remains.
- [x] Assert the legacy gateway callback registration remains present.
- [x] Verify the harness can fail when a controlled fixture/source mutation violates one of its contracts, then restore.
- [x] Run it against the real candidate and confirm PASS.

## Task 2 — Add executable active-callback characterization

- [x] Create `tests/harness/architecture-active-callback-characterization-harness.php`.
- [x] Build controlled WordPress/WooCommerce hook and request doubles.
- [x] Characterize public status routing.
- [x] Characterize browser callback routing into `PaymentLifecycle::process_order_status`.
- [x] Characterize webhook routing into `PaymentLifecycle::process_order_status`.
- [x] Characterize terminal redirect/response behavior and prove the priority-5 handler terminates before legacy priority 10 under the canonical route.
- [x] Inventory/pin legacy method identities separately as retained compatibility surfaces; direct behavioral characterization is explicitly deferred and required before those public methods are modified.
- [x] Verify a controlled contract break goes RED, restore, then verify GREEN.

## Task 3 — Register permanent gates

- [x] Update `.github/workflows/quality-gates.yml` to execute both new harnesses in the existing architecture/permanent-control section.
- [x] Do not alter the compatibility-matrix workflow unless a runtime-matrix-specific need is proven.
- [x] Run/inspect all locally available static checks for the changed files.

## Task 4 — Verify package and baseline invariants

- [x] Confirm changed filenames are tests/control/workflow only.
- [x] Confirm `UPayments.php` remains exactly `87,995` bytes.
- [x] Build the deterministic release artifact.
- [x] Verify exactly `51` files.
- [x] Verify SHA-256 remains `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`.

## Task 5 — PR certification and merge

- [x] Open one T1 PR from the exact current `main`.
- [x] Inspect every changed filename and diff.
- [x] Require Governance, H12 Regression Harness, Compatibility Gate, Release Gate and all triggered security/release checks to succeed.
- [x] Require zero unresolved valid review threads.
- [x] Reconfirm package-byte identity from CI artifact/sidecars.
- [x] Squash merge only.
- [x] Verify post-merge `main`, topology, checks and package invariants.
- [x] Update living status only if T1 completion changes the recorded Approach 3 state.


## Completion evidence

**DONE / VERIFIED — PR #102.**

- Certified PR head: `aed57ca4ce3402362de65f604756bde5c256385c` — 40/40 checks successful.
- Dependency boundaries: 11 PASS / 0 FAIL.
- Active callback characterization: 41 PASS / 0 FAIL.
- Merged main: `beb89ac0c4d8c0e9b7c8b2de1e13c237bbd37b15` — verified GitHub signature.
- PR-head tree and merged-main tree: identical `d158ec92ab83e849e5325cfa1dad4a01b5842efd`.
- Post-merge checks triggered on main: 28/28 successful.
- Package remained exactly 51 files, SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`.
- No production/runtime source file changed in T1.
