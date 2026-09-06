<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset=".github/assets/simplix-innovations-logo-white.svg">
    <source media="(prefers-color-scheme: light)" srcset=".github/assets/simplix-innovations-logo-black.svg">
    <img src=".github/assets/simplix-innovations-logo-black.svg" alt="Simplix Innovations" width="280">
  </picture>
</p>

<h1 align="center">SimplixPay for UPayments</h1>

<p align="center"><strong>Independently engineered UPayments payment integration for WooCommerce</strong><br>maintained by <a href="https://simplixi.com">Simplix Innovations</a></p>

<p align="center">
  <a href="https://github.com/SimplixInnovations/simplixpay-upayments/actions/workflows/quality-gates.yml"><img alt="Quality Gates" src="https://github.com/SimplixInnovations/simplixpay-upayments/actions/workflows/quality-gates.yml/badge.svg?branch=main"></a>
  <a href="LICENSE"><img alt="MIT License" src="https://img.shields.io/badge/License-MIT-2ea44f?style=flat-square"></a>
  <a href="SECURITY.md"><img alt="Security Policy" src="https://img.shields.io/badge/Security-Private%20Reporting-2ea44f?style=flat-square"></a>
  <img alt="Version 0.1.0" src="https://img.shields.io/badge/Version-0.1.0-2563eb?style=flat-square">
  <img alt="Maturity: Release candidate qualification" src="https://img.shields.io/badge/Maturity-Release%20candidate%20qualification-f59e0b?style=flat-square">
</p>

> [!IMPORTANT]
> **SimplixPay for UPayments** is independently engineered and maintained by **Simplix Innovations**. UPayments is the payment-service provider and owns its respective names and trademarks. This repository does not imply endorsement or official distribution by UPayments.

## Status

Repository Foundation, Phase 0, Phase 9I, Provider Payment Lifecycle, bounded Security Threat-Model Closure, Architecture A1-A5 and Quality Platform Q1-Q19 are **DONE / VERIFIED**. Enterprise Tasks 1–7 are also **DONE / VERIFIED**: executable platform compatibility, support declarations, bounded public-provider sandbox, deterministic installable artifact, feature/operations boundaries and existing-install upgrade/identity certification.

The current program gate is **Enterprise Release Candidate Closeout — CURRENT / FINAL VERIFICATION**.

The project is still **pre-release**. No public SimplixPay 1.0 tag, GitHub Release or WordPress.org release has been created. Task 8 qualifies one exact release-candidate source/artifact state; publication remains a separate owner release action.

| Area | Current verified position |
|---|---|
| Development version | `0.1.0` |
| WordPress | 6.9 series through 7.1 in exact certified cells |
| WooCommerce | 10.8 series through 11.1 in exact certified cells |
| PHP | 7.4, 8.3, 8.4 in exact certified cells |
| Classic checkout | **Verified** |
| Cart / Checkout Blocks registration & availability | **Verified** |
| HPOS | **Verified / declared compatible** |
| Provider public-sandbox Charge initialization | **Verified, bounded** |
| Saved-card/token identity boundaries | **Verified, bounded runtime** |
| Subscription eligibility/pre-dispatch | **Verified, bounded runtime** |
| Single additional-merchant allocation | **Verified, bounded runtime** |
| Activation/deactivation/uninstall retention | **Verified** |
| Deterministic ZIP + checksum + manifest | **Verified** |
| Existing-install same-basename upgrade/rollback | **Verified** |
| Physical main file for first stable | `UPayments.php` — intentionally retained |
| Text domain for first stable | `upayments` — intentionally retained |
| Automatic Woo refunds | **Unsupported** |
| Arbitrary marketplace multi-split | **Unsupported** |
| Stable public release | **Not yet published** |

See [`docs/COMPATIBILITY.md`](docs/COMPATIBILITY.md) for the exact verified/external/unsupported matrix and [`docs/project/PROJECT-STATUS.md`](docs/project/PROJECT-STATUS.md) for the living engineering ledger.

## Why the transitional basename remains

Task 7 tested an already-active historical installation in real current and floor WordPress/WooCommerce environments. Same-basename upgrade, rollback, deactivate/reactivate, settings/order/token/subscription/cron/callback retention all passed.

A controlled candidate that changed only:

`UPayments.php` → `simplixpay-upayments.php`

failed the active-install migration contract: WordPress kept `simplixpay-upayments/UPayments.php` in `active_plugins`, the target basename was inactive, and the SimplixPay runtime did not load. The first stable release therefore keeps the historical physical basename. The text domain also remains `upayments`; the package still has 70 explicit PHP translation calls bound to it and no coordinated WPML/String Translation migration has been certified.

This is a compatibility decision, not unfinished branding cleanup.

## Release artifact

The canonical build is produced by:

```bash
bash scripts/build-release.sh dist
bash scripts/verify-release.sh dist/simplixpay-upayments-0.1.0.zip
```

The release system guarantees, within the defined toolchain:

- one `simplixpay-upayments/` ZIP root;
- exact file set and bytes from Git `HEAD` tree/blobs;
- fixed archive timestamps/modes and deterministic ordering;
- SHA-256 ZIP sidecar and sorted per-file manifest;
- independent ZIP/source-byte verification;
- rejection of a self-consistent but source-divergent tampered ZIP;
- dirty worktree/index isolation;
- real packaged WordPress/WooCommerce activation, Blocks and legacy/HPOS smoke;
- current/floor existing-install upgrade and rollback certification.

Development/test/control files are excluded by `.distignore`.

## Compatibility and evidence boundaries

A green repository does **not** justify claims that require external systems or organizations. The following remain external/manual or explicitly unsupported unless separately certified:

- production merchant payment completion and production credentials;
- Apple Pay / Google Pay / Samsung Pay completion across real devices/accounts;
- WPML/WCML, multilingual, multicurrency and RTL certification;
- broad browser/device/theme/accessibility certification;
- store-specific performance/load thresholds;
- penetration-test, PCI or legal/compliance attestation;
- UPayments webhook signature trust until a stable published signature contract exists;
- live subscription auto-deduction;
- automatic Woo refunds;
- arbitrary marketplace multi-split routing.

The repository intentionally does not fabricate these claims.

## Protected compatibility identities

Do not mechanically rename historical runtime/persisted identities. Protected contracts include:

- gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks / Store API identity `upayments`;
- callback `wc_upayments`;
- `_upay_*` metadata;
- `upayments_token_identity_secret_v2` and token provenance/scope/generation state;
- `upay_process_subscriptions` and billing-attempt state;
- historical order payment-method values.

Changing any of these requires an explicit migration, precedence, rollback and regression contract.

## Security and payment truth

- Browser redirects and webhook bodies are not financial truth.
- Paid state requires provider-authenticated status verification and exact order/transaction/economics binding.
- Non-idempotent Charge/refund/recurring operations are never blindly retried.
- Checkout does not depend on third-party font/icon CDNs.
- Uninstall is non-destructive by default.
- Merchant secrets, card data and token-provenance secrets must never be exposed in diagnostics or CI.

See [`SECURITY.md`](SECURITY.md), [`docs/project/PROVIDER-PAYMENT-LIFECYCLE.md`](docs/project/PROVIDER-PAYMENT-LIFECYCLE.md) and [`docs/project/SECURITY-THREAT-MODEL.md`](docs/project/SECURITY-THREAT-MODEL.md).

## Engineering records

- [`docs/project/PROJECT-STATUS.md`](docs/project/PROJECT-STATUS.md) — current canonical state
- [`docs/project/ENTERPRISE-CERTIFICATION.md`](docs/project/ENTERPRISE-CERTIFICATION.md) — platform/provider/feature evidence
- [`docs/project/RELEASE-ENGINEERING.md`](docs/project/RELEASE-ENGINEERING.md) — artifact/upgrade/release-candidate evidence
- [`docs/project/QUALITY-PLATFORM.md`](docs/project/QUALITY-PLATFORM.md) — permanent Q1-Q19 record
- [`docs/project/PHASE-9I-MIGRATION.md`](docs/project/PHASE-9I-MIGRATION.md) — historical identity migration
- [`docs/project/PROVIDER-PAYMENT-LIFECYCLE.md`](docs/project/PROVIDER-PAYMENT-LIFECYCLE.md) — financial-state contract
- [`docs/ENGINEERING-ROADMAP.md`](docs/ENGINEERING-ROADMAP.md) — high-level program sequence

## Development

The production plugin has no runtime Composer dependency. Composer tooling is development-only.

```bash
composer install
composer quality
```

CI also runs distributed PHP syntax, every permanent architecture/Quality/H12 harness, real compatibility certification, release-artifact certification and CodeQL.

## License

MIT. See [`LICENSE`](LICENSE) and [`NOTICE.md`](NOTICE.md).
