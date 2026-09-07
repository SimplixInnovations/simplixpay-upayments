# Final Pre-Release Deep Audit Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Remove the final evidence-backed pre-release defects found by the deep audit without changing protected UPayments/provider/persisted identities or widening the plugin's certified feature claims.

**Architecture:** Keep the work narrowly partitioned into three independently testable hardening tasks: fix the My Account filter routing bug, remove dead/incorrect asset-loading behavior while binding first-party cache versions to the canonical release version, and stop packaging WordPress.org screenshot source material. Every behavior change follows RED → GREEN verification, then the complete exact-head certification stack runs before merge.

**Tech Stack:** WordPress, WooCommerce, PHP 7.4+ runtime, PHPUnit 11, PHPStan 2, PHPCS/WPCS, GitHub Actions, deterministic ZIP tooling.

**Spec:** `docs/project/OWNER-HANDOFF.md`, `docs/project/PROJECT-STATUS.md`, `docs/project/NAMING-IDENTITY-STANDARD.md`, and the certified protected-identity contract in `AGENTS.md`.

## Global Constraints

- Product name: **SUPCheckout for UPayments**; technical slug/text domain/root: `supcheckout`.
- Preserve gateway/payment ID `upayments`, `woocommerce_upayments_settings`, `wc_upayments`, `_upay_*`, `UPayments_order_id`, token/subscription/billing-attempt identities and historical order payment-method values.
- Keep `supcheckout/UPayments.php` as the qualified first-stable bootstrap.
- PHP 7.4 remains the runtime certification floor; PHP 7.2 remains syntax-only.
- No new feature claims, version promotion, public tag, GitHub Release or WordPress.org publication.
- No blanket analyzer/Plugin Check suppressions.
- Every production behavior change must be preceded by a failing test that demonstrates the exact defect.

---

### Task 1: Remove hard-coded My Account page ID routing

**Files:**
- Modify: `tests/unit/Subscription/PresentationTest.php`
- Modify: `tests/harness/quality-platform-subscription-presentation-harness.php`
- Modify: `src/Subscription/Presentation.php`

**Interfaces:**
- Consumes: existing `Presentation::render_account_orders_filter()` and private `request_text()` boundary.
- Produces: GET filter form that preserves a legitimate supplied `page_id` but never invents page ID `12` when absent.

- [ ] **Step 1: Write the failing PHPUnit test**

Extend the account-filter test so a request without `page_id` renders no `name="page_id"` hidden field, while a scalar supplied page ID is escaped and preserved.

- [ ] **Step 2: Advance the permanent source harness to the desired contract**

Replace the Q15 assertion that requires `self::request_text($_GET, 'page_id', '12')` with assertions that reject the hard-coded default and require conditional rendering only when a non-empty sanitized page ID is present.

- [ ] **Step 3: Run RED verification**

Run on the exact branch head:

```bash
vendor/bin/phpunit --configuration phpunit.xml.dist tests/unit/Subscription/PresentationTest.php
php tests/harness/quality-platform-subscription-presentation-harness.php
```

Expected: failure specifically because the current renderer injects `page_id=12` when no page ID exists and the old Q15 source contract still requires that default.

- [ ] **Step 4: Implement the minimal fix**

Read `page_id` with an empty default and emit the hidden input only when the sanitized value is non-empty. Do not change filter status semantics or WooCommerce query behavior.

- [ ] **Step 5: Run GREEN verification**

Repeat both commands and require zero failures.

### Task 2: Remove dead asset behavior and bind cache versions to SUPCheckout version

**Files:**
- Modify: `tests/harness/sucheckout-frontend-identity-harness.php` or create a focused asset-contract harness if the existing harness is not the correct ownership boundary.
- Modify: `UPayments.php`
- Modify: `src/Admin/GatewaySettings.php`
- Modify: `includes/class-wc-gateway-upayments-blocks.php`
- Delete only after RED proof: `assets/css/old-design.css`, `includes/admin-footer.php`
- Evaluate and delete only when no runtime/control reference exists: `assets/js/upayments-blocks-integration.js`, `assets/js/upayments-thankyou.js`, `assets/js/checkout/constants.js`, `assets/js/checkout/data.js`, `assets/images/disabled.gif`

**Interfaces:**
- Consumes: canonical `SUPCHECKOUT_VERSION` constant and existing WordPress enqueue/register handles.
- Produces: no empty CSS request/no-op admin-footer include/dead localization call; first-party asset versions track the plugin release version.

- [ ] **Step 1: Prove each candidate is dead before deleting it**

Search tracked runtime/control files for each exact path/handle/symbol. Dynamic payment-method image naming must be considered; do not remove Google Pay/Samsung Pay images.

- [ ] **Step 2: Write failing asset-contract assertions**

Require: no `your-gateway-core` localization; no admin-footer no-op hook/include; no `old-design.css` enqueue; first-party CSS/JS enqueue/register calls use `SUPCHECKOUT_VERSION` rather than literal `3.0.0`; every deleted candidate has no remaining tracked reference.

- [ ] **Step 3: Run RED verification**

Run the focused harness and confirm failures correspond exactly to the current stale/dead asset behavior.

- [ ] **Step 4: Apply the minimal production cleanup**

Remove only proven no-op/dead paths. Replace cache-version literals with `SUPCHECKOUT_VERSION`. Preserve script/style handles and dependencies unless the handle itself is proven dead.

- [ ] **Step 5: Run GREEN verification**

Run focused harness + PHPUnit + PHPCS/PHPStan through `composer quality` and require zero failures/warnings.

### Task 3: Exclude WordPress.org screenshots from the installable ZIP

**Files:**
- Modify: `.distignore`
- Modify: `tests/harness/release-artifact-harness.php`
- Modify: `tests/harness/wordpress-org-submission-harness.php` if needed for the package contract.

**Interfaces:**
- Consumes: deterministic Git-HEAD-bound release builder.
- Produces: installable ZIP without `assets/screenshots/`, while repository screenshots remain available as source/reference material.

- [ ] **Step 1: Write the failing package assertion**

Require `.distignore` to contain `/assets/screenshots/` and the independently inspected ZIP to contain no path under `supcheckout/assets/screenshots/`.

- [ ] **Step 2: Run RED verification**

Run:

```bash
php tests/harness/release-artifact-harness.php
```

Expected: fail because current package includes the screenshot directory.

- [ ] **Step 3: Add the distribution exclusion**

Add `/assets/screenshots/` to `.distignore`; do not delete repository screenshots merely to shrink the ZIP.

- [ ] **Step 4: Run GREEN verification**

Re-run release harness; build twice; verify identical ZIP/checksum/manifest and inspect package membership.

### Task 4: Full exact-head certification and review

**Files:** none unless a real failure is found.

- [ ] **Step 1: Run/observe the full exact-head stack**

Require success for Quality/H12, all 16 Compatibility cells + `Compatibility Gate`, deterministic Release + `Release Gate`, Provider Sandbox, strict WordPress.org Plugin Check, and CodeQL.

- [ ] **Step 2: Independently review the complete diff**

Confirm no protected identifier changed and no unplanned feature/runtime expansion occurred.

- [ ] **Step 3: Request external code review**

Request Codex review on the exact final head when available; resolve every valid Critical/Important finding. If quota prevents review, record that limitation and rely on independent patch review plus executable evidence.

- [ ] **Step 4: Squash-merge from exact certified head**

Merge only with the expected head SHA.

- [ ] **Step 5: Reverify merged `main`**

Require the applicable post-merge stack green, branch auto-deleted, `main` only, zero open PRs/issues, and no tags/releases.

## Self-Review

- Spec coverage: protected identities, release/package integrity, account routing, asset cache invalidation, dead assets and WordPress.org package hygiene are explicitly covered.
- Placeholder scan: no TBD/TODO/implement-later placeholders.
- Type/interface consistency: no public PHP API is added; existing static `Presentation` and WordPress enqueue interfaces remain unchanged.
- Scope boundary: provider endpoints, payment authority, subscription billing semantics, versioning and publication are not changed by this plan.
