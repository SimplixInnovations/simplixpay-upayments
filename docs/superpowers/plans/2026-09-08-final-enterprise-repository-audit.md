# SUPCheckout Final Enterprise Repository Audit Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Produce the cleanest enterprise-grade SUPCheckout repository state before the owner creates a completely fresh local clone, with no known stale public wording, unreferenced repository assets, misleading status claims, unsafe runtime residue, or contradictory living documentation.

**Architecture:** Treat runtime/payment compatibility as frozen unless a concrete defect is proven. Audit the entire tracked tree and current GitHub control plane, then make only evidence-backed cleanup/presentation/documentation changes. Historical engineering records remain historical; living public/current-authority files are simplified and reconciled to current truth.

**Tech Stack:** PHP 7.4+, WordPress, WooCommerce, JavaScript, CSS, GitHub Actions, Markdown, PHPUnit, PHPStan, PHPCS/WPCS, WordPress Plugin Check.

**Spec:** User-directed final enterprise audit plus `AGENTS.md`, `docs/project/NAMING-IDENTITY-STANDARD.md`, `docs/project/ARCHITECTURE-CODE-QUALITY.md`, `docs/project/RELEASE-ENGINEERING.md`, and `docs/COMPATIBILITY.md`.

## Global Constraints

- Formal product remains exactly **SUPCheckout for UPayments**.
- Canonical technical slug/text domain remains exactly `supcheckout`.
- Canonical repository remains exactly `SimplixInnovations/supcheckout`.
- PHP namespace root remains exactly `Simplixi\SUPCheckout`.
- First-stable physical bootstrap remains exactly `supcheckout/UPayments.php`.
- Development version remains `0.1.0` unless separately approved.
- Provider scope remains UPayments-only.
- Preserve gateway/payment ID `upayments`, `woocommerce_upayments_settings`, `wc_upayments`, `_upay_*`, `UPayments_order_id`, H12 token/provenance keys, subscription/billing-attempt identities, historical order payment-method values, frozen migration identities, `getAPIUrlForRetreiveCards()`, and `whitelabled` compatibility shape.
- Do not rewrite historical evidence merely to remove old product names or SHAs.
- No tag, GitHub Release, WordPress.org publication, or version promotion is authorized by this plan.
- Public-facing repository copy should be concise, premium, factual, and free of internal-program jargon when that detail belongs in project-control docs.
- Current certification claims must be tied to real evidence; public README must not become a changelog or internal handoff.

---

### Task 1: Re-inventory and residue/security audit

**Files:**
- Review: every tracked repository path and GitHub control-plane state.
- Modify only if a concrete defect is proven.

**Interfaces:**
- Consumes: exact `main` SHA `2f05e72dc96c9bd1dae977e7316023e1a8ea15d9` and current branch/ruleset/CI evidence.
- Produces: classified findings: runtime blocker, repository-hygiene issue, documentation issue, intentional historical/compatibility exception, or no action.

- [ ] **Step 1:** Verify branch topology, open PRs/issues, tags/releases, default branch and Main Rule.
- [ ] **Step 2:** Enumerate the complete recursive Git tree and classify runtime, assets, tests, docs, CI, packaging and historical records.
- [ ] **Step 3:** Search the full default branch for debug residue, TODO/FIXME markers, unsafe request surfaces, direct secret/log leakage patterns, dangerous dynamic execution, stale first-party identities, stale public status language and dead asset references.
- [ ] **Step 4:** Review all production/runtime file boundaries and large-file hotspots against existing architecture harnesses and current certification evidence; do not refactor large payment files solely for cosmetics before first release.
- [ ] **Step 5:** Record only evidence-backed changes in later tasks; zero speculative runtime refactors.

### Task 2: Rebuild the public README and repository presentation

**Files:**
- Modify: `README.md`
- Modify if needed: `.github/assets/*` only when an existing tracked asset is demonstrably wrong; do not invent a new logo in this task.

**Interfaces:**
- Consumes: current verified compatibility/support boundaries.
- Produces: concise public landing page that separates product overview from engineering evidence.

- [ ] **Step 1:** Remove migration-era warning/note blocks from the hero and replace them with a short, neutral relationship/trademark note near the legal/provenance section.
- [ ] **Step 2:** Replace the stale PR #71/current-main narrative with a compact current status summary and links to authoritative evidence docs.
- [ ] **Step 3:** Add a disciplined badge set for Quality, Compatibility, Release Artifact, Provider Sandbox, WordPress.org Submission Check, CodeQL, license, development status and supported platform floor/range without implying publication or endorsement.
- [ ] **Step 4:** Reorder the README for product-first comprehension: value proposition, capabilities, support boundaries, compatibility, install/development, security, evidence/docs, provenance/license.
- [ ] **Step 5:** Keep exact historical SHAs/run numbers out of the main landing flow unless needed to substantiate a current claim; deep evidence remains in project docs.

### Task 3: Reconcile WordPress.org copy and root repository policies

**Files:**
- Modify: `readme.txt`
- Review/modify as needed: `CONTRIBUTING.md`, `SECURITY.md`, `SUPPORT.md`, `MAINTAINERS.md`, `NOTICE.md`, `UPSTREAM.md`
- Review: `.github/ISSUE_TEMPLATE/*`, `.github/PULL_REQUEST_TEMPLATE.md`, `.github/CODEOWNERS`, `.github/dependabot.yml`

**Interfaces:**
- Consumes: public product copy and legal/provenance boundaries from Task 2.
- Produces: consistent user/contributor/security language with no duplicated defensive disclaimers or conflicting support claims.

- [ ] **Step 1:** Simplify repeated independence/endorsement prose while retaining the legally useful trademark/provenance boundary in `NOTICE.md` and concise references elsewhere.
- [ ] **Step 2:** Ensure `readme.txt` is WordPress.org-ready, user-focused, and consistent with actual certified/unsupported boundaries.
- [ ] **Step 3:** Verify issue/PR templates request sanitized, reproducible evidence and do not use stale product terminology.
- [ ] **Step 4:** Verify support/security channels and contribution model are clear, current and non-duplicative.

### Task 4: Reconcile living project-control documentation

**Files:**
- Modify: `docs/project/PROJECT-STATUS.md`
- Modify: `docs/project/OWNER-HANDOFF.md`
- Modify: `docs/project/NEW-CHAT-HANDOFF.md`
- Modify: `docs/project/README.md` if navigation/precedence needs improvement.
- Modify: `docs/COMPATIBILITY.md` only if current-position language is stale.
- Modify: `AGENTS.md` only if current engineering-state wording is stale.
- Modify: `CHANGELOG.md` with the final audit milestone.
- Preserve historical phase/quality/spec/plan records unless they make a false current claim.

**Interfaces:**
- Consumes: user-confirmed deletion of the last stale branch and current `main`-only topology.
- Produces: one coherent current-state story with no obsolete owner cleanup step and no self-invalidating docs SHA claims.

- [ ] **Step 1:** Mark branch cleanup complete and remove the now-obsolete stale-branch owner action.
- [ ] **Step 2:** Distinguish the latest runtime-bearing certified baseline from later documentation/presentation-only descendants without embedding a self-referential final docs SHA.
- [ ] **Step 3:** Ensure owner handoff assumes a fresh clone path, because the owner plans to create the local project from scratch.
- [ ] **Step 4:** Keep exact local acceptance commands canonical and aligned with `supcheckout-*` harness filenames.
- [ ] **Step 5:** Preserve historical milestones as evidence, not current branding guidance.

### Task 5: Remove proven repository-only dead assets and packaging residue

**Files:**
- Delete only if unreferenced after complete search: `assets/screenshots/*`
- Modify: `.distignore` if the deleted directory makes its rule obsolete.
- Review all tracked `assets/css/*`, `assets/js/*`, `assets/images/*`, `.github/assets/*`, templates and package exclusions for live references.

**Interfaces:**
- Consumes: complete reference audit from Task 1.
- Produces: smaller, clearer source repository without breaking runtime or WordPress.org packaging.

- [ ] **Step 1:** Prove whether each screenshot/source-only asset is referenced by README, readme.txt, docs, code, packaging or automated tests.
- [ ] **Step 2:** Delete only assets with zero live/reference/evidence role; retain dynamically resolved wallet/payment images when runtime constructs their paths.
- [ ] **Step 3:** Remove obsolete `.distignore` entries created solely for deleted source material.
- [ ] **Step 4:** Re-run release-artifact and residue contracts to ensure the installable package stays correct.

### Task 6: Exact-head review, certification and merge

**Files:**
- Review the complete PR diff; no opportunistic runtime changes.

**Interfaces:**
- Consumes: Tasks 1–5.
- Produces: one fully certified final enterprise-audit head on `main`.

- [ ] **Step 1:** Open a draft PR from `hardening/final-enterprise-repository-audit`.
- [ ] **Step 2:** Inspect every changed file and verify no protected provider/persisted identity changed.
- [ ] **Step 3:** Require exact-head Quality/H12, 16-cell Compatibility, Release Artifact + migration cells, Provider Sandbox when triggered, strict WordPress.org Plugin Check and CodeQL/security success.
- [ ] **Step 4:** Resolve any valid review finding; do not waive a blocker.
- [ ] **Step 5:** Mark ready and squash-merge only the exact certified head with expected-head guard.
- [ ] **Step 6:** Re-run and verify post-merge `main` checks, main-only topology, zero open PRs/issues/tags/releases, ruleset integrity and deterministic final package fingerprint.
- [ ] **Step 7:** Provide the owner with a clean-from-scratch clone/bootstrap procedure and a bounded local-agent verification brief for the fresh local project.
