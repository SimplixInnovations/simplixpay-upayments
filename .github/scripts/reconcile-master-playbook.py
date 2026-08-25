from pathlib import Path

path = Path('docs/project/MASTER-ENGINEERING-PLAYBOOK.md')
text = path.read_text(encoding='utf-8')

replacements = [
    (
        '**Last independently verified implementation `main`:** `db1c4ea4dab45bc1ffaf4529e0ccb940153cd999`',
        '**Last independently verified implementation `main`:** `9569e39973a9e94926087738eae06c3846361943`',
    ),
    (
        'Before a broad production release, the project must complete full payment-lifecycle validation, security closure, compatibility certification, quality-platform expansion, operational readiness and release engineering.',
        'Provider Contract & Payment Lifecycle is now DONE / VERIFIED. Before a broad production release, the project must still complete security closure, compatibility certification, quality-platform expansion, operational readiness and release engineering.',
    ),
    (
        '| Provider contract audit | **DISCOVERY — CURRENT GATE** | Critical |',
        '| Provider contract audit | **DONE / VERIFIED** | Critical |',
    ),
    (
        '| Payment lifecycle/state machine | **DISCOVERY — CURRENT GATE** | Critical |',
        '| Payment lifecycle/state machine | **DONE / VERIFIED** | Critical |',
    ),
    (
        '| Security threat-model audit | **PARTIAL** | Critical |',
        '| Security threat-model audit | **DISCOVERY — CURRENT GATE** | Critical |',
    ),
    (
        'The current unified gate is **Provider Contract & Payment Lifecycle — DISCOVERY**. Provider contract and lifecycle rows remain separated in this roadmap because they have distinct deliverables, but discovery/characterization must be coordinated before payment-critical refactoring.',
        'Provider Contract & Payment Lifecycle is **DONE / VERIFIED**. The current unified gate is **Security Threat-Model Closure — DISCOVERY**. Provider contract and lifecycle rows remain separated because they retain distinct closed contracts and regression evidence.',
    ),
    (
        '3. Provider Contract & Payment Lifecycle — **CURRENT / DISCOVERY**.\n4. Security Threat-Model Audit.',
        '3. Provider Contract & Payment Lifecycle — **DONE / VERIFIED**.\n4. Security Threat-Model Closure — **CURRENT / DISCOVERY**.',
    ),
    (
        '- Phase 9I preflight/executor/operations harnesses;\n- existing H12 PHP harness;\n- existing Blocks harness;',
        '- Phase 9I preflight/executor/operations harnesses;\n- Provider Payment Lifecycle and Provider Exact Amount Binding harnesses;\n- existing H12 PHP harness;\n- existing Blocks harness;',
    ),
    (
        '# PROVIDER CONTRACT AUDIT\n\n**Current program status:** DISCOVERY, coordinated with Payment Lifecycle / State Machine.',
        '# PROVIDER CONTRACT AUDIT\n\n**Current program status:** DONE / VERIFIED. The closed contract/evidence is retained in `docs/project/PROVIDER-PAYMENT-LIFECYCLE.md`.',
    ),
    (
        '# PAYMENT LIFECYCLE / STATE MACHINE\n\n**Current program status:** DISCOVERY, coordinated with Provider Contract Audit.',
        '# PAYMENT LIFECYCLE / STATE MACHINE\n\n**Current program status:** DONE / VERIFIED. The ordinary-checkout lifecycle is frozen by `docs/project/PROVIDER-PAYMENT-LIFECYCLE.md` and its required regression harnesses.',
    ),
    (
        '# SECURITY THREAT-MODEL AUDIT\n\n## 30. Scope',
        '# SECURITY THREAT-MODEL AUDIT\n\n**Current program status:** DISCOVERY / CURRENT GATE.\n\n## 30. Scope',
    ),
    (
        '''LAST VERIFIED PROJECT STATE
Date: 2026-08-25
Repository: SimplixInnovations/simplixpay-upayments
Last verified implementation main SHA before Phase 9I closure reconciliation: db1c4ea4dab45bc1ffaf4529e0ccb940153cd999
Canonical implementation tree: 5bec24ad26c66a504cd0dd609f4311f9e70add76
Historical H12 merge: SimplixInnovations/upayments-woocommerce@93e9925247a8bfade626cb822136852fd96eaea2
Repository foundation/readiness: DONE / VERIFIED
Phase 0 release identity/updater ownership: DONE / VERIFIED
Phase 9I historical token-identity migration: DONE / VERIFIED
Current program gate: Provider Contract & Payment Lifecycle — DISCOVERY
Production readiness: R0 — engineering hardening
Public stable release: NO
WordPress.org release: NO
Known remaining P0/P1 program blockers:
- provider contract/payment lifecycle validation and deterministic state/reconciliation contract
- security threat-model closure
- broad compatibility/feature certification
- full automated quality platform
- release engineering/distribution''',
        '''LAST VERIFIED PROJECT STATE
Date: 2026-08-25
Repository: SimplixInnovations/simplixpay-upayments
Last verified implementation main SHA: 9569e39973a9e94926087738eae06c3846361943
Canonical implementation tree: 40ec562674361624c2764263ba55cfba84594955
Historical H12 merge: SimplixInnovations/upayments-woocommerce@93e9925247a8bfade626cb822136852fd96eaea2
Repository foundation/readiness: DONE / VERIFIED
Phase 0 release identity/updater ownership: DONE / VERIFIED
Phase 9I historical token-identity migration: DONE / VERIFIED
Provider Contract & Payment Lifecycle: DONE / VERIFIED
Current program gate: Security Threat-Model Closure — DISCOVERY
Production readiness: R0 — engineering hardening
Public stable release: NO
WordPress.org release: NO
Known remaining P0/P1 program blockers:
- security threat-model closure
- broad compatibility/feature certification
- full automated quality platform
- release engineering/distribution''',
    ),
    (
        'The exact closure-PR merge SHA cannot be written before that PR is merged. `PROJECT-STATUS.md` and live GitHub remain authoritative; the next verified state update should replace the implementation anchor above with the closure merge if needed.',
        'The provider lifecycle implementation anchor above is post-merge verified. `PROJECT-STATUS.md` and live GitHub remain authoritative; future verified gate merges must update this living block without rewriting dated historical baselines.',
    ),
    (
        '- [x] Phase 9I final implementation-head regression evidence: Phase 0 35/0, preflight 123/0, executor 59/0, operations 81/0, H12 PHP 1927/0, H12 Blocks 144/0.\n\n### Remaining P0/P1 work\n\n- [ ] Provider Contract & Payment Lifecycle — **DISCOVERY / CURRENT GATE**.\n- [ ] Full provider contract spec.\n- [ ] Deterministic payment lifecycle/webhook/status/refund state machine/reconciliation contract.\n- [ ] Security threat-model closure.',
        '- [x] Phase 9I final implementation-head regression evidence: Phase 0 35/0, preflight 123/0, executor 59/0, operations 81/0, H12 PHP 1927/0, H12 Blocks 144/0.\n- [x] Provider Contract & Payment Lifecycle completed and independently verified (PR #15).\n- [x] Provider lifecycle evidence: Provider Payment Lifecycle 141/0, Provider Exact Amount 4/0, H12 PHP 1927/0, H12 Blocks 144/0, with Governance/syntax green on the exact PR merge-ref and post-merge `main`.\n- [x] Provider lifecycle squash merge `9569e39973a9e94926087738eae06c3846361943`, tree `40ec562674361624c2764263ba55cfba84594955`, VERIFIED signature and implementation-branch cleanup.\n\n### Remaining P0/P1 work\n\n- [ ] Security Threat-Model Closure — **DISCOVERY / CURRENT GATE**.',
    ),
    (
        'Read root AGENTS.md first, then docs/project/PROJECT-STATUS.md, docs/project/NAMING-IDENTITY-STANDARD.md, docs/project/NEW-CHAT-HANDOFF.md, docs/project/PHASE-9I-MIGRATION.md and the relevant sections of docs/project/MASTER-ENGINEERING-PLAYBOOK.md.',
        'Read root AGENTS.md first, then docs/project/PROJECT-STATUS.md, docs/project/NAMING-IDENTITY-STANDARD.md, docs/project/NEW-CHAT-HANDOFF.md, docs/project/PHASE-0-RELEASE-IDENTITY.md, docs/project/PHASE-9I-MIGRATION.md, docs/project/PROVIDER-PAYMENT-LIFECYCLE.md and the relevant sections of docs/project/MASTER-ENGINEERING-PLAYBOOK.md.',
    ),
    (
        '- Closed Phase 9I evidence: repository `docs/project/PHASE-9I-MIGRATION.md`\n- Current compatibility matrix:',
        '- Closed Phase 9I evidence: repository `docs/project/PHASE-9I-MIGRATION.md`\n- Closed Provider Contract & Payment Lifecycle evidence: repository `docs/project/PROVIDER-PAYMENT-LIFECYCLE.md`\n- Current compatibility matrix:',
    ),
]

for old, new in replacements:
    count = text.count(old)
    if count != 1:
        raise SystemExit(f'Expected exactly one match, found {count}: {old[:140]!r}')
    text = text.replace(old, new, 1)

path.write_text(text, encoding='utf-8')
