# SUCheckout for UPayments — Clean Chat Handoff

Use this file with root `AGENTS.md`, `docs/project/PROJECT-STATUS.md`, `docs/project/ENTERPRISE-CERTIFICATION.md`, `docs/project/RELEASE-ENGINEERING.md`, the naming standard and the immutable historical phase records.

## Identity

- Current GitHub repository pending owner/admin rename: `SimplixInnovations/simplixpay-upayments`
- Target canonical repository: `SimplixInnovations/sucheckout-upayments`
- Formal product: **SUCheckout for UPayments**
- Short product reference / family: **SUCheckout**
- Canonical slug / text domain: `sucheckout-upayments`
- PHP namespace root: `Simplixi\SUCheckout\UPayments`
- Maintainer: **Simplix Innovations**
- Provider: **UPayments**
- Development version: **0.1.0**

## Current program position

- Repository Foundation & Readiness — **DONE / VERIFIED**
- Phase 0 release identity/updater ownership — **DONE / VERIFIED**
- Phase 9I historical token-identity migration — **DONE / VERIFIED**
- Provider Contract & Payment Lifecycle — **DONE / VERIFIED**
- Security Threat-Model Closure — **DONE / VERIFIED**
- Architecture A1-A5 — **DONE / VERIFIED**
- Quality Platform Q1-Q19 — **DONE / VERIFIED; closed at Q19**
- Quality Platform Q16 migration-core analysis: **DONE / VERIFIED**
- Quality Platform Q17 payment-runtime analysis: **DONE / VERIFIED**
- Quality Platform Q18 Blocks availability enforcement: **DONE / VERIFIED**
- Quality Platform Q19 subscription product-eligibility consistency: **DONE / VERIFIED**
- Enterprise Task 1 quality closeout — **DONE / VERIFIED**
- Enterprise Task 2 real compatibility matrix — **DONE / VERIFIED**
- Enterprise Task 3 declarations — **DONE / VERIFIED**
- Enterprise Task 4 bounded provider sandbox — **DONE / VERIFIED**
- Enterprise Task 5 deterministic release artifact — **DONE / VERIFIED**
- Enterprise Task 6 feature/operations boundaries — **DONE / VERIFIED**
- Enterprise Task 7 existing-install/release identity — **DONE / VERIFIED**
- Enterprise Task 8 release-candidate closeout — **DONE / VERIFIED**
- Current engineering state — **SUCheckout identity migration DONE / VERIFIED on merged main; owner rename/local acceptance/release administration remain**
- Public stable release — **NO**
- WordPress.org release — **NO**

Never invent Q20. Live GitHub evidence wins over recorded milestone SHAs.

## Current certified SUCheckout baseline

- PR #58 established the canonical SUCheckout identity and merged as `6aabc4fcb0606567a11637ea07fe081fed4c7f85`;
- PR #59 reconciled living owner/control docs and merged as `9591c431e1eb56fe40ca60147afdf9f3f909a212`;
- PR #61 completed the remaining retired pre-rebrand first-party naming/control-prefix migration and merged as current runtime-bearing `main` `efe937c67343242b7ccf3396a67b3cf2ce35ebac`;
- Quality #781, Compatibility #309 (**16/16**), Release Artifact #258, Provider Sandbox #221, WordPress.org Submission Check #116 and CodeQL/main-security #595 — **SUCCESS**;
- official packaged Plugin Check — **0 blocking errors**.

Engineering work for the SUCheckout identity/naming migration is closed. Next actions are owned by `docs/project/OWNER-HANDOFF.md`; do not invent another numbered Q phase.


## First-stable identity decision

Task 7 proves the physical main file must not be renamed casually. The approved SUCheckout package therefore uses:

- physical main file `UPayments.php`;
- canonical basename `sucheckout-upayments/UPayments.php`;
- canonical text domain `sucheckout-upayments`;
- legacy basename `simplixpay-upayments/UPayments.php` only as migration/rollback evidence.

A future physical rename to `sucheckout-upayments.php` remains separately gated. Do not search/replace protected provider/persisted `upayments` identities.

Protected runtime/persisted identities include gateway/payment method `upayments`, settings `woocommerce_upayments_settings`, Blocks/Store API identity `upayments`, callback `wc_upayments`, `_upay_*` metadata, token identity secret/provenance/scope/generation, scheduler/billing identities and historical order payment-method values.

## Verified enterprise surfaces

### Compatibility

Permanent 16-cell real WordPress/WooCommerce/PHP × legacy/HPOS matrix, including Classic/Blocks registration and real Woo CRUD.

### Provider

One bounded public-sandbox Charge initialization using only the documented public test credential. No payment completion/card data/status loop/refund/card mutation/recurring mutation/production credential.

### Artifact

Git-HEAD-bound deterministic ZIP + checksum + per-file manifest; independent source-byte verification; tamper rejection; dirty worktree/index isolation; packaged legacy/HPOS smoke.

### Feature/operations

Real-runtime bounded evidence for saved-card/token provenance, subscription eligibility/pre-dispatch, the existing one-additional-merchant allocation, and non-destructive activation/deactivation/uninstall retention. Task 6 found/fixed the bootstrap-history pagination defect.

### Upgrade

Current/floor same-basename upgrade, rollback, deactivate/reactivate, merchant/payment/token/subscription/cron/callback retention and duplicate-root characterization.

## Historical Task 8 closeout — DONE / VERIFIED

- final reviewed head: `5a24944617f7ee482c381e5e899f687b77d81d09`;
- exact-head Quality #552/H12, Compatibility #80 (16/16), Release Artifact #34, Provider Sandbox #12 and CodeQL: **SUCCESS**;
- reserved final Codex review: **completed**;
- valid final-review P2: **reproduced, fixed and permanently regression-guarded**;
- squash merge: `2ddb1790fead37c6055256847dc7c827e165af4a`;
- post-merge Quality #553, Compatibility #81, Release Artifact #35, Provider Sandbox #13 and CodeQL/main-security #358: **SUCCESS**;
- open issues: **0**;
- open PRs: **0**;
- public stable release: **NO — separate owner decision**.

PR #53 is closed unmerged and explicitly superseded by Task 7 PR #52. Its remote branch is not an active implementation line.

## External/manual and unsupported boundaries

External/manual:

- production merchant payment completion;
- real wallet/account/device completion;
- WPML/WCML/multilingual/multicurrency/RTL;
- browser/device/theme/accessibility;
- representative-store performance/load;
- penetration testing / PCI / legal-compliance attestations;
- live subscription auto-deduction;
- provider webhook signature until a stable published contract exists.

Unsupported:

- automatic Woo refunds;
- arbitrary marketplace multi-split (only one additional merchant is certified).

## Merge discipline

External AI/bot output is evidence to verify, never authority. Task 8's reserved final whole-plugin Codex review is complete and must not be repeated as a second Task 8 review. Future work must still pin merge decisions to exact heads, resolve valid review findings and verify `main` after merge.
