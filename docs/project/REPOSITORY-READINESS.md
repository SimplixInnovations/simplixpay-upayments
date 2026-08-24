# Pre-Phase-0 Repository Readiness

**Purpose:** close repository, governance, presentation and local-history issues before any runtime-changing SimplixPay release-identity work begins.

**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

**Audited base:** `c6e8c32044da254654e7a928e80900d943843e7a`

**Audit date:** 2026-08-24

**Runtime-change scope:** **NONE**

> Live GitHub state always wins over recorded SHAs. Re-verify before acting.

## Readiness definition

Phase 0 — SimplixPay release identity and updater ownership may begin only when every **required** item below is either **DONE / VERIFIED** or explicitly recorded as an accepted external/manual dependency with an owner and exact action.

## A. Canonical history and source integrity

| Gate | State | Evidence / action |
|---|---|---|
| Standalone repository, not GitHub fork | **DONE / VERIFIED** | Canonical repo is standalone. Historical fork is retained separately. |
| Clean canonical root | **DONE / VERIFIED** | Parentless product root `1caf38410354322c1d842c28a40b0909ba31026d`. |
| Reachable default-branch history is clean | **DONE / VERIFIED** | At audit time `main` has three reachable commits only: clean root + two squash governance commits. |
| Historical engineering provenance retained | **DONE / VERIFIED** | `SimplixInnovations/upayments-woocommerce` remains the audit archive. |
| H12 production anchors preserved through governance | **DONE / VERIFIED** | Five frozen blob SHAs match `BASELINE-H12.md`. |
| Old engineering changelog retained without presenting it as product releases | **IN THIS READINESS CHANGE** | Archive old blob under `docs/history/H12-ENGINEERING-CHANGELOG.md`; root changelog becomes product-oriented. |
| Whole tracked tree classified | **IN THIS READINESS CHANGE** | See `REPOSITORY-AUDIT.md` for keep/fix/defer decisions. |

## B. Naming and public identity

| Gate | State | Evidence / action |
|---|---|---|
| Formal name | **FROZEN** | `SimplixPay for UPayments`. |
| Short integration reference | **FROZEN** | `SimplixPay UPayments`; `SimplixPay` alone remains reserved for the future broader product family. |
| Canonical technical slug | **FROZEN** | `simplixpay-upayments`. |
| Compatibility-sensitive historical IDs protected | **DONE / POLICY** | Naming standard and `AGENTS.md` prohibit blind `upayments` / `_upay_*` renames. |
| README/product presentation | **IN THIS READINESS CHANGE** | Restore Woo Agency Partner proof, expand truthful badges, separate public positioning from engineering ledger. |
| GitHub About metadata/topics/homepage | **MANUAL SETTINGS REQUIRED** | Exact target configuration below. |

## C. Governance and documentation

| Gate | State |
|---|---|
| Root `AGENTS.md` | **DONE** |
| Project status ledger | **DONE; refreshed in this readiness change** |
| Repository audit ledger | **IN THIS READINESS CHANGE** |
| Naming/identity standard | **DONE / FROZEN** |
| Master Engineering Playbook | **DONE** |
| New-chat handoff | **DONE; refreshed in this readiness change** |
| H12 baseline record | **DONE** |
| CODEOWNERS | **DONE** |
| Security/support/contribution/maintainer/upstream/provenance policies | **DONE; consistency-reviewed** |
| Product changelog separated from engineering archive | **IN THIS READINESS CHANGE** |

## D. CI and dependency hygiene

| Gate | State | Evidence / action |
|---|---|---|
| Pull-request/main quality workflow | **DONE** | `Quality Gates`. |
| H12 PHP baseline | **DONE / BASELINE** | 1927 PASS / 0 FAIL on last verified governance run. |
| H12 Blocks baseline | **DONE / BASELINE** | 144 PASS / 0 FAIL on last verified governance run. |
| Third-party Actions immutable pins | **IN THIS READINESS CHANGE** | Pin checkout, setup-php and setup-node to full release SHAs. |
| Current checkout release | **IN THIS READINESS CHANGE** | `actions/checkout` v7.0.1. |
| Current setup-node release | **IN THIS READINESS CHANGE** | `actions/setup-node` v7.0.0, Node 24 runtime. |
| Dependabot PR noise | **IN THIS READINESS CHANGE** | Group GitHub Actions updates weekly and keep owner review. |
| Full PHPUnit/WP/Woo/browser/static-analysis platform | **NOT YET A PRE-PHASE-0 BLOCKER** | Planned later; current green CI must not be marketed as broad certification. |

## E. Repository settings — required manual configuration

The connected GitHub tool can read these settings but does not expose the required write operations. Configure them in GitHub before Phase 0.

### General → Features

- **Issues:** ON
- **Projects:** OFF unless actively used
- **Wiki:** OFF
- **Discussions:** OFF for now

### General → Pull Requests

Recommended canonical merge policy:

- **Allow squash merging:** ON
- **Allow merge commits:** OFF
- **Allow rebase merging:** OFF
- **Automatically delete head branches:** ON
- **Always suggest updating pull request branches:** ON if available
- **Allow auto-merge:** optional; keep OFF until required checks/rules are stable

This creates concise, linear canonical history while preserving PR discussion/evidence.

### Rules → Rulesets (target `main`)

Create an active branch ruleset for `main`:

- restrict deletion;
- block force pushes;
- require a pull request before merging;
- do not require an approval count that would prevent the sole maintainer from merging their own reviewed work;
- require conversation resolution before merge;
- require status checks to pass;
- required checks: **Governance** and **H12 Regression Harness**;
- require branch to be up to date before merge after the checks stabilize;
- prefer linear history;
- include administrators in normal enforcement, retaining only a deliberate emergency bypass if the organization needs one.

### Security → Code security and analysis

Enable/verify where GitHub makes them available for this public repository:

- Dependency graph;
- Dependabot alerts;
- Dependabot security updates;
- secret scanning;
- push protection for secrets;
- private vulnerability reporting.

Do not enable a code-scanning claim/badge until an appropriate PHP-capable/static-analysis program is actually configured and reviewed.

### About

Target description:

> SimplixPay for UPayments — independently engineered WooCommerce payment integration by Simplix Innovations.

Target website/homepage:

`https://simplixi.com`

Recommended topics:

`woocommerce`, `wordpress`, `payment-gateway`, `payments`, `upayments`, `simplixpay`, `php`, `ecommerce`, `woocommerce-payment-gateway`, `checkout-blocks`, `hpos`, `wpml`

## F. Contributor attribution

### What Git actually contains

At the 2026-08-24 audit, the canonical default branch contains only three reachable commits. The former upstream/fork commits are not reachable from canonical `main`.

### Why GitHub may still display five contributors

GitHub documents that contributor statistics may remain stale for about 24 hours after force-pushing or rewriting history. The count shown in the UI is therefore not proof that the old commits still exist in canonical history.

There is also one real attribution detail to close: the parentless root commit was authored as `Simplix Innovations <info@simplixi.com>`, while GitHub's commit API still does not map that root to the `SimplixInnovationsAdmin` user. GitHub requires the exact commit email to be associated with the user account.

Required account action:

1. GitHub → personal **Settings → Emails** for `SimplixInnovationsAdmin`.
2. Add `info@simplixi.com` if it is not already present.
3. Complete GitHub's verification email flow.
4. Keep the email attached to that account.
5. Allow contributor statistics time to rebuild after the history rewrite/email association.
6. If the contributor graph is still wrong after GitHub's documented refresh window, contact GitHub Support rather than rewriting canonical history again.

The account's public/profile email may remain different; commit attribution depends on the authored email being associated with the account.

## G. Local IDE clone reconciliation

A clone created before the canonical history rewrite can show a misleading large divergence such as **1 incoming / 131 outgoing** even when there are no local file changes. That is commit-graph divergence, not 131 unpublished source edits.

After the readiness work is merged, reconcile the existing clone from the repository directory in PowerShell:

```powershell
git status --short
git fetch origin --prune
git branch local-before-canonical-reset-20260824
git reset --hard origin/main
git status -sb
git rev-list --left-right --count HEAD...origin/main
```

Expected final divergence:

```text
0    0
```

Do not run the hard reset if `git status --short` shows local work you need to keep. The backup branch is deliberately created first as an additional safety net.

## H. Branch/PR cleanup

Before Phase 0 starts, the repository should contain only:

- `main`;
- the one currently active, purpose-specific implementation branch, if any.

Close superseded Dependabot PRs after their audited updates are incorporated. Delete merged governance/readiness branches. The current connector cannot delete Git refs, so branch deletion is a manual GitHub/CLI action unless automatic deletion is enabled in repository settings.

## I. Pre-Phase-0 exit gate

**Required before Phase 0 starts:**

- readiness PR merged and post-merge verified;
- H12 CI green on final readiness head and resulting `main`;
- no runtime source changes in readiness diff;
- GitHub About/feature/merge/rules/security settings configured as above;
- old/superseded branches removed;
- Dependabot #2/#3 superseded/closed after audited CI update;
- local IDE clone reconciled to canonical `main`;
- `info@simplixi.com` associated/verified on `SimplixInnovationsAdmin` and contributor graph allowed to refresh;
- `PROJECT-STATUS.md` updated to **PRE-PHASE-0 READY / VERIFIED**.

Only then proceed to **Phase 0 — SimplixPay release identity and updater ownership**.
