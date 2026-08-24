<p align="center">
  <img src="assets/images/logo.png" alt="UPayments" width="120">
</p>

<h1 align="center">SimplixPay for UPayments</h1>

<p align="center">
  <strong>Independently engineered UPayments integration for WooCommerce</strong><br>
  maintained by <a href="https://simplixi.com">Simplix Innovations</a>
</p>

<p align="center">
  <a href="https://simplixi.com"><img alt="Maintained by Simplix Innovations" src="https://img.shields.io/badge/Maintained%20by-Simplix%20Innovations-111111?style=flat-square"></a>
  <a href="https://github.com/upaymentskwt/woocommerce"><img alt="Upstream UPayments WooCommerce" src="https://img.shields.io/badge/Upstream-UPayments-4b5563?style=flat-square"></a>
  <img alt="Status" src="https://img.shields.io/badge/Status-Engineering%20hardening-f59e0b?style=flat-square">
</p>

> [!IMPORTANT]
> **SimplixPay for UPayments** is independently engineered and maintained by Simplix Innovations. UPayments is the payment provider and owns its respective trademarks. This repository is not the official UPayments plugin distribution unless explicitly stated otherwise by UPayments.

## Current status

This is the **standalone canonical SimplixPay UPayments product repository**. It was initialized from the exact independently verified H12 source tree; the full pre-product fork/PR history remains preserved separately in `SimplixInnovations/upayments-woocommerce` as the engineering audit archive.

The project is currently **R0 — engineering hardening**. It is **not yet a broadly certified stable production release** and is not yet published on WordPress.org. Compatibility claims are evidence-based.

See [`docs/project/PROJECT-STATUS.md`](docs/project/PROJECT-STATUS.md) for the live engineering state.

## Why this project exists

Payment extensions are business-critical infrastructure. A WooCommerce gateway must remain correct across provider failures, WordPress/WooCommerce changes, PHP upgrades, HPOS, Checkout Blocks, multilingual stacks, saved-card/token flows, subscriptions, webhooks, redirects, order persistence and recovery conditions.

SimplixPay UPayments is being hardened around:

- payment integrity and deterministic state handling;
- independent release/update ownership;
- saved-card/token identity safety and historical migration;
- WooCommerce Classic Checkout and Blocks;
- HPOS and modern WooCommerce compatibility;
- WPML/WCML/multilingual and RTL behavior;
- subscriptions, wallets, refunds and multi-merchant flows;
- scoped frontend assets and accessibility;
- structured diagnostics/logging with sensitive-data redaction;
- performance/stability engineering;
- reproducible tests, CI and release discipline.

## Product identity

- Formal name: **SimplixPay for UPayments**
- Short integration reference: **SimplixPay UPayments**
- Canonical slug: `simplixpay-upayments`
- Maintainer: **Simplix Innovations**
- Provider: **UPayments**

`SimplixPay` alone is reserved for the broader Simplix payment-product family and future multi-provider direction.

See [`docs/project/NAMING-IDENTITY-STANDARD.md`](docs/project/NAMING-IDENTITY-STANDARD.md).

## Critical compatibility rule

Public branding does not justify renaming persisted payment identity. Existing identifiers such as gateway ID `upayments`, `woocommerce_upayments_settings`, callback `wc_upayments`, `_upay_*` metadata, token-identity secret/provenance keys and existing scheduler/table identities remain protected unless a dedicated migration proves a change safe.

## Engineering control plane

Start with:

- [`AGENTS.md`](AGENTS.md) — mandatory repository execution rules
- [`docs/project/PROJECT-STATUS.md`](docs/project/PROJECT-STATUS.md) — current verified state
- [`docs/project/NAMING-IDENTITY-STANDARD.md`](docs/project/NAMING-IDENTITY-STANDARD.md) — frozen identity contract
- [`docs/project/NEW-CHAT-HANDOFF.md`](docs/project/NEW-CHAT-HANDOFF.md) — compact continuation context
- [`docs/project/MASTER-ENGINEERING-PLAYBOOK.md`](docs/project/MASTER-ENGINEERING-PLAYBOOK.md) — complete engineering program
- [`docs/project/BASELINE-H12.md`](docs/project/BASELINE-H12.md) — clean-root/H12 provenance

Additional policies: [`SECURITY.md`](SECURITY.md), [`SUPPORT.md`](SUPPORT.md), [`CONTRIBUTING.md`](CONTRIBUTING.md), [`MAINTAINERS.md`](MAINTAINERS.md), [`UPSTREAM.md`](UPSTREAM.md), [`CHANGELOG.md`](CHANGELOG.md).

## Compatibility policy

A feature is marked **Verified** only after Simplix Innovations records a reproducible tested environment. Provider/upstream documentation is a capability baseline, not automatic certification of this distribution. See [`docs/COMPATIBILITY.md`](docs/COMPATIBILITY.md).

## Current Phase 0 priorities

1. repository governance and baseline CI;
2. remove/replace the upstream-controlled updater;
3. establish independent SimplixPay version/product identity;
4. test upgrade behavior for filename/folder/text-domain changes;
5. configure required checks, branch/rulesets and GitHub security settings;
6. then proceed to Phase 9I historical token-identity migration.

## Reporting issues

GitHub Issues may be used for reproducible bugs, compatibility reports and feature requests. Never include live API keys, bearer tokens, card data, customer tokens, customer PII, database exports or production secrets.

Security-sensitive findings must follow [`SECURITY.md`](SECURITY.md) instead of a public issue.

## Support boundaries

Simplix Innovations maintains the WooCommerce integration layer. UPayments remains responsible for merchant onboarding, KYC, settlements, acquiring, pricing, account status, production API enablement and provider-platform operations.

- Simplix Innovations: https://simplixi.com
- Contact: info@simplixi.com
- UPayments developer documentation: https://developers.upayments.com/reference/woocommerce

## License and provenance

The source lineage is MIT-licensed. Upstream provenance and independence are documented in [`UPSTREAM.md`](UPSTREAM.md). Distribution/license packaging remains part of Phase 0 review before public stable/WordPress.org publication.
