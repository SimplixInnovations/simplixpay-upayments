# ADR-001: Approach 3 incremental strangler modernization

- **Status:** Accepted
- **Date:** 2026-09-10
- **Repository:** `SimplixInnovations/supcheckout`
- **Live documentation descendant at decision time:** `0b153c661314669924c81e89fd71bfd2c734310b`
- **Frozen owner-accepted Approach 2 runtime baseline:** `0c883d609906676966002eb022a82a9656eeacc5`
- **Frozen accepted package SHA-256:** `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`
- **Accepted package file count:** `51`

## Context

SUPCheckout already completed Architecture A1-A5 and an owner technical acceptance cycle. The current repository is not a greenfield payment integration and must not be rebuilt as one.

The live callback architecture contains an important already-established strangler seam:

1. `UPayments.php` loads `src/Release/Identity.php`.
2. `Identity.php` bootstraps `Simplixi\SUPCheckout\Payment\PaymentLifecycle` when WordPress hooks are available.
3. `PaymentLifecycle::bootstrap()` registers `woocommerce_api_wc_upayments` at priority `5`.
4. The historical `WC_Upayments::check_ipn_response()` callback remains registered by the WooCommerce gateway at the default priority `10`.
5. `PaymentLifecycle::handle_callback()` terminates the callback request after handling public status, browser-return, or webhook routing. Therefore, under the canonical WordPress callback route, the priority-5 lifecycle is the active path and the legacy priority-10 callback is a shadowed compatibility surface.

The accepted source has three distinct provider HTTP egress implementations:

- gateway transport: `WC_Upayments::execute_upayments_request()` using `wp_remote_request()`;
- active status verification: `StatusVerifier::verify()` using `wp_remote_get()` and a strict status URL allowlist plus `StatusRateGate`;
- protected subscription renewal dispatch in `includes/Subscription/Cron/Scheduler.php`.

The older private gateway `verify_payment_status()` remains reachable from legacy gateway callback methods, but those methods are not the normal active callback path because the priority-5 lifecycle exits first.

## Decision

Use **incremental strangler modernization around the proven A1-A5 seams and the existing `PaymentLifecycle` callback strangler**.

The target direction is:

`WordPress/WooCommerce platform edges -> explicit SUPCheckout application/payment services -> UPayments-specific provider infrastructure`

with these constraints:

- keep the physical `UPayments.php` bootstrap;
- keep `WC_Upayments` as the WooCommerce compatibility gateway shell;
- keep protected provider/persisted identities unchanged;
- keep UPayments-only ownership; do not introduce a generic provider framework;
- do not introduce a service locator, PSR container, reflection-based DI, or heavyweight framework layer;
- prefer explicit construction and explicit platform adapters;
- preserve the protected H12 token subsystem and protected subscription scheduler/cycle-claim blobs unless a separately approved migration contract exists;
- treat file size as a secondary hotspot signal, never the primary success metric.

## Active callback authority

The canonical callback/reconciliation path is:

`woocommerce_api_wc_upayments`
→ `PaymentLifecycle::handle_callback()` at priority `5`
→ `PaymentLifecycle::process_order_status()`
→ `StatusVerifier::verify()`
→ `OrderLock` around applicable mutation/rebind
→ WooCommerce state application
→ terminal redirect/response.

The historical gateway methods:

- `check_ipn_response()`;
- `return_from_upayments()`;
- `web_hook_handler()`;
- private `verify_payment_status()`

remain compatibility surfaces until characterized and safely consolidated. Approach 3 must not build a second new return/webhook controller architecture beside `PaymentLifecycle`.

## Provider/security model

Provider financial verification, concurrency control, and WooCommerce state mutation are separate responsibilities.

- **Provider financial verification:** active callback/reconciliation uses `StatusVerifier::verify()`. The legacy gateway methods retain a separate private verification implementation.
- **Concurrency control:** `OrderLock` protects the active `PaymentLifecycle` mutation/rebind path; it is not itself the state mutator.
- **WooCommerce state mutation:** active lifecycle mutations live in `PaymentLifecycle`; subscription renewal mutations remain in the protected `Scheduler`; legacy direct gateway mutation remains only in shadowed compatibility methods.

`upayments_token_identity_secret_v2` is a random server-side secret stored in a non-autoloaded WordPress option with a versioned record and HMAC verifier. It is **not encrypted at rest by SUPCheckout**. The protected H12 implementation is not changed by this decision.

## First tranche

The first tranche is runtime-neutral:

`t01-architecture-guardrails-and-active-callback-characterization`

It must:

- add no production source files;
- change no packaged runtime file;
- preserve `UPayments.php` at exactly `87,995` bytes;
- preserve the accepted ZIP at exactly 51 files and SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`;
- pin the active priority-5 lifecycle routing and shadowed priority-10 compatibility route;
- characterize active callback/reconciliation behavior and legacy compatibility behavior separately;
- pin the accepted provider egress set and forbid new egress locations;
- record the exact provider-verification / locking / mutation responsibility map.

## Subsequent modernization

The first runtime-bearing tranche after T1 should finish the existing strangler before considering new layers.

The default candidate is **legacy callback consolidation**: preserve legacy public methods while delegating their behavior to the already-proven `PaymentLifecycle`/`StatusVerifier` path where characterization proves safe.

Provider transport extraction is not pre-approved. It must first demonstrate that centralizing transport does not erase endpoint-specific security policy such as status URL allowlisting, rate gating, and strict response semantics.

Saved-card extraction is also not pre-approved. It remains evidence-driven and must not touch `CustomerTokenIdentity.php` without a separate migration/security decision.

## Consequences

### Positive

- Modernization extends an already-proven architecture instead of duplicating it.
- The highest-risk callback/payment-authority behavior remains characterized before changes.
- Compatibility surfaces can remain callable while their duplicated internals are gradually retired.
- No new runtime dependency or framework layer is introduced.
- The Approach 2 accepted package remains the frozen regression reference throughout Approach 3.

### Trade-offs

- The gateway remains intentionally mixed for some compatibility responsibilities during the transition.
- The repository temporarily retains multiple provider HTTP implementations.
- Some duplicate legacy logic remains until characterization supports consolidation.
- Owner technical re-acceptance is required at Approach 3 closeout after runtime-bearing changes.

## Rejected alternatives

- Big-bang rewrite.
- Generic multi-provider/payment-router framework.
- Heavyweight dependency-injection/service-locator architecture.
- Full repository-wide Clean/Hexagonal restructuring.
- Refactoring protected H12 or subscription financial blobs for aesthetics.
- Creating new Return/Webhook controllers beside the already-active `PaymentLifecycle` callback controller.

## Verification and completion

Every runtime-bearing Approach 3 tranche must keep all repository-required checks green, including Quality/H12, the full compatibility matrix, Release Artifact, Provider Sandbox where applicable, WordPress.org submission checks, CodeQL/security checks, and zero unresolved valid review threads.

Approach 3 is complete only after the final merged runtime candidate passes fresh-clone owner technical re-acceptance and becomes a new explicitly recorded owner-accepted baseline. Public release remains separately authorized.
