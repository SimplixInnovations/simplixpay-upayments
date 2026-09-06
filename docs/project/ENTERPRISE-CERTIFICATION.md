# Enterprise Compatibility Certification

**Status:** CORE PLATFORM + SUPPORT DECLARATIONS VERIFIED / PROVIDER SANDBOX VERIFIED

**Verified runtime-foundation merge:** `5e4f33d24bcaed1032691c564b570e60c95a9483`

**Verified support-declaration merge:** `bfcd572f4eb27945a98b158743a4826c9a8894ea`

## Feature and operations certification — DONE / VERIFIED

Task 6 permanently extends the real-runtime matrix with four bounded certification surfaces on every WordPress/WooCommerce/PHP × legacy/HPOS cell. Final Task 6 head `355a871636f2df00c0bd7357a810289be284b58c` passed Compatibility Certification #67 across all 16 cells, Quality Gates #539 including H12, Release Artifact #21 including packaged legacy/HPOS installs and CodeQL, then squash-merged as `6c19dbcfab607f81c4ff28f7bd088a87575adbf3`. Post-merge main repeated all permanent checks successfully.

The certification covers:

- **Saved cards/tokenization:** guest rejection before transport or identity creation; authenticated canonical identity establishment in the real WordPress user store; exact saved-card retrieval binding; exact selected-card membership; foreign-card rejection; malformed provenance fails closed before retrieval.
- **Subscriptions:** real Woo subscription products and orders prove product-level opt-out, mixed-order rejection, guest rejection, strict plan/interval rejection, and that each invalid case stops before provider transport. A valid Classic subscription is permitted to advance to the bounded token-initialization seam, while the test intentionally prevents external mutation.
- **Multi-merchant:** only the inherited single additional-merchant allocation is certified. The real checkout orchestrator must emit exactly one `extraMerchantData` allocation whose amount equals the order amount; malformed allocation configuration must reject before Charge.
- **Operations/data retention:** merchant settings and payment/token metadata must survive deactivation, reactivation, and WordPress uninstall-hook execution. The migration CLI module is required in WP-CLI context, the admin module is absent outside admin, and explicit admin boot must not expose credentials.

These tests are fixture/boundary certification only. They do **not** execute live saved-card mutations, subscription auto-deduction, arbitrary marketplace splits, or destructive data erasure.

Exact candidate run evidence is recorded only after the new matrix completes on one immutable head.

## Evidence rule

A matrix cell becomes **VERIFIED** only after the exact WordPress, WooCommerce and PHP versions run the real plugin inside an installed WordPress/WooCommerce site and the required activation, checkout-registration, declaration and order-storage assertions all pass. Source inspection, syntax-only jobs, Q1-Q19 or H12 do not substitute for this evidence.

This record certifies only the runtime behaviors exercised below. It does not by itself certify provider sandbox/card completion, subscriptions, saved cards, wallets, refunds, multi-merchant marketplace routing, WPML/WCML, RTL, browsers/devices, accessibility, performance, production operations or release packaging.

## Runtime foundation — DONE / VERIFIED

PR #47 established the permanent real-runtime certification framework.

Final reviewed head:

`d46abc86f329a2b0ae24e79c18c371db2083a43a`

Exact-head evidence:

- Quality Gates #490: **SUCCESS**
- Compatibility Certification #18: **SUCCESS**
- all **16/16** real runtime/storage jobs: **SUCCESS**
- H12 Regression Harness: **SUCCESS**
- CodeQL: **SUCCESS**
- zero unresolved review threads

Verified squash merge:

`5e4f33d24bcaed1032691c564b570e60c95a9483`

Post-merge evidence:

- Quality Gates #491: **SUCCESS**
- Compatibility Certification #19: **SUCCESS**
- all **16/16** real runtime/storage jobs: **SUCCESS**
- CodeQL Analyze (actions): **SUCCESS**
- CodeQL Analyze (javascript-typescript): **SUCCESS**
- implementation branch auto-deleted

The compatibility workflow is permanent and runs on both pull requests and pushes to `main`. Quality Gates Governance requires its installer, test files and certification record.

## RED -> GREEN activation hardening

The first real-runtime RED was captured on test-only head `23cc9edfa3a905730fbb3924318f09a06803e750`:

- Compatibility Certification #1: **FAILURE**
- Quality Gates #473: **SUCCESS**
- WordPress **7.1**
- WooCommerce **11.1.0**
- PHP **8.3.33**
- MySQL **8.0**
- activation failed at `UPayments.php:1901` with `Cannot use object of type stdClass as array` after a malformed object-valued `woocommerce_upayments_settings` option was seeded.

Minimal production fix `e912819ad30c3be980c18fe104a1961f306a572a` added only the `is_array($settings)` guard before reading `enable_block_checkout`. Compatibility Certification #2 then passed the real activation scenario.

The accepted `UPayments.php` architecture byte ratchet advanced from **88,839** to **88,862** for this 23-byte compatibility guard without removing permanent semantic assertions.

The certification fixture was subsequently tightened so malformed option storage is written directly to the WordPress options table without firing WooCommerce update observers. Final PR #47 evidence also proves:

- the complete serialized protected gateway settings option is byte-identical before and after SimplixPay activation;
- the SimplixPay activation callback actually executes by replacing a seeded checkout-page marker with `[woocommerce_checkout]`;
- Classic gateway ID `upayments` is registered in the real WooCommerce payment gateway registry.

## Verified runtime matrix

Every row below passed with both **legacy posts/order storage** and **HPOS authoritative order storage**. Every job installs real WordPress and WooCommerce, activates SimplixPay, verifies Classic and Blocks registration/availability, selects the requested authoritative order store, and creates/reloads/deletes a real WooCommerce order through CRUD while preserving `upayments` payment identity and protected UPayments order metadata.

| WordPress | WooCommerce | PHP | Legacy | HPOS | Purpose |
|---|---|---:|---|---|---|
| 7.1 | 11.1.0 | 8.4 | **VERIFIED** | **VERIFIED** | Current modern PHP |
| 7.1 | 11.1.0 | 8.3 | **VERIFIED** | **VERIFIED** | Current recommended runtime |
| 7.0.4 | 11.1.0 | 8.3 | **VERIFIED** | **VERIFIED** | Woo 11.1 WordPress floor series |
| 7.0.4 | 11.0.1 | 8.3 | **VERIFIED** | **VERIFIED** | Previous WooCommerce line |
| 7.0.4 | 10.8.1 | 8.3 | **VERIFIED** | **VERIFIED** | WordPress 7.0 / older supported Woo line |
| 7.1 | 10.8.1 | 8.3 | **VERIFIED** | **VERIFIED** | New WordPress / older supported Woo line |
| 6.9.7 | 10.8.1 | 8.3 | **VERIFIED** | **VERIFIED** | Woo 10.8 WordPress floor series |
| 6.9.7 | 10.8.1 | 7.4 | **VERIFIED** | **VERIFIED** | Legacy ecosystem floor characterization |

WooCommerce 11.1 itself requires WordPress 7.0+, so WordPress 6.9 / WooCommerce 11.1 is intentionally excluded as an upstream-invalid combination.

A passing PHP 7.4 cell proves compatibility for this bounded matrix only. PHP 7.4 is end-of-life and is not the recommended production runtime.

## Support metadata — RED -> GREEN VERIFIED

RED-A test-only head `92d6f21ccde6795c8a9b97915f3c66d8d005e581` added a real-runtime metadata assertion while leaving production headers unchanged.

Compatibility Certification #20 failed exactly at:

`FAIL: WordPress minimum support series is matrix-proven 6.9`

Minimal GREEN-A production change `d4fbf937c9ed81816e16c6f74d6f6e7e4721869a` changed only plugin support headers to:

- `Requires at least: 6.9`
- `Tested up to: 7.1`
- `Requires PHP: 7.4`
- `WC requires at least: 10.8`
- `WC tested up to: 11.1`

After updating the exact architecture byte ratchet from **88,862** to **88,938**, exact candidate `b247965c2ff7e98c00b394b2672b5ef2ba14fba6` passed:

- Quality Gates #495: **SUCCESS**
- Compatibility Certification #23: **SUCCESS**
- all **16/16** runtime/storage jobs: **SUCCESS**

## HPOS declaration — RED -> GREEN VERIFIED

RED-B head `e5e1a324970cf98c4c0218d5996b954f6cc04729` extended the real-runtime metadata test to WooCommerce's public feature registry after `woocommerce_init`.

The test proved:

- `cart_checkout_blocks` remained present in the compatible registry;
- Woo returned both compatible and incompatible feature arrays;
- `custom_order_tables` was absent.

Compatibility Certification #24 failed exactly at:

`FAIL: HPOS custom_order_tables compatibility is declared in the real WooCommerce registry`

Minimal GREEN-B production change `1849ee900a0da21c3c39fa85975417063a8b4a12` added only:

```php
\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
    'custom_order_tables',
    __FILE__,
    true
);
```

inside the existing guarded `before_woocommerce_init` callback beside the already-existing Blocks declaration.

Real runtime cells then passed the metadata/feature-registry assertion and proceeded through Blocks plus HPOS/legacy order CRUD. Quality Gates #497 failed only the intentionally stale architecture byte ratchet. The exact measured `UPayments.php` size after the declaration is **89,102 bytes**, and the ratchet has been advanced without removing semantic architecture checks.

Declaration tranche final head `2c2a6af890cf7304dcbf63cbc7fb03c2be1af7a6` passed Quality Gates #512, Compatibility Certification #40 across all 16 runtime/storage cells, H12 and CodeQL, then squash-merged as signed `bfcd572f4eb27945a98b158743a4826c9a8894ea`. Post-merge `main` passed the permanent 16-cell compatibility matrix, H12, Quality Platform, Governance, syntax lanes and CodeQL.

## Current public platform declarations

The declaration candidate now contains:

- WordPress minimum: **6.9**
- WordPress tested: **7.1**
- WooCommerce minimum: **10.8**
- WooCommerce tested: **11.1**
- PHP minimum: **7.4**
- `cart_checkout_blocks`: **compatible**
- `custom_order_tables`: **compatible**

These are the only platform declarations authorized by the current runtime matrix.

## Source-level HPOS cross-check

A production-tree audit found no direct order access through:

- `get_post_meta` / `update_post_meta` / `delete_post_meta` / `add_post_meta`;
- `$wpdb->posts` / `$wpdb->postmeta`;
- raw `shop_order` assumptions;
- direct `get_post()`/ `wp_update_post()` order paths.

This source evidence supplements—never replaces—the real legacy/HPOS CRUD matrix.

## Non-claims

Still not certified by this platform-core tranche:

- UPayments sandbox Charge/status transport and provider response schema;
- browser/card completion;
- saved-card provider retrieval/charge end-to-end;
- subscription recurring auto-deduction end-to-end;
- wallets;
- arbitrary marketplace multi-split;
- automatic refunds (intentionally unsupported);
- WPML/WCML/multicurrency/RTL;
- browser/device/theme interoperability;
- accessibility;
- performance/stability;
- browser/device/theme interoperability;
- accessibility;
- performance/stability;
- production merchant payment completion or public stable-release readiness.

## Existing-install upgrade / basename decision — VERIFIED

Task 7 runs two installed-package upgrade cells: current WP 7.1 / WC 11.1 / PHP 8.3 and floor WP 6.9.7 / WC 10.8.1 / PHP 8.3.

The retained `simplixpay-upayments/UPayments.php` identity passes upgrade, deactivation/reactivation, rollback, data retention, callback retention, cron retention and duplicate-package characterization.

The RED renamed-main candidate fails identically in both cells with:

- `active_plugins` still containing `simplixpay-upayments/UPayments.php`;
- target `simplixpay-upayments/simplixpay-upayments.php` inactive;
- SimplixPay runtime not loaded.

The first stable release therefore retains `UPayments.php` and text domain `upayments`. The eventual filename/text-domain targets remain deferred migration goals. The installable package currently contains 70 explicit translation calls bound to `upayments`.

## Remaining certification actions

Repository closeout must now reconcile living documents, classify genuinely manual/external UI, multilingual and production evidence, re-run all final automated certification including the bounded provider sandbox, and perform the reserved final whole-plugin review.


## Provider public-sandbox Charge initialization — VERIFIED

Current official UPayments test documentation publishes:

- sandbox API base: `https://sandboxapi.upayments.com/api/v1/`;
- non-whitelabel public test bearer token: `jtest123`;
- Charge endpoint: `POST /charge`;
- successful Charge initialization: HTTP `201`;
- Get Payment Status limit: **30 requests/minute**.

The bounded repository smoke in `tests/provider/sandbox-charge-smoke.php` uses only that documented public non-whitelabel test token. It derives the Charge endpoint through `Simplix\\Pay\\UPayments\\Provider\\EndpointResolver`, preserves TLS verification and redirect-disable transport behavior, and validates the returned payment link through the production `CheckoutPayload::normalize_upayments_redirect_url()` boundary.

### Safety boundary

The automated provider smoke:

- creates exactly one sandbox Charge initialization per workflow job;
- uses a one-minute payment-link expiry;
- never follows the returned payment link;
- never enters card/test-card data;
- never completes or captures a payment;
- never polls payment status;
- never issues a refund;
- never saves/retrieves a card;
- never performs subscription/auto-deduction;
- never uses a merchant production credential;
- never logs the raw provider body, bearer token or session URL.

Exact PR #49 candidate `e7dd5b96b03c2aefe73b44b837df84976b852d83` produced Provider Sandbox Certification **run #1 — SUCCESS**. The public-sandbox job proved: exact documented token gating, endpoint derivation through Simplix `EndpointResolver`, HTTPS host/path, JSON encoding, cURL transport without error, exact HTTP 201, valid JSON, strict `status === true`, structured `data`, production redirect normalization, HTTPS payment-link scheme and bounded UPayments sandbox host.

The sanitized workflow log exposed no raw response body, bearer token or session URL and ended with:

`CERT: UPayments public-sandbox Charge initialization verified; no payment completion attempted.`

This certifies sandbox Charge **transport and initialization schema only**. It is not captured-payment, wallet, saved-card, subscription, refund, webhook-signature or production-merchant certification.

Current provider-document rate boundaries remain explicit:
- Get Payment Status: **30 requests/minute**;
- Check Payment Button Availability: **1 request/minute**.

The automated provider smoke performs no status polling and preserves the plugin's stricter bounded availability/cache behavior.
