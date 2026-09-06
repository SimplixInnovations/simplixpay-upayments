# SUCheckout for UPayments Identity Migration Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [x]`) syntax for tracking.

**Goal:** Replace the retired SimplixPay first-party identity with SUCheckout for UPayments / `sucheckout-upayments`, preserve only evidence-backed legacy/provider contracts, and produce a WordPress.org-ready deterministic package with zero blocking Plugin Check errors.

**Architecture:** Treat the work as a compatibility-preserving identity migration rather than a global search/replace. First-party branding/package/runtime surfaces move to SUCheckout, while persisted/provider identifiers are either preserved or dual-read/dual-route behind regression tests. Each tranche is test-first and must leave the repository in a coherent, independently reviewable state.

**Tech Stack:** PHP 7.4+ runtime, WordPress 6.9+, WooCommerce 10.8+, PHPUnit, PHPStan, PHPCS/WPCS, GitHub Actions, official WordPress Plugin Check, shell release tooling.

**Spec:** `docs/superpowers/specs/2026-09-06-sucheckout-upayments-identity-migration-design.md`

**Execution status:** Tasks 1–9 are implemented. Task 10 remains the active closeout: exact-head certification, whole-PR review, merge, and post-merge verification.

## Global Constraints

- Human-facing formal name is exactly **SUCheckout for UPayments**.
- Canonical technical identity is exactly **`sucheckout-upayments`**.
- The token `for` MUST NOT appear inside canonical technical identifiers.
- Target repository is `SimplixInnovations/sucheckout-upayments`; repository rename is an owner/admin action after code certification.
- New PHP namespace root is `Simplixi\SUCheckout\UPayments`.
- Text domain is the literal `sucheckout-upayments`.
- Do not rename provider API schema or endpoints.
- Do not destroy historical WooCommerce settings/orders/tokens/subscription data.
- No blanket Plugin Check ignore codes.
- No public tag, GitHub Release, WordPress.org submission, or version promotion during this plan.
- TDD is mandatory for production behavior/refactoring.

---

### Task 1: Add the permanent SUCheckout identity contract harness

**Files:**
- Create: `tests/harness/sucheckout-identity-migration-harness.php`
- Modify: `.github/workflows/quality-gates.yml`
- Modify: `tests/harness/quality-platform-release-identity-harness.php`
- Modify: `docs/project/NAMING-IDENTITY-STANDARD.md`
- Modify: `src/Release/Identity.php`

**Interfaces:**
- Consumes: approved identity spec.
- Produces: canonical constants `PRODUCT_NAME`, `SHORT_NAME`, `SLUG`, `REPOSITORY`, `TEXT_DOMAIN`, `NAMESPACE_ROOT`, plus an explicit legacy compatibility allowlist.

- [x] **Step 1: Write the failing identity harness.**

The harness must assert:
```text
PRODUCT_NAME = SUCheckout for UPayments
SHORT_NAME = SUCheckout
SLUG = sucheckout-upayments
REPOSITORY = SimplixInnovations/sucheckout-upayments
TEXT_DOMAIN = sucheckout-upayments
NAMESPACE_ROOT = Simplixi\SUCheckout\UPayments
```

It must reject `simplixpay`, `sucheckout-for-upayments`, and `Simplix\Pay\UPayments` on active first-party identity surfaces while allowing explicitly listed legacy/provider contracts.

- [x] **Step 2: Run the new harness and verify RED.**

Run:
```bash
php tests/harness/sucheckout-identity-migration-harness.php
```

Expected: FAIL because current `src/Release/Identity.php` still exposes SimplixPay identity.

- [x] **Step 3: Implement the minimal canonical identity object and naming standard.**

Set the new canonical values in `src/Release/Identity.php` and replace the old naming standard with the approved SUCheckout standard. Keep legacy identifiers as explicitly named compatibility constants rather than branding constants.

- [x] **Step 4: Run identity/release harnesses and verify GREEN.**

Run:
```bash
php tests/harness/sucheckout-identity-migration-harness.php
php tests/harness/quality-platform-release-identity-harness.php
```

Expected: PASS.

- [x] **Step 5: Make the new harness mandatory in H12 and commit.**

Commit message:
```text
refactor: establish SUCheckout canonical identity
```

### Task 2: Refactor first-party PHP namespace/package identity

**Files:**
- Modify: `composer.json`
- Modify: `composer.lock`
- Modify: all `src/**/*.php` first-party namespace declarations/imports
- Modify: affected `tests/unit/**/*.php`, `tests/phpstan/**/*.php`, harnesses, and bootstrap files
- Preserve/bridge: legacy `UPayments\...` classes in `includes/` only where compatibility evidence requires them.

**Interfaces:**
- Consumes: `Simplixi\SUCheckout\UPayments` root from Task 1.
- Produces: PSR-4 autoload `Simplixi\\SUCheckout\\UPayments\\ => src/`.

- [x] **Step 1: Extend the identity harness/static-analysis harness to fail on active `Simplix\Pay\UPayments` declarations/imports.**
- [x] **Step 2: Run the focused harness and verify RED.**
- [x] **Step 3: Change Composer package to `simplix-innovations/sucheckout-upayments`, update PSR-4 roots, then refactor `src/` and matching tests/imports.**
- [x] **Step 4: Run PHPUnit, PHPStan, and architecture harnesses; fix only namespace/package fallout until GREEN.**
- [x] **Step 5: Commit as `refactor: migrate first-party PHP namespace to SUCheckout`.**

### Task 3: Migrate plugin metadata, text domain, and translation calls

**Files:**
- Modify: `UPayments.php` initially; canonical bootstrap decision is Task 5.
- Modify: all plugin-owned PHP translation call sites.
- Modify: `tests/harness/quality-platform-static-analysis-harness.php`
- Create/update: `readme.txt`
- Modify: `README.md`, `CHANGELOG.md`.

**Interfaces:**
- Produces literal translation domain `sucheckout-upayments`.

- [x] **Step 1: Add a failing static harness that rejects plugin-owned translation calls using `upayments`, `woocommerce`, a variable domain, or an empty translatable string.**
- [x] **Step 2: Verify RED against current source.**
- [x] **Step 3: Change plugin header to `Plugin Name: SUCheckout for UPayments` and `Text Domain: sucheckout-upayments`; replace translation domains with the literal canonical domain and rewrite empty/dynamic translatable strings as literal format strings.**
- [x] **Step 4: Remove `Domain Path` unless a real packaged languages artifact exists; do not rely on an empty Git directory.**
- [x] **Step 5: Run focused harness + PHPCS + PHPUnit and verify GREEN.**
- [x] **Step 6: Commit as `refactor: migrate SUCheckout text domain and metadata`.**

### Task 4: Refactor CSS, JS, handles, HTML/data identifiers, and assets

**Files:**
- Modify: `assets/css/*.css`
- Modify: `assets/js/*.js`
- Modify: PHP registration/enqueue/template files that emit handles/classes/IDs/data attributes.
- Rename: first-party asset filenames containing obsolete plugin branding.
- Preserve: provider-facing payment method IDs/keys where compatibility requires `upayments`.

**Interfaces:**
- Produces CSS root `.sucheckout-upayments`, handles `sucheckout-upayments-*`, JS namespace/config `suCheckoutUpayments*`.

- [x] **Step 1: Add a failing frontend identity harness that enumerates first-party handles/selectors/globals and rejects SimplixPay plus unapproved first-party `upayments-*` branding names.**
- [x] **Step 2: Verify RED.**
- [x] **Step 3: Refactor first-party CSS/JS/DOM identifiers; do not rename provider request keys or the legacy Woo gateway ID merely because they contain `upayments`.**
- [x] **Step 4: Rename the invalid screenshot filename containing spaces and update every reference.**
- [x] **Step 5: Run Blocks harness, PHP harness, PHPCS and frontend identity harness to GREEN.**
- [x] **Step 6: Commit as `refactor: align frontend assets with SUCheckout identity`.**

### Task 5: Prove the canonical bootstrap migration

**Files:**
- Create candidate: `sucheckout-upayments.php`
- Modify/retain candidate shim: `UPayments.php`
- Modify: `tests/integration/PluginActivationTest.php`
- Modify: `tests/integration/UpgradeCompatibilityTest.php`
- Modify: release identity/build harnesses.

**Interfaces:**
- Desired canonical basename: `sucheckout-upayments/sucheckout-upayments.php`.
- Legacy basename: `sucheckout-upayments/UPayments.php`.

- [x] **Step 1: Write a failing real-WordPress upgrade test for an installation whose active plugin entry is the legacy `UPayments.php` basename.**
- [x] **Step 2: Verify the direct rename fails for the already-proven reason or equivalent active-basename breakage.**
- [x] **Step 3: Implement the smallest compatibility bootstrap/shim that preserves old active installs without creating two visible plugin entries.**
- [x] **Step 4: Run activation, upgrade, rollback, duplicate-package and compatibility matrix tests.**
- [x] **Step 5: If the shim cannot be proven safe, revert the canonical-file switch and retain `UPayments.php` as the documented first-stable compatibility exception. Do not force the target.**
- [x] **Step 6: Commit the proven outcome only.**

### Task 6: Preserve legacy merchant/payment data behind explicit compatibility tests

**Files:**
- Modify: gateway/settings/bootstrap code as needed.
- Modify: `tests/integration/UpgradeCompatibilityTest.php`
- Modify: `tests/integration/SavedCardRuntimeTest.php`
- Modify: `tests/integration/SubscriptionRuntimeTest.php`
- Modify: migration/token/subscription tests.

**Interfaces:**
- Legacy reads: `woocommerce_upayments_settings`, historical order method `upayments`, `_upay_*`, callback `wc_upayments`, token provenance keys, legacy cron/table identities.
- New first-party identifiers: `sucheckout_upayments_*` where creating genuinely new state.

- [x] **Step 1: Add failing regression cases proving old settings/orders/tokens/subscription schedules survive the SUCheckout code identity.**
- [x] **Step 2: Verify RED where the refactor would otherwise disconnect state.**
- [x] **Step 3: Add dual-read/alias/migration behavior only for demonstrated failures; never delete the old persisted value during the first migration.**
- [x] **Step 4: Run saved-card, subscription, multi-merchant, payment lifecycle, upgrade and rollback tests to GREEN.**
- [x] **Step 5: Commit as `refactor: preserve legacy UPayments merchant data`.**

### Task 7: Rebuild deterministic package and WordPress.org metadata

**Files:**
- Modify: `scripts/build-release.sh`
- Modify: `scripts/verify-release.sh`
- Modify: `tests/harness/release-artifact-harness.php`
- Modify: `.github/workflows/release-artifact.yml`
- Create/update: `readme.txt`
- Create: `.github/workflows/wordpress-org-submission-check.yml`
- Create: `tests/harness/wordpress-org-submission-harness.php`

**Interfaces:**
- ZIP: `sucheckout-upayments-0.1.0.zip`.
- Package root: `sucheckout-upayments/`.
- Plugin Check slug: `sucheckout-upayments`.

- [x] **Step 1: Add RED release/submission assertions for the new package root/name/readme/workflow.**
- [x] **Step 2: Verify RED.**
- [x] **Step 3: Update deterministic packaging and verification; include canonical `readme.txt`.**
- [x] **Step 4: Add the official Plugin Check action pinned to the already-audited v1.1.9 commit, category `plugin_repo`, no ignore codes.**
- [x] **Step 5: Build/verify the ZIP and run submission harness to GREEN.**
- [x] **Step 6: Commit as `build: package SUCheckout for WordPress.org checks`.**

### Task 8: Clear official Plugin Check blocking errors systematically

**Files:** Exact files are driven by the official packaged-artifact report.

**Interfaces:**
- Consumes: Plugin Check report against the actual deterministic ZIP.
- Produces: zero blocking `plugin_repo` errors.

- [x] **Step 1: Run official Plugin Check and record errors grouped by code/file.**
- [x] **Step 2: Fix low-risk mechanical errors first with failing regression assertions: direct-access guards, `wp_parse_url()`, invalid filenames, empty/dynamic i18n.**
- [x] **Step 3: Fix `$wpdb` preparation issue(s) from exact query context with a reproducing test.**
- [x] **Step 4: Fix output escaping context-by-context using the correct escaping function for HTML/attribute/URL/JS contexts; never blanket-escape data before business logic.**
- [x] **Step 5: Replace direct cURL transport with WordPress HTTP API behind transport tests that prove timeout, method, headers/body, status, error and provider-result semantics.**
- [x] **Step 6: Re-run Plugin Check after each error family until blocking errors are zero.**
- [x] **Step 7: Classify remaining warnings individually and fix genuine defects; do not hide warnings with blanket suppression.**
- [x] **Step 8: Commit each independently reviewable error family.**

### Task 9: Repository-wide residue and documentation reconciliation

**Files:**
- Modify: `docs/project/*.md`, `docs/history/*.md` only where current/canonical statements are stale.
- Modify: `README.md`, `CHANGELOG.md`, `SECURITY.md`, `SUPPORT.md`, `CONTRIBUTING.md`, `AGENTS.md`, workflow names/comments, source comments.
- Historical evidence may retain old names when clearly labeled historical.

**Interfaces:**
- Produces a machine-readable allowlist of every remaining old identity occurrence and why it is allowed.

- [x] **Step 1: Add a repository residue harness that scans tracked text files for `SimplixPay`, `simplixpay-upayments`, `Simplix\Pay\UPayments`, and `sucheckout-for-upayments`.**
- [x] **Step 2: Verify RED.**
- [x] **Step 3: Reconcile current docs/workflows/comments and leave historical references only under explicit historical/legacy allowlist paths or lines.**
- [x] **Step 4: Verify the residue harness GREEN with zero unexplained occurrences.**
- [x] **Step 5: Commit as `docs: reconcile repository with SUCheckout identity`.**

### Task 10: Exact-head certification, review, merge, and post-merge verification

**Files:** no new feature scope; evidence/reconciliation only.

**Interfaces:**
- Produces one verified migration PR and a canonical main SHA.

- [ ] **Step 1: Run Quality/H12, PHPUnit, PHPStan, PHPCS, deterministic release build/verify, full Compatibility certification, Provider Sandbox when transport/provider code changed, CodeQL/security, and WordPress.org Submission Check on the exact PR head.**
- [ ] **Step 2: Review every failing job by root cause; do not merge around failures.**
- [ ] **Step 3: Request/review Codex whole-plugin review, address valid findings, resolve stale findings with exact-head evidence.**
- [ ] **Step 4: Merge with expected-head guard only when every required check is successful.**
- [ ] **Step 5: Re-run the same required workflows on the exact merged `main` SHA and verify all attached checks succeed.**
- [ ] **Step 6: Audit open issues, PRs, unresolved review threads, stale branches, tags/releases, and current docs.**
- [ ] **Step 7: Leave repository rename to `SimplixInnovations/sucheckout-upayments` as the final owner/admin action; after rename, update/verify canonical links before publication.**
- [ ] **Step 8: Do not create a public release or WordPress.org submission without a separate explicit owner release decision.**
