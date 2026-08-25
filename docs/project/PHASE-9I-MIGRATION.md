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

## Preflight — merged / verified tranche

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

## Executor contract — current tranche

The implementation under review is `Simplix\Pay\UPayments\Migration\MigrationExecutor`.

It must:

1. call a fresh preflight and act only on `MIGRATABLE`;
2. support true dry-run with zero writes/provider calls;
3. acquire the H12-compatible bootstrap lock when a secret is absent, otherwise the exact per-user/current-scope lock;
4. rerun preflight while holding the lock before mutation;
5. create a genuinely missing H12 secret only while the bootstrap lock is held and verify its exact readback;
6. rerun preflight after secret creation and require the exact same candidate token before provenance mutation;
7. derive current scope/generation from the validated secret record;
8. create only immutable H12 `legacy_compat` / `legacy_verified_capture` provenance;
9. verify provenance readback exactly;
10. rerun preflight after provenance creation and require `CLEAN`;
11. be idempotent under normal rerun and concurrent-worker completion;
12. record only a redacted Simplix-owned per-user ledger (`simplixpay_upayments_migration_v1`) containing token digest, never raw token;
13. perform zero provider calls and zero historical order-meta mutation.

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

## Executor test gate

`tests/harness/phase-9i-executor-harness.php` must run inside the required `H12 Regression Harness` CI job.

The executor harness uses the real Phase 9I preflight and real frozen H12 identity class. It must prove at minimum:

- zero-write dry-run;
- full missing-secret/unscoped migration;
- exact `legacy_compat` / `legacy_verified_capture` provenance;
- raw-token redaction from result/ledger;
- idempotent rerun;
- current-scope orphan migration without option write;
- malformed-secret fail-closed behavior;
- lock contention with zero mutation;
- evidence-change-under-lock fail closed;
- retry safety after provenance persistence failure;
- ledger-failure observability without identity rollback;
- cross-user conflict no-write behavior;
- already-clean dry-run no-write behavior;
- static prohibition on order mutation/provider transport/`create_201` fabrication.

Phase 0, Phase 9I preflight, H12 PHP and H12 Blocks harnesses must remain green unchanged.

## Operational contract — next Phase 9I tranche

Admin/CLI migration must be:

- explicit, not checkout-triggered;
- dry-run capable;
- bounded per invocation;
- resumable;
- idempotent;
- permission/capability checked;
- safe under concurrent workers;
- redacted: no raw token/card/API-key output;
- observable through reason/classification counters and per-user ledger state.

The intended CLI namespace remains `wp simplixpay-upayments`.

## Exit condition

Phase 9I is **not** complete when preflight or executor alone is merged.

The phase closes only after:

1. read-only preflight is independently verified;
2. executor is independently verified;
3. bounded dry-run/execute operational surface is independently verified;
4. all 13 blocker classes retain explicit fail-closed test evidence;
5. Phase 0 + H12 regressions remain green;
6. project status/changelog/handoff are reconciled;
7. implementation branches are merged/cleaned under the protected-branch rules.
