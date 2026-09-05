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
- Architecture A3 — gateway settings/admin presentation: **DONE / VERIFIED**
- Architecture A4 — subscription product/account presentation: **DONE / VERIFIED**
- Architecture A5 — checkout payload/orchestration core: **DONE / VERIFIED**
- Quality Platform Q1 foundation: **DONE / VERIFIED**
- Quality Platform Q2 CheckoutPayload analysis: **DONE / VERIFIED**
- Quality Platform Q3 payment-concurrency analysis: **DONE / VERIFIED**
- Quality Platform Q4 authenticated-status analysis: **DONE / VERIFIED**
- Quality Platform Q5 payment-method availability analysis: **DONE / VERIFIED**
- Quality Platform Q6 gateway-settings analysis: **DONE / VERIFIED**
- Quality Platform Q7 public-order-status analysis: **DONE / VERIFIED**
- Quality Platform Q8 release-identity analysis: **DONE / VERIFIED**
- Quality Platform Q9 migration-settings analysis: **DONE / VERIFIED**
- Quality Platform Q10 migration-bootstrap analysis: **DONE / VERIFIED**
- Quality Platform Q11 subscription-composition analysis: **DONE / VERIFIED**
- Quality Platform Q12 subscription-product-type analysis: **DONE / VERIFIED**
- Quality Platform Q13 migration-CLI analysis: **DONE / VERIFIED**
- Quality Platform Q14 migration-admin analysis: **DONE / VERIFIED**
- Quality Platform Q15 subscription-presentation analysis: **DONE / VERIFIED**
- Current program gate: **Full Automated Quality Platform — Q16**
- Stable production release: **NO**
- WordPress.org release: **NO**

Always verify live GitHub before acting. Recorded SHAs are milestone evidence, not substitutes for fresh source/check/review verification.

## Latest verified milestone — Quality Platform Q15

PR #41 final reviewed head `01a06d45fcc0bc3d08da8d58f6be177b232bb1d4`, tree `ea5b0b3880a99999577d51a9ed5f6a8c77a52cf0`, was squash-merged as commit `a4bbb05021dbded73072c0ba108a18245b60ad88` on sole parent `22857f6304d4b4f19ec1cb6303a80d120173bcd1`. Exact-head Quality Gates run #253 and post-merge Quality Gates run #254 passed all five jobs; PHPUnit was **144 tests / 899 assertions**, Q15 was **107/0**, H12 PHP was **1927/0**, H12 Blocks was **144/0**, final exact-head review was clean after its valid findings were fixed and regression-guarded, and the implementation branch was deleted after verified merge.

## Previous verified milestone — Quality Platform Q14

PR #40 final reviewed head `b2d8630a5903af8f26a7f770a2a80547c871f7c6` was squash-merged as commit `22857f6304d4b4f19ec1cb6303a80d120173bcd1`, tree `53107c93c8756985461a8d75e2009c91b89ee851`, on sole parent `a744417e1ec2f40b4f59706df84589d8b18638cb`. Exact-head Quality Gates run #247 and post-merge run #248 passed all five jobs; PHPUnit was **129 tests / 825 assertions**, Q14 was **109/0**, PHPStan/PHPCS/audit and every historical/architecture/H12 regression were green, final independent exact-head review was clean with zero unresolved threads, the GitHub signature was valid, and the implementation branch was deleted.

## Previous verified milestone — Quality Platform Q5

PR #31 final reviewed head `d4132b0caccaa6edc6d7421afcfd8e9694563224` was squash-merged as commit `984053aee6bb50e62e457a639f44307e461f5e38`, tree `dee657b03f8d44670b0ae2501a40dabf718d4bb2`, on sole parent `4b3db92b0ded0c598bad0ab677babab9e6102811`. Exact-head Quality Gates run #197 and post-merge run #198 passed all five jobs; PHPUnit was **47 tests / 444 assertions**, Q1 was **74/0**, Q2 was **64/0**, Q3 was **69/0**, Q4 was **68/0**, Q5 was **83/0**, PHPStan/PHPCS/audit and every historical/architecture/H12 regression were green, final independent exact-head review found no major issues after the valid living-state P2 was fixed, and the implementation branch was deleted.

## Previous verified milestone — Quality Platform Q4

PR #30 final reviewed head `8543bdfce1a4e216200791dc5637b646f49bcb59` was squash-merged as commit `4b3db92b0ded0c598bad0ab677babab9e6102811`, tree `ad5ae98d5e935bb48d1441f94e130f5d3adb3ca9`, on sole parent `30e99a6a456b72709c87e442b8437301ba64e99b`. Exact-head Quality Gates run #194 and post-merge run #195 passed all five jobs; PHPUnit was **39 tests / 327 assertions**, Q1 was **74/0**, Q2 was **64/0**, Q3 was **69/0**, Q4 was **68/0**, PHPStan/PHPCS/audit and every historical/architecture/H12 regression were green, final independent exact-head review found no major issues after every valid P2 was fixed, and the implementation branch was deleted.

## Previous verified milestone — Quality Platform Q3

PR #29 final reviewed head `e08be468b5453524996c525860c12d5619081132` was squash-merged as commit `30e99a6a456b72709c87e442b8437301ba64e99b`, tree `703a56c03e95862b8b4807d9a1ea28e2e3e201dd`, on sole parent `356680b9fe8a2724e778d40386ca182247715249`. Exact-head Quality Gates run #188 and post-merge run #189 passed all five jobs; PHPUnit was **31 tests / 220 assertions**, Q1 was **74/0**, Q2 was **64/0**, Q3 was **69/0**, PHPStan/PHPCS/audit and every historical/architecture/H12 regression were green, final independent exact-head review found no major issues after every valid P1/P2 was fixed, and the implementation branch was deleted.

## Previous verified milestone — Quality Platform Q2

PR #28 final reviewed head `c2c30f90688747a523301cb776ed920ef39063f3` was squash-merged as commit `356680b9fe8a2724e778d40386ca182247715249`, tree `3550fdbb0810af26808851e24e39a6130725e8db`, on sole parent `9b3ead774a5a9bc2ac0f3b3ad754b2d99053f362`. Exact-head Quality Gates run #182 and post-merge run #183 passed all five jobs; PHPUnit was **21 tests / 126 assertions**, Q1 was **74/0**, Q2 was **64/0**, PHPStan/PHPCS/audit and every historical/architecture/H12 regression were green, final independent re-review found no major issues, and the implementation branch was deleted. PR #27 remains closed unmerged as evidence-only.

## Previous verified milestone — Quality Platform Q1

PR #26 final reviewed head `936e4630c83f7a92cbc4c77f061626e2b0c0c800` was squash-merged as commit `9b3ead774a5a9bc2ac0f3b3ad754b2d99053f362`, tree `473543cd08515eedd764a4b1ef7b6581590d13a1`, on sole parent `3223a882867634a2ba7588d7afbd2b2e4b4c21e4`. Exact-head Quality Gates run #177 and post-merge run #178 passed; the final independent review was clean after its valid required-check P1 was fixed, Quality Platform Foundation was **73/0**, and the implementation branch was deleted.

## Previous verified milestone — Architecture A5

PR #25 final reviewed head `997e18d8eb6264a84c6a9a35158213d3d655e6b3` was squash-merged as verified commit `3223a882867634a2ba7588d7afbd2b2e4b4c21e4`, tree `392b73425fa3219b6414a0984136b92c8ef77576`, on parent `d24b83356cc766f82c3ad9e529d3ec3f4194e887`. Exact-head Quality Gates run #173 and post-merge run #174 passed; Checkout Orchestration was **67/0**, final independent review was clean with zero unresolved threads, and the implementation branch was deleted.

## Previous verified milestone — Architecture A4

PR #24 final reviewed head `2a2c6a4c67775b6614297d2c0150f3ca61220498` was squash-merged as verified commit `d24b83356cc766f82c3ad9e529d3ec3f4194e887`, tree `f74899b93f493be872e0ce993e30079d0223dc7b`, on parent `6291196b35a952ea974549d1aa6d6ae9bbcc64dc`. Exact-head Quality Gates run #164 and post-merge run #165 passed; Subscription Presentation was **75/0**, final independent review was clean with zero unresolved threads, and the implementation branch was deleted.

## Previous verified milestone — Architecture A3

PR #23 final reviewed head `85028cfb4431cc29820eaca4e254bf6c87daa378` was squash-merged as verified commit `6291196b35a952ea974549d1aa6d6ae9bbcc64dc`, tree `a7f66ee6cf8c9d5324a0ae77b8c61e69e87bdff7`, on parent `f85894271e8f991e77a8e6a2b306f4d191483bbd`. Exact-head Quality Gates run #158 and post-merge run #159 passed; Gateway Settings was **90/0**, final independent review was clean with zero unresolved threads, and the implementation branch was deleted.

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

## Current tranche — Full Automated Quality Platform Q16

**Status: Q16 — MIGRATION CORE ANALYSIS / IMPLEMENTATION.**

Required bounded sequence:

1. Work only from verified Q15 merge `a4bbb05021dbded73072c0ba108a18245b60ad88`, tree `ea5b0b3880a99999577d51a9ed5f6a8c77a52cf0`, on `quality/migration-core-analysis`.
2. Expand deterministic PHPUnit characterization across `MigrationPreflight.php`, `MigrationBatch.php` and `MigrationExecutor.php`.
3. Add those three files to the existing baseline-free PHPStan level 5 / PHP 7.2 and risk-focused PHPCS scopes.
4. Preserve the closed Phase 9I classification, bounded history/provenance scan, batch checkpoint/resume, locking, exact legacy provenance and redacted-ledger contracts.
5. Correct only analyzer- or test-proven migration-core defects; full-string identifiers must fail closed and SQL must remain properly prepared.
6. Keep the Q1 lockfile, tool versions, analysis level, PHPStan no-baseline/no-`ignoreErrors` rule, distributed syntax matrix and protected H12 prerequisite aggregator unchanged; do not broadly disable PHPCS rules.
7. Keep every closed regression and architecture harness mandatory, plus all Q1-Q16 permanent Quality Platform harnesses.
8. Preserve exact Scheduler/CycleClaim blobs, provider, Security, H12, Phase 9I, payment truth and compatibility identities.
9. Require independent exact-head review, green CI, verified merge, post-merge CI and branch cleanup before Q16 is DONE / VERIFIED.

Q17 payment runtime remains the planned Quality Platform closeout. Do not create later Q gates merely to extend the sequence; any additional gate requires a concrete separately bounded enterprise-critical risk that is not better owned by the subsequent certification/readiness/release programs.

The quality gate may improve evidence and tooling; it may not reinterpret provider truth, weaken authorization, broaden migration eligibility or silently broaden certified feature/platform claims.

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
9. `docs/project/ARCHITECTURE-CODE-QUALITY.md`
10. `docs/project/QUALITY-PLATFORM.md`
11. relevant sections of `docs/project/MASTER-ENGINEERING-PLAYBOOK.md`
12. `docs/project/REPOSITORY-AUDIT.md`
13. `docs/project/REPOSITORY-READINESS.md`
14. `docs/project/BASELINE-H12.md`

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
5. Architecture & Code-Quality Foundation — **DONE / VERIFIED (A1-A5)**
6. Full automated quality platform — **CURRENT / Q16**
7. Platform certification: Woo/WP/PHP/HPOS/Blocks/WPML
8. Feature certification
9. Performance/UX/operations/diagnostics
10. Release engineering/distribution/WordPress.org when eligible
11. Continuous maintenance

## Copy-ready opening prompt

```text
Continue the SimplixPay for UPayments engineering program in SimplixInnovations/simplixpay-upayments.

Read AGENTS.md first, then docs/project/PROJECT-STATUS.md, docs/project/NAMING-IDENTITY-STANDARD.md, docs/project/NEW-CHAT-HANDOFF.md, docs/project/PHASE-0-RELEASE-IDENTITY.md, docs/project/PHASE-9I-MIGRATION.md, docs/project/PROVIDER-PAYMENT-LIFECYCLE.md, docs/project/SECURITY-THREAT-MODEL.md, docs/project/ARCHITECTURE-CODE-QUALITY.md, docs/project/QUALITY-PLATFORM.md and the relevant living sections of docs/project/MASTER-ENGINEERING-PLAYBOOK.md.

Treat recorded SHAs/status as verified milestone anchors, not substitutes for live GitHub. Freshly verify current main, open PRs/branches, checks, review state and current source before acting; reconcile any drift first.

Repository readiness, Phase 0, Phase 9I, Provider Contract & Payment Lifecycle, Security Threat-Model Closure, Architecture discovery/A1-A5 and Quality Platform Q1-Q15 are DONE / VERIFIED. Q15 was squash-merged from PR #41 as main `a4bbb05021dbded73072c0ba108a18245b60ad88`, tree `ea5b0b3880a99999577d51a9ed5f6a8c77a52cf0`; exact-head Quality Gates #253 and post-merge Quality Gates #254 passed. The current permitted gate is Full Automated Quality Platform — Q16.

Implement only Q16 migration-core analysis expansion: add deterministic PHPUnit characterization for `MigrationPreflight.php`, `MigrationBatch.php` and `MigrationExecutor.php`, add them to baseline-free PHPStan level 5/PHP 7.2 and risk-focused PHPCS, and correct only analyzer- or test-proven migration-core defects. Keep Q1's lockfile, tool versions, dependency audit, distributed syntax jobs and always-running protected H12 prerequisite aggregator unchanged. Composer and tests remain development-only. Do not call the provider, dispatch payments, mutate historical order evidence, run scheduler/cycle-claim/billing-attempt behavior or alter compatibility identities. Keep all existing regression suites, every architecture harness and all Q1-Q16 permanent Quality Platform harnesses mandatory. Q17 remains the planned payment-runtime closeout; later Q work requires a concrete separately bounded enterprise-critical risk.

Do not claim broad security, PCI/compliance, platform, feature, performance or production certification from the bounded security closure. UPayments webhook signature details remain provider-document unresolved, automatic refunds remain unsupported pending durable idempotency/reconciliation design, and subscription auto-deduction remains separately characterized rather than broadly certified.

Work directly in GitHub wherever tools permit. Never approve or merge without independent exact-SHA source/diff/check/review verification and post-merge verification.
```
