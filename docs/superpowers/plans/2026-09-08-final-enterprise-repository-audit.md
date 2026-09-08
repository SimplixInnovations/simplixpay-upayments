# SUPCheckout Final Enterprise Repository Audit Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Produce the cleanest evidence-backed, enterprise-grade SUPCheckout repository state before the owner creates a completely fresh local clone, while preserving all protected UPayments/payment/persisted compatibility contracts.

**Architecture:** Audit the exact recursive Git tree and live GitHub control plane, classify every tracked path, and change only evidence-proven defects, dead repository material, stale living documentation or misleading public presentation. Runtime/payment architecture stays frozen unless a concrete defect is proven; historical evidence stays historical.

**Tech Stack:** PHP 7.4+, WordPress, WooCommerce, JavaScript, CSS, GitHub Actions, Markdown, PHPUnit, PHPStan, PHPCS/WPCS, Composer, WordPress Plugin Check, CodeQL.

**Spec:** `docs/superpowers/specs/2026-09-08-final-enterprise-repository-audit-design.md`

## Global Constraints

- Human product name: **SUPCheckout for UPayments**.
- Short product name: **SUPCheckout**.
- Maintainer: **Simplix Innovations**.
- Provider scope: **UPayments only**.
- Repository: `SimplixInnovations/supcheckout`.
- Slug / text domain: `supcheckout`.
- PHP namespace root: `Simplixi\SUPCheckout`.
- New first-party global prefix: `supcheckout_`.
- Constants: `SUPCHECKOUT_*`.
- Package root: `supcheckout/`.
- First-stable physical bootstrap: `supcheckout/UPayments.php`.
- Development version: `0.1.0` unless separately approved.
- Preserve gateway/payment ID `upayments`, `woocommerce_upayments_settings`, `wc_upayments`, existing `_upay_*` metadata, `UPayments_order_id`, H12 token/provenance/scope/generation identities, `upay_process_subscriptions`, billing-attempt state, historical order payment-method values, frozen Phase 9I migration identities, both historical package roots, `getAPIUrlForRetreiveCards()` and the normalized `whitelabled` compatibility shape.
- Historical docs preserve historical product names, SHAs and repository coordinates when those were true.
- No tag, GitHub Release, WordPress.org publication or version promotion is authorized.
- Approach 3 architectural modernization is explicitly deferred until this audit and fresh-clone owner acceptance are complete.

---

### Task 1: Exact repository inventory and control-plane audit

**Files:**
- Review: every tracked path at the exact PR head.
- Review: repository metadata, branches, open PRs/issues, tags/releases, Main Rule/ruleset.
- Modify: only the audit plan/spec if evidence recording itself needs correction.

**Interfaces:**
- Consumes: PR #77 head and `main` baseline.
- Produces: a complete classification map and a bounded list of evidence-backed findings used by Tasks 2–5.

- [ ] **Step 1:** Fetch the exact recursive Git tree for the current PR head and record total blob/tree counts.
- [ ] **Step 2:** Classify every tracked blob into runtime PHP, templates, JS/CSS, runtime media, repository presentation assets, scripts/build tooling, workflows/governance, tests/fixtures/harnesses, public docs, WordPress.org distribution copy, living project-control docs, historical evidence/specs/plans, and configuration/tooling.
- [ ] **Step 3:** Verify live branch topology, default branch, open PRs/issues, tags/releases, repository metadata/topics and the active Main Rule.
- [ ] **Step 4:** Verify the Main Rule still requires squash-only linear history, deletion/non-fast-forward protection, review-thread resolution, no bypass actors and strict `Governance`, `H12 Regression Harness`, `Compatibility Gate`, `Release Gate`.
- [ ] **Step 5:** Produce a finding ledger with only these outcomes: retain as-is; correct; remove with evidence; retain as historical/protected compatibility evidence.
- [ ] **Step 6:** Do not proceed to runtime/public changes until each proposed deletion or rename has a concrete reference/persistence analysis.

### Task 2: Runtime, symbol, asset and package integrity audit

**Files:**
- Review: `UPayments.php`, `src/**`, `includes/**`, `templates/**`, `assets/js/**`, `assets/css/**`, `assets/images/**`, `.github/assets/**`, release/package scripts and `.distignore`.
- Modify/delete: only paths proven defective or unreferenced.
- Test: existing residue, architecture, H12, release-artifact and package-verification harnesses.

**Interfaces:**
- Consumes: Task 1 classification/finding ledger.
- Produces: a runtime/package tree with zero known debug residue or proven-dead shipped/repository-only material.

- [ ] **Step 1:** Scan every runtime text blob for `TODO`, `FIXME`, `HACK`, `console.log`, `var_dump`, `print_r`, debug-only `error_log`, `debugger`, unsafe dynamic execution patterns and stale first-party names.
- [ ] **Step 2:** Enumerate PHP functions/classes/methods and verify apparently low-reference symbols against WordPress hooks, callbacks, dynamic dispatch, templates and compatibility wrappers before calling them dead.
- [ ] **Step 3:** Verify request/callback/public-status code retains expected capability/nonce/authentication/provider-verification boundaries and does not log credentials/card/token/provenance secrets.
- [ ] **Step 4:** Verify every JS/CSS file has a live enqueue/registration/reference path or an explicit repository-only role.
- [ ] **Step 5:** Verify every runtime image has a direct or dynamic reference. Preserve wallet images used through `assets/images/<payment-method>.png` construction even if literal filename search has no hit.
- [ ] **Step 6:** Verify every repository screenshot/presentation asset has a README/readme/docs/code/test/evidence role; delete only zero-role assets.
- [ ] **Step 7:** Verify `.distignore`, deterministic build scripts and package verifier exclude development/repository-only material while preserving every runtime dependency.
- [ ] **Step 8:** If a runtime defect is discovered, add or tighten an executable regression assertion first, demonstrate RED on the defect, implement the smallest fix, then demonstrate GREEN.
- [ ] **Step 9:** If no runtime defect is proven, make no runtime refactor merely for file size or aesthetics.

### Task 3: Public README, badge system and repository presentation

**Files:**
- Modify: `README.md`.
- Review/modify if necessary: `.github/assets/simplix-innovations-logo-black.svg`, `.github/assets/simplix-innovations-logo-white.svg`, `.github/assets/simplix-innovations-mark.svg`.

**Interfaces:**
- Consumes: Task 1 current evidence and Task 2 runtime/package truth.
- Produces: a concise product-first repository landing page with honest, useful status badges.

- [ ] **Step 1:** Remove migration-era IMPORTANT/NOTE admonitions and historical run/PR narrative from the hero/main reading flow.
- [ ] **Step 2:** Structure the README as hero → status badges → navigation → overview/capability boundaries → compatibility → release status → technical identity → development/build → security/payment integrity → engineering docs → support → provenance → license.
- [ ] **Step 3:** Verify each workflow badge URL and click target corresponds to a real workflow on `main`.
- [ ] **Step 4:** Keep primary engineering badges limited to Quality, Compatibility, Release Artifact, Provider Sandbox, Plugin Check and CodeQL/security only when a stable truthful target exists.
- [ ] **Step 5:** Keep metadata badges limited to license, development version and certified WordPress/WooCommerce/PHP scope; do not imply public release or broad untested support.
- [ ] **Step 6:** Use coherent Simplix Innovations first-party/status colors while allowing recognizable ecosystem colors where useful.
- [ ] **Step 7:** Verify light/dark logo rendering, alt text, relative paths and GitHub Markdown/HTML compatibility.
- [ ] **Step 8:** Keep provider/trademark wording concise near provenance rather than duplicated defensively through the page.

### Task 4: WordPress.org copy and root policy documents

**Files:**
- Modify: `readme.txt`.
- Review/modify as needed: `CONTRIBUTING.md`, `SECURITY.md`, `SUPPORT.md`, `MAINTAINERS.md`, `NOTICE.md`, `UPSTREAM.md`.
- Review: `.github/CODEOWNERS`, `.github/ISSUE_TEMPLATE/**`, `.github/PULL_REQUEST_TEMPLATE.md`, `.github/dependabot.yml`.

**Interfaces:**
- Consumes: Task 3 public terminology and Task 2 feature/security boundary.
- Produces: consistent merchant, contributor, support, security and provenance policy.

- [ ] **Step 1:** Make `readme.txt` merchant/user focused: concise description, capabilities, external service, installation, FAQ, privacy/support and development-line changelog.
- [ ] **Step 2:** Preserve accurate external-service/data-sharing disclosure and remove redundant “not official / no endorsement” repetition.
- [ ] **Step 3:** Verify Classic/Blocks/HPOS claims, refunds/multi-split boundaries, subscription auto-deduction boundary and WPML/WCML/multicurrency/RTL non-certification match evidence docs.
- [ ] **Step 4:** Verify issue/PR templates explicitly request sanitized reproducible evidence and never solicit credentials, tokens, private webhook payloads or unnecessary personal data.
- [ ] **Step 5:** Verify `SECURITY.md` provides a private security reporting path and `SUPPORT.md` distinguishes repository support from commercial support.
- [ ] **Step 6:** Verify `NOTICE.md` / `UPSTREAM.md` own detailed provenance/trademark/source-lineage explanation so README/readme copy can remain concise.
- [ ] **Step 7:** Verify CODEOWNERS, Dependabot and PR/contribution policy are internally consistent and current.

### Task 5: Living documentation reconciliation and historical-boundary audit

**Files:**
- Modify as needed: `AGENTS.md`, `CHANGELOG.md`, `docs/COMPATIBILITY.md`, `docs/project/PROJECT-STATUS.md`, `docs/project/OWNER-HANDOFF.md`, `docs/project/NEW-CHAT-HANDOFF.md`, `docs/project/README.md`.
- Review: `docs/project/NAMING-IDENTITY-STANDARD.md`, `docs/project/RELEASE-ENGINEERING.md`, `docs/project/ENTERPRISE-CERTIFICATION.md`, `docs/project/ARCHITECTURE-CODE-QUALITY.md`, `docs/project/MASTER-ENGINEERING-PLAYBOOK.md`, `docs/project/QUALITY-PLATFORM.md`, `docs/project/BASELINE-H12.md`, `docs/history/**`, retained Superpowers specs/plans.
- Preserve historical evidence unless it falsely presents itself as current authority.

**Interfaces:**
- Consumes: Tasks 1–4 final truth.
- Produces: one coherent current-state story and one explicit fresh-clone owner sequence.

- [ ] **Step 1:** Remove obsolete owner stale-branch cleanup instructions now that the owner deleted the old branch.
- [ ] **Step 2:** State that PR #77's branch is temporary audit work rather than a persistent topology defect.
- [ ] **Step 3:** Distinguish latest runtime-bearing certified baseline from later presentation/docs-only descendants without creating a self-referential final-doc SHA requirement.
- [ ] **Step 4:** Ensure `OWNER-HANDOFF.md` starts from a completely fresh clone and uses canonical `supcheckout-*` harness paths.
- [ ] **Step 5:** Run a contradiction scan for stale current-baseline SHAs, false `main-only` claims during an open audit PR, old harness filenames, obsolete branch cleanup steps and public-release wording.
- [ ] **Step 6:** Review historical documents only to classify them correctly; do not bulk-rebrand milestone evidence.
- [ ] **Step 7:** Add the final enterprise-audit milestone to `CHANGELOG.md` without pretending `0.1.0` is already publicly released.

### Task 6: Exact-head whole-branch review and certification

**Files:**
- Review: every PR #77 changed file and every deleted path.
- Modify: only to resolve valid findings.

**Interfaces:**
- Consumes: Tasks 1–5.
- Produces: one exact PR head eligible for merge.

- [ ] **Step 1:** Fetch the complete PR diff and changed-file list; review every changed/deleted path.
- [ ] **Step 2:** Compare protected payment/provider/persisted identity occurrences between `main` and the candidate head; investigate every count or semantic difference.
- [ ] **Step 3:** Verify deleted screenshots/assets have zero remaining live/evidence references and no package dependency.
- [ ] **Step 4:** Run/inspect exact-head Quality/H12, full 16-cell Compatibility, deterministic Release Artifact, packaged legacy/HPOS, both historical package-root migration families, Provider Sandbox when applicable, strict WordPress Plugin Check and CodeQL/security.
- [ ] **Step 5:** Require zero Critical/Important review findings and zero unresolved valid review threads.
- [ ] **Step 6:** Update the PR body to document exact evidence and non-changes.
- [ ] **Step 7:** Mark PR ready only after exact-head verification is fully green.

### Task 7: Merge, post-merge verification and fresh-clone handoff

**Files:**
- No speculative changes after certification.
- Documentation-only correction PR is permitted only if post-merge current-state evidence makes one necessary.

**Interfaces:**
- Consumes: certified PR #77 head.
- Produces: final `main`, final package fingerprint and owner/local-agent acceptance brief.

- [ ] **Step 1:** Squash-merge PR #77 with an expected-head SHA guard.
- [ ] **Step 2:** Verify the merged `main` commit signature and tree.
- [ ] **Step 3:** Wait for fresh post-merge main checks; require all triggered checks to succeed with zero pending/failures.
- [ ] **Step 4:** Verify persistent branch topology is `main` only after automatic audit-branch deletion.
- [ ] **Step 5:** Verify zero open PRs/issues unless a newly discovered genuine blocker explicitly requires one; verify zero tags/releases/publication.
- [ ] **Step 6:** Re-read the active Main Rule and verify its strict four required checks and protections remain intact.
- [ ] **Step 7:** Capture the final deterministic `supcheckout-0.1.0.zip` SHA-256 and file count from the exact final main build.
- [ ] **Step 8:** Provide a clean-from-scratch clone/bootstrap procedure and a bounded local-agent brief covering Composer quality, permanent harnesses, deterministic build/checksum, disposable WordPress install, Classic/Blocks/HPOS, sandbox/manual merchant acceptance and the exact report format to return.
- [ ] **Step 9:** Do not begin Approach 3 until the owner returns successful fresh-clone/local acceptance evidence.
