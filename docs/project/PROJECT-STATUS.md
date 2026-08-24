# SimplixPay for UPayments — Project Status

**Status document:** canonical living engineering state

**Last updated:** 2026-08-25

**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

**Historical audit archive:** `SimplixInnovations/upayments-woocommerce`

**Provider upstream:** `upaymentskwt/woocommerce`

> Live GitHub/source evidence always wins over recorded SHAs. Recorded SHAs are verified milestones/evidence anchors, not substitutes for a fresh remote check.

## Current program state

| Item | State |
|---|---|
| Product | **SimplixPay for UPayments** |
| Canonical slug | `simplixpay-upayments` |
| Product family reserved for broader future use | **SimplixPay** |
| Current development version | **0.1.0** |
| Production maturity | **Pre-release engineering hardening** |
| Stable SimplixPay release | **NO** |
| WordPress.org release | **NO** |
| H12 token-identity hardening | **DONE / VERIFIED** |
| Repository foundation/readiness | **DONE / VERIFIED** |
| Phase 0 — release identity/updater ownership | **DONE / VERIFIED** |
| Current implementation gate | **Phase 9I — Historical token-identity migration** |

**PHASE 0 IS CLOSED.** The plugin now has Simplix-owned public release identity/versioning and no inherited upstream update authority. This does **not** mean the plugin is broadly production-certified or release-ready.

## Latest verified implementation milestone

Phase 0 implementation PR #9 was squash-merged as:

- `main` milestone: `678f3bdae32b7a0d5922c6ebb7fa7535ede256dd`
- tree: `80618e737476a92357bd463f6e1495c364157e83`
- GitHub signature: **VERIFIED**
- author: `SimplixInnovationsAdmin`

Immediately after merge:

- real remote branches: `main` only;
- open PRs: 0;
- Phase 0 feature branch auto-deleted;
- inherited Plugin Update Checker path absent;
- active plugin header identifies **SimplixPay for UPayments 0.1.0** by Simplix Innovations;
- uninstall is non-destructive by default.

Treat this SHA as Phase 0 evidence. A new session must always check the actual live `main` again.

## Phase 0 release identity — verified state

Public header:

- Plugin Name: **SimplixPay for UPayments**
- Plugin URI: `https://github.com/SimplixInnovations/simplixpay-upayments`
- Description: `Independently engineered UPayments payment integration for WooCommerce by Simplix Innovations.`
- Version: `0.1.0`
- Author: **Simplix Innovations**
- Author URI: `https://simplixi.com`
- License: MIT
- Text Domain: `upayments` — transitional by design
- Domain Path: `/languages`

Canonical code-side release identity is `Simplix\Pay\UPayments\Release\Identity`.

External self-update channel is intentionally **disabled** until a separately tested physical package/basename migration establishes a safe Simplix distribution identity.

The inherited bundled Plugin Update Checker and `upaymentskwt/woocommerce` update authority are gone.

See `PHASE-0-RELEASE-IDENTITY.md` for the exact contract and evidence.

## Transitional install/i18n identities

Phase 0 deliberately did **not** physically rename the active main file or mechanically change the runtime text domain.

Current transitional identities:

- main file: `UPayments.php`;
- runtime/header text domain: `upayments`.

Frozen eventual targets:

- main file: `simplixpay-upayments.php`;
- text domain: `simplixpay-upayments`.

Those transitions are explicit upgrade/i18n migrations requiring their own install/rollback/duplicate-plugin/WPML characterization. They are not incomplete branding work to perform by search/replace.

## Frozen H12 compatibility/runtime contracts

Do not rename merely for branding:

- gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks/Store API identity `upayments`;
- callback route `wc_upayments`;
- existing `_upay_*` metadata;
- `upayments_token_identity_secret_v2` and H12 provenance/scope/generation state;
- subscription scheduler/historical cleanup identities;
- billing-attempt table/schema state;
- historical order payment-method values;
- existing UPayments classes/namespaces unless separately characterized.

The exact naming/compatibility registry in `NAMING-IDENTITY-STANDARD.md` remains authoritative.

## Phase 0 test evidence

Initial characterization before implementation:

- Phase 0 harness: **22 PASS / 13 FAIL** — exactly the inherited header/updater/vendor defects.

Final exact reviewed PR #9 head `8b67259bd05453150f837cda4b961f649f50cf02`:

- Governance: **SUCCESS**
- tracked PHP syntax: **SUCCESS**
- Phase 0 release identity: **35 PASS / 0 FAIL**
- H12 PHP: **1927 PASS / 0 FAIL**
  - semantic runtime: 368
  - helper unit runtime: 841
  - static source: 46
  - harness self-test: 662
  - lint tooling: 10
- Blocks syntax: **SUCCESS**
- H12 Blocks: **144 PASS / 0 FAIL**
  - runtime: 88
  - static: 15
  - harness: 41

Four H12 implementation anchors outside the intentionally changed bootstrap remain frozen:

- `includes/Token/CustomerTokenIdentity.php` — `85430d37e9baf540842f5655b86ccf0eca3e6aea`
- `includes/class-wc-gateway-upayments-blocks.php` — `813d192d69c069eb7ee11df93acc9dbdf03e270a`
- `includes/Subscription/Cron/Scheduler.php` — `5251866d4df2d1326e7c09f0c8ec1d146c0bb325`
- `includes/Subscription/Cron/CycleClaim.php` — `c34d83e2d77cc65024fe663e4c378cecb2b17347`

The H12 harness remains a regression baseline, not broad platform/security/performance certification.

## Repository/governance state

Repository readiness remains closed/verified:

- standalone repository (`fork: false`);
- protected default branch `main`;
- squash-only merge policy;
- required PR + review-thread resolution;
- strict required `Governance` and `H12 Regression Harness` checks;
- linear history + deletion/non-fast-forward restrictions;
- auto-delete merged branches;
- secret scanning + push protection;
- Dependabot security updates;
- private vulnerability reporting;
- MIT recognized;
- Projects/Wiki/Discussions off;
- evidence-safe topics;
- sole contributor presentation previously verified as `SimplixInnovationsAdmin`.

See the closed `REPOSITORY-READINESS.md` for the repository-foundation evidence record.

## Whole-repository audit status after Phase 0

Resolved since the original audit:

- **upstream updater authority** — removed;
- **bundled Plugin Update Checker** — removed;
- **provider-branded plugin header/version** — replaced by Simplix identity/version;
- **destructive uninstall behavior** — removed; data retained by default;
- **new Simplix namespace foothold** — `Simplix\Pay\UPayments\Release` introduced;
- **release-identity characterization CI** — permanent Phase 0 harness added.

Still deliberately deferred:

- broad extraction of the large inherited bootstrap;
- physical main-file/folder migration;
- text-domain migration;
- explicit coexistence/conflict detection;
- empty/duplicate/legacy asset cleanup;
- full Composer/PSR-4 package architecture;
- full PHPUnit/WP/Woo/browser/static-analysis platform;
- broad Woo/WP/PHP/HPOS/Blocks/WPML/security/performance certification.

## Current implementation gate — Phase 9I

Phase 9I must close the historical token-identity migration blockers **without provider calls or writes during preflight**.

Required design:

### A. Read-only deterministic preflight

Classify evidence as exactly one of:

- `CLEAN`
- `MIGRATABLE`
- `BLOCKED`
- `INDETERMINATE`

Preflight must perform zero provider calls and zero identity mutation.

### B. Executor

Execute only explicit `MIGRATABLE` cases.

Attributable legacy identity may become `legacy_compat` / `legacy_verified_capture`; the executor must never fabricate canonical/Create-201 provenance.

### C. Operational surface

Provide bounded, idempotent, resumable admin/CLI batch behavior with dry-run capability and per-user ledger/state. Do not perform unbounded historical scans on checkout hot paths.

## Phase 9I blockers — all 13 open at Phase 0 closure

1. Unscoped legacy tokens
2. Current-scope orphan histories
3. Cross-user token conflicts
4. Malformed scoped histories
5. Secret generation mismatches
6. Card-token-only historical identity
7. Prior-scope same-generation histories
8. Non-scalar evidence
9. Orphan metadata
10. >200/incomplete history → `INDETERMINATE`
11. Unloadable orders
12. Force-refresh failures
13. Malformed-vs-missing secret distinction

No Phase 9I implementation is approved merely because it handles a subset of these cases.

## Later program blockers

After Phase 9I:

- provider contract/payment lifecycle/webhook/status/refund audit;
- deterministic payment state machine/reconciliation;
- security threat-model closure;
- architecture/code-quality foundation;
- full automated quality platform;
- platform/feature/browser/performance certification;
- onboarding/diagnostics/observability;
- release packaging and eventual WordPress.org publication.

## Update rule

Update this file after every independently verified milestone merge or program-state change. Never mark a gate DONE from an implementation/agent report alone; verify exact source, diff/tree, checks, review state and post-merge `main` first.
