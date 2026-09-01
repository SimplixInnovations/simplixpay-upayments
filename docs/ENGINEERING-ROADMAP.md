# Engineering Roadmap

The complete engineering program is controlled by the project documents under `docs/project/`. This file is the public high-level sequence only.

Use:

- [`project/PROJECT-STATUS.md`](project/PROJECT-STATUS.md) for current verified state and the next permitted action;
- [`project/PHASE-0-RELEASE-IDENTITY.md`](project/PHASE-0-RELEASE-IDENTITY.md) for the closed Phase 0 evidence record;
- [`project/PHASE-9I-MIGRATION.md`](project/PHASE-9I-MIGRATION.md) for the closed historical token-identity migration record;
- [`project/PROVIDER-PAYMENT-LIFECYCLE.md`](project/PROVIDER-PAYMENT-LIFECYCLE.md) for the closed provider/payment lifecycle record;
- [`project/SECURITY-THREAT-MODEL.md`](project/SECURITY-THREAT-MODEL.md) for the closed bounded security threat-model record;
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

4. **Security Threat-Model Closure — DONE / VERIFIED**
   - closed public status-poll IDOR with exact owner/order-key authorization and UPayments object preflight;
   - moved subscription customer mutations to POST-only owner/nonce/object/transition boundaries;
   - removed checkout Google Fonts/cdnjs Font Awesome trust across classic and Blocks;
   - tightened output-context escaping and checkout request-source handling;
   - mirrored WooCommerce product-meta authorization preconditions locally;
   - retained provider status transport/payment-truth, H12, Phase 9I, subscription no-blind-retry and immutable Actions-pin controls;
   - permanent Security Threat-Model regression **81 PASS / 0 FAIL**;
   - final PR #17 head `fba12225899c3e01d6b23a6bba2f757a3b5f6a4a` passed merge-ref Quality Gates #88;
   - squash merge `01f3fc59eed8641b3e5372558f61a7a0f0cdfac9`, tree `e0027005f059fad03d8c08273b7aac6553c45f53`, VERIFIED signature, deleted implementation branch, green post-merge run #89;
   - webhook HMAC/signature remains provider-document unresolved; automatic refunds and broad recurring-billing certification remain outside this gate;
   - bounded closure is not broad penetration-test/PCI/platform/feature/performance/production certification.

5. **Architecture & Code-Quality Foundation — DONE / VERIFIED (A1-A5)**
   - incremental `Simplix\Pay\UPayments` architecture extraction;
   - characterization before refactoring;
   - static analysis/coding standards/dead-code/complexity cleanup;
   - no big-bang runtime rename.

6. **Full Automated Quality Platform — CURRENT GATE / Q11**
   - Q1 locked development-toolchain foundation: **DONE / VERIFIED** through PR #26 and post-merge Quality Gates #178;
   - Q2 CheckoutPayload boundary characterization and baseline-free static-analysis expansion: **DONE / VERIFIED** through PR #28 and post-merge Quality Gates #183;
   - Q3 payment-concurrency characterization and baseline-free analysis for StatusRateGate/OrderLock: **DONE / VERIFIED** through PR #29 and post-merge Quality Gates #189;
   - Q4 authenticated status transport/binding characterization and baseline-free analysis for StatusVerifier: **DONE / VERIFIED** through PR #30 and post-merge Quality Gates #195;
   - Q5 payment-method availability cache/lock/gate/provider normalization characterization and baseline-free analysis: **DONE / VERIFIED**;
   - Q6 gateway settings schema/validation/sanitation/rendering/admin-asset characterization and baseline-free analysis: **DONE / VERIFIED**;
   - Q7 public order-status parsing/authorization/response characterization and baseline-free analysis: **DONE / VERIFIED** through PR #33 and post-merge Quality Gates #213;
   - Q8 release-identity/version/updater/legacy-and-target-identity characterization and baseline-free analysis: **DONE / VERIFIED** through PR #34 and post-merge Quality Gates #219;
   - Q9 migration-settings option/credential/mode/redaction characterization and baseline-free analysis: **DONE / VERIFIED** through PR #35 and post-merge Quality Gates #224;
   - Q10 migration-bootstrap context/dependency/registration characterization and baseline-free analysis: **DONE / VERIFIED** through PR #36 and post-merge Quality Gates #227;
   - Q11 subscription-composition hook/dependency/initializer characterization and baseline-free analysis: **CURRENT**;
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
