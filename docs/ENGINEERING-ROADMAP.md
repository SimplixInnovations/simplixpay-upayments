# SimplixPay for UPayments — Engineering Roadmap

This is the public high-level sequence. `docs/project/PROJECT-STATUS.md` owns the current verified state; historical phase records preserve their closeout evidence.

## Completed foundations

1. **Repository Foundation & Readiness — DONE / VERIFIED**
2. **Phase 0 — release identity and updater ownership — DONE / VERIFIED**
3. **Phase 9I — historical token-identity migration — DONE / VERIFIED**
4. **Provider Contract & Payment Lifecycle — DONE / VERIFIED**
5. **Security Threat-Model Closure — DONE / VERIFIED**
6. **Architecture & Code-Quality Foundation A1-A5 — DONE / VERIFIED**
7. **Full Automated Quality Platform Q1-Q19 — DONE / VERIFIED**

The numbered Quality Platform is closed at Q19. No Q20 is justified by current evidence.

## Enterprise completion program

The enterprise completion plan is `docs/superpowers/plans/2026-09-06-enterprise-completion.md`.

### Task 1 — quality-program closeout — DONE / VERIFIED

Retired the numbered gate sequence at Q19 and established the named enterprise certification/release program without weakening the permanent Q1-Q19/H12 regression stack.

### Task 2 — real platform compatibility — DONE / VERIFIED

Permanent 16-cell real WordPress/WooCommerce/PHP × legacy/HPOS matrix covering activation, Classic registration, Blocks registration/availability and Woo order CRUD.

### Task 3 — evidence-derived public declarations — DONE / VERIFIED

Public WordPress/WooCommerce/PHP support headers and Woo `cart_checkout_blocks` / `custom_order_tables` declarations are derived from real matrix evidence, not static assumptions.

### Task 4 — bounded provider sandbox — DONE / VERIFIED

One controlled public UPayments sandbox Charge initialization verifies endpoint/transport/201/schema/redirect normalization without payment completion, card data, polling, refunds, recurring mutation or production credentials.

### Task 5 — deterministic installable artifact — DONE / VERIFIED

Git-HEAD-bound deterministic ZIP, checksum, manifest, independent source-byte verification, tamper rejection, dirty-worktree/index isolation and packaged real-runtime smoke.

### Task 6 — feature and operational boundaries — DONE / VERIFIED

Permanent real-runtime evidence for saved-card/token provenance, subscription eligibility/pre-dispatch, the existing one-additional-merchant allocation and non-destructive lifecycle/data retention. This task discovered and fixed a real token-bootstrap history pagination defect.

### Task 7 — existing-install upgrade / release identity — DONE / VERIFIED

Current and floor runtime cells prove safe same-basename upgrade, rollback, deactivate/reactivate, data/callback/cron continuity and duplicate-package characterization.

Direct `UPayments.php` → `simplixpay-upayments.php` migration fails the active-install contract. The first stable release therefore retains:

- `UPayments.php`;
- plugin basename `simplixpay-upayments/UPayments.php`;
- text domain `upayments`.

The eventual canonical filename/text-domain targets remain future tested migrations.

### Task 8 — Enterprise Release Candidate Closeout — CURRENT / FINAL VERIFICATION

Task 8 must finish on one immutable candidate head:

- reconcile living status/readiness/public docs;
- clean repository topology and unjustified work;
- pass full Quality/H12, 16-cell Compatibility, Release Artifact including upgrade cells, bounded Provider Sandbox, CodeQL and locked dependency audit;
- classify external/manual evidence honestly;
- run the reserved one final whole-plugin Codex challenge after primary evidence is green;
- resolve every valid finding;
- squash-merge the exact verified head and repeat required checks on `main`.

Task 8 does **not** automatically create a public 1.0 tag, GitHub Release or WordPress.org publication. Those are owner release actions after release-candidate qualification.

## External/manual evidence track

These items are not to be falsely converted into repository CI claims:

- production merchant payment completion;
- wallet completion on real provider-enabled devices/accounts;
- WPML/WCML/multilingual/multicurrency/RTL certification;
- browser/device/theme/accessibility matrix;
- representative store load/performance thresholds;
- penetration testing / PCI / legal-compliance attestations;
- live non-idempotent subscription auto-deduction;
- provider webhook signature verification until a stable published contract exists.

Automatic Woo refunds and arbitrary marketplace multi-split remain intentionally unsupported unless separately designed and certified.

## Continuous maintenance after release-candidate closeout

- platform/provider version monitoring;
- dependency/security updates;
- compatibility regression matrix;
- release/support lifecycle;
- future explicit migrations for physical basename/text domain only when upgrade/i18n evidence permits them.

## Rule

A roadmap item is complete only after exact implementation/review evidence, required checks, merge, post-merge verification and living-state reconciliation. Branch existence, a bot report or one green workflow is never sufficient.
