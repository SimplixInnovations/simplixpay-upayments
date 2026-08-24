# Pre-Phase-0 Repository Readiness

**Purpose:** close repository, governance, presentation, settings, attribution, and local-history issues before any runtime-changing SimplixPay release-identity work begins.

**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

**Last live audit:** 2026-08-25

**Runtime-change scope:** **NONE**

> Live GitHub state always wins over recorded SHAs. Re-verify before acting.

## Readiness definition

Phase 0 — SimplixPay release identity and updater ownership may begin only when every required item below is **DONE / VERIFIED**.

## A. Canonical history and source integrity

| Gate | State | Evidence |
|---|---|---|
| Standalone repository, not GitHub fork | **DONE / VERIFIED** | Live repo reports `fork: false`. |
| Clean canonical root | **DONE / VERIFIED** | Parentless product root `1caf38410354322c1d842c28a40b0909ba31026d`. |
| Reachable default-branch history is clean | **DONE / VERIFIED** | Five canonical commits before this final-certification change: root + four reviewed squash merges. |
| Historical engineering provenance retained | **DONE / VERIFIED** | Historical audit repo retained separately. |
| H12 production anchors preserved | **DONE / VERIFIED** | Five frozen blob SHAs re-fetched on `main` and match exactly. |
| Historical H12 engineering changelog preserved | **DONE / VERIFIED** | `docs/history/H12-ENGINEERING-CHANGELOG.md` retains blob `8c42bc6fdae163dd4159b8036b05cd2f70cc3d5d`. |
| Product changelog separated | **DONE / VERIFIED** | Root `CHANGELOG.md` is product-oriented. |
| Whole tracked tree classified | **DONE / VERIFIED** | `REPOSITORY-AUDIT.md`. |
| Remote branch cleanup | **DONE / VERIFIED** | Live remote branch inventory is `main` only. |
| Open PR cleanup | **DONE / VERIFIED** | Live open PR count is 0 before this final-certification PR. |

## B. Naming and public identity

| Gate | State | Evidence |
|---|---|---|
| Formal name | **FROZEN** | `SimplixPay for UPayments`. |
| Short integration reference | **FROZEN** | `SimplixPay UPayments`; `SimplixPay` alone reserved for broader product family. |
| Canonical technical slug | **FROZEN** | `simplixpay-upayments`. |
| Compatibility-sensitive historical IDs protected | **DONE / POLICY** | Naming standard + `AGENTS.md`. |
| README/product presentation | **DONE IN FINAL CERTIFICATION** | Simplix-led presentation, Woo Agency Partner proof, truthful maturity/provider badges. |
| GitHub description/homepage | **DONE / VERIFIED** | Live repo metadata matches SimplixPay/Simplix target. |
| GitHub license recognition | **DONE / VERIFIED** | Live repo reports SPDX MIT. |
| About topics do not overstate certification | **EXTERNAL ACTION REQUIRED** | Remove `hpos-compatible` and `wpml-ready`; neutral `hpos` / `wpml` may remain. |

## C. Governance and documentation

| Gate | State |
|---|---|
| Root `AGENTS.md` | **DONE** |
| Project status ledger | **DONE; refreshed in final certification** |
| Repository readiness ledger | **DONE; refreshed in final certification** |
| Repository audit ledger | **DONE** |
| Naming/identity standard | **DONE / FROZEN** |
| Master Engineering Playbook | **DONE** |
| New-chat handoff | **DONE; refreshed in final certification** |
| H12 baseline record | **DONE** |
| CODEOWNERS | **DONE** |
| Security/support/contribution/maintainer/upstream/provenance policies | **DONE** |
| Product changelog separated from engineering archive | **DONE / VERIFIED** |

## D. CI and dependency hygiene

| Gate | State | Evidence |
|---|---|---|
| Pull-request/main quality workflow | **DONE** | `Quality Gates`. |
| H12 PHP baseline | **DONE / VERIFIED** | Exact reviewed PR heads: 1927 PASS / 0 FAIL. |
| H12 Blocks baseline | **DONE / VERIFIED** | Exact reviewed PR heads: 144 PASS / 0 FAIL. |
| Third-party Actions immutable pins | **DONE / VERIFIED** | checkout/setup-php/setup-node pinned to full SHAs. |
| Current audited checkout/setup-node majors | **DONE** | checkout v7.0.1; setup-node v7.0.0; Node 24. |
| Complete tracked PHP syntax gate | **DONE** | CI uses `git ls-files` for all tracked PHP. |
| Dependabot PR noise | **DONE** | grouped weekly Actions updates; old individual major PRs closed as superseded. |
| Full PHPUnit/WP/Woo/browser/static-analysis platform | **NOT A PRE-PHASE-0 BLOCKER** | Planned later; H12 green is not marketed as broad certification. |

For documentation/control-only squash merges, exact green PR-head Quality Gates plus independently verified merged tree/runtime anchors are accepted evidence. A separate push-triggered `main` run is useful but is not required when the merged tree is proven equivalent to the reviewed green head and runtime anchors are unchanged.

## E. Repository settings — live verified vs private-detail check

### Verified through repository API

- Issues: ON
- Projects: OFF
- Wiki: OFF
- Discussions: OFF
- homepage: `https://simplixi.com`
- correct SimplixPay description
- squash merging: ON
- merge commits: OFF
- rebase merging: OFF
- auto-delete source branches: ON and independently proven
- auto-merge: OFF
- default branch: `main`
- `main`: `protected: true`
- repository license: MIT

### Still requires manual/private GitHub verification

The connected API does not expose the active ruleset body or security-analysis/private-vulnerability settings. Confirm:

- `main` deletion restricted;
- force pushes blocked;
- pull request required before merging;
- conversation resolution required;
- required checks include **Governance** and **H12 Regression Harness**;
- branch-up-to-date behavior is intentional;
- linear history enforced/preferred;
- administrator/bypass behavior is intentional;
- dependency graph enabled;
- Dependabot alerts enabled;
- Dependabot security updates enabled;
- secret scanning enabled;
- push protection enabled;
- private vulnerability reporting enabled.

Do not claim these details from `protected: true` alone.

## F. Contributor attribution

### Current Git truth

Canonical `main` contains only the clean standalone product history. Former fork/upstream commits are not reachable from the canonical default branch.

### Remaining attribution evidence

The parentless root commit is authored as `Simplix Innovations <info@simplixi.com>`, but the live commit API still returns `author: null` for that root. Required account state:

1. GitHub personal **Settings → Emails** for `SimplixInnovationsAdmin`.
2. `info@simplixi.com` present and verified.
3. Keep it associated with the account.
4. Allow contributor statistics time to rebuild.

Do not rewrite canonical history again merely to force the contributor UI.

## G. Local IDE clone reconciliation

This cannot be verified remotely. Final local evidence must show:

- no uncommitted work that should be preserved;
- current branch tracking `origin/main` as intended;
- `HEAD...origin/main` divergence `0 0` after final readiness merge/fetch;
- only expected remote branch `origin/main` after pruning.

A pre-rewrite clone can otherwise show misleading historical divergence.

## H. Tags and releases

The new canonical repository must start the Simplix version line cleanly.

Before Phase 0 begins, externally verify:

- no inherited Git tags remain in the canonical repo;
- no unintended GitHub Releases exist;
- the historical audit repo may retain historical tags/releases as provenance.

Do not create a Simplix release/tag until Phase 0 establishes the version/update strategy.

## I. Branch/PR cleanup

**DONE / VERIFIED** before this final-certification branch:

- live remote branches: `main` only;
- open pull requests: 0;
- old Dependabot PRs closed/unmerged/superseded;
- merged source branches removed;
- automatic branch deletion independently proven on PR #6.

The transient final-certification branch must auto-delete after merge and be independently checked.

## J. Pre-Phase-0 exit gate

Required before Phase 0 starts:

- [x] standalone canonical repository and clean root verified;
- [x] H12 source/runtime anchors preserved;
- [x] historical audit provenance preserved separately;
- [x] permanent governance/control docs established;
- [x] public README/support/provenance/license presentation reviewed;
- [x] whole tracked tree classified;
- [x] immutable baseline CI/dependency hygiene established;
- [x] remote branch/PR cleanup completed;
- [x] About description/homepage/feature/merge policy verified through live repository metadata;
- [x] `main` reports protected;
- [ ] remove overclaiming About topics `hpos-compatible` and `wpml-ready`;
- [ ] detailed ruleset/security settings manually verified;
- [ ] local IDE clone reconciled to final `origin/main` with divergence `0 0`;
- [ ] `info@simplixi.com` associated/verified and GitHub attribution allowed to refresh;
- [ ] canonical repo confirmed to have no inherited tags/unintended releases;
- [ ] final-certification PR merged from exact green head, `main` re-verified, transient branch auto-deleted;
- [ ] `PROJECT-STATUS.md` changed to **PRE-PHASE-0 READY / VERIFIED** only after every item above passes.

Only then proceed to **Phase 0 — SimplixPay release identity and updater ownership**.
