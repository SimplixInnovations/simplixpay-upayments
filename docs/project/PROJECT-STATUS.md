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
| Phase 9I — historical token-identity migration | **DONE / VERIFIED** |
| Current program gate | **Provider Contract & Payment Lifecycle — DISCOVERY** |

**PHASE 9I IS CLOSED.** The project now has a deterministic read-only historical-identity classifier, a locked fail-closed executor for explicit migratable states, and bounded resumable admin/CLI operations with durable redacted per-user decision/result checkpoints.

Phase 9I completion means the migration **system and its safety contract are implemented and independently verified**. It does not mean every merchant installation has already been classified or migrated. Site-specific migration remains an explicit bounded operational action using the verified tooling.

The plugin remains a **pre-release engineering project**. Phase 9I closure does not constitute broad production, WordPress/WooCommerce/WPML, security, performance, or WordPress.org certification.

## Latest verified implementation milestone — Phase 9I operations

Phase 9I operations PR #13 was independently reviewed and squash-merged from exact reviewed head:

- reviewed head: `2989862683754f8a8eda8e9d4239ada4a61b23f4`
- squash merge on `main`: `db1c4ea4dab45bc1ffaf4529e0ccb940153cd999`
- tree: `5bec24ad26c66a504cd0dd609f4311f9e70add76`
- parent: `708253bd9d0daf217735fbb087b360e8b848136c`
- GitHub signature: **VERIFIED**
- implementation branch `phase-9i/operations`: **deleted after verified merge**

The reviewed/merged operations tranche contains:

- bounded explicit-user batch processing;
- existing WooCommerce credential/mode resolution without a new API-key input surface;
- canonical `wp simplixpay-upayments migration` CLI commands;
- WooCommerce admin migration tooling;
- identity-nonmutating dry-run plus redacted durable operations-result checkpoints;
- a separate `_simplixpay_upayments_migration_result_v1` per-user decision/result ledger for CLEAN, BLOCKED, INDETERMINATE, migrated, dry-run and executor-exception outcomes;
- credential/mode/list-scoped HMAC resume fingerprints without persisting the API key;
- fail-closed checkpoint behavior that stops on uncertain ledger persistence and leaves the current user as the retry point;
- no provider transport path, no checkout/frontend migration hook, and no historical order-meta rewrite.

## Phase 9I verified sequence

### A. Read-only deterministic preflight — PR #11

- merge commit: `8cca32819dd165e35efa0fcc5a48bdd551757d8c`
- tree: `c0af8a2ab1fbd2494f961ee9f924c00aaf519ab0`
- classification contract: exactly `CLEAN`, `MIGRATABLE`, `BLOCKED`, or `INDETERMINATE`
- preflight provider calls: **0**
- preflight identity writes: **0**
- all 13 historical blocker families retained explicit fail-closed classification evidence.

### B. Locked fail-closed executor — PR #12

- merge commit: `708253bd9d0daf217735fbb087b360e8b848136c`
- tree: `e222a18c9808229fdde79efb42268d8c3fbd33ae`
- GitHub signature: **VERIFIED**
- executes only explicit `MIGRATABLE` evidence;
- may establish only `legacy_compat` / `legacy_verified_capture` provenance;
- never fabricates `canonical` / `create_201` provenance;
- locked re-preflight before mutation;
- safe missing-secret initialization only under the verified bootstrap-lock transition;
- exact provenance readback and final CLEAN verification;
- idempotent rerun/concurrent-worker behavior;
- zero provider calls and zero historical order-meta mutation.

### C. Bounded operational surface — PR #13

- merge commit: `db1c4ea4dab45bc1ffaf4529e0ccb940153cd999`
- tree: `5bec24ad26c66a504cd0dd609f4311f9e70add76`
- GitHub signature: **VERIFIED**
- bounded lists: maximum 500 submitted users, maximum 50 processed per invocation, default 20;
- explicit offset or durable resume;
- dry-run and execute surfaces in admin + CLI;
- explicit execute confirmation (`--yes` / admin checkbox);
- failed CLI batches emit redacted results then terminate non-zero;
- redacted durable result checkpoints for every processed user;
- operations checkpoint failures stop progress and preserve the current retry position;
- admin/CLI only; no provider, checkout, Store API, frontend or cron migration hooks.

## Phase 9I closure regression evidence

Exact final reviewed PR #13 head `2989862683754f8a8eda8e9d4239ada4a61b23f4` passed the complete then-required stack:

- Governance: **SUCCESS**
- tracked PHP syntax: **SUCCESS**
- Phase 0 release identity: **35 PASS / 0 FAIL**
- Phase 9I preflight: **123 PASS / 0 FAIL**
- Phase 9I executor: **59 PASS / 0 FAIL**
- Phase 9I operations: **81 PASS / 0 FAIL**
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

The Phase 9I closure documentation PR must rerun this full stack again before merge. These targeted harnesses remain regression evidence, not broad ecosystem certification.

## Phase 9I blocker disposition

The 13 blocker classes opened at Phase 0 closure are no longer unhandled implementation gaps. They are explicitly classified and fail closed under the verified Phase 9I preflight/executor contract:

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

A site can still legitimately contain `BLOCKED` or `INDETERMINATE` users. Phase 9I deliberately does not guess those states into valid identities.

See `PHASE-9I-MIGRATION.md` for the exact closed contract and operational semantics.

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

External self-update channel is intentionally **disabled** until a separately tested physical package/basename migration establishes a safe Simplix distribution identity. The inherited bundled Plugin Update Checker and `upaymentskwt/woocommerce` update authority remain removed.

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

## Repository/governance state

Repository readiness remains closed/verified:

- standalone repository (`fork: false`);
- protected default branch `main`;
- squash-only merge policy;
- required PR + review-thread resolution;
- required `Governance` and `H12 Regression Harness` checks;
- linear history + deletion/non-fast-forward restrictions;
- auto-delete merged branches;
- secret scanning + push protection;
- Dependabot security updates;
- private vulnerability reporting;
- MIT recognized.

## Current program gate — Provider Contract & Payment Lifecycle

**Status: DISCOVERY.**

The next program gate must now freeze and verify the provider/payment lifecycle contracts before runtime refactoring. At minimum it covers:

- charge/request contract and success/failure envelope;
- authoritative payment truth hierarchy across server webhook, status reconciliation and browser return;
- callback/webhook authentication, replay/idempotency and duplicate-event behavior;
- order/payment state transitions and ambiguity handling;
- status-query and reconciliation semantics;
- refund contract, idempotency, partial/full refund behavior and failure recovery;
- multi-merchant routing/identity boundaries;
- retry/rate-limit/transient-failure rules without blindly retrying non-idempotent financial operations;
- logging/redaction and operational evidence needed to support payment disputes/reconciliation.

Discovery must compare the current exact source against current official UPayments documentation before an implementation contract is frozen.

## Later program blockers

After Provider Contract & Payment Lifecycle:

- security threat-model closure;
- architecture/code-quality foundation;
- full automated quality platform;
- platform/feature/browser/performance certification;
- onboarding/diagnostics/observability;
- release packaging and eventual WordPress.org publication.

## Update rule

Update this file after every independently verified milestone merge or program-state change. Never mark a gate DONE from an implementation/Agent report alone; verify exact source, diff/tree, checks, review state and post-merge `main` first.
