# Approach 3 T2 Legacy Callback Routing Consolidation Plan

**Goal:** Collapse the historical priority-10 callback fallback onto the already-proven `PaymentLifecycle` while preserving every protected public/provider identity and leaving uncharacterized legacy method behavior untouched.

**Base:** `97bc88518550d02e4c9f38f766b583dd78e88986`

## Task 1 — Record the bounded runtime decision

- [x] Add ADR-002 for legacy callback routing consolidation.
- [x] Keep direct public legacy return/webhook methods out of scope.
- [x] Keep legacy private verification out of scope.
- [x] Keep provider egress inventory unchanged.

## Task 2 — RED: executable direct-entrypoint regression

- [x] Add `tests/harness/architecture-legacy-callback-routing-harness.php`.
- [x] Execute the real `WC_Upayments::check_ipn_response()` entrypoint in child processes without running its constructor.
- [x] Override legacy status/browser/webhook methods with distinct sentinels.
- [x] Assert desired direct-entrypoint browser/webhook/public-status behavior belongs to `PaymentLifecycle`/`PublicOrderStatus`, with legacy sentinels absent.
- [x] Register the harness in Quality Gates.
- [x] Run exact-head CI and capture the expected RED failure on unchanged production at `09b2c1758652ce57fb5ef39008b168dad347e927`: all three legacy sentinels observed with empty stderr.

## Task 3 — GREEN: minimal production delegation

- [x] Change only `WC_Upayments::check_ipn_response()` so it delegates to `\Simplixi\SUPCheckout\Payment\PaymentLifecycle::handle_callback()`.
- [x] Retain defensive terminal `exit()`.
- [x] Do not modify `return_from_upayments()`, `web_hook_handler()`, or `verify_payment_status()`.
- [x] Re-run the direct-entrypoint harness: implementation head `86224692d88fd7738e396859456a65bf1bd92caa` produced 25 PASS / 0 FAIL.

## Task 4 — Full exact-head certification

- [ ] Audit changed files and exact diff.
- [ ] Require T1 dependency and active-callback harnesses green.
- [ ] Require H12 and Quality Gates green.
- [ ] Require full 20-cell compatibility success.
- [ ] Require deterministic canonical/Linux/Windows package equality.
- [ ] Record the new T2 candidate package SHA-256 and confirm 51 files.
- [ ] Require Provider Sandbox, WordPress.org and CodeQL/security checks as triggered.
- [ ] Require zero unresolved valid review threads.

## Task 5 — Merge and continuity

- [ ] Squash merge only after exact-head certification.
- [ ] Verify signed/verified merged `main`, topology and post-merge checks.
- [ ] Record T2 completion evidence without redefining the frozen owner-accepted Approach 2 baseline.
- [ ] Do not modify the direct public legacy methods until a later tranche first adds their behavioral characterization.
