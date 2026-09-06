<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset=".github/assets/simplix-innovations-logo-white.svg">
    <source media="(prefers-color-scheme: light)" srcset=".github/assets/simplix-innovations-logo-black.svg">
    <img src=".github/assets/simplix-innovations-logo-black.svg" alt="Simplix Innovations" width="280">
  </picture>
</p>

<h1 align="center">SUCheckout for UPayments</h1>

<p align="center"><strong>Independently engineered UPayments payment integration for WooCommerce</strong><br>maintained by <a href="https://simplixi.com">Simplix Innovations</a></p>

<p align="center">
  <a href="https://github.com/SimplixInnovations/simplixpay-upayments/actions/workflows/quality-gates.yml"><img alt="Quality Gates" src="https://github.com/SimplixInnovations/simplixpay-upayments/actions/workflows/quality-gates.yml/badge.svg?branch=main"></a>
  <a href="LICENSE"><img alt="MIT License" src="https://img.shields.io/badge/License-MIT-2ea44f?style=flat-square"></a>
  <a href="SECURITY.md"><img alt="Security Policy" src="https://img.shields.io/badge/Security-Private%20Reporting-2ea44f?style=flat-square"></a>
  <img alt="Version 0.1.0" src="https://img.shields.io/badge/Version-0.1.0-2563eb?style=flat-square">
  <img alt="Maturity: Pre-release certification" src="https://img.shields.io/badge/Maturity-Pre--release%20certification-f59e0b?style=flat-square">
</p>

> [!IMPORTANT]
> **SUCheckout for UPayments** is independently engineered and maintained by **Simplix Innovations**. UPayments is the payment-service provider and owns its respective names and trademarks. This repository does not imply endorsement or official distribution by UPayments.

## Status

The historical enterprise engineering program through Quality Platform Q1-Q19 and Enterprise Tasks 1-8 is preserved as completed evidence.

Quality Platform Q1-Q19 are **DONE / VERIFIED**. The historical Enterprise Release Candidate Closeout is **DONE / VERIFIED**. These are retained closure records; the current pre-release line is SUCheckout and every candidate remains subject to exact-head certification.

| Historical program | Status |
|---|---|
| Quality Platform Q1-Q19 | **DONE / VERIFIED** |
| Enterprise Tasks 1-8 | **DONE / VERIFIED** |

The approved **SUCheckout identity migration is merged and post-merge certified**. Permanent Quality/H12, compatibility, artifact, provider-sandbox, security and WordPress.org gates remain mandatory for every future candidate before release.

No public SUCheckout tag, GitHub Release or WordPress.org publication exists yet. Repository rename and publication remain separate owner/admin actions after the certified engineering merge.

| Area | Current verified / target position |
|---|---|
| Human-facing product | **SUCheckout for UPayments** |
| Canonical technical slug | `sucheckout-upayments` |
| Development version | `0.1.0` |
| Composer namespace | `Simplixi\SUCheckout\UPayments` |
| WordPress text domain | `sucheckout-upayments` |
| Release ZIP/root | `sucheckout-upayments-X.Y.Z.zip` / `sucheckout-upayments/` |
| Physical bootstrap | `UPayments.php` retained as a qualified compatibility exception |
| Gateway/payment method ID | `upayments` preserved |
| Settings option | `woocommerce_upayments_settings` preserved |
| Woo callback identity | `wc_upayments` preserved |
| Historical `_upay_*` metadata / tokens / cron | preserved |
| WordPress | 6.9 series through 7.1 in exact certified cells |
| WooCommerce | 10.8 series through 11.1 in exact certified cells |
| PHP | 7.4, 8.3, 8.4 in exact certified cells |
| Classic checkout | **Verified** |
| Cart / Checkout Blocks registration & availability | **Verified** |
| HPOS | **Verified / declared compatible** |
| Provider public-sandbox Charge initialization | **Verified, bounded** |
| Deterministic ZIP + checksum + manifest | **Verified on merged SUCheckout main; permanent exact-head gate** |
| Official WordPress Plugin Check | **Verified with 0 blocking errors on certified merged main; permanent packaged-artifact gate** |
| Automatic Woo refunds | **Unsupported** |
| Arbitrary marketplace multi-split | **Unsupported** |
| Stable public release | **Not yet published** |

Latest runtime-bearing SUCheckout engineering closeout:

- PR #58 certified head: `5bf84dccb880733da45c1f922d43554af69a33dc`;
- squash merge on `main`: `6aabc4fcb0606567a11637ea07fe081fed4c7f85`;
- post-merge Quality #764, Compatibility #292 (**16/16**), Release Artifact #243, Provider Sandbox #207, WordPress.org Submission Check #101 and CodeQL/main-security #579: **SUCCESS**;
- official Plugin Check: **0 blocking errors**;
- open issues / open PRs / unresolved review threads after closeout: **0 / 0 / 0**.


See [`docs/COMPATIBILITY.md`](docs/COMPATIBILITY.md) for verified/external/unsupported boundaries and [`docs/project/PROJECT-STATUS.md`](docs/project/PROJECT-STATUS.md) for the living engineering ledger.

## Identity architecture

The rebrand intentionally separates first-party product identity from provider and merchant-data compatibility contracts.

### Canonical first-party identity

- product: **SUCheckout for UPayments**;
- slug/text domain: `sucheckout-upayments`;
- namespace: `Simplixi\SUCheckout\UPayments`;
- deterministic release package: `sucheckout-upayments-X.Y.Z.zip` with root `sucheckout-upayments/`.

### Protected compatibility identities

Do not mechanically rename these without an explicit migration contract:

- gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks / Store API identity `upayments`;
- callback `wc_upayments`;
- `_upay_*` metadata;
- `upayments_token_identity_secret_v2` and token provenance/scope/generation state;
- `upay_process_subscriptions` and billing-attempt state;
- historical order payment-method values.

### Why `UPayments.php` remains

Earlier real-install qualification proved that renaming an already-active physical main file could strand WordPress's stored plugin basename. Therefore the canonical SUCheckout package changes its package root, namespace, metadata and text domain while retaining `UPayments.php` as the bounded first-stable bootstrap exception.

The release certification now exercises a real pre-release migration from legacy `simplixpay-upayments/UPayments.php` to canonical `sucheckout-upayments/UPayments.php`, preserves settings/orders/tokens/cron/provider IDs, proves rollback is non-destructive, and ends with the legacy package removed.

## Release artifact

Build and verify the canonical package with:

```bash
bash scripts/build-release.sh dist
bash scripts/verify-release.sh dist/sucheckout-upayments-0.1.0.zip
```

The release contract provides:

- one `sucheckout-upayments/` ZIP root;
- exact file set and bytes from Git `HEAD` tree/blobs;
- fixed archive timestamps/modes and deterministic ordering;
- SHA-256 ZIP sidecar and sorted per-file manifest;
- source-byte verification and rejection of self-consistent tampered artifacts;
- real packaged WordPress/WooCommerce activation, Blocks and legacy/HPOS smoke;
- legacy-root → canonical-root migration/rollback qualification;
- official WordPress Plugin Check against the unpacked deterministic package.

Development/test/control files are excluded by `.distignore`.

## Compatibility and evidence boundaries

A green repository does **not** justify claims that require external systems or organizations. These remain external/manual or unsupported unless separately certified:

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
- [`docs/project/NAMING-IDENTITY-STANDARD.md`](docs/project/NAMING-IDENTITY-STANDARD.md) — SUCheckout identity contract
- [`docs/project/ENTERPRISE-CERTIFICATION.md`](docs/project/ENTERPRISE-CERTIFICATION.md) — platform/provider/feature evidence
- [`docs/project/RELEASE-ENGINEERING.md`](docs/project/RELEASE-ENGINEERING.md) — artifact/upgrade evidence
- [`docs/project/QUALITY-PLATFORM.md`](docs/project/QUALITY-PLATFORM.md) — permanent Q1-Q19 record
- [`docs/superpowers/specs/2026-09-06-sucheckout-upayments-identity-migration-design.md`](docs/superpowers/specs/2026-09-06-sucheckout-upayments-identity-migration-design.md) — approved migration design
- [`docs/superpowers/plans/2026-09-06-sucheckout-upayments-identity-migration.md`](docs/superpowers/plans/2026-09-06-sucheckout-upayments-identity-migration.md) — approved execution plan

## Development

The production plugin has no runtime Composer dependency. Composer tooling is development-only.

```bash
composer install
composer quality
```

CI runs static/unit quality, distributed PHP syntax, permanent H12 regressions, real compatibility certification, deterministic package/runtime certification, provider sandbox qualification and official WordPress Plugin Check.

## License

MIT. See [`LICENSE`](LICENSE) and [`NOTICE.md`](NOTICE.md).