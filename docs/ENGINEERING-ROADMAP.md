# SUCheckout for UPayments — Engineering Roadmap

This is the public high-level sequence. `docs/project/PROJECT-STATUS.md` owns the current verified state; historical phase records preserve their closeout evidence.

## Completed foundations

1. **Repository Foundation & Readiness — DONE / VERIFIED**
2. **Phase 0 — release identity and updater ownership — DONE / VERIFIED**
3. **Phase 9I — historical token-identity migration — DONE / VERIFIED**
4. **Provider Contract & Payment Lifecycle — DONE / VERIFIED**
5. **Security Threat-Model Closure — DONE / VERIFIED**
6. **Architecture & Code-Quality Foundation A1-A5 — DONE / VERIFIED**
7. **Full Automated Quality Platform Q1-Q19 — DONE / VERIFIED**
   - Q1 locked development-toolchain foundation: **DONE / VERIFIED** through PR #26 and post-merge Quality Gates #178;
   - Q2 CheckoutPayload boundary characterization and baseline-free static-analysis expansion: **DONE / VERIFIED** through PR #28 and post-merge Quality Gates #183;
   - Q3 payment-concurrency characterization and baseline-free analysis for StatusRateGate/OrderLock: **DONE / VERIFIED** through PR #29 and post-merge Quality Gates #189;
   - Q4 authenticated status transport/binding characterization and baseline-free analysis for StatusVerifier: **DONE / VERIFIED** through PR #30 and post-merge Quality Gates #195;
   - Q5 payment-method availability cache/lock/gate/provider normalization characterization and baseline-free analysis: **DONE / VERIFIED**;
   - Q6 gateway settings schema/validation/sanitation/rendering/admin-asset characterization and baseline-free analysis: **DONE / VERIFIED**;
   - Q7 public order-status parsing/authorization/response characterization and baseline-free analysis: **DONE / VERIFIED** through PR #33 and post-merge Quality Gates #213;
   - Q8 release-identity/version/updater/legacy-and-target-identity characterization and baseline-free analysis: **DONE / VERIFIED** through PR #34 and post-merge Quality Gates #219;
   - Q9 migration-settings option/credential/mode/redaction characterization and baseline-free analysis: **DONE / VERIFIED** through PR #35 and post-merge Quality Gates #224;
   - Q10 migration-bootstrap context/dependency/registration characterization and baseline-free analysis: **DONE / VERIFIED** through PR #36 and post-merge Quality Gates #227;
   - Q11 subscription-composition hook/dependency/initializer characterization and baseline-free analysis: **DONE / VERIFIED** through PR #37 and post-merge Quality Gates #230;
   - Q12 guarded subscription-product-type load/parent/type characterization and baseline-free analysis: **DONE / VERIFIED** through PR #38 and post-merge Quality Gates #232;
   - Q13 migration CLI parsing/confirmation/bounds/redaction/error characterization and baseline-free analysis: **DONE / VERIFIED** through PR #39 and post-merge Quality Gates #237;
   - Q14 migration-admin authorization/nonce/form/bounds/redaction/escaping characterization and baseline-free analysis: **DONE / VERIFIED** through PR #40 and post-merge Quality Gates #248;
   - Q15 subscription-presentation product/admin/cart/account characterization and baseline-free analysis: **DONE / VERIFIED** through PR #41 and post-merge Quality Gates #254;
   - Q16 migration-core preflight/batch/executor characterization and baseline-free analysis: **DONE / VERIFIED** through PR #42 and post-merge Quality Gates #316;
   - Q17 payment-runtime checkout-orchestration/lifecycle characterization and baseline-free analysis: **DONE / VERIFIED** through PR #43 and post-merge Quality Gates #415;
   - Q18 Blocks activation/availability enforcement, analyzer ownership, Woo logging correction and permanent regression coverage: **DONE / VERIFIED** through PR #44 and post-merge Quality Gates #442;
   - Q19 subscription product-eligibility consistency: **DONE / VERIFIED** through PR #45, exact-head Quality Gates #463, squash merge `29ba16a1eabc00e25c3652ae838be9b9539b3a10` and post-merge Quality Gates #464;

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

The historical Task 7 experiment proved that changing the physical main filename alone does not transfer WordPress active-plugin identity. That evidence is why the approved SUCheckout migration retains `UPayments.php` as the physical bootstrap.

The SUCheckout package identity is now:

- physical main file `UPayments.php`;
- canonical package basename `sucheckout-upayments/UPayments.php`;
- canonical text domain `sucheckout-upayments`;
- legacy pre-release basename `simplixpay-upayments/UPayments.php` retained only for migration/rollback certification.

A future physical rename to `sucheckout-upayments.php` remains separately gated.

### Task 8 — Enterprise Release Candidate Closeout — DONE / VERIFIED

Final reviewed head `5a24944617f7ee482c381e5e899f687b77d81d09` passed the complete Quality/H12 stack, permanent 16-cell Compatibility matrix, deterministic Release Artifact including packaged legacy/HPOS and current/floor upgrade cells, bounded Provider Sandbox, locked dependency audit and CodeQL.

The reserved final whole-plugin Codex review produced one valid P2 governance finding. It was independently reproduced, fixed and made permanent by asserting all closed Q1-Q19 stale-current markers before the final exact head was reverified.

PR #54 squash-merged as `2ddb1790fead37c6055256847dc7c827e165af4a`. Canonical `main` then passed Quality #553, Compatibility #81, Release Artifact #35, Provider Sandbox #13 and CodeQL/main-security #358.

That pre-rebrand enterprise qualification is retained as historical evidence. The approved **SUCheckout identity migration is DONE / VERIFIED** through PR #58 and fresh post-merge main certification. Repository rename, owner-local acceptance and any public tag/GitHub Release/WordPress.org publication are the remaining separate owner/admin track.

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

## SUCheckout post-merge owner stage

Final first-party naming reconciliation is runtime-bearing certified `main` `efe937c67343242b7ccf3396a67b3cf2ce35ebac`.

Fresh evidence:

- Quality #781 — **SUCCESS**;
- Compatibility #309 — **16/16 SUCCESS**;
- Release Artifact #258 — **SUCCESS**;
- Provider Sandbox #221 — **SUCCESS**;
- WordPress.org Submission Check #116 — **SUCCESS**;
- CodeQL/main-security #595 — **SUCCESS**;
- official packaged Plugin Check — **0 blocking errors**.

The next permitted track is documented in `docs/project/OWNER-HANDOFF.md`:

1. merge final docs/control-plane reconciliation;
2. remove obsolete remote branches;
3. rename the repository to `SimplixInnovations/sucheckout-upayments`;
4. reconcile repository-coordinate links and reverify;
5. perform independent local owner acceptance;
6. apply final product visual branding/UI acceptance;
7. make a separate explicit publication/version decision.

No Q20 or invented engineering phase is justified by these administrative and acceptance actions.

## Continuous maintenance after release-candidate closeout

- platform/provider version monitoring;
- dependency/security updates;
- compatibility regression matrix;
- release/support lifecycle;
- future explicit migrations for physical basename/text domain only when upgrade/i18n evidence permits them.

## Rule

A roadmap item is complete only after exact implementation/review evidence, required checks, merge, post-merge verification and living-state reconciliation. Branch existence, a bot report or one green workflow is never sufficient.
