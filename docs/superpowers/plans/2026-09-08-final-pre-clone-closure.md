# SUPCheckout Final Pre-Clone Closure Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Produce a minimal, compatibility-safe SUPCheckout repository that has no proven dead runtime assets or stray diagnostic output, uses canonical SUPCheckout naming on living first-party QA/control surfaces, preserves all protected UPayments/historical persistence contracts, and is freshly certified before the owner clones it locally.

**Architecture:** Treat cleanup as a compatibility migration boundary, not a global rename. Runtime changes are limited to proven no-op/dead/debug residue and are test-first; protected gateway/provider/persisted identities stay byte-identical. Non-runtime QA/control-plane naming is canonicalized separately, historical records remain historical, and current-authority documentation is reconciled only after the runtime/QA cleanup is merged and exact-main evidence exists.

**Tech Stack:** PHP 7.4+ compatibility, WordPress, WooCommerce, JavaScript, GitHub Actions, PHPUnit, PHPCS/WPCS, PHPStan, WordPress Plugin Check.

**Spec:** `docs/project/NAMING-IDENTITY-STANDARD.md`, `docs/project/ARCHITECTURE-CODE-QUALITY.md`, `docs/project/RELEASE-ENGINEERING.md`, and this final-closeout request.

## Global Constraints

- Formal product remains exactly **SUPCheckout for UPayments**.
- Canonical technical slug/text domain remains exactly `supcheckout`.
- PHP namespace root remains exactly `Simplixi\SUPCheckout`.
- First-stable physical bootstrap remains exactly `supcheckout/UPayments.php`.
- Provider scope remains UPayments-only.
- Runtime certification floor remains PHP 7.4.
- Preserve gateway/payment identity `upayments`, `woocommerce_upayments_settings`, `wc_upayments`, `_upay_*`, `UPayments_order_id`, H12 token/provenance keys, subscription/billing-attempt identities, and historical order payment-method values.
- Preserve explicitly frozen historical control identities such as the existing payment-lifecycle reconciliation/lock/rate-gate metadata/options and Phase 9I migration ledgers/CLI unless a separately tested migration is approved.
- Preserve historically misspelled public compatibility wrapper `getAPIUrlForRetreiveCards()`.
- Do not rename the broadly frozen normalized `whitelabled` internal compatibility shape in this cleanup.
- Historical evidence documents may retain SimplixPay/SUCheckout-era names where they record true past state.
- No tag, GitHub Release, WordPress.org publication, or version promotion is authorized by this plan.
- No production change may merge without RED→GREEN regression evidence and exact-head required gates.

---

### Task 1: Ratchet Proven Runtime Residue RED

**Files:**
- Modify: `tests/harness/sucheckout-frontend-identity-harness.php`

**Interfaces:**
- Consumes: current production `UPayments.php`, `assets/js/upayments-block.js`, and tracked runtime assets.
- Produces: failing static regression assertions that define the cleanup contract before production changes.

- [ ] **Step 1: Add failing assertions for ungated diagnostics**

Require the gateway source to contain no direct `upayments-debug` logger source and require Blocks JS to contain no production `console.log(` call.

- [ ] **Step 2: Add failing assertions for proven-dead/duplicate assets**

Require `assets/js/upay.js` and `assets/images/logo.png` to be absent; require the gateway icon to use `assets/images/upayment.png`.

- [ ] **Step 3: Add failing assertion for the active provider-icon typo**

Reject the active string `UPayemnts`.

- [ ] **Step 4: Open a draft PR and run the exact-head Quality gate**

Expected: the frontend identity harness fails specifically because current production still contains the direct debug logging, Blocks console log, dead `upay.js`, duplicate `logo.png`, and provider alt-text typo. Unrelated failures are blockers.

- [ ] **Step 5: Commit**

Commit message: `test: ratchet final runtime residue cleanup`.

### Task 2: Remove Proven Runtime Residue GREEN

**Files:**
- Modify: `UPayments.php`
- Modify: `assets/js/upayments-block.js`
- Delete: `assets/js/upay.js`
- Delete: `assets/images/logo.png`
- Modify: `tests/harness/architecture-foundation-harness.php` only to advance the exact accepted `UPayments.php` byte ratchet to the newly verified file size.

**Interfaces:**
- Consumes: Task 1 RED contract.
- Produces: same merchant/payment behavior without unconditional diagnostic writes or duplicate/dead files.

- [ ] **Step 1: Remove direct default-gateway diagnostic writes**

Delete the two direct `wc_get_logger()->info(... ['source' => 'upayments-debug'])` calls from the `woocommerce_default_gateway` filter. Preserve only the existing selection behavior: return `upayments` when `make_default_gateway === 'yes'`; otherwise return the incoming default.

- [ ] **Step 2: Consolidate identical provider logo**

Change the gateway icon source from `assets/images/logo.png` to the already-used byte-identical `assets/images/upayment.png`, then delete `assets/images/logo.png`.

- [ ] **Step 3: Remove dead legacy JavaScript**

Delete `assets/js/upay.js`; do not change the live `new-upay.js` or `old-upay.js` conditional checkout paths.

- [ ] **Step 4: Remove production console debugging**

Delete `console.log('UPayments selected');` from `assets/js/upayments-block.js` without changing selection state or event behavior.

- [ ] **Step 5: Fix active presentation typo**

Change only the icon alt text `UPayemnts` to `UPayments`.

- [ ] **Step 6: Advance the exact monolith byte ratchet**

Read the resulting GitHub blob size for `UPayments.php` and set `$acceptedGatewayBytes` in `tests/harness/architecture-foundation-harness.php` to that exact value. Do not weaken the equality assertion.

- [ ] **Step 7: Run exact-head gates**

Expected: frontend identity harness GREEN, architecture ratchet GREEN, Quality/H12 GREEN, Compatibility 16/16 GREEN, Release Artifact and migration cells GREEN, Provider Sandbox GREEN, WordPress.org packaged Plugin Check GREEN, CodeQL no new alert.

- [ ] **Step 8: Commit**

Commit message: `refactor: remove final proven runtime residue`.

### Task 3: Canonicalize Living QA and Control-Plane Identity

**Files:**
- Rename living harnesses:
  - `tests/harness/sucheckout-frontend-identity-harness.php` → `tests/harness/supcheckout-frontend-identity-harness.php`
  - `tests/harness/sucheckout-http-transport-harness.php` → `tests/harness/supcheckout-http-transport-harness.php`
  - `tests/harness/sucheckout-identity-migration-harness.php` → `tests/harness/supcheckout-identity-migration-harness.php`
  - `tests/harness/sucheckout-namespace-migration-harness.php` → `tests/harness/supcheckout-namespace-migration-harness.php`
  - `tests/harness/sucheckout-provenance-db-failure-harness.php` → `tests/harness/supcheckout-provenance-db-failure-harness.php`
  - `tests/harness/sucheckout-residue-harness.php` → `tests/harness/supcheckout-residue-harness.php`
- Modify: `.github/workflows/quality-gates.yml`
- Modify: `.github/ISSUE_TEMPLATE/compatibility-report.yml`
- Modify: `.github/workflows/provider-sandbox-certification.yml`
- Modify: `scripts/install-wp-test-environment.sh`
- Modify: `phpcs.xml.dist`
- Modify: `phpunit.xml.dist`
- Modify: living files under `tests/support/**`, `tests/unit/**`, `tests/integration/**`, and non-historical harness/fixture identifiers that still use first-party `SimplixPay_Test_*`, `simplixpay_test_*`, `SUCheckout_*`, or `sucheckout_*`.
- Do not rewrite immutable historical migration slugs/fixtures such as `simplixpay-upayments` and `sucheckout-upayments`.

**Interfaces:**
- Consumes: canonical naming standard.
- Produces: living QA/control names aligned to SUPCheckout without changing production/persisted identity.

- [ ] **Step 1: Rename active harness filenames and references**

Update Quality workflow paths and living owner/agent instructions. Historical plans/specs may keep the old filenames where they describe past implementation steps.

- [ ] **Step 2: Canonicalize test-only symbol prefixes**

Use `SUPCheckout_Test_*` and `supcheckout_test_*` consistently in current fixtures/support/unit/harness code; update all corresponding assertions/references.

- [ ] **Step 3: Canonicalize certification-only identifiers**

Use `supcheckout_cert_*`, `_supcheckout_certification_*`, `_supcheckout_feature_ops_*`, and `supcheckout-cert-*` for ephemeral test state. Do not change merchant/provider compatibility keys.

- [ ] **Step 4: Canonicalize CI/test labels and local test URL**

Change `SUCheckout`/SimplixPay-only living labels to SUPCheckout, use `SUPCHECKOUT_UPAYMENTS_SANDBOX_TOKEN`, and use `http://supcheckout.test`.

- [ ] **Step 5: Keep historical contracts intact**

Verify the release migration matrix still contains both immutable prior package roots `simplixpay-upayments` and `sucheckout-upayments`, and protected lifecycle/migration persisted identifiers remain unchanged.

- [ ] **Step 6: Run exact-head gates**

Expected: all required checks GREEN with no missing path after harness renames.

- [ ] **Step 7: Commit**

Commit message: `test: align living QA identity with SUPCheckout`.

### Task 4: Review, Certify, and Merge Runtime/QA Closure

**Files:**
- Review the complete PR diff only; no opportunistic production changes.

**Interfaces:**
- Consumes: Tasks 1–3.
- Produces: a single exact certified cleanup head merged by squash.

- [ ] **Step 1: Inspect every changed file and classify every removed/renamed symbol**

Critical/Important findings must be resolved before merge. Protected UPayments/persisted identities must show zero unintended delta.

- [ ] **Step 2: Verify exact-head checks**

Require Governance, Quality/H12, Compatibility Gate, Release Gate, Provider Sandbox, WordPress.org packaged Plugin Check, and CodeQL/security results appropriate to the exact head.

- [ ] **Step 3: Merge only the certified SHA**

Use squash merge with `expected_head_sha`.

- [ ] **Step 4: Verify merged main**

Require fresh post-merge push workflows on the resulting `main` SHA.

### Task 5: Reconcile Current-Authority Documentation

**Files:**
- Modify: `AGENTS.md`
- Modify: `docs/project/PROJECT-STATUS.md`
- Modify: `docs/project/OWNER-HANDOFF.md`
- Modify: `docs/project/NEW-CHAT-HANDOFF.md`
- Modify: `docs/COMPATIBILITY.md` where current-state wording is stale
- Modify: `CHANGELOG.md` with an unreleased closeout entry if needed
- Preserve: `docs/history/**`, historical phase records, historical specs/plans, and immutable prior certification facts.

**Interfaces:**
- Consumes: exact merged runtime/QA SHA and fresh post-merge evidence from Task 4.
- Produces: current operational docs that point to the actual certified runtime-bearing baseline and correct remaining owner actions.

- [ ] **Step 1: Replace stale current-state baseline references**

Record the new runtime-bearing `main` SHA and fresh Quality/Compatibility/Release/Provider/WordPress.org/CodeQL evidence.

- [ ] **Step 2: Reconcile topology truth**

State live branch/PR/issue/release state exactly. Do not claim `main only` while a stale remote branch exists.

- [ ] **Step 3: Preserve historical evidence**

Do not rewrite historical SimplixPay/SUCheckout milestones or old migration package roots.

- [ ] **Step 4: Open documentation closeout PR and certify exact head**

All triggered required checks must pass.

- [ ] **Step 5: Squash merge exact certified documentation head**

Then reverify final `main`.

### Task 6: Final Remote Topology and Owner Handoff

**Files:** none unless a final factual documentation correction is required.

**Interfaces:**
- Consumes: final certified `main`.
- Produces: pre-clone owner instructions only for actions unavailable to the connected GitHub capability.

- [ ] **Step 1: Verify live repository**

Confirm default branch, squash-only setting, Main Rule required contexts, zero open PRs/issues, no unauthorized release/tag publication, and exact final main SHA.

- [ ] **Step 2: Verify remote branches**

If the historical unprotected `hardening/deep-release-audit-cleanup` branch still exists and no delete-ref capability is available, explicitly hand only that deletion to the owner. Do not claim it was removed.

- [ ] **Step 3: Hand off a fresh-clone local acceptance**

Owner clones `https://github.com/SimplixInnovations/supcheckout.git` into a brand-new directory, verifies exact SHA/clean status, runs Composer quality and permanent harnesses, builds/verifies the deterministic ZIP, then performs disposable WordPress/WooCommerce merchant-facing smoke. Any failure blocks publication.
