# SUPCheckout for UPayments — Canonical Naming, Identity, Compatibility and Namespace Standard

**Status:** CANONICAL / CURRENT
**Maintainer:** Simplix Innovations
**Product family:** SUPCheckout
**Canonical slug:** `supcheckout`

This document is authoritative for all new naming and identity decisions.

## Product hierarchy

```text
Simplix Innovations
└── SUPCheckout
    └── SUPCheckout for UPayments
```

- Human-facing formal name: **SUPCheckout for UPayments**
- Short product reference: **SUPCheckout**
- Provider/integration: **UPayments**
- Maintainer/publisher: **Simplix Innovations**

The word **for** is relationship copy only. It MUST NOT appear in URLs, repository names, WordPress.org slug, text domain, package names, namespaces, prefixes, CSS/JS roots, REST namespaces, logger sources, scheduler groups or release ZIP identifiers.

Forbidden technical form: `supcheckout-for-upayments`.

## Canonical technical identity

| Surface | Canonical value |
|---|---|
| Formal plugin name | **SUPCheckout for UPayments** |
| Product family | **SUPCheckout** |
| Provider | **UPayments** |
| Technical slug | `supcheckout` |
| Canonical GitHub repository | `SimplixInnovations/sucheckout` |
| WordPress.org slug | `supcheckout` |
| Plugin folder | `supcheckout/` |
| First-stable physical bootstrap | `UPayments.php` |
| First-stable plugin basename | `supcheckout/UPayments.php` |
| Future optional bootstrap target | `supcheckout.php` — only after a separately approved migration proves it safe |
| Text domain | `supcheckout` |
| Composer package | `simplix-innovations/supcheckout` |
| PHP namespace root | `Simplixi\SUPCheckout\UPayments` |
| Global PHP prefix | `supcheckout_` |
| Constants | `SUPCHECKOUT_*` |
| CSS component root | `.supcheckout` |
| CSS custom properties | `--supcheckout-*` |
| JS namespace | `supCheckout` |
| Localized JS config | `supCheckoutConfig` |
| REST namespace | `supcheckout/v1` |
| Action Scheduler group for new first-party jobs | `supcheckout` |
| Logger source for new first-party logging | `supcheckout` |
| Release ZIP | `supcheckout-X.Y.Z.zip` |
| Git tag form | `vX.Y.Z` |

## Provider-specific product boundary

SUPCheckout is permanently scoped to UPayments. Other providers must be implemented as separate products/repositories rather than adapters inside SUPCheckout. Cross-provider orchestration, routing and fraud-platform behavior belong to a separate platform project.

## Public positioning

Preferred first reference:

> **SUPCheckout for UPayments is an independently engineered UPayments payment gateway integration for WooCommerce by Simplix Innovations.**

UPayments is the external payment provider/service. Never imply that Simplix Innovations is the acquiring bank/payment processor, that SUPCheckout is owned by UPayments, or that UPayments officially endorses/distributes SUPCheckout unless explicit authorization exists.

## Critical compatibility rule

> **First-party rebranding must never destroy, detach or silently reinterpret persisted payment identity.**

Every inherited identifier must be classified before change as one of:

- **FIRST-PARTY RENAME** — owned branding/implementation identity that should use SUPCheckout;
- **LEGACY COMPATIBILITY** — historical merchant/store identity that remains readable/usable;
- **PROVIDER CONTRACT** — UPayments-defined request/response/schema terminology that must remain provider-accurate;
- **REMOVE** — obsolete implementation residue proven unused and safe to delete.

Never perform a blind repository-wide replacement of provider/persisted compatibility tokens or the retired pre-rebrand package-root token.

## Protected compatibility identifiers

| Identifier | Protected value | Policy |
|---|---|---|
| WooCommerce gateway/payment ID | `upayments` | preserve until a tested dual-ID migration exists |
| Settings option | `woocommerce_upayments_settings` | preserve/read; never silently discard |
| Historical order payment method | `upayments` | preserve indefinitely |
| Blocks / Store API payment identity | `upayments` | preserve until upgrade-safe migration exists |
| Callback route | `wc_upayments` | continue recognizing for existing/in-flight callbacks |
| Existing order/user/product metadata | `_upay_*` | preserve/read compatibly |
| Provider order identity | e.g. `UPayments_order_id` | preserve provider/historical semantics |
| H12 token secret | `upayments_token_identity_secret_v2` | preserve exactly |
| H12 provenance/scope/generation keys | historical forms | preserve exactly |
| Subscription cron | `upay_process_subscriptions` | preserve/recognize unless a tested migration supersedes it |
| Historical cleanup cron | `upay_hourly_cron_job` | recognize/clean only under characterized behavior |
| Billing-attempt table | `{$wpdb->prefix}upayments_billing_attempts` | preserve unless transactional migration is proven |
| Existing public hooks | `upayments_*` | audit before replacement; alias where compatibility requires it |

Provider API request/response fields, endpoint paths, provider payment-method names and schema terminology retain UPayments' exact contract.

## New first-party identifiers

New plugin-owned options, hooks, nonces and cache keys use `supcheckout_*`.

New metadata uses `_supcheckout_*` only where new storage is genuinely required; do not create duplicate metadata just for naming uniformity.

New script/style handles use `supcheckout-*`.
New CSS uses `.supcheckout` component scoping and `--supcheckout-*` custom properties.
A JS global is allowed only when necessary and must use `supCheckout`; localized configuration uses `supCheckoutConfig`.
New REST routes use `supcheckout/v1`.

## Physical bootstrap decision

The **first-stable physical bootstrap is intentionally `UPayments.php`**.

Prior real WordPress qualification proved that deleting or renaming an already-active `UPayments.php` can strand WordPress's stored plugin basename. The canonical SUPCheckout package therefore uses:

```text
supcheckout/UPayments.php
```

The future filename `supcheckout.php` is not current release identity. It may be considered only in a dedicated future migration that proves, on real WordPress:

1. old active installations continue loading;
2. no duplicate visible plugin entry is created;
3. activation, update, rollback and duplicate-package behavior remain safe;
4. stored plugin-basename state is migrated without stranding the plugin;
5. rollback remains non-destructive.

Until that proof exists, `UPayments.php` is a deliberate compatibility contract, not unfinished branding work.

## Text domain and translations

All SUPCheckout-owned translatable strings use the literal text domain `supcheckout`.

Dynamic translation domains and inherited `upayments` or third-party domains are not acceptable for SUPCheckout-owned copy. Provider names inside translated strings remain provider names; the **text domain** remains `supcheckout`.

No blanket Plugin Check ignore list is permitted.

## PHP architecture

Canonical namespace root:

```php
Simplixi\SUPCheckout\UPayments
```

Existing globals, provider identifiers and persisted compatibility identifiers migrate only where evidence permits. Namespace cleanliness never takes priority over merchant/payment compatibility.

## Repository coordinate rule

The canonical GitHub repository is:

`SimplixInnovations/sucheckout`

This repository coordinate is intentionally shorter than the WordPress/plugin technical slug `supcheckout`. The repository decision does **not** rename the plugin folder, text domain, Composer package, release ZIP, namespace, or protected compatibility identities.

Only **living repository-coordinate references** use the canonical GitHub coordinate. Historical evidence and legacy package-root migration fixtures retain older coordinates/tokens where they record true past state.

## Release engineering

The project remains on the independent `0.x` development line until an explicit release/version decision.

Canonical artifact forms:

```text
folder: supcheckout/
ZIP:    supcheckout-X.Y.Z.zip
tag:    vX.Y.Z
```

WordPress.org Plugin Check must execute against the actual unpacked deterministic release package using slug `supcheckout` and `plugin_repo` checks, without blanket error suppression.

## Identity governance

Changing any of the following requires explicit owner approval plus regression evidence appropriate to the risk:

- formal product name;
- technical slug;
- repository identity;
- WordPress.org slug;
- plugin folder or bootstrap filename;
- text domain;
- PHP namespace root;
- Composer package;
- public/global prefixes;
- REST/JS/CSS identity;
- protected compatibility allowlist.

Unsafe destructive compatibility rename verdict:

`NOT APPROVED.`
`DO NOT MERGE.`

**Naming architecture:** FROZEN / CURRENT
**First-stable bootstrap:** `UPayments.php` PROTECTED
**Legacy compatibility identifiers:** PROTECTED
**Formal trademark/legal clearance:** separate business/legal gate
