# Approach 3 T3 Legacy Direct Callback Characterization

**Status:** IN PROGRESS — RUNTIME-NEUTRAL

**Base:** `348fd1e7493b97dee37e9839569ba2a7f7a3ab32`

## Purpose

Characterize the three remaining historical payment-authority compatibility surfaces before any further runtime consolidation:

- public `WC_Upayments::return_from_upayments()`;
- public `WC_Upayments::web_hook_handler()`;
- private `WC_Upayments::verify_payment_status()`.

T3 changes no production runtime behavior. Its output is executable evidence for the next architecture decision.

## Required evidence

### Legacy verifier

Pin representative outcomes for:

- invalid order;
- missing track ID;
- missing local `UPayments_order_id`;
- transport failure;
- unexpected HTTP status;
- malformed/top-level provider response;
- transaction binding failure;
- non-numeric / mismatched amount;
- authenticated non-captured result;
- fully bound `CAPTURED` result;
- unexpected exception fail-closed behavior.

Also prove the legacy verifier uses the general gateway transport route `get-payment-status/<track>` and does not itself call the modern `StatusVerifier`, `StatusRateGate`, or `OrderLock` path.

### Direct browser return

Pin at least:

- missing/invalid local preflight -> neutral verification-pending redirect;
- authenticated non-captured -> neutral redirect with no paid-state mutation;
- captured -> authenticated provider metadata written, paid-state transition, verified flags written after successful transition, save, cart clear, return URL redirect;
- failed Woo status transition -> no verified-success flags;
- verified-capture replay -> no provider request and no mutation;
- refunded order -> no provider request and no mutation.

### Direct webhook

Pin at least:

- missing/invalid local preflight -> terminal no mutation;
- binding/verification failure -> terminal no paid-state mutation;
- captured -> authenticated provider metadata, paid-state transition, verified flags and save;
- failed Woo status transition -> no verified-success flags;
- verified-capture replay -> no provider request and no mutation;
- refunded order -> no provider request and no mutation.

## Compatibility trap to preserve for the next decision

A direct caller of `return_from_upayments()` does not necessarily arrive through the canonical WC-API router with a GET `page` marker. `PaymentLifecycle::handle_callback()` currently identifies browser mode by presence of that marker. Therefore T3 must not assume a direct legacy browser-method call can be replaced by a raw call to `PaymentLifecycle::handle_callback()` without request-shape normalization or an explicit compatibility decision.

## Non-scope

Do not modify:

- `UPayments.php`;
- any `src/` or `includes/` runtime file;
- provider/persisted identities;
- provider egress policy;
- H12 token/security logic;
- subscription financial logic;
- package/release authority.

## Completion gate

T3 may merge only if:

- the characterization harness executes the real legacy methods/private verifier against controlled Woo/provider doubles;
- characterization passes without PHP warnings/notices/deprecations;
- T1 and T2 permanent harnesses remain green;
- Quality/H12 and full compatibility gates remain green;
- deterministic release remains exactly the T2 runtime candidate: 51 files / SHA-256 `368aaa5cb1a75e6df41ff17bb2e2126431b49da5e6b8dfe508c718433009fc04`;
- no production runtime file changes;
- zero unresolved valid review threads;
- merged-main checks are reverified.

No T4 runtime implementation is authorized by T3 alone.
