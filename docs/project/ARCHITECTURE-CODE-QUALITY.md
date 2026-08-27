# Architecture & Code-Quality Foundation

**Status:** A1 / IMPLEMENTATION

**Current branch:** `architecture/a1-provider-endpoints`

**Verified base `main`:** `596ffb433813cdc06e81d67162617b3019af686b`

**Verified base tree:** `3fcaed35546a6b1407d2a46797630e46301e65ef`

**Gate purpose:** replace inherited mixed-responsibility structure incrementally with explicit Simplix-owned boundaries while preserving every closed payment, security, H12, Phase 9I and compatibility contract.

This is not permission for a big-bang rewrite, cosmetic renaming, broad API redesign, or opportunistic cleanup of payment-critical code.

## Entry conditions

The gate starts only because the following are already independently DONE / VERIFIED:

- Repository Foundation & Readiness;
- Phase 0 release identity/updater ownership;
- Phase 9I historical token-identity migration;
- Provider Contract & Payment Lifecycle;
- Security Threat-Model Closure;
- H12 token-identity hardening baseline.

The permanent regression platform entering this gate is:

- Phase 0: **35 PASS / 0 FAIL**;
- Phase 9I preflight: **123 / 0**;
- Phase 9I executor: **59 / 0**;
- Phase 9I operations: **81 / 0**;
- Provider Payment Lifecycle: **141 / 0**;
- Provider Exact Amount Binding: **4 / 0**;
- Security Threat-Model: **81 / 0**;
- H12 PHP: **1927 / 0**;
- H12 Blocks: **144 / 0**.

Every architecture tranche must keep those suites mandatory.

## Discovery closure evidence

The discovery/characterization tranche is **DONE / VERIFIED**:

- PR #19 final reviewed head: `6e51b1c1c5649313acf86943e30793c38bc71f14`;
- exact PR merge tree: `3fcaed35546a6b1407d2a46797630e46301e65ef`;
- squash merge on `main`: `596ffb433813cdc06e81d67162617b3019af686b`, with a valid verified GitHub signature;
- exact-head Quality Gates run #147: **SUCCESS**;
- push-triggered post-merge Quality Gates run #148: **SUCCESS**;
- implementation branch `architecture/discovery`: **deleted after verified merge**.

Discovery froze the responsibility map, dependency direction, compatibility surfaces, A1-A5 order, monolith ratchet and permanent architecture harnesses. A1 is the first permitted runtime extraction under that reviewed contract.

## Current structural baseline

### Primary monolith

At the verified discovery closure, `UPayments.php` was **257,832 bytes**. A1 reduces it to the current accepted ratchet of **257,298 bytes** while preserving the main plugin bootstrap and `WC_Upayments` gateway implementation. Source characterization identifies at least these responsibility families inside the same file:

1. plugin bootstrap, constants, WooCommerce availability checks and gateway registration;
2. gateway constructor/hook registration and runtime composition;
3. settings schema, settings validation and admin presentation;
4. checkout/request-context parsing for classic and Store API flows;
5. exact decimal validation, provider amount arithmetic and JSON-number injection;
6. provider URL construction and authenticated transport;
7. payment-method discovery, cache, advisory lock and rate-gate behavior;
8. saved-card discovery and customer-token integration;
9. ordinary checkout `process_payment()` orchestration and provider Charge payload assembly;
10. legacy return/webhook/IPN routing around the extracted payment lifecycle;
11. thank-you/admin-order/account presentation;
12. multi-merchant routing/configuration/repeater presentation;
13. subscription module bootstrap and subscription summary/cart behavior;
14. custom subscription product type, product-meta persistence and cart/order-item presentation;
15. frontend/admin asset enqueueing and gateway icons;
16. activation/setup and global WooCommerce hooks.

This mixed ownership is the principal architecture debt. The gate must reduce it by delegation behind stable compatibility wrappers rather than deleting or globally renaming entry points.

File size alone is not authority to refactor; it is only a hotspot signal.

### Monolith ratchet contract

The permanent architecture harness records the **exact accepted `UPayments.php` byte size for the current architecture milestone**, not merely the original entry ceiling. Any tranche that changes `UPayments.php` must update that accepted size to the exact reviewed post-change size, so every shrink becomes the next ratchet point instead of allowing later regrowth back to 257,832 bytes.

Normal architecture tranches may hold or reduce the ratchet. Increasing it is merge-blocking unless the PR documents a concrete defect/compatibility necessity, proves why the change cannot safely live behind an extracted boundary, and receives explicit independent review. Size ratcheting must also be supplemented by responsibility-specific assertions; removed bytes may not simply be replaced with a new unrelated responsibility.

### Existing extracted Simplix-owned seams

`src/` already provides useful strangler boundaries:

- `src/Release/` — public Simplix release identity and updater ownership foothold;
- `src/Migration/` — Phase 9I preflight/executor/batch/admin/CLI/settings/bootstrap;
- `src/Payment/` — `PaymentLifecycle`, `StatusVerifier`, `ProviderResult`, `StatusRateGate`, `OrderLock`;
- `src/Security/` — public order-status authorization boundary.

Legacy but already separated protected areas remain under `includes/`:

- `includes/Token/CustomerTokenIdentity.php` — **96,821 bytes**, H12-critical;
- `includes/Subscription/Cron/Scheduler.php` — **47,965 bytes**;
- `includes/Subscription/Cron/CycleClaim.php` — **15,954 bytes**;
- `includes/Subscription/Checkout/Fields.php`;
- `includes/Subscription/Manager.php`;
- `includes/class-wc-gateway-upayments-blocks.php` — Blocks gateway integration.

## Protected compatibility boundaries

Architecture work must preserve unless a separately characterized migration explicitly replaces them:

- payment method/gateway ID `upayments`;
- settings key `woocommerce_upayments_settings`;
- callback route `wc_upayments`;
- existing `_upay_*` metadata and historical `UPayments_*` evidence used by closed contracts;
- H12 secret/provenance/scope/generation identities;
- historical order payment-method values;
- subscription scheduler, cleanup, cycle-claim and billing-attempt identities;
- current `UPayments.php` physical main-file identity and `upayments` text domain until their dedicated distribution/i18n migrations;
- public/third-party-callable legacy gateway methods unless characterization proves a safe removal path.

New code uses the `Simplix\Pay\UPayments` namespace. Existing UPayments namespaces are not globally renamed in this gate.

## Dependency direction

The target is a small composition root plus explicit services, not an overbuilt container.

Rules:

1. plugin/bootstrap code may construct services and register WordPress/WooCommerce hooks;
2. domain/service classes must not reach back into the bootstrap to discover dependencies;
3. new pure provider/value helpers should not depend on WooCommerce globals;
4. WordPress/WooCommerce adapters may depend on stable domain services, not the reverse where avoidable;
5. Payment, Migration and Security closed modules must not acquire new cross-dependencies merely for convenience;
6. legacy gateway methods may remain as thin compatibility wrappers delegating into extracted services;
7. no new global mutable state is introduced;
8. no extraction may silently change persisted keys, hooks, routes, provider payload fields or order-state semantics.

## Candidate target modules

The long-term shape remains incremental and evidence-driven:

```text
src/
  Plugin/
  Requirements/
  Gateway/
  Provider/
  Payment/
  Webhook/
  Refund/
  Token/
  Migration/
  Subscription/
  MultiMerchant/
  Blocks/
  Admin/
  Diagnostics/
  Logging/
  Compatibility/
```

A directory is created only when a real responsibility is extracted; empty architecture theater is prohibited.

## Extraction priority

### A1 — provider endpoint/mode resolution — first safe runtime seam

Create a small pure `Provider` service for test/live API base and endpoint resolution. Existing public gateway helpers remain compatibility wrappers and must return byte-equivalent URLs. No network behavior, credentials, payment truth or payload semantics change.

**A1 implementation contract:**

- `src/Provider/EndpointResolver.php` owns only deterministic mode-to-base and route resolution;
- `getAPIUrl()`, `getAPIUrlForCreateToken()`, `getAPIUrlForCheckPaymentButtonStatus()` and the historically misspelled `getAPIUrlForRetreiveCards()` remain public compatibility wrappers;
- live mode remains byte-equivalent to the inherited `https://apiv2api.upayments.com/api/v1/` base and test mode remains byte-equivalent to `https://sandboxapi.upayments.com/api/v1/`;
- the current official UPayments V2 documentation names `https://uapi.upayments.com/api/v1/` for production. A1 deliberately does **not** adopt that provider-host change because it would be a runtime migration, not a structure-only extraction; any host migration requires separate provider-contract research, compatibility analysis, review and executable evidence;
- the resolver has no WordPress/WooCommerce/global-option dependency and performs no transport, authentication, payment-truth or payload work;
- `tests/harness/architecture-provider-endpoints-harness.php` freezes both modes, arbitrary route byte-equivalence, fixed endpoint routes, compatibility-wrapper behavior and dependency purity.

Why first:

- narrow and deterministic;
- duplicated provider host selection currently exists in several public gateway methods;
- pure logic is easy to characterize independently;
- wrappers preserve third-party compatibility;
- creates the first reusable `Provider` dependency without touching Charge/status state machines.

### A2 — payment-method availability client/cache

Only after A1 is verified. Characterize current cache-key, credential fingerprint, advisory-lock, cooldown and provider-schema behavior first. Preserve test/live isolation and fail-closed behavior.

### A3 — gateway settings/admin/multi-merchant presentation

Separate settings definition/validation/presentation from runtime payment orchestration while preserving all option keys and the current one-additional-merchant capability claim.

### A4 — subscription product/account presentation

Move hook-heavy product-type/account/presentation behavior behind a subscription composition boundary only after characterization. Preserve scheduler/table/meta identities and no-blind-retry contract.

### A5 — checkout payload/orchestration core

`process_payment()`, strict decimal/payload construction, saved-card charge and provider Charge orchestration remain high-risk and late. They require dedicated characterization before any extraction.

## Static/code-quality baseline sequence

Do not enable a tool by blindly failing the whole historical tree.

1. record current PHP compatibility (`Requires PHP: 7.2`) and WordPress/WooCommerce runtime assumptions;
2. introduce Composer only with an explicit distribution rule and no runtime dependency leakage;
3. add PHPCS/WPCS and PHPStan incrementally with a recorded baseline or initially scoped paths;
4. target newly extracted `src/` code first, then ratchet scope outward;
5. add dead-code/complexity findings only after runtime hook/reflection/callback reachability is characterized;
6. every suppression/baseline entry must identify why it exists and must not hide payment/security correctness findings.

## Discovery findings / debt register

Current evidence already shows:

- `UPayments.php` mixes bootstrap, gateway, provider, settings, UI, subscription and product responsibilities;
- several provider endpoint helpers duplicate test/live host selection;
- global hooks and anonymous closures make reachability harder to reason about than namespaced services;
- subscription responsibilities are split between `UPayments.php` and `includes/Subscription/`;
- H12 token identity remains a large protected legacy module and must not be opportunistically decomposed;
- payment truth is already better isolated in `src/Payment/` and should be treated as a model for strangler migration, not reopened;
- architecture modernization must distinguish genuinely dead code from public compatibility surfaces such as legacy gateway helper methods.

## Verified acceptance contract for the discovery tranche

This tranche is complete only when:

1. this responsibility/dependency map is reviewed against exact `main` source;
2. a permanent architecture-foundation harness protects the current gate, required module boundaries and compatibility identities;
3. the architecture harness is required in Quality Gates alongside every prior regression suite;
4. no production runtime behavior changes in the discovery PR;
5. exact PR head passes Governance, syntax and the complete regression platform;
6. independent review has no unresolved valid findings;
7. the discovery PR is squash-merged and post-merge verified before A1 begins.

## Non-claims

Architecture discovery does not certify code quality, eliminate technical debt, establish full static-analysis cleanliness, certify platform/features/performance, or authorize physical plugin/text-domain renames. It only freezes the decomposition contract and the safe order of work.

A1 likewise does not certify or change provider hosts, connectivity, credentials, request payloads, payment state, platform compatibility or production readiness. It extracts one deterministic endpoint-resolution seam behind the existing public API.
