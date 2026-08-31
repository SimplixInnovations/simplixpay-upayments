# Full Automated Quality Platform

**Status:** Q6 / IMPLEMENTATION

**Current branch:** `quality/gateway-settings-analysis`

**Verified base `main`:** `984053aee6bb50e62e457a639f44307e461f5e38`

**Verified base tree:** `dee657b03f8d44670b0ae2501a40dabf718d4bb2`

## Entry evidence

Q5 is DONE / VERIFIED:

- PR #31 final reviewed head `d4132b0caccaa6edc6d7421afcfd8e9694563224`;
- exact reviewed tree `dee657b03f8d44670b0ae2501a40dabf718d4bb2`;
- exact-head Quality Gates run #197: SUCCESS across all five jobs;
- PHPUnit: **47 tests / 444 assertions**;
- PHPStan level 5/PHP 7.2 and PHPCS/WPCS: clean;
- Quality Platform Foundation: **74/0**;
- Q2 Checkout Payload Analysis: **64/0**;
- Q3 Payment Concurrency Analysis: **69/0**;
- Q4 Authenticated Status Analysis: **68/0**;
- Q5 Payment-Method Availability Analysis: **83/0**;
- every historical, architecture and H12 regression remained green, including H12 PHP **1927/0** and Blocks **144/0**;
- final independent review: no major issues on exact head `d4132b0cacc`, with the valid living-state P2 resolved;
- squash merge `984053aee6bb50e62e457a639f44307e461f5e38` on sole parent `4b3db92b0ded0c598bad0ab677babab9e6102811` with the identical reviewed tree;
- push-triggered post-merge Quality Gates run #198: SUCCESS across all five jobs;
- implementation branch deleted after verified merge.

## Closed Q1 contract

Q1 is DONE / VERIFIED and established:

- a canonical development-only Composer manifest and committed lockfile with zero production package dependencies;
- disabled Composer plugin execution and locked dependency auditing;
- PHPUnit 11.5 pure-service tests;
- baseline-free PHPStan level 5 against PHP 7.2 for the initial pure-module scope;
- risk-focused PHPCS/WPCS security checks;
- PHP 7.2 and PHP 8.2 distributed-source syntax evidence without compatibility-certification claims;
- an always-running protected H12 aggregator that rejects every non-success quality or syntax prerequisite;
- permanent Quality Platform Foundation, historical and architecture regression gates.

Composer remains development-only. `vendor/`, tests, the lockfile and analysis configuration remain excluded through `.distignore`, and runtime does not load `vendor/autoload.php`.

## Closed Q2 contract

Q2 is DONE / VERIFIED:

- PR #28 final reviewed head `c2c30f90688747a523301cb776ed920ef39063f3`;
- exact reviewed tree `3550fdbb0810af26808851e24e39a6130725e8db`;
- exact-head Quality Gates run #182: SUCCESS;
- squash merge `356680b9fe8a2724e778d40386ca182247715249` with the identical reviewed tree;
- push-triggered post-merge Quality Gates run #183: SUCCESS;
- implementation branch deleted after verified merge.

Q2 added `src/Payment/CheckoutPayload.php` to the baseline-free PHPStan level 5/PHP 7.2 scope and expanded executable PHPUnit characterization across strict checkout tokens, exact provider-decimal handling, JSON-number injection, Store API classification, redirects and provider-text normalization. It removed only three analyzer-proven unreachable checks and changed no observable payment contract.

## Closed Q3 contract

Q3 is DONE / VERIFIED and added `src/Payment/StatusRateGate.php` and `src/Payment/OrderLock.php` to the same baseline-free analyzer and risk-focused PHPCS scopes. Development-only option/`wpdb` fixtures and deterministic tests characterize:

- exact 30-per-minute credential/mode-scoped atomic status slots and stale-bucket cleanup;
- atomic per-order lock acquisition, exact-token release, 45-second expiry and exact-record compare-and-swap takeover;
- contested takeover races, malformed records and option-cache invalidation.

Q3 changed no executable production statement. PHPDoc retains positive-integer order identities, and the only PHPCS annotations are exact-line prepared-SQL false-positive annotations immediately after fixed-placeholder preparation.

## Closed Q4 contract

Q4 is DONE / VERIFIED and added `src/Payment/StatusVerifier.php` to baseline-free PHPStan and PHPCS. Development-only WordPress HTTP fixtures and PHPUnit tests characterize:

- invalid gateway/order/track/credential boundaries before rate or HTTP mutation;
- exact sandbox/live HTTPS destinations and rejection of user info, ports, foreign hosts, queries, fragments and wrong paths before Bearer transport;
- no redirects, TLS verification, finite timeout, exact HTTP 201 and strict provider envelope handling;
- exact track/requested-order/reference/currency/decimal binding;
- CAPTURED payment-ID requirements and fail-closed nonterminal/unknown classification.

Q4 changed no executable production statement. Its PHPDoc reflects existing defensive mixed-input guards only.

Q4 closure evidence remains pinned: PR #30 final reviewed head `8543bdfce1a4e216200791dc5637b646f49bcb59`, exact reviewed tree `ad5ae98d5e935bb48d1441f94e130f5d3adb3ca9`, exact-head Quality Gates run #194, squash merge `4b3db92b0ded0c598bad0ab677babab9e6102811`, post-merge Quality Gates run #195 and implementation branch deleted after verified merge.

## Closed Q5 contract

Q5 is DONE / VERIFIED and added `src/Provider/PaymentMethodAvailability.php` to baseline-free PHPStan and PHPCS. Development-only transient/advisory-lock fixtures and PHPUnit tests characterize exact credential/mode/site identities, strict schema-3 positive and negative caches, non-blocking lock contention, the durable 65-second gate, provider failure caching and bounded payment-button normalization. Q5 changed no executable production statement.

## Q6 purpose

Expand baseline-free static analysis and executable PHPUnit characterization into `src/Admin/GatewaySettings.php`, the extracted admin boundary that owns the exact 21-field gateway schema, subscription/save-card dependency normalization, gateway post-data validation, bounded single-allocation presentation sanitation, escaped rendering and exact settings-page asset scopes.

Q6 is deliberately limited to:

- exact field keys, order, defaults and runtime setting identities;
- the existing subscription-to-save-card dependency normalization;
- missing API-key and enabled-allocation completeness failures;
- clearing the five runtime allocation fields when multi-merchant allocation is disabled;
- retaining only the five non-secret historical presentation fields during JSON sanitation;
- context-escaped single-allocation rendering with no credential fields;
- exact WooCommerce gateway-section and checkout-settings asset scopes, handles, paths, dependencies and versions.

`GatewaySettings` does not own checkout Charge construction, provider transport, payment truth, callbacks, reconciliation, saved-card identity, subscription mutation, scheduler/cycle claims, billing attempts or arbitrary marketplace splitting.

## Q6 scope

Q6 may:

- add `GatewaySettings.php` to the existing baseline-free PHPStan level 5/PHP 7.2 and risk-focused PHPCS scopes;
- add bounded development-only WordPress/WooCommerce translation, sanitation, escaping and enqueue symbols and deterministic fixtures;
- add PHPUnit characterization for schema, normalization, validation, sanitation, escaped rendering and exact asset scopes;
- reconcile only analyzer-proven type documentation or fail-closed return normalization when characterization protects the existing contract;
- add a permanent Quality Platform Gateway Settings harness;
- retain the exact Q1 Composer lock, tool versions, analysis level, PHPCS rules and PHPStan no-baseline/no-`ignoreErrors` rule.

Q6 may not change `woocommerce_upayments_settings`, any field key/order/default, the five runtime allocation keys, the single-additional-merchant boundary, provider payloads, credentials, payment truth, order mutation, saved-card identity, scheduler/cycle-claim/billing-attempt state, subscription mutation, protected compatibility identities or runtime Composer behavior.

## Q6 acceptance

Q6 may be merged only when:

1. PHPUnit covers every named settings schema/validation/sanitation/rendering/asset boundary and passes with no risky tests or warnings;
2. PHPStan level 5 passes on all Q1-Q6 modules against PHP 7.2 with no baseline or `ignoreErrors` entries;
3. PHPCS/WPCS, Composer validation, locked install and dependency audit remain clean;
4. the Q1 **74/0**, Q2 **64/0**, Q3 **69/0**, Q4 **68/0**, Q5 **83/0** and new Q6 permanent harnesses are green;
5. PHP 7.2 and PHP 8.2 distributed-source syntax jobs remain green;
6. the protected H12 prerequisite aggregator still rejects every non-success upstream result;
7. every historical and architecture regression remains green with unchanged payment/security counts;
8. exact-head independent review is clean with zero unresolved valid findings;
9. merge, post-merge CI and branch cleanup are independently verified.

## Non-claims

Q6 is a bounded static-analysis and deterministic unit-characterization tranche. It is not WordPress, WooCommerce, PHP, HPOS, Checkout Blocks, WPML/WCML, browser, accessibility, performance, penetration-test, PCI/compliance or production certification. It is also not live-provider certification; deterministic fixtures do not replace later runtime integration or compatibility certification.

Later quality tranches expand WordPress/WooCommerce integration tests, further static-analysis scope, compatibility matrices, mutation testing, CodeQL and browser tooling only when each protects a named risk.
