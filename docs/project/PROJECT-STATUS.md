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
| Pre-Phase-0 repository readiness | **FINAL EXTERNAL VERIFICATION** |
| Next runtime-changing phase | **Phase 0 — SimplixPay release identity and updater ownership** |

Phase 0 remains blocked until the final external-only readiness evidence in `REPOSITORY-READINESS.md` is closed.

## Latest independently verified canonical state

Verified on 2026-08-25 before the current final-certification change:

- `main`: `9e77bddfad66b08356be9f0e4dcdf6ebf8350af7`
- `main` tree: `011ebc2af187c86e87b70b2e34ee0cb248b0e829`
- tip commit: GitHub-signature **VERIFIED**
- default branch: `main`
- live remote branch inventory: **`main` only**
- open pull requests: **0**
- repository: standalone (`fork: false`), public, MIT-recognized
- merge policy: squash only; merge commits off; rebase off
- automatic source-branch deletion: on and independently proven by PR #6 cleanup
- `main`: GitHub reports **protected: true**
- Projects/Wiki/Discussions: off
- homepage: `https://simplixi.com`
- description: `SimplixPay for UPayments — independently engineered WooCommerce payment integration by Simplix Innovations.`
- canonical reachable history: five commits only
  1. parentless product root `1caf38410354322c1d842c28a40b0909ba31026d`
  2. governance squash `cc565779c541178f63ae21f8e712f9708035361e`
  3. governance/license squash `c6e8c32044da254654e7a928e80900d943843e7a`
  4. readiness squash `7c86bbc29dd6d311004c0305533d5d731327f05e`
  5. status-sync squash `9e77bddfad66b08356be9f0e4dcdf6ebf8350af7`

PR #6 changed only three control documents and passed the exact-head Quality Gates before merge:

- Governance: **SUCCESS**
- tracked PHP syntax: **SUCCESS**
- H12 PHP: **1927 PASS / 0 FAIL**
- Blocks syntax: **SUCCESS**
- H12 Blocks: **144 PASS / 0 FAIL**

The connected workflow helper enumerates PR-triggered runs but does not reliably list push-triggered `main` runs. Exact green PR-head evidence plus independently verified squash-merge tree/runtime integrity is the accepted repository gate for these documentation-only changes.

## Repository presentation / metadata findings

The public repository presentation is now Simplix-led and clearly identifies UPayments as the provider rather than presenting the provider logo as the repository owner.

The live About metadata is broadly correct, but two current topics overstate uncompleted compatibility certification:

- `hpos-compatible`
- `wpml-ready`

Those topics must be removed before the pre-Phase-0 gate is marked READY. Neutral discovery topics such as `hpos` and `wpml` are acceptable because they describe engineering scope without asserting certification.

## Remaining pre-Phase-0 blockers

Only evidence/actions that cannot be fully verified or mutated through the connected GitHub surface remain:

1. **About-topic claim cleanup** — remove `hpos-compatible` and `wpml-ready` from repository topics.
2. **Local IDE clone convergence** — local `HEAD...origin/main` divergence must be `0 0`, with no uncommitted work.
3. **Root-commit account attribution** — parentless root remains authored as `Simplix Innovations <info@simplixi.com>` but GitHub's commit API still returns `author: null`; ensure `info@simplixi.com` is associated and verified on `SimplixInnovationsAdmin`, then allow contributor statistics to refresh.
4. **Detailed ruleset/security verification** — verify the active `main` ruleset and repository security settings listed in `REPOSITORY-READINESS.md`. `protected: true` alone is insufficient evidence of the individual controls.
5. **Tag/release cleanliness** — confirm the canonical repo has no inherited tags/releases before the Simplix version line is intentionally established.

No runtime/plugin identity change is authorized until those items close.

## Frozen H12 production blob anchors

These remain byte-identical on verified `main`:

- `UPayments.php` — `64c789e81ae4d292ef9b1d7382812c319a44bc25`
- `includes/Token/CustomerTokenIdentity.php` — `85430d37e9baf540842f5655b86ccf0eca3e6aea`
- `includes/class-wc-gateway-upayments-blocks.php` — `813d192d69c069eb7ee11df93acc9dbdf03e270a`
- `includes/Subscription/Cron/Scheduler.php` — `5251866d4df2d1326e7c09f0c8ec1d146c0bb325`
- `includes/Subscription/Cron/CycleClaim.php` — `c34d83e2d77cc65024fe663e4c378cecb2b17347`

The archived H12 engineering changelog remains original blob `8c42bc6fdae163dd4159b8036b05cd2f70cc3d5d`.

## Whole-repository audit status

`REPOSITORY-AUDIT.md` classifies the complete tracked tree. Known inherited/runtime debts are deliberately deferred rather than silently cleaned before characterization, including:

- the large inherited `UPayments.php` bootstrap;
- H12-critical token/subscription classes;
- empty inherited files and duplicate provider assets;
- legacy screenshots and multiple JS paths;
- bundled Plugin Update Checker;
- inherited `uninstall.php` data-deletion behavior;
- absence of the future `src/` + Composer/PSR-4 package structure;
- absence of the future broad PHPUnit/WP/Woo/browser/static-analysis platform.

Those are Phase 0 or later engineering concerns, not pre-Phase-0 repository-governance defects.

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

After every final readiness item is independently verified, Phase 0 will:

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
