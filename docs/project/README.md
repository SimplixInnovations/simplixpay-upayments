# Project Control Documents

This directory is the permanent engineering control plane for **SimplixPay for UPayments**.

Read in this order for a new session:

1. [`../../AGENTS.md`](../../AGENTS.md) — mandatory execution rules.
2. [`PROJECT-STATUS.md`](PROJECT-STATUS.md) — current verified state and next permitted action.
3. [`NAMING-IDENTITY-STANDARD.md`](NAMING-IDENTITY-STANDARD.md) — frozen product/slug/namespace and compatibility rules.
4. [`NEW-CHAT-HANDOFF.md`](NEW-CHAT-HANDOFF.md) — compact continuation context.
5. [`MASTER-ENGINEERING-PLAYBOOK.md`](MASTER-ENGINEERING-PLAYBOOK.md) — full roadmap/standards/gates.
6. [`BASELINE-H12.md`](BASELINE-H12.md) — canonical-root and H12 provenance.

## Precedence

- Live GitHub state beats stale recorded SHAs.
- `PROJECT-STATUS.md` is the living current-state ledger.
- `NAMING-IDENTITY-STANDARD.md` controls naming/identity decisions.
- `MASTER-ENGINEERING-PLAYBOOK.md` controls program/gates unless a later approved ADR/status entry explicitly supersedes it.
- Existing persisted payment identities are not renamed merely for naming uniformity.

Every substantive session must independently verify the live repository before acting.
