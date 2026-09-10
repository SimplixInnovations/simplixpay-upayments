# SUPCheckout architecture project context

## Product

SUPCheckout for UPayments is a WooCommerce payment integration maintained by Simplix Innovations. This repository is permanently UPayments-specific.

## Current coordinates

- Live `main` at architecture decision: `0b153c661314669924c81e89fd71bfd2c734310b`
- Frozen owner-accepted Approach 2 runtime baseline: `0c883d609906676966002eb022a82a9656eeacc5`
- Accepted package: `supcheckout-0.1.0.zip`
- Accepted package SHA-256: `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`
- Accepted package file count: `51`
- Approach 3: authorized
- Public release: not authorized

## Architectural baseline

Existing completed seams:

- A1 — `Provider\EndpointResolver`
- A2 — `Provider\PaymentMethodAvailability`
- A3 — `Admin\GatewaySettings`
- A4 — subscription presentation/composition
- A5 — checkout payload/orchestration

Current active callback strangler:

- `Release\Identity` loads/bootstraps `PaymentLifecycle`.
- `PaymentLifecycle` registers `woocommerce_api_wc_upayments` at priority `5`.
- `WC_Upayments::check_ipn_response()` remains a priority-10 compatibility callback.
- `PaymentLifecycle::handle_callback()` handles and exits the canonical callback request before the legacy priority-10 callback normally runs.

## Protected contracts

Do not change without an explicitly approved compatibility/migration contract:

- physical bootstrap `UPayments.php`;
- gateway/payment identity `upayments`;
- option `woocommerce_upayments_settings`;
- callback route `wc_upayments`;
- `_upay_*` metadata and `UPayments_order_id`;
- H12 token/provenance/scope/generation identities;
- subscription hook `upay_process_subscriptions`;
- billing-attempt identities/table;
- historical payment-method identity;
- provider schema/path terminology;
- compatibility accessor `getAPIUrlForRetreiveCards()`;
- `whitelabled` compatibility field;
- protected H12 `CustomerTokenIdentity.php`;
- protected subscription `Scheduler.php` and `CycleClaim.php`.

## Security model

- Browser/provider callback data is routing evidence, never financial truth.
- Canonical callback/reconciliation provider truth is established through `StatusVerifier::verify()`.
- `PaymentLifecycle` rebinds under `OrderLock` before mutation.
- H12 browser saved-card handles stay opaque.
- Merchant bearer/API secrets stay server-side.
- `upayments_token_identity_secret_v2` is HMAC-verified, not encrypted by the plugin.
- Charges and subscription mutations are never blindly retried.

## Modernization strategy

Use incremental strangler modernization around the existing A1-A5 seams and `PaymentLifecycle`. Do not add a second callback architecture. Preserve compatibility facades and progressively remove duplicated internals behind them only after executable characterization.

## Runtime/provider egress baseline

Accepted production egress implementations:

1. `WC_Upayments::execute_upayments_request()` — general gateway transport;
2. `StatusVerifier::verify()` — strict provider status verification;
3. protected subscription Scheduler renewal dispatch.

T1 may add a ratchet forbidding any additional provider HTTP egress beyond this accepted set.

## First tranche

`t01-architecture-guardrails-and-active-callback-characterization`

Control-plane/tests only. No production source or package-byte change.


## Current Approach 3 execution state

- T1 architecture guardrails/active callback characterization: **DONE / VERIFIED**, merged main `beb89ac0c4d8c0e9b7c8b2de1e13c237bbd37b15`.
- T2 legacy callback routing consolidation: **DONE / VERIFIED**, merged main `047cc86060efb97761d7a0cc4a3806f971ab6fe1`.
- T2 changed only the historical `WC_Upayments::check_ipn_response()` fallback to delegate to `PaymentLifecycle::handle_callback()`; direct public legacy return/webhook methods and private verification were intentionally untouched.
- Fresh T2 merged-main checks: **41/41 SUCCESS**.
- T2 deterministic candidate package: **51 files**, SHA-256 `368aaa5cb1a75e6df41ff17bb2e2126431b49da5e6b8dfe508c718433009fc04`.
- Frozen owner-accepted Approach 2 baseline remains `0c883d609906676966002eb022a82a9656eeacc5` with accepted package SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`.
- Next architecture evidence task: directly characterize `return_from_upayments()`, `web_hook_handler()`, and legacy `verify_payment_status()` behavior/call surface before any further consolidation.
