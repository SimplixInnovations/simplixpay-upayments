# Engineering Roadmap

The complete engineering program is controlled by the project documents under `docs/project/`. This file is the public high-level sequence only.

Use:

- [`project/PROJECT-STATUS.md`](project/PROJECT-STATUS.md) for current verified state and the next permitted action;
- [`project/PHASE-0-RELEASE-IDENTITY.md`](project/PHASE-0-RELEASE-IDENTITY.md) for the closed Phase 0 evidence record;
- [`project/PHASE-9I-MIGRATION.md`](project/PHASE-9I-MIGRATION.md) for the closed historical token-identity migration record;
- [`project/PROVIDER-PAYMENT-LIFECYCLE.md`](project/PROVIDER-PAYMENT-LIFECYCLE.md) for the closed provider/payment lifecycle record;
- [`project/REPOSITORY-READINESS.md`](project/REPOSITORY-READINESS.md) for the closed repository-foundation record;
- [`project/MASTER-ENGINEERING-PLAYBOOK.md`](project/MASTER-ENGINEERING-PLAYBOOK.md) for detailed phases and release criteria;
- [`project/NAMING-IDENTITY-STANDARD.md`](project/NAMING-IDENTITY-STANDARD.md) for product/slug/namespace and compatibility identity rules.

## Program order

0. **Repository Foundation & Readiness — DONE / VERIFIED**
   - standalone canonical history/provenance;
   - public repository identity/documentation;
   - governance/CODEOWNERS;
   - baseline CI/dependency hygiene;
   - protected default-branch/rules/security controls;
   - contributor/history cleanup and repository presentation.

1. **Phase 0 — SimplixPay release identity and updater ownership — DONE / VERIFIED**
   - active plugin identity **SimplixPay for UPayments** / Simplix Innovations;
   - independent development version **0.1.0**;
   - inherited upstream update authority and bundled Plugin Update Checker removed;
   - external self-update channel disabled pending tested distribution/basename migration;
   - non-destructive uninstall by default;
   - transitional `UPayments.php` and `upayments` text-domain choices explicitly frozen as future migrations;
   - protected historical payment identities preserved;
   - Phase 0 characterization **35 PASS / 0 FAIL** with H12 **1927/0 PHP** and **144/0 Blocks**.

2. **Phase 9I — Historical token-identity migration — DONE / VERIFIED**
   - deterministic read-only `CLEAN` / `MIGRATABLE` / `BLOCKED` / `INDETERMINATE` preflight;
   - all 13 historical blocker families fail closed;
   - locked executor acts only on fresh `MIGRATABLE` evidence;
   - historical identity can become only `legacy_compat` / `legacy_verified_capture`;
   - historical order evidence remains immutable;
   - bounded admin/CLI workflow with durable redacted per-user checkpoints and credential/mode/list-scoped resume;
   - verified sequence: PR #11 preflight, PR #12 executor, PR #13 operations;
   - final implementation evidence: preflight **123/0**, executor **59/0**, operations **81/0**, H12 PHP **1927/0**, H12 Blocks **144/0**.

3. **Provider Contract & Payment Lifecycle — DONE / VERIFIED**
   - compared exact source against current official UPayments and WooCommerce documentation;
   - froze Charge/session-vs-capture semantics and server-status truth hierarchy;
   - moved ordinary browser/webhook financial truth to an isolated `Simplix\Pay\UPayments\Payment` lifecycle layer while preserving the historical callback route;
   - strict authenticated Get Payment Status host/schema/order/reference/currency/amount binding;
   - canonical decimal amount equality without Woo display rounding;
   - deterministic CAPTURED / PENDING / FAILED / CANCELLED / INDETERMINATE classification including provider `NULL`/Processing-style uncertainty;
   - CAPTURED uses WooCommerce `payment_complete($verified_payment_id)` and standard transaction-ID semantics;
   - duplicate/replay protection and paid/refunded no-resurrection rules;
   - separate unverified/trusted reconciliation cursors scoped to the current `UPayments_order_id`;
   - bounded reconciliation at 60/120/240/480 seconds, maximum four attempts, never retries Charge;
   - compare-and-swap per-order lifecycle locking;
   - exact callback GET/POST conflict handling with cookies/`$_REQUEST` excluded;
   - stricter 30/min status-query gate while provider documentation remains contradictory;
   - automatic refunds intentionally unsupported pending durable refund idempotency/reconciliation design;
   - current multi-merchant claim frozen to one additional merchant allocation only;
   - provider webhook HMAC details retained as provider-document unresolved rather than guessed;
   - final PR #15 evidence: Provider Lifecycle **141/0**, Exact Amount **4/0**, Phase 0 **35/0**, Phase 9I **123/0 + 59/0 + 81/0**, H12 PHP **1927/0**, H12 Blocks **144/0**;
   - squash merge `9569e39973a9e94926087738eae06c3846361943`, tree `40ec562674361624c2764263ba55cfba84594955`, VERIFIED signature, deleted implementation branch, green post-merge run #71.

4. **Security Threat-Model Closure — CURRENT GATE / DISCOVERY**
   - map assets, trust boundaries, actors, financial/security state and data flows;
   - audit authorization/capability boundaries and CSRF/nonces;
   - audit callback/webhook abuse, replay, IDOR and order-object reference boundaries;
   - audit SSRF, redirect and URL/host allowlists;
   - audit API credentials, saved-card/customer-token identity, secret roots and migration security material;
   - audit input parsing/type confusion/injection and output escaping;
   - audit logs/order notes/diagnostics for secret and PII redaction;
   - audit concurrency, race and idempotency properties as security controls;
   - audit dependency/supply-chain and GitHub Actions trust boundaries;
   - retain fail-closed behavior where provider security documentation is incomplete;
   - add executable security characterization before broad refactoring.

5. **Architecture & Code-Quality Foundation**
   - incremental `Simplix\Pay\UPayments` architecture extraction;
   - characterization before refactoring;
   - static analysis/coding standards/dead-code/complexity cleanup;
   - no big-bang runtime rename.

6. **Full Automated Quality Platform**
   - PHPUnit;
   - WordPress/WooCommerce integration tests;
   - PHPStan and PHPCS/WPCS/Woo standards;
   - mutation/property/boundary/failure-injection tests;
   - provider fixtures and webhook/concurrency tests;
   - browser E2E/accessibility/performance regression coverage.

7. **Platform Certification**
   - supported WordPress/WooCommerce/PHP matrix;
   - HPOS;
   - Classic Checkout and Checkout Blocks;
   - WPML/WCML/multilingual/multicurrency/RTL.

8. **Feature Certification**
   - saved cards/tokenization;
   - subscriptions/auto deduction;
   - wallets;
   - refunds;
   - multi-merchant;
   - browser/device/theme interoperability.

9. **Performance, UX & Operations**
   - performance/stability engineering;
   - frontend/UI/UX/accessibility;
   - merchant onboarding/readiness checks;
   - Site Health/diagnostics/support bundle;
   - structured errors/observability/logging.

10. **Release Engineering & Distribution**
    - deterministic packaging;
    - safe physical plugin basename/folder distribution contract;
    - signed/checksummed releases;
    - upgrade/rollback/recovery certification;
    - final docs/readme/release notes;
    - WordPress.org preparation/publication when eligible.

11. **Continuous Maintenance**
    - provider/platform monitoring;
    - dependency/security updates;
    - compatibility regression matrix;
    - release/support lifecycle.

## Rule

A roadmap item is complete only after exact implementation/review evidence, required checks, merge, post-merge verification and status-ledger reconciliation. Branch existence or an Agent/bot success report is never sufficient.
