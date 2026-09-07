# SUCheckout for UPayments — Canonical Naming, Identity, Compatibility and Namespace Standard

**Status:** CANONICAL / FROZEN CURRENT IDENTITY
**Maintainer:** Simplix Innovations
**Product family:** SUCheckout
**Canonical slug:** `sucheckout-upayments`

This document is authoritative for all new naming decisions.

## Product hierarchy

```text
Simplix Innovations
└── SUCheckout
    └── SUCheckout for UPayments
```

Human-facing formal name: **SUCheckout for UPayments**.
Short product reference: **SUCheckout**.
Provider: **UPayments**.

The word **for** is relationship copy only. It MUST NOT appear in URL, repository, WordPress.org slug, text domain, package, namespace, prefix, CSS/JS root, REST namespace, logger source, scheduler group, or release ZIP identifiers.

## Frozen technical identity

| Surface | Canonical value |
|---|---|
| Formal plugin name | **SUCheckout for UPayments** |
| Product family | **SUCheckout** |
| Provider | **UPayments** |
| Canonical slug | `sucheckout-upayments` |
| Target GitHub repository | `SimplixInnovations/sucheckout-upayments` |
| WordPress.org slug | `sucheckout-upayments` |
| Plugin folder | `sucheckout-upayments/` |
| Current first-stable physical bootstrap | `UPayments.php` — certified compatibility exception |
| Future bootstrap target | `sucheckout-upayments.php` — only after separately certified migration |
| Text domain | `sucheckout-upayments` |
| Composer package | `simplix-innovations/sucheckout-upayments` |
| PHP namespace root | `Simplixi\SUCheckout\UPayments` |
| Global PHP prefix | `sucheckout_upayments_` |
| Constants | `SUCHECKOUT_UPAYMENTS_*` |
| CSS root | `.sucheckout-upayments` |
| JS namespace | `suCheckoutUpayments` |
| REST namespace | `sucheckout-upayments/v1` |
| Action Scheduler group | `sucheckout-upayments` |
| Release ZIP | `sucheckout-upayments-X.Y.Z.zip` |

Forbidden technical form: `sucheckout-for-upayments`.

## Public positioning

Preferred first reference:

> **SUCheckout for UPayments is an independently engineered UPayments payment gateway integration for WooCommerce by Simplix Innovations.**

UPayments is the external payment provider/service. Do not imply that Simplix Innovations is the acquirer/processor, that SUCheckout is owned by UPayments, or that UPayments officially endorses SUCheckout unless explicit authorization exists.

## Critical compatibility rule

> **First-party rebranding must never destroy or silently disconnect persisted payment identity.**

Every inherited identifier is classified as **RENAME**, **LEGACY-COMPATIBILITY**, **PROVIDER-CONTRACT**, or **REMOVE**.

### Protected compatibility identifiers

| Identifier | Legacy value | Policy |
|---|---|---|
| WooCommerce gateway/payment ID | `upayments` | preserve until a tested dual-ID migration exists |
| Settings option | `woocommerce_upayments_settings` | preserve/read; never silently discard |
| Historical order payment method | `upayments` | preserve indefinitely |
| Blocks/Store API legacy key | `upayments` | preserve until upgrade-safe migration exists |
| Callback route | `wc_upayments` | continue recognizing for old/in-flight callbacks |
| Existing metadata | `_upay_*` | preserve/read compatibly |
| H12 token secret | `upayments_token_identity_secret_v2` | preserve exactly |
| H12 provenance/scope/generation keys | historical forms | preserve exactly |
| Subscription cron | `upay_process_subscriptions` | recognize/migrate safely |
| Historical cleanup cron | `upay_hourly_cron_job` | recognize/clean as required |
| Billing-attempt table | `{$wpdb->prefix}upayments_billing_attempts` | preserve unless transactional migration is proven |
| Existing public hooks | `upayments_*` | audit before replacement; alias when needed |

Provider API request/response fields, endpoint paths, payment-method names, and schema terminology retain the provider's exact contract.

## New first-party identifiers

New plugin-owned options/hooks/nonces/cache keys use `sucheckout_upayments_*`.
New metadata uses `_sucheckout_upayments_*` only where new storage is genuinely required.
New REST routes use `sucheckout-upayments/v1`.
New script/style handles use `sucheckout-upayments-*`.
New CSS uses a `.sucheckout-upayments` component root and `--sucheckout-upayments-*` custom properties.
A JS global is allowed only when necessary and must use `suCheckoutUpayments`; localized configuration uses `suCheckoutUpaymentsConfig`.

## Main-file transition

The desired future bootstrap target is `sucheckout-upayments.php`, but the current certified first-stable physical bootstrap is `UPayments.php`. Prior real WordPress qualification proved that deleting/renaming an already-active `UPayments.php` can strand the historical active-plugin basename.

Therefore the physical bootstrap transition is test-gated:

1. old active installations must continue loading;
2. no duplicate visible plugin entry may be created;
3. activation, upgrade, rollback and duplicate-package behavior must be verified on real WordPress;
4. if a compatibility shim cannot prove those properties, `UPayments.php` remains a documented first-stable compatibility exception.

The physical filename does not block migration of the product name, text domain, package slug, namespace, assets, docs, or release identity.

## Text domain

All plugin-owned translatable source strings must converge on the literal domain `sucheckout-upayments`.

Dynamic translation domains and inherited `upayments`/third-party domains are not acceptable for SUCheckout-owned strings. No blanket Plugin Check ignores are permitted.

## PHP architecture

Approved destination namespace:

```php
Simplixi\SUCheckout\UPayments
```

Existing `UPayments\...` and global legacy classes migrate only where compatibility tests permit. Provider/persistence names are not renamed merely for cosmetic consistency.

## Release engineering

Independent semantic versioning remains on the current 0.x development line until an explicit release decision.

Target artifacts:

```text
folder: sucheckout-upayments/
ZIP:    sucheckout-upayments-X.Y.Z.zip
tag:    vX.Y.Z
```

WordPress.org Plugin Check must execute against the actual unpacked deterministic ZIP using the `plugin_repo` category with no blanket ignore list.

## Identity governance

Changing the formal name, technical slug, repository, WordPress.org slug, plugin folder, current physical bootstrap/future bootstrap target, text domain, PHP namespace root, Composer package, prefixes, REST/JS/CSS naming, or compatibility allowlist requires explicit owner approval plus regression evidence.

Unsafe destructive compatibility rename verdict:

`NOT APPROVED.`
`DO NOT MERGE.`

**Naming architecture:** FROZEN CURRENT IDENTITY
**Legacy compatibility identifiers:** PROTECTED
**Formal trademark/legal clearance:** separate business/legal gate
