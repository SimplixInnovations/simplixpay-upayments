# Repository Audit Ledger

**Repository:** `SimplixInnovations/simplixpay-upayments`

**Audit base:** `c6e8c32044da254654e7a928e80900d943843e7a`

**Audit date:** 2026-08-24

**Purpose:** classify the entire tracked repository before runtime-changing Phase 0 work.

**Rule:** this audit records debt; it does not authorize drive-by runtime cleanup.

## Executive classification

The repository is usable as the verified H12 engineering baseline, but it is **not yet the intended final SimplixPay package architecture**. Repository/governance presentation is being corrected in the pre-Phase-0 readiness change. Runtime/package identity and updater ownership belong to Phase 0. Larger architecture, testing and compatibility cleanup belong to later controlled phases.

No item below is a compatibility certification unless explicitly marked as independently verified elsewhere.

## Top-level inventory

| Area | Current state | Classification | Next owner/gate |
|---|---|---|---|
| `.github/` | CODEOWNERS, issue/PR templates, Dependabot, Quality Gates, Simplix assets | **KEEP / HARDEN NOW** | Readiness: immutable Action pins, grouped Dependabot, repo settings |
| `.editorconfig`, `.gitattributes`, `.gitignore` | Basic repository hygiene present | **KEEP** | Extend only when tooling/packaging requires it |
| `AGENTS.md` | Permanent execution/review rules | **KEEP / CONTROL PLANE** | Keep synchronized with active project gates |
| `README.md` | Public product page | **REVISE NOW** | Readiness public presentation |
| `CHANGELOG.md` | 113 KB pre-product engineering transcript on audit base | **SPLIT NOW** | Root product changelog + byte-preserved historical archive |
| `LICENSE` | Canonical MIT, GitHub detects SPDX MIT | **KEEP** | No change unless legal review requires it |
| `NOTICE.md`, `UPSTREAM.md` | Provenance/trademark/independence boundaries | **KEEP** | Review at release/publication gates |
| `SECURITY.md`, `SUPPORT.md`, `CONTRIBUTING.md`, `MAINTAINERS.md` | Simplix-oriented policies | **KEEP / CONSISTENCY PASS** | Readiness |
| `UPayments.php` | 256,735-byte inherited bootstrap/gateway plus current hardening | **PHASE 0 + LATER ARCHITECTURE** | Do not refactor during repository readiness |
| `includes/` | Token, subscription and Blocks implementation | **RUNTIME — PROTECTED** | Phase-scoped work only |
| `assets/` | CSS/JS/images/screenshots from inherited integration | **RUNTIME/PUBLIC ASSET AUDIT LATER** | Phase 0 identity + later UX/performance certification |
| `templates/` | Old/new design and order templates | **RUNTIME** | Later characterization/UX/accessibility work |
| `tests/harness/` | Large custom H12 regression harness | **KEEP AS BASELINE** | Later supplement with standard test platform |
| `vendor/plugin-update-checker/` | Bundled Plugin Update Checker only | **PHASE 0 BLOCKER/DECISION** | Updater ownership and distribution strategy |
| `index.php` | Minimal plugin directory guard | **KEEP** | Re-evaluate only with packaging structure |
| `uninstall.php` | Deletes inherited subscription table/options | **PHASE 0 INSTALL/UNINSTALL AUDIT** | Must be characterized before first Simplix release |

## Detailed findings

### Bootstrap / runtime structure

`UPayments.php` is **256,735 bytes** on the audit base. It remains the active bootstrap/gateway surface and still contains inherited public plugin metadata and the upstream-controlled update path. Its size and mixed responsibilities are architecture debt, but broad extraction before characterization would increase payment risk.

**Decision:** Phase 0 may make only the minimum tested changes required for release identity/updater ownership. Larger extraction into `Simplix\Pay\UPayments` modules belongs to the later architecture program.

### Token and subscription modules

- `includes/Token/CustomerTokenIdentity.php`: **96,821 bytes**; H12 critical.
- `includes/Subscription/Cron/Scheduler.php`: **47,965 bytes**; payment/auto-deduction critical.
- `includes/Subscription/Cron/CycleClaim.php`: **15,954 bytes**; H12 durable attempt journal.
- Blocks integration: **9,587 bytes**.

**Decision:** preserve exact H12 blobs throughout repository readiness. Future cleanup requires characterization and exact regression evidence.

### Empty inherited files

Two tracked files are empty on the audit base:

- `includes/admin-footer.php`
- `assets/css/old-design.css`

They may be obsolete, but deletion can still affect includes/enqueues, packaging expectations or upgrade diffs.

**Decision:** record as dead-code/package candidates; do not delete during pre-Phase-0 readiness. Confirm references first in the architecture/code-quality phase or a narrowly scoped earlier package test if Phase 0 proves they affect installation identity.

### Frontend/admin assets

The repository contains multiple inherited JS paths, including `new-upay.js`, `old-upay.js`, `upay.js`, `upayments-block.js`, `upayments-blocks-integration.js`, `upayments-thankyou.js`, and subscription/admin/multi-merchant scripts.

**Risk:** duplicate/legacy execution paths, asset scope, dependency ordering, bundle overlap and naming debt are not yet fully certified.

**Decision:** no mechanical rename/delete before dependency and runtime characterization. Asset handles/names can migrate only under tests.

### Images and screenshots

- `assets/images/logo.png` and `assets/images/upayment.png` are the same tracked blob.
- Existing screenshots are named/presented around inherited UPayments plugin/admin/payment screens.

**Decision:** the duplicate image is a later package-cleanup candidate. Existing screenshots are historical/inherited visual evidence, not current SimplixPay product marketing. Replace or relocate them only after SimplixPay admin/runtime identity exists so new screenshots are truthful.

### Vendor/updater dependency

The only tracked vendor package is `vendor/plugin-update-checker/`, and the bootstrap currently points its updater at the upstream UPayments GitHub repository.

**Decision:** this is the highest-priority Phase 0 runtime blocker. Phase 0 must decide the Simplix-owned update strategy, GitHub-vs-WordPress.org build behavior, dependency/vendor treatment and rollback/update tests. Do not remove the library during repository readiness because the current bootstrap requires it.

### Uninstall behavior

Current `uninstall.php` drops `{$wpdb->prefix}upay_subscriptions` and deletes several inherited subscription/payment-method rate-gate options. It does not represent a documented SimplixPay data-retention/uninstall policy and predates the current H12/migration architecture.

**Decision:** Phase 0 install/update/upgrade/uninstall characterization must include this file before any public SimplixPay release. Do not expand destructive cleanup without an explicit data-retention policy and migration tests.

### Tests

The tracked test surface is currently `tests/harness/` only. The H12 PHP harness is large (~453 KB) and the Blocks harness is ~87 KB. This is valuable historical regression protection, but there is no root PHPUnit/Composer test platform, WordPress/WooCommerce integration suite, browser E2E suite or broad static-analysis configuration yet.

**Decision:** keep H12 harness green as the immediate safety baseline. Do not market it as broad certification. Add the standard quality platform in the planned testing/architecture phases, with targeted install/update tests introduced earlier where Phase 0 needs them.

### Missing intended final package structure

The audit base intentionally does **not** yet contain the future final structure such as root `composer.json` with `Simplix\Pay\UPayments` PSR-4, `src/`, a root SimplixPay `languages/` catalog, WordPress.org `readme.txt`, or final `simplixpay-upayments.php` bootstrap filename.

**Decision:** not a readiness defect. These belong to explicit later gates. Introducing them prematurely would mix repository cleanup with runtime/installation identity changes.

## Files intentionally not changed by repository readiness

The readiness change must not modify `UPayments.php`, `includes/`, runtime `assets/`, `templates/`, `tests/harness/`, `vendor/`, `uninstall.php`, or `index.php`.

**Repository ready** means the repository/history/presentation/governance/settings/local-clone state is trustworthy enough to begin Phase 0. It does **not** mean the plugin itself is production-ready.
