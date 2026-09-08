# SUPCheckout for UPayments — Project Control Documents

This directory is the engineering control plane for **SUPCheckout for UPayments**.

The governing rule is simple:

> **Living documents own current truth; historical documents preserve milestone truth.**

Do not rewrite historical evidence merely to make old milestones look as though they originally occurred under current branding.

## Required read order

For a new engineering or release session:

1. [`../../AGENTS.md`](../../AGENTS.md) — repository-wide engineering, compatibility and merge rules
2. [`PROJECT-STATUS.md`](PROJECT-STATUS.md) — current verified state
3. [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md) — fresh-clone, local-acceptance and release sequence
4. [`NAMING-IDENTITY-STANDARD.md`](NAMING-IDENTITY-STANDARD.md) — canonical SUPCheckout identity and protected compatibility IDs
5. [`../COMPATIBILITY.md`](../COMPATIBILITY.md) — public compatibility/certification boundary
6. [`NEW-CHAT-HANDOFF.md`](NEW-CHAT-HANDOFF.md) — compact continuation context
7. [`RELEASE-ENGINEERING.md`](RELEASE-ENGINEERING.md) — deterministic artifact and migration/release contract
8. [`ENTERPRISE-CERTIFICATION.md`](ENTERPRISE-CERTIFICATION.md) — retained certification evidence
9. [`MASTER-ENGINEERING-PLAYBOOK.md`](MASTER-ENGINEERING-PLAYBOOK.md) — broader permanent engineering discipline
10. [`ARCHITECTURE-CODE-QUALITY.md`](ARCHITECTURE-CODE-QUALITY.md) — architecture and code-quality controls
11. [`QUALITY-PLATFORM.md`](QUALITY-PLATFORM.md) — permanent closed Q1-Q19 record
12. [`BASELINE-H12.md`](BASELINE-H12.md) — historical token/saved-card/subscription evidence anchors when needed

## Living current-state documents

Reconcile these when verified project truth changes:

- `PROJECT-STATUS.md`
- `OWNER-HANDOFF.md`
- `NAMING-IDENTITY-STANDARD.md`
- `NEW-CHAT-HANDOFF.md`
- `../../README.md`
- `../COMPATIBILITY.md`
- `../../AGENTS.md`
- `RELEASE-ENGINEERING.md` when the artifact/migration/release contract changes

These files should be concise enough to operate from. Deep milestone detail belongs in retained certification/history records rather than being duplicated into every living document.

## Historical / closure evidence

These may intentionally contain former product names, old repository coordinates, old SHAs and then-current gate language:

- `REPOSITORY-READINESS.md`
- `REPOSITORY-AUDIT.md`
- Phase 0 / Phase 9I records
- Provider/Security closure evidence
- Quality Platform Q1-Q19 closure ledger
- historical Enterprise Task records
- `../history/**`
- approved `docs/superpowers/**` plans/specs that describe what was decided or executed at the time

Do not bulk-rebrand these records. Preserve milestone facts.

## Authority by question

| Question | Authority |
|---|---|
| Where are we now? | `PROJECT-STATUS.md` |
| What should the owner do next? | `OWNER-HANDOFF.md` |
| What is the canonical name/slug/namespace/bootstrap? | `NAMING-IDENTITY-STANDARD.md` |
| What can be publicly claimed as compatible? | `docs/COMPATIBILITY.md` |
| How is the ZIP built/verified/migrated/released? | `RELEASE-ENGINEERING.md` |
| What evidence closed the enterprise program? | `ENTERPRISE-CERTIFICATION.md` |
| What are permanent coding/merge rules? | `AGENTS.md` + `MASTER-ENGINEERING-PLAYBOOK.md` |
| What was true at an older milestone? | the relevant historical phase/quality record |

## Current identity summary

```text
Human product:       SUPCheckout for UPayments
Short product:       SUPCheckout
Technical slug:      supcheckout
Text domain:         supcheckout
Namespace:           Simplixi\SUPCheckout
Package root:        supcheckout/
First-stable file:   UPayments.php
Canonical basename:  supcheckout/UPayments.php
Canonical GitHub:    SimplixInnovations/supcheckout
Development version: 0.1.0
```

`for` is display/relationship wording only and must never be encoded into technical identifiers.

Existing provider/payment/persisted compatibility identities are not renamed merely for naming uniformity.

## Precedence

1. Freshly verified live GitHub/source/provider evidence.
2. `PROJECT-STATUS.md` for current engineering state.
3. `OWNER-HANDOFF.md` for owner/local/release actions.
4. `NAMING-IDENTITY-STANDARD.md` for identity.
5. `docs/COMPATIBILITY.md` for public claims.
6. `RELEASE-ENGINEERING.md` for artifact/migration/release mechanics.
7. `AGENTS.md` / `MASTER-ENGINEERING-PLAYBOOK.md` for permanent engineering discipline.
8. Historical records for milestone evidence only.

When documents appear inconsistent, first determine whether one is a historical snapshot. Never “fix” historical truth by rewriting it into current branding.
