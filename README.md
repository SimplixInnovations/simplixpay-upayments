<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset=".github/assets/simplix-innovations-logo-white.svg">
    <source media="(prefers-color-scheme: light)" srcset=".github/assets/simplix-innovations-logo-black.svg">
    <img src=".github/assets/simplix-innovations-logo-black.svg" alt="Simplix Innovations" width="280">
  </picture>
</p>

<h1 align="center">SUPCheckout for UPayments</h1>

<p align="center"><strong>Independent UPayments payment gateway integration for WooCommerce</strong><br>engineered and maintained by <a href="https://simplixi.com">Simplix Innovations</a></p>

<p align="center">
  <a href="https://github.com/SimplixInnovations/supcheckout/actions/workflows/quality-gates.yml"><img alt="Quality Gates" src="https://github.com/SimplixInnovations/supcheckout/actions/workflows/quality-gates.yml/badge.svg?branch=main"></a>
  <a href="LICENSE"><img alt="MIT License" src="https://img.shields.io/badge/License-MIT-2ea44f?style=flat-square"></a>
  <a href="SECURITY.md"><img alt="Security Policy" src="https://img.shields.io/badge/Security-Private%20Reporting-2ea44f?style=flat-square"></a>
  <img alt="Version 0.1.0" src="https://img.shields.io/badge/Version-0.1.0-2563eb?style=flat-square">
  <img alt="Maturity: Pre-release" src="https://img.shields.io/badge/Maturity-Pre--release-f59e0b?style=flat-square">
</p>

> [!IMPORTANT]
> **SUPCheckout for UPayments** is independently engineered and maintained by **Simplix Innovations**. UPayments is the external payment-service provider and owns its respective names and trademarks. This project does not imply UPayments endorsement, ownership or official distribution.

> [!NOTE]
> The canonical GitHub repository is `SimplixInnovations/supcheckout`. The final SUPCheckout identity migration is merged and post-merge certified; public release/version publication remains a separate owner decision.

## What SUPCheckout is

**SUPCheckout is permanently UPayments-specific.** Other payment providers are developed as independent Simplix products/repositories, while any future cross-provider orchestration or fraud platform remains a separate project. This repository will not become a multi-provider runtime.

SUPCheckout connects WooCommerce checkout to UPayments while preserving WooCommerce order semantics, provider-authenticated payment truth and compatibility with historical merchant/payment data that must not be renamed merely for branding.

The current engineering line includes:

- Classic WooCommerce checkout registration;
- Cart / Checkout Blocks registration and availability;
- HPOS and legacy order storage support in the certified matrix;
- authenticated provider-status verification before financial state transitions;
- saved-card/token provenance boundaries;
- subscription eligibility/pre-dispatch boundaries;
- one additional-merchant allocation boundary;
- deterministic source-bound release packaging;
- explicit legacy package-root → SUPCheckout package-root migration qualification;
- official WordPress Plugin Check against the actual deterministic release package.

## Current status

Quality Platform Q1-Q19 are **DONE / VERIFIED**. Enterprise Tasks 1-8 are **DONE / VERIFIED** and retained as historical evidence. Enterprise Release Candidate Closeout is **DONE / VERIFIED**. The numbered Quality Platform is permanently closed at Q19; no Q20 is justified.

| Historical program | Status |
|---|---|
| Quality Platform Q1-Q19 | **DONE / VERIFIED** |
| Enterprise Tasks 1-8 | **DONE / VERIFIED** |

The **SUPCheckout identity migration and first-party naming cleanup are DONE / VERIFIED**. No public stable tag, GitHub Release or WordPress.org publication has been created yet.

| Area | Current position |
|---|---|
| Product | **SUPCheckout for UPayments** |
| Product family | **SUPCheckout** |
| Canonical GitHub repository | `SimplixInnovations/supcheckout` |
| Technical slug | `supcheckout` |
| WordPress text domain | `supcheckout` |
| PHP namespace | `Simplixi\SUPCheckout` |
| Development version | `0.1.0` |
| Canonical package root | `supcheckout/` |
| First-stable bootstrap | `UPayments.php` — qualified compatibility exception |
| Canonical basename | `supcheckout/UPayments.php` |
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

Final SUPCheckout identity and repository-coordinate closure:

- PR #67 certified head `0059f365883fa4edd6a2d623c7b370d38d3f565c` and squash merge `7547e59a2d5ef6d49b059851c6899a2d9987b16a`;
- post-merge PR #67 Quality #896, Compatibility #424 (**16/16**), Release Artifact #373, Provider Sandbox #334, WordPress.org #231 and CodeQL #717 — **SUCCESS**;
- repository renamed to `SimplixInnovations/supcheckout`;
- coordinate-closure PR #68 exact head `0e6ef6334282a83a428da7ee793daa98360c2bcc` passed Quality #900, Compatibility #428 (**16/16**), Release Artifact #377, Provider Sandbox #338, WordPress.org #235 and CodeQL #722;
- PR #68 squash-merged as `05fec942cc8fbeb58cfd0bd41f0ef5fdb86f966f`;
- post-merge Quality #901, Compatibility #429 (**16/16**), Release Artifact #378, Provider Sandbox #339, WordPress.org #236 and CodeQL #723 — **SUCCESS**;
- canonical remote branch topology is `main` only; open PRs/issues, tags and releases are empty.


Runtime-bearing SUPCheckout migration:

- PR #58 certified head `5bf84dccb880733da45c1f922d43554af69a33dc`;
- squash merge `6aabc4fcb0606567a11637ea07fe081fed4c7f85`;
- post-merge Quality #764, Compatibility #292 (**16/16**), Release Artifact #243, Provider Sandbox #207, WordPress.org #101 and CodeQL #579 — **SUCCESS**.

Documentation/control-plane closeout:

- squash merge `9591c431e1eb56fe40ca60147afdf9f3f909a212`;
- fresh main Quality #773, Compatibility #301 (**16/16**), Release Artifact #252, Provider Sandbox #216, WordPress.org #110 and CodeQL #588 — **SUCCESS**.

Latest first-party naming cleanup:

- PR #61 squash merge `efe937c67343242b7ccf3396a67b3cf2ce35ebac`;
- remaining safe retired first-party runtime/control identifiers migrated to canonical SUPCheckout equivalents while protected UPayments/persisted contracts remained unchanged;
- fresh main Quality #781, Compatibility #309 (**16/16**), Release Artifact #258, Provider Sandbox #221, WordPress.org #116 and CodeQL #595 — **SUCCESS**.

See [`docs/project/PROJECT-STATUS.md`](docs/project/PROJECT-STATUS.md) for the living state and [`docs/COMPATIBILITY.md`](docs/COMPATIBILITY.md) for the public evidence boundary.

## Identity architecture

The SUPCheckout rebrand deliberately separates **first-party product identity** from **provider/persisted compatibility identity**.

### Canonical first-party identity

- human name: **SUPCheckout for UPayments**;
- technical slug/text domain: `supcheckout`;
- namespace: `Simplixi\SUPCheckout`;
- release package: `supcheckout-X.Y.Z.zip`;
- package root: `supcheckout/`.

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

Real WordPress upgrade qualification proved that deleting/renaming an already-active physical `UPayments.php` can strand WordPress's stored plugin basename. Therefore the first-stable SUPCheckout package intentionally uses:

```text
supcheckout/UPayments.php
```

A future physical rename to `supcheckout.php` requires a separately approved and tested migration. It is not an unfinished requirement for the first release.

## Release artifact

Build and verify the current development artifact with:

```bash
bash scripts/build-release.sh dist
bash scripts/verify-release.sh dist/supcheckout-0.1.0.zip
sha256sum dist/supcheckout-0.1.0.zip
cat dist/supcheckout-0.1.0.zip.sha256
```

The release contract provides:

- one `supcheckout/` ZIP root;
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

1. finish the residual GitHub topic cleanup and Main Rule aggregate-check requirements;
2. verify local `origin` points directly to the canonical repository;
3. isolated local Composer/H12/SUPCheckout quality acceptance;
4. deterministic ZIP build/verification;
5. disposable WordPress/WooCommerce install;
6. Classic + Blocks + HPOS + bounded sandbox smoke;
7. approved launch branding/visual/accessibility acceptance;
8. explicit release/version and WordPress.org publication decision.

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
- [`docs/project/NAMING-IDENTITY-STANDARD.md`](docs/project/NAMING-IDENTITY-STANDARD.md) — canonical SUPCheckout identity and protected IDs
- [`docs/COMPATIBILITY.md`](docs/COMPATIBILITY.md) — public compatibility/evidence matrix
- [`docs/project/ENTERPRISE-CERTIFICATION.md`](docs/project/ENTERPRISE-CERTIFICATION.md) — enterprise and SUPCheckout certification evidence
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
