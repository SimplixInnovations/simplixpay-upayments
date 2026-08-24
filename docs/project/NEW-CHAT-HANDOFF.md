# SimplixPay for UPayments — Clean Chat Handoff

Use this file with root `AGENTS.md`, `PROJECT-STATUS.md`, `REPOSITORY-READINESS.md`, the naming standard and the Master Engineering Playbook.

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

`SimplixPay` alone is reserved for the future broader/multi-provider payment product, including the planned **SimplixPay for WooCommerce** direction.

## Current program position

The repository is in **pre-Phase-0 repository readiness**.

**Do not begin runtime/plugin identity changes until `docs/project/REPOSITORY-READINESS.md` is closed and `PROJECT-STATUS.md` says PRE-PHASE-0 READY / VERIFIED.**

The next runtime-changing program phase is:

**Phase 0 — SimplixPay release identity and updater ownership.**

## Last independently verified canonical milestones

- clean standalone product root: `1caf38410354322c1d842c28a40b0909ba31026d` — parentless;
- governance PR #1: **DONE / VERIFIED**;
- governance squash merge: `cc565779c541178f63ae21f8e712f9708035361e`;
- governance/license follow-up PR #4: `c6e8c32044da254654e7a928e80900d943843e7a`;
- historical H12 merge retained in audit repo: `93e9925247a8bfade626cb822136852fd96eaea2`;
- H12 customer-token identity hardening: **DONE / VERIFIED**;
- last verified H12 baseline: PHP **1927 PASS / 0 FAIL**; Blocks **144 PASS / 0 FAIL**;
- production maturity: **pre-release engineering hardening**;
- stable SimplixPay release: **NO**;
- WordPress.org release: **NO**.

Treat every recorded SHA as milestone evidence only. First action in a new session is always live GitHub verification.

## Pre-Phase-0 readiness findings

The readiness audit found and is correcting:

1. README had become too internal and had lost the verified Woo Agency Partner proof/link.
2. Root `CHANGELOG.md` was a ~113 KB pre-product engineering transcript rather than a product release changelog.
3. GitHub Actions were on mutable major tags and one major behind current releases.
4. Dependabot opened separate setup-node/checkout major PRs; the canonical workflow should incorporate audited current releases and group future Actions updates.
5. GitHub About topics/homepage, merge policy, branch rules and security settings require manual configuration because current connected tools cannot write them.
6. Merged governance branches and Dependabot branches need cleanup.
7. A local clone made before the history rewrite can show misleading large sync divergence (for example 1 incoming / 131 outgoing) despite no file changes.
8. GitHub contributor statistics can remain stale after the history rewrite. The clean-root author email `info@simplixi.com` also needs to be associated/verified on `SimplixInnovationsAdmin` so GitHub can map that commit correctly.

Exact actions live in `REPOSITORY-READINESS.md`.

## Permanent governance/control plane

- `AGENTS.md` — mandatory execution rules;
- `docs/project/PROJECT-STATUS.md` — living verified engineering state;
- `docs/project/REPOSITORY-READINESS.md` — current pre-Phase-0 exit gate;
- `docs/project/NAMING-IDENTITY-STANDARD.md` — frozen public/technical identity and compatibility registry;
- `docs/project/MASTER-ENGINEERING-PLAYBOOK.md` — complete engineering program;
- `docs/project/BASELINE-H12.md` — canonical-root/H12 provenance;
- `.github/CODEOWNERS` — canonical ownership by `@SimplixInnovationsAdmin`;
- `Quality Gates` CI — governance, PHP syntax and H12 regression baseline;
- root security/support/contribution/upstream/license/provenance policies.

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
- Secret option `upayments_token_identity_secret_v2` is protected; malformed is distinct from missing and fails closed.
- Selected saved card requires current valid provenance + exact scope/generation + fresh provider Retrieve + exact membership.

## Protected compatibility identities

Do not globally rename historical `upayments` / `_upay_*` identities for branding. Protected by default include gateway/payment ID `upayments`, `woocommerce_upayments_settings`, Blocks/Store API identity, `wc_upayments`, existing metadata, H12 secret/provenance, subscription scheduler identities, billing-attempt table/schema state and historical order payment-method values.

Any change requires an explicit tested migration contract.

## Phase 0 — only after readiness closes

Phase 0 must:

1. remove/replace the dangerous upstream-controlled update path;
2. establish Simplix-owned semantic versioning (`0.x` while hardening; `1.0.0` only after stable gates);
3. change public plugin metadata to **SimplixPay for UPayments** / Simplix Innovations;
4. design/test folder + main filename + text-domain transition as an upgrade problem;
5. preserve protected persisted/runtime identifiers unless a dedicated migration explicitly changes them;
6. add updater/version/install/upgrade/rollback regression evidence.

## Phase 9I blockers — all remain open

1. Unscoped legacy tokens
2. Current-scope orphan histories
3. Cross-user token conflicts
4. Malformed scoped histories
5. Secret generation mismatches
6. Card-token-only historical identity
7. Prior-scope same-generation histories
8. Non-scalar evidence
9. Orphan metadata
10. >200/incomplete history
11. Unloadable orders
12. Force-refresh failures
13. Malformed-vs-missing secret distinction

## Required working method

1. Read root `AGENTS.md`.
2. Read `PROJECT-STATUS.md`, then `REPOSITORY-READINESS.md` if readiness is not closed.
3. Read naming standard before touching names/IDs.
4. Verify live `main`, open PRs/branches, exact source and CI before implementation.
5. Reconcile documented state with live GitHub; report drift.
6. Prefer direct GitHub operations; delegate only genuinely inaccessible actions.
7. Never approve/merge from an Agent or bot report alone.
8. Pin review/merge decisions to exact base/head SHAs.
9. After merge, verify resulting `main`, critical files/checks and branch cleanup before DONE.
10. Update `PROJECT-STATUS.md` after verified milestone/state changes.

## Program sequence

0. **Repository Foundation / Readiness Gate** — current; must close first.
1. **Phase 0 — SimplixPay release identity and updater ownership**.
2. **Phase 9I — Historical token-identity migration**.
3. Provider contract + payment lifecycle/state machine audit.
4. Security threat-model closure.
5. Architecture/code-quality foundation and full quality platform.
6. WooCommerce/WordPress/PHP/HPOS/Blocks and multilingual certification.
7. Feature-specific certification, performance, UX/accessibility and diagnostics.
8. Release engineering and eventual WordPress.org preparation.

## Copy-ready opening prompt

```text
Continue the SimplixPay for UPayments engineering program in SimplixInnovations/simplixpay-upayments.

Read AGENTS.md first, then docs/project/PROJECT-STATUS.md. If pre-Phase-0 readiness is not closed, read docs/project/REPOSITORY-READINESS.md and finish that gate before any runtime identity work. Then read docs/project/NAMING-IDENTITY-STANDARD.md, docs/project/NEW-CHAT-HANDOFF.md and relevant sections of docs/project/MASTER-ENGINEERING-PLAYBOOK.md.

Treat documented SHAs/status as milestone evidence until you independently verify live GitHub main, branches, PRs, current source and checks. Reconcile drift before work.

Work directly in GitHub wherever tools permit. Delegate only genuinely inaccessible actions. Preserve protected historical upayments/upay identifiers unless an approved tested migration changes them. Never approve or merge without independent verification pinned to exact SHAs.

Continue from the first unfinished permitted gate in PROJECT-STATUS.md.
```
