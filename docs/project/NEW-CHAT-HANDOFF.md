# SimplixPay for UPayments — Clean Chat Handoff

Use this file with root `AGENTS.md`, `docs/project/PROJECT-STATUS.md`, the naming standard and the Master Engineering Playbook.

## Project

Canonical repository: `SimplixInnovations/simplixpay-upayments`  
Historical engineering/audit archive: `SimplixInnovations/upayments-woocommerce`  
Upstream provider repository: `upaymentskwt/woocommerce`  
Formal product: **SimplixPay for UPayments**  
Short integration reference: **SimplixPay UPayments**  
Product family reserved for broader use: **SimplixPay**  
Maintainer: **Simplix Innovations**

## Last independently verified milestone

- governance PR #1: **DONE / VERIFIED**
- governance merge: `cc565779c541178f63ae21f8e712f9708035361e`
- governance tree: `aa387ff76c300a12933c25932dece75e8def534e`
- merge is GitHub-signature verified and attributed to `SimplixInnovationsAdmin`
- canonical product root remains parentless at `1caf38410354322c1d842c28a40b0909ba31026d`
- historical fork retains the complete H12 PR/review/commit trail
- H12 customer-token identity: **DONE / VERIFIED**
- governance CI reproduced H12: PHP **1927 PASS / 0 FAIL**; Blocks **144 PASS / 0 FAIL**
- five H12 production blobs remained byte-identical after governance merge
- production readiness: **R0 — engineering hardening**
- broad public stable release: **NO**
- WordPress.org release: **NO**
- active program: **Phase 0 — Release & Repository Safety**

Treat all recorded SHAs as milestone evidence only; first action in a new chat is always live GitHub verification.

## Permanent governance now present

- root `AGENTS.md` instructs ChatGPT/Codex/Agents;
- `docs/project/PROJECT-STATUS.md` is the living state ledger;
- naming/identity standard is repository-controlled;
- Master Engineering Playbook is repository-controlled;
- CODEOWNERS points canonical ownership to `@SimplixInnovationsAdmin`;
- `Quality Gates` CI runs governance checks, PHP lint and both H12 harnesses;
- Dependabot tracks GitHub Actions;
- README/security/support/contribution/upstream/compatibility/issue/PR docs are SimplixPay-oriented;
- canonical MIT license is separated from provenance/trademark notice in `NOTICE.md`.

## Next Phase 0 implementation

**Release identity/updater ownership**:

1. remove/replace the dangerous upstream-controlled update path;
2. establish Simplix-owned semantic versioning (`0.x` during hardening; stable `1.0.0` only after release gates);
3. change public plugin metadata to **SimplixPay for UPayments** / Simplix Innovations;
4. design/test plugin folder + main filename + text-domain transition as an upgrade problem;
5. preserve all protected persisted/runtime `upayments` / `_upay_*` identities unless a dedicated migration explicitly changes them;
6. add updater/version/install/upgrade/rollback regression evidence.

## Repository-settings gate still open

The connected GitHub tools do not expose all repository Settings mutations. The settings gate must still configure/verify:

- `main` branch rules and required checks (`Governance`, `H12 Regression Harness`);
- require pull requests and prevent force-push/deletion of `main`;
- secret scanning and push protection where available;
- private vulnerability reporting;
- intentional merge methods and automatic source-branch deletion;
- repository topics/homepage and disabling unused Wiki/Projects if desired.

Do not confuse this settings gate with runtime/plugin implementation.

## Critical release blockers still open

1. Bootstrap still declares legacy upstream identity (`UPayments`, `3.1.1`, upstream author, text domain `upayments`).
2. Bundled updater still targets `https://github.com/upaymentskwt/woocommerce`.
3. Repository-settings hardening above remains incomplete.
4. Phase 9I historical migration is not implemented.
5. Payment lifecycle/webhook/status/refund behavior is not broadly certified.
6. WooCommerce/WordPress/PHP/HPOS/Blocks/WPML/feature/browser/performance matrices remain incomplete.
7. Release packaging/update/rollback/WordPress.org work remains.

## H12 non-negotiable token rules

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
- Secret option: `upayments_token_identity_secret_v2`; malformed is distinct from missing and fails closed.
- Selected saved card requires current valid provenance + exact scope/generation + fresh provider Retrieve + exact membership.

## Phase 9I blockers — all open

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

1. Read root `AGENTS.md` first.
2. Read `PROJECT-STATUS.md` and naming standard.
3. Verify live `main`, open PRs/branches, source, checks/updater and relevant official docs.
4. Reconcile any drift before implementation.
5. Prefer direct GitHub operations; delegate only genuinely inaccessible actions.
6. Never approve/merge from an Agent report alone.
7. Pin review/merge decisions to exact base/head SHAs.
8. After merge, independently verify merged state, `main`, critical files/checks and branch cleanup before DONE.
9. Update `PROJECT-STATUS.md` after verified milestone changes.

## Next sequence

1. Finish repository-settings hardening.
2. Phase 0 release identity/updater ownership and versioning.
3. Phase 9I-A read-only migration classifier.
4. Phase 9I-B/C executor and bounded operations.
5. Provider/payment lifecycle and threat-model audit.
6. Standard quality platform and incremental architecture extraction.
7. Compatibility/performance/UX certification.
8. Release engineering and eventual WordPress.org preparation.

## Copy-ready opening prompt

```text
Continue the SimplixPay for UPayments engineering program in SimplixInnovations/simplixpay-upayments.

Read AGENTS.md first, then docs/project/PROJECT-STATUS.md, docs/project/NAMING-IDENTITY-STANDARD.md, docs/project/NEW-CHAT-HANDOFF.md and relevant sections of docs/project/MASTER-ENGINEERING-PLAYBOOK.md.

Treat recorded SHAs/status as milestone evidence until you independently verify live GitHub main, branches, PRs, current source and checks. Reconcile any drift before work.

Do repository work directly wherever GitHub tools permit. Delegate only genuinely inaccessible actions. Preserve protected legacy upayments/upay identifiers unless an approved tested migration changes them. Never approve or merge without independent verification pinned to exact SHAs.

Continue from the first unfinished permitted gate in PROJECT-STATUS.md.
```
