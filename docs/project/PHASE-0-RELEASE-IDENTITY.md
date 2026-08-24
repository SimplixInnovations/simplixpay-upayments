# Phase 0 — Release Identity and Updater Ownership

**Status:** IN PROGRESS

**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

**Phase 0 development version:** `0.1.0`

## Objective

Take ownership of the public plugin/release identity without rewriting persisted payment identity or allowing upstream code to replace the Simplix-maintained integration.

## Frozen decisions

### Public plugin identity

The active plugin header moves to:

- Plugin Name: **SimplixPay for UPayments**
- Plugin URI: `https://github.com/SimplixInnovations/simplixpay-upayments`
- Description: `Independently engineered UPayments payment integration for WooCommerce by Simplix Innovations.`
- Version: `0.1.0`
- Author: **Simplix Innovations**
- Author URI: `https://simplixi.com`
- License: MIT
- Domain Path: `/languages`

The inherited `Requires at least` / `Requires PHP` values are not expanded during this phase. Broad compatibility claims remain evidence-gated.

### Independent version line

The inherited provider version `3.1.1` is no longer the Simplix product version. SimplixPay uses independent semantic versioning beginning at `0.1.0` while engineering hardening continues. `1.0.0` remains reserved for stable-release gates.

`Simplix\Pay\UPayments\Release\Identity::VERSION` is the canonical code-side version constant and must equal the plugin header.

### Update authority

External self-update checking is **disabled** in Phase 0.

The inherited bundled Plugin Update Checker and its `upaymentskwt/woocommerce` authority are removed. This prevents a Simplix-maintained installation from being silently replaced by upstream code.

A Simplix-controlled external updater is intentionally **not** introduced yet. The current Plugin Update Checker documentation recommends aligning its slug with the plugin directory, while the physical plugin basename/package migration is intentionally not yet performed. Update distribution will be reintroduced only after that migration/package contract is independently tested.

A future WordPress.org build should normally use WordPress.org update infrastructure rather than ship a conflicting external updater.

### Physical plugin basename

Phase 0 deliberately retains the existing main filename:

`UPayments.php`

The frozen eventual target remains:

`simplixpay-upayments.php`

Changing the main filename changes WordPress activation/update identity. It is therefore an **upgrade migration**, not a branding cleanup. No filename removal/rename is permitted until tests prove existing active installations, rollback, replacement packages, and duplicate-plugin failure modes.

### Text domain

Existing runtime translation calls use the legacy domain `upayments`. Phase 0 therefore retains the plugin header text domain `upayments` rather than creating a header/source mismatch or mechanically rewriting translation identities.

The frozen eventual target remains `simplixpay-upayments`. The text-domain transition requires a dedicated WPML/String Translation/i18n compatibility tranche.

### Persisted/runtime compatibility identity

Phase 0 does not rename protected historical identities, including:

- gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks/Store API `upayments` identity;
- callback `wc_upayments`;
- existing `_upay_*` metadata;
- H12 secret/provenance identities;
- subscription cron/table/schema state;
- historical order payment-method values;
- existing `UPayments\...`, `WC_Upayments`, and Blocks class identities unless separately characterized.

### Uninstall data retention

Uninstall is non-destructive by default. The plugin does not drop legacy payment/subscription tables or delete persisted merchant/payment options merely because the plugin is removed.

Any future data-erasure feature requires an explicit merchant action and a separately tested retention/deletion contract.

## Coexistence with another UPayments plugin

The Simplix integration and another plugin that owns the same historical gateway/class/callback identities must not be advertised as safe for simultaneous activation. The legacy identities are intentionally preserved for existing-install compatibility; symmetrical coexistence cannot be guaranteed when the other plugin also defines those identities.

A future install/onboarding tranche must provide explicit conflict detection and user guidance before broad distribution.

## Phase 0 acceptance tests

`tests/harness/phase-0-release-identity-harness.php` must pass and is run inside the required `H12 Regression Harness` CI job.

It verifies:

- canonical public header fields;
- independent `0.1.0` version contract;
- canonical Simplix release constants;
- complete removal of upstream update authority and bundled Plugin Update Checker;
- deliberate retention of `UPayments.php` during this phase;
- deliberate retention of legacy `upayments` text domain until controlled migration;
- presence of protected callback/gateway/token/subscription identities;
- non-destructive uninstall;
- exact canonical slug/repository values.

The existing H12 PHP and Blocks regression baselines must also remain green.

## Exit condition

Phase 0 release-identity/updater ownership is DONE / VERIFIED only after:

1. the exact implementation PR passes Governance, the Phase 0 harness, tracked PHP syntax, H12 PHP and H12 Blocks;
2. review conversations are resolved;
3. the exact reviewed head is squash-merged;
4. merged `main` is independently read back;
5. the feature branch auto-deletes;
6. project status/changelog/handoff record the new verified state.

No tag or GitHub Release is created by this phase.
