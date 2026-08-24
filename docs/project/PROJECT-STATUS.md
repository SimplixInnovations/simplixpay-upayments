# SimplixPay for UPayments — Project Status

**Status document:** canonical living engineering state

**Last updated:** 2026-08-25

**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

**Historical audit archive:** `SimplixInnovations/upayments-woocommerce`

**Provider upstream:** `upaymentskwt/woocommerce`

> Live GitHub/source evidence always wins over recorded SHAs. Recorded SHAs below are verified milestones, not substitutes for a fresh remote check.

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
| Pre-Phase-0 repository readiness | **READY / VERIFIED** |
| Next permitted runtime-changing phase | **Phase 0 — SimplixPay release identity and updater ownership** |

**PRE-PHASE-0 READY / VERIFIED.** Repository foundation/readiness is closed. This does **not** mean the plugin is production-certified or release-ready; it means the repository, governance, provenance, baseline CI, security controls, branch policy, public presentation and local-history state are ready for controlled Phase 0 engineering.

## Readiness evidence base

The readiness gate was closed from independently reviewed evidence gathered on 2026-08-25.

### Canonical repository

- standalone public repository (`fork: false`);
- default branch: `main`;
- remote real branch inventory before the closing PR: **`main` only**;
- open PRs before the closing PR: **0**;
- no inherited Git tags;
- no GitHub Releases;
- MIT recognized by GitHub;
- Projects/Wiki/Discussions off;
- homepage: `https://simplixi.com`;
- description: `SimplixPay for UPayments — independently engineered WooCommerce payment integration by Simplix Innovations.`;
- squash merge enabled; merge commits and rebase disabled;
- automatic source-branch deletion enabled and independently proven.

Readiness evidence-base commit before this closing change:

- `main`: `7e530c2c6881c04a3170e110b23289d90185da14`
- tree: `64973fa4918061bdf8489319712ef2c79813a45b`
- GitHub signature: **VERIFIED**

### Main ruleset

Active repository ruleset `21327778` applies to the default branch and was verified to enforce:

- branch deletion restriction;
- non-fast-forward / force-push restriction;
- required linear history;
- pull request required before merge;
- review-thread resolution required;
- squash as the only allowed merge method;
- required status checks with strict/up-to-date policy:
  - **Governance** — GitHub Actions integration `15368`;
  - **H12 Regression Harness** — GitHub Actions integration `15368`.

The ruleset has no bypass actors and reports `current_user_can_bypass: never` at the readiness review.

### Security controls

Verified enabled:

- private vulnerability reporting;
- Dependabot security updates;
- secret scanning;
- secret-scanning push protection;
- vulnerability alerts/dependency graph enable command completed successfully.

`secret_scanning_non_provider_patterns` and `secret_scanning_validity_checks` remained unavailable/disabled after an accepted repository PATCH. They are optional enhancements, not a pre-Phase-0 blocker.

### Contributor/account presentation

GitHub's repository contributors API returned only:

- `SimplixInnovationsAdmin` — 5 contributions at verification time.

The sole-contributor repository presentation objective is therefore satisfied. The parentless root retains `Simplix Innovations <info@simplixi.com>` as its historical author string. Because the GitHub CLI user-email scope was not granted during readiness review, account-level verification of `info@simplixi.com` was not independently read through the API. **Before any future manual IDE-authored commit using `info@simplixi.com`, verify that address in GitHub Settings so future local commits map to `SimplixInnovationsAdmin`.** This is account hygiene, not a blocker to repository readiness.

### Local IDE clone

Externally verified local state before the closing PR:

- working tree clean;
- `HEAD...origin/main` divergence: `0 0`;
- remote tracking shows `origin/main`; `origin/HEAD -> origin/main` is only the normal symbolic default-branch pointer;
- local commit identity configured as `Simplix Innovations <info@simplixi.com>`.

After this closing PR merges, any local clone must simply `git pull --ff-only origin main` before new work.

### About/topics

Live topics were reduced to neutral discovery/scope terms without unearned certification claims:

`checkout-blocks`, `ecommerce`, `hpos`, `payment-gateway`, `payments`, `php`, `simplixpay`, `upayments`, `woocommerce`, `woocommerce-payment-gateway`, `wordpress`, `wpml`.

`hpos-compatible` and `wpml-ready` are intentionally absent until evidence supports those claims.

## Frozen H12 production baseline

Repository-readiness work did not alter payment/runtime source. Frozen anchors remain:

- `UPayments.php` — `64c789e81ae4d292ef9b1d7382812c319a44bc25`
- `includes/Token/CustomerTokenIdentity.php` — `85430d37e9baf540842f5655b86ccf0eca3e6aea`
- `includes/class-wc-gateway-upayments-blocks.php` — `813d192d69c069eb7ee11df93acc9dbdf03e270a`
- `includes/Subscription/Cron/Scheduler.php` — `5251866d4df2d1326e7c09f0c8ec1d146c0bb325`
- `includes/Subscription/Cron/CycleClaim.php` — `c34d83e2d77cc65024fe663e4c378cecb2b17347`
- archived H12 engineering changelog — `8c42bc6fdae163dd4159b8036b05cd2f70cc3d5d`

Last exact reviewed readiness CI before gate closure:

- Governance: **SUCCESS**
- all tracked PHP syntax: **SUCCESS**
- H12 PHP: **1927 PASS / 0 FAIL**
  - semantic runtime 368
  - helper unit runtime 841
  - static source 46
  - harness self-test 662
  - lint tooling 10
- Blocks syntax: **SUCCESS**
- H12 Blocks: **144 PASS / 0 FAIL**
  - runtime 88
  - static 15
  - harness 41

This custom H12 harness is a regression baseline, not broad WordPress/WooCommerce/PHP/HPOS/Blocks/WPML/browser/security/performance certification.

## Whole-repository audit status

`REPOSITORY-AUDIT.md` classifies the complete tracked tree. Known inherited/runtime debts remain deliberately deferred until characterization and the correct engineering phase, including:

- large inherited `UPayments.php` bootstrap;
- H12-critical token/subscription classes;
- empty inherited files and duplicate provider assets;
- legacy screenshots and multiple JS paths;
- bundled Plugin Update Checker;
- inherited `uninstall.php` data-deletion behavior;
- absence of the future `src/` + Composer/PSR-4 package structure;
- absence of the future broad PHPUnit/WP/Woo/browser/static-analysis platform.

Those are Phase 0 or later work, not unfinished repository-readiness tasks.

## Protected compatibility identities

Do not rename merely for branding. Protected by default include:

- WooCommerce gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks/Store API identity `upayments`;
- callback route `wc_upayments`;
- existing `_upay_*` metadata;
- `upayments_token_identity_secret_v2` and H12 provenance/scope/generation state;
- subscription scheduler/historical cleanup identities;
- billing-attempt table/schema state;
- historical order payment-method values.

See `NAMING-IDENTITY-STANDARD.md` for the full compatibility registry.

## Next permitted action — Phase 0

Phase 0 may now begin, but only under the existing PR/CI/review rules. Its objectives are:

1. eliminate/replace the upstream-controlled update path;
2. establish independent SimplixPay semantic versioning (`0.x` while hardening; `1.0.0` only after stable-release gates);
3. change public plugin metadata to **SimplixPay for UPayments** / Simplix Innovations;
4. design and test folder + main-file + text-domain transition as an upgrade problem;
5. preserve protected persisted/runtime identifiers unless an explicit tested migration changes them;
6. add updater/version/install/upgrade/rollback regression evidence.

The inherited `UPayments.php` filename, updater, version and provider-branded plugin header remain intentionally unchanged at the readiness baseline and are Phase 0 work.

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

Update this file after every independently verified milestone merge or program-state change. Never claim a recorded SHA is still live without checking GitHub. Never approve or merge from an implementation/agent report alone; verify exact source, tree/diff, checks and post-merge state.
