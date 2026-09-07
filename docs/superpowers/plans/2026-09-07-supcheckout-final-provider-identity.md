# SUPCheckout Final Provider-Specific Identity Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Finish the last pre-stable identity migration so the UPayments-only plugin ships as **SUPCheckout for UPayments** with WordPress/package/text-domain root `supcheckout`, PHP namespace root `Simplixi\\SUPCheckout`, and all historical UPayments payment/data compatibility contracts preserved.

**Architecture:** Keep SUPCheckout permanently provider-specific. Separate product-owned code from provider-contract code through `Simplixi\\SUPCheckout` and `Simplixi\\SUPCheckout\\Provider\\UPayments` where appropriate, but do not introduce multi-provider runtime abstractions. Preserve `UPayments.php` as the first-stable physical bootstrap and prove package-root upgrades from every relevant pre-stable identity.

**Tech Stack:** WordPress, WooCommerce, PHP 7.4+ certified runtime, PHP 7.2 syntax ratchet, Composer, PHPUnit, PHPStan, PHPCS/WPCS, JavaScript Blocks harnesses, GitHub Actions, WP-CLI, deterministic shell release tooling.

**Spec:** `docs/superpowers/specs/2026-09-07-supcheckout-provider-specific-identity-design.md`

## Global Constraints

- Formal plugin name is **SUPCheckout for UPayments**.
- Short product name is **SUPCheckout**.
- Provider scope is permanently **UPayments only**.
- Canonical WordPress/plugin slug and text domain become exactly `supcheckout`.
- Canonical PHP namespace root becomes exactly `Simplixi\\SUPCheckout`.
- Provider-specific first-party implementation may use `Simplixi\\SUPCheckout\\Provider\\UPayments`.
- `UPayments.php` remains the first-stable physical bootstrap.
- Gateway/payment ID `upayments`, `woocommerce_upayments_settings`, `wc_upayments`, historical `_upay_*`, token/provenance state, subscription hooks/state, billing-attempt state, provider API terminology and historical order payment-method values remain protected.
- The GitHub repository remains `SimplixInnovations/sucheckout` during implementation; owner/admin renames it to `SimplixInnovations/supcheckout` only after the code/release identity migration is merged and re-certified.
- No public stable tag, GitHub Release or WordPress.org publication occurs during this plan.
- No multi-provider, smart-routing, cross-provider fraud, or orchestration functionality is added.
- Historical evidence remains historically accurate; living current control surfaces converge on SUPCheckout.
- No Q20 is created.
- Every production/release change follows RED -> GREEN evidence.

---

### Task 1: Freeze SUPCheckout identity as RED

**Files:**
- Modify: `tests/harness/sucheckout-identity-migration-harness.php`
- Modify: `tests/harness/sucheckout-namespace-migration-harness.php`
- Modify: `tests/harness/sucheckout-frontend-identity-harness.php`
- Modify: `tests/harness/sucheckout-residue-harness.php`
- Modify: `tests/unit/Release/IdentityTest.php`
- Modify: `tests/harness/release-artifact-harness.php`
- Modify: `tests/harness/wordpress-org-submission-harness.php`

**Interfaces:**
- Consumes: current certified SUCheckout transitional identity on `main`.
- Produces: failing assertions that define the final SUPCheckout product/package/namespace contract without changing production code.

- [ ] **Step 1: Replace expected first-party product identity in focused harnesses**

Required expectations:

```php
PRODUCT_NAME === 'SUPCheckout for UPayments'
SHORT_NAME === 'SUPCheckout'
SLUG === 'supcheckout'
TEXT_DOMAIN === 'supcheckout'
NAMESPACE_ROOT === 'Simplixi\\SUPCheckout'
```

Production code must still be untouched at this point.

- [ ] **Step 2: Add package/release RED expectations**

Require:

```text
plugin root: supcheckout/
plugin basename: supcheckout/UPayments.php
release ZIP: supcheckout-X.Y.Z.zip
Composer package: simplix-innovations/supcheckout
WordPress.org/Plugin Check slug: supcheckout
```

- [ ] **Step 3: Add negative living-residue assertions**

Living production/release/control surfaces must reject canonical use of:

```text
SUCheckout for UPayments
sucheckout-upayments
Simplixi\SUCheckout\UPayments
simplix-innovations/sucheckout-upayments
```

Historical docs/fixtures remain allowlisted where they record true past state.

- [ ] **Step 4: Preserve UPayments compatibility assertions**

The RED harness must continue requiring:

```text
gateway id: upayments
settings: woocommerce_upayments_settings
callback: wc_upayments
token secret: upayments_token_identity_secret_v2
subscription hook: upay_process_subscriptions
billing-attempt identities: upayments_billing_attempts
```

- [ ] **Step 5: Push the RED-only commit and verify CI fails for the intended identity reasons**

Expected result: identity/residue/release assertions fail because production still exposes SUCheckout transitional identity. Failures unrelated to the intended identity delta must be fixed before proceeding.

- [ ] **Step 6: Commit**

```bash
git add tests/
git commit -m "test: define final SUPCheckout identity"
```

### Task 2: Migrate Composer and PHP namespace root

**Files:**
- Modify: `composer.json`
- Modify: `composer.lock`
- Modify: all first-party namespaced PHP files under `src/`
- Modify: `UPayments.php`
- Modify: tests/harnesses referencing first-party FQCNs

**Interfaces:**
- Consumes: RED namespace contract from Task 1.
- Produces: `Simplixi\\SUPCheckout\\` PSR-4 root with provider-specific API classes placed under `Simplixi\\SUPCheckout\\Provider\\UPayments\\` only where provider semantics justify it.

- [ ] **Step 1: Change Composer package and PSR-4 identity**

```json
{
  "name": "simplix-innovations/supcheckout",
  "require": {
    "php": ">=7.4"
  },
  "autoload": {
    "psr-4": {
      "Simplixi\\SUPCheckout\\": "src/"
    }
  },
  "autoload-dev": {
    "psr-4": {
      "Simplixi\\SUPCheckout\\Tests\\": "tests/unit/"
    }
  }
}
```

Keep the separate CI PHP 7.2 syntax-only ratchet; do not claim PHP 7.2 runtime support.

- [ ] **Step 2: Move first-party namespace declarations/imports to `Simplixi\\SUPCheckout`**

Examples:

```php
Simplixi\SUCheckout\UPayments\Release\Identity
// becomes
Simplixi\SUPCheckout\Release\Identity

Simplixi\SUCheckout\UPayments\Payment\PaymentLifecycle
// becomes
Simplixi\SUPCheckout\Payment\PaymentLifecycle
```

- [ ] **Step 3: Use provider namespace only for provider-contract implementation**

Provider endpoint/schema/transport helpers that are explicitly UPayments-specific may use:

```php
namespace Simplixi\SUPCheckout\Provider\UPayments;
```

Do not move generic product-owned Payment, Security, Release, Admin or Migration classes under Provider.

- [ ] **Step 4: Update bootstrap imports and exact namespace ratchets**

- [ ] **Step 5: Run namespace/unit/static checks GREEN before proceeding**

- [ ] **Step 6: Commit**

```bash
git add composer.json composer.lock src UPayments.php tests
git commit -m "refactor: migrate SUPCheckout namespace root"
```

### Task 3: Migrate product name, slug, text domain and first-party runtime identity

**Files:**
- Modify: `UPayments.php`
- Modify: `src/Release/Identity.php`
- Modify: `readme.txt`
- Modify: first-party PHP files containing SUCheckout translation/prefix identity
- Modify: `templates/*.php`
- Modify: first-party JavaScript/CSS handles/config roots where currently provider-qualified
- Modify: focused frontend/residue tests

**Interfaces:**
- Consumes: green namespace root.
- Produces: SUPCheckout product/runtime identity while UPayments remains the provider/payment compatibility identity.

- [ ] **Step 1: Change canonical release identity**

```php
public const PRODUCT_NAME = 'SUPCheckout for UPayments';
public const SHORT_NAME = 'SUPCheckout';
public const SLUG = 'supcheckout';
public const TEXT_DOMAIN = 'supcheckout';
public const NAMESPACE_ROOT = 'Simplixi\\SUPCheckout';
public const TARGET_TEXT_DOMAIN = 'supcheckout';
```

Keep `REPOSITORY = 'SimplixInnovations/sucheckout'` until owner rename.

- [ ] **Step 2: Change WordPress plugin metadata**

```text
Plugin Name: SUPCheckout for UPayments
Text Domain: supcheckout
Description: Independently engineered UPayments payment integration for WooCommerce by Simplix Innovations.
```

Do not remove UPayments from provider-facing descriptions.

- [ ] **Step 3: Collapse first-party symbols**

Use final first-party forms for new/current product-owned surfaces:

```text
SUCHECKOUT_* -> SUPCHECKOUT_*
sucheckout_upayments_* -> supcheckout_*
sucheckout-upayments-* -> supcheckout-*
suCheckoutUpayments -> supCheckout
suCheckoutUpaymentsConfig -> supCheckoutConfig
```

Do not mechanically alter protected `upayments` provider/data identifiers.

- [ ] **Step 4: Change all SUPCheckout-owned translations to literal text domain `supcheckout`**

- [ ] **Step 5: Run focused identity/frontend/H12/Blocks tests GREEN**

- [ ] **Step 6: Commit**

```bash
git add UPayments.php src readme.txt templates assets tests
git commit -m "refactor: migrate SUPCheckout runtime identity"
```

### Task 4: Migrate deterministic release and WordPress.org package identity

**Files:**
- Modify: `scripts/build-release.sh`
- Modify: `scripts/verify-release.sh`
- Modify: `scripts/install-wp-test-environment.sh`
- Modify: `.github/workflows/release-artifact.yml`
- Modify: `.github/workflows/wordpress-org-submission-check.yml`
- Modify: `.github/workflows/compatibility-certification.yml`
- Modify: affected release/WordPress.org harnesses

**Interfaces:**
- Consumes: canonical `supcheckout` runtime identity.
- Produces: deterministic `supcheckout-X.Y.Z.zip` with exactly one `supcheckout/` root and packaged Plugin Check targeting `supcheckout`.

- [ ] **Step 1: Change builder/verifier root and ZIP naming**

Expected:

```text
dist/supcheckout-0.1.0.zip
supcheckout/
supcheckout/UPayments.php
```

- [ ] **Step 2: Change packaged activation/default slug to `supcheckout`**

- [ ] **Step 3: Change WordPress.org packaged Plugin Check slug to `supcheckout`**

- [ ] **Step 4: Keep required `Release Gate` created for every PR**

No workflow-level `pull_request.paths` filter may be reintroduced.

- [ ] **Step 5: Build twice and require byte-identical ZIP output**

- [ ] **Step 6: Verify source-byte binding, sorted manifest and SHA-256 sidecar**

- [ ] **Step 7: Commit**

```bash
git add scripts .github/workflows tests/harness
git commit -m "build: migrate SUPCheckout release identity"
```

### Task 5: Certify every relevant pre-stable package-root upgrade

**Files:**
- Modify: `tests/integration/UpgradeCompatibilityTest.php`
- Modify: `tests/harness/release-artifact-harness.php`
- Modify: `.github/workflows/release-artifact.yml`
- Modify: release migration fixtures/evidence under existing test structure

**Interfaces:**
- Consumes: final `supcheckout/UPayments.php` package.
- Produces: non-destructive migration/rollback proof from previous Simplix package roots.

- [ ] **Step 1: Preserve historical SimplixPay root test**

```text
simplixpay-upayments/UPayments.php -> supcheckout/UPayments.php
```

- [ ] **Step 2: Preserve transitional SUCheckout-UPayments root test**

```text
sucheckout-upayments/UPayments.php -> supcheckout/UPayments.php
```

- [ ] **Step 3: Include `sucheckout/UPayments.php` only if a certified transitional fixture exists on repository history**

Do not invent a fixture from unmerged branch work.

- [ ] **Step 4: In every upgrade family verify non-destructive preservation of**

```text
woocommerce_upayments_settings
historical order payment-method value upayments
_upay_* metadata
token/provenance/scope/generation state
upay_process_subscriptions state
billing-attempt state
callback compatibility
rollback behavior
canonical final activation
```

- [ ] **Step 5: End each migration with obsolete package root removed and final `supcheckout` package active**

- [ ] **Step 6: Commit**

```bash
git add tests .github/workflows/release-artifact.yml
git commit -m "test: certify SUPCheckout package migration"
```

### Task 6: Reconcile living docs and governance

**Files:**
- Modify: `AGENTS.md`
- Modify: `README.md`
- Modify: `CHANGELOG.md`
- Modify: `NOTICE.md`
- Modify: `UPSTREAM.md`
- Modify: `docs/COMPATIBILITY.md`
- Modify: `docs/ENGINEERING-ROADMAP.md`
- Modify: `docs/project/PROJECT-STATUS.md`
- Modify: `docs/project/NAMING-IDENTITY-STANDARD.md`
- Modify: `docs/project/NEW-CHAT-HANDOFF.md`
- Modify: `docs/project/OWNER-HANDOFF.md`
- Modify: `docs/project/README.md`
- Modify: `docs/project/RELEASE-ENGINEERING.md`
- Modify: `docs/project/ENTERPRISE-CERTIFICATION.md`
- Preserve historical documents accurately

**Interfaces:**
- Consumes: exact implementation evidence from Tasks 1-5.
- Produces: one living SUPCheckout identity and one owner/admin rename checklist.

- [ ] **Step 1: Replace living product identity with SUPCheckout**

- [ ] **Step 2: Mark SUCheckout technical-identity plans/specs as superseded where they are historical**

Do not rewrite old SHAs or evidence descriptions.

- [ ] **Step 3: Record provider-isolated strategy**

Living docs must state SUPCheckout is permanently UPayments-only and cross-provider orchestration/fraud belongs to a separate platform project.

- [ ] **Step 4: Record repository rename boundary**

Before owner action:

```text
current: SimplixInnovations/sucheckout
target after certified merge: SimplixInnovations/supcheckout
```

- [ ] **Step 5: Update governance/residue harnesses so stale SUCheckout first-party identity cannot return**

- [ ] **Step 6: Commit**

```bash
git add AGENTS.md README.md CHANGELOG.md NOTICE.md UPSTREAM.md docs tests
git commit -m "docs: reconcile SUPCheckout canonical identity"
```

### Task 7: Exact-head certification, independent review and merge

**Files:** no production files unless valid review findings require test-first fixes.

**Interfaces:**
- Consumes: final branch candidate.
- Produces: exact-reviewed, exact-green squash merge to `main`.

- [ ] **Step 1: Require Quality Gates / Governance / H12 GREEN**

- [ ] **Step 2: Require full Compatibility Certification matrix GREEN**

- [ ] **Step 3: Require deterministic Release Artifact and every upgrade-family GREEN**

- [ ] **Step 4: Require Provider Sandbox GREEN**

- [ ] **Step 5: Require WordPress.org packaged Plugin Check with zero blocking findings**

- [ ] **Step 6: Require CodeQL/security GREEN**

- [ ] **Step 7: Request independent whole-PR review**

Critical and Important findings must be resolved with RED -> GREEN evidence. No valid unresolved review threads may remain.

- [ ] **Step 8: Squash-merge exact reviewed head**

- [ ] **Step 9: Verify post-merge `main` again before declaring engineering closure**

### Task 8: Owner/admin repository rename and coordinate-only closure

**Owner action required after Task 7 is post-merge green.**

**Interfaces:**
- Consumes: certified SUPCheckout `main`.
- Produces: final public GitHub coordinate `SimplixInnovations/supcheckout`.

- [ ] **Step 1: Owner renames repository**

GitHub repository settings:

```text
sucheckout -> supcheckout
```

Expected final URL:

```text
https://github.com/SimplixInnovations/supcheckout
```

- [ ] **Step 2: Update repository About/topics/social preview as appropriate**

Do not add unsupported compatibility claims.

- [ ] **Step 3: Update local origin**

```bash
git remote set-url origin https://github.com/SimplixInnovations/supcheckout.git
git fetch --prune --tags origin
git remote set-head origin -a
```

- [ ] **Step 4: Create one coordinate-only PR**

Update only living current repository URLs/constants/badges from:

```text
SimplixInnovations/sucheckout
```

to:

```text
SimplixInnovations/supcheckout
```

Historical evidence remains unchanged where old coordinates are historically true.

- [ ] **Step 5: Re-run all mandatory checks and merge the coordinate-only PR**

- [ ] **Step 6: Verify final branch topology and repository controls**

Final intended remote topology after cleanup:

```text
main
```

No obsolete refactor branch remains.
