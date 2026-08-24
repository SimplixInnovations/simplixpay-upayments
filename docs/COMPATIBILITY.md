# Compatibility Matrix

This document separates **provider/upstream capability claims** from **SimplixPay UPayments verification**. A feature is not marked Verified until it has passed a documented reproducible environment.

> Current project posture: **R0 — engineering hardening**. The plugin bootstrap still carries legacy upstream public metadata during Phase 0; that is not a compatibility certification.

| Area | Provider/upstream position | Simplix status | Notes |
|---|---|---|---|
| Classic WooCommerce Checkout | Supported upstream | Pending regression validation | Core integration path; broad certification pending. |
| Cart/Checkout Blocks — standard products | UPayments documents support | Pending independent validation | Registration and real checkout behavior must both be tested. |
| Blocks — subscription/tokenization | UPayments guidance has historically favored Classic for subscription reliability | Pending validation | Test separately from standard products. |
| HPOS | No current Simplix certification | Audit required | Direct post/order access must be reviewed and tested. |
| WPML / String Translation | Historical upstream defect reproduced | Fix implemented — certification pending | Existing remediation remains subject to full WPML/WCML validation and planned text-domain identity migration. |
| My Account / theme interoperability | Historical generic CSS conflict reproduced | Fix implemented — certification pending | Cross-theme/device/accessibility validation remains open. |
| PHP versions | No broad current Simplix certification | Audit required | Header claims do not substitute for runtime/static evidence. |
| Saved cards / tokenization | Upstream feature | H12 identity hardening verified; migration/regression certification pending | Historical migration Phase 9I remains required for existing stores. |
| Multi-merchant | Upstream feature | Pending regression validation | Provider contract/sum/routing behavior must be audited. |
| Webhook payment updates | `notificationUrl` documented by UPayments | Audit required | Validation, idempotency, order matching, replay/failure behavior pending. |
| Payment status verification | UPayments status API exists | Audit required | Retry/rate-limit/reconciliation contract must be frozen. |
| Refunds | UPayments refund capability exists | Audit required | Full/partial/idempotency/reconciliation semantics pending. |
| Subscriptions / auto deduction | Upstream feature | Targeted safety hardening exists; broad certification pending | Concurrency/idempotency/lifecycle/recovery matrix remains open. |

## Evidence rule

A Simplix **Verified** entry must identify exact WordPress, WooCommerce, PHP, checkout mode, HPOS state, multilingual state, relevant plugin versions and payment feature. Validation must be reproducible and linked to CI/test/review evidence where practical.

## Primary documentation

- UPayments WooCommerce: https://developers.upayments.com/reference/woocommerce
- UPayments webhook: https://developers.upayments.com/reference/webhook
- UPayments payment status: https://developers.upayments.com/reference/checkpaymentstatus
- UPayments saved cards: https://developers.upayments.com/reference/retrievecustomercards
- WooCommerce compatibility guidance: https://developer.woocommerce.com/docs/extensions/best-practices-extensions/compatibility
- WooCommerce HPOS: https://developer.woocommerce.com/docs/features/orders/high-performance-order-storage/recipe-book/
