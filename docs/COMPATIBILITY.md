# Compatibility Matrix

This document separates **provider/upstream capability claims** from **SimplixPay UPayments verification**. A feature is marked **Verified** only when it has passed a documented reproducible environment and the supporting evidence has been reviewed.

> Current project posture: **pre-release Enterprise Compatibility Certification**. Repository readiness, Phase 0, Phase 9I, Provider Contract & Payment Lifecycle, Security Threat-Model Closure, Architecture A1-A5 and Quality Platform Q1-Q19 are DONE / VERIFIED. The core WordPress/WooCommerce/PHP runtime matrix, Classic gateway registration, standard Cart/Checkout Blocks registration/availability and HPOS order CRUD are now independently verified on the exact matrix below. This is not broad feature, provider, multilingual, browser, performance or production certification.

| Area | Provider/upstream position | Simplix status | Notes |
|---|---|---|---|
| Classic WooCommerce Checkout registration/runtime bootstrap | Supported upstream | **Verified** | Real WooCommerce gateway registry contains exact ID `upayments` in every certified matrix cell. Provider payment completion remains separately governed by the closed Payment Lifecycle contract and provider sandbox certification. |
| Cart/Checkout Blocks — standard payment-method registration/availability | UPayments documents support | **Verified** | Real Woo Blocks registry loads `upayments`; enabled/disabled/fresh-default/malformed-settings behavior passes the Q18 contract in every certified matrix cell. |
| Blocks — subscription/tokenization end-to-end | Provider/account behavior dependent | Pending feature certification | Registration is verified, but recurring/tokenized end-to-end payment behavior is a separate feature matrix. |
| HPOS | WooCommerce stable feature | **Verified / declared compatible** | Legacy and HPOS authoritative storage both pass real Woo order create/reload/delete CRUD while preserving payment method identity and protected UPayments metadata across every certified matrix cell. |
| WPML / String Translation | Historical upstream defect reproduced | Fix implemented — certification pending | Existing remediation remains subject to full WPML/WCML validation and planned text-domain identity migration. |
| Multicurrency / WCML | Provider/platform dependent | Audit required | Currency amount/source/provider semantics and display/charge consistency require dedicated testing. |
| RTL / Arabic | Platform/theme capability | Audit required | Admin/checkout/account UI and provider return/error flows require dedicated validation. |
| My Account / theme interoperability | Historical generic CSS conflict reproduced | Fix implemented — certification pending | Cross-theme/device/accessibility validation remains open. |
| PHP versions | WordPress/WooCommerce version-dependent | **Verified: PHP 7.4, 8.3, 8.4 in exact cells below** | PHP 7.4 is EOL and is a compatibility floor, not the recommended production runtime. PHP 8.3+ remains the recommended modern target. |
| WordPress versions | Current maintained line 7.1 | **Verified: 6.9.7, 7.0.4, 7.1** | Public metadata: `Requires at least: 6.9`; `Tested up to: 7.1`. |
| WooCommerce versions | Current stable line 11.1 | **Verified: 10.8.1, 11.0.1, 11.1.0 in exact cells below** | Public metadata: `WC requires at least: 10.8`; `WC tested up to: 11.1`. |
| Saved cards / tokenization | Upstream feature | H12 identity hardening verified; end-to-end certification pending | H12/Phase 9I protect identity/provenance; live provider retrieval/charge feature matrix remains separate. |
| Multi-merchant | Upstream feature | Bounded contract verified; provider certification pending | Current support remains one additional merchant allocation only; end-to-end sandbox/provider validation remains open. |
| Webhook payment updates | `notificationUrl` documented by UPayments | Authenticated-status lifecycle verified; provider signature unresolved | Browser/webhook payload remains non-authoritative; authenticated Get Payment Status is financial truth. |
| Payment status verification | UPayments status API exists | Local lifecycle contract verified; sandbox certification pending | Host/TLS/schema/binding/retry/rate semantics are permanently regression-tested. |
| Refunds | UPayments refund capability exists | **Unsupported** | Automatic Woo refunds remain intentionally unsupported pending durable idempotency/reconciliation design. |
| Subscriptions / auto deduction | Upstream feature | Targeted safety hardening exists; broad certification pending | Concurrency/idempotency/lifecycle/recovery provider matrix remains open. |
| Wallet methods | Provider capability varies by environment/account | Audit required | UPayments sandbox does not provide full Apple Pay/Google Pay/Samsung Pay completion coverage; availability and production-account behavior require separate evidence. |
| Accessibility | WooCommerce/theme/browser dependent | Audit required | Keyboard, focus, screen-reader, contrast and error-state testing pending. |
| Browser/device matrix | Platform dependent | Audit required | Desktop/mobile and supported browser/device matrix not yet certified. |
| Performance/stability | Platform/store dependent | Audit required | No public performance badge/claim until repeatable regression thresholds exist. |

## Verified core runtime matrix

Every row below runs in a fresh real WordPress/WooCommerce/MySQL installation. Each combination is executed with both **legacy posts/order storage** and **HPOS authoritative storage**.

The certification asserts:

- SimplixPay activation succeeds with malformed pre-existing gateway settings and does not mutate the complete serialized protected option;
- the activation callback actually executes;
- Classic gateway ID `upayments` registers;
- the real Woo Blocks payment registry registers `upayments` and enforces exact availability semantics;
- the public support metadata matches the matrix-derived values;
- WooCommerce reports both `cart_checkout_blocks` and `custom_order_tables` as compatible features;
- a real Woo order can be created, saved, reloaded and deleted through CRUD while preserving SimplixPay payment identity and protected UPayments order metadata.

| WordPress | WooCommerce | PHP | Legacy storage | HPOS |
|---|---|---:|---|---|
| 7.1 | 11.1.0 | 8.4 | **Verified** | **Verified** |
| 7.1 | 11.1.0 | 8.3 | **Verified** | **Verified** |
| 7.0.4 | 11.1.0 | 8.3 | **Verified** | **Verified** |
| 7.0.4 | 11.0.1 | 8.3 | **Verified** | **Verified** |
| 7.0.4 | 10.8.1 | 8.3 | **Verified** | **Verified** |
| 7.1 | 10.8.1 | 8.3 | **Verified** | **Verified** |
| 6.9.7 | 10.8.1 | 8.3 | **Verified** | **Verified** |
| 6.9.7 | 10.8.1 | 7.4 | **Verified** | **Verified** |

WooCommerce 11.1 requires WordPress 7.0+, so WordPress 6.9 / WooCommerce 11.1 is intentionally not a certification cell.

## Permanent regression baseline

The custom Q1-Q19/H12 platform remains mandatory beside the real compatibility matrix:

- H12 PHP: **1927 PASS / 0 FAIL**;
- H12 Blocks: **144 PASS / 0 FAIL**;
- Quality Platform Q1-Q19: **DONE / VERIFIED**.

That baseline protects specific payment/token/migration/security contracts. It does not substitute for the separate feature/provider/browser/performance/release certification still listed as pending above.

## Evidence rule

A Simplix **Verified** entry must identify exact WordPress, WooCommerce and PHP versions plus checkout mode, HPOS state and the feature exercised. Validation must be reproducible and linked to CI/review evidence where practical.

Compatibility status values mean:

- **Verified** — independently tested against a documented environment and evidence set;
- **Known issue** — reproducible defect tracked for remediation;
- **Fix implemented — certification pending** — source remediation exists but required runtime matrix is incomplete;
- **Provider/upstream claim** — documented externally but not independently certified here;
- **Audit required / Pending validation** — no Simplix certification yet;
- **Unsupported** — intentionally not implemented/advertised.

## Primary documentation

### UPayments

- WooCommerce integration: https://developers.upayments.com/reference/woocommerce
- Test environment: https://developers.upayments.com/reference/test-environment-details
- Webhooks: https://developers.upayments.com/reference/webhook
- Payment status: https://developers.upayments.com/reference/checkpaymentstatus
- Saved cards: https://developers.upayments.com/reference/retrievecustomercards

### WooCommerce

- Extension compatibility: https://developer.woocommerce.com/docs/extensions/best-practices-extensions/compatibility
- Cart/Checkout extensibility: https://developer.woocommerce.com/docs/block-development/extensible-blocks/cart-and-checkout-blocks/
- HPOS extension guidance: https://developer.woocommerce.com/docs/features/orders/high-performance-order-storage/recipe-book/

## Public-claim rule

Do not broaden WordPress/WooCommerce/PHP/HPOS/Blocks/WPML/performance claims beyond the exact verified evidence above. A green neighboring version, static analyzer or H12 run is not evidence for an untested environment or feature.


## Feature/operations certification status

The permanent compatibility matrix is being extended with installed-runtime evidence for saved-card/token identity, subscription pre-dispatch eligibility, the existing single additional-merchant allocation, and non-destructive activation/deactivation/uninstall behavior.

This does **not** broaden support to automatic refunds, arbitrary marketplace split routing, unattended live subscription deductions, provider webhook trust, or destructive uninstall cleanup. Those remain outside the certified contract unless separately implemented and proven.
