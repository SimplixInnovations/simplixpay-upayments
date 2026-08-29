<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset=".github/assets/simplix-innovations-logo-white.svg">
    <source media="(prefers-color-scheme: light)" srcset=".github/assets/simplix-innovations-logo-black.svg">
    <img src=".github/assets/simplix-innovations-logo-black.svg" alt="Simplix Innovations" width="280">
  </picture>
</p>

<h1 align="center">SimplixPay for UPayments</h1>

<p align="center">
  <strong>Independently engineered UPayments payment integration for WooCommerce</strong><br>
  maintained by <a href="https://simplixi.com">Simplix Innovations</a>
</p>

<p align="center">
  <a href="https://github.com/SimplixInnovations/simplixpay-upayments/actions/workflows/quality-gates.yml"><img alt="Quality Gates" src="https://github.com/SimplixInnovations/simplixpay-upayments/actions/workflows/quality-gates.yml/badge.svg?branch=main"></a>
  <a href="https://woocommerce.com/development-services/simplix-innovations-woocommerce-full-service-agency/232995338/"><img alt="Woo Agency Partner" src="https://img.shields.io/badge/Woo-Agency%20Partner-96588A?style=flat-square&logo=woocommerce&logoColor=white"></a>
  <a href="https://simplixi.com"><img alt="Maintained by Simplix Innovations" src="https://img.shields.io/badge/Maintained%20by-Simplix%20Innovations-111111?style=flat-square"></a>
  <a href="LICENSE"><img alt="MIT License" src="https://img.shields.io/badge/License-MIT-2ea44f?style=flat-square"></a>
  <a href="SECURITY.md"><img alt="Security Policy" src="https://img.shields.io/badge/Security-Private%20Reporting-2ea44f?style=flat-square"></a>
  <a href="https://developers.upayments.com/reference/woocommerce"><img alt="Provider: UPayments" src="https://img.shields.io/badge/Provider-UPayments-4b5563?style=flat-square"></a>
  <img alt="Version 0.1.0" src="https://img.shields.io/badge/Version-0.1.0-2563eb?style=flat-square">
  <img alt="Maturity: Pre-release engineering" src="https://img.shields.io/badge/Maturity-Pre--release%20engineering-f59e0b?style=flat-square">
</p>

> [!IMPORTANT]
> **SimplixPay for UPayments** is independently engineered and maintained by **Simplix Innovations**. **UPayments is the payment-service provider** and owns its respective names and trademarks. This repository is not represented as the official UPayments plugin distribution unless explicitly stated by UPayments.

## Project status

The following engineering gates are **DONE / VERIFIED**:

- Repository Foundation & Readiness;
- Phase 0 — SimplixPay release identity/updater ownership;
- Phase 9I — historical token-identity migration;
- Provider Contract & Payment Lifecycle;
- Security Threat-Model Closure;
- Architecture & Code-Quality Foundation A1-A5.

The current program gate is **Full Automated Quality Platform — Q1**.

The project remains in **pre-release engineering hardening**. It is not yet a broadly certified stable production release and has not yet been published to WordPress.org.

| Item | Current position |
|---|---|
| Canonical repository | `SimplixInnovations/simplixpay-upayments` |
| Formal product | **SimplixPay for UPayments** |
| Current development version | **0.1.0** |
| Maintainer | **Simplix Innovations** |
| Payment provider | **UPayments** |
| Repository foundation/readiness | **DONE / VERIFIED** |
| Phase 0 release identity/updater ownership | **DONE / VERIFIED** |
| Phase 9I historical token-identity migration | **DONE / VERIFIED** |
| Provider Contract & Payment Lifecycle | **DONE / VERIFIED** |
| Security Threat-Model Closure | **DONE / VERIFIED** |
| Architecture & Code-Quality Foundation | **DONE / VERIFIED (A1-A5)** |
| Current engineering gate | **Full Automated Quality Platform — Q1** |
| Stable SimplixPay release | **Not yet published** |
| WordPress.org release | **Not yet published** |
| Phase 0 release-identity harness | **35 PASS / 0 FAIL** |
| Phase 9I preflight | **123 PASS / 0 FAIL** |
| Phase 9I executor | **59 PASS / 0 FAIL** |
| Phase 9I operations | **81 PASS / 0 FAIL** |
| Provider lifecycle harness | **141 PASS / 0 FAIL** |
| Provider exact-amount harness | **4 PASS / 0 FAIL** |
| Security threat-model harness | **82 PASS / 0 FAIL** |
| Quality-platform foundation harness | **68 PASS / 0 FAIL** |
| H12 regression baseline | **PHP 1927 PASS / 0 FAIL; Blocks 144 PASS / 0 FAIL** |
| Bounded Security Threat-Model Closure | **DONE / VERIFIED** |
| Broad penetration-test/PCI/platform/feature certification | **Pending** |

These harness counts are targeted regression evidence, not a substitute for the planned broader WordPress/WooCommerce integration, security, browser, performance and compatibility certification suites.

For exact engineering state, see [`docs/project/PROJECT-STATUS.md`](docs/project/PROJECT-STATUS.md).

Closed evidence records:

- [`docs/project/PHASE-0-RELEASE-IDENTITY.md`](docs/project/PHASE-0-RELEASE-IDENTITY.md)
- [`docs/project/PHASE-9I-MIGRATION.md`](docs/project/PHASE-9I-MIGRATION.md)
- [`docs/project/PROVIDER-PAYMENT-LIFECYCLE.md`](docs/project/PROVIDER-PAYMENT-LIFECYCLE.md)
- [`docs/project/SECURITY-THREAT-MODEL.md`](docs/project/SECURITY-THREAT-MODEL.md)
- [`docs/project/ARCHITECTURE-CODE-QUALITY.md`](docs/project/ARCHITECTURE-CODE-QUALITY.md)
- [`docs/project/QUALITY-PLATFORM.md`](docs/project/QUALITY-PLATFORM.md)

## Verified Security Threat-Model Closure outcome

PR #17 closed five bounded security findings without broad architectural cleanup: public order-status IDOR, state-changing subscription GET actions, checkout third-party font/icon trust, overly broad plain-data output trust, and product-meta write defense in depth.

Verified evidence:

- final reviewed head: `fba12225899c3e01d6b23a6bba2f757a3b5f6a4a`;
- squash merge: `01f3fc59eed8641b3e5372558f61a7a0f0cdfac9`;
- merge tree: `e0027005f059fad03d8c08273b7aac6553c45f53`;
- sole parent: `08054a93c619f3c34fef747a6e530abce1e8986e`;
- GitHub signature: **VERIFIED**;
- exact PR merge-ref Quality Gates run #88: **SUCCESS**;
- post-merge `main` Quality Gates run #89: **SUCCESS**;
- implementation branch deleted;
- Security Threat-Model harness: **81 PASS / 0 FAIL**;
- all Phase 0, Phase 9I, Provider Lifecycle, H12 PHP and H12 Blocks regression gates remained green.

One valid automated P2 review finding—remaining Font Awesome chevrons in Checkout Blocks after removing the CDN stylesheet—was fixed before merge and made a permanent security regression.

The closure remains intentionally bounded: webhook HMAC/signature details are provider-document unresolved, automatic refunds remain unsupported pending durable idempotency/reconciliation design, subscription auto-deduction is not broadly recurring-billing certified, and this gate is not a penetration-test/PCI/platform/feature/performance/production certification.

## Verified Provider Contract & Payment Lifecycle outcome

PR #15 established a small, testable `Simplix\Pay\UPayments\Payment` lifecycle layer rather than broadly rewriting the inherited gateway bootstrap.

Verified final evidence:

- reviewed head: `d2b08ebe1e65ad4ea8f4e06b41423e7bd9904fc3`;
- squash merge: `9569e39973a9e94926087738eae06c3846361943`;
- merge tree: `40ec562674361624c2764263ba55cfba84594955`;
- sole parent: `8e5a93ceb4f133663fdf433cc1a10b8b36c13d97`;
- GitHub signature: **VERIFIED**;
- implementation branch deleted;
- exact PR merge-ref Quality Gates run #70: **SUCCESS**;
- post-merge `main` Quality Gates run #71: **SUCCESS**.

The verified ordinary-checkout contract includes:

- browser/webhook payload fields are non-authoritative;
- paid state requires Bearer-authenticated Get Payment Status plus strict transaction/order binding;
- exact UPayments HTTPS status host/path validation, no redirects, TLS verification and finite timeout;
- credential/mode-scoped 30/minute automated status-query ceiling until provider documentation resolves its conflicting limits;
- exact provider-order/reference/currency/amount binding with canonical decimal equality;
- `CAPTURED` uses WooCommerce `payment_complete($verified_payment_id)` and standard Woo transaction-ID semantics;
- duplicate/replayed captures do not re-complete payment;
- paid/refunded orders cannot be downgraded or resurrected;
- pending/authorized/approved/provider-NULL/processing/unknown states remain unpaid and reconcile boundedly;
- terminal authenticated failure/cancellation affects only unverified/unpaid orders;
- first-query transient failures can retry using a separate unverified cursor that becomes trusted only after authenticated rebinding;
- reconciliation cursors are paired with the current `UPayments_order_id`, so a later Charge attempt on the same Woo order cannot inherit stale attempt state;
- reconciliation is bounded to four attempts at 60/120/240/480 seconds and never retries Charge;
- order lifecycle locking uses compare-and-swap stale takeover/release semantics;
- callback routing rejects GET/POST conflicts, excludes cookies and never uses `$_REQUEST`.

Four valid automated review findings were fixed before merge: rate-gate runtime/test seam, first-query transient reconciliation, stale-lock takeover race, and amount-rounding mismatch.

Exact final regression counts: Phase 0 **35/0**; Phase 9I preflight **123/0**; executor **59/0**; operations **81/0**; Provider Lifecycle **141/0**; Provider Exact Amount **4/0**; H12 PHP **1927/0**; H12 Blocks **144/0**, with Governance and syntax checks green.

### Deliberate non-claims

The lifecycle gate does not pretend unresolved provider/feature boundaries are solved:

- exact webhook HMAC/signature verification remains provider-document unresolved because UPayments' public documentation reviewed on 2026-08-25 did not publish a complete stable verification contract;
- automatic WooCommerce refunds remain unsupported because safe asynchronous refund idempotency/reconciliation needs its own durable design;
- current multi-merchant behavior remains one additional merchant allocation only; arbitrary multi-split marketplace routing is not certified;
- subscription auto-deduction keeps its separately characterized path.

## Verified Phase 9I outcome

Phase 9I remains DONE / VERIFIED through three independently reviewed tranches:

1. **Preflight — PR #11** — exact `CLEAN` / `MIGRATABLE` / `BLOCKED` / `INDETERMINATE` classification, no provider calls or identity writes, all 13 blocker families fail closed.
2. **Executor — PR #12** — acts only on fresh `MIGRATABLE` evidence under lock; creates only `legacy_compat` / `legacy_verified_capture`; never fabricates canonical/Create-201 history; historical order metadata remains immutable.
3. **Operations — PR #13** — bounded admin/CLI dry-run and confirmed execute, durable redacted per-user checkpoints, credential/mode/list-scoped resume, and no provider/checkout/frontend migration hooks.

Phase 9I system completion does not mean every merchant installation was automatically migrated. `BLOCKED` and `INDETERMINATE` site-specific outcomes remain valid fail-closed results.

## Current engineering gate — Full Automated Quality Platform

Architecture discovery and A1-A5 are DONE / VERIFIED. Q1 now establishes a locked development-only Composer/PHPUnit/PHPStan/PHPCS foundation, dependency audit and declared-PHP-floor syntax evidence while every historical and architecture regression remains mandatory. It must preserve the closed payment lifecycle, Security Threat-Model, H12 and Phase 9I contracts and protected persisted/runtime identities.

No big-bang rewrite, runtime branding rename, runtime Composer dependency or broad compatibility claim is authorized by this gate.

## Transitional identities — deliberate compatibility choices

The physical main file remains `UPayments.php` and the runtime/header text domain remains `upayments`.

Frozen eventual targets:

- `simplixpay-upayments.php`
- text domain `simplixpay-upayments`

Those are explicit upgrade/i18n migrations, not cosmetic search/replace work.

Persisted/runtime identities such as gateway ID `upayments`, `woocommerce_upayments_settings`, callback `wc_upayments`, `_upay_*` metadata, H12 token/provenance state, scheduler/table identities and historical order method values remain protected unless a dedicated tested migration changes them.

## Why this project exists

Payment extensions are business-critical infrastructure. Simplix Innovations is engineering this integration around deterministic financial state, independent release ownership, saved-card/customer-token identity safety, historical migration without guessing, WooCommerce lifecycle semantics, Classic/Blocks interoperability, multilingual commerce, subscriptions/refunds/multi-merchant boundaries, diagnostics, performance and evidence-based release controls.

These are engineering targets unless [`docs/COMPATIBILITY.md`](docs/COMPATIBILITY.md) explicitly marks a capability **Verified**.

## Verified repository controls

The project uses protected `main` with squash-only merge policy, PR/review-thread workflow, required Governance and H12 Regression Harness checks, linear history, merged-branch cleanup, secret scanning/push protection, Dependabot security updates and private vulnerability reporting.

Current CI validates all tracked PHP syntax, Phase 0, all Phase 9I suites, Provider Payment Lifecycle, Provider Exact Amount Binding, Security Threat-Model, H12 PHP and H12 Blocks behavior.

## Simplix Innovations and WooCommerce

**Simplix Innovations is listed by WooCommerce as a Woo Agency Partner** and serves WooCommerce clients internationally from the United Arab Emirates.

- [Official WooCommerce Agency Partner profile](https://woocommerce.com/development-services/simplix-innovations-woocommerce-full-service-agency/232995338/)
- [Simplix Innovations](https://simplixi.com)
- Contact: **info@simplixi.com**

The Woo Agency Partner listing reflects Simplix Innovations' broader WooCommerce practice. It is not an endorsement by WooCommerce or UPayments of this specific plugin.

## Engineering control plane

Read in this order for engineering work:

1. [`AGENTS.md`](AGENTS.md)
2. [`docs/project/PROJECT-STATUS.md`](docs/project/PROJECT-STATUS.md)
3. [`docs/project/NAMING-IDENTITY-STANDARD.md`](docs/project/NAMING-IDENTITY-STANDARD.md)
4. [`docs/project/NEW-CHAT-HANDOFF.md`](docs/project/NEW-CHAT-HANDOFF.md)
5. [`docs/project/PHASE-0-RELEASE-IDENTITY.md`](docs/project/PHASE-0-RELEASE-IDENTITY.md)
6. [`docs/project/PHASE-9I-MIGRATION.md`](docs/project/PHASE-9I-MIGRATION.md)
7. [`docs/project/PROVIDER-PAYMENT-LIFECYCLE.md`](docs/project/PROVIDER-PAYMENT-LIFECYCLE.md)
8. [`docs/project/SECURITY-THREAT-MODEL.md`](docs/project/SECURITY-THREAT-MODEL.md)
9. [`docs/project/MASTER-ENGINEERING-PLAYBOOK.md`](docs/project/MASTER-ENGINEERING-PLAYBOOK.md)
10. [`docs/project/REPOSITORY-AUDIT.md`](docs/project/REPOSITORY-AUDIT.md)
11. [`docs/project/REPOSITORY-READINESS.md`](docs/project/REPOSITORY-READINESS.md)
12. [`docs/project/BASELINE-H12.md`](docs/project/BASELINE-H12.md)

Additional policies: [`SECURITY.md`](SECURITY.md), [`SUPPORT.md`](SUPPORT.md), [`CONTRIBUTING.md`](CONTRIBUTING.md), [`MAINTAINERS.md`](MAINTAINERS.md), [`UPSTREAM.md`](UPSTREAM.md), [`NOTICE.md`](NOTICE.md), and [`CHANGELOG.md`](CHANGELOG.md).

## Issues, security and support

Use GitHub Issues for reproducible bugs, compatibility reports and feature requests. Security-sensitive findings must follow [`SECURITY.md`](SECURITY.md), including the repository's private reporting path.

Simplix Innovations maintains the WooCommerce integration layer. UPayments remains responsible for merchant/provider-platform operations.

## License and provenance

This repository is distributed under the [MIT License](LICENSE). Upstream provenance, independent maintenance and trademark boundaries are documented in [`NOTICE.md`](NOTICE.md) and [`UPSTREAM.md`](UPSTREAM.md). Historical engineering-only changelog material is retained under [`docs/history/`](docs/history/) rather than presented as SimplixPay product releases.
