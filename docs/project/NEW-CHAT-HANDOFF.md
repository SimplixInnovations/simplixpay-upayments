# SimplixPay for UPayments — Clean Chat Handoff

Use this file with root `AGENTS.md`, `PROJECT-STATUS.md`, the closed readiness record, the naming standard and the Master Engineering Playbook.

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

**PRE-PHASE-0 READY / VERIFIED.** Repository foundation/readiness is closed.

The next permitted runtime-changing phase is:

**Phase 0 — SimplixPay release identity and updater ownership.**

This does not mean the plugin is stable, broadly certified, production-release ready or WordPress.org ready. It means the repository/control plane is ready for controlled Phase 0 engineering.

## Readiness evidence base

Immediately before the closing readiness-status PR:

- `main`: `7e530c2c6881c04a3170e110b23289d90185da14`
- tree: `64973fa4918061bdf8489319712ef2c79813a45b`
- GitHub signature: **VERIFIED**
- real remote branches: `main` only
- open PRs: 0
- tags: none
- releases: none
- standalone repo (`fork: false`)
- MIT recognized
- squash-only merging
- automatic source-branch deletion enabled and verified
- Projects/Wiki/Discussions off
- homepage `https://simplixi.com`
- H12 PHP: **1927 PASS / 0 FAIL**
- H12 Blocks: **144 PASS / 0 FAIL**

Repository ruleset `21327778` was verified active with deletion/non-fast-forward protection, linear history, PR requirement, review-thread resolution, squash-only merging and strict required `Governance` + `H12 Regression Harness` checks.

Security verified enabled: private vulnerability reporting, Dependabot security updates, secret scanning and push protection.

GitHub contributors API returned only `SimplixInnovationsAdmin`. Local clone evidence before closure was clean with `HEAD...origin/main = 0 0`.

Treat recorded SHAs as milestone evidence only. Always re-check live GitHub before acting.

## Permanent governance/control plane

- `AGENTS.md` — mandatory execution/review rules;
- `docs/project/PROJECT-STATUS.md` — living verified program state;
- `docs/project/REPOSITORY-READINESS.md` — closed repository-readiness evidence record;
- `docs/project/REPOSITORY-AUDIT.md` — whole tracked-tree classification;
- `docs/project/NAMING-IDENTITY-STANDARD.md` — frozen public/technical identity + compatibility registry;
- `docs/project/MASTER-ENGINEERING-PLAYBOOK.md` — complete engineering program;
- `docs/project/BASELINE-H12.md` — canonical-root/H12 provenance;
- `.github/CODEOWNERS` — canonical ownership by `@SimplixInnovationsAdmin`;
- `Quality Gates` CI — governance, all tracked PHP syntax and H12 regression baseline;
- root security/support/contribution/upstream/license/provenance policies.

## Public repository state

README is Simplix-led, retains the Woo Agency Partner proof/link, clearly identifies UPayments as the provider, and explicitly bounds maturity/compatibility claims.

Evidence-safe About topics at readiness closure:

`checkout-blocks`, `ecommerce`, `hpos`, `payment-gateway`, `payments`, `php`, `simplixpay`, `upayments`, `woocommerce`, `woocommerce-payment-gateway`, `wordpress`, `wpml`.

`hpos-compatible` and `wpml-ready` are intentionally absent until certification evidence exists.

## Contributor/account note

The repository contributors API returned only `SimplixInnovationsAdmin`, satisfying the sole-contributor presentation objective. The historical root author string remains `Simplix Innovations <info@simplixi.com>`.

The GitHub CLI was not granted the `user` scope needed to enumerate account emails. Before any future manual IDE-authored commit using `info@simplixi.com`, verify that address in GitHub Settings so future local commits map to `SimplixInnovationsAdmin`. This is account-level commit hygiene, not a blocker to the closed repository gate.

## Frozen H12 evidence

- `UPayments.php` — `64c789e81ae4d292ef9b1d7382812c319a44bc25`
- `includes/Token/CustomerTokenIdentity.php` — `85430d37e9baf540842f5655b86ccf0eca3e6aea`
- Blocks integration — `813d192d69c069eb7ee11df93acc9dbdf03e270a`
- Scheduler — `5251866d4df2d1326e7c09f0c8ec1d146c0bb325`
- CycleClaim — `c34d83e2d77cc65024fe663e4c378cecb2b17347`
- archived H12 engineering changelog — `8c42bc6fdae163dd4159b8036b05cd2f70cc3d5d`

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

## Phase 0 — now permitted

Phase 0 must:

1. remove/replace the dangerous upstream-controlled update path;
2. establish Simplix-owned semantic versioning (`0.x` while hardening; `1.0.0` only after stable gates);
3. change public plugin metadata to **SimplixPay for UPayments** / Simplix Innovations;
4. design/test folder + main filename + text-domain transition as an upgrade problem;
5. preserve protected persisted/runtime identifiers unless a dedicated migration explicitly changes them;
6. add updater/version/install/upgrade/rollback regression evidence.

The current inherited `UPayments.php` name/header/version/updater are intentionally still present at the baseline. They are Phase 0 work, not a readiness defect.

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
2. Read `PROJECT-STATUS.md`.
3. Read the naming standard before touching names/IDs.
4. Read the closed readiness record when repository-foundation evidence is relevant.
5. Verify live `main`, branches, PRs, exact source and CI before implementation.
6. Reconcile documented state with live GitHub; report drift.
7. Prefer direct GitHub operations; delegate only genuinely inaccessible actions.
8. Never approve/merge from an Agent or bot report alone.
9. Pin review/merge decisions to exact base/head SHAs.
10. After merge, verify resulting `main`, critical files/checks and branch cleanup before DONE.
11. Update `PROJECT-STATUS.md` after verified milestone/state changes.

## Program sequence

0. **Repository Foundation / Readiness Gate** — **DONE / VERIFIED**.
1. **Phase 0 — SimplixPay release identity and updater ownership** — next.
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

Read AGENTS.md first, then docs/project/PROJECT-STATUS.md, docs/project/NAMING-IDENTITY-STANDARD.md, docs/project/NEW-CHAT-HANDOFF.md and relevant sections of docs/project/MASTER-ENGINEERING-PLAYBOOK.md. Repository readiness is closed; use docs/project/REPOSITORY-READINESS.md as the evidence record if needed.

Treat documented SHAs/status as milestone evidence until you independently verify live GitHub main, branches, PRs, current source and checks. Reconcile drift before work.

Work directly in GitHub wherever tools permit. Delegate only genuinely inaccessible actions. Preserve protected historical upayments/upay identifiers unless an approved tested migration changes them. Never approve or merge without independent verification pinned to exact SHAs.

Continue from the first unfinished permitted task in Phase 0.
```
