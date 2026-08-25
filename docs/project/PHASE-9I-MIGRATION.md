# Phase 9I — Historical Token-Identity Migration

**Status:** IN PROGRESS

**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

**Current development version:** `0.1.0`

## Objective

Safely classify and migrate historical UPayments customer-token identity evidence that H12 correctly refuses to guess.

Phase 9I extends the frozen H12 identity model; it does not replace it. Existing `UPayments\Token\CustomerTokenIdentity` runtime behavior remains the regression oracle while new migration orchestration lives under `Simplix\Pay\UPayments\Migration`.

## Non-negotiable rule

Historical identity must never be promoted by inference when attribution is ambiguous.

Preflight is read-only. It performs:

- zero provider calls;
- zero secret creation/rotation;
- zero option writes;
- zero user-meta writes;
- zero order-meta writes;
- zero checkout-hot-path migration work.

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

It must never fabricate:

- kind: `canonical`;
- source: `create_201`.

Canonical provenance remains proof of the strict provider Create-token 201 contract only.

## Preflight evidence model

The current preflight implementation is `Simplix\Pay\UPayments\Migration\MigrationPreflight`.

It reuses H12 public validators/readers including:

- secret-record validation;
- atomic current scope/generation context;
- authoritative provenance reading;
- prior-provenance inspection;
- exact historical metadata cardinality;
- order-meta force refresh;
- legacy/canonical token grammar.

It independently performs the migration-specific attribution/collision census that H12 intentionally does not perform.

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

The provenance collision query may use SQL only as a bounded candidate discovery mechanism. Exact decoded-record comparison is mandatory before declaring a conflict.

Raw customer tokens are security-sensitive migration material. Preflight exposes a SHA-256 digest for reporting; any raw token exists only in the in-memory migration payload supplied to the executor. CLI/admin/log output must never print the raw token.

## Thirteen blocker classes

### 1. Unscoped legacy tokens

Potentially `MIGRATABLE` only when the complete relevant history contains exactly one valid legacy token and cross-user attribution is clear.

### 2. Current-scope orphan histories

Potentially `MIGRATABLE` only when the orphan snapshot already asserts `legacy_compat`, token/scope/generation are structurally valid, scope/generation equal the current identity context, and attribution is unambiguous.

An orphan claiming `canonical` without an authoritative provenance record is `BLOCKED`; Phase 9I will not invent Create-201 proof.

### 3. Cross-user token conflicts

`BLOCKED`.

### 4. Malformed scoped histories

`BLOCKED`.

### 5. Secret generation mismatches

`BLOCKED`.

### 6. Card-token-only historical identity

`BLOCKED`.

A credit-card token is not a customer identity token and must not be used to synthesize one.

### 7. Prior-scope same-generation histories

`BLOCKED`.

Changing scope may represent a credential/mode boundary; migration cannot collapse that boundary by assumption.

### 8. Non-scalar or duplicate evidence

`BLOCKED`.

### 9. Orphan metadata

Partial kind/scope/generation evidence without an attributable customer token is `BLOCKED`.

### 10. >200 or otherwise incomplete history

`INDETERMINATE`.

### 11. Unloadable orders

`INDETERMINATE`.

### 12. Force-refresh failures

`INDETERMINATE`.

### 13. Malformed versus missing secret

A malformed existing secret is `BLOCKED` and must never be silently replaced.

A genuinely missing secret is distinct. Secret creation is allowed only by the future executor after a locked re-preflight proves a single attributable legacy token and zero provenance artifacts under the missing root.

## Additional contradictions

The preflight also fails closed on:

- multiple different customer tokens for one user;
- historical token contradicting current authoritative provenance;
- current or prior malformed provenance;
- provenance generation mismatch;
- scoped history while the secret is missing;
- duplicate order IDs or unstable pagination totals/page counts;
- malformed query/result shapes;
- invalid historical order IDs;
- cross-user attribution scans that cannot be proven complete.

## Executor contract — next tranche

The executor is not part of the initial preflight PR.

When implemented it must:

1. accept only a fresh `MIGRATABLE` preflight decision;
2. acquire a bounded per-user/current-context migration lock;
3. rerun preflight under that lock before any mutation;
4. create a missing H12 secret only through a separately safe/verified transition;
5. derive the authoritative current scope/generation from the resulting valid secret;
6. normalize only the exact candidate historical order snapshots identified by preflight;
7. create immutable H12 provenance only as `legacy_compat` / `legacy_verified_capture`;
8. verify durable persistence after every mutation;
9. fail closed and provide explicit rollback/recovery semantics for partial failure;
10. be idempotent: a successful rerun resolves to `CLEAN` with no duplicate provenance/order mutation;
11. record a per-user migration ledger using new Simplix-prefixed state and token digest only;
12. perform zero provider calls.

## Operational contract — later Phase 9I tranche

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

## Preflight test gate

`tests/harness/phase-9i-preflight-harness.php` is a standalone synthetic WP/Woo harness for the new preflight layer.

Every scenario asserts:

- exact classification;
- exact reason;
- option writes = 0;
- user-meta writes = 0;
- order writes = 0;
- provider calls = 0.

It covers all 13 named blocker classes plus fresh state, valid current provenance, token contradictions and database uncertainty.

The existing Phase 0 and H12 harnesses must remain green unchanged.

## Exit condition

Phase 9I is **not** complete when preflight alone is merged.

The phase closes only after preflight, executor and bounded operational surface are independently verified; all 13 blocker classes have explicit test evidence; H12 remains green; project status is reconciled; and all implementation branches are merged/cleaned under the normal protected-branch rules.
