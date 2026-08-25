# Phase 9I — Historical Token-Identity Migration

**Status:** DONE / VERIFIED

**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

**Current development version:** `0.1.0`

## Objective

Safely classify and migrate historical UPayments customer-token identity evidence that H12 correctly refuses to guess.

Phase 9I extends the frozen H12 identity model; it does not replace it. Existing `UPayments\Token\CustomerTokenIdentity` runtime behavior remains the regression oracle while migration orchestration lives under `Simplix\Pay\UPayments\Migration`.

## Closure decision

Phase 9I is **DONE / VERIFIED** because the full required architecture has been independently reviewed, merged and post-merge verified:

1. deterministic read-only preflight;
2. locked fail-closed executor;
3. bounded admin/CLI operational surface with durable redacted per-user result checkpoints;
4. all 13 historical blocker families retain explicit fail-closed evidence;
5. Phase 0 and H12 regressions remained green through the final implementation tranche;
6. implementation branches were merged and cleaned.

Phase completion certifies the migration **system and safety contract**. It does **not** mean every merchant installation has been automatically classified or migrated. Site-specific migration remains an explicit bounded operational action. A real site may legitimately contain `BLOCKED` or `INDETERMINATE` users; those states remain fail closed.

## Non-negotiable rule

Historical identity must never be promoted by inference when attribution is ambiguous.

The core preflight is read-only. It performs zero provider calls, secret creation/rotation, option writes, user-meta writes, order-meta writes, or checkout-hot-path migration work.

The operational dry-run wrapper is identity/provider non-mutating but intentionally persists only its separate redacted operations-result checkpoint so interrupted batches can resume safely.

## Exact preflight classifications

Every evaluated user resolves to exactly one of:

- `CLEAN` — no migration is needed and evidence is internally consistent;
- `MIGRATABLE` — one attributable legacy customer token can be migrated under an explicit legacy provenance contract;
- `BLOCKED` — contradictory, malformed, security-sensitive or otherwise unsafe evidence requires manual investigation or a future explicit repair policy;
- `INDETERMINATE` — the system cannot prove a complete trustworthy view of the relevant history.

`BLOCKED` and `INDETERMINATE` are fail-closed states. The executor never acts on them.

## Frozen migration provenance

A Phase 9I migration may create only:

- kind: `legacy_compat`;
- source: `legacy_verified_capture`.

It must never fabricate `canonical` / `create_201`. Canonical provenance remains proof of the strict provider Create-token 201 contract only.

## Preflight — DONE / VERIFIED

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

## Thirteen blocker classes — verified disposition

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

These are no longer unimplemented Phase 9I gaps. They remain permanent safety classifications unless a later separately reviewed repair/migration contract deliberately changes a case.

## Executor — DONE / VERIFIED

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
2. supports true core dry-run with zero identity writes/provider calls;
3. acquires the H12-compatible bootstrap lock when a secret is absent, otherwise the exact per-user/current-scope lock;
4. reruns preflight while holding the lock before mutation;
5. creates a genuinely missing H12 secret only while the bootstrap lock is held and verifies exact readback;
6. reruns preflight after secret creation and requires the exact same candidate token before provenance mutation;
7. derives current scope/generation from the validated secret record;
8. creates only immutable H12 `legacy_compat` / `legacy_verified_capture` provenance;
9. verifies provenance readback exactly;
10. reruns preflight after provenance creation and requires `CLEAN`;
11. is idempotent under normal rerun and characterized concurrent-worker completion;
12. records only a redacted Simplix-owned successful-migration identity ledger (`simplixpay_upayments_migration_v1`) containing token digest, never raw token;
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
- executor identity-ledger failure after verified identity migration: do not roll back valid provenance; surface `migrated_ledger_write_failed` so operations can repair observability separately.

## Operational surface — DONE / VERIFIED

The operational tranche is intentionally explicit rather than auto-discovering every historical customer. Automatic discovery would require its own bounded, resumable global-census contract and is not silently introduced here.

Verified merge milestone:

- PR #13;
- final reviewed head `2989862683754f8a8eda8e9d4239ada4a61b23f4`;
- squash merge `db1c4ea4dab45bc1ffaf4529e0ccb940153cd999`;
- tree `5bec24ad26c66a504cd0dd609f4311f9e70add76`;
- parent `708253bd9d0daf217735fbb087b360e8b848136c`;
- GitHub signature: VERIFIED;
- implementation branch `phase-9i/operations`: deleted after verified merge.

### Shared batch engine

`Simplix\Pay\UPayments\Migration\MigrationBatch`:

- accepts an explicit list of positive user IDs;
- maximum submitted list: **500 users**;
- default users per invocation: **20**;
- hard maximum users per invocation: **50**;
- rejects duplicate, leading-zero, exponent, negative, zero, overflow and malformed IDs;
- supports explicit zero-based `offset` plus returned `next_offset`;
- persists a separate redacted operations result ledger for **every processed user** at `_simplixpay_upayments_migration_result_v1`, including `CLEAN`, `BLOCKED`, `INDETERMINATE`, dry-run and executor-exception outcomes;
- records position, next offset, input count, mode, dry-run/write mode, sanitized result fields and timestamp, but never raw tokens, API credentials or nested preflight/provider payloads;
- scopes durable resume evidence with an HMAC-SHA256 batch fingerprint keyed by the in-memory existing API key, so the credential itself is never persisted and stale results from another credential/mode/list cannot be reused;
- can recover the first not-durably-evaluated position for the exact credential/mode/list through `resumeOffset()`;
- treats a persisted failed/BLOCKED/INDETERMINATE outcome as evaluated for resume purposes; an operator can deliberately re-evaluate it by choosing an explicit offset rather than resume mode;
- stops the page if the operations result ledger cannot be durably written, surfaces `batch_checkpoint_failed`, and leaves that same user as the retry offset;
- never rolls back an already-verified identity mutation merely because the auxiliary operations ledger failed; executor idempotency makes re-evaluation safe;
- isolates one-user executor exceptions while persisting their sanitized outcome when storage is available;
- exposes only redacted per-user fields and aggregate reason/classification counters;
- does not persist API credentials, raw tokens, unbounded batch queues or checkout state.

The operations result ledger is deliberately separate from `MigrationExecutor`'s `simplixpay_upayments_migration_v1` identity-migration ledger. The latter remains evidence only for a successfully verified migration; the operations ledger records the decision/result checkpoint for every evaluated user.

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

- `preflight --user-ids=<ids> [--offset=<n> | --resume] [--limit=<n>]`
- `execute --user-ids=<ids> --yes [--offset=<n> | --resume] [--limit=<n>]`

`--resume` recovers the first user without a matching durable operations-result checkpoint for the exact credential/mode/list. It is mutually exclusive with explicit `--offset`; explicit offset remains the deliberate re-evaluation mechanism.

Write mode requires explicit `--yes`. Failed batches emit their redacted JSON result before terminating non-zero. There is deliberately no `--api-key` argument.

### WooCommerce admin

A WooCommerce submenu page is registered as **SimplixPay Migration**.

Security/operations controls:

- capability: `manage_woocommerce`;
- WordPress nonce required for POST;
- default mode is identity-nonmutating dry-run preflight plus redacted operations-result checkpoint persistence;
- execute mode requires a separate explicit confirmation checkbox;
- user IDs, explicit offset and batch limit are strictly validated;
- durable-resume checkbox recovers the first not-durably-evaluated position and cannot be combined with a nonzero explicit offset;
- no credential field exists;
- output is escaped, redacted JSON only.

### Runtime isolation

`MigrationBootstrap` is loaded by the plugin bootstrap but exits immediately unless the request is WordPress admin or WP-CLI.

It registers no checkout, Store API, frontend, cron or provider hooks. Operational source contains no provider transport path. Historical order metadata remains immutable.

### Operations test gate

`tests/harness/phase-9i-operations-harness.php` is permanently required inside `H12 Regression Harness` CI.

It covers strict ID parsing, settings resolution/redaction, bounded page behavior, per-user durable result persistence, BLOCKED/exception ledger coverage, durable resume recovery, credential/mode/dry-run checkpoint isolation, checkpoint-write failure/retry semantics, per-user failure aggregation, invalid-window no-execution behavior, safe CLI output, nonzero CLI failure status, CLI execute confirmation, admin capability/nonce/confirmation/resume source contracts, canonical CLI namespace, no checkout/frontend hook, no provider transport and hard batch bounds.

## Final implementation-head regression evidence

Exact reviewed PR #13 head `2989862683754f8a8eda8e9d4239ada4a61b23f4` passed:

- Governance: **SUCCESS**
- tracked PHP syntax: **SUCCESS**
- Phase 0 release identity: **35 PASS / 0 FAIL**
- Phase 9I preflight: **123 PASS / 0 FAIL**
- Phase 9I executor: **59 PASS / 0 FAIL**
- Phase 9I operations: **81 PASS / 0 FAIL**
- H12 PHP: **1927 PASS / 0 FAIL**
- Blocks syntax: **SUCCESS**
- H12 Blocks: **144 PASS / 0 FAIL**

The separate Phase 9I closure documentation PR must rerun this complete stack before its own merge. Targeted harness success remains regression evidence, not broad production/platform certification.

## Closed exit condition

The original exit conditions are satisfied:

1. read-only preflight independently verified — **YES**;
2. executor independently verified — **YES**;
3. bounded dry-run/execute admin + CLI operational surface independently verified — **YES**;
4. all 13 blocker classes retain explicit fail-closed test evidence — **YES**;
5. Phase 0 + H12 regressions remained green — **YES**;
6. project status/changelog/README/roadmap/handoff reconciled by the closure tranche — **YES, subject to closure-PR merge verification**;
7. implementation branches merged/cleaned under protected-branch rules — **YES**.

The next program gate is **Provider Contract & Payment Lifecycle — DISCOVERY**.
