# Changelog

All notable **SimplixPay for UPayments** product changes are documented here.

The project is still in pre-release engineering hardening. Entries below are engineering milestones and do not imply a merchant-facing stable release. The independent Simplix development line is `0.x`; `1.0.0` remains reserved for the first release that satisfies the stable-release gates.

## [Unreleased]

### Architecture A3 — Gateway Settings/Admin Presentation — DONE / VERIFIED

- Extracted the complete characterized settings schema, validation, one-row allocation renderer and admin assets to `src/Admin/GatewaySettings.php` behind all legacy public gateway wrappers.
- Strengthened the permanent Gateway Settings gate with a frozen complete 21-field schema fixture and exact asset-registration tuples; final result **90 PASS / 0 FAIL**.
- Exact final PR #23 head `85028cfb4431cc29820eaca4e254bf6c87daa378` passed Quality Gates #158 and clean independent review.
- Squash-merged PR #23 as signed commit `6291196b35a952ea974549d1aa6d6ae9bbcc64dc`, tree `a7f66ee6cf8c9d5324a0ae77b8c61e69e87bdff7`; post-merge run #159 passed and the implementation branch was deleted.

### Architecture A4 — Subscription Presentation Boundary — DONE / VERIFIED

- Added `Simplix\Pay\UPayments\Subscription\Composition` and `Presentation` for the characterized product/admin/My Account hook and rendering surface while retaining all named global and public gateway compatibility wrappers.
- Kept the customer mutation handler, scheduler, cycle-claim journal, billing table, Charge/auto-deduct dispatch and protected metadata outside the presentation module.
- Added the mandatory Architecture Subscription Presentation regression gate, strengthened it to **75 PASS / 0 FAIL**, and reduced the exact `UPayments.php` ratchet to **205,702 bytes**.
- Exact final PR #24 head `2a2c6a4c67775b6614297d2c0150f3ca61220498` passed Quality Gates #164 and clean independent review.
- Squash-merged PR #24 as signed commit `d24b83356cc766f82c3ad9e529d3ec3f4194e887`, tree `f74899b93f493be872e0ce993e30079d0223dc7b`; post-merge run #165 passed and the implementation branch was deleted.

### Architecture A5 — Checkout Payload/Orchestration Core — DONE / VERIFIED

- Added pure `Simplix\Pay\UPayments\Payment\CheckoutPayload` and bounded `CheckoutOrchestrator` services behind the public legacy `process_payment()` compatibility entry point.
- Preserved protected request-body and provider-transport override seams via gateway-scoped closures, along with strict decimal/payload, H12 saved-card, single Charge dispatch, redirect and metadata behavior.
- Added the mandatory Architecture Checkout Orchestration gate and reduced the exact `UPayments.php` ratchet to **88,839 bytes**.
- Exact final PR #25 head `997e18d8eb6264a84c6a9a35158213d3d655e6b3` passed Quality Gates #173 and clean independent review.
- Squash-merged PR #25 as signed commit `3223a882867634a2ba7588d7afbd2b2e4b4c21e4`, tree `392b73425fa3219b6414a0984136b92c8ef77576`; post-merge run #174 passed and the implementation branch was deleted.

### Full Automated Quality Platform — Q1 Foundation

- Added a canonical development-only Composer manifest and committed lockfile with plugin execution disabled and no production package dependencies.
- Added PHPUnit pure-service tests, baseline-free PHPStan scope, risk-focused PHPCS/WPCS checks, locked dependency auditing and declared PHP-floor syntax CI for distributed PHP; development-only tests remain on the PHP 8.2 regression runtime.
- Made the protected H12 check an always-running aggregator that explicitly rejects failed or skipped Composer-quality and syntax prerequisites.
- Added the permanent Quality Platform Foundation regression gate while retaining every historical and architecture harness.
- Exact final PR #26 head `936e4630c83f7a92cbc4c77f061626e2b0c0c800` passed Quality Gates #177 and clean independent review.
- Squash-merged PR #26 as commit `9b3ead774a5a9bc2ac0f3b3ad754b2d99053f362`, tree `473543cd08515eedd764a4b1ef7b6581590d13a1`; post-merge run #178 passed and the implementation branch was deleted.

### Full Automated Quality Platform — Q2 Checkout Payload Analysis — DONE / VERIFIED

- Expanded PHPUnit boundary characterization and baseline-free PHPStan level 5 scope into `CheckoutPayload` without changing observable payment behavior or tool versions.
- Final exact-head evidence: PHPUnit **21 tests / 126 assertions**, Q1 **74/0**, Q2 **64/0**, PHPStan/PHPCS/audit clean and every historical/architecture/H12 regression green.
- Exact final PR #28 head `c2c30f90688747a523301cb776ed920ef39063f3` passed Quality Gates #182 and clean independent re-review.
- Squash-merged PR #28 as `356680b9fe8a2724e778d40386ca182247715249`, tree `3550fdbb0810af26808851e24e39a6130725e8db`; post-merge run #183 passed and the implementation branch was deleted.

### Full Automated Quality Platform — Q3 Payment Concurrency Analysis — DONE / VERIFIED

- Expanded baseline-free PHPStan/PHPCS and deterministic PHPUnit characterization into `StatusRateGate` and `OrderLock`.
- Froze exact 30-per-minute credential/mode-scoped option slots plus exact-record compare-and-swap order-lock takeover/release without changing executable runtime behavior.
- Exact final PR #29 head `e08be468b5453524996c525860c12d5619081132` passed Quality Gates #188 and clean independent exact-head review; Q1 was **74/0**, Q2 **64/0**, Q3 **69/0** and PHPUnit **31 tests / 220 assertions**.
- Squash-merged PR #29 as `30e99a6a456b72709c87e442b8437301ba64e99b`, tree `703a56c03e95862b8b4807d9a1ea28e2e3e201dd`; post-merge run #189 passed and the implementation branch was deleted.

### Full Automated Quality Platform — Q4 Authenticated Status Analysis

- Expands baseline-free PHPStan/PHPCS and deterministic PHPUnit characterization into `StatusVerifier`.
- Freezes exact UPayments status destination validation before rate/Bearer use, hardened no-redirect/TLS HTTP handling and exact authenticated order/transaction binding.
- Adds bounded development-only WordPress HTTP fixtures and a permanent Q4 regression harness; no executable payment behavior, runtime dependency or certification claim is introduced.

### Architecture A2 — Payment-Method Availability Client/Cache — DONE / VERIFIED

- Extracted the characterized availability client/cache to `src/Provider/PaymentMethodAvailability.php` behind public `getUpayPaymentMethods()` while preserving cache identity, site/mode locking, the 65-second durable gate, strict provider normalization and fail-closed presentation.
- Added the mandatory Payment-Method Availability harness; the final exact-head and post-merge result was **102 PASS / 0 FAIL** alongside the complete historical and architecture stack.
- Exact final PR #22 head `bdb627520aa28e71b69a91f8ef71d04d257a3ad8` passed Quality Gates run #155 and clean independent review.
- Squash-merged PR #22 as signed commit `f85894271e8f991e77a8e6a2b306f4d191483bbd`, tree `1addbcc02e0d30f57a948cafd8111fb94e60c4da`; post-merge run #156 passed and the implementation branch was deleted.

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

**Full Automated Quality Platform — Q4** is now the active gate. Architecture A1-A5 and Quality Platform Q1-Q3 are DONE / VERIFIED; Q4 is limited to StatusVerifier authenticated-transport/binding characterization and baseline-free static-analysis expansion and does not certify platforms or alter payment runtime contracts.

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
