# Engineering Roadmap

The complete engineering program is controlled by the project documents under `docs/project/`. This file is the public high-level sequence only.

Use:

- [`project/PROJECT-STATUS.md`](project/PROJECT-STATUS.md) for current verified state and the next permitted action;
- [`project/PHASE-0-RELEASE-IDENTITY.md`](project/PHASE-0-RELEASE-IDENTITY.md) for the closed Phase 0 evidence record;
- [`project/PHASE-9I-MIGRATION.md`](project/PHASE-9I-MIGRATION.md) for the closed historical token-identity migration evidence record;
- [`project/REPOSITORY-READINESS.md`](project/REPOSITORY-READINESS.md) for the closed repository-foundation evidence record;
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
   - inherited upstream update authority removed;
   - bundled Plugin Update Checker removed;
   - external self-update channel disabled pending a tested distribution/basename contract;
   - non-destructive uninstall by default;
   - transitional `UPayments.php` and `upayments` text-domain choices explicitly frozen as future migrations;
   - protected historical payment identities preserved;
   - Phase 0 characterization **35 PASS / 0 FAIL** with H12 **1927/0 PHP** and **144/0 Blocks**.

2. **Phase 9I — Historical token-identity migration — DONE / VERIFIED**
   - deterministic read-only preflight with exact `CLEAN` / `MIGRATABLE` / `BLOCKED` / `INDETERMINATE` classification;
   - zero provider calls and zero identity writes in the core preflight;
   - all 13 historical blocker families explicitly characterized and fail closed;
   - locked executor acts only on fresh `MIGRATABLE` evidence;
   - historical identity can become only `legacy_compat` / `legacy_verified_capture`; no fabricated canonical/Create-201 provenance;
   - historical order evidence remains immutable;
   - bounded/idempotent admin/CLI workflow with dry-run, confirmed execute, explicit offsets and durable resume;
   - redacted per-user operations-result checkpoints for every processed outcome;
   - credential/mode/list-scoped HMAC resume identity without persisting credentials;
   - checkpoint-persistence failure stops progress and keeps the current user as the retry point;
   - no provider, checkout, Store API, frontend or cron migration hooks;
   - verified implementation sequence: PR #11 preflight, PR #12 executor, PR #13 operations;
   - final implementation-head evidence: Phase 0 **35/0**, preflight **123/0**, executor **59/0**, operations **81/0**, H12 PHP **1927/0**, H12 Blocks **144/0**, plus Governance/PHP/Blocks syntax success.

   Phase completion certifies the migration system and safety contract. It does not mean every merchant installation has already been migrated; site-specific classification/migration remains an explicit operational action.

3. **Provider Contract & Payment Lifecycle — CURRENT GATE / DISCOVERY**
   - compare exact current source against current official UPayments documentation before implementation;
   - freeze charge request/response and success/failure contracts;
   - freeze authoritative truth hierarchy across validated server webhook, status reconciliation and browser return;
   - characterize callback authentication, replay/idempotency and duplicate-event behavior;
   - characterize current WooCommerce order/payment state transitions and ambiguity paths;
   - freeze reconciliation, retry, transient-failure and rate-limit rules without blindly retrying non-idempotent financial operations;
   - freeze refund contracts including full/partial refund, idempotency and failure recovery;
   - characterize multi-merchant routing and identity boundaries;
   - define redacted logging/evidence requirements for reconciliation and support;
   - centralize into a deterministic payment lifecycle/state-machine architecture only after characterization and contract review.

4. **Security Threat-Model Closure**
   - authorization/CSRF/replay/IDOR/SSRF/input/output/secrets/logging;
   - callback/webhook trust boundaries;
   - dependency and supply-chain review;
   - fail-closed payment/security behavior.

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
