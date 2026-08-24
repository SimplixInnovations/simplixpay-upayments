# Project Control Documents

This directory is the permanent engineering control plane for **SimplixPay for UPayments**.

## Read order

For a new engineering session:

1. [`../../AGENTS.md`](../../AGENTS.md) — mandatory execution/review rules.
2. [`PROJECT-STATUS.md`](PROJECT-STATUS.md) — current verified state and next permitted action.
3. [`REPOSITORY-READINESS.md`](REPOSITORY-READINESS.md) — required while the pre-Phase-0 readiness gate is open.
4. [`REPOSITORY-AUDIT.md`](REPOSITORY-AUDIT.md) — whole-tree inventory, debt classification and explicit defer/keep decisions.
5. [`NAMING-IDENTITY-STANDARD.md`](NAMING-IDENTITY-STANDARD.md) — frozen product/slug/namespace and protected compatibility identities.
6. [`NEW-CHAT-HANDOFF.md`](NEW-CHAT-HANDOFF.md) — compact continuation context.
7. [`MASTER-ENGINEERING-PLAYBOOK.md`](MASTER-ENGINEERING-PLAYBOOK.md) — full program, quality standards and release gates.
8. [`BASELINE-H12.md`](BASELINE-H12.md) — canonical-root and H12 provenance/evidence anchors.

## Document roles

- **PROJECT-STATUS** answers: *where are we now and what may happen next?*
- **REPOSITORY-READINESS** answers: *what repository/settings/local-state work must close before Phase 0 runtime changes?*
- **REPOSITORY-AUDIT** answers: *what exists in the tracked repository, what is safe to keep now, and which runtime/package debts belong to later gates?*
- **NAMING-IDENTITY-STANDARD** answers: *what names/IDs are canonical and which historical IDs must remain compatible?*
- **MASTER-ENGINEERING-PLAYBOOK** answers: *what is the complete engineering and release program?*
- **NEW-CHAT-HANDOFF** answers: *what minimum context should a fresh session recover immediately?*
- **BASELINE-H12** answers: *what historical payment/token baseline was independently verified?*

## Precedence

1. Freshly verified live GitHub/source/provider evidence beats stale recorded state.
2. `PROJECT-STATUS.md` controls the current program gate.
3. `REPOSITORY-READINESS.md` blocks runtime Phase 0 while marked open.
4. `REPOSITORY-AUDIT.md` records inventory/debt classification but does not authorize runtime cleanup by itself.
5. `NAMING-IDENTITY-STANDARD.md` controls naming/identity decisions.
6. `MASTER-ENGINEERING-PLAYBOOK.md` controls the broader program unless a later explicitly approved status/ADR updates a gate.
7. Historical records are evidence; do not rewrite them merely to make the project look further along.

Existing persisted payment identities are never renamed merely for naming uniformity.
