# Changelog

All notable **SimplixPay for UPayments** product changes will be documented in this file.

The project has not yet published a stable SimplixPay release. During pre-release engineering, entries describe repository and engineering milestones rather than implying merchant-facing release availability.

Versioning follows [Semantic Versioning](https://semver.org/) once the SimplixPay version line is established. The intended development line is `0.x`; `1.0.0` is reserved for the first release that satisfies the stable-release gates.

## [Unreleased]

### Repository foundation

- Established the standalone canonical repository `SimplixInnovations/simplixpay-upayments` from the independently verified H12 source tree.
- Preserved the complete pre-product fork/PR history in `SimplixInnovations/upayments-woocommerce` for audit provenance.
- Established the formal product identity **SimplixPay for UPayments** and canonical slug `simplixpay-upayments` while protecting compatibility-sensitive persisted `upayments` / `_upay_*` identities.
- Added permanent repository agent instructions, project-control documents, CODEOWNERS, issue/PR governance, security/support policies, MIT license and provenance notice.
- Added GitHub Actions quality gates for governance, PHP syntax, H12 PHP regression and H12 Blocks regression.
- Normalized repository licensing so GitHub recognizes SPDX `MIT`.
- Added pre-Phase-0 repository-readiness auditing and public repository presentation cleanup.

### H12 regression baseline retained

The current frozen baseline remains:

- PHP harness: **1927 PASS / 0 FAIL**
  - semantic runtime: 368
  - helper unit runtime: 841
  - static source: 46
  - harness self-test: 662
  - lint tooling: 10
- Blocks harness: **144 PASS / 0 FAIL**
  - runtime: 88
  - static: 15
  - harness: 41

These are regression assertions from the existing H12 custom harness. They are not a substitute for the planned PHPUnit, WordPress/WooCommerce integration, browser, compatibility, security and performance certification suites.

### Next runtime-changing gate

**Phase 0 — SimplixPay release identity and updater ownership** will:

- remove/replace the upstream-controlled updater;
- establish independent SimplixPay versioning;
- change public plugin metadata to SimplixPay for UPayments / Simplix Innovations;
- design and test the plugin folder/main-file/text-domain transition;
- preserve protected historical payment identities;
- add install/update/upgrade/rollback regression evidence.

## Historical engineering record

The large pre-product hardening changelog inherited from the H12 source baseline is preserved byte-for-byte at:

[`docs/history/H12-ENGINEERING-CHANGELOG.md`](docs/history/H12-ENGINEERING-CHANGELOG.md)

That archive documents engineering corrections made before the standalone SimplixPay product history was established. It is retained for auditability and should not be interpreted as a sequence of SimplixPay product releases.
