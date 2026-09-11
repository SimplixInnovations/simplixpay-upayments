from pathlib import Path
import re

OLD_SHA = "5d8d954ffb8c2e6646dadc4aa1f994b5d56c462e"
NEW_SHA = "27e90d5a0cd2ba4f7c38889dbef15d1be851efb6"
OLD_HASH = "470db11afb2869bc187f0e920aeae4de02e92f6c1f7ca478ef5cb2ac62151b74"
NEW_HASH = "dc31c02a8047f9e5120650a46b9c16413383d6644b29f569573739a08f3e7a2d"
OLD_GATE = "E2 residual ecosystem economics / extension-generated contracts"
NEW_GATE = "E3 theme/cache/CDN/optimizer/analytics compatibility"
OLD_MACHINE_GATE = "e2-residual-ecosystem-economics"
NEW_MACHINE_GATE = "e3-theme-cache-analytics"

FILES = [
    "AGENTS.md",
    "docs/project/START-HERE.md",
    "docs/project/PROJECT-STATUS.md",
    "docs/project/OWNER-HANDOFF.md",
    "docs/project/NEW-CHAT-HANDOFF.md",
    ".ai-architect/implementation-plan.md",
    ".ai-architect/architecture-contract.yaml",
    "tests/unit/Governance/CurrentProjectStateRegressionTest.php",
]


def required_replace(text, old, new, path, minimum=1):
    count = text.count(old)
    if count < minimum:
        raise SystemExit(f"{path}: required text not found ({old!r})")
    return text.replace(old, new)


def replace_section(text, start_heading, end_heading, replacement, path):
    pattern = re.compile(
        rf"{re.escape(start_heading)}\n.*?(?=\n{re.escape(end_heading)}\n)",
        re.S,
    )
    updated, count = pattern.subn(replacement.rstrip() + "\n", text, count=1)
    if count != 1:
        raise SystemExit(f"{path}: expected exactly one section {start_heading!r}, got {count}")
    return updated


contents = {path: Path(path).read_text(encoding="utf-8") for path in FILES}

# Exact certified coordinate/package promotion across current-state records.
for path in FILES:
    text = contents[path]
    text = text.replace(OLD_SHA, NEW_SHA)
    text = text.replace(OLD_HASH, NEW_HASH)
    text = text.replace("generic/core E2", "repository-executable generic E2")
    text = text.replace("Generic/core E2", "Repository-executable generic E2")
    text = text.replace("E2 generic/core", "E2 repository-executable generic")
    contents[path] = text

# AGENTS: advance the operational gate and permanently retain the ecosystem lane.
path = "AGENTS.md"
text = contents[path]
text = required_replace(
    text,
    "Current post-T3 state: R0, R1 and E1 are **DONE / VERIFIED** on PR #108; repository-executable generic E2 is **CERTIFIED** at the exact checkpoint above. The current executable gate is **E2 residual ecosystem economics / extension-generated contracts**, followed by E3 theme/cache/analytics evidence, R2 callback portability/cache safety, R3 subscription safety, R4 scalability/idempotency/observability, separately gated R5/T4 architecture decision, and R6 final exact-head qualification plus owner re-acceptance.",
    "Current post-T3 state: R0, R1 and E1 are **DONE / VERIFIED** on PR #108; repository-executable generic E2 is **DONE / CERTIFIED** at the exact checkpoint above. Named paid/licensed vendor integrations remain external/unverified until separately qualified. The current executable gate is **E3 theme/cache/CDN/optimizer/analytics compatibility**, followed by R2 callback portability/cache safety, R3 subscription safety, R4 scalability/idempotency/observability, separately gated R5/T4 architecture decision, and R6 final exact-head qualification plus owner re-acceptance.",
    path,
)
text = required_replace(
    text,
    "- `.github/workflows/compatibility-certification.yml`;\n- `.github/workflows/provider-sandbox-certification.yml`;",
    "- `.github/workflows/compatibility-certification.yml`;\n- `.github/workflows/ecosystem-certification.yml`;\n- `.github/workflows/provider-sandbox-certification.yml`;",
    path,
)
contents[path] = text

# START-HERE: promote E2, move the ledger to E3, and remove the ancient R0 instruction.
path = "docs/project/START-HERE.md"
text = contents[path]
text = required_replace(
    text,
    "R0, R1 and E1 are **DONE / VERIFIED on the PR #108 branch**. Repository-executable generic E2 is **CERTIFIED** at this checkpoint. This does not close residual ecosystem-economics qualification or any later post-T3 tranche.",
    "R0, R1 and E1 are **DONE / VERIFIED on the PR #108 branch**. Repository-executable generic E2 is **DONE / CERTIFIED** at this checkpoint, including the current-runtime legacy+HPOS Ecosystem Certification lane. Named paid/licensed vendor integrations remain external/unverified until separately qualified; later post-T3 tranches remain open.",
    path,
)
text = text.replace(
    "| E2 repository-executable generic | **CERTIFIED** at `" + NEW_SHA + "` |",
    "| E2 repository-executable generic | **DONE / CERTIFIED** at `" + NEW_SHA + "` |",
)
text = required_replace(text, f"| Current operational gate | **{OLD_GATE}** |", f"| Current operational gate | **{NEW_GATE}** |", path)
text = re.sub(
    r"## 13\. Immediate next step\n\n.*\Z",
    "## 13. Immediate next step\n\nExecute **E3 theme/cache/CDN/optimizer/analytics compatibility** under the permanent Ecosystem Certification lane and existing checkout lifecycle harnesses. Keep named paid/licensed themes, optimizers and analytics products external/unverified unless their packages and required credentials are legally available. After E3 reaches an immutable exact-head checkpoint, continue to R2 callback portability/cache safety. Do not jump directly to R5/T4 callback consolidation.\n",
    text,
    count=1,
    flags=re.S,
)
if "Finish R0 control-plane reconciliation" in text:
    raise SystemExit(f"{path}: obsolete R0 immediate-step text survived")
contents[path] = text

# PROJECT-STATUS: record the new lane and replace the residual-E2 program section.
path = "docs/project/PROJECT-STATUS.md"
text = contents[path]
text = required_replace(
    text,
    "- Compatibility Certification — **20/20 runtime cells + Compatibility Gate SUCCESS**;\n- Provider Sandbox Certification — **SUCCESS**;",
    "- Compatibility Certification — **20/20 runtime cells + Compatibility Gate SUCCESS**;\n- Ecosystem Certification — **current WP/Woo legacy + HPOS runtime cells + Ecosystem Gate SUCCESS**;\n- Provider Sandbox Certification — **SUCCESS**;",
    path,
)
text = required_replace(
    text,
    "R0, R1 and E1 are **DONE / VERIFIED on the PR #108 branch**. Repository-executable generic E2 is **CERTIFIED** at this exact checkpoint.",
    "R0, R1 and E1 are **DONE / VERIFIED on the PR #108 branch**. Repository-executable generic E2 is **DONE / CERTIFIED** at this exact checkpoint. Named paid/licensed vendor integrations remain external/unverified until separately qualified.",
    path,
)
replacement = """## Remaining post-T3 program

The overall post-T3 program is **not finished**.

Repository-executable generic E2 is **DONE / CERTIFIED** at `27e90d5a0cd2ba4f7c38889dbef15d1be851efb6`. This covers the repository-owned generic economics/product/shipping/tax/composition/refund boundaries, including the current-runtime legacy+HPOS Ecosystem Certification lane. It does **not** convert unavailable paid/licensed named integrations into certified compatibility.

Current executable gate: **E3 theme/cache/CDN/optimizer/analytics compatibility**.

1. **E3 — theme/cache/analytics compatibility:** qualify available Classic/block/free theme runtimes, optimizer/cache/CDN interaction behavior, repeated fragment/defer/delay execution and analytics return/replay semantics. Paid/licensed named products remain external until legally available and actually exercised.
2. **R2 — callback portability/cache safety:** Woo API URL abstraction, `home_url` vs `site_url`, subdirectories, permalink/index/proxy behavior, explicit no-cache callback/public-status semantics and replay freshness.
3. **R3 — subscription safety:** exact auto-deduct amount/currency/parent/cycle/provider binding, removal of first-card fallback, paid-parent discovery beyond `completed`, customer control policy, token-retention contract and held-cycle reconciliation.
4. **R4 — scalability/operations:** due-work scheduling/Action Scheduler, bounded batches, durable idempotency/journal semantics, observability, load/concurrency/failure injection and queue health.
5. **R5 / T4 — callback consolidation decision:** **separately gated architecture decision**. T3 characterization is evidence, not implementation authorization.
6. **R6 — final release qualification:** exact-head full gates, browser/device/manual/external qualification, fresh owner re-acceptance, explicit version/publication decision.

"""
text = replace_section(text, "## Remaining post-T3 program", "## Permanent payment/security invariants", replacement, path)
contents[path] = text

# OWNER-HANDOFF: update the current checkpoint and make the next executable gate explicit.
path = "docs/project/OWNER-HANDOFF.md"
text = contents[path]
text = required_replace(
    text,
    "Residual E2 and later tranches remain open.",
    "Repository-executable generic E2 is DONE / CERTIFIED at this checkpoint. Named paid/licensed vendor integrations remain external/unverified until separately qualified. The current executable gate is E3 theme/cache/CDN/optimizer/analytics compatibility; later R2-R6 tranches remain open.",
    path,
)
contents[path] = text

# NEW-CHAT-HANDOFF: E3 is now the first action.
path = "docs/project/NEW-CHAT-HANDOFF.md"
text = contents[path]
text = required_replace(
    text,
    "R0, R1 and E1 are **DONE / VERIFIED on PR #108 branch**. Repository-executable generic E2 is **CERTIFIED** at this checkpoint.",
    "R0, R1 and E1 are **DONE / VERIFIED on PR #108 branch**. Repository-executable generic E2 is **DONE / CERTIFIED** at this checkpoint, including current-runtime legacy+HPOS Ecosystem Certification. Named paid/licensed vendor integrations remain external/unverified until separately qualified.",
    path,
)
replacement = """## Remaining execution order

Current executable gate: **E3 theme/cache/CDN/optimizer/analytics compatibility**.

1. Execute **E3** repository-owned theme/cache/CDN/optimizer/analytics behavior against legally available runtimes; keep unavailable paid/licensed named products explicitly external/unverified.
2. Execute **R2** callback URL portability/no-cache safety.
3. Execute **R3** subscription safety: exact cycle economics/identity, selected-card authority, no first-card fallback, parent discovery, cancellation/control and held-cycle reconciliation.
4. Execute **R4** due-work scheduling, idempotency/journal, observability, load/concurrency and failure-injection hardening.
5. Gate **R5/T4** architecture separately; do not implement merely because characterization exists.
6. Execute **R6** exact-head release qualification, fresh owner re-acceptance and version decision; publication requires separate explicit authorization.

"""
text = replace_section(text, "## Remaining execution order", "## Permanent invariants", replacement, path)
contents[path] = text

# AI architecture handoff: promote E2 and E3 machine/human gate.
path = ".ai-architect/implementation-plan.md"
text = contents[path]
text = required_replace(text, "- E2 repository-executable generic — **CERTIFIED**.", "- E2 repository-executable generic — **DONE / CERTIFIED**.", path)
text = required_replace(
    text,
    "Current executable gate: **E2 residual ecosystem economics / extension-generated contracts**. Then execute E3 theme/cache/minification/defer/consent/analytics interaction evidence; R2 callback URL portability/no-cache safety; R3 subscription selected-card/parent/cancellation/token/economic/held-cycle safety; R4 Action Scheduler/idempotency/journal/observability/load/concurrency/failure-injection hardening; separately gate R5/T4 callback consolidation; and finish with R6 exact-head qualification plus fresh owner re-acceptance.",
    "Current executable gate: **E3 theme/cache/CDN/optimizer/analytics compatibility**. Repository-executable generic E2 is closed/certified; named paid/licensed integrations remain external until actually qualified. Then execute R2 callback URL portability/no-cache safety; R3 subscription selected-card/parent/cancellation/token/economic/held-cycle safety; R4 Action Scheduler/idempotency/journal/observability/load/concurrency/failure-injection hardening; separately gate R5/T4 callback consolidation; and finish with R6 exact-head qualification plus fresh owner re-acceptance.",
    path,
)
contents[path] = text

path = ".ai-architect/architecture-contract.yaml"
text = contents[path]
text = required_replace(text, "generic_core_e2_status: certified", "generic_e2_status: done_certified", path)
text = required_replace(text, f"current_gate: {OLD_MACHINE_GATE}", f"current_gate: {NEW_MACHINE_GATE}", path)
text = required_replace(
    text,
    "goal: Close residual ecosystem economics, theme/cache/analytics, portability, subscription and scalability gaps under the post-T3 plan; do not treat T3 as authorization for T4 consolidation.",
    "goal: Qualify E3 theme/cache/CDN/optimizer/analytics behavior, then close portability, subscription and scalability gaps under the post-T3 plan; do not treat T3 as authorization for T4 consolidation.",
    path,
)
contents[path] = text

# Permanent anti-staleness regression must encode the new truth, not merely follow prose.
path = "tests/unit/Governance/CurrentProjectStateRegressionTest.php"
text = contents[path]
text = required_replace(text, "test_current_post_t3_gate_cannot_regress_before_residual_e2", "test_current_post_t3_gate_cannot_regress_before_e3", path)
text = required_replace(text, f"Current operational gate | **{OLD_GATE}**", f"Current operational gate | **{NEW_GATE}**", path)
text = required_replace(text, "E2 repository-executable generic | **CERTIFIED**", "E2 repository-executable generic | **DONE / CERTIFIED**", path)
text = required_replace(text, f"Current executable gate: **{OLD_GATE}**.", f"Current executable gate: **{NEW_GATE}**.", path)
text = required_replace(text, "Repository-executable generic E2 exact-head checkpoint", "Repository-executable generic E2 exact-head checkpoint", path)
text = required_replace(text, "generic_core_e2_status: certified", "generic_e2_status: done_certified", path)
text = required_replace(text, f"current_gate: {OLD_MACHINE_GATE}", f"current_gate: {NEW_MACHINE_GATE}", path)
needle = "        self::assertStringContainsString('R0, R1 and E1 are **DONE / VERIFIED**', $agents);\n"
if needle not in text:
    raise SystemExit(f"{path}: governance anchor missing")
text = text.replace(
    needle,
    needle + "        self::assertStringContainsString('.github/workflows/ecosystem-certification.yml', $agents);\n",
    1,
)
contents[path] = text

# Final anti-staleness and scope guards.
for path, text in contents.items():
    for stale in (OLD_SHA, OLD_HASH, OLD_GATE, OLD_MACHINE_GATE):
        if stale in text:
            raise SystemExit(f"{path}: stale current-state marker survived: {stale}")
    Path(path).write_text(text, encoding="utf-8")

required_markers = {
    "AGENTS.md": [NEW_SHA, NEW_HASH, NEW_GATE, ".github/workflows/ecosystem-certification.yml"],
    "docs/project/START-HERE.md": [NEW_SHA, NEW_HASH, NEW_GATE, "E2 repository-executable generic | **DONE / CERTIFIED**"],
    "docs/project/PROJECT-STATUS.md": [NEW_SHA, NEW_HASH, NEW_GATE, "Ecosystem Certification"],
    "docs/project/OWNER-HANDOFF.md": [NEW_SHA, NEW_HASH, "current executable gate is E3"],
    "docs/project/NEW-CHAT-HANDOFF.md": [NEW_SHA, NEW_HASH, NEW_GATE],
    ".ai-architect/implementation-plan.md": [NEW_SHA, NEW_HASH, NEW_GATE],
    ".ai-architect/architecture-contract.yaml": [NEW_SHA, NEW_HASH, NEW_MACHINE_GATE, "generic_e2_status: done_certified"],
    "tests/unit/Governance/CurrentProjectStateRegressionTest.php": [NEW_SHA, NEW_HASH, NEW_GATE, NEW_MACHINE_GATE],
}
for path, markers in required_markers.items():
    text = Path(path).read_text(encoding="utf-8")
    for marker in markers:
        if marker not in text:
            raise SystemExit(f"{path}: required reconciled marker missing: {marker}")

print("control-plane reconciliation prepared for E3")
