# SimplixPay for UPayments — Project Status

**Status document:** canonical living engineering state

**Last updated:** 2026-08-30

**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

**Historical audit archive:** `SimplixInnovations/upayments-woocommerce`

**Provider upstream:** `upaymentskwt/woocommerce`

> Live GitHub/source evidence always wins over recorded SHAs. Recorded SHAs are verified milestone anchors, not substitutes for a fresh remote check.

## Current program state

| Item | State |
|---|---|
| Product | **SimplixPay for UPayments** |
| Canonical slug | `simplixpay-upayments` |
| Current development version | **0.1.0** |
| Production maturity | **Pre-release engineering hardening** |
| Stable SimplixPay release | **NO** |
| WordPress.org release | **NO** |
| H12 token-identity hardening | **DONE / VERIFIED** |
| Repository foundation/readiness | **DONE / VERIFIED** |
| Phase 0 — release identity/updater ownership | **DONE / VERIFIED** |
| Phase 9I — historical token-identity migration | **DONE / VERIFIED** |
| Provider Contract & Payment Lifecycle | **DONE / VERIFIED** |
| Security Threat-Model Closure | **DONE / VERIFIED** |
| Architecture & Code-Quality Foundation | **DONE / VERIFIED (DISCOVERY + A1-A5)** |
| Quality Platform Q1 foundation | **DONE / VERIFIED** |
| Quality Platform Q2 CheckoutPayload analysis | **DONE / VERIFIED** |
| Quality Platform Q3 payment-concurrency analysis | **DONE / VERIFIED** |
| Quality Platform Q4 authenticated-status analysis | **DONE / VERIFIED** |
| Current program gate | **Full Automated Quality Platform — Q5** |

The plugin remains a pre-release engineering project. Architecture discovery/A1-A5 and Quality Platform Q1-Q4 are DONE / VERIFIED; Q5 payment-method availability analysis is the current bounded implementation tranche. None of these milestones constitutes broad code-quality, provider-host, penetration-test, PCI/compliance, platform, feature, performance or release certification.

## Latest verified milestone — Quality Platform Q4 authenticated-status analysis

PR #30 final reviewed head:

- `8543bdfce1a4e216200791dc5637b646f49bcb59`

Verified squash merge on `main`:

- merge: `4b3db92b0ded0c598bad0ab677babab9e6102811`
- tree: `ad5ae98d5e935bb48d1441f94e130f5d3adb3ca9`
- parent: `30e99a6a456b72709c87e442b8437301ba64e99b`
- implementation branch `quality/authenticated-status-analysis`: **deleted after verified merge**
- exact-head Quality Gates run #194: **SUCCESS**
- push-triggered post-merge Quality Gates run #195: **SUCCESS**
- PHPUnit: **39 tests / 327 assertions**
- Quality Platform Foundation: **74/0**
- Q2 Checkout Payload Analysis: **64/0**
- Q3 Payment Concurrency Analysis: **69/0**
- Q4 Authenticated Status Analysis: **68/0**

Q4 expanded baseline-free PHPStan level 5/PHP 7.2, PHPCS and PHPUnit into StatusVerifier without changing executable production statements. It characterizes exact sandbox/live destination allowlisting before Bearer/rate use, hardened HTTP failures and authenticated transaction/order binding. Every historical/architecture/H12 regression remained green; all valid P2 findings were resolved, and final independent exact-head review found no major issues. Q4 makes no live-provider, platform or production-certification claim.

## Previous verified milestone — Quality Platform Q3 payment-concurrency analysis

PR #29 final reviewed head:

- `e08be468b5453524996c525860c12d5619081132`

Verified squash merge on `main`:

- merge: `30e99a6a456b72709c87e442b8437301ba64e99b`
- tree: `703a56c03e95862b8b4807d9a1ea28e2e3e201dd`
- parent: `356680b9fe8a2724e778d40386ca182247715249`
- implementation branch `quality/payment-concurrency-analysis`: **deleted after verified merge**
- exact-head Quality Gates run #188: **SUCCESS**
- push-triggered post-merge Quality Gates run #189: **SUCCESS**
- PHPUnit: **31 tests / 220 assertions**
- Quality Platform Foundation: **74/0**
- Q2 Checkout Payload Analysis: **64/0**
- Q3 Payment Concurrency Analysis: **69/0**

Q3 expanded baseline-free PHPStan level 5/PHP 7.2, PHPCS and PHPUnit into the StatusRateGate and OrderLock concurrency boundaries without changing executable production statements. Every historical/architecture/H12 regression remained green; all valid P1/P2 findings were resolved, and final independent exact-head review found no major issues. Q3 makes no platform, real-database concurrency or production-certification claim.

## Previous verified milestone — Quality Platform Q2 CheckoutPayload analysis

PR #28 final reviewed head:

- `c2c30f90688747a523301cb776ed920ef39063f3`

Verified squash merge on `main`:

- merge: `356680b9fe8a2724e778d40386ca182247715249`
- tree: `3550fdbb0810af26808851e24e39a6130725e8db`
- parent: `9b3ead774a5a9bc2ac0f3b3ad754b2d99053f362`
- implementation branch `quality/static-analysis-expansion`: **deleted after verified merge**
- exact-head Quality Gates run #182: **SUCCESS**
- push-triggered post-merge Quality Gates run #183: **SUCCESS**
- PHPUnit: **21 tests / 126 assertions**
- Quality Platform Foundation: **74/0**
- Q2 Checkout Payload Analysis: **64/0**

Q2 expanded baseline-free PHPStan level 5/PHP 7.2 and PHPUnit characterization into the pure CheckoutPayload decision boundary. PHPStan, PHPCS/WPCS, Composer audit, every historical/architecture harness and H12 remained green. The final independent re-review found no major issues on the exact head; PR #27 remains closed unmerged as evidence-only. Q2 makes no platform or production-certification claim.

## Previous verified milestone — Quality Platform Q1 foundation

PR #26 final reviewed head:

- `936e4630c83f7a92cbc4c77f061626e2b0c0c800`

Verified squash merge on `main`:

- merge: `9b3ead774a5a9bc2ac0f3b3ad754b2d99053f362`
- tree: `473543cd08515eedd764a4b1ef7b6581590d13a1`
- parent: `3223a882867634a2ba7588d7afbd2b2e4b4c21e4`
- implementation branch `quality/platform-foundation`: **deleted after verified merge**
- exact-head Quality Gates run #177: **SUCCESS**
- push-triggered post-merge Quality Gates run #178: **SUCCESS**
- Quality Platform Foundation harness: **73/0**
- PHPUnit: **8 tests / 30 assertions**

Q1 established the locked development-only Composer/PHPUnit/PHPStan/PHPCS platform, dependency audit, PHP 7.2/8.2 distributed-source syntax evidence and a protected always-running H12 prerequisite aggregator. The final independent review was clean after its valid required-check P1 was fixed, and Q1 makes no platform or production-certification claim.

## Previous verified milestone — Architecture A5 checkout orchestration

PR #25 final reviewed head:

- `997e18d8eb6264a84c6a9a35158213d3d655e6b3`

Verified squash merge on `main`:

- merge: `3223a882867634a2ba7588d7afbd2b2e4b4c21e4`
- tree: `392b73425fa3219b6414a0984136b92c8ef77576`
- parent: `d24b83356cc766f82c3ad9e529d3ec3f4194e887`
- GitHub signature: **VERIFIED**
- implementation branch `architecture/a5-checkout-orchestration`: **deleted after verified merge**
- exact-head Quality Gates run #173: **SUCCESS**
- push-triggered post-merge Quality Gates run #174: **SUCCESS**
- Checkout Orchestration harness: **67/0**

A5 moved strict checkout request/decimal/payload construction and the checkout-to-Charge workflow to `src/Payment/CheckoutPayload.php` and `CheckoutOrchestrator.php`, reduced the exact `UPayments.php` ratchet to **88,839 bytes**, preserved the public `process_payment()` and protected request/transport seams, and left scheduler/attempt/auto-deduct behavior unchanged.

## Previous verified milestone — Architecture A4 subscription presentation

PR #24 final reviewed head:

- `2a2c6a4c67775b6614297d2c0150f3ca61220498`

Verified squash merge on `main`:

- merge: `d24b83356cc766f82c3ad9e529d3ec3f4194e887`
- tree: `f74899b93f493be872e0ce993e30079d0223dc7b`
- parent: `6291196b35a952ea974549d1aa6d6ae9bbcc64dc`
- GitHub signature: **VERIFIED**
- implementation branch `architecture/a4-subscription-presentation`: **deleted after verified merge**
- exact-head Quality Gates run #164: **SUCCESS**
- push-triggered post-merge Quality Gates run #165: **SUCCESS**
- Subscription Presentation harness: **75/0**

A4 moved subscription hook composition, product/admin fields and My Account presentation to `src/Subscription/Composition.php` and `src/Subscription/Presentation.php`, reduced the exact `UPayments.php` ratchet to **205,702 bytes**, retained every named global/public compatibility wrapper and left customer mutation, scheduler, cycle claims, billing attempts, checkout and provider dispatch unchanged.

## Previous verified milestone — Architecture A2 payment-method availability

PR #22 final reviewed head:

- `bdb627520aa28e71b69a91f8ef71d04d257a3ad8`

Verified squash merge on `main`:

- merge: `f85894271e8f991e77a8e6a2b306f4d191483bbd`
- tree: `1addbcc02e0d30f57a948cafd8111fb94e60c4da`
- parent: `d43d175a1443709d42efabfbe78519a5a84f4dc9`
- GitHub signature: **VERIFIED**
- implementation branch `architecture/a2-payment-method-availability`: **deleted after verified merge**
- exact-head Quality Gates run #155: **SUCCESS**
- push-triggered post-merge Quality Gates run #156: **SUCCESS**

A2 moved availability cache/lock/gate/provider normalization to `src/Provider/PaymentMethodAvailability.php` behind public `getUpayPaymentMethods()`, reduced the exact `UPayments.php` ratchet to **238,714 bytes**, and made Payment-Method Availability **102/0** mandatory alongside the complete historical and architecture stack.

## Previous verified milestone — Architecture A1 provider endpoint/mode resolution

PR #21 final reviewed head:

- `baed693964556120dc7ad07dbc740d3acc1af20f`

Verified squash merge on `main`:

- merge: `d43d175a1443709d42efabfbe78519a5a84f4dc9`
- tree: `ddb2ac7cd8b2d4f454867e10bc361fee94dbcf4b`
- parent: `596ffb433813cdc06e81d67162617b3019af686b`
- GitHub signature: **VERIFIED**
- implementation branch `architecture/a1-provider-endpoints`: **deleted after verified merge**
- exact-head Quality Gates run #152: **SUCCESS**
- push-triggered post-merge Quality Gates run #153: **SUCCESS**

A1 preserved the inherited live/sandbox URL bytes behind all four public gateway wrappers, reduced the exact `UPayments.php` ratchet to **257,298 bytes**, and made Provider Endpoints **49/0** mandatory alongside the complete historical and architecture stack. The official provider production-host difference remains a separately reviewed future migration.

## Previous verified milestone — Architecture discovery

PR #19 final reviewed head:

- `6e51b1c1c5649313acf86943e30793c38bc71f14`

Verified squash merge on `main`:

- merge: `596ffb433813cdc06e81d67162617b3019af686b`
- tree: `3fcaed35546a6b1407d2a46797630e46301e65ef`
- parent: `ddb3fc901c5dc949c634f745c4c3a7ec2a72414c`
- GitHub signature: **VERIFIED**
- implementation branch `architecture/discovery`: **deleted after verified merge**
- exact PR merge-ref Quality Gates run #147: **SUCCESS**
- push-triggered post-merge Quality Gates run #148: **SUCCESS**

The verified discovery milestone froze the exact responsibility/dependency map, public compatibility surfaces, A1-A5 extraction order, monolith size ratchet, bootstrap reachability checks and mandatory architecture regression platform. Its final counters were Architecture Foundation **67/0**, Runtime Bindings **138/0** and Bootstrap Paths **153/0**, alongside the complete historical stack.

## Previous verified implementation milestone — Security Threat-Model Closure

PR #17 final reviewed head:

- `fba12225899c3e01d6b23a6bba2f757a3b5f6a4a`

Verified squash merge on `main`:

- merge: `01f3fc59eed8641b3e5372558f61a7a0f0cdfac9`
- tree: `e0027005f059fad03d8c08273b7aac6553c45f53`
- parent: `08054a93c619f3c34fef747a6e530abce1e8986e`
- GitHub signature: **VERIFIED**
- implementation branch `security/threat-model-discovery`: **deleted after verified merge**
- exact PR merge-ref Quality Gates run #88: **SUCCESS**
- push-triggered post-merge Quality Gates run #89: **SUCCESS**

Exact final implementation-head regression evidence:

- Governance: **SUCCESS**
- tracked PHP syntax: **SUCCESS**
- Phase 0 release identity: **35 PASS / 0 FAIL**
- Phase 9I preflight: **123 PASS / 0 FAIL**
- Phase 9I executor: **59 PASS / 0 FAIL**
- Phase 9I operations: **81 PASS / 0 FAIL**
- Provider Payment Lifecycle: **141 PASS / 0 FAIL**
- Provider Exact Amount Binding: **4 PASS / 0 FAIL**
- Security Threat-Model: **81 PASS / 0 FAIL**
- H12 PHP: **1927 PASS / 0 FAIL**
- Blocks syntax: **SUCCESS**
- H12 Blocks: **144 PASS / 0 FAIL**

The final security implementation closed five bounded findings: public status-poll IDOR, state-changing subscription GET actions, checkout third-party font/icon trust, overly broad plain-data output trust, and product-meta save defense in depth. A valid P2 automated review finding on Checkout Blocks chevrons was fixed before merge and added to the permanent security harness.

## Closed security threat-model contract

The verified bounded security closure now has these frozen properties:

- numeric order ID is never authority for public status polling; exact logged-in ownership or exact Woo order key is required, and only UPayments orders are eligible;
- customer subscription pause/resume/unsubscribe mutations are POST-only, exact-owner-bound, action-nonced, object-preflighted and transition-validated;
- classic and Checkout Blocks no longer depend on Google Fonts/cdnjs Font Awesome for checkout presentation;
- plain provider/order values are escaped as text and stored settings use attribute-context escaping;
- checkout display markers no longer consume `$_REQUEST`;
- product custom-meta writes mirror WooCommerce nonce, posted-ID and `edit_post` authorization preconditions locally;
- existing payment lifecycle host/TLS/redirect/Bearer, H12 identity, Phase 9I capability/nonce, no-blind-retry and immutable Actions-pin controls remain regression-protected;
- the permanent security harness is part of required Quality Gates.

Explicitly unresolved boundaries remain explicit: provider webhook HMAC/signature details are provider-document unresolved; subscription auto-deduction is not promoted to broad recurring-billing certification; automatic refunds remain unsupported pending durable idempotency/reconciliation design; H12 token/provenance and Phase 9I migration contracts remain frozen.

## Previous verified implementation milestone — Provider Contract & Payment Lifecycle

PR #15 final reviewed head:

- `d2b08ebe1e65ad4ea8f4e06b41423e7bd9904fc3`

Verified squash merge on `main`:

- merge: `9569e39973a9e94926087738eae06c3846361943`
- tree: `40ec562674361624c2764263ba55cfba84594955`
- parent: `8e5a93ceb4f133663fdf433cc1a10b8b36c13d97`
- GitHub signature: **VERIFIED**
- implementation branch `provider-lifecycle/discovery`: **deleted after verified merge**
- push-triggered post-merge Quality Gates run #71: **SUCCESS**

Exact final implementation-head regression evidence:

- Governance: **SUCCESS**
- tracked PHP syntax: **SUCCESS**
- Phase 0 release identity: **35 PASS / 0 FAIL**
- Phase 9I preflight: **123 PASS / 0 FAIL**
- Phase 9I executor: **59 PASS / 0 FAIL**
- Phase 9I operations: **81 PASS / 0 FAIL**
- Provider Payment Lifecycle: **141 PASS / 0 FAIL**
- Provider Exact Amount Binding: **4 PASS / 0 FAIL**
- H12 PHP: **1927 PASS / 0 FAIL**
- Blocks syntax: **SUCCESS**
- H12 Blocks: **144 PASS / 0 FAIL**

The exact PR merge-ref was tested by Quality Gates run #70 before merge. The complete workflow passed again on merged `main` in run #71.

## Closed provider/payment lifecycle contract

The verified ordinary-checkout lifecycle now has these frozen properties:

- provider/browser/webhook payload fields are non-authoritative;
- financial truth requires Bearer-authenticated Get Payment Status plus strict order binding;
- status transport accepts only exact HTTPS UPayments status endpoints, with redirects disabled, TLS verification enabled and finite timeout;
- status-query automation is capped at the stricter documented 30/minute contract;
- `track_id`, provider order identity, Woo reference, currency and amount bind before mutation;
- amount equality is canonical decimal-string equality, not display-precision rounding;
- `CAPTURED` uses WooCommerce `payment_complete($verified_payment_id)`;
- Woo's standard transaction ID and payment-complete lifecycle are preserved;
- paid/refunded orders cannot be resurrected or downgraded;
- `PENDING`, `AUTHORIZED`, `APPROVED`, provider `NULL`, Processing-style and unknown future outcomes remain unpaid and reconcile boundedly;
- authenticated `FAILED`/`ERROR`/`NOT CAPTURED` and `CANCELED` affect only unverified/unpaid orders;
- an unverified first-callback cursor may be retained only for retry routing and is promoted to trusted only after authenticated rebinding;
- cursor/reconciliation state is paired with the current `UPayments_order_id` so later Charge attempts on the same Woo order cannot inherit stale attempt state;
- reconciliation is bounded to 60/120/240/480 seconds and never creates/retries a Charge;
- per-order lifecycle locking uses compare-and-swap stale takeover/release semantics;
- callback routing excludes cookies and `$_REQUEST` and rejects conflicting GET/POST values.

See `PROVIDER-PAYMENT-LIFECYCLE.md` for the complete closed contract.

### Explicit unresolved provider/feature boundaries

These were deliberately **not** guessed into support:

- UPayments webhook HMAC/signature verification remains provider-document unresolved because the public documentation reviewed did not provide a complete stable header/canonicalization/key contract;
- automatic WooCommerce refunds remain unsupported because UPayments documents asynchronous completion, status polling, no refund webhook and no idempotency keys;
- arbitrary multi-entry marketplace splitting is not certified; current support remains one additional merchant allocation only;
- subscription auto-deduction retains its separate characterized scheduler/attempt-journal path.

These boundaries become inputs to security and later feature-certification gates rather than hidden lifecycle assumptions.

## Phase 9I — verified closed state

Phase 9I remains **DONE / VERIFIED** through PRs #11, #12 and #13:

- preflight merge `8cca32819dd165e35efa0fcc5a48bdd551757d8c`, tree `c0af8a2ab1fbd2494f961ee9f924c00aaf519ab0`;
- executor merge `708253bd9d0daf217735fbb087b360e8b848136c`, tree `e222a18c9808229fdde79efb42268d8c3fbd33ae`;
- operations merge `db1c4ea4dab45bc1ffaf4529e0ccb940153cd999`, tree `5bec24ad26c66a504cd0dd609f4311f9e70add76`.

The contract remains:

- exact read-only `CLEAN` / `MIGRATABLE` / `BLOCKED` / `INDETERMINATE` classification;
- all 13 historical blocker families fail closed;
- executor acts only on fresh `MIGRATABLE` evidence;
- historical provenance can become only `legacy_compat` / `legacy_verified_capture`;
- no fabricated canonical/Create-201 provenance;
- historical order metadata remains immutable;
- bounded admin/CLI operations with durable redacted per-user result checkpoints and credential/mode/list-scoped resume.

Phase 9I closure certifies the migration system, not automatic migration of every merchant installation.

## Phase 0 release identity — verified state

Public header remains:

- Plugin Name: **SimplixPay for UPayments**
- Plugin URI: `https://github.com/SimplixInnovations/simplixpay-upayments`
- Version: `0.1.0`
- Author: **Simplix Innovations**
- License: MIT
- Text Domain: `upayments` — transitional by design

External self-updates remain intentionally disabled until a separately tested physical package/basename migration establishes a safe Simplix distribution identity. The inherited Plugin Update Checker and upstream update authority remain removed.

## Transitional install/i18n identities

Current transitional identities remain:

- main file: `UPayments.php`;
- runtime/header text domain: `upayments`.

Frozen eventual targets remain:

- `simplixpay-upayments.php`;
- text domain `simplixpay-upayments`.

These require explicit package/upgrade and i18n/WPML migrations rather than cosmetic replacement.

## Protected compatibility/runtime identities

Do not rename merely for branding:

- gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks/Store API identity `upayments`;
- callback route `wc_upayments`;
- existing `_upay_*` metadata;
- `upayments_token_identity_secret_v2` and H12 provenance/scope/generation state;
- subscription scheduler/historical cleanup identities;
- billing-attempt table/schema state;
- historical order payment-method values;
- existing UPayments classes/namespaces unless separately characterized.

`NAMING-IDENTITY-STANDARD.md` remains authoritative.

## Retained H12 / Phase 0 evidence

Initial Phase 0 characterization before implementation: **22 PASS / 13 FAIL**.

Final Phase 0 implementation evidence: **35 PASS / 0 FAIL** with H12 PHP **1927/0** and H12 Blocks **144/0**.

Historical H12 implementation anchors outside deliberately changed later surfaces remain evidence anchors, not permanent prohibitions:

- `includes/Token/CustomerTokenIdentity.php` — `85430d37e9baf540842f5655b86ccf0eca3e6aea`
- `includes/class-wc-gateway-upayments-blocks.php` — `813d192d69c069eb7ee11df93acc9dbdf03e270a`
- `includes/Subscription/Cron/Scheduler.php` — `5251866d4df2d1326e7c09f0c8ec1d146c0bb325`
- `includes/Subscription/Cron/CycleClaim.php` — `c34d83e2d77cc65024fe663e4c378cecb2b17347`

## Repository/governance state

Repository readiness remains DONE / VERIFIED:

- standalone canonical repository;
- protected `main`;
- squash-only merge policy;
- PR/review-thread workflow;
- required Governance and H12 Regression Harness checks;
- linear history and deletion/non-fast-forward restrictions;
- automatic merged-branch deletion;
- secret scanning + push protection;
- Dependabot security updates;
- private vulnerability reporting;
- MIT recognized.

## Current program gate — Full Automated Quality Platform

**Status: Q5 — PAYMENT-METHOD AVAILABILITY ANALYSIS / IMPLEMENTATION.**

Architecture discovery/A1-A5 and Quality Platform Q1-Q4 are complete. Q5 is limited to the extracted payment-method availability cache, advisory-lock, durable-gate and provider-normalization boundary. Current scope:

- add `PaymentMethodAvailability.php` to the baseline-free PHPStan level 5 / PHP 7.2 and risk-focused PHPCS scopes;
- add development-only WordPress transient/advisory-lock symbols and deterministic fixtures;
- expand PHPUnit characterization across exact cache/gate/lock identities, strict schema-3 cache shapes, lock contention, cooldown/write failure, provider transport/envelope failure and normalized known-button results;
- reconcile documentation-only property/return PHPDoc with existing runtime behavior and change no executable payment behavior;
- keep the Q1 dependency lock, tool versions, analysis level, PHPStan no-baseline/no-`ignoreErrors` rule and protected H12 aggregator unchanged;
- keep every historical and architecture harness plus all five permanent quality-platform harnesses mandatory;
- preserve protected identities and keep Security **82/0**, Provider **141/0 + 4/0**, Phase 9I, Phase 0, H12 and all architecture regressions green.

No cache schema/prefix/fingerprint width, known-button set, gate option identity, 65-second cooldown, advisory-lock formula/timing, provider route/transport, strict HTTP 201/status contract, normalization, payment truth, scheduler/attempt state, protected persistence identity or order-state mutation is authorized to change in Q5. `QUALITY-PLATFORM.md` is the current gate record.

## Later program blockers

After the Full Automated Quality Platform:

- platform and feature certification;
- performance/UX/operations/diagnostics;
- release packaging/distribution and eventual WordPress.org publication.

## Update rule

Update this file after every independently verified milestone merge or program-state change. Never mark a gate DONE from an implementation report alone; verify exact source, diff/tree, checks, review state, merged `main`, post-merge CI and branch cleanup first.
