# SUCheckout for UPayments — Canonical Naming, Identity, Compatibility and Namespace Standard

**Status:** CANONICAL / CURRENT
**Maintainer:** Simplix Innovations
**Product family:** SUCheckout
**Canonical technical slug:** `sucheckout-upayments`

This document is authoritative for all new naming and identity decisions.

## Product hierarchy

```text
Simplix Innovations
└── SUCheckout
    └── SUCheckout for UPayments
```

- Human-facing formal name: **SUCheckout for UPayments**
- Short product reference: **SUCheckout**
- Provider/integration: **UPayments**
- Maintainer/publisher: **Simplix Innovations**

The word **for** is relationship copy only. It MUST NOT appear in URLs, repository names, WordPress.org slug, text domain, package names, namespaces, prefixes, CSS/JS roots, REST namespaces, logger sources, scheduler groups or release ZIP identifiers.

Forbidden technical form: `sucheckout-for-upayments`.

## Canonical technical identity

| Surface | Canonical value |
|---|---|
| Formal plugin name | **SUCheckout for UPayments** |
| Product family | **SUCheckout** |
| Provider | **UPayments** |
| Technical slug | `sucheckout-upayments` |
| Target GitHub repository | `SimplixInnovations/sucheckout-upayments` |
| WordPress.org slug | `sucheckout-upayments` |
| Plugin folder | `sucheckout-upayments/` |
| First-stable physical bootstrap | `UPayments.php` |
| First-stable plugin basename | `sucheckout-upayments/UPayments.php` |
| Future optional bootstrap target | `sucheckout-upayments.php` — only after a separately approved migration proves it safe |
| Text domain | `sucheckout-upayments` |
| Composer package | `simplix-innovations/sucheckout-upayments` |
| PHP namespace root | `Simplixi\SUCheckout\UPayments` |
| Global PHP prefix | `sucheckout_upayments_` |
| Constants | `SUCHECKOUT_UPAYMENTS_*` |
| CSS component root | `.sucheckout-upayments` |
| CSS custom properties | `--sucheckout-upayments-*` |
| JS namespace | `suCheckoutUpayments` |
| Localized JS config | `suCheckoutUpaymentsConfig` |
| REST namespace | `sucheckout-upayments/v1` |
| Action Scheduler group for new first-party jobs | `sucheckout-upayments` |
| Logger source for new first-party logging | `sucheckout-upayments` |
| Release ZIP | `sucheckout-upayments-X.Y.Z.zip` |
| Git tag form | `vX.Y.Z` |

## Public positioning

Preferred first reference:

> **SUCheckout for UPayments is an independently engineered UPayments payment gateway integration for WooCommerce by Simplix Innovations.**

UPayments is the external payment provider/service. Never imply that Simplix Innovations is the acquiring bank/payment processor, that SUCheckout is owned by UPayments, or that UPayments officially endorses/distributes SUCheckout unless explicit authorization exists.

## Critical compatibility rule

> **First-party rebranding must never destroy, detach or silently reinterpret persisted payment identity.**

Every inherited identifier must be classified before change as one of:

- **FIRST-PARTY RENAME** — owned branding/implementation identity that should use SUCheckout;
- **LEGACY COMPATIBILITY** — historical merchant/store identity that remains readable/usable;
- **PROVIDER CONTRACT** — UPayments-defined request/response/schema terminology that must remain provider-accurate;
- **REMOVE** — obsolete implementation residue proven unused and safe to delete.

Never perform a blind repository-wide replacement of `upayments`, `_upay_`, `UPayments` or `simplixpay-upayments`.

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

New plugin-owned options, hooks, nonces and cache keys use `sucheckout_upayments_*`.

New metadata uses `_sucheckout_upayments_*` only where new storage is genuinely required; do not create duplicate metadata just for naming uniformity.

New script/style handles use `sucheckout-upayments-*`.
New CSS uses `.sucheckout-upayments` component scoping and `--sucheckout-upayments-*` custom properties.
A JS global is allowed only when necessary and must use `suCheckoutUpayments`; localized configuration uses `suCheckoutUpaymentsConfig`.
New REST routes use `sucheckout-upayments/v1`.

## Physical bootstrap decision

The **first-stable physical bootstrap is intentionally `UPayments.php`**.

Prior real WordPress qualification proved that deleting or renaming an already-active `UPayments.php` can strand WordPress's stored plugin basename. The canonical SUCheckout package therefore uses:

```text
sucheckout-upayments/UPayments.php
```

The future filename `sucheckout-upayments.php` is not current release identity. It may be considered only in a dedicated future migration that proves, on real WordPress:

1. old active installations continue loading;
2. no duplicate visible plugin entry is created;
3. activation, update, rollback and duplicate-package behavior remain safe;
4. stored plugin-basename state is migrated without stranding the plugin;
5. rollback remains non-destructive.

Until that proof exists, `UPayments.php` is a deliberate compatibility contract, not unfinished branding work.

## Text domain and translations

All SUCheckout-owned translatable strings use the literal text domain `sucheckout-upayments`.

Dynamic translation domains and inherited `upayments` or third-party domains are not acceptable for SUCheckout-owned copy. Provider names inside translated strings remain provider names; the **text domain** remains `sucheckout-upayments`.

No blanket Plugin Check ignore list is permitted.

## PHP architecture

Canonical namespace root:

```php
Simplixi\SUCheckout\UPayments
```

Existing globals, provider identifiers and persisted compatibility identifiers migrate only where evidence permits. Namespace cleanliness never takes priority over merchant/payment compatibility.

## Repository rename rule

Until the owner renames GitHub, the repository coordinate remains temporarily:

`SimplixInnovations/simplixpay-upayments`

The approved target is:

`SimplixInnovations/sucheckout-upayments`

After the GitHub rename, update only **living repository-coordinate references** in a dedicated PR. Do not rewrite historical evidence or legacy migration fixtures merely to remove the old repository/package token.

## Release engineering

The project remains on the independent `0.x` development line until an explicit release/version decision.

Canonical artifact forms:

```text
folder: sucheckout-upayments/
ZIP:    sucheckout-upayments-X.Y.Z.zip
tag:    vX.Y.Z
```

WordPress.org Plugin Check must execute against the actual unpacked deterministic release package using slug `sucheckout-upayments` and `plugin_repo` checks, without blanket error suppression.

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
