# Approach 3 T2 Legacy Callback Routing Consolidation Plan

**Status:** DONE / VERIFIED

**Goal:** Collapse the historical priority-10 callback fallback onto the already-proven `PaymentLifecycle` while preserving every protected public/provider identity and leaving uncharacterized legacy method behavior untouched.

**Base:** `97bc88518550d02e4c9f38f766b583dd78e88986`

**Merged main:** `047cc86060efb97761d7a0cc4a3806f971ab6fe1`

**PR:** #104

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
- [x] Capture valid RED at `09b2c1758652ce57fb5ef39008b168dad347e927`: browser, webhook and public-status children had empty stderr and reached `LEGACY_BROWSER_SENTINEL`, `LEGACY_WEBHOOK_SENTINEL` and `LEGACY_STATUS_SENTINEL` respectively; harness result 15 PASS / 10 FAIL for the intended behavioral reason.

## Task 3 — GREEN: minimal production delegation

- [x] Change only `WC_Upayments::check_ipn_response()` so it delegates to `\Simplixi\SUPCheckout\Payment\PaymentLifecycle::handle_callback()`.
- [x] Retain defensive terminal `exit()`.
- [x] Do not modify `return_from_upayments()`, `web_hook_handler()`, or `verify_payment_status()`.
- [x] Re-run the direct-entrypoint harness: implementation head `86224692d88fd7738e396859456a65bf1bd92caa` produced 25 PASS / 0 FAIL.
- [x] Advance the exact gateway-size architecture ratchet from the frozen Approach 2/T1 coordinate 87,995 bytes to the reviewed T2 candidate coordinate 87,724 bytes.

## Task 4 — Full exact-head certification

- [x] Audit changed files and exact diff: exactly one production runtime file changed (`UPayments.php`), limited to `check_ipn_response()`.
- [x] T1 dependency/provider-egress harness green: 11 PASS / 0 FAIL.
- [x] T1 active-callback characterization green: 41 PASS / 0 FAIL.
- [x] T2 direct-entrypoint harness green: 25 PASS / 0 FAIL.
- [x] Quality/H12 green on exact PR head `4cff2dc6e6d11a4b3232a6d3d70d6280a59741c4`.
- [x] Full 20-cell compatibility matrix + Compatibility Gate green.
- [x] Deterministic canonical/Linux/Windows package equality green.
- [x] T2 candidate package SHA-256: `368aaa5cb1a75e6df41ff17bb2e2126431b49da5e6b8dfe508c718433009fc04`; file count: 51.
- [x] Provider Sandbox Certification green.
- [x] WordPress.org Submission Check / Plugin Check green.
- [x] CodeQL/security checks green.
- [x] Zero unresolved valid review threads at merge gate.
- [x] External Codex review was explicitly requested but unavailable because the account reached its code-review usage limit; no external approval is claimed. A separate exact-diff conformance/security audit was completed before merge.

## Task 5 — Merge and continuity

- [x] Squash-merged PR #104 after exact-head certification.
- [x] Verified merged `main` SHA `047cc86060efb97761d7a0cc4a3806f971ab6fe1` is GitHub-verified.
- [x] Verified the merged Git tree is identical to the certified PR-head tree: `01060868bc00829be408fac258d178d0f15d8f02`.
- [x] Verified clean repository topology after merge: only `main`, zero open PRs/issues, zero tags/releases.
- [x] Fresh post-merge Quality/H12 green.
- [x] Fresh post-merge full compatibility matrix + Compatibility Gate green.
- [x] Fresh post-merge deterministic release, packaged runtime, migration, Release Gate, sandbox, submission and security checks green.
- [x] Preserve the frozen owner-accepted Approach 2 baseline `0c883d609906676966002eb022a82a9656eeacc5` and package SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`; the T2 candidate does not become owner-accepted until Approach 3 closeout and fresh owner technical re-acceptance.
- [x] Do not modify direct public legacy `return_from_upayments()` or `web_hook_handler()` methods until a later tranche first characterizes their direct behavior.

## Closure evidence

T2 is technically closed. The runtime change reduced duplicate callback authority by making the historical priority-10 compatibility fallback reuse the already-certified `PaymentLifecycle` path. No new provider egress site, framework layer, provider identity, persisted identity, H12 token behavior, subscription financial behavior, or release authority was introduced.

Public release remains **NOT AUTHORIZED**.
