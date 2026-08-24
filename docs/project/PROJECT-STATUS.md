# SimplixPay for UPayments — Project Status

**Status document:** canonical living engineering state

**Last updated:** 2026-08-25

**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

**Historical audit archive:** `SimplixInnovations/upayments-woocommerce`

**Provider upstream:** `upaymentskwt/woocommerce`

> Always verify live GitHub state before acting. Recorded SHAs are verified milestones/evidence anchors, not substitutes for a fresh remote check.

## Current program state

| Item | State |
|---|---|
| Product | **SimplixPay for UPayments** |
| Canonical slug | `simplixpay-upayments` |
| Product family reserved for broader future use | **SimplixPay** |
| Production maturity | **Pre-release engineering hardening** |
| Stable SimplixPay release | **NO** |
| WordPress.org release | **NO** |
| H12 token-identity hardening | **DONE / VERIFIED** |
| Repository governance | **DONE / VERIFIED** |
| Pre-Phase-0 repository readiness | **IN PROGRESS — final external cleanup** |
| Next runtime-changing phase | **Phase 0 — SimplixPay release identity and updater ownership** |

## Latest independently verified canonical state

Verified on 2026-08-25 before this status-sync change:

- `main`: `7c86bbc29dd6d311004c0305533d5d731327f05e`
- `main` tree: `b11efcf0d0acf008d2088c67b9975226a72d7e5d`
- tip commit: GitHub-signature **VERIFIED**
- default branch: `main`
- `main`: GitHub reports **protected: true**
- canonical reachable history: four commits only
  1. parentless product root `1caf38410354322c1d842c28a40b0909ba31026d`
  2. governance squash `cc565779c541178f63ae21f8e712f9708035361e`
  3. governance/license squash `c6e8c32044da254654e7a928e80900d943843e7a`
  4. readiness squash `7c86bbc29dd6d311004c0305533d5d731327f05e`
- PR #5 approved head tree equals merged `main` tree exactly: `b11efcf0d0acf008d2088c67b9975226a72d7e5d`
- PR #5 Quality Gates: **SUCCESS**
  - Governance: success
  - tracked PHP syntax: success
  - H12 PHP: **1927 PASS / 0 FAIL**
  - Blocks syntax: success
  - H12 Blocks: **144 PASS / 0 FAIL**
- open pull requests: **0** at verification time
- Dependabot PR #2: closed / not merged / superseded
- Dependabot PR #3: closed / not merged / superseded

The connected workflow-run helper enumerates pull-request-triggered runs, not push-triggered runs. Therefore the verified CI evidence for the final readiness tree is the successful exact PR head plus byte-identical merged tree; a separate main-push run must be checked in GitHub Actions UI if required by the final exit review.

## Repository settings independently verified

The live repository metadata now confirms:

- standalone repository (`fork: false`);
- correct description: `SimplixPay for UPayments — independently engineered WooCommerce payment integration by Simplix Innovations.`;
- homepage: `https://simplixi.com`;
- license recognized by GitHub as **MIT**;
- Issues enabled;
- Projects disabled;
- Wiki disabled;
- Discussions disabled;
- squash merge enabled;
- merge commits disabled;
- rebase merge disabled;
- automatic source-branch deletion enabled;
- `main` reports protected;
- WooCommerce / WordPress / payments / UPayments / SimplixPay / HPOS / WPML topics populated.

The current connector does not expose the active ruleset body or security-analysis/private-vulnerability settings. Required-check names, force-push/deletion rules, secret scanning, push protection and private vulnerability reporting therefore remain **manual-verification items**, not assumed facts.

## Remaining pre-Phase-0 blockers

### 1. Remove three obsolete remote branches

Live branch inventory before the transient status-sync branch:

- `main`
- `phase-0/repository-governance`
- `phase-0/governance-finalize`
- `pre-phase0/repository-readiness`

All three non-main branches are proven safe to delete:

- `phase-0/repository-governance` was PR #1 head `7a81489a16cd0c264f26784d547542dcc2417e19`; its final tree `aa387ff76c300a12933c25932dece75e8def534e` exactly equals squash commit `cc565779c541178f63ae21f8e712f9708035361e` tree.
- `phase-0/governance-finalize` was PR #4 head `5878a165e0352b64c323efb354e2fa5e58348131`; its final tree `bb1bdc29d51a73edcdb1c4da7ca4ba99cede9b80` exactly equals squash commit `c6e8c32044da254654e7a928e80900d943843e7a` tree.
- `pre-phase0/repository-readiness` was PR #5 head `792a0a9f2d995a9c9a1b80f7718e50be2d4396c0`; its final tree `b11efcf0d0acf008d2088c67b9975226a72d7e5d` exactly equals squash commit `7c86bbc29dd6d311004c0305533d5d731327f05e` tree.

The connected GitHub tool has no delete-ref operation, so deletion is the one repository mutation that must be performed externally.

### 2. Reconcile the local IDE clone

A clone from before the canonical history rewrite can show a misleading large divergence such as 1 incoming / 131 outgoing despite no source edits. This cannot be verified remotely. Follow `REPOSITORY-READINESS.md` and require final `HEAD...origin/main` divergence `0 0` before closing readiness.

### 3. Fix root-commit GitHub attribution and allow statistics to refresh

The parentless root commit remains authored as `Simplix Innovations <info@simplixi.com>`, but GitHub's commit API currently returns `author: null` for that commit. Associate and verify `info@simplixi.com` on `SimplixInnovationsAdmin`.

The rewrite occurred on 2026-08-24; GitHub documents that contributor statistics may take about 24 hours after history changes to refresh. Do not rewrite canonical history again merely to change the contributor UI.

### 4. Manually verify ruleset/security details

Confirm in GitHub Settings that the active `main` ruleset requires the intended controls/checks and that repository security settings are enabled as specified in `REPOSITORY-READINESS.md`. The API surface available to this reviewer confirms `protected: true` but does not expose those details.

## Frozen H12 production blob anchors

These remain byte-identical on verified `main`:

- `UPayments.php` — `64c789e81ae4d292ef9b1d7382812c319a44bc25`
- `includes/Token/CustomerTokenIdentity.php` — `85430d37e9baf540842f5655b86ccf0eca3e6aea`
- `includes/class-wc-gateway-upayments-blocks.php` — `813d192d69c069eb7ee11df93acc9dbdf03e270a`
- `includes/Subscription/Cron/Scheduler.php` — `5251866d4df2d1326e7c09f0c8ec1d146c0bb325`
- `includes/Subscription/Cron/CycleClaim.php` — `c34d83e2d77cc65024fe663e4c378cecb2b17347`

The archived H12 engineering changelog remains original blob `8c42bc6fdae163dd4159b8036b05cd2f70cc3d5d`.

## Protected compatibility identities

Do not rename merely for branding:

- WooCommerce gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks/Store API identity `upayments`;
- callback route `wc_upayments`;
- existing `_upay_*` order/user/product metadata;
- `upayments_token_identity_secret_v2` and H12 provenance/scope/generation state;
- `upay_process_subscriptions` and historical cleanup hooks;
- existing billing-attempt table/schema state;
- historical order payment-method values.

See `NAMING-IDENTITY-STANDARD.md` for the full compatibility registry.

## Phase 0 — blocked until readiness closes

Phase 0 is **not authorized to start yet**. After every final readiness item is independently verified, Phase 0 will:

- eliminate the upstream-controlled update path;
- establish independent SimplixPay semantic versioning;
- change public plugin metadata to SimplixPay for UPayments / Simplix Innovations;
- design/test folder + main filename + text-domain transition as an upgrade problem;
- preserve protected persisted/runtime identifiers unless an approved migration changes them;
- add install/update/upgrade/rollback regression evidence.

## Post-Phase-0 program blockers already known

- Phase 9I historical token-identity migration remains open;
- provider contract/payment lifecycle/webhook/status/refund audits remain incomplete;
- security threat-model closure remains incomplete;
- full automated quality platform remains incomplete;
- broad WooCommerce/WordPress/PHP/HPOS/Blocks/WPML/feature/browser/performance certification remains incomplete;
- release packaging and WordPress.org preparation remain incomplete.

## Phase 9I blockers — all 13 remain open

1. Unscoped legacy tokens
2. Current-scope orphan histories
3. Cross-user token conflicts
4. Malformed scoped histories
5. Secret generation mismatches
6. Card-token-only historical identity
7. Prior-scope same-generation histories
8. Non-scalar evidence
9. Orphan metadata
10. >200/incomplete history
11. Unloadable orders
12. Force-refresh failures
13. Malformed-vs-missing secret distinction

## Update rule

Update this file after every independently verified milestone merge or readiness-state change. Never claim a recorded SHA is still live without checking GitHub. Never mark a gate complete from an implementation report alone; verify the exact source, tree/diff, checks and post-merge state.