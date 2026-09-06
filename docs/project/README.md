# Project Control Documents

This directory is the permanent engineering control plane for **SUCheckout for UPayments**.

## Read order

For a new engineering session:

1. [`../../AGENTS.md`](../../AGENTS.md) — mandatory execution/review rules.
2. [`PROJECT-STATUS.md`](PROJECT-STATUS.md) — canonical current verified state and next permitted action.
3. [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md) — repository rename, branch cleanup, local acceptance and release-administration checklist.
4. [`NAMING-IDENTITY-STANDARD.md`](NAMING-IDENTITY-STANDARD.md) — frozen product/slug/namespace and protected compatibility identities.
5. [`NEW-CHAT-HANDOFF.md`](NEW-CHAT-HANDOFF.md) — compact continuation context.
6. [`RELEASE-ENGINEERING.md`](RELEASE-ENGINEERING.md) — deterministic package, migration and publication evidence boundary.
7. [`ENTERPRISE-CERTIFICATION.md`](ENTERPRISE-CERTIFICATION.md) — historical enterprise foundation plus SUCheckout re-certification evidence.
8. [`MASTER-ENGINEERING-PLAYBOOK.md`](MASTER-ENGINEERING-PLAYBOOK.md) — broader historical program and permanent quality/release rules.
9. [`ARCHITECTURE-CODE-QUALITY.md`](ARCHITECTURE-CODE-QUALITY.md) — verified architecture discovery/A1-A5 and later ratchet record.
10. [`QUALITY-PLATFORM.md`](QUALITY-PLATFORM.md) — permanent closed Q1-Q19 quality record.
11. [`BASELINE-H12.md`](BASELINE-H12.md) — canonical-root and H12 provenance/evidence anchors.
12. [`REPOSITORY-READINESS.md`](REPOSITORY-READINESS.md) and [`REPOSITORY-AUDIT.md`](REPOSITORY-AUDIT.md) — historical repository-foundation evidence; not current-gate owners.

## Document roles

- **PROJECT-STATUS** answers: *where are we now and what may happen next?*
- **OWNER-HANDOFF** answers: *what must the owner do for branch cleanup, repository rename, local acceptance and later publication?*
- **REPOSITORY-READINESS** answers historically: *what repository/settings/local-state work had to close before Phase 0 runtime changes?*
- **REPOSITORY-AUDIT** answers: *what exists in the tracked repository, what is safe to keep now, and which runtime/package debts belong to later gates?*
- **NAMING-IDENTITY-STANDARD** answers: *what names/IDs are canonical and which historical IDs must remain compatible?*
- **MASTER-ENGINEERING-PLAYBOOK** answers: *what is the complete engineering and release program?*
- **NEW-CHAT-HANDOFF** answers: *what minimum context should a fresh session recover immediately?*
- **ARCHITECTURE-CODE-QUALITY** answers: *what decomposition contracts and compatibility seams were verified?*
- **QUALITY-PLATFORM** records: *which Q1-Q19 development-tooling scopes and non-certification boundaries were permanently established?*
- **BASELINE-H12** answers: *what historical payment/token baseline was independently verified?*

## Precedence

1. Freshly verified live GitHub/source/provider evidence beats stale recorded state.
2. `PROJECT-STATUS.md` controls the current program state.
3. `OWNER-HANDOFF.md` controls the remaining owner/admin sequence after engineering closeout.
4. `NAMING-IDENTITY-STANDARD.md` controls naming/identity decisions.
5. `RELEASE-ENGINEERING.md` controls deterministic artifact and publication evidence boundaries.
6. `REPOSITORY-READINESS.md`, `REPOSITORY-AUDIT.md`, phase records and Q1-Q19 records are historical evidence, not current-gate owners.
7. `MASTER-ENGINEERING-PLAYBOOK.md` controls broader permanent engineering rules unless a later explicitly approved status/ADR updates them.
8. Historical records are evidence; do not rewrite their milestone facts merely to make the project look further along.

Existing persisted payment identities are never renamed merely for naming uniformity.
