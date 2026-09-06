# SimplixPay for UPayments — Clean Chat Handoff

Use this file with root `AGENTS.md`, `docs/project/PROJECT-STATUS.md`, `docs/project/ENTERPRISE-CERTIFICATION.md`, `docs/project/RELEASE-ENGINEERING.md`, the naming standard and the immutable historical phase records.

## Identity

- Repository: `SimplixInnovations/simplixpay-upayments`
- Formal product: **SimplixPay for UPayments**
- Short integration reference: **SimplixPay UPayments**
- Reserved broader family: **SimplixPay**
- Canonical slug: `simplixpay-upayments`
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
- Current gate — **Enterprise Release Candidate Closeout — CURRENT / FINAL VERIFICATION**
- Public stable release — **NO**
- WordPress.org release — **NO**

Never invent Q20. Live GitHub evidence wins over recorded milestone SHAs.

## Canonical base entering Task 8

Task 7 PR #52:

- final head `dd550eb6af86262aabfd50479407903172327726`;
- squash merge `02b8d1c2851faabe020f23bbe84ebcca43a4827d`;
- post-merge Quality #545 — SUCCESS;
- post-merge Compatibility #73 — SUCCESS;
- post-merge Release Artifact #27 — SUCCESS;
- post-merge CodeQL #349 — SUCCESS.

Task 8 branch should descend from that canonical merge or a later independently verified `main`.

## First-stable identity decision

Task 7 proves the first stable must retain:

- main file `UPayments.php`;
- basename `simplixpay-upayments/UPayments.php`;
- text domain `upayments`.

Direct rename to `simplixpay-upayments.php` does not transfer WordPress active-plugin identity. The eventual filename/text-domain targets remain explicit future migrations. Do not search/replace protected `upayments` identities.

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

## Task 8 execution contract

1. reconcile living docs and governance;
2. check issues/PRs/branches and remove or explicitly supersede unjustified work;
3. obtain one exact head passing Quality/H12, 16-cell Compatibility, Release Artifact/upgrade, bounded Provider Sandbox and CodeQL;
4. keep external/manual/unsupported boundaries explicit;
5. only after primary evidence is green, request the reserved final whole-plugin Codex review;
6. independently verify and fix every valid finding, rerunning affected evidence;
7. exact-head squash merge;
8. verify canonical `main` workflows and topology;
9. only then mark Task 8 **DONE / VERIFIED**.

Do not create a public 1.0 tag/GitHub Release/WordPress.org publication as part of engineering closeout unless the owner explicitly authorizes that release action.

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

External AI/bot output is evidence to verify, never authority. The final Codex review is intentionally reserved for Task 8 after primary evidence is green. Pin all merge decisions to the exact head SHA; require zero unresolved valid review threads and verify `main` after squash merge.
