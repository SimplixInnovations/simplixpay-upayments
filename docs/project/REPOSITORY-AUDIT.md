# Repository Audit Ledger

**Repository:** `SimplixInnovations/simplixpay-upayments`

**Original audit base:** `c6e8c32044da254654e7a928e80900d943843e7a`

**Phase 0 verified implementation:** `678f3bdae32b7a0d5922c6ebb7fa7535ede256dd`

**Last reconciled:** 2026-08-25

**Purpose:** maintain the tracked-tree debt classification without authorizing drive-by runtime cleanup.

## Executive classification

Repository foundation/readiness and **Phase 0 — release identity/updater ownership are DONE / VERIFIED**. The repository is still a pre-release engineering codebase, not the intended final SimplixPay package architecture and not a broad compatibility/security/performance certification.

Phase 0 deliberately made only the minimum characterized runtime changes needed to take ownership of public release identity and eliminate upstream update authority. Historical payment/runtime identifiers remain protected.

## Top-level inventory after Phase 0

| Area | Current state | Classification | Next owner/gate |
|---|---|---|---|
| `.github/` | CODEOWNERS, templates, Dependabot, protected Quality Gates | **KEEP / CONTROL PLANE** | Keep synchronized with active gates |
| `AGENTS.md` | Permanent execution/review rules | **KEEP / CONTROL PLANE** | Mandatory before substantive work |
| `README.md`, `CHANGELOG.md` | Simplix-led public/project records | **KEEP CURRENT** | Update at verified milestones |
| `LICENSE`, `NOTICE.md`, `UPSTREAM.md` | MIT + provenance/trademark boundaries | **KEEP** | Re-review at publication gates |
| `UPayments.php` | Active inherited large bootstrap/gateway with Simplix 0.1.0 header/release ownership | **PROTECTED / LATER ARCHITECTURE** | No broad refactor before characterization |
| `src/Release/Identity.php` | Canonical Simplix release identity foothold | **KEEP** | Extend new architecture under `Simplix\Pay\UPayments` only as phase-owned |
| `includes/Token/CustomerTokenIdentity.php` | H12-critical token identity implementation | **PHASE 9I / PROTECTED** | Historical migration work only under characterization |
| `includes/Subscription/` | Subscription/auto-deduction implementation | **RUNTIME — PROTECTED** | Later phase-scoped work |
| Blocks integration | H12-critical Blocks implementation | **RUNTIME — PROTECTED** | Later phase-scoped work |
| `assets/`, `templates/` | Inherited frontend/admin paths and assets | **AUDIT LATER** | UX/performance/architecture phases |
| `tests/harness/` | H12 + Phase 0 custom regression harnesses | **KEEP AS BASELINE** | Supplement, never replace without evidence |
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

Added in Phase 0. `tests/harness/phase-0-release-identity-harness.php` is now part of the required H12 Regression Harness job and closed at **35 PASS / 0 FAIL** while H12 remained **1927/0 PHP** and **144/0 Blocks**.

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

`UPayments.php` remains a large mixed-responsibility inherited surface. Phase 0 intentionally changed only its public header/updater prefix. Larger extraction belongs to the later architecture program and requires characterization first.

### Token and subscription modules

The Phase 0 final implementation preserved these H12 anchors byte-for-byte outside the intentionally changed bootstrap:

- `includes/Token/CustomerTokenIdentity.php` — `85430d37e9baf540842f5655b86ccf0eca3e6aea`
- `includes/class-wc-gateway-upayments-blocks.php` — `813d192d69c069eb7ee11df93acc9dbdf03e270a`
- `includes/Subscription/Cron/Scheduler.php` — `5251866d4df2d1326e7c09f0c8ec1d146c0bb325`
- `includes/Subscription/Cron/CycleClaim.php` — `c34d83e2d77cc65024fe663e4c378cecb2b17347`

The current gate is Phase 9I historical token-identity migration. Do not mix unrelated cleanup into that work.

### Empty/duplicate/legacy assets

Known inherited candidates still include empty files, duplicate provider images, historical screenshots and multiple JS paths. Their existence is recorded debt, not authorization to delete/rename them before dependency/runtime characterization.

### Test platform

The custom harness is valuable regression protection but is not a full modern quality platform. Root PHPUnit/WordPress/WooCommerce integration, broad static analysis, coding standards, browser E2E, accessibility and performance suites remain later planned work.

## Current next owner/gate

**Phase 9I — Historical token-identity migration**.

Its preflight must be read-only, deterministic and perform zero provider calls/writes. Historical evidence must resolve to `CLEAN`, `MIGRATABLE`, `BLOCKED` or `INDETERMINATE`; execution may operate only on explicit `MIGRATABLE` cases and must never fabricate canonical/Create-201 provenance.

See `PROJECT-STATUS.md` for the 13 blocker classes and current live milestone.

## Rule

This ledger records debt and gate ownership. It does not authorize mechanical cleanup of payment/runtime code. `PROJECT-STATUS.md`, the naming standard, AGENTS.md and fresh live GitHub evidence control execution truth.
