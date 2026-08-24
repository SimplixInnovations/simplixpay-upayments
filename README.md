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
  <img alt="Maturity: Pre-release engineering" src="https://img.shields.io/badge/Maturity-Pre--release%20engineering-f59e0b?style=flat-square">
</p>

> [!IMPORTANT]
> **SimplixPay for UPayments** is independently engineered and maintained by **Simplix Innovations**. **UPayments is the payment-service provider** and owns its respective names and trademarks. This repository is not represented as the official UPayments plugin distribution unless explicitly stated by UPayments.

## Project status

This is the standalone canonical repository for **SimplixPay for UPayments**. The project is in **pre-release engineering hardening**. It is not yet a broadly certified stable production release and has not yet been published to WordPress.org.

Repository governance, provenance, baseline CI, whole-tree audit controls, and the H12 token-identity regression baseline are established. The repository-readiness gate is in final verification before any runtime identity/updater work begins.

| Item | Current position |
|---|---|
| Canonical repository | `SimplixInnovations/simplixpay-upayments` |
| Formal product | **SimplixPay for UPayments** |
| Short integration reference | **SimplixPay UPayments** |
| Maintainer | **Simplix Innovations** |
| Payment provider | **UPayments** |
| Repository maturity | Pre-release engineering hardening |
| Stable SimplixPay release | **Not yet published** |
| WordPress.org release | **Not yet published** |
| H12 regression baseline | PHP 1927 PASS / 0 FAIL; Blocks 144 PASS / 0 FAIL |
| Broad Woo/WP/PHP/HPOS/Blocks/WPML certification | **Pending** |

The H12 counts are regression assertions from the existing custom harness. They do **not** substitute for the planned PHPUnit, WordPress/WooCommerce integration, browser, security, performance, or compatibility certification suites.

For the exact live engineering ledger, see [`docs/project/PROJECT-STATUS.md`](docs/project/PROJECT-STATUS.md).

## Why SimplixPay for UPayments exists

Payment extensions are business-critical infrastructure. A WooCommerce gateway must remain correct across provider failures, retries, callbacks, webhooks, WordPress/WooCommerce changes, PHP upgrades, HPOS, Checkout Blocks, multilingual commerce, saved-card identity, subscriptions, refunds, multi-merchant routing, order persistence, and recovery conditions.

Simplix Innovations is engineering this integration around:

- deterministic payment-state handling and recovery;
- independent Simplix-controlled release/update ownership;
- saved-card and customer-token identity safety;
- historical migration without guessing or silently rewriting payment identity;
- Classic Checkout and Checkout Blocks interoperability;
- HPOS and modern WooCommerce compatibility;
- WPML/WCML, multilingual, multicurrency, and RTL commerce;
- subscriptions, refunds, wallets, and multi-merchant flows;
- scoped frontend assets, accessibility, and theme interoperability;
- structured diagnostics and sensitive-data-safe logging;
- performance, stability, and operational supportability;
- evidence-based compatibility claims, CI, and controlled releases.

These are engineering targets unless the compatibility matrix explicitly marks a capability **Verified**.

## Product identity

- **Formal plugin/product:** SimplixPay for UPayments
- **Short integration reference:** SimplixPay UPayments
- **Broader product family reserved for future use:** SimplixPay
- **Canonical slug:** `simplixpay-upayments`
- **Company / maintainer:** Simplix Innovations
- **Provider:** UPayments

`SimplixPay` alone is intentionally reserved for the broader future payment product. See [`docs/project/NAMING-IDENTITY-STANDARD.md`](docs/project/NAMING-IDENTITY-STANDARD.md).

## Compatibility principle

Public rebranding must never silently rewrite persisted payment identity. Existing compatibility-sensitive identifiers such as WooCommerce gateway ID `upayments`, `woocommerce_upayments_settings`, callback route `wc_upayments`, existing `_upay_*` metadata, H12 token/provenance state, and historical scheduler/table identities remain protected unless a dedicated, tested migration explicitly changes them.

See [`docs/COMPATIBILITY.md`](docs/COMPATIBILITY.md) for the evidence matrix.

## Current verified engineering baseline

The canonical repository was established from the exact independently reviewed H12 source tree. The complete pre-product fork/PR history is preserved separately in [`SimplixInnovations/upayments-woocommerce`](https://github.com/SimplixInnovations/upayments-woocommerce) as the historical engineering/audit archive.

Current repository CI performs:

- governance/control-file checks;
- syntax validation for all tracked PHP files;
- H12 PHP regression harness;
- H12 Blocks harness syntax validation;
- H12 Blocks regression harness.

This is a **baseline safety gate**, not final product certification. The full quality platform remains part of the engineering program.

## Simplix Innovations and WooCommerce

**Simplix Innovations is listed by WooCommerce as a Woo Agency Partner** and serves WooCommerce clients internationally from the United Arab Emirates. The official WooCommerce partner listing covers development, payment gateways, multilingual/multicurrency commerce, performance, integrations, maintenance, and enterprise support.

- [Official WooCommerce Agency Partner profile](https://woocommerce.com/development-services/simplix-innovations-woocommerce-full-service-agency/232995338/)
- [Simplix Innovations](https://simplixi.com)
- Contact: **info@simplixi.com**

The Woo Agency Partner listing reflects Simplix Innovations' broader WooCommerce practice. It is not an endorsement by WooCommerce or UPayments of this specific plugin.

## Engineering control plane

For engineering work, read in this order:

1. [`AGENTS.md`](AGENTS.md) — mandatory repository execution rules.
2. [`docs/project/PROJECT-STATUS.md`](docs/project/PROJECT-STATUS.md) — live verified state and next permitted gate.
3. [`docs/project/REPOSITORY-READINESS.md`](docs/project/REPOSITORY-READINESS.md) — pre-Phase-0 repository readiness checklist.
4. [`docs/project/REPOSITORY-AUDIT.md`](docs/project/REPOSITORY-AUDIT.md) — whole tracked-tree classification and deferred debt.
5. [`docs/project/NAMING-IDENTITY-STANDARD.md`](docs/project/NAMING-IDENTITY-STANDARD.md) — frozen identity and compatibility rules.
6. [`docs/project/NEW-CHAT-HANDOFF.md`](docs/project/NEW-CHAT-HANDOFF.md) — compact continuation context.
7. [`docs/project/MASTER-ENGINEERING-PLAYBOOK.md`](docs/project/MASTER-ENGINEERING-PLAYBOOK.md) — complete engineering program.
8. [`docs/project/BASELINE-H12.md`](docs/project/BASELINE-H12.md) — H12 provenance and baseline anchors.

Additional policies: [`SECURITY.md`](SECURITY.md), [`SUPPORT.md`](SUPPORT.md), [`CONTRIBUTING.md`](CONTRIBUTING.md), [`MAINTAINERS.md`](MAINTAINERS.md), [`UPSTREAM.md`](UPSTREAM.md), [`NOTICE.md`](NOTICE.md), and [`CHANGELOG.md`](CHANGELOG.md).

## Roadmap immediately ahead

Before runtime identity work, close the remaining repository-readiness evidence that cannot be independently observed through the connected GitHub API: local-clone convergence, account email attribution, and detailed branch/security rule configuration.

Then begin **Phase 0 — SimplixPay release identity and updater ownership**:

1. remove or replace the upstream-controlled updater;
2. establish independent SimplixPay semantic versioning;
3. change public plugin metadata to SimplixPay for UPayments / Simplix Innovations;
4. design and test folder/main-file/text-domain transition as an upgrade problem;
5. preserve protected legacy payment identities;
6. add install/update/upgrade/rollback regression evidence.

See [`docs/ENGINEERING-ROADMAP.md`](docs/ENGINEERING-ROADMAP.md).

## Issues, security, and support

Use GitHub Issues for reproducible bugs, compatibility reports, and feature requests. Never publish API keys, bearer tokens, card data, customer/card tokens, token-identity secrets, customer PII, private production payloads, or database exports.

Security-sensitive findings must follow [`SECURITY.md`](SECURITY.md), not a public issue.

Simplix Innovations maintains the WooCommerce integration layer. UPayments remains responsible for merchant onboarding, KYC, settlements, acquiring, pricing, provider account status, production API enablement, and provider-platform operations.

## License and provenance

This repository is distributed under the [MIT License](LICENSE). Upstream provenance, independent maintenance, and trademark boundaries are documented in [`NOTICE.md`](NOTICE.md) and [`UPSTREAM.md`](UPSTREAM.md). Historical engineering-only changelog material is retained under [`docs/history/`](docs/history/) rather than presented as SimplixPay product releases.
