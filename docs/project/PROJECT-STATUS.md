# SimplixPay for UPayments — Project Status

**Status document:** canonical living engineering state

**Last updated:** 2026-08-24

**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

**Historical audit archive:** `SimplixInnovations/upayments-woocommerce`

**Provider upstream:** `upaymentskwt/woocommerce`

> Always verify live GitHub state before acting. Recorded SHAs are verified milestones/audit bases, not substitutes for a fresh remote check.

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
| Pre-Phase-0 repository readiness | **IN PROGRESS** |
| Next runtime-changing phase | **Phase 0 — SimplixPay release identity and updater ownership** |

## Last verified canonical milestones

| Milestone | Evidence |
|---|---|
| Clean standalone product root | `1caf38410354322c1d842c28a40b0909ba31026d` — parentless |
| Governance squash merge | `cc565779c541178f63ae21f8e712f9708035361e` — PR #1 |
| Governance/license follow-up | `c6e8c32044da254654e7a928e80900d943843e7a` — PR #4 |
| Current readiness audit base | `c6e8c32044da254654e7a928e80900d943843e7a` |
| Historical H12 merge | `upayments-woocommerce@93e9925247a8bfade626cb822136852fd96eaea2` |
| Last verified H12 CI baseline | PHP **1927 PASS / 0 FAIL**; Blocks **144 PASS / 0 FAIL** |

The current custom H12 harness is a regression baseline. It is not broad WordPress/WooCommerce/PHP/HPOS/Blocks/WPML/browser/performance certification.

## Active objective — pre-Phase-0 readiness

Close every repository-level issue before changing plugin/runtime identity:

1. fully revise README/public presentation and restore verified Woo Agency Partner proof;
2. separate the product changelog from the historical H12 engineering transcript while preserving the latter byte-for-byte;
3. pin third-party GitHub Actions to audited immutable release SHAs;
4. group Dependabot GitHub Actions updates and close superseded bot PRs;
5. refresh current status, roadmap, compatibility posture and new-chat handoff;
6. configure GitHub About, merge policy, branch rules, required checks and security settings;
7. remove obsolete branches;
8. reconcile local clones created before the history rewrite;
9. associate/verify `info@simplixi.com` on `SimplixInnovationsAdmin` and allow GitHub contributor statistics to refresh;
10. verify final readiness `main` and mark this gate **READY / VERIFIED**.

The exact checklist and manual settings are in [`REPOSITORY-READINESS.md`](REPOSITORY-READINESS.md).

## Repository state found by the readiness audit

- Canonical `main` contains only three reachable commits at the audit base: clean root plus two governance squash commits.
- GitHub's contributor UI may still show stale pre-rewrite contributors; GitHub documents a rebuild delay after history changes.
- The root commit author email `info@simplixi.com` is not yet mapped by GitHub's commit API to `SimplixInnovationsAdmin`; the email must be associated/verified on that account rather than rewriting history again.
- Local clones made before the history rewrite can show large false-looking sync divergence such as 1 incoming / 131 outgoing.
- Open Dependabot PRs #2 and #3 propose current major GitHub Action upgrades; their audited releases are being incorporated into the readiness change instead of merging bot-authored commits directly.
- Merged governance branches and Dependabot branches still require deletion because automatic branch deletion is currently disabled.
- GitHub repository settings still require manual hardening because the connected tool does not expose those writes.

## Frozen H12 production blob anchors

These remain the pre-readiness runtime evidence anchors and must stay byte-identical throughout this repository-only work:

- `UPayments.php` — `64c789e81ae4d292ef9b1d7382812c319a44bc25`
- `includes/Token/CustomerTokenIdentity.php` — `85430d37e9baf540842f5655b86ccf0eca3e6aea`
- `includes/class-wc-gateway-upayments-blocks.php` — `813d192d69c069eb7ee11df93acc9dbdf03e270a`
- `includes/Subscription/Cron/Scheduler.php` — `5251866d4df2d1326e7c09f0c8ec1d146c0bb325`
- `includes/Subscription/Cron/CycleClaim.php` — `c34d83e2d77cc65024fe663e4c378cecb2b17347`

They are evidence anchors, not permanent bans on future phase-scoped changes.

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

Phase 0 is **not authorized to start yet**. When the repository-readiness exit gate is verified, Phase 0 will:

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

Update this file after every independently verified milestone merge or readiness-state change. Never claim a recorded SHA is still live without checking GitHub. Never mark a gate complete from an implementation report alone; verify the exact source/diff/checks and post-merge state.
