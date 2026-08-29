# SimplixPay for UPayments — Repository Agent Instructions

These instructions apply to the entire repository. A nested `AGENTS.md` may tighten them for a subtree but may not weaken payment/security invariants.

## Read first

Before substantive work, read in this order:

1. `docs/project/PROJECT-STATUS.md`
2. `docs/project/NAMING-IDENTITY-STANDARD.md`
3. `docs/project/NEW-CHAT-HANDOFF.md`
4. `docs/project/PHASE-0-RELEASE-IDENTITY.md`
5. `docs/project/PHASE-9I-MIGRATION.md`
6. `docs/project/PROVIDER-PAYMENT-LIFECYCLE.md`
7. `docs/project/SECURITY-THREAT-MODEL.md`
8. `docs/project/ARCHITECTURE-CODE-QUALITY.md`
9. `docs/project/QUALITY-PLATFORM.md` when the Full Automated Quality Platform is current
10. relevant living sections of `docs/project/MASTER-ENGINEERING-PLAYBOOK.md`
11. `docs/project/BASELINE-H12.md` when token/saved-card/subscription identity is relevant
12. `docs/project/REPOSITORY-READINESS.md` for historical repository-foundation evidence when relevant

## Canonical identity

- Repository: `SimplixInnovations/simplixpay-upayments`
- Formal product: **SimplixPay for UPayments**
- Short integration reference: **SimplixPay UPayments**
- Product family reserved for broader use: **SimplixPay**
- Canonical slug: `simplixpay-upayments`
- New PHP namespace root: `Simplix\Pay\UPayments`
- New global prefix: `simplixpay_upayments_`
- New constants: `SIMPLIXPAY_UPAYMENTS_*`

`SimplixPay` alone is reserved for the future broader/multi-provider payment product. Do not use it as the short name for this provider-specific plugin when ambiguity matters.

Do not invent alternate product names, slugs, prefixes or namespace schemes.

## Mandatory freshness rule

Never assume a documented SHA/status is current. Before implementation or review:

- verify live `main`;
- inspect open PRs and relevant branches;
- inspect the exact current source/diff;
- inspect CI/check state;
- reconcile `PROJECT-STATUS.md` with reality;
- use current official provider/platform documentation when behavior depends on it.

Live evidence beats stale project text. Do not rewrite historical facts merely to make a status document look current.

## Phase gate rule

If `PROJECT-STATUS.md` says **pre-Phase-0 repository readiness** is open, finish `REPOSITORY-READINESS.md` before changing plugin/runtime identity, updater behavior, main plugin file, text domain or protected payment identifiers.

Do not start a later phase because code has been drafted. Phase progression requires independent verification and an explicit current-state update.

When **Architecture & Code-Quality Foundation** is current, `ARCHITECTURE-CODE-QUALITY.md` is mandatory. Characterize a responsibility before extracting it, keep legacy public entry points as compatibility wrappers where required, and follow the frozen extraction order unless new evidence justifies a separately reviewed change.

For A5 and every later architecture tranche, keep `tests/harness/architecture-foundation-harness.php`, `tests/harness/architecture-runtime-bindings-harness.php`, `tests/harness/architecture-bootstrap-path-harness.php`, `tests/harness/architecture-provider-endpoints-harness.php`, `tests/harness/architecture-payment-method-availability-harness.php`, `tests/harness/architecture-gateway-settings-harness.php`, `tests/harness/architecture-subscription-presentation-harness.php` and `tests/harness/architecture-checkout-orchestration-harness.php` mandatory in Quality Gates.

When **Full Automated Quality Platform** is current, `QUALITY-PLATFORM.md` is mandatory. Introduce tools progressively against named risks, commit dependency locks, keep Composer development-only until an explicit packaging migration exists, and do not convert green tooling into platform-certification claims. Keep every historical/architecture harness and `tests/harness/quality-platform-foundation-harness.php` mandatory.

## Protected compatibility identities

Rebranding must never silently change persisted payment identity. Do not globally rename `upayments` or `_upay_*`.

Protected by default:

- gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks identity `upayments`;
- Store API extension key `upayments`;
- callback route `wc_upayments`;
- existing `_upay_*` order/user/product metadata;
- `upayments_token_identity_secret_v2`;
- H12 provenance/scope/generation keys;
- `upay_process_subscriptions`;
- existing billing-attempt tables/state;
- historical order payment-method identity.

Changing one requires an explicitly approved migration contract, old/new precedence, fallback/rollback semantics and tests.

## Payment/security engineering

- Evidence before claims.
- Characterize before refactoring.
- Fail closed on ambiguous security/payment identity.
- Do not blindly retry non-idempotent charges/refunds/auto-deduct operations.
- Do not infer provider success from browser/user-facing prose.
- Browser redirects are not the sole payment source of truth.
- Preserve H12 token/provenance contracts unless a later approved phase explicitly supersedes them.
- Never expose API secrets, bearer tokens, card data, customer/card tokens, H12 identity secrets/provenance, unnecessary PII or production database exports.

## Public claims

- A green H12 workflow is a regression baseline, not broad production certification.
- Do not add WooCommerce/WordPress/PHP/HPOS/Blocks/WPML/performance compatibility badges until the corresponding matrix is independently verified.
- External credentials such as the Simplix Innovations Woo Agency Partner listing may be shown only while the official source remains verifiable.
- Do not imply WooCommerce or UPayments endorses this plugin.

## Change discipline

- Work on a dedicated branch from a freshly verified base.
- Keep changes phase-scoped; avoid drive-by cleanup in payment-critical files.
- Add regression evidence for defects.
- Do not mechanically rename/refactor payment code without characterization tests.
- During architecture work, prefer strangler/delegation seams over moving large blocks wholesale.
- Do not grow `UPayments.php` with new responsibilities; new responsibilities require an explicit module boundary.
- Update project status/docs when a verified milestone changes project truth.
- Do not claim compatibility/security/performance/production readiness without evidence.

## Review and merge

Agent/bot reports are evidence requests, not proof. Independently verify source, diff, tests and checks. Pin approval to exact base/head SHAs.

If verification fails:

`NOT APPROVED.`  
`DO NOT MERGE.`

External implementation reports end:

`STOP. DO NOT MERGE.`  
`Awaiting reviewer verification.`

Merge-only reports end:

`STOP.`  
`Awaiting reviewer verification of merge.`

After merge, independently verify `main`, expected topology for the chosen merge method, critical files/checks and branch cleanup before marking DONE / VERIFIED.

## Initial validation commands

Until the standard quality platform replaces them:

```bash
php tests/harness/architecture-foundation-harness.php
php tests/harness/architecture-runtime-bindings-harness.php
php tests/harness/architecture-bootstrap-path-harness.php
php tests/harness/architecture-provider-endpoints-harness.php
php tests/harness/architecture-payment-method-availability-harness.php
php tests/harness/architecture-gateway-settings-harness.php
php tests/harness/architecture-subscription-presentation-harness.php
php tests/harness/architecture-checkout-orchestration-harness.php
php tests/harness/quality-platform-foundation-harness.php
php tests/harness/security-threat-model-harness.php
php tests/harness/phase-9g-h12-php-harness.php
node --check tests/harness/phase-9g-h12-blocks-harness.js
node tests/harness/phase-9g-h12-blocks-harness.js
composer validate --strict
composer audit --locked --no-interaction
composer quality
```

Also run repository CI and phase-specific tests.
