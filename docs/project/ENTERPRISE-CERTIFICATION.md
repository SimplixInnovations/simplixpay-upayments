# Enterprise Compatibility Certification

**Status:** RUNTIME FOUNDATION VERIFIED / DECLARATIONS PENDING

**Verified source base:** `93cc66f47847384540cc4ad293e54bc8dc2d6d12`

**Verified candidate head:** `1178ffa31687298761f06ef775b691a8433c2578`

**Program branch:** `enterprise/compatibility-certification`

## Evidence rule

A matrix cell becomes **VERIFIED** only after the exact WordPress, WooCommerce and PHP versions run the real plugin inside an installed WordPress/WooCommerce site and the required activation, checkout-registration and order-storage assertions all pass. Source inspection, syntax-only jobs, Q1-Q19 or H12 do not substitute for this evidence.

This record certifies only the runtime behaviors exercised below. It does not by itself certify provider sandbox/card completion, subscriptions, saved cards, wallets, refunds, multi-merchant marketplace routing, WPML/WCML, RTL, browsers/devices, accessibility, performance, production operations or release packaging.

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

The accepted `UPayments.php` architecture byte ratchet advanced from **88,839** to the exact reviewed post-fix size **88,862** without removing the permanent semantic architecture assertions.

Certification fixtures were then tightened so malformed option storage is written directly to the WordPress options table without firing WooCommerce update observers. Exact head `1178ffa31687298761f06ef775b691a8433c2578` additionally proves:

- the complete serialized protected gateway settings option is byte-identical before and after SimplixPay activation;
- the SimplixPay activation callback actually executes by replacing a seeded checkout-page marker with `[woocommerce_checkout]`;
- Classic gateway ID `upayments` is registered in the real WooCommerce payment gateway registry.

## Verified runtime matrix

Exact workflow: **Compatibility Certification #14 — SUCCESS**

Every row below passed with both **legacy posts/order storage** and **HPOS authoritative order storage** where shown. Every job installed real WordPress and WooCommerce, activated SimplixPay, verified the Classic gateway, verified Blocks registration/availability including malformed settings, selected the requested authoritative order store, and created/reloaded/deleted a real WooCommerce order through CRUD while preserving `upayments` payment identity and protected UPayments order metadata.

| WordPress | WooCommerce | PHP | Legacy | HPOS | Purpose |
|---|---|---:|---|---|---|
| 7.1 | 11.1.0 | 8.3 | **VERIFIED** | **VERIFIED** | Current recommended runtime |
| 7.1 | 11.1.0 | 8.4 | **VERIFIED** | **VERIFIED** | Current modern PHP |
| 7.0.4 | 11.1.0 | 8.3 | **VERIFIED** | **VERIFIED** | Woo 11.1 WordPress floor series |
| 7.0.4 | 11.0.1 | 8.3 | **VERIFIED** | **VERIFIED** | Previous WooCommerce line |
| 7.0.4 | 10.8.1 | 8.3 | **VERIFIED** | **VERIFIED** | WordPress 7.0 / older supported Woo line |
| 7.1 | 10.8.1 | 8.3 | **VERIFIED** | **VERIFIED** | New WordPress / older supported Woo line |
| 6.9.7 | 10.8.1 | 8.3 | **VERIFIED** | **VERIFIED** | Woo 10.8 WordPress floor series |
| 6.9.7 | 10.8.1 | 7.4 | **VERIFIED** | **VERIFIED** | Legacy ecosystem floor characterization |

WooCommerce 11.1.0 itself requires WordPress 7.0+, so the proposed WordPress 6.9.7 / WooCommerce 11.1.0 combination was removed as upstream-invalid rather than misclassified as a SimplixPay failure.

A passing PHP 7.4 cell proves compatibility for this bounded matrix only. PHP 7.4 is end-of-life and is not the recommended production runtime.

## Exact candidate verification

Exact head `1178ffa31687298761f06ef775b691a8433c2578`:

- Quality Gates #486: **SUCCESS**
- Compatibility Certification #14: **SUCCESS**
- all **16/16** runtime/storage jobs: **SUCCESS**
- Governance: **SUCCESS**
- Quality Platform: **SUCCESS**
- PHP 7.2 syntax lane: **SUCCESS** — syntax evidence only, not runtime certification
- PHP 8.2 syntax lane: **SUCCESS**
- protected H12 Regression Harness: **SUCCESS**
- CodeQL: **SUCCESS**
- CodeQL Analyze (actions): **SUCCESS**
- CodeQL Analyze (javascript-typescript): **SUCCESS**

## Feature declaration position

The existing `cart_checkout_blocks` compatibility declaration is now backed by the real runtime matrix for the tested WordPress/WooCommerce/PHP cells above.

HPOS `custom_order_tables` compatibility has real enabled/disabled WooCommerce CRUD evidence across the same matrix, but the declaration is intentionally deferred to the next test-first metadata/declaration tranche so the declaration itself receives RED -> GREEN coverage and another full matrix run.

## Metadata position

Current public header metadata remains intentionally unchanged on this certification-foundation candidate:

- `Requires at least: 5.6`
- `Requires PHP: 7.2`
- no `WC requires at least`
- no `WC tested up to`

Those values are not converted into claims until the next metadata tranche adds test-first assertions and re-runs the full matrix. The evidence currently supports evaluating:

- WordPress minimum series **6.9**
- WordPress tested series **7.1**
- WooCommerce minimum series **10.8**
- WooCommerce tested series **11.1**
- PHP runtime floor **7.4**

## Next certification action

1. test-first release metadata and `custom_order_tables` declaration;
2. full runtime matrix rerun after declarations;
3. merge and post-merge verify the certification foundation/declaration state;
4. provider sandbox certification;
5. feature-specific certification;
6. multilingual/RTL/browser/accessibility/performance and operations certification;
7. deterministic release packaging and packaged-artifact installation certification.
