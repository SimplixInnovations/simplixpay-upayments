# Repository Audit Ledger

**Repository:** `SimplixInnovations/simplixpay-upayments`

**Original audit base:** `c6e8c32044da254654e7a928e80900d943843e7a`

**Phase 0 verified implementation:** `678f3bdae32b7a0d5922c6ebb7fa7535ede256dd`

**Phase 9I verified operations implementation:** `db1c4ea4dab45bc1ffaf4529e0ccb940153cd999`

**Last reconciled:** 2026-08-25

**Purpose:** maintain the tracked-tree debt classification without authorizing drive-by runtime cleanup.

## Executive classification

Repository foundation/readiness, **Phase 0 — release identity/updater ownership**, and **Phase 9I — historical token-identity migration** are **DONE / VERIFIED**. The repository is still a pre-release engineering codebase, not the intended final SimplixPay package architecture and not a broad compatibility/security/performance certification.

Phase 0 deliberately made only the minimum characterized runtime changes needed to take ownership of public release identity and eliminate upstream update authority. Phase 9I then added an isolated historical-identity classifier/executor/operations layer while preserving protected payment/runtime identities and historical order evidence.

The current owner/gate is **Provider Contract & Payment Lifecycle — DISCOVERY**. No broad runtime cleanup is authorized by that gate; current provider and payment behavior must be characterized before refactoring.

## Top-level inventory after Phase 9I

| Area | Current state | Classification | Next owner/gate |
|---|---|---|---|
| `.github/` | CODEOWNERS, templates, Dependabot, protected Quality Gates | **KEEP / CONTROL PLANE** | Keep synchronized with active gates |
| `AGENTS.md` | Permanent execution/review rules | **KEEP / CONTROL PLANE** | Mandatory before substantive work |
| `README.md`, `CHANGELOG.md` | Simplix-led public/project records | **KEEP CURRENT** | Update at verified milestones |
| `LICENSE`, `NOTICE.md`, `UPSTREAM.md` | MIT + provenance/trademark boundaries | **KEEP** | Re-review at publication gates |
| `UPayments.php` | Active inherited large bootstrap/gateway with Simplix 0.1.0 header/release ownership | **PROTECTED / PROVIDER-LIFECYCLE AUDIT** | Characterize payment-critical behavior before refactor |
| `src/Release/Identity.php` | Canonical Simplix release identity foothold | **KEEP** | Extend new architecture under `Simplix\Pay\UPayments` only as phase-owned |
| `src/Migration/` | Verified Phase 9I preflight/executor/admin/CLI operations | **DONE / VERIFIED / PROTECTED** | Keep as closed migration contract unless separately changed |
| `includes/Token/CustomerTokenIdentity.php` | H12-critical token identity implementation | **DONE / VERIFIED H12 + PHASE 9I DEPENDENCY** | Do not weaken during provider lifecycle work |
| `includes/Subscription/` | Subscription/auto-deduction implementation | **RUNTIME — PROTECTED** | Provider lifecycle/feature characterization where relevant |
| Blocks integration | H12-critical Blocks implementation | **RUNTIME — PROTECTED** | Later phase-scoped work unless provider lifecycle characterization requires observation only |
| `assets/`, `templates/` | Inherited frontend/admin paths and assets | **AUDIT LATER** | UX/performance/architecture phases |
| `tests/harness/` | H12 + Phase 0 + Phase 9I custom regression harnesses | **KEEP AS BASELINE** | Supplement, never replace without evidence |
| `vendor/plugin-update-checker/` | **ABSENT — REMOVED IN PHASE 0** | **RESOLVED** | Do not reintroduce without explicit distribution design |
| `uninstall.php` | Non-destructive by default | **RESOLVED FOR PHASE 0** | Future erasure feature needs explicit contract |
| `index.php` | Minimal directory guard | **KEEP** | Re-evaluate only with packaging migration |

## Phase 0 resolved findings

### Public release identity

Resolved. The active header identifies **SimplixPay for UPayments 0.1.0** by **Simplix Innovations** and loads `Simplix\Pay\UPayments\Release\Identity`.

The inherited provider version `3.1.1` is no longer the Simplix product version.

### Vendor/updater dependency

Resolved for the current engineering phase.

The original audit found that `UPayments.php` loaded a bundled Plugin Update Checker configured against `upaymentskwt/woocommerce`. Phase 0 removed:

- the upstream update authority;
- the Plugin Update Checker include/use/initialization;
- the complete `vendor/plugin-update-checker/` subtree.

External self-updates are intentionally `disabled` until the physical package/basename migration has a separately tested distribution contract.

### Uninstall behavior

Resolved for the current engineering phase.

The original uninstall dropped an inherited subscription table and deleted persisted options. Phase 0 replaced that with non-destructive retention by default. A future destructive erasure path must be explicit, confirmed and independently tested.

### Release-identity characterization

Added in Phase 0. `tests/harness/phase-0-release-identity-harness.php` is part of the required H12 Regression Harness job and remains at **35 PASS / 0 FAIL** while the H12 baseline remains **1927/0 PHP** and **144/0 Blocks**.

## Phase 9I resolved historical-identity gap

Phase 9I is **DONE / VERIFIED** through PRs #11, #12 and #13.

Verified implementation milestones:

- preflight PR #11 → `8cca32819dd165e35efa0fcc5a48bdd551757d8c`;
- executor PR #12 → `708253bd9d0daf217735fbb087b360e8b848136c`;
- operations PR #13 → `db1c4ea4dab45bc1ffaf4529e0ccb940153cd999`, tree `5bec24ad26c66a504cd0dd609f4311f9e70add76`, GitHub signature VERIFIED.

The closed contract provides:

- exact read-only `CLEAN` / `MIGRATABLE` / `BLOCKED` / `INDETERMINATE` preflight classification;
- explicit fail-closed handling for all 13 historical blocker families;
- locked execution only for fresh `MIGRATABLE` evidence;
- only `legacy_compat` / `legacy_verified_capture` historical provenance;
- no fabricated canonical/Create-201 provenance;
- historical order metadata immutability;
- bounded admin/CLI operation with dry-run and confirmed execute;
- separate redacted durable operations-result checkpoints for every processed user;
- credential/mode/list-scoped durable resume without persisting API credentials;
- no provider, checkout, Store API, frontend or cron migration hooks.

Phase 9I completion certifies the migration system/safety contract, not automatic migration of every merchant installation. Site-specific `BLOCKED` or `INDETERMINATE` results remain valid fail-closed outcomes.

## Deliberately unresolved package/identity work

### Physical plugin basename

The active main filename remains `UPayments.php`.

Frozen eventual target: `simplixpay-upayments.php`.

Changing the main file/folder affects WordPress plugin basename, activation/update identity, rollback and duplicate-package behavior. Treat it as an explicit upgrade/package migration, never cosmetic cleanup.

### Text domain

Runtime/header text domain remains `upayments` during the transition.

Frozen eventual target: `simplixpay-upayments`.

The migration requires dedicated i18n/WPML/String Translation evidence and must not be a blind global replacement.

### Coexistence/conflict detection

Because existing-install compatibility requires preserving historical gateway/classes/callback identities, simultaneous activation with another UPayments plugin that owns the same globals cannot be assumed safe. Explicit install/onboarding conflict detection remains future work.

## Protected runtime debt

### Large bootstrap

`UPayments.php` remains a large mixed-responsibility inherited surface. Phase 0 intentionally changed only its public header/updater prefix; later Phase 9I added only its isolated migration bootstrap include. Larger extraction belongs to the later architecture program and requires characterization first.

The current Provider Contract & Payment Lifecycle gate may characterize payment-critical methods in this file, but it does not authorize broad cleanup before the provider/state contracts and executable characterization are frozen.

### Token and subscription modules

Historical H12 anchors remain regression evidence:

- `includes/Token/CustomerTokenIdentity.php` — `85430d37e9baf540842f5655b86ccf0eca3e6aea`
- `includes/class-wc-gateway-upayments-blocks.php` — `813d192d69c069eb7ee11df93acc9dbdf03e270a`
- `includes/Subscription/Cron/Scheduler.php` — `5251866d4df2d1326e7c09f0c8ec1d146c0bb325`
- `includes/Subscription/Cron/CycleClaim.php` — `c34d83e2d77cc65024fe663e4c378cecb2b17347`

These are historical regression anchors, not permanent claims that later reviewed phases can never modify the files.

### Empty/duplicate/legacy assets

Known inherited candidates still include empty files, duplicate provider images, historical screenshots and multiple JS paths. Their existence is recorded debt, not authorization to delete/rename them before dependency/runtime characterization.

### Test platform

The custom harness is valuable regression protection but is not a full modern quality platform. Root PHPUnit/WordPress/WooCommerce integration, broad static analysis, coding standards, browser E2E, accessibility and performance suites remain later planned work.

## Current next owner/gate

**Provider Contract & Payment Lifecycle — DISCOVERY**.

The gate must first compare current exact source with current official UPayments documentation and freeze evidence-backed contracts for charge, webhook/status/browser-return truth, deterministic WooCommerce payment/order transitions, reconciliation/idempotency/retry semantics, refunds and multi-merchant boundaries.

Do not treat inherited runtime behavior as certified merely because it exists. Do not start with a broad refactor.

See `PROJECT-STATUS.md` for the current live milestone and `PHASE-9I-MIGRATION.md` for the closed historical-identity contract.

## Rule

This ledger records debt and gate ownership. It does not authorize mechanical cleanup of payment/runtime code. `PROJECT-STATUS.md`, the naming standard, AGENTS.md and fresh live GitHub/provider evidence control execution truth.
