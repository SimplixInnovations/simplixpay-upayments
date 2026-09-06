# SimplixPay for UPayments — Project Status

**Status document:** canonical living engineering state
**Last updated:** 2026-09-06
**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

> Live GitHub/source evidence wins over recorded SHAs. Historical phase records preserve what was true at their close; this file owns the current state.

## Current program state

| Item | State |
|---|---|
| Product | **SimplixPay for UPayments** |
| Canonical slug | `simplixpay-upayments` |
| Current development version | **0.1.0** |
| Production maturity | **Pre-release / enterprise-qualified release candidate** |
| Stable SimplixPay release | **NO — no public 1.0/tag/release yet** |
| WordPress.org release | **NO** |
| Repository Foundation & Readiness | **DONE / VERIFIED** |
| Phase 0 — release identity/updater ownership | **DONE / VERIFIED** |
| Phase 9I — historical token-identity migration | **DONE / VERIFIED** |
| Provider Contract & Payment Lifecycle | **DONE / VERIFIED** |
| Security Threat-Model Closure | **DONE / VERIFIED** |
| Architecture & Code-Quality Foundation | **DONE / VERIFIED (A1-A5)** |
| Quality Platform Q16 migration-core analysis | **DONE / VERIFIED** |
| Quality Platform Q17 payment-runtime analysis | **DONE / VERIFIED** |
| Quality Platform Q1-Q19 | **DONE / VERIFIED — numbered sequence closed** |
| Enterprise Task 1 — quality closeout | **DONE / VERIFIED** |
| Enterprise Task 2 — executable compatibility matrix | **DONE / VERIFIED** |
| Enterprise Task 3 — support metadata + Woo declarations | **DONE / VERIFIED** |
| Enterprise Task 4 — bounded provider sandbox | **DONE / VERIFIED** |
| Enterprise Task 5 — deterministic release artifact | **DONE / VERIFIED** |
| Enterprise Task 6 — feature/operations boundaries | **DONE / VERIFIED** |
| Enterprise Task 7 — existing-install identity decision | **DONE / VERIFIED** |
| Enterprise Task 8 — release-candidate closeout | **DONE / VERIFIED** |
| Current engineering state | **Enterprise release candidate qualified — awaiting owner release decision** |

No Q20 is justified. The numbered Quality Platform remains permanently closed at Q19. No active engineering gate is open.

## Preserved Quality Platform closure evidence

These are historical closure checkpoints protected by the permanent Q16/Q17 regressions. They do not own the current program gate.

### Q17 payment-runtime analysis

- final head: `2c5d8e9213086c88147f5d1d26247d58f1cbc81b`;
- tree: `4dae7ad7db04fcd1466389d304e661ac0666983f`;
- squash merge: `570dbf3501b359b16767d070d18c25a67a0c24fe`;
- Quality Gates run #414 and post-merge Quality Gates run #415: **SUCCESS**;
- PHPUnit: **172 tests / 1053 assertions**;
- Q17 Payment Runtime Analysis: **97/0**.

### Q16 migration-core analysis

- final head: `3cff2fcc64053d79be7427696c86039f1b52bbfd`;
- tree: `b9cc6eafb3c7f8df36b9c5db8b2e45bb330688d2`;
- squash merge: `06a9ebd732c7cc3f062d4bb361aaef4054a1dfa3`;
- Quality Gates run #315 and post-merge Quality Gates run #316: **SUCCESS**;
- PHPUnit: **160 tests / 987 assertions**;
- Q16 Migration Core Analysis: **120/0**;
- implementation branch `quality/migration-core-analysis`: **deleted after verified merge**.

## Verified enterprise evidence through Task 7

### Runtime compatibility

The permanent compatibility workflow has verified **16/16** real WordPress/WooCommerce/PHP × legacy/HPOS cells:

- WordPress: 6.9.7, 7.0.4, 7.1 in the exact supported combinations;
- WooCommerce: 10.8.1, 11.0.1, 11.1.0;
- PHP runtime cells: 7.4, 8.3, 8.4;
- Classic gateway ID `upayments`;
- Cart/Checkout Blocks registration/availability;
- legacy and HPOS authoritative Woo order CRUD;
- declared compatibility for `cart_checkout_blocks` and `custom_order_tables`.

Public support headers remain matrix-derived: WordPress 6.9 minimum / 7.1 tested, WooCommerce 10.8 minimum / 11.1 tested, PHP 7.4 minimum.

### Provider sandbox

PR #49 established one bounded public UPayments sandbox Charge-initialization smoke using the provider-documented public test token only. It verifies HTTPS endpoint, transport, HTTP 201/schema and normalized HTTPS payment-link output. It **does not** follow the payment link, enter card data, capture a payment, poll status, refund, save/retrieve a card, auto-deduct, or use production credentials.

### Deterministic release artifact

PR #50 established a Git-HEAD-bound deterministic ZIP, SHA-256 sidecar, per-file manifest, independent ZIP/source verification, dirty-worktree/index isolation, tamper rejection, and packaged real WordPress/WooCommerce legacy+HPOS smoke.

### Feature and operations certification

PR #51 permanently verifies bounded real-runtime behavior for saved-card/token provenance, subscription eligibility/pre-dispatch, the existing one-additional-merchant allocation, and non-destructive activation/deactivation/uninstall retention. It also fixed a real `inspect_bootstrap_history()` pagination defect discovered by the runtime matrix.

Live saved-card mutation, recurring provider deduction, arbitrary marketplace split routing and destructive data erasure remain outside automated certification.

### Existing-install / release identity

PR #52 proved safe same-basename upgrade, rollback, deactivate/reactivate, data retention, callback/cron continuity and duplicate-package characterization in current and floor runtime cells.

The controlled direct rename from `UPayments.php` to `simplixpay-upayments.php` **failed** the active-install migration contract: WordPress retained the historical basename in `active_plugins`, the target basename was inactive, and the runtime did not load. Therefore the first stable release intentionally retains:

- main file: `UPayments.php`;
- plugin basename: `simplixpay-upayments/UPayments.php`;
- text domain: `upayments`.

The eventual canonical filename/text-domain targets remain future migrations requiring their own upgrade/i18n evidence.

## Canonical Task 7 merge and post-merge evidence

Task 7 PR #52 final head: `dd550eb6af86262aabfd50479407903172327726`
Squash merge: `02b8d1c2851faabe020f23bbe84ebcca43a4827d`

Post-merge `main` passed:

- Quality Gates #545 — **SUCCESS**;
- Compatibility Certification #73 — **SUCCESS**;
- Release Artifact #27 — **SUCCESS**;
- CodeQL main analysis #349 — **SUCCESS**.

## Task 8 — DONE / VERIFIED

PR #54 completed the Enterprise Release Candidate Closeout.

Final exact reviewed head:

- `5a24944617f7ee482c381e5e899f687b77d81d09`;
- Quality Gates #552 including H12 — **SUCCESS**;
- Compatibility Certification #80 — **16/16 SUCCESS**;
- Release Artifact #34 — **SUCCESS**, including deterministic build, packaged legacy/HPOS and current/floor upgrade-identity cells;
- Provider Sandbox #12 — **SUCCESS**;
- locked dependency audit — **SUCCESS**;
- CodeQL — **SUCCESS**;
- unresolved review threads — **0**.

The reserved final whole-plugin Codex review found one valid P2: Governance omitted stale-current rejection guards for Q4 and Q15-Q19. The finding was independently reproduced, fixed on the final head, and permanently regression-guarded by Q19's complete Q1-Q19 guard assertion.

Exact squash merge:

- `2ddb1790fead37c6055256847dc7c827e165af4a`.

Post-merge canonical `main` passed:

- Quality Gates #553 — **SUCCESS**;
- Compatibility Certification #81 — **16/16 SUCCESS**;
- Release Artifact #35 — **SUCCESS**;
- Provider Sandbox #13 — **SUCCESS**;
- CodeQL/main-security #358 — **SUCCESS**;
- every check attached to the merge SHA — **SUCCESS**;
- open issues — **0**;
- open PRs — **0**.

PR #53 is closed unmerged and explicitly superseded by Task 7 PR #52; its non-protected remote branch is not an active implementation line and contains no canonical release work.

Task 8 establishes an **enterprise-qualified release-candidate engineering state**. It does **not** publish public 1.0, create a GitHub Release or publish to WordPress.org. Publication/version promotion remains an explicit owner release action.

## Explicit external/manual or unsupported boundaries

These are not repository blockers that should be faked away:

- **Production merchant payment completion:** external/manual merchant-account evidence; repository CI uses no production credential.
- **Wallet completion (Apple Pay / Google Pay / Samsung Pay):** provider/account/device dependent; not broadly certified.
- **WPML/WCML, multilingual, multicurrency and RTL:** dedicated commercial-plugin/runtime validation still required before public claims.
- **Browser/device/theme/accessibility:** manual/real-browser matrix remains separate from server-side certification.
- **Store-specific performance/stability thresholds:** require representative production-like load/data; no universal performance badge is authorized.
- **Penetration testing / PCI / legal/compliance attestation:** organizational/external evidence, not created by source CI.
- **Provider webhook signature:** non-authoritative until UPayments publishes and we implement a stable signature contract.
- **Automatic Woo refunds:** intentionally unsupported pending durable idempotency/reconciliation design.
- **Arbitrary marketplace multi-split:** unsupported; only the existing single additional-merchant allocation is certified.
- **Live subscription auto-deduction:** non-idempotent provider mutation remains fixture-backed/external.

These limitations must remain explicit in release notes and public compatibility claims.

## Canonical records

- `docs/project/ENTERPRISE-CERTIFICATION.md` — exact platform/provider/feature certification;
- `docs/project/RELEASE-ENGINEERING.md` — artifact/upgrade/release-candidate evidence;
- `docs/COMPATIBILITY.md` — public verified/unsupported/external matrix;
- `docs/superpowers/plans/2026-09-06-enterprise-completion.md` — Tasks 1–8 execution plan;
- historical Phase 0/9I/provider/security/architecture/Quality Platform records remain immutable evidence, not current-gate owners.
