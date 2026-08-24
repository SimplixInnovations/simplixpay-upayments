# Engineering Roadmap

The complete engineering program is controlled by the project documents under `docs/project/`. This file is the public/high-level sequence only.

Use:

- [`project/PROJECT-STATUS.md`](project/PROJECT-STATUS.md) for current verified state and the next permitted action;
- [`project/REPOSITORY-READINESS.md`](project/REPOSITORY-READINESS.md) for the current pre-Phase-0 repository exit gate;
- [`project/MASTER-ENGINEERING-PLAYBOOK.md`](project/MASTER-ENGINEERING-PLAYBOOK.md) for detailed phases, quality gates and release criteria;
- [`project/NAMING-IDENTITY-STANDARD.md`](project/NAMING-IDENTITY-STANDARD.md) for product/slug/namespace and compatibility identity rules.

## Program order

0. **Repository Foundation & Readiness — current gate**
   - canonical standalone history/provenance;
   - public repository identity and documentation;
   - governance/CODEOWNERS;
   - baseline CI and dependency hygiene;
   - GitHub About/merge/rules/security settings;
   - contributor attribution/cache convergence;
   - local-clone reconciliation after history rewrite.

1. **Phase 0 — SimplixPay release identity and updater ownership**
   - remove/replace upstream-controlled updater;
   - establish independent SimplixPay semantic versioning;
   - public plugin header/author/product identity;
   - tested folder/main-file/text-domain upgrade path;
   - install/update/rollback/conflict regression evidence;
   - preserve protected historical payment identities.

2. **Phase 9I — Historical token-identity migration**
   - read-only deterministic preflight;
   - explicit migratable/blocked/indeterminate classification;
   - bounded/idempotent executor;
   - admin/CLI batch workflow and resumability;
   - rerun/freeze H12 evidence after migration work.

3. **Provider Contract & Payment Lifecycle**
   - charge/status/webhook/return/refund/multi-merchant contracts;
   - deterministic payment state machine;
   - reconciliation/idempotency/rate-limit rules;
   - failure and ambiguity semantics.

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

A numbered roadmap item is not complete because work exists in a branch or an Agent reports success. `PROJECT-STATUS.md` controls truth after independent exact-SHA verification and post-merge validation.
