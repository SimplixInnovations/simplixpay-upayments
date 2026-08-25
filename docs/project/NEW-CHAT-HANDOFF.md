# SimplixPay for UPayments — Clean Chat Handoff

Use this file with root `AGENTS.md`, `PROJECT-STATUS.md`, the naming standard, the closed Phase 0 / Phase 9I / Provider Lifecycle records and the Master Engineering Playbook.

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

## Current program position

- Repository Foundation / Readiness: **DONE / VERIFIED**
- Phase 0 — release identity/updater ownership: **DONE / VERIFIED**
- Phase 9I — historical token-identity migration: **DONE / VERIFIED**
- Provider Contract & Payment Lifecycle: **DONE / VERIFIED**
- Current program gate: **Security Threat-Model Closure — DISCOVERY**
- Stable production release: **NO**
- WordPress.org release: **NO**

Always verify live GitHub before acting. Recorded SHAs are milestone evidence, not substitutes for fresh source/check/review verification.

## Latest verified implementation milestone — Provider Contract & Payment Lifecycle

PR #15 final reviewed head:

- `d2b08ebe1e65ad4ea8f4e06b41423e7bd9904fc3`

Verified squash merge on `main`:

- merge: `9569e39973a9e94926087738eae06c3846361943`
- tree: `40ec562674361624c2764263ba55cfba84594955`
- parent: `8e5a93ceb4f133663fdf433cc1a10b8b36c13d97`
- GitHub signature: **VERIFIED**
- `provider-lifecycle/discovery` branch: **deleted after verified merge**
- post-merge Quality Gates run #71 on `main`: **SUCCESS**

Exact final implementation-head evidence:

- Governance: **SUCCESS**
- tracked PHP syntax: **SUCCESS**
- Phase 0 release identity: **35 PASS / 0 FAIL**
- Phase 9I preflight: **123 PASS / 0 FAIL**
- Phase 9I executor: **59 PASS / 0 FAIL**
- Phase 9I operations: **81 PASS / 0 FAIL**
- Provider Payment Lifecycle: **141 PASS / 0 FAIL**
- Provider Exact Amount Binding: **4 PASS / 0 FAIL**
- H12 PHP: **1927 PASS / 0 FAIL**
- Blocks syntax: **SUCCESS**
- H12 Blocks: **144 PASS / 0 FAIL**

The exact PR merge-ref passed Quality Gates run #70 before merge; the full workflow passed again on merged `main` in run #71.

## Closed provider/payment lifecycle architecture

### Financial truth hierarchy

Only the authenticated status API can authorize payment state:

1. Bearer-authenticated Get Payment Status;
2. exact UPayments HTTPS status host/path allowlist;
3. HTTP 201 + strict top-level response shape;
4. full transaction binding;
5. exact provider-result classification;
6. locked WooCommerce state mutation.

Browser return and webhook payload fields are never financial truth by themselves.

### Binding contract

Before any financial transition, require exact:

- `track_id`;
- `merchant_requested_order_id` == local `UPayments_order_id`;
- provider `reference` == Woo order ID;
- mapped currency;
- canonical decimal amount equality;
- non-empty provider payment ID for CAPTURED.

Amount comparison never rounds through Woo display precision. `10.00` and `10.0000` are equivalent; `10.004` is not `10.00`.

### Result/state contract

- `CAPTURED` → canonical Woo `payment_complete($payment_id)`.
- `PENDING`, `AUTHORIZED`, `APPROVED` → remain unpaid + bounded reconciliation.
- provider `NULL`, Processing-style, unknown future values → INDETERMINATE, remain unpaid + bounded reconciliation.
- `FAILED`, `ERROR`, `NOT CAPTURED` → failed only if the order is not already paid/verified/refunded.
- `CANCELED` → cancelled only if the order is not already paid/verified/refunded.
- `REFUND` / `VOIDED` never prove an initial capture.

Verified capture is idempotent: replay does not re-query/re-complete after `_upay_verified_capture = 1`.

### Reconciliation contract

- callback routing may persist an **unverified** cursor only after local order/provider-order preflight;
- the cursor becomes **trusted** only after authenticated rebinding;
- cursor state is paired with current `UPayments_order_id` so a later Charge attempt on the same Woo order does not inherit stale attempt state;
- schedule at 60/120/240/480 seconds, maximum four attempts;
- deduplicate events;
- every attempt repeats full authenticated verification/binding;
- never retry/create Charge from reconciliation;
- binding conflicts and terminal/refund/verified states stop retries;
- exhaustion leaves the order unpaid and adds one sanitized manual-review note.

### Concurrency/transport contract

- per-order lifecycle lock uses exact database compare-and-swap stale takeover/release, not blind option deletion;
- callback GET/POST conflicts fail closed;
- cookies are excluded and new lifecycle code never uses `$_REQUEST`;
- status lookup rejects foreign/query-bearing URLs before credentials are sent;
- redirects disabled, TLS verification enabled, timeout finite;
- automated status lookup uses the stricter documented **30/minute** ceiling until provider documentation resolves its contradictory limit.

### Review findings fixed before merge

1. P1 rate-gate/wp_salt seam;
2. P1 first-query transient reconciliation gap;
3. P1 stale-lock takeover race;
4. P2 amount-rounding mismatch.

All review threads were resolved only after the fixes and regressions existed.

### Explicit unresolved boundaries

Do **not** silently claim these are solved:

- webhook HMAC/signature: provider public docs reviewed on 2026-08-25 did not provide a complete stable header/canonicalization/key contract;
- automatic refunds: unsupported pending a durable refund-intent/idempotency/reconciliation journal;
- arbitrary marketplace multi-split: not certified; current support remains one additional merchant allocation;
- subscription auto-deduction: retains its separate characterized path.

See `docs/project/PROVIDER-PAYMENT-LIFECYCLE.md` for the complete closed record.

## Phase 9I closed architecture

Phase 9I remains DONE / VERIFIED through PRs #11/#12/#13.

Contract:

- exact read-only `CLEAN`, `MIGRATABLE`, `BLOCKED`, `INDETERMINATE` classification;
- all 13 historical blocker families fail closed;
- executor acts only on fresh `MIGRATABLE` evidence under lock;
- only `legacy_compat` / `legacy_verified_capture` historical provenance;
- never fabricate `canonical` / `create_201` provenance;
- historical order metadata remains immutable;
- bounded admin/CLI dry-run + confirmed execute;
- redacted `_simplixpay_upayments_migration_result_v1` decision/result ledger;
- credential/mode/list-scoped durable resume without persisting API credentials.

A merchant site may still legitimately contain BLOCKED/INDETERMINATE users.

## Transitional install/i18n identities

Do not treat these as cosmetic unfinished branding:

- active main file remains `UPayments.php`;
- runtime/header text domain remains `upayments`.

Frozen eventual targets:

- `simplixpay-upayments.php`;
- text domain `simplixpay-upayments`.

Those require explicit package/upgrade and i18n/WPML migrations.

## Protected compatibility identities

Do not globally rename:

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
- malformed H12 secret is distinct from missing and fails closed.
- selected saved card requires current valid provenance + exact scope/generation + fresh provider Retrieve + exact membership.

## Next gate — Security Threat-Model Closure

**Status: DISCOVERY. Do not start with broad cleanup/refactoring.**

Required first sequence:

1. Freshly verify live `main`, PRs/branches/checks and current security-sensitive source.
2. Build an asset/trust-boundary/data-flow map across checkout, callback/status lifecycle, saved-card/customer-token identity, Phase 9I operations, subscriptions, admin settings, CLI, logging and CI/supply chain.
3. Enumerate threat scenarios and existing controls before implementation.
4. Characterize security-critical behavior with executable tests/static guards.
5. Prioritize P0/P1 exploitability paths and freeze narrow remediation contracts before edits.
6. Preserve closed payment-lifecycle/H12/Phase 9I contracts unless a security fix explicitly replaces one with stronger reviewed evidence.

Minimum audit scope:

- authorization/capabilities;
- CSRF/nonces;
- callback/webhook abuse and replay;
- IDOR/order-object references;
- SSRF/redirect/host allowlists;
- provider credentials, saved-card/customer-token secrets and migration roots;
- input parsing/type confusion/injection;
- output escaping/XSS;
- log/order-note/diagnostic redaction;
- concurrency/race/idempotency security;
- dependency/supply-chain/GitHub Actions trust;
- fail-closed behavior for undocumented provider security contracts.

## Permanent control plane

Read in this order:

1. `AGENTS.md`
2. `docs/project/PROJECT-STATUS.md`
3. `docs/project/NAMING-IDENTITY-STANDARD.md`
4. `docs/project/NEW-CHAT-HANDOFF.md`
5. `docs/project/PHASE-0-RELEASE-IDENTITY.md`
6. `docs/project/PHASE-9I-MIGRATION.md`
7. `docs/project/PROVIDER-PAYMENT-LIFECYCLE.md`
8. relevant sections of `docs/project/MASTER-ENGINEERING-PLAYBOOK.md`
9. `docs/project/REPOSITORY-AUDIT.md`
10. `docs/project/REPOSITORY-READINESS.md`
11. `docs/project/BASELINE-H12.md`

## Required working method

1. Verify live source/checks/review state before implementation.
2. Reconcile documentation drift first.
3. Prefer direct GitHub work wherever tools permit.
4. Preserve protected historical identities unless a separately approved/tested migration changes them.
5. Characterize before payment/security-critical refactors.
6. Never trust implementation/bot reports without exact independent verification.
7. Pin review/merge decisions to exact base/head SHAs.
8. Do not merge with unresolved valid findings or failing/missing required checks.
9. After merge, verify `main`, critical source/evidence, post-merge CI and branch cleanup before marking DONE.
10. Update `PROJECT-STATUS.md` after verified milestone/state changes.

## Program sequence

0. Repository Foundation / Readiness — **DONE / VERIFIED**
1. Phase 0 — release identity/updater ownership — **DONE / VERIFIED**
2. Phase 9I — Historical token-identity migration — **DONE / VERIFIED**
3. Provider Contract & Payment Lifecycle — **DONE / VERIFIED**
4. Security Threat-Model Closure — **CURRENT / DISCOVERY**
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

Read AGENTS.md first, then docs/project/PROJECT-STATUS.md, docs/project/NAMING-IDENTITY-STANDARD.md, docs/project/NEW-CHAT-HANDOFF.md, docs/project/PHASE-0-RELEASE-IDENTITY.md, docs/project/PHASE-9I-MIGRATION.md, docs/project/PROVIDER-PAYMENT-LIFECYCLE.md and relevant sections of docs/project/MASTER-ENGINEERING-PLAYBOOK.md.

Treat recorded SHAs/status as milestone evidence until live GitHub is independently verified. Reconcile drift before work.

Repository readiness, Phase 0, Phase 9I and Provider Contract & Payment Lifecycle are DONE / VERIFIED. The current gate is Security Threat-Model Closure — DISCOVERY.

Start with a fresh security asset/trust-boundary/data-flow map and current exact source. Characterize authorization, CSRF, callback/replay/IDOR/SSRF, credentials/secrets, input/output, logging/redaction, concurrency/idempotency and supply-chain boundaries before proposing runtime changes. Preserve the closed payment-lifecycle, H12 and Phase 9I contracts unless a stronger security contract explicitly replaces them.

Work directly in GitHub wherever tools permit. Never approve or merge without independent exact-SHA source/diff/check/review verification and post-merge verification.
```
