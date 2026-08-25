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

The repository foundation, **Phase 0 — release identity/updater ownership**, and **Phase 9I — historical token-identity migration** are **DONE / VERIFIED**.

The active plugin identifies as **SimplixPay for UPayments 0.1.0** by **Simplix Innovations**. The inherited updater that pointed at `upaymentskwt/woocommerce` and its bundled Plugin Update Checker dependency are removed. External self-updates remain intentionally disabled until the physical package/basename migration has a separately tested distribution contract.

Phase 9I adds a deterministic read-only historical-identity classifier, a locked fail-closed executor for explicit migratable states, and bounded resumable admin/CLI operations with a separate redacted per-user decision/result checkpoint ledger. Completion of Phase 9I means the migration **system and safety contract** are verified; it does not mean every merchant installation has already been classified or migrated.

The current program gate is **Provider Contract & Payment Lifecycle — DISCOVERY**.

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
| Phase 9I historical token-identity migration | **DONE / VERIFIED** |
| Current engineering gate | **Provider Contract & Payment Lifecycle — DISCOVERY** |
| Stable SimplixPay release | **Not yet published** |
| WordPress.org release | **Not yet published** |
| Phase 0 release-identity harness | **35 PASS / 0 FAIL** |
| Phase 9I preflight | **123 PASS / 0 FAIL** |
| Phase 9I executor | **59 PASS / 0 FAIL** |
| Phase 9I operations | **81 PASS / 0 FAIL** |
| H12 regression baseline | **PHP 1927 PASS / 0 FAIL; Blocks 144 PASS / 0 FAIL** |
| Broad Woo/WP/PHP/HPOS/Blocks/WPML certification | **Pending** |

These harness counts are targeted regression evidence, not a substitute for the planned broader WordPress/WooCommerce integration, browser, security, performance and compatibility certification suites.

For the exact engineering ledger, see [`docs/project/PROJECT-STATUS.md`](docs/project/PROJECT-STATUS.md). Phase 0's exact contract/evidence is retained at [`docs/project/PHASE-0-RELEASE-IDENTITY.md`](docs/project/PHASE-0-RELEASE-IDENTITY.md). The closed historical-migration contract is documented at [`docs/project/PHASE-9I-MIGRATION.md`](docs/project/PHASE-9I-MIGRATION.md).

## Verified Phase 9I outcome

Phase 9I was delivered in three independently reviewed tranches:

1. **Preflight — PR #11**
   - exact `CLEAN` / `MIGRATABLE` / `BLOCKED` / `INDETERMINATE` classification;
   - zero provider calls and zero identity writes;
   - bounded historical/cross-user evidence analysis;
   - explicit fail-closed handling for all 13 historical blocker families.
2. **Executor — PR #12**
   - executes only fresh `MIGRATABLE` evidence;
   - locked re-preflight before mutation;
   - safe missing-secret initialization only under the verified transition;
   - immutable `legacy_compat` / `legacy_verified_capture` provenance only;
   - no fabricated canonical/Create-201 history;
   - idempotent retry/concurrency behavior;
   - zero provider calls and zero historical order-meta rewrites.
3. **Operations — PR #13**
   - bounded explicit-user admin/CLI workflow;
   - dry-run and confirmed execute modes;
   - maximum 500 submitted users, 50 processed per invocation, default 20;
   - explicit offset or durable resume;
   - redacted per-user `_simplixpay_upayments_migration_result_v1` result checkpoints for every processed outcome;
   - credential/mode/list-scoped HMAC resume identity without persisting API credentials;
   - failed checkpoint persistence stops progress and leaves the current user as the retry point;
   - no provider, checkout, Store API, frontend or cron migration hooks.

Verified PR #13 merge evidence:

- final reviewed head: `2989862683754f8a8eda8e9d4239ada4a61b23f4`;
- squash merge: `db1c4ea4dab45bc1ffaf4529e0ccb940153cd999`;
- merge tree: `5bec24ad26c66a504cd0dd609f4311f9e70add76`;
- GitHub signature: **VERIFIED**;
- implementation branch deleted after merge.

The full exact-head regression stack passed before merge: Governance and tracked PHP syntax; Phase 0 **35/0**; Phase 9I preflight **123/0**; executor **59/0**; operations **81/0**; H12 PHP **1927/0**; Blocks syntax; H12 Blocks **144/0**.

## Current engineering gate — Provider Contract & Payment Lifecycle

The next gate is discovery and contract freezing before payment-critical refactoring. It will compare the exact current source against current official UPayments documentation and establish evidence-backed contracts for:

- charge request/success/failure behavior;
- webhook, status reconciliation and browser-return truth hierarchy;
- callback validation, replay/idempotency and duplicate events;
- deterministic WooCommerce payment/order state transitions;
- ambiguous/transient failure and retry rules;
- refund semantics including partial/full refund and recovery;
- multi-merchant routing boundaries;
- reconciliation, logging/redaction and support evidence.

No payment lifecycle behavior is considered certified merely because it currently exists in inherited runtime code.

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

Current CI validates all tracked PHP syntax, Phase 0 release identity, Phase 9I preflight/executor/operations, H12 PHP behavior and H12 Blocks behavior.

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
6. [`docs/project/PHASE-9I-MIGRATION.md`](docs/project/PHASE-9I-MIGRATION.md) — closed Phase 9I evidence
7. [`docs/project/MASTER-ENGINEERING-PLAYBOOK.md`](docs/project/MASTER-ENGINEERING-PLAYBOOK.md)
8. [`docs/project/REPOSITORY-AUDIT.md`](docs/project/REPOSITORY-AUDIT.md)
9. [`docs/project/REPOSITORY-READINESS.md`](docs/project/REPOSITORY-READINESS.md) — closed foundation evidence
10. [`docs/project/BASELINE-H12.md`](docs/project/BASELINE-H12.md)

Additional policies: [`SECURITY.md`](SECURITY.md), [`SUPPORT.md`](SUPPORT.md), [`CONTRIBUTING.md`](CONTRIBUTING.md), [`MAINTAINERS.md`](MAINTAINERS.md), [`UPSTREAM.md`](UPSTREAM.md), [`NOTICE.md`](NOTICE.md), and [`CHANGELOG.md`](CHANGELOG.md).

## Issues, security and support

Use GitHub Issues for reproducible bugs, compatibility reports and feature requests. Security-sensitive findings must follow [`SECURITY.md`](SECURITY.md), which includes the repository's private reporting path.

Simplix Innovations maintains the WooCommerce integration layer. UPayments remains responsible for merchant/provider-platform operations.

## License and provenance

This repository is distributed under the [MIT License](LICENSE). Upstream provenance, independent maintenance and trademark boundaries are documented in [`NOTICE.md`](NOTICE.md) and [`UPSTREAM.md`](UPSTREAM.md). Historical engineering-only changelog material is retained under [`docs/history/`](docs/history/) rather than presented as SimplixPay product releases.
