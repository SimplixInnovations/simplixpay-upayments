# Repository Audit Ledger

**Repository:** `SimplixInnovations/simplixpay-upayments`

**Original audit base:** `c6e8c32044da254654e7a928e80900d943843e7a`

**Phase 0 verified implementation:** `678f3bdae32b7a0d5922c6ebb7fa7535ede256dd`

**Phase 9I verified operations implementation:** `db1c4ea4dab45bc1ffaf4529e0ccb940153cd999`

**Provider lifecycle verified implementation:** `9569e39973a9e94926087738eae06c3846361943`

**Provider lifecycle tree:** `40ec562674361624c2764263ba55cfba84594955`

**Last reconciled:** 2026-08-25

**Purpose:** maintain tracked-tree debt classification and current gate ownership without authorizing drive-by runtime cleanup.

## Executive classification

Repository foundation/readiness, **Phase 0**, **Phase 9I**, and **Provider Contract & Payment Lifecycle** are **DONE / VERIFIED**.

The repository remains a pre-release engineering codebase. It is not the intended final SimplixPay package architecture and is not yet broadly security/platform/feature/performance certified.

Phase 0 took ownership of public release identity and removed inherited update authority. Phase 9I added isolated historical-identity migration tooling. The provider lifecycle gate then added an isolated `Simplix\Pay\UPayments\Payment` strangler for ordinary browser/webhook/status truth and WooCommerce payment-state transitions without broadly rewriting the inherited gateway bootstrap.

The current owner/gate is **Security Threat-Model Closure — DISCOVERY**. This gate may characterize and remediate security-critical behavior, but it does not authorize unrelated cleanup or weakening closed payment/H12/Phase 9I contracts.

## Top-level inventory after Provider Lifecycle closure

| Area | Current state | Classification | Next owner/gate |
|---|---|---|---|
| `.github/` | CODEOWNERS, templates, Dependabot, protected Quality Gates | **KEEP / CONTROL PLANE** | Security gate audits workflow/supply-chain trust |
| `AGENTS.md` | Permanent execution/review rules | **KEEP / CONTROL PLANE** | Mandatory before substantive work |
| `README.md`, `CHANGELOG.md` | Simplix-led public/project records | **KEEP CURRENT** | Update at verified milestones |
| `LICENSE`, `NOTICE.md`, `UPSTREAM.md` | MIT + provenance/trademark boundaries | **KEEP** | Re-review at publication gates |
| `UPayments.php` | Active inherited large gateway/bootstrap with Simplix 0.1.0 identity; Charge/config/subscription and legacy callback paths remain | **PROTECTED / SECURITY + ARCHITECTURE AUDIT** | Security threat model first; broad extraction later |
| `src/Release/Identity.php` | Canonical Simplix release identity + conditional runtime foothold | **KEEP / CHARACTERIZED** | Preserve Phase 0 isolation contract |
| `src/Migration/` | Verified Phase 9I preflight/executor/admin/CLI operations | **DONE / VERIFIED / PROTECTED** | Security-audit capabilities/nonces/credentials/ledgers without weakening migration semantics |
| `src/Payment/` | Verified provider result, rate gate, order lock, status verifier and lifecycle strangler | **DONE / VERIFIED / SECURITY INPUT** | Security threat-model callback/status/SSRF/replay/concurrency/logging boundaries |
| `includes/Token/CustomerTokenIdentity.php` | H12-critical token identity implementation | **DONE / VERIFIED H12 + PHASE 9I DEPENDENCY** | Security-audit secret/token boundaries; no casual semantic changes |
| `includes/Subscription/` | Subscription/auto-deduction implementation | **RUNTIME — PROTECTED** | Security threat model, then later feature certification |
| Blocks integration | H12-critical Blocks implementation | **RUNTIME — PROTECTED** | Security input boundaries now; broader platform certification later |
| `assets/`, `templates/` | Inherited frontend/admin paths and assets | **AUDIT LATER / SECURITY OBSERVABLE** | Security output/XSS checks where relevant; UX/performance cleanup later |
| `tests/harness/` | Phase 0 + Phase 9I + Provider Lifecycle + H12 custom regressions | **KEEP AS REQUIRED BASELINE** | Add security characterization; supplement rather than replace |
| `vendor/plugin-update-checker/` | absent | **RESOLVED** | Do not reintroduce without explicit distribution design |
| `uninstall.php` | non-destructive by default | **RESOLVED FOR PHASE 0** | Future erasure path requires explicit contract |
| `index.php` | minimal directory guard | **KEEP** | Re-evaluate only with packaging migration |

## Phase 0 resolved findings

### Public release identity

Resolved. The active header identifies **SimplixPay for UPayments 0.1.0** by **Simplix Innovations** and loads `Simplix\Pay\UPayments\Release\Identity`.

### Vendor/updater dependency

Resolved. The inherited Plugin Update Checker and `upaymentskwt/woocommerce` update authority were removed. External self-updates remain disabled until a separately tested package/basename distribution contract exists.

### Uninstall behavior

Resolved for current engineering scope. Uninstall retains merchant/payment data by default. Future destructive erasure must be explicit, confirmed and independently tested.

### Release-identity characterization

Permanent Phase 0 regression: **35 PASS / 0 FAIL**, alongside H12 PHP **1927/0** and H12 Blocks **144/0**.

## Phase 9I resolved historical-identity gap

Phase 9I is **DONE / VERIFIED** through PRs #11, #12 and #13.

Verified implementation milestones:

- preflight → `8cca32819dd165e35efa0fcc5a48bdd551757d8c`;
- executor → `708253bd9d0daf217735fbb087b360e8b848136c`;
- operations → `db1c4ea4dab45bc1ffaf4529e0ccb940153cd999`, tree `5bec24ad26c66a504cd0dd609f4311f9e70add76`.

Closed contract:

- exact read-only `CLEAN` / `MIGRATABLE` / `BLOCKED` / `INDETERMINATE` preflight;
- all 13 historical blocker families fail closed;
- locked execution only for fresh `MIGRATABLE` evidence;
- only `legacy_compat` / `legacy_verified_capture` historical provenance;
- no fabricated canonical/Create-201 provenance;
- historical order metadata immutability;
- bounded admin/CLI operation with redacted durable result checkpoints and credential/mode/list-scoped resume;
- no provider/checkout/frontend migration hooks.

## Provider Contract & Payment Lifecycle — resolved

Provider Contract & Payment Lifecycle is **DONE / VERIFIED** through PR #15.

Verified final reviewed head:

- `d2b08ebe1e65ad4ea8f4e06b41423e7bd9904fc3`

Verified merge:

- `9569e39973a9e94926087738eae06c3846361943`;
- tree `40ec562674361624c2764263ba55cfba84594955`;
- sole parent `8e5a93ceb4f133663fdf433cc1a10b8b36c13d97`;
- GitHub signature VERIFIED;
- implementation branch deleted;
- push-triggered post-merge Quality Gates run #71 SUCCESS.

The closed implementation provides:

- non-authoritative browser/webhook payloads;
- authenticated Get Payment Status as financial truth;
- exact UPayments HTTPS status-host/path enforcement before Bearer credentials are sent;
- strict status schema and order/provider-order/reference/currency/amount binding;
- canonical decimal amount equality without display rounding;
- deterministic provider-result classification with `NULL`/Processing/unknown uncertainty preserved;
- Woo `payment_complete($payment_id)` for verified capture and standard transaction-ID semantics;
- duplicate/replay idempotency and paid/refunded no-resurrection rules;
- separate unverified/trusted retry cursors scoped to current `UPayments_order_id`;
- bounded 60/120/240/480 reconciliation, maximum four attempts, never retries Charge;
- compare-and-swap per-order lifecycle lock semantics;
- callback GET/POST conflict rejection with cookies/`$_REQUEST` excluded;
- stricter 30/min status-query automation while provider documentation remains contradictory.

Permanent lifecycle regression evidence:

- Provider Payment Lifecycle **141 PASS / 0 FAIL**;
- Provider Exact Amount Binding **4 PASS / 0 FAIL**.

Four valid review findings were corrected before merge: rate-gate/wp_salt seam, first-query transient reconciliation, stale-lock race, and display-rounded amount mismatch.

### Deliberately unresolved provider/feature boundaries

- webhook HMAC/signature verification remains provider-document unresolved because the public docs reviewed did not publish a complete stable verification contract;
- automatic WooCommerce refunds remain unsupported pending durable refund idempotency/reconciliation design;
- arbitrary multi-entry marketplace splitting remains uncertified; current behavior supports one additional merchant allocation only;
- subscription auto-deduction remains on its separately characterized path.

These are explicit later/security inputs, not hidden implementation gaps that may be guessed through cleanup.

## Deliberately unresolved package/identity work

### Physical plugin basename

Active main filename remains `UPayments.php`; eventual target `simplixpay-upayments.php` requires explicit upgrade/package migration evidence.

### Text domain

Runtime/header text domain remains `upayments`; eventual `simplixpay-upayments` migration requires dedicated i18n/WPML/String Translation evidence.

### Coexistence/conflict detection

Simultaneous activation with another UPayments plugin that owns the same globals/classes/callback identities cannot be assumed safe. Explicit install/onboarding conflict detection remains future work.

## Protected runtime debt

### Large bootstrap

`UPayments.php` remains a large mixed-responsibility inherited surface. The provider lifecycle gate deliberately used an isolated strangler rather than broad cleanup. Security Threat-Model Closure may inspect/remediate security-critical paths, but general decomposition belongs to the later Architecture & Code-Quality gate.

### Token and subscription modules

Historical H12 anchors remain regression evidence:

- `includes/Token/CustomerTokenIdentity.php` — `85430d37e9baf540842f5655b86ccf0eca3e6aea`
- `includes/class-wc-gateway-upayments-blocks.php` — `813d192d69c069eb7ee11df93acc9dbdf03e270a`
- `includes/Subscription/Cron/Scheduler.php` — `5251866d4df2d1326e7c09f0c8ec1d146c0bb325`
- `includes/Subscription/Cron/CycleClaim.php` — `c34d83e2d77cc65024fe663e4c378cecb2b17347`

These are historical regression anchors, not claims that stronger later reviewed phases can never modify the files.

### Empty/duplicate/legacy assets

Known inherited empty/duplicate/legacy assets remain recorded debt. Do not delete/rename them without dependency/runtime characterization.

### Test platform

The required custom harness stack now includes Phase 0, all Phase 9I suites, Provider Lifecycle, Exact Amount, H12 PHP and H12 Blocks. It remains regression protection rather than a replacement for full PHPUnit/WordPress/WooCommerce integration, broad static analysis, browser E2E, accessibility and performance testing.

## Current next owner/gate

**Security Threat-Model Closure — DISCOVERY**.

The gate must first build a security asset/trust-boundary/data-flow map and characterize:

- authorization/capabilities and CSRF/nonces;
- callback/webhook abuse, replay and IDOR;
- SSRF/redirect/host allowlists;
- provider credentials, H12 token/secrets and migration material;
- input parsing/type confusion/injection and output escaping;
- logs/order notes/diagnostics redaction;
- concurrency/race/idempotency security;
- dependency/supply-chain/GitHub Actions trust;
- fail-closed handling of undocumented provider security contracts.

Do not start with a broad refactor. Closed provider lifecycle/H12/Phase 9I contracts remain regression constraints unless an explicit stronger security contract replaces one.

See `PROJECT-STATUS.md` for live program state, `PHASE-9I-MIGRATION.md` for historical-identity migration, and `PROVIDER-PAYMENT-LIFECYCLE.md` for the closed ordinary-checkout lifecycle contract.

## Rule

This ledger records debt and gate ownership. It does not authorize mechanical cleanup of payment/runtime code. `PROJECT-STATUS.md`, the naming standard, `AGENTS.md`, closed phase contracts and fresh live GitHub/provider evidence control execution truth.
