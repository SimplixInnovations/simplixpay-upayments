# Repository Audit Ledger

**Repository:** `SimplixInnovations/simplixpay-upayments`

**Original audit base:** `c6e8c32044da254654e7a928e80900d943843e7a`

**Phase 0 verified implementation:** `678f3bdae32b7a0d5922c6ebb7fa7535ede256dd`

**Phase 9I verified operations implementation:** `db1c4ea4dab45bc1ffaf4529e0ccb940153cd999`

**Provider lifecycle verified implementation:** `9569e39973a9e94926087738eae06c3846361943`

**Provider lifecycle tree:** `40ec562674361624c2764263ba55cfba84594955`

**Security Threat-Model verified implementation:** `01f3fc59eed8641b3e5372558f61a7a0f0cdfac9`

**Security Threat-Model tree:** `e0027005f059fad03d8c08273b7aac6553c45f53`

**Architecture A3 verified implementation:** `6291196b35a952ea974549d1aa6d6ae9bbcc64dc`

**Architecture A2 tree:** `1addbcc02e0d30f57a948cafd8111fb94e60c4da`

**Last reconciled:** 2026-08-25

**Purpose:** maintain tracked-tree debt classification and current gate ownership without authorizing drive-by runtime cleanup.

## Executive classification

Repository foundation/readiness, **Phase 0**, **Phase 9I**, **Provider Contract & Payment Lifecycle**, and bounded **Security Threat-Model Closure** are **DONE / VERIFIED**.

The repository remains a pre-release engineering codebase. It is not the intended final SimplixPay package architecture and is not yet broadly security/platform/feature/performance certified.

Phase 0 took ownership of public release identity and removed inherited update authority. Phase 9I added isolated historical-identity migration tooling. The provider lifecycle gate then added an isolated `Simplix\Pay\UPayments\Payment` strangler for ordinary browser/webhook/status truth and WooCommerce payment-state transitions without broadly rewriting the inherited gateway bootstrap.

Architecture discovery/A1-A5 and Quality Platform Q1-Q11 are **DONE / VERIFIED**. The current owner/gate is **Full Automated Quality Platform — Q12**. Q12 may expand isolated-process PHPUnit characterization and baseline-free PHPStan/PHPCS only across the bounded `Subscription/WCProductCustomType.php` guarded compatibility surface. It does not authorize a big-bang rewrite, global-class/base/type changes, unconditional autoloading, hook changes, identity migration, updater activation, scheduler/cycle-claim/attempt/dispatch/mutation changes, protected-meta renames, provider-host migration, payment-truth reinterpretation or weakening closed Security/Payment/H12/Phase 9I contracts.

## Top-level inventory after Security Threat-Model closure

| Area | Current state | Classification | Next owner/gate |
|---|---|---|---|
| `.github/` | CODEOWNERS, templates, Dependabot, protected Quality Gates | **KEEP / CONTROL PLANE** | Q12 preserves Q1-Q11 gating while expanding bounded subscription-product-type evidence |
| `AGENTS.md` | Permanent execution/review rules | **KEEP / CONTROL PLANE** | Mandatory before substantive work |
| `README.md`, `CHANGELOG.md` | Simplix-led public/project records | **KEEP CURRENT** | Update at verified milestones |
| `LICENSE`, `NOTICE.md`, `UPSTREAM.md` | MIT + provenance/trademark boundaries | **KEEP** | Re-review at publication gates |
| `UPayments.php` | Active inherited large gateway/bootstrap with Simplix 0.1.0 identity; Charge/config/subscription and legacy callback paths remain | **PROTECTED / ARCHITECTURE AUDIT** | Characterize responsibilities; extract incrementally |
| `src/Release/Identity.php` | Canonical Simplix release identity + conditional runtime foothold | **KEEP / CHARACTERIZED** | Preserve Phase 0 isolation contract |
| `src/Migration/` | Verified Phase 9I preflight/executor/admin/CLI operations | **DONE / VERIFIED / PROTECTED** | Architecture gate preserves the migration boundary; later quality/platform work may extend coverage without weakening semantics |
| `src/Payment/` | Verified provider result, rate gate, order lock, status verifier and lifecycle strangler | **DONE / VERIFIED / PROTECTED** | Architecture gate may extract only behind full characterization/regression |
| `src/Security/` | Verified public order-status authorization boundary | **DONE / VERIFIED / PROTECTED** | Preserve SEC-01 contract during architecture work |
| `includes/Token/CustomerTokenIdentity.php` | H12-critical token identity implementation | **DONE / VERIFIED H12 + PHASE 9I DEPENDENCY** | Architecture gate treats token identity as a protected dependency; later certification may extend evidence without casual semantic changes |
| `includes/Subscription/` | Subscription/auto-deduction implementation | **RUNTIME — PROTECTED** | Architecture only under full characterization; later feature certification owns recurring-billing certification |
| Blocks integration | H12-critical Blocks implementation with local checkout chevrons | **RUNTIME — SECURITY VERIFIED / PROTECTED** | Architecture only with Blocks/H12/security regressions; platform certification later |
| `assets/`, `templates/` | Inherited frontend/admin paths and assets; bounded checkout dependency/output fixes verified | **PARTIALLY SECURITY-CHARACTERIZED / AUDIT LATER** | Architecture/UX/performance gates; preserve security escaping/dependency contracts |
| `tests/harness/` | Phase 0 + Phase 9I + Provider Lifecycle + Security Threat-Model + H12 custom regressions | **KEEP AS REQUIRED BASELINE** | Architecture/static-analysis additions supplement rather than replace |
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

These remain explicit later-gate inputs, not hidden implementation gaps that may be guessed through cleanup.

## Security Threat-Model Closure — resolved

Bounded Security Threat-Model Closure is **DONE / VERIFIED** through PR #17.

Verified implementation:

- final reviewed head `fba12225899c3e01d6b23a6bba2f757a3b5f6a4a`;
- squash merge `01f3fc59eed8641b3e5372558f61a7a0f0cdfac9`;
- tree `e0027005f059fad03d8c08273b7aac6553c45f53`;
- sole parent `08054a93c619f3c34fef747a6e530abce1e8986e`;
- VERIFIED GitHub signature;
- merge-ref Quality Gates #88 SUCCESS;
- post-merge Quality Gates #89 SUCCESS;
- implementation branch deleted;
- permanent Security Threat-Model harness **81 PASS / 0 FAIL**.

Closed findings:

- SEC-01 public legacy status IDOR — exact UPayments object + owner/order-key authorization and allowlisted output;
- SEC-02 subscription GET mutation — POST-only exact-owner/action-nonce/object/transition boundary;
- SEC-03 checkout CDN trust — remote fonts/icons removed across classic and Blocks with local presentation;
- SEC-04 output trust — context-correct escaping and no checkout `$_REQUEST` display markers;
- SEC-05 product-meta defense in depth — local WooCommerce nonce/post-ID/capability preconditions.

One valid P2 automated review finding on Checkout Blocks chevrons was corrected and permanently characterized before merge.

Explicit non-closures: webhook HMAC/signature remains provider-document unresolved; automatic refunds remain unsupported pending durable idempotency/reconciliation; subscription auto-deduction is not broad recurring-billing certification; no penetration-test/PCI/platform/feature/performance/production certification is claimed.

## Deliberately unresolved package/identity work

### Physical plugin basename

Active main filename remains `UPayments.php`; eventual target `simplixpay-upayments.php` requires explicit upgrade/package migration evidence.

### Text domain

Runtime/header text domain remains `upayments`; eventual `simplixpay-upayments` migration requires dedicated i18n/WPML/String Translation evidence.

### Coexistence/conflict detection

Simultaneous activation with another UPayments plugin that owns the same globals/classes/callback identities cannot be assumed safe. Explicit install/onboarding conflict detection remains future work.

## Protected runtime debt

### Large bootstrap

`UPayments.php` remains a large mixed-responsibility inherited surface. Provider and Security gates deliberately used isolated strangler/boundary changes rather than broad cleanup. General decomposition now belongs to the current Architecture & Code-Quality Foundation gate and must be incremental and characterization-led.

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

The required custom harness stack now includes Phase 0, all Phase 9I suites, Provider Lifecycle, Exact Amount, Security Threat-Model, all architecture suites, H12 PHP and H12 Blocks. Q1 added locked PHPUnit/PHPStan/PHPCS evidence and a foundation harness; Q2 added CheckoutPayload boundary tests and a static-analysis expansion harness; Q3 added deterministic rate-gate/order-lock tests and a payment-concurrency harness; Q4 added authenticated status transport/binding tests and a dedicated permanent harness; Q5 added payment-method availability cache/lock/gate/provider-normalization tests and a dedicated permanent harness; Q6 added gateway settings schema/validation/sanitation/rendering/admin-asset tests and a dedicated permanent harness; Q7 added public status request/authorization/minimal-response tests and a dedicated permanent harness; Q8 added release-identity/version/updater/legacy-target tests and its own permanent harness; Q9 added historical-option/credential/mode/redaction tests and its own permanent harness; Q10 added migration-bootstrap context/dependency/registration tests and its own permanent harness; Q11 added subscription-composition hook/dependency/initializer tests and its own permanent harness; Q12 adds guarded product-type load/parent/type tests and its own permanent harness. This remains a progressive quality platform rather than WordPress/WooCommerce/browser/performance/product-type/recurring-billing certification.

## Current next owner/gate

**Full Automated Quality Platform — Q12**.

The current tranche must preserve the verified architecture map while establishing:

- isolated-process PHPUnit characterization of absent-base, available-base and predeclared-child product-type load states, the exact parent and historical type result;
- baseline-free PHPStan level 5 and PHPCS ownership of `Subscription/WCProductCustomType.php` beside the Q1-Q11 modules;
- unchanged Q1 dependency, audit, syntax and required-check controls;
- permanent Q1/Q2/Q3/Q4/Q5/Q6/Q7/Q8/Q9/Q10/Q11/Q12 and historical regression gates.

Do not reinterpret green tooling as platform certification or modify payment runtime to satisfy a tool. Closed Architecture/Security/Provider/H12/Phase 9I contracts remain required regressions throughout quality-platform work.

See `PROJECT-STATUS.md` for live program state, `PHASE-9I-MIGRATION.md` for historical-identity migration, and `PROVIDER-PAYMENT-LIFECYCLE.md` for the closed ordinary-checkout lifecycle contract.

## Rule

This ledger records debt and gate ownership. It does not authorize mechanical cleanup of payment/runtime code. `PROJECT-STATUS.md`, the naming standard, `AGENTS.md`, closed phase contracts and fresh live GitHub/provider evidence control execution truth.
