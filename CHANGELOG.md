# Changelog

All notable **SimplixPay for UPayments** product and engineering changes are documented here.

The independent Simplix development line remains `0.x`. `1.0.0` is reserved for an owner-approved first stable publication after Enterprise Release Candidate Closeout is DONE / VERIFIED. No changelog entry alone implies a public release.

## [Unreleased]

### Enterprise Release Candidate Closeout — CURRENT / FINAL VERIFICATION

- Reconciles living README/status/compatibility/roadmap/handoff/release records to the verified Tasks 1–7 state.
- Replaces stale Enterprise Compatibility gate ownership with **Enterprise Release Candidate Closeout** governance.
- Requires one exact final head to pass Quality/H12, permanent 16-cell Compatibility, deterministic Release Artifact including upgrade cells, bounded Provider Sandbox and CodeQL/dependency audit.
- Classifies production merchant payment completion, wallets, WPML/WCML/multilingual/RTL, browser/device/accessibility, representative performance and penetration/PCI/compliance as external/manual evidence rather than fabricating CI claims.
- Retains automatic Woo refunds and arbitrary marketplace multi-split as explicitly unsupported.
- Reserves one final whole-plugin Codex review until primary closeout evidence is green.
- Does not create a public 1.0 tag, GitHub Release or WordPress.org publication.

### Enterprise Task 7 — existing-install upgrade and release identity — DONE / VERIFIED

- Added permanent current and floor real-runtime installed-package upgrade cells.
- Verified same-basename active force-upgrade, merchant-settings retention, historical `upayments` payment/provider/token/subscription state, `wc_upayments` callback continuity and `upay_process_subscriptions` schedule continuity.
- Verified explicit deactivate/reactivate, rollback to a prior package and return to current.
- Verified a duplicate-root package is a distinct inactive WordPress plugin identity.
- Proved a controlled `UPayments.php` → `simplixpay-upayments.php` direct rename does **not** transfer active WordPress plugin identity; runtime becomes inactive until the historical basename returns.
- Locked the first-stable decision to main file `UPayments.php`, basename `simplixpay-upayments/UPayments.php` and text domain `upayments`.
- Kept future `simplixpay-upayments.php` / `simplixpay-upayments` targets as explicit later migrations requiring dedicated upgrade/i18n evidence.
- Final PR #52 head `dd550eb6af86262aabfd50479407903172327726` passed Release Artifact #26, Quality #544/H12, Compatibility #72 (16/16), CodeQL and zero unresolved review threads; squash merge `02b8d1c2851faabe020f23bbe84ebcca43a4827d` passed post-merge Quality #545, Compatibility #73, Release Artifact #27 and CodeQL #349.

### Enterprise Task 6 — feature and operations boundaries — DONE / VERIFIED

- Added permanent real-runtime saved-card/token provenance certification: guest rejection, canonical identity establishment, exact membership, foreign-card rejection and malformed-provenance fail-closed behavior.
- Added subscription eligibility/pre-dispatch certification for product opt-out, mixed orders, guests and strict plan/interval handling.
- Added bounded one-additional-merchant runtime allocation certification.
- Added non-destructive lifecycle/data-retention certification across deactivate/reactivate/uninstall-hook execution, including canonical identity secret/provenance retention.
- The real-runtime challenge discovered and fixed a production `CustomerTokenIdentity::inspect_bootstrap_history()` pagination defect that could falsely return `legacy_migration_required` after querying an out-of-range page.
- Final PR #51 head `355a871636f2df00c0bd7357a810289be284b58c` passed Compatibility #67 (16/16), Quality #539/H12, Release Artifact #21 and CodeQL; squash merge `6c19dbcfab607f81c4ff28f7bd088a87575adbf3` repeated permanent checks on `main`.

### Enterprise Task 5 — deterministic installable release artifact — DONE / VERIFIED

- Added `scripts/build-release.sh`, `scripts/verify-release.sh` and strengthened `.distignore`.
- Package input is derived exclusively from the exact Git `HEAD` tree/blobs, not mutable worktree or staged-index state.
- Added deterministic path ordering/timestamps/modes/compression, ZIP SHA-256 sidecar and sorted per-file manifest.
- Added independent PHP `ZipArchive` inspection, explicit release-path allowlist and exact ZIP-path/source-byte binding.
- Added a negative self-consistent/rehashed tampered-ZIP probe and dirty worktree/index isolation proof.
- Added packaged real WordPress/WooCommerce legacy+HPOS activation/Blocks/order-CRUD smoke.
- Final PR #50 head `27fb42b32051e4cd18db0c0231f782d3b4a8e932` passed the permanent release harness at **76 PASS / 0 FAIL**, Quality/H12, Compatibility 16/16 and CodeQL; squash merge `54b1fbcc280b92372bd93baf929d6a746cfd3959` repeated checks on `main`.

### Enterprise Task 4 — bounded UPayments public sandbox — DONE / VERIFIED

- Added a permanent provider sandbox workflow using only UPayments' documented public non-whitelabel test bearer token.
- Performs exactly one bounded sandbox Charge initialization and validates endpoint/TLS/HTTP 201/schema plus production redirect normalization.
- Never follows the payment link, enters card data, completes/captures payment, polls status, refunds, saves/retrieves cards, auto-deducts or uses production merchant credentials.
- PR #49 established the first verified public-sandbox Charge initialization evidence.

### Enterprise Tasks 2–3 — runtime compatibility and public declarations — DONE / VERIFIED

- Added a permanent 16-cell real WordPress/WooCommerce/PHP × legacy/HPOS runtime matrix.
- Reproduced and fixed a real activation fatal caused by object-valued malformed `woocommerce_upayments_settings`.
- Verified Classic gateway registration, standard Cart/Checkout Blocks registration/availability and real Woo order CRUD under both storage modes.
- Derived public support headers only from the matrix: WordPress 6.9 minimum / 7.1 tested, WooCommerce 10.8 minimum / 11.1 tested, PHP 7.4 minimum.
- Added runtime-registry-verified compatibility declarations for `cart_checkout_blocks` and `custom_order_tables`.

### Enterprise Task 1 — numbered Quality Platform closeout — DONE / VERIFIED

- Closed the Full Automated Quality Platform at Q19; no Q20 is justified by current evidence.
- Preserved all Q1-Q19 and H12 regressions as permanent controls while transitioning later work to named enterprise certification/release programs.

### Full Automated Quality Platform Q1-Q19 — DONE / VERIFIED

- Q1 established the locked development-only Composer/quality toolchain, syntax lanes, analyzers, coding standards, dependency audit and protected H12 aggregation.
- Q2-Q19 progressively added bounded baseline-free analysis/characterization and fixes for CheckoutPayload, concurrency, authenticated status, availability, gateway settings, public status authorization, release identity, migration settings/bootstrap/CLI/admin/core, subscription composition/product/presentation, payment runtime, Blocks availability and subscription product eligibility.
- Q19 removed arbitrary hard-coded subscription-product restrictions and aligned Classic/Store API product opt-out semantics before provider transport.
- The numbered sequence is permanently closed at Q19.

### Architecture & Code-Quality Foundation A1-A5 — DONE / VERIFIED

- Introduced incremental `Simplix\Pay\UPayments` modules for provider endpoint/mode resolution, payment-method availability, gateway settings/admin presentation, subscription presentation/composition and checkout payload/orchestration.
- Retained characterized legacy public entry points as compatibility adapters and avoided a big-bang runtime rename.

### Security Threat-Model Closure — DONE / VERIFIED

- Closed public order-status IDOR, state-changing subscription GET actions, checkout third-party font/icon trust, broad plain-data output trust and product-meta authorization defense-in-depth gaps.
- Preserved provider-status financial truth, H12 token identity and no-blind-retry payment semantics.
- Automatic refunds, provider webhook signature trust and broad penetration/PCI certification remained deliberately outside this bounded gate.

### Provider Contract & Payment Lifecycle — DONE / VERIFIED

- Established authenticated provider Get Payment Status as ordinary payment financial truth with strict host/schema/order/reference/currency/amount binding.
- Added Woo `payment_complete()` CAPTURED semantics, replay/no-resurrection protection, bounded reconciliation and per-order locking.
- Browser/webhook payloads remain non-authoritative and Charge is never blindly retried.

### Phase 9I historical token-identity migration — DONE / VERIFIED

- Added deterministic `CLEAN` / `MIGRATABLE` / `BLOCKED` / `INDETERMINATE` preflight.
- Added locked executor acting only on fresh MIGRATABLE evidence and preserving historical order evidence.
- Added bounded redacted admin/CLI operations and checkpoint/resume behavior.

### Phase 0 release identity/updater ownership — DONE / VERIFIED

- Established independent SimplixPay product/version/repository ownership.
- Removed inherited upstream self-update authority and kept external update channel disabled until release engineering was certified.
- Preserved historical payment/runtime identities and non-destructive uninstall.

### Repository Foundation & Readiness — DONE / VERIFIED

- Established canonical repository identity, governance, required checks, branch/rules/security controls, public documentation and evidence-safe history/provenance.

For exact historical run numbers, SHAs and test counts beyond the release-candidate milestones above, see the immutable records under `docs/project/`, `docs/history/` and Git history.
