# Full Automated Quality Platform

**Status:** Q1 / IMPLEMENTATION

**Current branch:** `quality/platform-foundation`

**Verified base `main`:** `3223a882867634a2ba7588d7afbd2b2e4b4c21e4`

**Verified base tree:** `392b73425fa3219b6414a0984136b92c8ef77576`

## Entry evidence

Architecture A5 is DONE / VERIFIED:

- PR #25 final reviewed head `997e18d8eb6264a84c6a9a35158213d3d655e6b3`;
- exact reviewed tree `392b73425fa3219b6414a0984136b92c8ef77576`;
- exact-head Quality Gates run #173: SUCCESS;
- final independent review: clean with zero unresolved threads;
- squash merge `3223a882867634a2ba7588d7afbd2b2e4b4c21e4` on parent `d24b83356cc766f82c3ad9e529d3ec3f4194e887`;
- push-triggered post-merge Quality Gates run #174: SUCCESS;
- implementation branch deleted after verified merge.

## Q1 purpose

Establish the standard, locked development quality toolchain without changing payment, provider, persistence or compatibility behavior.

Q1 introduces:

- a canonical Composer package manifest and committed lockfile;
- PHPUnit for pure service-level unit tests;
- PHPStan at an explicit level and explicit PHP 7.2 analysis target, initially limited to clean pure modules;
- risk-focused PHPCS/WPCS security checks on the initial pure-module scope;
- locked dependency auditing;
- declared PHP 7.2 and PHP 8.2 syntax checks;
- dedicated CI jobs that gate the already-required H12 Regression check while every historical harness remains mandatory;
- a permanent executable foundation harness that prevents toolchain, scope and distribution drift.

## Composer and distribution rule

Composer is development tooling only in Q1.

- Production `require` contains only the declared PHP floor.
- Plugin runtime does not load `vendor/autoload.php`.
- Composer plugin execution is disabled.
- `vendor/`, tests, the lockfile and analysis configuration are excluded from a release package through `.distignore`.
- No runtime behavior may depend on Composer until a later separately reviewed packaging/autoload migration defines install, upgrade and rollback semantics.

## Initial static-analysis scope

PHPStan level 5 begins with:

- `src/Payment/ProviderResult.php`;
- `src/Provider/EndpointResolver.php`.

The scope has no baseline or ignored errors. `CheckoutPayload` is covered by PHPUnit and the permanent A5/H12 harnesses, but is not yet in PHPStan because its legacy PHPDoc narrows values before defensive runtime validation. That typing work requires a separately characterized expansion rather than suppressions or payment-critical rewrites performed only to satisfy a tool.

PHPCS/WPCS initially applies named security rules to `CheckoutPayload`, `ProviderResult` and `EndpointResolver`. Two raw server-value reads retain narrow inline explanations because the route and method are validated by exact allowlists and generic text sanitization would alter route bytes.

## Q1 acceptance

Q1 may be merged only when:

1. Composer metadata validates strictly and the committed lockfile installs exactly;
2. the locked dependency audit is clean;
3. PHPUnit passes with no risky tests or warnings;
4. PHPStan passes at the recorded level and scope with no baseline;
5. PHPCS/WPCS passes the recorded named-risk scope;
6. declared-floor and regression-runtime PHP syntax jobs pass;
7. the complete historical and architecture regression stack remains green;
8. exact-head independent review is clean with zero unresolved valid findings;
9. merge, post-merge CI and branch cleanup are independently verified.

## Non-claims

Q1 is a quality-platform foundation, not platform certification. It does not certify WordPress, WooCommerce, PHP, HPOS, Checkout Blocks, WPML/WCML, browser, accessibility, performance, PCI/compliance or production readiness. It also does not replace the existing provider, Security, Phase 9I, H12 or architecture regression contracts.

Later quality tranches expand WordPress/WooCommerce integration tests, static-analysis scope, compatibility matrices, mutation testing, CodeQL and browser tooling only when each protects a named risk.
