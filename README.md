<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset=".github/assets/simplix-innovations-logo-white.svg">
    <source media="(prefers-color-scheme: light)" srcset=".github/assets/simplix-innovations-logo-black.svg">
    <img src=".github/assets/simplix-innovations-logo-black.svg" alt="Simplix Innovations" width="280">
  </picture>
</p>

<h1 align="center">SUCheckout for UPayments</h1>

<p align="center"><strong>Independent UPayments payment gateway integration for WooCommerce</strong><br>engineered and maintained by <a href="https://simplixi.com">Simplix Innovations</a></p>

<p align="center">
  <a href="https://github.com/SimplixInnovations/simplixpay-upayments/actions/workflows/quality-gates.yml"><img alt="Quality Gates" src="https://github.com/SimplixInnovations/simplixpay-upayments/actions/workflows/quality-gates.yml/badge.svg?branch=main"></a>
  <a href="LICENSE"><img alt="MIT License" src="https://img.shields.io/badge/License-MIT-2ea44f?style=flat-square"></a>
  <a href="SECURITY.md"><img alt="Security Policy" src="https://img.shields.io/badge/Security-Private%20Reporting-2ea44f?style=flat-square"></a>
  <img alt="Version 0.1.0" src="https://img.shields.io/badge/Version-0.1.0-2563eb?style=flat-square">
  <img alt="Maturity: Pre-release" src="https://img.shields.io/badge/Maturity-Pre--release-f59e0b?style=flat-square">
</p>

> [!IMPORTANT]
> **SUCheckout for UPayments** is independently engineered and maintained by **Simplix Innovations**. UPayments is the external payment-service provider and owns its respective names and trademarks. This project does not imply UPayments endorsement, ownership or official distribution.

> [!NOTE]
> The GitHub repository is temporarily still named `simplixpay-upayments` while the owner performs the approved administrative rename to `sucheckout-upayments`. The plugin/package/text-domain identity is already **SUCheckout**. Living GitHub links and badges will be reconciled in one coordinate-only PR immediately after the repository rename.

## What SUCheckout is

SUCheckout connects WooCommerce checkout to UPayments while preserving WooCommerce order semantics, provider-authenticated payment truth and compatibility with historical merchant/payment data that must not be renamed merely for branding.

The current engineering line includes:

- Classic WooCommerce checkout registration;
- Cart / Checkout Blocks registration and availability;
- HPOS and legacy order storage support in the certified matrix;
- authenticated provider-status verification before financial state transitions;
- saved-card/token provenance boundaries;
- subscription eligibility/pre-dispatch boundaries;
- one additional-merchant allocation boundary;
- deterministic source-bound release packaging;
- explicit legacy package-root → SUCheckout package-root migration qualification;
- official WordPress Plugin Check against the actual deterministic release package.

## Current status

The historical Quality Platform Q1-Q19 and Enterprise Tasks 1-8 are **DONE / VERIFIED** and retained as evidence. The numbered Quality Platform is permanently closed at Q19; no Q20 is justified.

The **SUCheckout identity migration is DONE / VERIFIED**. No public stable tag, GitHub Release or WordPress.org publication has been created yet.

| Area | Current position |
|---|---|
| Product | **SUCheckout for UPayments** |
| Product family | **SUCheckout** |
| Technical slug | `sucheckout-upayments` |
| WordPress text domain | `sucheckout-upayments` |
| PHP namespace | `Simplixi\SUCheckout\UPayments` |
| Development version | `0.1.0` |
| Canonical package root | `sucheckout-upayments/` |
| First-stable bootstrap | `UPayments.php` — qualified compatibility exception |
| Canonical basename | `sucheckout-upayments/UPayments.php` |
| Classic checkout | **Verified** |
| Cart / Checkout Blocks | **Verified** |
| HPOS | **Verified / declared compatible** |
| WordPress | 6.9 series through 7.1 in exact certified cells |
| WooCommerce | 10.8 series through 11.1 in exact certified cells |
| PHP runtime | 7.4, 8.3 and 8.4 in exact certified cells |
| Provider sandbox Charge initialization | **Verified — bounded** |
| Deterministic ZIP/checksum/manifest | **Verified / permanent gate** |
| Official Plugin Check | **0 blocking errors on certified package** |
| Automatic Woo refunds | **Unsupported** |
| Arbitrary marketplace multi-split | **Unsupported** |
| Stable public release | **Not yet published** |

### Certification anchors

Runtime-bearing SUCheckout migration:

- PR #58 certified head `5bf84dccb880733da45c1f922d43554af69a33dc`;
- squash merge `6aabc4fcb0606567a11637ea07fe081fed4c7f85`;
- post-merge Quality #764, Compatibility #292 (**16/16**), Release Artifact #243, Provider Sandbox #207, WordPress.org #101 and CodeQL #579 — **SUCCESS**.

Final documentation/control-plane closeout:

- squash merge `9591c431e1eb56fe40ca60147afdf9f3f909a212`;
- fresh main Quality #773, Compatibility #301 (**16/16**), Release Artifact #252, Provider Sandbox #216, WordPress.org #110 and CodeQL #588 — **SUCCESS**.

See [`docs/project/PROJECT-STATUS.md`](docs/project/PROJECT-STATUS.md) for the living state and [`docs/COMPATIBILITY.md`](docs/COMPATIBILITY.md) for the public evidence boundary.

## Identity architecture

The SUCheckout rebrand deliberately separates **first-party product identity** from **provider/persisted compatibility identity**.

### Canonical first-party identity

- human name: **SUCheckout for UPayments**;
- technical slug/text domain: `sucheckout-upayments`;
- namespace: `Simplixi\SUCheckout\UPayments`;
- release package: `sucheckout-upayments-X.Y.Z.zip`;
- package root: `sucheckout-upayments/`.

The word **for** is human-facing relationship copy only and never appears in technical identifiers such as the slug, text domain, repository target or ZIP name.

### Protected compatibility identities

Do **not** mechanically rename these:

- gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks / Store API identity `upayments`;
- callback `wc_upayments`;
- historical `_upay_*` metadata;
- provider-order identities such as `UPayments_order_id`;
- `upayments_token_identity_secret_v2` and token provenance/scope/generation state;
- `upay_process_subscriptions` and billing-attempt state;
- historical order payment-method values.

These are payment/merchant compatibility contracts, not stale branding residue.

### Why `UPayments.php` remains

Real WordPress upgrade qualification proved that deleting/renaming an already-active physical `UPayments.php` can strand WordPress's stored plugin basename. Therefore the first-stable SUCheckout package intentionally uses:

```text
sucheckout-upayments/UPayments.php
```

A future physical rename to `sucheckout-upayments.php` requires a separately approved and tested migration. It is not an unfinished requirement for the first release.

## Release artifact

Build and verify the current development artifact with:

```bash
bash scripts/build-release.sh dist
bash scripts/verify-release.sh dist/sucheckout-upayments-0.1.0.zip
sha256sum dist/sucheckout-upayments-0.1.0.zip
cat dist/sucheckout-upayments-0.1.0.zip.sha256
```

The release contract provides:

- one `sucheckout-upayments/` ZIP root;
- exact file set/bytes from Git `HEAD` under `.distignore`;
- deterministic ordering/timestamps/modes;
- ZIP SHA-256 sidecar;
- sorted per-file SHA-256 manifest;
- source-byte verification and tamper rejection;
- real packaged WordPress/WooCommerce activation and order-storage smoke;
- legacy-root → canonical-root migration/rollback qualification;
- official WordPress Plugin Check against the unpacked deterministic package.

Development/test/control files are excluded from the public package.

## Local owner acceptance

The authoritative local and owner-administration sequence is documented in:

[`docs/project/OWNER-HANDOFF.md`](docs/project/OWNER-HANDOFF.md)

It includes:

1. obsolete branch cleanup;
2. repository rename to `SimplixInnovations/sucheckout-upayments`;
3. GitHub About/security/rules/integration verification;
4. local `origin` update;
5. post-rename coordinate-only documentation reconciliation;
6. isolated local Composer/H12/SUCheckout quality acceptance;
7. deterministic ZIP build/verification;
8. disposable WordPress/WooCommerce install;
9. Classic + Blocks + HPOS + bounded sandbox smoke;
10. explicit release/version and WordPress.org publication decision.

## Compatibility and evidence boundaries

Repository CI does **not** establish claims requiring external systems, commercial plugins, devices or organizational review. These remain external/manual unless separately certified:

- production merchant payment completion;
- Apple Pay / Google Pay / Samsung Pay completion on real eligible accounts/devices;
- WPML/WCML, multilingual, multicurrency and RTL;
- broad browser/device/theme/accessibility testing;
- representative-store performance/load thresholds;
- penetration testing, PCI or legal/compliance attestation;
- UPayments webhook-signature trust until a stable documented verification contract exists;
- live subscription auto-deduction.

Automatic WooCommerce refunds and arbitrary marketplace multi-split remain unsupported.

## Security and payment truth

- Browser redirects and webhook bodies are not financial truth by themselves.
- Paid state requires provider-authenticated verification and exact order/transaction/economic binding.
- Non-idempotent Charge/refund/recurring operations are never blindly retried.
- Checkout does not depend on third-party font/icon CDNs.
- Uninstall is non-destructive by default.
- Merchant secrets, card data and token/provenance secrets must never be exposed in diagnostics, browser output or CI logs.

See [`SECURITY.md`](SECURITY.md), [`docs/project/PROVIDER-PAYMENT-LIFECYCLE.md`](docs/project/PROVIDER-PAYMENT-LIFECYCLE.md) and [`docs/project/SECURITY-THREAT-MODEL.md`](docs/project/SECURITY-THREAT-MODEL.md).

## Engineering records

- [`docs/project/PROJECT-STATUS.md`](docs/project/PROJECT-STATUS.md) — living canonical state
- [`docs/project/OWNER-HANDOFF.md`](docs/project/OWNER-HANDOFF.md) — exact owner/admin/local/release checklist
- [`docs/project/NAMING-IDENTITY-STANDARD.md`](docs/project/NAMING-IDENTITY-STANDARD.md) — canonical SUCheckout identity and protected IDs
- [`docs/COMPATIBILITY.md`](docs/COMPATIBILITY.md) — public compatibility/evidence matrix
- [`docs/project/ENTERPRISE-CERTIFICATION.md`](docs/project/ENTERPRISE-CERTIFICATION.md) — enterprise and SUCheckout certification evidence
- [`docs/project/RELEASE-ENGINEERING.md`](docs/project/RELEASE-ENGINEERING.md) — deterministic artifact/migration contract
- [`docs/project/QUALITY-PLATFORM.md`](docs/project/QUALITY-PLATFORM.md) — permanent historical Q1-Q19 record
- [`docs/project/README.md`](docs/project/README.md) — control-document map and precedence

Historical phase documents deliberately retain historical SimplixPay names/SHAs where those were true at the time. They are evidence, not current branding.

## Development

The production plugin has no runtime Composer dependency. Composer is development tooling only.

```bash
composer install --no-interaction --prefer-dist
composer validate --strict
composer audit --locked
composer quality
```

CI additionally owns the real compatibility matrix, deterministic packaged-runtime certification, provider sandbox qualification, official WordPress Plugin Check and CodeQL/security checks.

## License

MIT. See [`LICENSE`](LICENSE), [`NOTICE.md`](NOTICE.md) and [`UPSTREAM.md`](UPSTREAM.md).
