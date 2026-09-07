# SUCheckout for UPayments — Project Control Documents

This directory is the permanent engineering control plane for **SUCheckout for UPayments**.

The main rule is simple: **living documents own current truth; historical documents preserve milestone truth.** Do not rewrite historical evidence merely to make old milestones look like they originally occurred under SUCheckout.

## Required read order

For a new engineering/release session:

1. [`../../AGENTS.md`](../../AGENTS.md) — repository-wide execution, compatibility and merge rules
2. [`PROJECT-STATUS.md`](PROJECT-STATUS.md) — canonical current verified state
3. [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md) — exact owner/admin/local/release sequence
4. [`NAMING-IDENTITY-STANDARD.md`](NAMING-IDENTITY-STANDARD.md) — frozen SUCheckout identity and protected compatibility IDs
5. [`../../docs/COMPATIBILITY.md`](../COMPATIBILITY.md) — public compatibility/evidence truth
6. [`NEW-CHAT-HANDOFF.md`](NEW-CHAT-HANDOFF.md) — compact continuation context
7. [`RELEASE-ENGINEERING.md`](RELEASE-ENGINEERING.md) — deterministic artifact and migration/release contract
8. [`ENTERPRISE-CERTIFICATION.md`](ENTERPRISE-CERTIFICATION.md) — retained enterprise + SUCheckout certification evidence
9. [`MASTER-ENGINEERING-PLAYBOOK.md`](MASTER-ENGINEERING-PLAYBOOK.md) — broader permanent engineering rules
10. [`ARCHITECTURE-CODE-QUALITY.md`](ARCHITECTURE-CODE-QUALITY.md) — architecture discovery/A1-A5 and later ratchets
11. [`QUALITY-PLATFORM.md`](QUALITY-PLATFORM.md) — permanent closed Q1-Q19 record
12. [`BASELINE-H12.md`](BASELINE-H12.md) — historical token/saved-card/subscription evidence anchors
13. [`REPOSITORY-READINESS.md`](REPOSITORY-READINESS.md) and [`REPOSITORY-AUDIT.md`](REPOSITORY-AUDIT.md) — historical repository-foundation snapshots

## Document classes

### Living current-state documents

These must be reconciled when verified project truth changes:

- `PROJECT-STATUS.md`
- `OWNER-HANDOFF.md`
- `NAMING-IDENTITY-STANDARD.md`
- `NEW-CHAT-HANDOFF.md`
- `../../README.md`
- `../COMPATIBILITY.md`
- `../../AGENTS.md`
- `RELEASE-ENGINEERING.md` when the artifact/release contract changes

### Historical/closure evidence

These may intentionally contain former product names, old repository coordinates, historical SHAs and then-current gate language:

- `REPOSITORY-READINESS.md`
- `REPOSITORY-AUDIT.md`
- Phase 0 / Phase 9I records
- Provider/Security historical closure evidence
- Quality Platform Q1-Q19 closure ledger
- historical Task 1-8 closeout evidence
- `docs/history/**`
- approved Superpowers plans/specs that describe what was decided/executed at the time

Do not bulk-rebrand these records. Add a current-state banner/cross-reference when needed; preserve milestone facts.

## Authority by question

- **Where are we now?** → `PROJECT-STATUS.md`
- **What exactly must the owner do next?** → `OWNER-HANDOFF.md`
- **What is the canonical name/slug/namespace/bootstrap?** → `NAMING-IDENTITY-STANDARD.md`
- **What can we publicly claim as compatible?** → `docs/COMPATIBILITY.md`
- **How is the ZIP built/verified/migrated/released?** → `RELEASE-ENGINEERING.md`
- **What evidence closed the enterprise program?** → `ENTERPRISE-CERTIFICATION.md`
- **What are the permanent coding/merge rules?** → `AGENTS.md` + `MASTER-ENGINEERING-PLAYBOOK.md`
- **What did an older gate believe at that time?** → the relevant historical phase/quality record

## Current identity summary

```text
Human product:      SUCheckout for UPayments
Short product:      SUCheckout
Technical slug:     sucheckout-upayments
Text domain:        sucheckout-upayments
Namespace:          Simplixi\SUCheckout\UPayments
Package root:       sucheckout-upayments/
First-stable file:  UPayments.php
Canonical basename: sucheckout-upayments/UPayments.php
Target repository:  SimplixInnovations/sucheckout-upayments
```

`for` is display/relationship wording only. Never create `sucheckout-for-upayments` technical identifiers.

Existing persisted/provider payment identities are never renamed merely for naming uniformity.

## Precedence

1. Freshly verified live GitHub/source/provider evidence.
2. `PROJECT-STATUS.md` for current program state.
3. `OWNER-HANDOFF.md` for owner/admin/local/release actions.
4. `NAMING-IDENTITY-STANDARD.md` for naming/identity.
5. `docs/COMPATIBILITY.md` for public compatibility claims.
6. `RELEASE-ENGINEERING.md` for artifact/migration/release mechanics.
7. `AGENTS.md` / `MASTER-ENGINEERING-PLAYBOOK.md` for permanent engineering discipline.
8. Historical records for milestone evidence only.

When two documents appear inconsistent, first determine whether one is a historical snapshot. Never “fix” historical truth by rewriting it into current branding.
