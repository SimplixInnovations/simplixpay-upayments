# Changelog

All notable **SimplixPay for UPayments** product changes are documented here.

The project is still in pre-release engineering hardening. Entries below are engineering milestones and do not imply a merchant-facing stable release. The independent Simplix development line is `0.x`; `1.0.0` remains reserved for the first release that satisfies the stable-release gates.

## [Unreleased]

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

### Current implementation gate

**Phase 9I — Historical token-identity migration** is next.

It must provide a read-only deterministic preflight that classifies historical evidence as `CLEAN`, `MIGRATABLE`, `BLOCKED` or `INDETERMINATE`; perform zero provider calls/writes during preflight; execute only explicit `MIGRATABLE` cases; never fabricate canonical/Create-201 provenance; and provide bounded, idempotent, resumable operational behavior.

The 13 open Phase 9I blocker classes are tracked in `docs/project/PROJECT-STATUS.md`.

## Historical engineering record

The large pre-product H12 engineering changelog is preserved byte-for-byte at:

[`docs/history/H12-ENGINEERING-CHANGELOG.md`](docs/history/H12-ENGINEERING-CHANGELOG.md)

It documents engineering corrections made before the standalone SimplixPay product history was established and must not be interpreted as SimplixPay product releases.
