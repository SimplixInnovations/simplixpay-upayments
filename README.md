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

The repository foundation and **Phase 0 — release identity/updater ownership are DONE / VERIFIED**.

The active plugin now identifies as **SimplixPay for UPayments 0.1.0** by **Simplix Innovations**. The inherited updater that pointed at `upaymentskwt/woocommerce` and its bundled Plugin Update Checker dependency have been removed. External self-updates are intentionally disabled until the physical package/basename migration has a separately tested distribution contract.

The project remains in **pre-release engineering hardening**. It is not yet a broadly certified stable production release and has not yet been published to WordPress.org.

| Item | Current position |
|---|---|
| Canonical repository | `SimplixInnovations/simplixpay-upayments` |
| Formal product | **SimplixPay for UPayments** |
| Short integration reference | **SimplixPay UPayments** |
| Current development version | **0.1.0** |
| Maintainer | **Simplix Innovations** |
| Payment provider | **UPayments** |
| Repository foundation/readiness | **DONE / VERIFIED** |
| Phase 0 release identity/updater ownership | **DONE / VERIFIED** |
| Current engineering gate | **Phase 9I — Historical token-identity migration** |
| Stable SimplixPay release | **Not yet published** |
| WordPress.org release | **Not yet published** |
| Phase 0 release-identity harness | **35 PASS / 0 FAIL** |
| H12 regression baseline | **PHP 1927 PASS / 0 FAIL; Blocks 144 PASS / 0 FAIL** |
| Broad Woo/WP/PHP/HPOS/Blocks/WPML certification | **Pending** |

These harness counts are targeted regression evidence, not a substitute for the planned broader WordPress/WooCommerce integration, browser, security, performance and compatibility certification suites.

For the exact engineering ledger, see [`docs/project/PROJECT-STATUS.md`](docs/project/PROJECT-STATUS.md). Phase 0's exact contract/evidence is retained at [`docs/project/PHASE-0-RELEASE-IDENTITY.md`](docs/project/PHASE-0-RELEASE-IDENTITY.md).

## What Phase 0 changed

Verified Phase 0 outcomes:

- public plugin identity → **SimplixPay for UPayments** / Simplix Innovations;
- independent Simplix development version → **0.1.0**;
- upstream UPayments repository can no longer replace this plugin through the inherited updater;
- bundled Plugin Update Checker removed;
- canonical new-code namespace foothold introduced at `Simplix\Pay\UPayments\Release`;
- uninstall changed to preserve merchant/payment data by default;
- Phase 0 release-identity characterization is permanently enforced in CI;
- all protected historical payment identities remain intact.

## Transitional identities — deliberate compatibility choices

The physical main file remains `UPayments.php` and the runtime/header text domain remains `upayments` for now.

Frozen eventual targets remain:

- `simplixpay-upayments.php`
- text domain `simplixpay-upayments`

Those are explicit upgrade/i18n migrations. Changing them affects WordPress activation/update identity and multilingual/string-translation behavior, so they will not be performed as cosmetic search/replace operations.

Likewise, persisted/runtime identities such as gateway ID `upayments`, `woocommerce_upayments_settings`, callback `wc_upayments`, `_upay_*` metadata, H12 token/provenance state, scheduler/table identities and historical order method values remain protected unless a dedicated tested migration explicitly changes them.

## Why this project exists

Payment extensions are business-critical infrastructure. Simplix Innovations is engineering this integration around deterministic payment-state handling, independent release ownership, saved-card/customer-token identity safety, historical migration without guessing, Classic/Blocks interoperability, multilingual commerce, subscriptions/refunds/multi-merchant flows, diagnostics, performance and evidence-based release controls.

These are engineering targets unless [`docs/COMPATIBILITY.md`](docs/COMPATIBILITY.md) explicitly marks a capability **Verified**.

## Current engineering gate — Phase 9I

Phase 9I addresses historical token-identity states that cannot safely be guessed or silently promoted.

Its preflight must be read-only and deterministically classify evidence as:

- `CLEAN`
- `MIGRATABLE`
- `BLOCKED`
- `INDETERMINATE`

Preflight performs no provider calls and no identity writes. Execution is permitted only for explicit `MIGRATABLE` cases, must be idempotent/resumable/bounded, and may record attributable legacy identity only as `legacy_compat` / `legacy_verified_capture`—never as fabricated canonical/Create-201 provenance.

See [`docs/project/PROJECT-STATUS.md`](docs/project/PROJECT-STATUS.md) for the 13 currently open Phase 9I blocker classes.

## Verified repository controls

The project uses protected `main` with:

- required pull requests;
- linear history;
- squash-only merges;
- required resolved review threads;
- required **Governance** and **H12 Regression Harness** checks;
- deletion/non-fast-forward restrictions;
- automatic merged-branch deletion;
- secret scanning + push protection;
- Dependabot security updates;
- private vulnerability reporting.

Current CI validates all tracked PHP syntax, the Phase 0 release-identity contract, H12 PHP behavior and H12 Blocks behavior.

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
5. [`docs/project/PHASE-0-RELEASE-IDENTITY.md`](docs/project/PHASE-0-RELEASE-IDENTITY.md) — closed Phase 0 evidence
6. [`docs/project/MASTER-ENGINEERING-PLAYBOOK.md`](docs/project/MASTER-ENGINEERING-PLAYBOOK.md)
7. [`docs/project/REPOSITORY-AUDIT.md`](docs/project/REPOSITORY-AUDIT.md)
8. [`docs/project/REPOSITORY-READINESS.md`](docs/project/REPOSITORY-READINESS.md) — closed foundation evidence
9. [`docs/project/BASELINE-H12.md`](docs/project/BASELINE-H12.md)

Additional policies: [`SECURITY.md`](SECURITY.md), [`SUPPORT.md`](SUPPORT.md), [`CONTRIBUTING.md`](CONTRIBUTING.md), [`MAINTAINERS.md`](MAINTAINERS.md), [`UPSTREAM.md`](UPSTREAM.md), [`NOTICE.md`](NOTICE.md), and [`CHANGELOG.md`](CHANGELOG.md).

## Issues, security and support

Use GitHub Issues for reproducible bugs, compatibility reports and feature requests. Security-sensitive findings must follow [`SECURITY.md`](SECURITY.md), which includes the repository's private reporting path.

Simplix Innovations maintains the WooCommerce integration layer. UPayments remains responsible for merchant/provider-platform operations.

## License and provenance

This repository is distributed under the [MIT License](LICENSE). Upstream provenance, independent maintenance and trademark boundaries are documented in [`NOTICE.md`](NOTICE.md) and [`UPSTREAM.md`](UPSTREAM.md). Historical engineering-only changelog material is retained under [`docs/history/`](docs/history/) rather than presented as SimplixPay product releases.
