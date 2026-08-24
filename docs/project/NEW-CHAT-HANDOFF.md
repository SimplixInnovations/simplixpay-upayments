# SimplixPay for UPayments — Clean Chat Handoff

Use this file with `AGENTS.md`, `docs/project/PROJECT-STATUS.md`, the naming standard and the Master Engineering Playbook.

## Project

Canonical repository: `SimplixInnovations/simplixpay-upayments`  
Historical engineering/audit archive: `SimplixInnovations/upayments-woocommerce`  
Upstream provider repository: `upaymentskwt/woocommerce`  
Formal product: **SimplixPay for UPayments**  
Short integration reference: **SimplixPay UPayments**  
Product family reserved for broader use: **SimplixPay**  
Maintainer: **Simplix Innovations**

## Last independently verified canonical state

- `main`: `1caf38410354322c1d842c28a40b0909ba31026d`
- `main` tree: `34594c00d243b59345ec9fbb3a88d2e1ec8f3efc`
- canonical `main` is a parentless root commit; standalone product history intentionally begins there
- historical fork retains complete H12 PR/review/commit trail
- H12 customer-token identity: **DONE / VERIFIED**
- five frozen H12 production blobs are byte-identical in the canonical root
- production readiness: **R0 — engineering hardening**
- broad public stable release: **NO**
- WordPress.org release: **NO**
- active program: **Phase 0 — Release & Repository Safety**

## Current Phase 0 work

Repository-governance bootstrap is the first Phase 0 change. It establishes permanent agent instructions, canonical project documents, ownership/governance files, initial CI and accurate repository-facing documentation. It must not change payment runtime behavior.

After governance is merged, the next Phase 0 implementation is release identity/updater ownership: remove the dangerous upstream-controlled update path, establish Simplix version/product identity, and design a tested upgrade path for folder/main-file/text-domain changes without renaming protected persisted payment identifiers.

## Critical release blockers still open

1. Bootstrap still declares legacy upstream identity (`UPayments`, `3.1.1`, upstream author, text domain `upayments`).
2. Bundled updater still targets `https://github.com/upaymentskwt/woocommerce`.
3. `main` rulesets/required checks must be configured after CI exists.
4. Phase 9I historical migration is not implemented.
5. Payment lifecycle/webhook/status/refund behavior is not broadly certified.
6. WooCommerce/WordPress/PHP/HPOS/Blocks/WPML/feature/browser/performance matrices remain incomplete.
7. Release packaging/update/rollback/WordPress.org work remains.
8. GitHub security/settings such as private vulnerability reporting and secret scanning/push protection need explicit verification/configuration.

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
3. Treat recorded SHAs as historical until live GitHub verification confirms them.
4. Verify `main`, PRs, branches, source, checks/updater and relevant official docs before implementation.
5. Prefer direct GitHub operations where tools permit; delegate only genuinely inaccessible actions.
6. Never approve/merge from an Agent report alone.
7. Pin review/merge decisions to exact base/head SHAs.
8. After merge, verify merged state, `main`, critical files/checks and branch cleanup before DONE.
9. Update `PROJECT-STATUS.md` after every independently verified merge.

## Next sequence

1. Complete Phase 0 repository governance.
2. Phase 0 release identity/updater ownership and versioning.
3. Configure branch/ruleset/security settings once checks exist.
4. Phase 9I-A read-only migration classifier.
5. Phase 9I-B/C executor and bounded operations.
6. Provider/payment lifecycle and threat-model audit.
7. Standard quality platform and incremental architecture extraction.
8. Compatibility/performance/UX certification.
9. Release engineering and eventual WordPress.org preparation.

## Copy-ready opening prompt

```text
Continue the SimplixPay for UPayments engineering program in SimplixInnovations/simplixpay-upayments.

Read AGENTS.md first, then docs/project/PROJECT-STATUS.md, docs/project/NAMING-IDENTITY-STANDARD.md, docs/project/NEW-CHAT-HANDOFF.md and relevant sections of docs/project/MASTER-ENGINEERING-PLAYBOOK.md.

Treat documented SHAs/status as historical until you independently verify live GitHub main, branches, PRs, current source and checks. Reconcile any drift before work.

Do repository work directly wherever GitHub tools permit. Delegate only genuinely inaccessible actions. Preserve protected legacy upayments/upay identifiers unless an approved tested migration changes them. Never approve or merge without independent verification pinned to exact SHAs.

Continue from the first unfinished permitted gate in PROJECT-STATUS.md.
```
