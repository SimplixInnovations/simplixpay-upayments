# SimplixPay for UPayments — Repository Agent Instructions

These instructions apply to the entire repository. A nested `AGENTS.md` may tighten them for a subtree but may not weaken payment/security invariants.

## Read first

Before substantive work, read in this order:

1. `docs/project/PROJECT-STATUS.md`
2. `docs/project/NAMING-IDENTITY-STANDARD.md`
3. `docs/project/NEW-CHAT-HANDOFF.md`
4. relevant sections of `docs/project/MASTER-ENGINEERING-PLAYBOOK.md`
5. `docs/project/BASELINE-H12.md` when token/saved-card/subscription identity is relevant

## Canonical identity

- Repository: `SimplixInnovations/simplixpay-upayments`
- Formal product: **SimplixPay for UPayments**
- Short integration reference: **SimplixPay UPayments**
- Product family reserved for broader use: **SimplixPay**
- Canonical slug: `simplixpay-upayments`
- New PHP namespace root: `Simplix\Pay\UPayments`
- New global prefix: `simplixpay_upayments_`
- New constants: `SIMPLIXPAY_UPAYMENTS_*`

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

Changing one requires an explicitly approved migration contract, fallback/rollback semantics and tests.

## Payment/security engineering

- Evidence before claims.
- Characterize before refactoring.
- Fail closed on ambiguous security/payment identity.
- Do not blindly retry non-idempotent charges/refunds/auto-deduct operations.
- Do not infer provider success from browser/user-facing prose.
- Browser redirects are not the sole payment source of truth.
- Preserve H12 token/provenance contracts unless a later approved phase explicitly supersedes them.
- Never expose API secrets, bearer tokens, card data, customer/card tokens, H12 identity secrets/provenance, unnecessary PII or production database exports.

## Change discipline

- Work on a dedicated branch from a freshly verified base.
- Keep changes phase-scoped; avoid drive-by cleanup in payment-critical files.
- Add regression evidence for defects.
- Do not mechanically rename/refactor payment code without characterization tests.
- Update project status/docs when a verified milestone changes project truth.
- Do not claim compatibility/security/performance/production readiness without evidence.

## Review and merge

Agent reports are evidence requests, not proof. Independently verify source, diff, tests and checks. Pin approval to exact base/head SHAs.

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
php tests/harness/phase-9g-h12-php-harness.php
node --check tests/harness/phase-9g-h12-blocks-harness.js
node tests/harness/phase-9g-h12-blocks-harness.js
```

Also run repository CI and phase-specific tests.