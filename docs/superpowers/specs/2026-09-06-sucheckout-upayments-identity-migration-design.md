# SUCheckout for UPayments — Identity Migration Design

**Status:** APPROVED / CANONICAL TARGET
**Approved:** 2026-09-06
**Maintainer:** Simplix Innovations
**Product brand:** SUCheckout
**Formal integration name:** SUCheckout for UPayments
**Canonical technical slug:** `sucheckout-upayments`

## Decision

The existing SimplixPay identity is retired before public release. The plugin becomes **SUCheckout for UPayments**.

The word **for** is human-facing relationship copy only. It MUST NOT appear in canonical technical identifiers such as URLs, repository slugs, WordPress.org slugs, text domains, package names, namespaces, prefixes, REST namespaces, CSS/JS roots, release ZIP names, or similar machine identifiers.

UPayments is the supported external payment provider/integration. SUCheckout is the Simplixi-owned product identity.

## Product hierarchy

```text
Simplix Innovations
└── SUCheckout
    └── SUCheckout for UPayments
```

Use:

- product family / brand: **SUCheckout**;
- formal plugin name: **SUCheckout for UPayments**;
- provider: **UPayments**;
- maintainer/public company name: **Simplix Innovations**.

Do not use SUPayments, SUPayment, SimplixPay, Simplixi Payments, SUCheckout UPayments, or SUCheckout-for-UPayments as canonical product identities.

## Canonical technical identity

| Surface | Canonical target |
|---|---|
| Formal plugin name | **SUCheckout for UPayments** |
| Product family | **SUCheckout** |
| Canonical slug | `sucheckout-upayments` |
| WordPress.org slug | `sucheckout-upayments` |
| Target GitHub repository | `SimplixInnovations/sucheckout-upayments` |
| Plugin folder | `sucheckout-upayments/` |
| Canonical main file target | `sucheckout-upayments.php` |
| Text domain | `sucheckout-upayments` |
| Composer package | `simplix-innovations/sucheckout-upayments` |
| PHP namespace root | `Simplixi\SUCheckout\UPayments` |
| Global function prefix | `sucheckout_upayments_` |
| Constants | `SUCHECKOUT_UPAYMENTS_*` |
| New option/meta prefix | `sucheckout_upayments_*` / `_sucheckout_upayments_*` |
| CSS root | `.sucheckout-upayments` |
| CSS custom properties | `--sucheckout-upayments-*` |
| Script/style handles | `sucheckout-upayments-*` |
| JS namespace | `suCheckoutUpayments` |
| JS config | `suCheckoutUpaymentsConfig` |
| HTML/data prefix | `sucheckout-upayments-*` / `data-sucheckout-upayments-*` |
| REST namespace | `sucheckout-upayments/v1` |
| Logger source | `sucheckout-upayments` |
| Action Scheduler group | `sucheckout-upayments` |
| Future WP-CLI root | `wp sucheckout-upayments` |
| Release ZIP | `sucheckout-upayments-X.Y.Z.zip` |
| Release tags | `vX.Y.Z` |

## Human-facing wording

Preferred first reference:

> **SUCheckout for UPayments is an independently engineered UPayments payment gateway integration for WooCommerce by Simplix Innovations.**

UPayments must be described as the external payment provider/service. Do not imply SUCheckout is UPayments, that Simplix Innovations processes/acquires funds, or that UPayments owns or officially endorses SUCheckout unless written authorization exists.

Customer-facing checkout labels may remain provider/payment-method clear rather than advertising the plugin brand.

## Compatibility architecture

The migration is a **full first-party rebrand**, not a destructive global replacement.

Every old identifier is classified into one of four groups:

1. **RENAME** — first-party product, package, namespace, UI, asset, build, documentation, or release identity.
2. **LEGACY-COMPATIBILITY** — persisted merchant/order/token/subscription identity that must remain readable or callable.
3. **PROVIDER-CONTRACT** — exact UPayments API fields, endpoints, provider terminology, and payment-method concepts that must not be renamed.
4. **REMOVE** — obsolete SimplixPay/old branding residue with no compatibility role.

### Protected legacy compatibility identifiers

These are not SUCheckout branding. They remain recognized where required to preserve historical data and in-flight operations:

| Legacy identifier | Policy |
|---|---|
| WooCommerce gateway/payment ID `upayments` | Preserve compatibility; migration to a new active ID requires explicit upgrade proof |
| Settings option `woocommerce_upayments_settings` | Preserve/read during migration; never silently discard |
| Historical order payment method `upayments` | Preserve indefinitely for old orders |
| Blocks/Store API legacy method key `upayments` | Preserve compatibility until an upgrade-safe dual-ID strategy is proven |
| Callback route `wc_upayments` | Continue recognizing for old/in-flight provider callbacks |
| Existing `_upay_*` metadata | Preserve/read compatibly |
| Token secret/provenance/scope/generation historical keys | Preserve exactly unless a cryptographically safe migration is separately proven |
| Subscription hook `upay_process_subscriptions` | Recognize/migrate safely; do not strand scheduled events |
| Historical cleanup hook `upay_hourly_cron_job` | Recognize/clean as required |
| Billing attempt table `{$wpdb->prefix}upayments_billing_attempts` | Preserve unless a transactional data migration is separately proven |
| Existing public `upayments_*` hooks | Audit before replacement; keep aliases when external consumers may depend on them |

New first-party identifiers MUST use SUCheckout naming.

## Main-file migration

The desired canonical bootstrap is `sucheckout-upayments.php`, but previous real WordPress qualification proved that deleting/renaming the active historical `UPayments.php` file can strand an existing active-plugin basename.

Therefore:

- do not remove `UPayments.php` until an automated real-WordPress upgrade test proves the transition;
- prefer a compatibility bootstrap/shim strategy if it can preserve old active-plugin entries without duplicate plugin listings;
- if WordPress cannot safely transition the basename in-place, retain the historical physical bootstrap as a documented compatibility exception for the first stable release rather than shipping an unproven migration;
- the text domain, package slug, public product identity, namespaces, assets, and release package do not depend on the physical bootstrap filename and should still migrate.

## Text-domain policy

All plugin-owned translatable source strings must use the literal domain:

```text
sucheckout-upayments
```

Dynamic translation domains and the inherited `upayments` domain are retired from first-party translation calls. Provider names inside translatable strings remain `UPayments` when semantically required.

No fake Plugin Check suppressions or blanket ignore codes are allowed.

## Source/refactor policy

First-party architecture should converge on `Simplixi\SUCheckout\UPayments`.

Legacy `UPayments\...` classes and global names may remain only behind explicit compatibility tests/aliases where replacing them would break stored data, callbacks, hooks, or external consumers.

Provider request/response schema names must retain provider terminology exactly.

## WordPress.org and release policy

The deterministic install package must be rooted at `sucheckout-upayments/` and use `sucheckout-upayments` for the WordPress.org slug/text domain.

A canonical `readme.txt` must disclose the external UPayments service, its API documentation/terms links, certified feature boundaries, and unsupported/external-manual boundaries.

The official WordPress Plugin Check `plugin_repo` category must run against the unpacked deterministic ZIP. Errors are fixed, not hidden.

No public tag, GitHub Release, WordPress.org submission, version promotion, or repository rename occurs until exact-head certification is green and the owner performs/approves the publication step.

## Quality requirements

The migration is complete only when:

- no active first-party SimplixPay/simplixpay-upayments product identity remains;
- no technical identifier contains `for`;
- all intended SUCheckout technical identifiers are canonical and tested;
- protected legacy/provider identities remain covered by regression tests;
- Quality/H12 is green;
- the full compatibility matrix is green;
- deterministic release build/verification is green;
- Provider Sandbox is green when provider transport/lifecycle code is touched;
- CodeQL/security checks are green;
- official WordPress Plugin Check has zero blocking `plugin_repo` errors against the packaged ZIP;
- remaining warnings are individually classified and fixed where genuine;
- docs/status/release evidence describe SUCheckout, not the retired SimplixPay identity;
- no stale open PR/issue remains at final closeout.

## Branding boundary

Engineering identity can proceed without the visual brand package. Before final UI/WordPress.org visual assets are locked, the latest Simplixi branding should be applied to typography, color, spacing, radii, iconography, logos, screenshots, banners, and admin/checkout visual presentation.
