# Pre-Phase-0 Repository Readiness

**Purpose:** close repository, governance, presentation, settings and local-history issues before any runtime-changing SimplixPay release-identity work begins.

**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

**Last live audit:** 2026-08-25

**Runtime-change scope:** **NONE**

> Live GitHub state always wins over recorded SHAs. Re-verify before acting.

## Readiness definition

Phase 0 — SimplixPay release identity and updater ownership may begin only when every required item below is **DONE / VERIFIED** or is explicitly recorded as an external dependency with an owner and exact action.

## A. Canonical history and source integrity

| Gate | State | Evidence |
|---|---|---|
| Standalone repository, not GitHub fork | **DONE / VERIFIED** | Live repo reports `fork: false`. |
| Clean canonical root | **DONE / VERIFIED** | Parentless product root `1caf38410354322c1d842c28a40b0909ba31026d`. |
| Reachable default-branch history is clean | **DONE / VERIFIED** | Four reachable commits on verified readiness `main`: root + PR #1 + PR #4 + PR #5 squash commits. |
| Historical engineering provenance retained | **DONE / VERIFIED** | Historical audit repo retained separately. |
| H12 production anchors preserved | **DONE / VERIFIED** | Five frozen blob SHAs re-fetched on `main` and match exactly. |
| Historical H12 engineering changelog preserved | **DONE / VERIFIED** | `docs/history/H12-ENGINEERING-CHANGELOG.md` retains blob `8c42bc6fdae163dd4159b8036b05cd2f70cc3d5d`. |
| Product changelog separated | **DONE / VERIFIED** | Root `CHANGELOG.md` is product-oriented. |
| Whole tracked tree classified | **DONE / VERIFIED** | `REPOSITORY-AUDIT.md`. |
| PR #5 merged tree equals approved head tree | **DONE / VERIFIED** | Both are `b11efcf0d0acf008d2088c67b9975226a72d7e5d`. |

## B. Naming and public identity

| Gate | State | Evidence |
|---|---|---|
| Formal name | **FROZEN** | `SimplixPay for UPayments`. |
| Short integration reference | **FROZEN** | `SimplixPay UPayments`; `SimplixPay` alone reserved for broader product family. |
| Canonical technical slug | **FROZEN** | `simplixpay-upayments`. |
| Compatibility-sensitive historical IDs protected | **DONE / POLICY** | Naming standard + `AGENTS.md`. |
| README/product presentation | **DONE / VERIFIED** | SimplixPay positioning, Woo Agency Partner proof, truthful badges/status. |
| GitHub description/homepage/topics | **DONE / VERIFIED** | Live repo metadata matches SimplixPay/Simplix target and has relevant topics. |
| GitHub license recognition | **DONE / VERIFIED** | Live repo reports MIT. |

## C. Governance and documentation

| Gate | State |
|---|---|
| Root `AGENTS.md` | **DONE** |
| Project status ledger | **DONE; live-state sync in current control-plane change** |
| Repository audit ledger | **DONE** |
| Naming/identity standard | **DONE / FROZEN** |
| Master Engineering Playbook | **DONE** |
| New-chat handoff | **DONE; live-state sync in current control-plane change** |
| H12 baseline record | **DONE** |
| CODEOWNERS | **DONE** |
| Security/support/contribution/maintainer/upstream/provenance policies | **DONE** |
| Product changelog separated from engineering archive | **DONE / VERIFIED** |

## D. CI and dependency hygiene

| Gate | State | Evidence |
|---|---|---|
| Pull-request/main quality workflow | **DONE** | `Quality Gates`. |
| H12 PHP baseline | **DONE / VERIFIED** | PR #5 final approved head: 1927 PASS / 0 FAIL. |
| H12 Blocks baseline | **DONE / VERIFIED** | PR #5 final approved head: 144 PASS / 0 FAIL. |
| Third-party Actions immutable pins | **DONE / VERIFIED** | checkout/setup-php/setup-node pinned to full SHAs. |
| checkout current audited release | **DONE** | v7.0.1 pin. |
| setup-node current audited release | **DONE** | v7.0.0 pin, Node 24. |
| Complete tracked PHP syntax gate | **DONE** | CI uses `git ls-files` for all tracked PHP. |
| Dependabot PR noise | **DONE** | grouped weekly Actions updates; PR #2/#3 closed as superseded. |
| Full PHPUnit/WP/Woo/browser/static-analysis platform | **NOT A PRE-PHASE-0 BLOCKER** | Planned later; H12 green is not marketed as broad certification. |

The merged `main` tree is byte-identical to the PR #5 head that passed the above Quality Gates. The current connector can enumerate PR-triggered workflow runs but not list the resulting push-triggered main run. If the final exit reviewer requires explicit main-run evidence, verify it in GitHub Actions UI before marking READY.

## E. Repository settings — live verified vs manual detail check

### Verified through repository API

- Issues: ON
- Projects: OFF
- Wiki: OFF
- Discussions: OFF
- homepage: `https://simplixi.com`
- correct SimplixPay description
- relevant topics populated
- squash merging: ON
- merge commits: OFF
- rebase merging: OFF
- auto-delete source branches: ON
- auto-merge: OFF
- default branch: `main`
- `main`: `protected: true`

### Still requires manual GitHub Settings verification

The connected API does not expose the active ruleset body or security-analysis/private-vulnerability settings. Verify:

- `main` deletion restricted;
- force pushes blocked;
- pull request required before merging;
- conversation resolution required;
- required checks include **Governance** and **H12 Regression Harness**;
- branch-up-to-date behavior is intentional;
- linear history enforced/preferred;
- administrators subject to normal enforcement except deliberate emergency bypass;
- dependency graph enabled;
- Dependabot alerts enabled;
- Dependabot security updates enabled;
- secret scanning enabled;
- push protection enabled;
- private vulnerability reporting enabled.

Do not claim these detailed settings from `protected: true` alone.

## F. Contributor attribution

### Current Git truth

Canonical `main` contains only the four clean reachable commits recorded in `PROJECT-STATUS.md`. Former fork/upstream commits are not reachable from this standalone product history.

### Current attribution issue

The parentless root commit is authored as `Simplix Innovations <info@simplixi.com>`, but the live commit API currently returns `author: null`. Required account action:

1. GitHub personal **Settings → Emails** for `SimplixInnovationsAdmin`.
2. Add `info@simplixi.com` if absent.
3. Complete GitHub verification.
4. Keep the address associated with the account.
5. Allow GitHub contributor statistics time to rebuild.

GitHub documents that contributor displays/statistics can take about 24 hours after force-push/history rewrite changes to refresh. The canonical root was created on 2026-08-24, so do not rewrite history again merely to force the contributor UI.

If the contributor graph is still wrong after the documented refresh period and the commit email is verified, contact GitHub Support.

## G. Local IDE clone reconciliation

A clone created before the canonical history rewrite can show misleading divergence such as 1 incoming / 131 outgoing even with no file changes. This cannot be verified remotely.

From the repository directory in PowerShell:

```powershell
git status --short
git fetch origin --prune
$backup = "backup-before-canonical-reset-" + (Get-Date -Format "yyyyMMdd-HHmmss")
git branch $backup
git reset --hard origin/main
git status -sb
git rev-list --left-right --count HEAD...origin/main
```

Expected final divergence:

```text
0    0
```

Do not run the hard reset if `git status --short` shows local work you need to keep.

## H. Branch/PR cleanup

### Pull requests

**DONE / VERIFIED** at live audit:

- open PR count: 0;
- PR #2 Dependabot: closed / unmerged / superseded;
- PR #3 Dependabot: closed / unmerged / superseded;
- PR #1, #4 and #5: merged.

### Remote branches still requiring deletion

Before the transient status-sync branch, live branches were:

- `main`
- `phase-0/repository-governance`
- `phase-0/governance-finalize`
- `pre-phase0/repository-readiness`

The three stale branches are **SAFE TO DELETE / VERIFIED** because each final branch tree exactly equals its corresponding squash-merge tree:

| Branch | PR | Head | Head tree | Squash merge | Merge tree |
|---|---:|---|---|---|---|
| `phase-0/repository-governance` | #1 | `7a81489a16cd0c264f26784d547542dcc2417e19` | `aa387ff76c300a12933c25932dece75e8def534e` | `cc565779c541178f63ae21f8e712f9708035361e` | same |
| `phase-0/governance-finalize` | #4 | `5878a165e0352b64c323efb354e2fa5e58348131` | `bb1bdc29d51a73edcdb1c4da7ca4ba99cede9b80` | `c6e8c32044da254654e7a928e80900d943843e7a` | same |
| `pre-phase0/repository-readiness` | #5 | `792a0a9f2d995a9c9a1b80f7718e50be2d4396c0` | `b11efcf0d0acf008d2088c67b9975226a72d7e5d` | `7c86bbc29dd6d311004c0305533d5d731327f05e` | same |

The connected GitHub tool exposes create/update refs but **not delete refs**. Delete externally with an authorized clone:

```powershell
git push origin --delete phase-0/repository-governance phase-0/governance-finalize pre-phase0/repository-readiness
git fetch origin --prune
git branch -r
```

Expected remote result after this status-sync branch auto-deletes: only `origin/main`.

## I. Pre-Phase-0 exit gate

**Required before Phase 0 starts:**

- [x] readiness PR merged and source/tree independently verified;
- [x] H12 CI green on exact final readiness head;
- [x] merged `main` tree equals exact green readiness head tree;
- [x] no runtime source changes in readiness diff;
- [x] About/features/merge policy verified through live repository metadata;
- [x] `main` reports protected;
- [x] Dependabot #2/#3 superseded and closed;
- [ ] detailed ruleset/security settings manually verified;
- [ ] three obsolete remote branches deleted;
- [ ] local IDE clone reconciled to `origin/main` with divergence `0 0`;
- [ ] `info@simplixi.com` associated/verified and contributor statistics allowed to refresh;
- [ ] explicit main push Quality Gates checked in Actions UI if required for final exit evidence;
- [ ] `PROJECT-STATUS.md` changed to **PRE-PHASE-0 READY / VERIFIED** only after every item above passes.

Only then proceed to **Phase 0 — SimplixPay release identity and updater ownership**.