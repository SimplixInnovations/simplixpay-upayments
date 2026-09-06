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
| Production maturity | **Pre-release / release-candidate qualification** |
| Stable SimplixPay release | **NO — no public 1.0/tag/release yet** |
| WordPress.org release | **NO** |
| Repository Foundation & Readiness | **DONE / VERIFIED** |
| Phase 0 — release identity/updater ownership | **DONE / VERIFIED** |
| Phase 9I — historical token-identity migration | **DONE / VERIFIED** |
| Provider Contract & Payment Lifecycle | **DONE / VERIFIED** |
| Security Threat-Model Closure | **DONE / VERIFIED** |
| Architecture & Code-Quality Foundation | **DONE / VERIFIED (A1-A5)** |
| Quality Platform Q1-Q19 | **DONE / VERIFIED — numbered sequence closed** |
| Enterprise Task 1 — quality closeout | **DONE / VERIFIED** |
| Enterprise Task 2 — executable compatibility matrix | **DONE / VERIFIED** |
| Enterprise Task 3 — support metadata + Woo declarations | **DONE / VERIFIED** |
| Enterprise Task 4 — bounded provider sandbox | **DONE / VERIFIED** |
| Enterprise Task 5 — deterministic release artifact | **DONE / VERIFIED** |
| Enterprise Task 6 — feature/operations boundaries | **DONE / VERIFIED** |
| Enterprise Task 7 — existing-install identity decision | **DONE / VERIFIED** |
| Current program gate | **Enterprise Release Candidate Closeout — CURRENT / FINAL VERIFICATION** |

No Q20 is justified. The numbered Quality Platform remains permanently closed at Q19.

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

## Task 8 — current closeout contract

Enterprise Release Candidate Closeout must complete on one immutable candidate head:

1. reconcile all living state/readiness/public documentation;
2. verify repository hygiene and remove/supersede unjustified work;
3. pass full Quality/H12, 16-cell Compatibility, deterministic Release Artifact including upgrade cells, bounded Provider Sandbox, CodeQL and locked dependency audit;
4. classify all evidence that is genuinely external/manual rather than pretending repository automation proves it;
5. run the reserved **one final whole-plugin Codex review** after primary evidence is green and resolve every valid finding;
6. squash-merge the exact verified head and repeat required verification on canonical `main`.

Task 8 does **not** itself publish a public 1.0, GitHub Release or WordPress.org package. Publication/version promotion is a separate owner release action after the release-candidate closeout is verified.

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
