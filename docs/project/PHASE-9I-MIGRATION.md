# Phase 9I — Historical Token-Identity Migration

**Status:** IN PROGRESS

**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

**Current development version:** `0.1.0`

## Objective

Safely classify and migrate historical UPayments customer-token identity evidence that H12 correctly refuses to guess.

Phase 9I extends the frozen H12 identity model; it does not replace it. Existing `UPayments\Token\CustomerTokenIdentity` runtime behavior remains the regression oracle while new migration orchestration lives under `Simplix\Pay\UPayments\Migration`.

## Non-negotiable rule

Historical identity must never be promoted by inference when attribution is ambiguous.

Preflight is read-only. It performs zero provider calls, secret creation/rotation, option writes, user-meta writes, order-meta writes, or checkout-hot-path migration work.

## Exact preflight classifications

Every evaluated user resolves to exactly one of:

- `CLEAN` — no migration is needed and evidence is internally consistent;
- `MIGRATABLE` — one attributable legacy customer token can be migrated under an explicit legacy provenance contract;
- `BLOCKED` — contradictory, malformed, security-sensitive or otherwise unsafe evidence requires manual investigation or a future explicit repair policy;
- `INDETERMINATE` — the system cannot prove a complete trustworthy view of the relevant history.

`BLOCKED` and `INDETERMINATE` are fail-closed states. The executor must never act on them.

## Frozen migration provenance

A Phase 9I migration may create only:

- kind: `legacy_compat`;
- source: `legacy_verified_capture`.

It must never fabricate `canonical` / `create_201`. Canonical provenance remains proof of the strict provider Create-token 201 contract only.

## Preflight — merged / verified

The read-only implementation is `Simplix\Pay\UPayments\Migration\MigrationPreflight`.

Verified merge milestone:

- PR #11;
- merge commit `8cca32819dd165e35efa0fcc5a48bdd551757d8c`;
- tree `c0af8a2ab1fbd2494f961ee9f924c00aaf519ab0`;
- GitHub signature: VERIFIED.

It reuses H12 public validators/readers for secret validation, atomic scope/generation context, provenance, historical metadata cardinality, force refresh, and token grammar while performing migration-specific bounded attribution/collision census.

### Historical scan bounds

- order page size: 20;
- maximum complete relevant order history: 200;
- any incomplete or unstable pagination view: `INDETERMINATE`;
- unloadable order: `INDETERMINATE`;
- force-refresh failure: `INDETERMINATE`;
- query/DB uncertainty: `INDETERMINATE`.

No safety cap is interpreted as scan completion.

### Cross-user attribution

A candidate token is not migratable until both are clear:

1. no UPayments order owned by another user/guest contains the exact customer token;
2. no provenance record owned by another user contains the exact customer token.

SQL may only discover bounded provenance candidates. Exact decoded-record comparison is mandatory before declaring a conflict.

Raw customer tokens are security-sensitive migration material. Preflight exposes a SHA-256 digest for reporting; raw token material exists only in the in-memory migration payload supplied to the executor. CLI/admin/log output must never print it.

## Thirteen blocker classes

1. **Unscoped legacy tokens** — potentially `MIGRATABLE` only for one complete, attributable token.
2. **Current-scope orphan histories** — potentially `MIGRATABLE` only for structurally exact `legacy_compat` evidence under current scope/generation; orphan `canonical` evidence is `BLOCKED`.
3. **Cross-user token conflicts** — `BLOCKED`.
4. **Malformed scoped histories** — `BLOCKED`.
5. **Secret generation mismatches** — `BLOCKED`.
6. **Card-token-only historical identity** — `BLOCKED`.
7. **Prior-scope same-generation histories** — `BLOCKED`.
8. **Non-scalar or duplicate evidence** — `BLOCKED`.
9. **Orphan metadata** — `BLOCKED`.
10. **>200 or otherwise incomplete history** — `INDETERMINATE`.
11. **Unloadable orders** — `INDETERMINATE`.
12. **Force-refresh failures** — `INDETERMINATE`.
13. **Malformed versus missing secret** — malformed is `BLOCKED`; genuinely missing is distinct and can be initialized only by a locked verified executor transition.

The preflight also fails closed on multiple tokens for one user, historical/current provenance contradictions, malformed provenance, scoped history with a missing secret, unstable pagination, malformed query/result shapes, invalid order IDs, and incomplete cross-user attribution scans.

## Executor — merged / verified

`Simplix\Pay\UPayments\Migration\MigrationExecutor` was independently reviewed and squash-merged in PR #12.

Verified merge milestone:

- merge commit `708253bd9d0daf217735fbb087b360e8b848136c`;
- tree `e222a18c9808229fdde79efb42268d8c3fbd33ae`;
- GitHub signature: VERIFIED;
- exact executor harness: **59 PASS / 0 FAIL**;
- preflight: **123 PASS / 0 FAIL**;
- Phase 0: **35 PASS / 0 FAIL**;
- H12 PHP: **1927 PASS / 0 FAIL**;
- H12 Blocks: **144 PASS / 0 FAIL**.

The executor:

1. calls a fresh preflight and acts only on `MIGRATABLE`;
2. supports true dry-run with zero writes/provider calls;
3. acquires the H12-compatible bootstrap lock when a secret is absent, otherwise the exact per-user/current-scope lock;
4. reruns preflight while holding the lock before mutation;
5. creates a genuinely missing H12 secret only while the bootstrap lock is held and verifies exact readback;
6. reruns preflight after secret creation and requires the exact same candidate token before provenance mutation;
7. derives current scope/generation from the validated secret record;
8. creates only immutable H12 `legacy_compat` / `legacy_verified_capture` provenance;
9. verifies provenance readback exactly;
10. reruns preflight after provenance creation and requires `CLEAN`;
11. is idempotent under normal rerun and concurrent-worker completion;
12. records only a redacted Simplix-owned per-user ledger (`simplixpay_upayments_migration_v1`) containing token digest, never raw token;
13. performs zero provider calls and zero historical order-meta mutation.

### Historical order immutability decision

The executor deliberately **does not rewrite historical order snapshots**. Those records are evidence and may be consumed by subscriptions/renewals. Phase 9I establishes authoritative current H12 provenance while preserving historical order metadata byte-for-byte unless a future separately characterized migration proves a specific order-field repair is necessary.

This supersedes the earlier draft idea of “normalizing” candidate order snapshots during identity migration.

### Failure/recovery semantics

- lock contention: fail with no mutation;
- evidence change under lock: fail closed before mutation;
- malformed secret: never replace;
- safe secret creation followed by provenance failure: retain the valid root and allow retry;
- provenance verification failure: fail closed and surface the failure;
- final preflight not `CLEAN`: fail closed;
- ledger failure after verified identity migration: do not roll back valid provenance; surface `migrated_ledger_write_failed` so operations can repair observability separately.

## Operational surface — current tranche

The operational tranche is intentionally explicit rather than auto-discovering every historical customer. Automatic discovery would require its own bounded, resumable global-census contract and is not silently introduced here.

### Shared batch engine

`Simplix\Pay\UPayments\Migration\MigrationBatch`:

- accepts an explicit list of positive user IDs;
- maximum submitted list: **500 users**;
- default users per invocation: **20**;
- hard maximum users per invocation: **50**;
- rejects duplicate, leading-zero, exponent, negative, zero, overflow and malformed IDs;
- resumes through an explicit zero-based `offset` and returned `next_offset`;
- isolates one-user executor exceptions so a bounded page can report all processed results;
- exposes only redacted per-user fields and aggregate reason/classification counters;
- does not persist API credentials, raw tokens, batch queues or checkout state;
- relies on the executor's Simplix-owned per-user ledger for durable migrated-user evidence.

### Credential/mode resolution

`MigrationSettings` reads the protected existing WooCommerce option `woocommerce_upayments_settings`.

- existing `api_key` is consumed in memory only;
- existing `test_mode` must be exact Woo checkbox state `yes`/`no`;
- no Phase 9I API-key option, CLI argument or admin input is introduced;
- reporting returns mode only and never the API key.

This prevents credentials from entering shell history, process arguments, migration form posts or Simplix migration storage.

### WP-CLI

Canonical registration:

`wp simplixpay-upayments migration`

Subcommands:

- `preflight --user-ids=<ids> [--offset=<n>] [--limit=<n>]`
- `execute --user-ids=<ids> --yes [--offset=<n>] [--limit=<n>]`

Write mode requires explicit `--yes`. Output is redacted JSON only. There is deliberately no `--api-key` argument.

### WooCommerce admin

A WooCommerce submenu page is registered as **SimplixPay Migration**.

Security controls:

- capability: `manage_woocommerce`;
- WordPress nonce required for POST;
- default mode is read-only preflight;
- execute mode requires a separate explicit confirmation checkbox;
- user IDs, resume offset and batch limit are strictly validated;
- no credential field exists;
- output is escaped, redacted JSON only.

### Runtime isolation

`MigrationBootstrap` is loaded by the plugin bootstrap but exits immediately unless the request is WordPress admin or WP-CLI.

It registers no checkout, Store API, frontend, cron or provider hooks. Operational source contains no provider transport path. Historical order metadata remains immutable.

### Operations test gate

`tests/harness/phase-9i-operations-harness.php` is required inside `H12 Regression Harness` CI.

It covers strict ID parsing, settings resolution/redaction, bounded page/resume behavior, per-user failure aggregation, executor exception isolation, invalid-window no-execution behavior, safe CLI output, CLI execute confirmation, admin capability/nonce/confirmation source contracts, canonical CLI namespace, no checkout/frontend hook, no provider transport and hard batch bounds.

## Exit condition

Phase 9I is **not** complete until the operational tranche itself is independently reviewed and merged.

The phase closes only after:

1. read-only preflight is independently verified;
2. executor is independently verified;
3. bounded dry-run/execute admin + CLI operational surface is independently verified;
4. all 13 blocker classes retain explicit fail-closed test evidence;
5. Phase 0 + H12 regressions remain green;
6. project status/changelog/handoff are reconciled;
7. implementation branches are merged/cleaned under the protected-branch rules.
