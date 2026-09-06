# Compatibility Matrix

This document separates **provider/upstream capability claims** from **SimplixPay UPayments verification**. A feature is not marked **Verified** until it has passed a documented reproducible environment and the supporting evidence has been reviewed.

> Current project posture: **pre-release Enterprise Compatibility Certification**. Repository readiness, Phase 0 release identity/updater ownership, Phase 9I historical token-identity migration, Provider Contract & Payment Lifecycle, Security Threat-Model Closure, Architecture A1-A5 and Quality Platform Q1-Q19 are DONE / VERIFIED. The runtime bootstrap still carries transitional physical basename/text-domain compatibility identities pending a separately proven distribution migration. Neither green Q1-Q19/H12 CI nor the absence of a known defect is a broad compatibility certification.

| Area | Provider/upstream position | Simplix status | Notes |
|---|---|---|---|
| Classic WooCommerce Checkout | Supported upstream | Pending regression certification | Core integration path; broad certification pending. |
| Cart/Checkout Blocks — standard products | UPayments documents support | Pending independent certification | Registration and real checkout behavior must both be tested. |
| Blocks — subscription/tokenization | UPayments guidance has historically favored Classic for subscription reliability | Pending validation | Test separately from standard products and saved-card flows. |
| HPOS | No current Simplix certification | Runtime certification required | Source/payment hardening is closed; HPOS enabled/disabled order behavior must still pass the executable certification matrix before any Woo feature declaration. |
| WPML / String Translation | Historical upstream defect reproduced | Fix implemented — certification pending | Existing remediation remains subject to full WPML/WCML validation and planned text-domain identity migration. |
| Multicurrency / WCML | Provider/platform dependent | Audit required | Currency amount/source/provider semantics and display/charge consistency require dedicated testing. |
| RTL / Arabic | Platform/theme capability | Audit required | Admin/checkout/account UI and provider return/error flows require dedicated validation. |
| My Account / theme interoperability | Historical generic CSS conflict reproduced | Fix implemented — certification pending | Cross-theme/device/accessibility validation remains open. |
| PHP versions | WordPress 7.0/7.1 support PHP 7.4–8.5; WooCommerce recommends PHP 8.3+ | Runtime certification required | Current plugin header remains transitional until exact SimplixPay matrix cells prove a support floor and ceiling. |
| WordPress versions | WordPress 7.1 is the current maintained release line | Runtime certification required | `Requires at least` / `Tested up to` remain unchanged until exact SimplixPay matrix cells pass. |
| WooCommerce versions | WooCommerce 11.1.0 is the current stable release | Runtime certification required | `WC requires at least` / `WC tested up to` will be added only from exact SimplixPay matrix evidence. |
| Saved cards / tokenization | Upstream feature | H12 identity hardening + Phase 9I migration engineering verified; runtime certification pending | Existing-store migration contracts are closed and permanent regressions; real WordPress/WooCommerce feature certification remains open. |
| Multi-merchant | Upstream feature | Provider contract hardened; runtime certification pending | Current supported scope remains one additional merchant allocation with exact amount/routing rules; broader split routing is not certified. |
| Webhook payment updates | `notificationUrl` documented by UPayments | Audit required | Validation, idempotency, order matching, replay/failure behavior pending. |
| Payment status verification | UPayments status API exists | Provider/lifecycle contract verified; runtime/provider certification pending | Authenticated status binding, bounded reconciliation and fail-closed lifecycle semantics are permanent regressions; live environment behavior remains separately certifiable. |
| Refunds | UPayments refund capability exists | Audit required | Full/partial/idempotency/reconciliation semantics pending. |
| Subscriptions / auto deduction | Upstream feature | Targeted safety hardening exists; broad certification pending | Concurrency/idempotency/lifecycle/recovery matrix remains open. |
| Wallet methods | Provider capability varies by environment/account | Audit required | Apple Pay/Google Pay/Samsung Pay and related method availability must be tested against real provider/account requirements. |
| Accessibility | WooCommerce/theme/browser dependent | Audit required | Keyboard, focus, screen-reader, contrast and error-state testing pending. |
| Browser/device matrix | Platform dependent | Audit required | Desktop/mobile and supported browser/device matrix not yet certified. |
| Performance/stability | Platform/store dependent | Audit required | No public performance badge/claim until repeatable regression thresholds exist. |

## H12 baseline scope

Current CI reproduces the custom H12 regression baseline:

- PHP: **1927 PASS / 0 FAIL**;
- Blocks: **144 PASS / 0 FAIL**.

That baseline protects specific payment/token/harness contracts already characterized. It does **not** establish general WordPress/WooCommerce/PHP/HPOS/Blocks/WPML/browser/device/performance support.

## Evidence rule

A Simplix **Verified** entry must identify exact WordPress, WooCommerce and PHP versions plus checkout mode, HPOS state, multilingual/multicurrency state, relevant plugin/theme/browser versions and the payment feature exercised. Validation must be reproducible and linked to test/CI/review evidence where practical.

Compatibility status values should mean:

- **Verified** — independently tested against a documented environment and evidence set;
- **Known issue** — reproducible defect tracked for remediation;
- **Fix implemented — certification pending** — source remediation exists but required runtime matrix is incomplete;
- **Provider/upstream claim** — documented externally but not independently certified here;
- **Audit required / Pending validation** — no Simplix certification yet.

## Primary documentation

### UPayments

- WooCommerce integration: https://developers.upayments.com/reference/woocommerce
- Webhooks: https://developers.upayments.com/reference/webhook
- Payment status: https://developers.upayments.com/reference/checkpaymentstatus
- Saved cards: https://developers.upayments.com/reference/retrievecustomercards

### WooCommerce

- Extension compatibility: https://developer.woocommerce.com/docs/extensions/best-practices-extensions/compatibility
- Cart/Checkout extensibility: https://developer.woocommerce.com/docs/block-development/extensible-blocks/cart-and-checkout-blocks/
- HPOS extension guidance: https://developer.woocommerce.com/docs/features/orders/high-performance-order-storage/recipe-book/

## Public-claim rule

Do not add WordPress/WooCommerce/PHP/HPOS/Blocks/WPML/performance compatibility badges merely because the plugin loads or H12 CI is green. Public badges must correspond to an independently verified matrix recorded here.
