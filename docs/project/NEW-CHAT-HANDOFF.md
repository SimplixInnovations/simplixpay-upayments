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

`SimplixPay` alone is reserved for the future broader/multi-provider payment product.

## Current program position

The repository remains in **pre-Phase-0 repository readiness — final external cleanup**.

**Do not begin runtime/plugin identity changes until `REPOSITORY-READINESS.md` is closed and `PROJECT-STATUS.md` says PRE-PHASE-0 READY / VERIFIED.**

The next runtime-changing program phase is:

**Phase 0 — SimplixPay release identity and updater ownership.**

## Latest independently verified canonical state

Before the current status-sync change:

- `main`: `7c86bbc29dd6d311004c0305533d5d731327f05e`
- `main` tree: `b11efcf0d0acf008d2088c67b9975226a72d7e5d`
- tip signature: **VERIFIED**
- `main`: default branch and reports **protected: true**
- canonical reachable history: four commits only
- PR #5 approved head tree exactly equals merged `main` tree
- PR #5 Quality Gates: **SUCCESS**
- H12 PHP: **1927 PASS / 0 FAIL**
- H12 Blocks: **144 PASS / 0 FAIL**
- open pull requests: **0** at verification time
- PR #2/#3 Dependabot updates: closed, unmerged, superseded
- production maturity: **pre-release engineering hardening**
- stable SimplixPay release: **NO**
- WordPress.org release: **NO**

Treat every recorded SHA as milestone evidence only. A new session must always re-check live GitHub first.

## Live repository settings already verified

The repository API confirms:

- standalone repo (`fork: false`);
- correct SimplixPay description;
- homepage `https://simplixi.com`;
- GitHub recognizes the license as MIT;
- Issues on;
- Projects off;
- Wiki off;
- Discussions off;
- squash merging on;
- merge commits off;
- rebase merging off;
- automatic source-branch deletion on;
- `main` protected;
- relevant WooCommerce / WordPress / payments / UPayments / SimplixPay / HPOS / WPML topics populated.

The current connector does **not** expose active ruleset details or repository security-analysis/private-vulnerability settings, so those details still require a manual GitHub Settings verification before readiness closes.

## Remaining pre-Phase-0 blockers

1. Delete three old remote branches:
   - `phase-0/repository-governance`
   - `phase-0/governance-finalize`
   - `pre-phase0/repository-readiness`
2. Reconcile any old local clone to `origin/main`; final divergence must be `0 0`.
3. Associate/verify `info@simplixi.com` on `SimplixInnovationsAdmin`; current root commit API still returns `author: null`.
4. Allow GitHub contributor statistics time to refresh after the history rewrite; GitHub documents roughly a 24-hour refresh window.
5. Manually verify the detailed `main` ruleset requirements and security settings listed in `REPOSITORY-READINESS.md`.
6. If final exit policy requires explicit push-run evidence, inspect the `main` Quality Gates run in GitHub Actions UI; the current connector enumerates PR-triggered runs but not the push-run listing.

The three stale branches are independently proven safe to delete: each branch's final tree exactly equals the corresponding squash-merge tree on canonical history. See `REPOSITORY-READINESS.md` for the exact SHAs and deletion command.

## Frozen H12 evidence

Current verified `main` retains:

- `UPayments.php` — `64c789e81ae4d292ef9b1d7382812c319a44bc25`
- `includes/Token/CustomerTokenIdentity.php` — `85430d37e9baf540842f5655b86ccf0eca3e6aea`
- Blocks integration — `813d192d69c069eb7ee11df93acc9dbdf03e270a`
- Scheduler — `5251866d4df2d1326e7c09f0c8ec1d146c0bb325`
- CycleClaim — `c34d83e2d77cc65024fe663e4c378cecb2b17347`
- archived H12 engineering changelog — `8c42bc6fdae163dd4159b8036b05cd2f70cc3d5d`

## Permanent governance/control plane

- `AGENTS.md` — mandatory execution rules;
- `docs/project/PROJECT-STATUS.md` — living verified engineering state;
- `docs/project/REPOSITORY-READINESS.md` — current pre-Phase-0 exit gate;
- `docs/project/REPOSITORY-AUDIT.md` — full tracked-tree classification;
- `docs/project/NAMING-IDENTITY-STANDARD.md` — frozen public/technical identity and compatibility registry;
- `docs/project/MASTER-ENGINEERING-PLAYBOOK.md` — complete engineering program;
- `docs/project/BASELINE-H12.md` — canonical-root/H12 provenance;
- `.github/CODEOWNERS` — canonical ownership by `@SimplixInnovationsAdmin`;
- `Quality Gates` CI — governance, all tracked PHP syntax and H12 regression baseline;
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
2. Read `PROJECT-STATUS.md`, then `REPOSITORY-READINESS.md` while readiness is open.
3. Read naming standard before touching names/IDs.
4. Verify live `main`, PRs/branches, exact source and CI before implementation.
5. Reconcile documented state with live GitHub; report drift.
6. Prefer direct GitHub operations; delegate only genuinely inaccessible actions.
7. Never approve/merge from an Agent or bot report alone.
8. Pin review/merge decisions to exact base/head SHAs.
9. After merge, verify resulting `main`, critical files/checks and branch cleanup before DONE.
10. Update `PROJECT-STATUS.md` after verified milestone/state changes.

## Program sequence

0. **Repository Foundation / Readiness Gate** — current; final cleanup remains.
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