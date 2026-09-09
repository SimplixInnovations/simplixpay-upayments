# Approach 3 T1 Guardrails Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

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

- [ ] Create `tests/harness/architecture-dependency-boundaries-harness.php`.
- [ ] Assert the accepted provider HTTP egress set exactly: gateway transport, `StatusVerifier::verify`, Scheduler renewal dispatch.
- [ ] Assert no additional production `wp_remote_*` provider egress appears outside the explicit exception set.
- [ ] Assert `PaymentLifecycle` remains bootstrapped from `Release\Identity`.
- [ ] Assert `woocommerce_api_wc_upayments` priority-5 lifecycle registration remains.
- [ ] Assert the legacy gateway callback registration remains present.
- [ ] Verify the harness can fail when a controlled fixture/source mutation violates one of its contracts, then restore.
- [ ] Run it against the real candidate and confirm PASS.

## Task 2 — Add executable active-callback characterization

- [ ] Create `tests/harness/architecture-active-callback-characterization-harness.php`.
- [ ] Build controlled WordPress/WooCommerce hook and request doubles.
- [ ] Characterize public status routing.
- [ ] Characterize browser callback routing into `PaymentLifecycle::process_order_status`.
- [ ] Characterize webhook routing into `PaymentLifecycle::process_order_status`.
- [ ] Characterize terminal redirect/response behavior and prove the priority-5 handler terminates before legacy priority 10 under the canonical route.
- [ ] Characterize legacy methods separately as compatibility surfaces.
- [ ] Verify a controlled contract break goes RED, restore, then verify GREEN.

## Task 3 — Register permanent gates

- [ ] Update `.github/workflows/quality-gates.yml` to execute both new harnesses in the existing architecture/permanent-control section.
- [ ] Do not alter the compatibility-matrix workflow unless a runtime-matrix-specific need is proven.
- [ ] Run/inspect all locally available static checks for the changed files.

## Task 4 — Verify package and baseline invariants

- [ ] Confirm changed filenames are tests/control/workflow only.
- [ ] Confirm `UPayments.php` remains exactly `87,995` bytes.
- [ ] Build the deterministic release artifact.
- [ ] Verify exactly `51` files.
- [ ] Verify SHA-256 remains `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`.

## Task 5 — PR certification and merge

- [ ] Open one T1 PR from the exact current `main`.
- [ ] Inspect every changed filename and diff.
- [ ] Require Governance, H12 Regression Harness, Compatibility Gate, Release Gate and all triggered security/release checks to succeed.
- [ ] Require zero unresolved valid review threads.
- [ ] Reconfirm package-byte identity from CI artifact/sidecars.
- [ ] Squash merge only.
- [ ] Verify post-merge `main`, topology, checks and package invariants.
- [ ] Update living status only if T1 completion changes the recorded Approach 3 state.
