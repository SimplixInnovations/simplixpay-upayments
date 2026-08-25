# Changelog

All notable **SimplixPay for UPayments** product changes are documented here.

The project is still in pre-release engineering hardening. Entries below are engineering milestones and do not imply a merchant-facing stable release. The independent Simplix development line is `0.x`; `1.0.0` remains reserved for the first release that satisfies the stable-release gates.

## [Unreleased]

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
- Added fail-closed checkpoint semantics: if a result checkpoint cannot be durably written, the page stops and leaves that same user as the retry point; valid identity mutation is not rolled back and executor idempotency makes re-evaluation safe.
- Ensured failed CLI batches emit redacted JSON before terminating non-zero.
- Kept the migration operational surface isolated from provider transport, checkout, Store API, frontend and cron paths.
- Verified preflight PR #11 merge `8cca32819dd165e35efa0fcc5a48bdd551757d8c` with tree `c0af8a2ab1fbd2494f961ee9f924c00aaf519ab0`.
- Verified executor PR #12 merge `708253bd9d0daf217735fbb087b360e8b848136c` with tree `e222a18c9808229fdde79efb42268d8c3fbd33ae`.
- Verified operations PR #13 exact reviewed head `2989862683754f8a8eda8e9d4239ada4a61b23f4`, squash merge `db1c4ea4dab45bc1ffaf4529e0ccb940153cd999`, tree `5bec24ad26c66a504cd0dd609f4311f9e70add76`, VERIFIED GitHub signature and post-merge branch deletion.
- Final implementation-head regression evidence: Phase 0 **35 PASS / 0 FAIL**; Phase 9I preflight **123 PASS / 0 FAIL**; executor **59 PASS / 0 FAIL**; operations **81 PASS / 0 FAIL**; H12 PHP **1927 PASS / 0 FAIL**; H12 Blocks **144 PASS / 0 FAIL**; Governance, tracked PHP syntax and Blocks syntax **SUCCESS**.
- Phase 9I completion certifies the migration system/safety contract, not automatic migration of every merchant installation. Site-specific classification/migration remains an explicit bounded operational action.

### Current program gate

**Provider Contract & Payment Lifecycle — DISCOVERY** is now the active gate.

The next work must compare current exact runtime behavior against current official UPayments documentation and freeze evidence-backed contracts for charge, webhook/status/browser-return truth hierarchy, payment state transitions, reconciliation/idempotency/retry semantics, refund behavior and multi-merchant boundaries before payment-critical refactoring.

### Phase 0 — release identity and updater ownership — DONE / VERIFIED

- Established the active public plugin identity as **SimplixPay for UPayments** by **Simplix Innovations**.
- Established the independent Simplix development version at **0.1.0**.
- Added canonical code-side release identity under `Simplix\Pay\UPayments\Release\Identity` and `SIMPLIXPAY_UPAYMENTS_*` release constants.
- Removed the inherited `upaymentskwt/woocommerce` update authority and all Plugin Update Checker bootstrap logic.
- Removed the bundled `vendor/plugin-update-checker/` dependency.
- Deliberately disabled external self-updates until the physical package/basename migration has its own tested distribution contract.
- Preserved the transitional physical main file `UPayments.php` and runtime/header text domain `upayments`; their eventual `simplixpay-upayments` targets remain explicit upgrade/i18n migrations rather than cosmetic renames.
- Preserved compatibility-sensitive payment identities including gateway ID `upayments`, `woocommerce_upayments_settings`, callback `wc_upayments`, `_upay_*` metadata, H12 token/provenance identities and subscription scheduling/table/schema state.
- Changed uninstall behavior to retain merchant/payment data by default; destructive cleanup now requires a future explicit, separately tested erasure contract.
- Added a permanent Phase 0 release-identity harness to the required Quality Gates workflow.
- Proved red → green characterization: **22 PASS / 13 FAIL** before implementation, then **35 PASS / 0 FAIL** on the final implementation head.
- Retained the frozen H12 regression baseline at **PHP 1927 PASS / 0 FAIL** and **Blocks 144 PASS / 0 FAIL**.
- Squash-merged implementation PR #9 as `678f3bdae32b7a0d5922c6ebb7fa7535ede256dd` with tree `80618e737476a92357bd463f6e1495c364157e83`, followed by independent post-merge verification.

### Repository foundation — DONE / VERIFIED

- Established standalone canonical repository `SimplixInnovations/simplixpay-upayments` from the independently reviewed H12 source tree.
- Preserved the complete pre-product fork/PR history in `SimplixInnovations/upayments-woocommerce` for audit provenance.
- Established the formal product identity **SimplixPay for UPayments** and canonical slug `simplixpay-upayments` while protecting persisted historical UPayments identities.
- Added permanent repository agent instructions, project-control documents, CODEOWNERS, issue/PR governance, security/support policies, MIT license and provenance notice.
- Added GitHub Actions quality gates for governance, tracked PHP syntax, H12 PHP regression and H12 Blocks regression.
- Normalized repository licensing so GitHub recognizes SPDX `MIT`.
- Completed whole-repository readiness auditing, Simplix-led public presentation, protected-branch policy, security controls and contributor/history cleanup.

## Historical engineering record

The large pre-product H12 engineering changelog is preserved byte-for-byte at:

[`docs/history/H12-ENGINEERING-CHANGELOG.md`](docs/history/H12-ENGINEERING-CHANGELOG.md)

It documents engineering corrections made before the standalone SimplixPay product history was established and must not be interpreted as SimplixPay product releases.
