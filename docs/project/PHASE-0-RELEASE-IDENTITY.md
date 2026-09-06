# Phase 0 — Release Identity and Updater Ownership

**Status:** DONE / VERIFIED

**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

**Development version established:** `0.1.0`

**Implementation merge:** PR #9 → `678f3bdae32b7a0d5922c6ebb7fa7535ede256dd`

**Merged tree:** `80618e737476a92357bd463f6e1495c364157e83`

## Outcome

Phase 0 took ownership of the public SimplixPay release identity without rewriting persisted UPayments payment identity and without leaving upstream code in control of updates.

The plugin now publicly identifies as **SimplixPay for UPayments** by **Simplix Innovations**, uses an independent pre-1.0 version line, has no inherited external updater authority, and preserves the historical runtime identifiers required for existing stores/orders/tokens/subscriptions.

This gate does **not** mean the plugin is broadly production-certified. It closes release-identity/updater ownership only. At Phase 0 closure, migration, payment-lifecycle, security, architecture, compatibility and release-certification phases still remained. Phase 9I, Provider Contract & Payment Lifecycle, the bounded Security Threat-Model Closure, Architecture discovery/A1-A5 and Quality Platform Q1-Q19 have since become **DONE / VERIFIED**; **Enterprise Compatibility Certification** is the current program gate.

## Verified public plugin identity

At Phase 0 closure, the active `UPayments.php` header was:

- Plugin Name: **SimplixPay for UPayments**
- Plugin URI: `https://github.com/SimplixInnovations/simplixpay-upayments`
- Description: `Independently engineered UPayments payment integration for WooCommerce by Simplix Innovations.`
- Version: `0.1.0`
- Author: **Simplix Innovations**
- Author URI: `https://simplixi.com`
- Requires at least: `5.6` — Phase 0 historical value; later Enterprise Compatibility Certification raises this only from real matrix evidence
- Requires PHP: `7.2` — Phase 0 historical value; later Enterprise Compatibility Certification raises this only from real matrix evidence
- License: MIT
- Text Domain: `upayments` — intentionally transitional
- Domain Path: `/languages`

The current Enterprise Compatibility declaration tranche has since independently verified a real WordPress/WooCommerce/PHP matrix and derives the current public support headers from that evidence. See `docs/project/ENTERPRISE-CERTIFICATION.md` and `docs/COMPATIBILITY.md` for the living compatibility truth; the values above remain the Phase 0 historical release-identity evidence.

The Simplix code-side identity is defined by `Simplix\Pay\UPayments\Release\Identity` and exposes canonical product/version/slug/repository/update-channel constants.

## Independent version line

The inherited provider version `3.1.1` is no longer the Simplix product version.

The Simplix development line begins at `0.1.0`. `1.0.0` remains reserved for the first stable release that satisfies the later release-readiness gates.

The Phase 0 harness requires the plugin header version to equal `Simplix\Pay\UPayments\Release\Identity::VERSION`.

## Update authority — resolved

External self-update checking is deliberately **disabled** at Phase 0 closure.

Verified changes:

- inherited `upaymentskwt/woocommerce` update authority removed from the bootstrap;
- `PucFactory` updater initialization removed;
- Plugin Update Checker bootstrap include removed;
- complete bundled `vendor/plugin-update-checker/` subtree removed;
- canonical `SIMPLIXPAY_UPAYMENTS_UPDATE_CHANNEL` reports `disabled`.

A Simplix-controlled external updater is intentionally not introduced until the physical package/basename migration has a tested contract. A future WordPress.org distribution should use WordPress.org update infrastructure rather than ship a conflicting external updater.

## Physical plugin basename — deliberately transitional

Phase 0 retains the existing main filename:

`UPayments.php`

The frozen eventual target remains:

`simplixpay-upayments.php`

Changing the main file/folder changes WordPress activation/update identity. It is therefore an explicit upgrade/package migration, not a cosmetic rebrand. No blind rename is authorized.

### First stable release decision — retain `UPayments.php`

Task 7 has now executed the required existing-install characterization on two real supported environments:

- WordPress 7.1 / WooCommerce 11.1.0 / PHP 8.3;
- WordPress 6.9.7 / WooCommerce 10.8.1 / PHP 8.3.

In both environments, an already-active prior `UPayments.php` package upgraded to the current same-basename package and rolled back without losing activation identity, merchant settings, historical payment/token/subscription metadata, the `wc_upayments` callback or the canonical `upay_process_subscriptions` schedule.

A duplicate-root copy was independently visible to WordPress as a distinct plugin basename, proving that packaging identity is not cosmetic.

The controlled RED renamed-main candidate then replaced `UPayments.php` with `simplixpay-upayments.php`. WordPress retained the historical active entry:

`simplixpay-upayments/UPayments.php`

while the target basename was inactive and the SimplixPay runtime did not load. Therefore a direct main-file rename does **not** preserve an already-active merchant installation.

The first stable release must consequently retain `UPayments.php`. The eventual target remains frozen for a future explicit multi-release migration that can preserve activation across old/new basenames without merchant intervention. The negative rename behavior is now a permanent release regression proof.

## Text domain — deliberately transitional

Existing runtime translation calls still use `upayments`. Phase 0 therefore keeps header Text Domain `upayments` rather than creating a header/source mismatch or mechanically changing i18n identity.

The frozen eventual target remains `simplixpay-upayments`.

Task 7's installed-package scan still finds **70 explicit runtime translation calls** bound to `upayments`. No separately certified WPML/String Translation migration exists that can safely move those calls and existing string registrations in the same first-stable transition. The first stable release therefore also retains header/runtime text domain `upayments`.

The eventual `simplixpay-upayments` text-domain target remains frozen for a later dedicated i18n/WPML/String Translation migration and must not be performed by global search/replace.

## Persisted/runtime compatibility identity — preserved

Phase 0 does not rename protected historical identities, including:

- gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks/Store API `upayments` identity;
- callback `wc_upayments`;
- existing `_upay_*` metadata;
- `upayments_token_identity_secret_v2` and H12 provenance/scope/generation state;
- subscription cron/table/schema state;
- historical order payment-method values;
- existing `UPayments\...`, `WC_Upayments`, and Blocks class identities unless separately characterized.

These are compatibility contracts, not stale branding to clean mechanically.

## Uninstall data retention — resolved

The inherited destructive uninstall behavior was removed.

`uninstall.php` now preserves merchant/payment data by default and performs no table drop or persisted-option deletion merely because the plugin is removed.

Any future explicit data-erasure feature requires its own confirmation, retention, deletion, migration and rollback contract.

## Coexistence boundary

The Simplix integration must not be advertised as safe for simultaneous activation with another plugin that defines the same historical gateway/class/callback identities.

Preserving those identities is necessary for existing-install compatibility, but it prevents guaranteeing symmetric coexistence with another UPayments plugin that owns the same global/runtime identifiers.

A future onboarding/install-safety tranche should add explicit conflict detection and merchant guidance before broad distribution.

## Test evidence

### Red characterization

Before implementation, the Phase 0 release-identity harness produced:

- **22 PASS**
- **13 FAIL**

The 13 failures were exactly the intended inherited release/updater contract: provider-branded header/version/author, absent Simplix release constants, upstream updater authority and bundled Plugin Update Checker.

### Final exact-head evidence

Final reviewed PR #9 head:

`8b67259bd05453150f837cda4b961f649f50cf02`

Passed:

- Governance: **SUCCESS**
- tracked PHP syntax: **SUCCESS**
- Phase 0 release identity: **35 PASS / 0 FAIL**
- H12 PHP: **1927 PASS / 0 FAIL**
  - semantic runtime: 368
  - helper unit runtime: 841
  - static source: 46
  - harness self-test: 662
  - lint tooling: 10
- Blocks syntax: **SUCCESS**
- H12 Blocks: **144 PASS / 0 FAIL**
  - runtime: 88
  - static: 15
  - harness: 41

The only review thread described the intentionally red pre-implementation state; it was satisfied by the implementation and resolved before merge.

## H12 integrity through Phase 0

The bootstrap intentionally changed only its public release/header/updater prefix. Four H12 implementation anchors outside that prefix remained byte-identical on the final reviewed head:

- `includes/Token/CustomerTokenIdentity.php` — `85430d37e9baf540842f5655b86ccf0eca3e6aea`
- `includes/class-wc-gateway-upayments-blocks.php` — `813d192d69c069eb7ee11df93acc9dbdf03e270a`
- `includes/Subscription/Cron/Scheduler.php` — `5251866d4df2d1326e7c09f0c8ec1d146c0bb325`
- `includes/Subscription/Cron/CycleClaim.php` — `c34d83e2d77cc65024fe663e4c378cecb2b17347`

The full H12 semantic/runtime harness also remained exact at 1927/0 and 144/0.

## Post-merge verification

PR #9 was squash-merged as:

`678f3bdae32b7a0d5922c6ebb7fa7535ede256dd`

with tree:

`80618e737476a92357bd463f6e1495c364157e83`

Post-merge verification established:

- GitHub signature: **VERIFIED**;
- author mapped to `SimplixInnovationsAdmin`;
- public header/version/Simplix constants present on `main`;
- bundled Plugin Update Checker path absent;
- uninstall non-destructive;
- Phase 0 branch auto-deleted;
- open PRs returned to zero.

## Exit verdict

**PHASE 0 — RELEASE IDENTITY AND UPDATER OWNERSHIP: DONE / VERIFIED.**

No tag or GitHub Release is created by this engineering milestone.

The implementation gate immediately following Phase 0 was **Phase 9I — Historical token-identity migration**. Phase 9I, Provider Contract & Payment Lifecycle, the bounded Security Threat-Model Closure, Architecture discovery/A1-A5 and Quality Platform Q1-Q19 are now **DONE / VERIFIED**; the current permitted gate is **Enterprise Compatibility Certification**, under the same protected-branch review/CI discipline and frozen compatibility contracts.
