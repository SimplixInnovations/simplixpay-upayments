# SimplixPay for UPayments — Clean Chat Handoff

Use this file with root `AGENTS.md`, `PROJECT-STATUS.md`, the naming standard, the closed Phase 0 / Phase 9I / Provider Lifecycle / Security Threat-Model records and the Master Engineering Playbook.

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
- Security Threat-Model Closure: **DONE / VERIFIED**
- Architecture discovery: **DONE / VERIFIED**
- Architecture A1 — provider endpoint/mode resolution: **DONE / VERIFIED**
- Architecture A2 — payment-method availability client/cache: **DONE / VERIFIED**
- Current program gate: **Architecture & Code-Quality Foundation — A3**
- Stable production release: **NO**
- WordPress.org release: **NO**

Always verify live GitHub before acting. Recorded SHAs are milestone evidence, not substitutes for fresh source/check/review verification.

## Latest verified milestone — Architecture A2

PR #22 final reviewed head `bdb627520aa28e71b69a91f8ef71d04d257a3ad8` was squash-merged as verified commit `f85894271e8f991e77a8e6a2b306f4d191483bbd`, tree `1addbcc02e0d30f57a948cafd8111fb94e60c4da`, on parent `d43d175a1443709d42efabfbe78519a5a84f4dc9`. Exact-head Quality Gates run #155 and post-merge run #156 passed; Payment-Method Availability was **102/0** and the implementation branch was deleted.

## Previous verified milestone — Architecture A1

PR #21 final reviewed head `baed693964556120dc7ad07dbc740d3acc1af20f` was squash-merged as verified commit `d43d175a1443709d42efabfbe78519a5a84f4dc9`, tree `ddb2ac7cd8b2d4f454867e10bc361fee94dbcf4b`, on parent `596ffb433813cdc06e81d67162617b3019af686b`. Exact-head Quality Gates run #152 and post-merge run #153 passed; Provider Endpoints was **49/0** and the implementation branch was deleted.

## Previous verified milestone — Architecture discovery

PR #19 final reviewed head `6e51b1c1c5649313acf86943e30793c38bc71f14` was squash-merged as verified commit `596ffb433813cdc06e81d67162617b3019af686b`, tree `3fcaed35546a6b1407d2a46797630e46301e65ef`, on parent `ddb3fc901c5dc949c634f745c4c3a7ec2a72414c`. Exact-head Quality Gates run #147 and post-merge run #148 passed; the implementation branch was deleted. Final architecture counters were Foundation **67/0**, Runtime Bindings **138/0** and Bootstrap Paths **153/0**.

## Previous verified implementation milestone — Security Threat-Model Closure

PR #17 final reviewed head:

- `fba12225899c3e01d6b23a6bba2f757a3b5f6a4a`

Verified squash merge on `main`:

- merge: `01f3fc59eed8641b3e5372558f61a7a0f0cdfac9`
- tree: `e0027005f059fad03d8c08273b7aac6553c45f53`
- parent: `08054a93c619f3c34fef747a6e530abce1e8986e`
- GitHub signature: **VERIFIED**
- `security/threat-model-discovery` branch: **deleted after verified merge**
- exact PR merge-ref Quality Gates run #88: **SUCCESS**
- post-merge Quality Gates run #89 on `main`: **SUCCESS**

Exact final implementation-head evidence:

- Governance: **SUCCESS**
- tracked PHP syntax: **SUCCESS**
- Phase 0: **35 PASS / 0 FAIL**
- Phase 9I preflight/executor/operations: **123/0 + 59/0 + 81/0**
- Provider Lifecycle / Exact Amount: **141/0 + 4/0**
- Security Threat-Model: **81 PASS / 0 FAIL**
- H12 PHP: **1927 PASS / 0 FAIL**
- Blocks syntax: **SUCCESS**
- H12 Blocks: **144 PASS / 0 FAIL**

### Closed bounded security architecture

- public legacy status polling requires an UPayments order plus exact owner or exact Woo order key; numeric ID alone is never authority;
- subscription pause/resume/unsubscribe is POST-only with exact owner, action nonce, subscription object preflight and valid transition;
- checkout has no Google Fonts/cdnjs Font Awesome dependency; classic and Blocks chevrons are local;
- plain provider/order metadata and stored settings are escaped to their exact output context;
- checkout templates exclude `$_REQUEST`;
- product-meta writes mirror WooCommerce nonce/post-ID/capability preconditions;
- payment host/TLS/redirect/Bearer controls, H12 identity, Phase 9I authorization, no-blind-retry and immutable Actions pins remain protected by regression evidence.

### Explicit unresolved security/feature boundaries

Do not silently claim these are solved:

- UPayments webhook HMAC/signature remains provider-document unresolved; authenticated Get Payment Status remains financial truth;
- subscription auto-deduction remains a separately characterized no-blind-retry/cycle-journal path, not broad recurring-billing certification;
- automatic WooCommerce refunds remain unsupported pending durable idempotency/reconciliation design;
- this closure is not broad penetration-test, PCI/compliance, platform, feature, performance or production certification.

See `docs/project/SECURITY-THREAT-MODEL.md` for the complete closed record.

## Previous verified implementation milestone — Provider Contract & Payment Lifecycle

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

## Current tranche — Architecture & Code-Quality Foundation A3

**Status: A3 — GATEWAY SETTINGS/ADMIN/SINGLE-ADDITIONAL-MERCHANT PRESENTATION / IMPLEMENTATION.**

Required bounded sequence:

1. Work only from verified A2 merge `f85894271e8f991e77a8e6a2b306f4d191483bbd` on `architecture/a3-gateway-settings-admin-multimerchant`.
2. Extract only gateway settings schema/validation, one-row allocation presentation and admin-asset scoping to `src/Admin/GatewaySettings.php` behind the existing public gateway methods.
3. Preserve `woocommerce_upayments_settings`, all 21 field keys/order/defaults and the five legacy allocation scalar keys.
4. Preserve save-card/subscription normalization, settings errors, disabled-allocation clearing, escaped stored presentation and exact admin asset handles/paths/scopes.
5. Preserve exactly one additional `extraMerchantData` entry and leave `process_payment()`, provider payload construction, authenticated transport, credentials, payment truth and order state unchanged.
6. Keep every closed regression plus all six architecture harnesses mandatory.
7. Do not begin A4 until the exact A3 head is independently reviewed, green, merged, post-merge verified and its branch deleted.

The architecture gate may improve structure; it may not reinterpret provider truth, weaken authorization, or silently broaden certified feature/platform claims.

## Permanent control plane

Read in this order:

1. `AGENTS.md`
2. `docs/project/PROJECT-STATUS.md`
3. `docs/project/NAMING-IDENTITY-STANDARD.md`
4. `docs/project/NEW-CHAT-HANDOFF.md`
5. `docs/project/PHASE-0-RELEASE-IDENTITY.md`
6. `docs/project/PHASE-9I-MIGRATION.md`
7. `docs/project/PROVIDER-PAYMENT-LIFECYCLE.md`
8. `docs/project/SECURITY-THREAT-MODEL.md`
9. relevant sections of `docs/project/MASTER-ENGINEERING-PLAYBOOK.md`
10. `docs/project/REPOSITORY-AUDIT.md`
11. `docs/project/REPOSITORY-READINESS.md`
12. `docs/project/BASELINE-H12.md`

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
4. Security Threat-Model Closure — **DONE / VERIFIED**
5. Architecture & Code-Quality Foundation — **CURRENT / A3**
6. Full automated quality platform
7. Platform certification: Woo/WP/PHP/HPOS/Blocks/WPML
8. Feature certification
9. Performance/UX/operations/diagnostics
10. Release engineering/distribution/WordPress.org when eligible
11. Continuous maintenance

## Copy-ready opening prompt

```text
Continue the SimplixPay for UPayments engineering program in SimplixInnovations/simplixpay-upayments.

Read AGENTS.md first, then docs/project/PROJECT-STATUS.md, docs/project/NAMING-IDENTITY-STANDARD.md, docs/project/NEW-CHAT-HANDOFF.md, docs/project/PHASE-0-RELEASE-IDENTITY.md, docs/project/PHASE-9I-MIGRATION.md, docs/project/PROVIDER-PAYMENT-LIFECYCLE.md, docs/project/SECURITY-THREAT-MODEL.md and the relevant living sections of docs/project/MASTER-ENGINEERING-PLAYBOOK.md.

Treat recorded SHAs/status as verified milestone anchors, not substitutes for live GitHub. Freshly verify current main, open PRs/branches, checks, review state and current source before acting; reconcile any drift first.

Repository readiness, Phase 0, Phase 9I, Provider Contract & Payment Lifecycle, Security Threat-Model Closure, Architecture discovery, A1 and A2 are DONE / VERIFIED. A2 was squash-merged from PR #22 as verified main f85894271e8f991e77a8e6a2b306f4d191483bbd, tree 1addbcc02e0d30f57a948cafd8111fb94e60c4da; exact-head run #155 and post-merge run #156 passed. The current permitted gate is Architecture & Code-Quality Foundation — A3.

Implement only the A3 gateway settings/admin/single-additional-merchant presentation boundary behind the existing public compatibility methods. Preserve every existing option key and field default, save-card/subscription normalization, settings errors, five allocation scalar keys, attribute escaping, admin asset scopes and exactly one runtime extraMerchantData entry. Change no endpoint, authenticated transport, credential, provider payload, payment-truth or order-state behavior. Keep all existing regression suites and the six architecture harnesses mandatory.

Do not claim broad security, PCI/compliance, platform, feature, performance or production certification from the bounded security closure. UPayments webhook signature details remain provider-document unresolved, automatic refunds remain unsupported pending durable idempotency/reconciliation design, and subscription auto-deduction remains separately characterized rather than broadly certified.

Work directly in GitHub wherever tools permit. Never approve or merge without independent exact-SHA source/diff/check/review verification and post-merge verification.
```
