# Changelog

All notable **SimplixPay for UPayments** product changes are documented here.

The project is still in pre-release engineering hardening. Entries below are engineering milestones and do not imply a merchant-facing stable release. The independent Simplix development line is `0.x`; `1.0.0` remains reserved for the first release that satisfies the stable-release gates.

## [Unreleased]

### Architecture A1 — Provider Endpoint/Mode Resolution — DONE / VERIFIED

- Extracted deterministic live/test endpoint resolution to `src/Provider/EndpointResolver.php` while preserving all four public gateway compatibility wrappers and the inherited URL bytes.
- Added the mandatory Provider Endpoints harness; the final exact-head and post-merge result was **49 PASS / 0 FAIL** alongside the complete historical and architecture stack.
- Kept the official provider production-host difference out of this structure-only tranche as a separately researched future runtime migration.
- Exact final PR #21 head `baed693964556120dc7ad07dbc740d3acc1af20f` passed Quality Gates run #152 and clean independent review.
- Squash-merged PR #21 as signed commit `d43d175a1443709d42efabfbe78519a5a84f4dc9`, tree `ddb2ac7cd8b2d4f454867e10bc361fee94dbcf4b`; post-merge run #153 passed and the implementation branch was deleted.

### Security Threat-Model Closure — DONE / VERIFIED

- Closed the public historical order-status IDOR by requiring an UPayments order plus exact logged-in ownership or exact WooCommerce order key; numeric order ID alone is no longer authority and returned state is allowlisted.
- Replaced nonce-bearing subscription pause/resume/unsubscribe GET mutations with exact-owner-bound POST forms/actions, action-specific nonce verification, subscription object preflight and valid transition checks.
- Removed Google Fonts and cdnjs Font Awesome checkout dependencies and replaced classic plus Checkout Blocks chevrons with local presentation.
- Tightened plain provider/order metadata to text escaping, stored settings to attribute escaping, and removed `$_REQUEST` from checkout display markers.
- Added local WooCommerce nonce/post-ID/`edit_post` preconditions before plugin product-meta writes.
- Added permanent `tests/harness/security-threat-model-harness.php` to required Quality Gates; final characterization is **81 PASS / 0 FAIL**.
- Preserved existing provider host/TLS/redirect/Bearer, payment-truth, H12 identity, Phase 9I authorization, subscription no-blind-retry and immutable GitHub Actions-pin controls.
- Fixed one valid automated P2 review finding before merge by covering the Checkout Blocks Font Awesome seam explicitly in the permanent security harness.
- Exact final PR #17 head `fba12225899c3e01d6b23a6bba2f757a3b5f6a4a` passed full merge-ref Quality Gates run #88.
- Squash-merged PR #17 as `01f3fc59eed8641b3e5372558f61a7a0f0cdfac9`, tree `e0027005f059fad03d8c08273b7aac6553c45f53`, with VERIFIED GitHub signature; implementation branch was deleted.
- Post-merge `main` Quality Gates run #89 passed the complete workflow again.
- Explicit non-claims remain: webhook HMAC/signature is provider-document unresolved, automatic refunds are unsupported pending durable idempotency/reconciliation, subscription auto-deduction is not broadly recurring-billing certified, and this is not broad penetration-test/PCI/platform/feature/performance/production certification.

### Current program gate

**Architecture & Code-Quality Foundation — A2** is now the active gate. A1 provider endpoint/mode resolution is DONE / VERIFIED; A2 is limited to the characterized payment-method availability client/cache behind the existing gateway entry point and must not weaken cache isolation, lock/cooldown, provider-schema, fail-closed, Security, Provider, H12 or Phase 9I contracts.

### Provider Contract & Payment Lifecycle — DONE / VERIFIED

- Researched and froze the ordinary-checkout UPayments/WooCommerce lifecycle contract against current official provider and WooCommerce documentation before implementation.
- Added isolated `Simplix\Pay\UPayments\Payment` lifecycle components rather than broadly refactoring the inherited gateway bootstrap.
- Preserved the historical `wc_upayments` route while moving ordinary browser/webhook financial truth to an earlier-priority controller.
- Made callback/browser payload fields non-authoritative; financial transitions require Bearer-authenticated Get Payment Status plus strict order binding.
- Added exact HTTPS UPayments status-host/path validation, redirect prohibition, TLS verification and finite timeout before Bearer credentials can be sent.
- Added a credential/mode-scoped atomic status-query gate at the stricter documented **30 requests/minute** ceiling while provider documentation remains contradictory.
- Added exact binding for `track_id`, `merchant_requested_order_id`, Woo order reference, currency and amount.
- Replaced display-precision monetary comparison with canonical exact decimal equality: trailing-zero equivalents are accepted, but additional fractional value can never round into equality.
- Classified exact provider results into CAPTURED / PENDING / FAILED / CANCELLED / INDETERMINATE; provider `NULL`, Processing-style and unknown future values remain unpaid and fail closed.
- Replaced direct paid-state `update_status()` semantics with WooCommerce `payment_complete($verified_payment_id)` and verified standard transaction-ID/paid-state postconditions before setting `_upay_verified_capture`.
- Prevented duplicate/replayed verified captures from repeating provider calls or payment-complete lifecycle hooks.
- Prevented terminal callback results from downgrading paid orders and prevented refunded orders from being resurrected.
- Added separate unverified and trusted reconciliation cursors; the former exists only for safe retry routing and is promoted only after authenticated rebinding.
- Scoped cursor/reconciliation state to the current `UPayments_order_id`, preventing a later Charge attempt on the same Woo order from inheriting stale attempt state.
- Added bounded deduplicated reconciliation at **60 / 120 / 240 / 480 seconds**, maximum four scheduled attempts, with no Charge retry.
- Added per-order database locking with exact compare-and-swap stale takeover/release semantics to prevent callback/browser/cron lifecycle races.
- Kept callback request parsing conflict-safe across GET/POST, excluded cookies, and removed `$_REQUEST` from the new lifecycle surface.
- Kept automatic WooCommerce refunds intentionally unsupported because UPayments documents asynchronous completion, no refund webhook and no idempotency keys; safe automation requires a later durable refund-intent/idempotency/reconciliation design.
- Froze current multi-merchant support as one additional merchant allocation only; arbitrary marketplace multi-split routing remains uncertified.
- Kept provider webhook HMAC verification explicitly unresolved instead of fabricating a verifier from incomplete public documentation.
- Added permanent Provider Payment Lifecycle and Provider Exact Amount Binding harnesses to required Quality Gates.
- Closed four valid automated review findings before merge: wp_salt/rate-gate seam, first-query transient reconciliation, stale-lock race and amount-rounding mismatch.
- Exact final PR #15 reviewed head `d2b08ebe1e65ad4ea8f4e06b41423e7bd9904fc3` passed Governance, tracked PHP syntax, Phase 0 **35/0**, Phase 9I preflight **123/0**, executor **59/0**, operations **81/0**, Provider Lifecycle **141/0**, Provider Exact Amount **4/0**, H12 PHP **1927/0**, Blocks syntax and H12 Blocks **144/0**.
- Squash-merged PR #15 as `9569e39973a9e94926087738eae06c3846361943`, tree `40ec562674361624c2764263ba55cfba84594955`, with VERIFIED GitHub signature and implementation-branch deletion.
- Post-merge `main` Quality Gates run #71 passed the complete workflow again.

### Phase 9I — historical token-identity migration — DONE / VERIFIED

- Added deterministic read-only migration preflight with exact `CLEAN`, `MIGRATABLE`, `BLOCKED` and `INDETERMINATE` classifications.
- Covered all 13 historical blocker families with explicit fail-closed behavior, including cross-user conflicts, malformed/mismatched scope or secret evidence, incomplete history, unloadable orders and force-refresh uncertainty.
- Kept preflight at zero provider calls and zero identity writes.
- Added a locked migration executor that acts only on fresh `MIGRATABLE` evidence and never fabricates `canonical` / `create_201` provenance.
- Limited migrated historical identity to `legacy_compat` / `legacy_verified_capture` with exact readback and final CLEAN verification.
- Preserved historical order metadata instead of rewriting payment/subscription evidence during migration.
- Added bounded admin and WP-CLI operations with dry-run, explicit execute confirmation, strict user-list/window bounds and no separate credential input surface.
- Added a separate redacted `_simplixpay_upayments_migration_result_v1` operations-result ledger for every processed user, including CLEAN, BLOCKED, INDETERMINATE, dry-run and exception outcomes.
- Added durable resume using credential/mode/list-scoped HMAC batch fingerprints without persisting the API key.
- Added fail-closed checkpoint semantics: failed result persistence stops the page and leaves the same user as the retry point; valid identity mutation is not rolled back and executor idempotency makes re-evaluation safe.
- Kept the migration operational surface isolated from provider transport, checkout, Store API, frontend and cron paths.
- Verified preflight PR #11 merge `8cca32819dd165e35efa0fcc5a48bdd551757d8c` with tree `c0af8a2ab1fbd2494f961ee9f924c00aaf519ab0`.
- Verified executor PR #12 merge `708253bd9d0daf217735fbb087b360e8b848136c` with tree `e222a18c9808229fdde79efb42268d8c3fbd33ae`.
- Verified operations PR #13 merge `db1c4ea4dab45bc1ffaf4529e0ccb940153cd999` with tree `5bec24ad26c66a504cd0dd609f4311f9e70add76` and VERIFIED GitHub signature.
- Phase 9I completion certifies the migration system/safety contract, not automatic migration of every merchant installation.

### Phase 0 — release identity and updater ownership — DONE / VERIFIED

- Established the active public plugin identity as **SimplixPay for UPayments** by **Simplix Innovations**.
- Established independent development version **0.1.0**.
- Added canonical release identity under `Simplix\Pay\UPayments\Release\Identity`.
- Removed inherited `upaymentskwt/woocommerce` update authority and bundled Plugin Update Checker.
- Disabled external self-updates pending a tested distribution/basename contract.
- Preserved transitional `UPayments.php`, `upayments` text domain and compatibility-sensitive payment identities.
- Changed uninstall behavior to retain merchant/payment data by default.
- Added permanent Phase 0 release-identity characterization: **35 PASS / 0 FAIL** with H12 PHP **1927/0** and Blocks **144/0**.

### Repository foundation — DONE / VERIFIED

- Established standalone canonical repository `SimplixInnovations/simplixpay-upayments` with preserved historical audit provenance.
- Established formal product identity **SimplixPay for UPayments** and canonical slug `simplixpay-upayments` while protecting persisted historical UPayments identities.
- Added permanent repository instructions, project-control documents, CODEOWNERS, issue/PR governance, security/support policies, MIT license and provenance notice.
- Added required GitHub Actions quality gates and protected-branch/security controls.

## Historical engineering record

The large pre-product H12 engineering changelog is preserved byte-for-byte at:

[`docs/history/H12-ENGINEERING-CHANGELOG.md`](docs/history/H12-ENGINEERING-CHANGELOG.md)

It documents engineering corrections made before the standalone SimplixPay product history was established and must not be interpreted as SimplixPay product releases.
