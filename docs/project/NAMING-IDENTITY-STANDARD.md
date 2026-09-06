# SimplixPay for UPayments — Canonical Naming, Identity, Compatibility and Namespace Standard

**Status:** CANONICAL / ENGINEERING-FROZEN  
**Maintainer:** Simplix Innovations  
**Canonical slug:** `simplixpay-upayments`

This document is authoritative for new naming decisions. It exists to prevent naming drift and accidental compatibility breakage.

## Product hierarchy

```text
Simplix Innovations
└── SimplixPay
    ├── SimplixPay for UPayments
    └── future SimplixPay integrations/products
```

`SimplixPay` alone is reserved for the broader/future payment-product family, including the planned multi-provider **SimplixPay for WooCommerce** direction.

Use:

- formal plugin/product: **SimplixPay for UPayments**;
- short integration reference: **SimplixPay UPayments**;
- product family: **SimplixPay**;
- provider: **UPayments**.

## Frozen identity table

| Surface | Canonical value |
|---|---|
| Company / maintainer | **Simplix Innovations** |
| Formal plugin name | **SimplixPay for UPayments** |
| Short integration reference | **SimplixPay UPayments** |
| Product family | **SimplixPay** |
| Provider | **UPayments** |
| Canonical slug | `simplixpay-upayments` |
| GitHub repository | `SimplixInnovations/simplixpay-upayments` |
| WordPress.org slug candidate | `simplixpay-upayments` |
| Plugin folder target | `simplixpay-upayments/` |
| Main plugin file target | `simplixpay-upayments.php` |
| Plugin basename target | `simplixpay-upayments/simplixpay-upayments.php` |
| Text domain target | `simplixpay-upayments` |
| Domain path | `/languages` |
| Composer package | `simplix-innovations/simplixpay-upayments` |
| PHP namespace root | `Simplix\Pay\UPayments` |
| Global PHP prefix | `simplixpay_upayments_` |
| Constants | `SIMPLIXPAY_UPAYMENTS_*` |
| CSS prefix/root | `simplixpay-upayments` |
| JS namespace | `simplixPayUpayments` |
| REST namespace | `simplixpay-upayments/v1` |
| Action Scheduler group | `simplixpay-upayments` |
| Release tags | `vX.Y.Z` |
| Release ZIP | `simplixpay-upayments-X.Y.Z.zip` |

## Spelling/casing

Human-facing: `SimplixPay`, `SimplixPay for UPayments`, `SimplixPay UPayments`, `Simplix Innovations`, `UPayments`, `WooCommerce`, `WordPress`.

Do not introduce `Simplixpay`, `Simplix Pay`, `SimplixPAY`, `Upayments`, `U Payments`, `simplixpay-for-upayments`, `simplix-pay-upayments`, `SPay`, `spay-upayments`, or similar alternatives.

## Public positioning

Recommended first reference:

> **SimplixPay for UPayments is an independently engineered and maintained UPayments integration for WooCommerce by Simplix Innovations.**

Until explicit authorization says otherwise, state that UPayments is the payment provider/trademark owner and this is not the official UPayments distribution.

Do not imply Simplix is the acquirer/processor, UPayments owns SimplixPay, or UPayments endorses the plugin.

## Repository/provenance

Canonical repo: `SimplixInnovations/simplixpay-upayments` (standalone). Historical engineering/audit archive: `SimplixInnovations/upayments-woocommerce`. Provider upstream: `upaymentskwt/woocommerce`.

The canonical product history may be clean while the historical archive retains old PRs/commits/reviews for provenance.

## Main plugin identity transition

Target folder/file/text domain are frozen, but moving from the inherited packaging is an upgrade migration problem, not cosmetic cleanup. Task 7 verified activation/update identity, settings retention, callbacks, scheduled events, rollback and duplicate-package behavior on current and floor runtime cells.

The direct main-file migration is **not authorized for the first stable release**: a controlled `simplixpay-upayments.php` candidate left WordPress's historical `simplixpay-upayments/UPayments.php` active entry pointing at a missing file, did not activate the target basename and did not load the runtime. The first stable release therefore retains `UPayments.php`; the target below remains a future migration target.

Target plugin header identity:

```php
/**
 * Plugin Name: SimplixPay for UPayments
 * Plugin URI: <canonical Simplix product/docs URL>
 * Description: Independently engineered UPayments payment integration for WooCommerce by Simplix Innovations.
 * Version: <Simplix version>
 * Author: Simplix Innovations
 * Author URI: https://simplixi.com
 * Text Domain: simplixpay-upayments
 * Domain Path: /languages
 */
```

Requires/Tested metadata must be evidence-based.

## Independent versioning/releases

SimplixPay UPayments owns its semantic version line. Do not continue upstream 3.x merely because inherited code says 3.1.1. Use development 0.x, first stable 1.0.0 when release gates pass. Tags `vX.Y.Z`; ZIP `simplixpay-upayments-X.Y.Z.zip` with root `simplixpay-upayments/`.

The update channel must be Simplix-controlled. A future WordPress.org build should normally use WordPress.org as update authority and not ship a conflicting external updater.

## PHP architecture

All new architecture uses namespace root:

```php
Simplix\Pay\UPayments
```

Approved domains include `Plugin`, `Requirements`, `Api`, `Gateway`, `Payment`, `Webhook`, `Refund`, `Token`, `Migration`, `Subscription`, `Blocks`, `Admin`, `Diagnostics`, `Logging`, `Compatibility`, `Infrastructure`, `Contracts`.

Use concise class names because namespace carries provider context, e.g. `Simplix\Pay\UPayments\Api\Client`, `Payment\StateMachine`, `Token\CustomerTokenIdentity`, `Migration\Preflight`.

Existing legacy classes/namespaces (`WC_Upayments`, `UPayments\...`) migrate incrementally only under characterization/regression tests. No big-bang namespace rename.

Global functions: `simplixpay_upayments_*`. Constants: `SIMPLIXPAY_UPAYMENTS_*`.

Composer target:

```json
{"name":"simplix-innovations/simplixpay-upayments","autoload":{"psr-4":{"Simplix\\Pay\\UPayments\\":"src/"}}}
```

## Critical compatibility rule

> **Rebranding must never destroy or silently change persisted payment identity.**

Public/product identity can become SimplixPay while historical runtime/storage/provider identities remain `upayments`/`_upay_*` where compatibility requires.

### Protected identifiers

| Identifier | Legacy/current value | Policy |
|---|---|---|
| WooCommerce gateway/payment ID | `upayments` | **PRESERVE** |
| Settings option | `woocommerce_upayments_settings` | **PRESERVE** |
| Blocks method identity | `upayments` | **PRESERVE** |
| Store API extension key | `upayments` | **PRESERVE** |
| Callback route | `wc_upayments` | **PRESERVE** |
| Historical order method | `upayments` | **PRESERVE indefinitely** |
| Existing order/user/product metadata | `_upay_*` | **PRESERVE/read compatibly** |
| H12 secret | `upayments_token_identity_secret_v2` | **ABSOLUTELY PRESERVE** |
| H12 provenance/scope/generation keys | historical forms | **ABSOLUTELY PRESERVE** |
| Subscription cron | `upay_process_subscriptions` | **PRESERVE unless migrated safely** |
| Historical cleanup cron | `upay_hourly_cron_job` | recognize/clean as required |
| Billing attempt table | `{$wpdb->prefix}upayments_billing_attempts` | **PRESERVE** |
| Existing public hooks | `upayments_*` | **AUDIT BEFORE CHANGE** |

Changing one requires an approved migration with old/new precedence, upgrade, rollback, mixed-version/failure behavior and regression tests.

## New WordPress identifiers

New plugin-owned options: `simplixpay_upayments_*`. New order/user/product metadata: `_simplixpay_upayments_*` where genuinely needed. New AJAX actions/nonces/cron/action hooks: `simplixpay_upayments_*`. New REST namespace: `simplixpay-upayments/v1`. New Action Scheduler group: `simplixpay-upayments`.

Do not create new custom capabilities/storage merely for naming consistency. Prefer standard WordPress/WooCommerce constructs when appropriate.

## Hooks

Existing `upayments_*` hooks are compatibility APIs until audited. New hooks use names such as `simplixpay_upayments/payment_reconciled` and `simplixpay_upayments/payment_request`.

## Database/cache/transients

Do not rename existing durable tables for branding. New custom tables, only if justified, use `{$wpdb->prefix}simplixpay_upayments_*`. New options/transients use `simplixpay_upayments_*` subject to WordPress key-length constraints. Object-cache group may be `simplixpay-upayments`.

Security-sensitive H12 identity reads keep fail-closed/fresh-read semantics where required.

## Frontend naming

New CSS root/prefix: `.simplixpay-upayments`, with component-scoped BEM-like classes (`.simplixpay-upayments__saved-card`, etc.) and custom properties `--simplixpay-upayments-*`. Avoid unscoped generic Woo/theme layout overrides.

New script/style handles: `simplixpay-upayments-*`. JS global only if unavoidable: `window.simplixPayUpayments`; localized config `simplixPayUpaymentsConfig`. Do not consume unqualified `window.simplixPay`, reserved for broader product-family use.

Data attributes: `data-simplixpay-upayments-*`. HTML IDs: `simplixpay-upayments-*` where IDs are necessary.

## Logging/errors/CLI

Preferred logger source: `simplixpay-upayments`, with structured component context. Stable errors: namespaced forms such as `simplixpay_upayments.payment.indeterminate` and `simplixpay_upayments.token.migration_required`.

Future WP-CLI root: `wp simplixpay-upayments` with subcommands for status, diagnostics, migration and reconciliation.

## Admin/customer naming

Admin gateway title may be **SimplixPay for UPayments**. Customer-facing checkout title should remain provider/payment-method clear (e.g. UPayments or merchant-configured title), not forced plugin advertising.

Provider API fields (`customerUniqueToken`, `notificationUrl`, etc.) keep exact provider terminology; Simplix branding never renames external API schema.

## Text domain/i18n

Target text domain `simplixpay-upayments`, with language files/WordPress.org infrastructure aligned to that slug. Existing `upayments` translation usage requires a deliberate WPML/String Translation-tested migration, not blind global replacement.

Task 7 observes 70 explicit translation calls still bound to `upayments` in the installable package. Because no coordinated WPML/String Translation migration has been certified, the first stable release retains `upayments`. This does not change the frozen future target.

## Agent/reviewer rule

Agents must not invent names or globally rename compatibility identifiers. Reviewers must inspect gateway IDs, callbacks, options/meta, scheduled hooks, provider schema, text-domain transition and plugin-basename upgrade implications.

Unsafe compatibility rename verdict:

`NOT APPROVED.`  
`DO NOT MERGE.`

## Identity governance

Changing formal name, short reference, slug, repo slug, WordPress.org slug, folder/main file, text domain, PHP namespace root, Composer package, prefixes, REST/JS/CSS naming requires explicit project-owner approval and compatibility assessment, plus updating this document.

## Legal/trademark status

Engineering identity is frozen; formal legal/trademark clearance is a separate business/legal gate before major commercial launch if required. Public searches are not a legal opinion.

## Quick reference

```text
Formal product: SimplixPay for UPayments
Short integration reference: SimplixPay UPayments
Reserved product family: SimplixPay
Canonical slug: simplixpay-upayments
Repo: SimplixInnovations/simplixpay-upayments
Target folder: simplixpay-upayments
Target main file: simplixpay-upayments.php
Target text domain: simplixpay-upayments
Composer: simplix-innovations/simplixpay-upayments
New namespace: Simplix\Pay\UPayments
New global prefix: simplixpay_upayments_
New constants: SIMPLIXPAY_UPAYMENTS_*
CSS prefix: simplixpay-upayments
JS namespace: simplixPayUpayments
REST: simplixpay-upayments/v1
Action Scheduler group: simplixpay-upayments
Release ZIP: simplixpay-upayments-X.Y.Z.zip

Do not globally rename protected upayments/upay runtime or persistence identities.
```

**Naming architecture:** FROZEN  
**Legacy compatibility identifiers:** PROTECTED  
**Formal trademark/legal clearance:** separate/pending gate
