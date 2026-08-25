# SimplixPay for UPayments — Clean Chat Handoff

Use this file with root `AGENTS.md`, `PROJECT-STATUS.md`, the naming standard, the closed Phase 0 record and the Master Engineering Playbook.

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
- Current implementation gate: **Phase 9I — Historical token-identity migration**
- Stable production release: **NO**
- WordPress.org release: **NO**

Always verify live GitHub before acting; recorded SHAs below are milestone evidence, not substitutes for a fresh check.

## Latest verified implementation milestone

Phase 0 implementation PR #9:

- final reviewed head: `8b67259bd05453150f837cda4b961f649f50cf02`
- squash merge on `main`: `678f3bdae32b7a0d5922c6ebb7fa7535ede256dd`
- merged tree: `80618e737476a92357bd463f6e1495c364157e83`
- GitHub signature: **VERIFIED**

Post-merge verification proved:

- plugin header: **SimplixPay for UPayments 0.1.0** / Simplix Innovations;
- canonical release identity: `Simplix\Pay\UPayments\Release\Identity`;
- inherited `upaymentskwt/woocommerce` updater authority removed;
- bundled `vendor/plugin-update-checker/` absent;
- external self-update channel intentionally `disabled` pending tested package/basename migration;
- uninstall non-destructive by default;
- implementation branch auto-deleted and open PRs returned to zero.

Phase 0 red → green evidence:

- characterization before implementation: **22 PASS / 13 FAIL**;
- final Phase 0 harness: **35 PASS / 0 FAIL**;
- H12 PHP: **1927 PASS / 0 FAIL**;
- H12 Blocks: **144 PASS / 0 FAIL**;
- tracked PHP syntax and Governance: **SUCCESS**.

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

Frozen Phase 0 H12 anchors outside the intentionally changed bootstrap:

- CustomerTokenIdentity `85430d37e9baf540842f5655b86ccf0eca3e6aea`
- Blocks `813d192d69c069eb7ee11df93acc9dbdf03e270a`
- Scheduler `5251866d4df2d1326e7c09f0c8ec1d146c0bb325`
- CycleClaim `c34d83e2d77cc65024fe663e4c378cecb2b17347`

## Phase 9I — current gate

### Required preflight

Read-only and deterministic. It must perform **zero provider calls and zero identity writes** and classify each evaluated state as exactly one of:

- `CLEAN`
- `MIGRATABLE`
- `BLOCKED`
- `INDETERMINATE`

### Executor

Execution is permitted only for explicit `MIGRATABLE` cases. Attributable historical identity may become `legacy_compat` / `legacy_verified_capture`. Never fabricate `canonical` / `create_201` provenance.

### Operational surface

Must be bounded, idempotent and resumable, support dry-run, use per-user ledger/state, and avoid unbounded historical scans on checkout hot paths.

### Thirteen blocker classes

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

Do not approve a Phase 9I implementation that handles only an easy subset while silently ignoring the remaining classes.

## Permanent control plane

Read in this order:

1. `AGENTS.md`
2. `docs/project/PROJECT-STATUS.md`
3. `docs/project/NAMING-IDENTITY-STANDARD.md`
4. `docs/project/NEW-CHAT-HANDOFF.md`
5. `docs/project/PHASE-0-RELEASE-IDENTITY.md` — closed Phase 0 evidence
6. relevant sections of `docs/project/MASTER-ENGINEERING-PLAYBOOK.md`
7. `docs/project/REPOSITORY-AUDIT.md`
8. `docs/project/REPOSITORY-READINESS.md` — closed foundation evidence
9. `docs/project/BASELINE-H12.md`

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
2. Phase 9I — Historical token-identity migration — **CURRENT**
3. Provider contract + payment lifecycle/state-machine audit
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

Read AGENTS.md first, then docs/project/PROJECT-STATUS.md, docs/project/NAMING-IDENTITY-STANDARD.md, docs/project/NEW-CHAT-HANDOFF.md, docs/project/PHASE-0-RELEASE-IDENTITY.md and relevant sections of docs/project/MASTER-ENGINEERING-PLAYBOOK.md.

Treat all recorded SHAs/status as milestone evidence until you independently verify live GitHub main, branches, open PRs, current source and checks. Reconcile any drift before work.

Repository readiness and Phase 0 are DONE / VERIFIED. The current implementation gate is Phase 9I historical token-identity migration.

Work directly in GitHub wherever tools permit. Delegate only genuinely inaccessible actions. Preserve protected historical upayments/upay identities unless an approved tested migration changes them. Never approve or merge without independent exact-SHA source/diff/check/review verification.

Continue from the first unfinished Phase 9I task.
```
