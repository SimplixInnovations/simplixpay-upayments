# SimplixPay Enterprise Completion Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Move SimplixPay for UPayments from completed Quality Platform Q1-Q19 engineering hardening to an evidence-backed enterprise release candidate with reproducible compatibility, packaging, release, and operational certification.

**Architecture:** Preserve all closed payment, H12, migration, security, and compatibility identities. Add certification and release capabilities around the existing implementation rather than redesigning payment-critical code. Every behavior change follows RED -> GREEN; every compatibility claim is derived from an explicit matrix run rather than source inspection alone.

**Tech Stack:** WordPress, WooCommerce, PHP, PHPUnit, PHPStan, PHPCS/WPCS, Node.js for Blocks harnesses, GitHub Actions, WP-CLI, shell-based deterministic release tooling.

**Spec:** `docs/ENGINEERING-ROADMAP.md`, `docs/project/MASTER-ENGINEERING-PLAYBOOK.md`, `docs/COMPATIBILITY.md`, and the closed Q1-Q19 evidence in `docs/project/QUALITY-PLATFORM.md`.

## Global Constraints

- Formal product remains **SimplixPay for UPayments**; canonical slug remains `simplixpay-upayments`.
- Protected runtime/persisted identities remain unchanged unless an explicit migration contract is independently justified and tested.
- `UPayments.php` and text domain `upayments` remain transitional until a dedicated distribution/i18n migration proves safe upgrade behavior.
- Q19 is the final numbered Quality Platform gate; do not create Q20 for continuity.
- External AI/Codex review is not an intermediate gate and is reserved for one final whole-plugin review after enterprise completion.
- A green custom harness is not broad WordPress/WooCommerce/PHP/HPOS/Blocks/WPML certification.
- Do not add compatibility headers, badges, or Woo feature declarations until their exact environments pass reproducible tests.
- Composer remains development-only unless a separately reviewed packaging migration explicitly changes runtime dependency policy.
- Automatic refunds remain unsupported until a durable refund-intent/idempotency/reconciliation contract is implemented and certified.
- Provider webhook payloads remain non-authoritative unless UPayments publishes a stable signature contract that is separately implemented and verified.
- All payment/provider mutations fail closed on ambiguity and never blindly retry a non-idempotent financial request.
- All production behavior changes use strict test-first RED -> GREEN evidence.

---

### Task 1: Close the numbered Quality Platform

**Files:**
- Modify: `AGENTS.md`
- Modify: `README.md`
- Modify: `CHANGELOG.md`
- Modify: `docs/ENGINEERING-ROADMAP.md`
- Modify: `docs/project/PROJECT-STATUS.md`
- Modify: `docs/project/QUALITY-PLATFORM.md`
- Modify: `docs/project/NEW-CHAT-HANDOFF.md`
- Modify: `docs/project/MASTER-ENGINEERING-PLAYBOOK.md`
- Modify: `docs/project/PHASE-0-RELEASE-IDENTITY.md`
- Modify: `docs/project/PHASE-9I-MIGRATION.md`
- Modify: `docs/project/PROVIDER-PAYMENT-LIFECYCLE.md`
- Modify: `docs/project/SECURITY-THREAT-MODEL.md`
- Modify: `docs/project/ARCHITECTURE-CODE-QUALITY.md`
- Modify: `docs/project/REPOSITORY-AUDIT.md`
- Modify: `docs/project/REPOSITORY-READINESS.md`
- Modify: `.github/workflows/quality-gates.yml`

**Interfaces:**
- Consumes: Q19 final head `1717f0c25da7140a7799c7db3a7f016abecec7e9`, squash merge `29ba16a1eabc00e25c3652ae838be9b9539b3a10`, Quality Gates #463/#464, exact-head and post-merge CodeQL success.
- Produces: one canonical current program name, **Enterprise Compatibility Certification**, and a closed Quality Platform record marked **DONE / VERIFIED (Q1-Q19)**.

- [ ] **Step 1: Replace only living Q19-current markers with Q19 closure truth**
  - Record Q19 as DONE / VERIFIED.
  - Preserve historical milestone narratives instead of rewriting what was true at earlier dates.
  - State explicitly that no Q20 is justified by current evidence.
  - Make Enterprise Compatibility Certification the current named program.

- [ ] **Step 2: Update governance assertions to enforce the new living state**
  - Replace Q19-current exact-string assertions in `.github/workflows/quality-gates.yml`.
  - Add explicit checks for Q19 DONE / VERIFIED and the named certification gate.
  - Preserve all required Q1-Q19 harness execution.

- [ ] **Step 3: Verify the closeout candidate**
  - Run the full Quality Gates workflow.
  - Require Governance, Quality Platform, PHP syntax lanes, H12 aggregate, and CodeQL to succeed on the exact PR head.
  - Reject any documentation update that weakens historical safety claims or changes runtime source.

- [ ] **Step 4: Squash-merge and verify canonical main**
  - Verify exact head before merge.
  - Verify the squash result and post-merge full Quality Gates/CodeQL.
  - Confirm zero unresolved PR threads and clean branch state.

### Task 2: Establish executable enterprise compatibility certification

**Files:**
- Create: `tests/integration/bootstrap.php`
- Create: `tests/integration/PluginActivationTest.php`
- Create: `tests/integration/WooCompatibilityTest.php`
- Create: `tests/integration/CheckoutRegistrationTest.php`
- Create: `tests/integration/HposCompatibilityTest.php`
- Create: `tests/integration/ReleaseMetadataTest.php`
- Create: `scripts/install-wp-test-environment.sh`
- Create: `.github/workflows/compatibility-certification.yml`
- Create: `docs/project/ENTERPRISE-CERTIFICATION.md`
- Modify: `phpunit.xml.dist` only if a separate integration suite is required.

**Interfaces:**
- Consumes: current WordPress/WooCommerce official supported releases, the existing plugin bootstrap, Classic gateway ID `upayments`, Blocks payment-method registration, and existing HPOS-safe order boundaries.
- Produces: reproducible runtime evidence for exact WordPress/WooCommerce/PHP combinations.

- [ ] **Step 1: Add a real WordPress/WooCommerce activation smoke test**
  - Boot WordPress and WooCommerce, activate SimplixPay, and assert no fatal/error.
  - Assert the `upayments` gateway registers through WooCommerce.
  - Assert the plugin does not mutate protected settings merely by activation.

- [ ] **Step 2: Add Classic and Blocks registration/runtime tests**
  - Verify Classic gateway discovery in a real WooCommerce runtime.
  - Verify Blocks integration registration when Woo Blocks APIs are available.
  - Verify disabled/malformed settings remain unavailable exactly as the Q18 contract requires.

- [ ] **Step 3: Add HPOS on/off runtime tests**
  - Exercise representative order create/read/payment metadata paths with HPOS disabled and enabled.
  - Assert no direct legacy-post dependency is required for the tested paths.
  - Do not declare HPOS compatibility until this matrix is green.

- [ ] **Step 4: Create the CI compatibility matrix**
  - Test current WordPress 7.1/current WooCommerce 11.1 on current recommended PHP.
  - Test at least one supported previous WordPress/WooCommerce line before choosing minimum support metadata.
  - Include a modern PHP range supported by the chosen WordPress/WooCommerce versions.
  - Treat PHP syntax-only jobs as evidence separate from runtime certification.

- [ ] **Step 5: Record exact matrix evidence**
  - Write exact WordPress, WooCommerce, PHP, checkout mode, HPOS state, and result into `docs/project/ENTERPRISE-CERTIFICATION.md`.
  - Failed cells remain unsupported; do not generalize from neighboring versions.

### Task 3: Declare only proven WooCommerce feature compatibility and release metadata

**Files:**
- Modify: `UPayments.php`
- Modify: `tests/integration/ReleaseMetadataTest.php`
- Modify: `docs/COMPATIBILITY.md`
- Modify: `README.md`

**Interfaces:**
- Consumes: green Task 2 matrix.
- Produces: truthful WordPress/WooCommerce/PHP metadata and Woo feature declarations for only verified targets.

- [ ] **Step 1: Write RED tests for each proposed declaration**
  - A test must fail because the intended HPOS/Blocks declaration or header is absent before implementation.
  - Each declaration is independently rejectable.

- [ ] **Step 2: Add minimum compatible metadata supported by the matrix**
  - Set `Requires at least`, `Tested up to`, `WC requires at least`, `WC tested up to`, and `Requires PHP` only to matrix-proven values.
  - Never copy provider/upstream minimum versions as Simplix certification.

- [ ] **Step 3: Add Woo feature declarations only when proven**
  - Use WooCommerce `FeaturesUtil::declare_compatibility()` for `custom_order_tables` only after HPOS on/off tests pass.
  - Use the Woo feature declaration for `cart_checkout_blocks` only after Blocks runtime tests pass.
  - Preserve guarded loading so absent Woo feature APIs cannot fatal.

- [ ] **Step 4: Run exact RED -> GREEN and full regression**
  - Run the new integration tests and all existing Q1-Q19/H12/static-quality gates.
  - Re-run the compatibility matrix after metadata/declaration changes.

### Task 4: Certify provider sandbox transport without turning test credentials into production secrets

**Files:**
- Create: `tests/provider/sandbox-charge-smoke.php`
- Create: `.github/workflows/provider-sandbox-certification.yml`
- Modify: `docs/project/ENTERPRISE-CERTIFICATION.md`

**Interfaces:**
- Consumes: UPayments' officially documented sandbox endpoint/public test credential and the plugin's frozen Charge/status transport rules.
- Produces: bounded external-provider evidence for transport/schema/redirect behavior, not a production merchant certification.

- [ ] **Step 1: Build a read/bounded sandbox smoke contract**
  - Use only documented public sandbox material or explicitly supplied test secrets.
  - Never run production credentials in repository CI.
  - Rate-limit and bound all network calls.

- [ ] **Step 2: Validate Charge initialization semantics**
  - Assert the request reaches the sandbox through the intended HTTPS host.
  - Assert successful initialization satisfies the plugin's strict HTTP/schema/redirect requirements.
  - Do not call initialization proof “captured payment”.

- [ ] **Step 3: Keep destructive/non-idempotent provider paths manual or fixture-backed**
  - Do not add automatic refund/recurring-charge execution to ordinary CI.
  - Use fixtures or explicit manual workflows for payment-card/browser completion where deterministic automation is not safe.

- [ ] **Step 4: Record provider-document contradictions**
  - Record any conflicting current UPayments Blocks/status-rate documentation.
  - Preserve the stricter plugin behavior until a separately tested contract change is justified.

### Task 5: Add deterministic release packaging and artifact verification

**Files:**
- Create: `scripts/build-release.sh`
- Create: `scripts/verify-release.sh`
- Create: `tests/harness/release-artifact-harness.php`
- Create: `.github/workflows/release-artifact.yml`
- Modify: `.distignore`
- Modify: `composer.json` only for developer convenience scripts if appropriate.
- Create: `docs/project/RELEASE-ENGINEERING.md`

**Interfaces:**
- Consumes: tracked repository tree plus `.distignore`, canonical slug/version identity.
- Produces: deterministic installable ZIP, SHA-256 checksum, file manifest, and verification evidence.

- [ ] **Step 1: Write a RED release-artifact harness**
  - Fail while no canonical deterministic builder exists.
  - Assert forbidden development files are absent from package output.
  - Assert required runtime files, plugin header, version, license and assets are present.

- [ ] **Step 2: Implement deterministic package construction**
  - Package under one canonical `simplixpay-upayments/` root.
  - Normalize ordering and timestamps so the same source tree builds byte-identically in the same defined toolchain.
  - Exclude tests, CI, VCS, dev Composer tooling, caches, local artifacts, and secrets.

- [ ] **Step 3: Emit and verify integrity evidence**
  - Generate SHA-256 for the ZIP.
  - Generate a sorted file manifest with per-file hashes.
  - Build twice in CI and compare byte-for-byte output.

- [ ] **Step 4: Install the built ZIP into the real certification environment**
  - Activate the artifact, not the source checkout.
  - Re-run the minimum activation/gateway/HPOS/Blocks smoke suite from the packaged artifact.

### Task 6: Certify feature and operational boundaries that are safe to automate

**Files:**
- Extend: `tests/integration/*`
- Extend: `tests/provider/*`
- Create or extend focused tests for saved cards, subscriptions, multi-merchant, diagnostics, uninstall/retention, and migration bootstrapping.
- Modify: `docs/project/ENTERPRISE-CERTIFICATION.md`
- Modify: `docs/COMPATIBILITY.md`

**Interfaces:**
- Consumes: closed H12/payment/security contracts and the real runtime matrix.
- Produces: explicit Verified / Unsupported / External-certification-required status per feature.

- [ ] **Step 1: Saved-card/tokenization runtime certification**
  - Cover authenticated-user eligibility, no guest persistence, exact selected-card membership, invalid provenance fail-closed, and no raw token exposure.

- [ ] **Step 2: Subscription runtime certification**
  - Cover Classic subscription eligibility, product opt-out, mixed-cart rejection, strict plan/interval handling, and no pre-rejection provider transport.
  - Keep auto-deduction mutation tests fixture-backed unless an explicit safe sandbox workflow exists.

- [ ] **Step 3: Multi-merchant current-scope certification**
  - Certify only the existing single additional merchant allocation and exact amount contract.
  - Do not broaden to arbitrary multi-split routing.

- [ ] **Step 4: Operations/data-retention certification**
  - Test activation/deactivation does not erase merchant/payment data.
  - Test uninstall remains non-destructive by default.
  - Test migration admin/CLI boot only in their permitted contexts and never expose credentials.

- [ ] **Step 5: Accessibility/browser/UI checks where executable**
  - Add browser E2E checks for Classic and Blocks checkout rendering/error states if a stable headless environment is available.
  - Keep theme/device/manual checks explicitly separate from automated evidence.

### Task 7: Decide the physical basename and text-domain release migration from evidence

**Files:**
- Modify only if upgrade/install tests prove the transition safe: `UPayments.php`, distribution layout, translation loading, and related identity tests.
- Create: `tests/integration/UpgradeCompatibilityTest.php`
- Modify: `docs/project/PHASE-0-RELEASE-IDENTITY.md`
- Modify: `docs/project/RELEASE-ENGINEERING.md`

**Interfaces:**
- Consumes: packaged artifact install tests and historical installation identity.
- Produces: either a verified migration to the frozen `simplixpay-upayments.php` / `simplixpay-upayments` targets, or an explicit decision to retain transitional identity for the first stable release.

- [ ] **Step 1: Characterize existing-install upgrade behavior first**
  - Test an existing active `UPayments.php` install upgraded with the new package.
  - Test activation/deactivation, rollback, duplicate-package, and conflict behavior.

- [ ] **Step 2: Make the migration decision from RED/GREEN evidence**
  - If a safe migration is provable, implement it behind exact tests.
  - If not, retain `UPayments.php` and `upayments` for 1.0 rather than risking merchant deactivation or duplicate-plugin identity.

### Task 8: Enterprise closeout and release-candidate governance

**Files:**
- Modify: all living status/readiness docs
- Modify: `README.md`
- Modify: `CHANGELOG.md`
- Modify: `docs/COMPATIBILITY.md`
- Modify: `docs/ENGINEERING-ROADMAP.md`
- Modify: CI governance assertions
- Create: final release-readiness evidence section in `docs/project/RELEASE-ENGINEERING.md`

**Interfaces:**
- Consumes: all successful certification and artifact evidence from Tasks 1-7.
- Produces: an enterprise release-candidate state with all residual external dependencies explicitly bounded.

- [ ] **Step 1: Reconcile every living document to exact current truth**
  - Remove obsolete current-gate markers.
  - Preserve historical facts and immutable milestone evidence.

- [ ] **Step 2: Verify repository hygiene**
  - Zero unjustified open issues/PRs.
  - No stale implementation branches for merged work.
  - Full CI and security analysis green on exact final candidate.

- [ ] **Step 3: Run final source and packaged-artifact verification**
  - Full Quality Gates.
  - Compatibility matrix.
  - Provider bounded smoke.
  - Artifact reproducibility/install smoke.
  - Security scanning and dependency audit.

- [ ] **Step 4: Classify genuinely external final evidence**
  - Production merchant credentials, third-party commercial plugin licenses, independent penetration/PCI work, or physical-device/manual-browser checks are listed only if they cannot be executed safely in repository automation.
  - Do not call the plugin enterprise-complete while a release-blocking external item is unverified.

- [ ] **Step 5: Reserve one final external AI/Codex review**
  - Only after all implementation/certification/release engineering above is complete.
  - That review is an additional final challenge, never the source of primary evidence.
