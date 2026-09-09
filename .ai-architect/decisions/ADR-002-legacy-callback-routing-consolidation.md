# ADR-002: Consolidate the legacy callback fallback onto PaymentLifecycle

- **Status:** Accepted
- **Date:** 2026-09-10
- **Repository:** `SimplixInnovations/supcheckout`
- **T2 base main:** `97bc88518550d02e4c9f38f766b583dd78e88986`
- **Frozen owner-accepted Approach 2 runtime baseline:** `0c883d609906676966002eb022a82a9656eeacc5`
- **Frozen owner-accepted package SHA-256:** `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`

## Context

T1 proved that the canonical `woocommerce_api_wc_upayments` route is owned by
`PaymentLifecycle::handle_callback()` at priority 5 and terminates before the
historical `WC_Upayments::check_ipn_response()` callback at priority 10.

T1 also established an important limit: the public legacy
`return_from_upayments()` and `web_hook_handler()` methods and the private
legacy `verify_payment_status()` method were inventoried/pinned by identity,
but were not behaviorally characterized. Their semantics therefore must not be
changed merely because the canonical route shadows them.

The remaining architectural duplication is that if the historical priority-10
callback is invoked directly or becomes reachable as a fallback, its
`check_ipn_response()` body still dispatches to the legacy callback
implementation instead of the already-proven lifecycle.

## Decision

For T2, preserve the historical public method and hook identity but make
`WC_Upayments::check_ipn_response()` a thin compatibility delegate to
`Simplixi\SUPCheckout\Payment\PaymentLifecycle::handle_callback()`.

Retain an explicit terminal `exit()` after the delegate call so the historical
entrypoint remains terminal even if the lifecycle unexpectedly returns in a
future regression.

Do **not** modify in T2:

- `return_from_upayments()`;
- `web_hook_handler()`;
- private `verify_payment_status()`;
- provider transport or endpoint policy;
- `PaymentLifecycle`, `StatusVerifier`, `OrderLock`, H12 token code, or subscription financial code;
- persisted/provider identities.

## Why this is the smallest safe runtime step

- It finishes routing ownership at the historical callback shell without inventing a second controller.
- It cannot create a new provider HTTP egress location.
- It preserves direct public legacy methods for unknown external callers.
- It avoids assuming the older verification semantics are equivalent to `StatusVerifier`.
- It reduces the chance that a priority change or direct method invocation can reactivate a separate financial-authority path.

## Required executable proof

Before changing production code, add a direct-entrypoint harness that invokes the
real `WC_Upayments::check_ipn_response()` entrypoint in child processes with
overridden legacy routing methods as sentinels.

The test must first fail on the accepted T2 base because the current method calls
those sentinels. After the minimal delegation change it must prove:

1. direct browser fallback is handled by `PaymentLifecycle`, produces the neutral verification-pending redirect, and never calls the legacy browser sentinel;
2. direct webhook fallback is handled by `PaymentLifecycle`, produces the terminal HTTP 200 response, and never calls the legacy webhook sentinel;
3. direct public-status fallback is handled by `PublicOrderStatus`, produces the fail-closed 404 response for the controlled invalid request, and never calls the legacy status sentinel;
4. `check_ipn_response()` remains present and the historical `woocommerce_api_wc_upayments` registration remains present at default priority 10;
5. T1 dependency/egress guardrails remain green.

## Package consequence

T2 changes `UPayments.php`, so the old 87,995-byte source size and old ZIP SHA-256
are historical Approach 2/T1 regression coordinates, not expected T2 outputs.
The package file count must remain 51 unless a separately justified packaging change occurs.
T2 must record the new deterministic candidate ZIP SHA-256 from exact-head CI;
that candidate does not become owner-accepted until Approach 3 closeout/re-acceptance.

## Completion gate

T2 may merge only with:

- RED proof captured before implementation;
- GREEN direct-entrypoint harness after implementation;
- Quality/H12 success;
- full 20-cell Compatibility Matrix success;
- Release Artifact and cross-platform deterministic package success;
- Provider Sandbox/WordPress.org/security checks as triggered;
- zero unresolved valid review threads;
- exact changed-file/diff audit showing no out-of-scope runtime change;
- post-merge main verification.

Public release remains not authorized.
