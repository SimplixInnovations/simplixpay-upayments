# SimplixPay for UPayments — Clean Chat Handoff

Use this file with root `AGENTS.md`, `PROJECT-STATUS.md`, the naming standard, the closed Phase 0/Phase 9I records and the Master Engineering Playbook.

## Project identity

- Canonical repository: `SimplixInnovations/simplixpay-upayments`
- Historical engineering/audit archive: `SimplixInnovations/upayments-woocommerce`
- Provider upstream repository: `upaymentskwt/woocommerce`
- Formal product: **SimplixPay for UPayments**
- Short integration reference: **SimplixPay UPayments**
- Reserved broader product family: **SimplixPay**
- Canonical slug: `simplixpay-upayments`
- Maintainer: **Simplix Innovations**
- Provider: **UPayments**
- Current development version: **0.1.0**

`SimplixPay` alone is reserved for the broader future/multi-provider payment product.

## Current program position

- Repository Foundation / Readiness: **DONE / VERIFIED**
- Phase 0 — release identity/updater ownership: **DONE / VERIFIED**
- Phase 9I — historical token-identity migration: **DONE / VERIFIED**
- Current program gate: **Provider Contract & Payment Lifecycle — DISCOVERY**
- Stable production release: **NO**
- WordPress.org release: **NO**

Always verify live GitHub before acting; recorded SHAs below are milestone evidence, not substitutes for a fresh check.

## Latest verified implementation milestone — Phase 9I operations

PR #13 final reviewed head:

- `2989862683754f8a8eda8e9d4239ada4a61b23f4`

Verified squash merge on `main`:

- merge: `db1c4ea4dab45bc1ffaf4529e0ccb940153cd999`
- tree: `5bec24ad26c66a504cd0dd609f4311f9e70add76`
- parent: `708253bd9d0daf217735fbb087b360e8b848136c`
- GitHub signature: **VERIFIED**
- `phase-9i/operations` branch: **deleted after verified merge**

Exact final implementation-head regression evidence:

- Governance: **SUCCESS**
- tracked PHP syntax: **SUCCESS**
- Phase 0 release identity: **35 PASS / 0 FAIL**
- Phase 9I preflight: **123 PASS / 0 FAIL**
- Phase 9I executor: **59 PASS / 0 FAIL**
- Phase 9I operations: **81 PASS / 0 FAIL**
- H12 PHP: **1927 PASS / 0 FAIL**
- Blocks syntax: **SUCCESS**
- H12 Blocks: **144 PASS / 0 FAIL**

## Phase 9I closed architecture

### Preflight — PR #11

Verified merge:

- `8cca32819dd165e35efa0fcc5a48bdd551757d8c`
- tree `c0af8a2ab1fbd2494f961ee9f924c00aaf519ab0`

Contract:

- read-only core classifier;
- zero provider calls and zero identity writes;
- exact `CLEAN`, `MIGRATABLE`, `BLOCKED`, `INDETERMINATE` outcomes;
- bounded history/collision analysis;
- all 13 historical blocker families explicitly fail closed.

### Executor — PR #12

Verified merge:

- `708253bd9d0daf217735fbb087b360e8b848136c`
- tree `e222a18c9808229fdde79efb42268d8c3fbd33ae`
- GitHub signature: **VERIFIED**

Contract:

- acts only on fresh `MIGRATABLE` evidence;
- locked re-preflight before mutation;
- creates a missing H12 secret only through the verified bootstrap-lock transition;
- creates only `legacy_compat` / `legacy_verified_capture` provenance;
- never fabricates `canonical` / `create_201` provenance;
- verifies exact provenance readback and final CLEAN state;
- idempotent/retry-safe under characterized concurrency;
- zero provider calls and zero historical order-meta writes.

### Operations — PR #13

Contract:

- explicit user lists only;
- maximum 500 submitted users;
- default 20 / maximum 50 processed per invocation;
- admin + `wp simplixpay-upayments migration` CLI;
- dry-run is identity/provider non-mutating but intentionally persists only redacted operations-result checkpoints;
- execute requires explicit confirmation;
- separate `_simplixpay_upayments_migration_result_v1` ledger records every processed decision/result, not only successful migrations;
- durable resume is credential/mode/list scoped by HMAC without persisting the API key;
- explicit offset remains available for deliberate re-evaluation;
- if operations checkpoint persistence is uncertain, stop and leave that user as the retry point;
- failed CLI batch emits redacted JSON before non-zero termination;
- no provider transport, checkout, Store API, frontend or cron migration hook;
- historical order metadata remains immutable.

**Important:** Phase 9I being DONE / VERIFIED does not mean every merchant installation was automatically migrated. A site may still contain users classified `BLOCKED` or `INDETERMINATE`; those states must remain fail closed until explicit evidence/policy resolves them.

## Thirteen historical blocker classes — implemented disposition

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

These are no longer unimplemented Phase 9I gaps; they have explicit verified classification/fail-closed semantics. Do not weaken them casually in later phases.

## Transitional install/i18n identities

Do **not** treat these as unfinished cosmetic branding:

- active main file remains `UPayments.php`;
- runtime/header text domain remains `upayments`.

Frozen eventual targets:

- `simplixpay-upayments.php`;
- text domain `simplixpay-upayments`.

Those changes are explicit package/upgrade and i18n/WPML migrations requiring their own evidence.

## Protected compatibility identities

Do not globally rename historical `upayments` / `_upay_*` identities. Protected by default include:

- gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks/Store API identity `upayments`;
- callback route `wc_upayments`;
- existing `_upay_*` metadata;
- `upayments_token_identity_secret_v2`;
- H12 provenance/scope/generation state;
- subscription scheduler/historical cleanup identities;
- billing-attempt table/schema state;
- historical order payment-method values;
- existing UPayments classes/namespaces unless separately characterized.

Any change requires an explicit tested migration contract.

## H12 non-negotiable token/provider rules

- Customer token is separate from phone/mobile.
- Create candidate: numeric 8–18 digits; non-predictable; 8 digits preferred for KFAST; never standalone phone number.
- Strict Create success: HTTP 201 + `status === true` + exact returned candidate.
- HTTP 422 fails closed; no message-based duplicate inference or automatic collision retry.
- Retrieve Cards uses customer token with strict structural success checks.
- Saved-card charge uses card token + customer unique token.
- Selecting a saved card does not imply save-again consent.
- Guests are never promoted to persistent identity.
- Phone changes do not rotate canonical identity.
- Provenance v3: `canonical` ↔ `create_201`; `legacy_compat` ↔ `legacy_verified_capture`.
- Secret option `upayments_token_identity_secret_v2` is protected; malformed is distinct from missing and fails closed.
- Selected saved card requires current valid provenance + exact scope/generation + fresh provider Retrieve + exact membership.

## Next gate — Provider Contract & Payment Lifecycle

**Status: DISCOVERY. Do not start with a broad refactor.**

Required first sequence:

1. Freshly verify live `main`, open PRs/branches and current payment-critical source.
2. Research the current official UPayments provider documentation for charge, status/reconciliation, notification/webhook, return/callback, refund and multi-merchant behavior.
3. Characterize the exact current runtime behavior in `UPayments.php` and related payment/refund/callback/subscription paths.
4. Build a provider-contract matrix: request fields, response success/failure, authoritative truth source, idempotency/retry semantics, state transitions and ambiguity handling.
5. Identify contradictions between provider docs, current code, H12 contracts and WooCommerce order-state semantics.
6. Freeze a narrow implementation contract and characterization tests before changing payment-critical runtime behavior.

Minimum audit scope:

- charge request/response contract;
- webhook/status/browser-return truth hierarchy;
- webhook/callback authentication, replay and duplicate delivery;
- status reconciliation and ambiguous settlement states;
- deterministic WooCommerce payment/order transitions;
- transient errors/rate limits/retries without blindly retrying non-idempotent financial operations;
- refunds including full/partial/idempotency/failure recovery;
- multi-merchant routing/identity boundaries;
- redacted logging and reconciliation/support evidence.

## Permanent control plane

Read in this order:

1. `AGENTS.md`
2. `docs/project/PROJECT-STATUS.md`
3. `docs/project/NAMING-IDENTITY-STANDARD.md`
4. `docs/project/NEW-CHAT-HANDOFF.md`
5. `docs/project/PHASE-0-RELEASE-IDENTITY.md` — closed Phase 0 evidence
6. `docs/project/PHASE-9I-MIGRATION.md` — closed Phase 9I evidence
7. relevant sections of `docs/project/MASTER-ENGINEERING-PLAYBOOK.md`
8. `docs/project/REPOSITORY-AUDIT.md`
9. `docs/project/REPOSITORY-READINESS.md` — closed foundation evidence
10. `docs/project/BASELINE-H12.md`

## Required working method

1. Verify live `main`, branches, PRs, source and checks before implementation.
2. Reconcile documented state with live GitHub; report drift.
3. Prefer direct GitHub operations; delegate only genuinely inaccessible actions.
4. Preserve protected historical identities unless a separately approved/tested migration changes them.
5. Characterize before payment/security-critical refactors.
6. Never trust Agent/bot implementation claims without exact independent verification.
7. Pin review/merge decisions to exact base/head SHAs.
8. Do not merge with unresolved valid review findings or failing/missing required checks.
9. After merge, verify resulting `main`, critical source/evidence and branch cleanup before marking DONE.
10. Update `PROJECT-STATUS.md` after every verified milestone/state change.

## Program sequence

0. Repository Foundation / Readiness — **DONE / VERIFIED**
1. Phase 0 — SimplixPay release identity/updater ownership — **DONE / VERIFIED**
2. Phase 9I — Historical token-identity migration — **DONE / VERIFIED**
3. Provider Contract & Payment Lifecycle — **CURRENT / DISCOVERY**
4. Security threat-model closure
5. Architecture/code-quality foundation
6. Full automated quality platform
7. Platform certification: Woo/WP/PHP/HPOS/Blocks/WPML
8. Feature certification
9. Performance/UX/operations/diagnostics
10. Release engineering/distribution/WordPress.org when eligible
11. Continuous maintenance

## Copy-ready opening prompt

```text
Continue the SimplixPay for UPayments engineering program in SimplixInnovations/simplixpay-upayments.

Read AGENTS.md first, then docs/project/PROJECT-STATUS.md, docs/project/NAMING-IDENTITY-STANDARD.md, docs/project/NEW-CHAT-HANDOFF.md, docs/project/PHASE-0-RELEASE-IDENTITY.md, docs/project/PHASE-9I-MIGRATION.md and relevant sections of docs/project/MASTER-ENGINEERING-PLAYBOOK.md.

Treat recorded SHAs/status as milestone evidence until you independently verify live GitHub main, branches, open PRs, current source and checks. Reconcile any drift before work.

Repository readiness, Phase 0 and Phase 9I are DONE / VERIFIED. The current gate is Provider Contract & Payment Lifecycle — DISCOVERY.

Start by verifying current payment-critical source and current official UPayments documentation. Characterize charge, webhook/status/browser-return truth, order-state transitions, reconciliation, retries/idempotency, refunds and multi-merchant boundaries before proposing runtime changes. Freeze a narrow contract and tests before refactoring.

Work directly in GitHub wherever tools permit. Preserve protected historical upayments/upay identities unless an approved tested migration changes them. Never approve or merge without independent exact-SHA source/diff/check/review verification.
```
