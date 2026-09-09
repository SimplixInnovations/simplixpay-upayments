# SUPCheckout for UPayments — Start Here

> **Mandatory session bootstrap.** Every AI agent, developer, reviewer, release operator or owner working from a new chat, machine, clone, worktree or session must read this file first, then follow the authority chain below.
>
> **Never start substantive work from chat memory alone. Live repository evidence wins over this document whenever they differ.**

## 1. Golden project identity

| Surface | Canonical value |
|---|---|
| Product | **SUPCheckout for UPayments** |
| Short product name | **SUPCheckout** |
| Maintainer | **Simplix Innovations** |
| Provider scope | **UPayments only** |
| Repository | `SimplixInnovations/supcheckout` |
| Default branch | `main` |
| Technical slug / text domain | `supcheckout` |
| PHP namespace | `Simplixi\SUPCheckout` |
| Package root | `supcheckout/` |
| First-stable bootstrap | `supcheckout/UPayments.php` |
| Development version | `0.1.0` |
| Public tag / GitHub Release | **Not created** |
| WordPress.org publication | **Not performed** |

The word **for** is relationship copy only. It must not be encoded into repository, package, WordPress.org, namespace, REST, CSS/JS or release-artifact identifiers.

SUPCheckout is permanently **UPayments-specific**. Do not turn this repository into a generic payment router or add unrelated provider adapters.

## 2. Session bootstrap contract

Before analysis, implementation, review, release work or claims about project state:

1. fetch/prune the repository and resolve the live `main` SHA;
2. inspect remote branches and any local branches/worktrees/stashes that can affect the session;
3. inspect open PRs and issues;
4. inspect tags and GitHub Releases;
5. inspect the active Main Rule / required checks;
6. inspect exact-head CI/check state relevant to the work;
7. compare live evidence with the living documents below;
8. reconcile living documents if verified project truth has changed;
9. only then begin substantive work.

For a local clone:

```bash
git fetch --prune --tags origin
git remote -v
git branch --show-current
git branch -r
git status --short
git rev-parse HEAD
git rev-parse origin/main
git worktree list
git stash list
```

A new session must not assume that a SHA, PR state, branch count, package hash or gate result copied from an older chat is still current.

## 3. Authority chain

Read in this order after this file:

1. [`../../AGENTS.md`](../../AGENTS.md) — repository-wide engineering, compatibility, security and merge rules;
2. [`PROJECT-STATUS.md`](PROJECT-STATUS.md) — canonical living engineering state;
3. [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md) — fresh-clone, local-acceptance and release-decision procedure;
4. [`NAMING-IDENTITY-STANDARD.md`](NAMING-IDENTITY-STANDARD.md) — canonical identity and protected compatibility IDs;
5. [`../COMPATIBILITY.md`](../COMPATIBILITY.md) — public compatibility/certification boundary;
6. [`NEW-CHAT-HANDOFF.md`](NEW-CHAT-HANDOFF.md) — compact continuation context;
7. [`RELEASE-ENGINEERING.md`](RELEASE-ENGINEERING.md) — deterministic package, migration and release contract;
8. [`ENTERPRISE-CERTIFICATION.md`](ENTERPRISE-CERTIFICATION.md) — retained certification evidence;
9. relevant historical phase/quality/spec/plan records when touching the contracts they established.

Historical documents may contain former product names, old repository coordinates and old SHAs because those facts were true at the time. Do not bulk-rewrite historical evidence into current branding.

## 4. Current certified pre-acceptance baseline

Latest runtime-bearing CI-certified `main`:

`82d1fdaee91ee6bde6c26dfcc7ceb974d0d59847`

PR #97 squash-merged from exact certified head `1f2a0b8d35f96008be6ccfeb10c67fffcd3be5c0`. Post-merge `main` completed **41/41 check-runs successfully**.

Current exact evidence:

- H12 PHP — **1936 PASS / 0 FAIL**;
- H12 Blocks — **150 PASS / 0 FAIL**;
- real compatibility — **20/20 runtime cells SUCCESS** plus **Compatibility Gate SUCCESS**;
- Release Artifact — **69 PASS / 0 FAIL**;
- WordPress.org readiness — **31 PASS / 0 FAIL**, plus official packaged Plugin Check **SUCCESS**;
- bounded UPayments Provider Sandbox — **SUCCESS**;
- CodeQL — **SUCCESS**;
- canonical deterministic ZIP — **51 files**, SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`;
- canonical, Linux and Windows package evidence — **byte-identical**.

The bounded pre-acceptance hardening chain after the rejected 2026-09-08 owner run is closed:

- PR #84 — B-X1 malformed gateway-settings fail-closed fix;
- PR #91 — Blocks and Classic runtime eligibility parity;
- PR #92 — admin isolation and dead multimerchant repeater cleanup;
- PR #93 — PHP 8.2 current-stack certification, expanding the real matrix to 20 cells;
- PR #94 — installable-package cleanup of repository-only documents;
- PR #95 — last-four-only saved-card presentation;
- PR #96 — opaque saved-card browser handles so provider card tokens do not enter browser state;
- PR #97 — removal of the unnecessary numeric WordPress user ID from subscription browser localization.

The installable package contains `readme.txt` and `LICENSE`; repository-only `README.md`, `CHANGELOG.md` and `SECURITY.md` are excluded.

Owner technical acceptance remains **NOT ACCEPTED**. Accepted owner baseline: **NONE**. Automated certification does not substitute for the independent fresh-clone owner gate.

Living-document-only descendants may advance `main` after this runtime anchor without changing distributable bytes. A new session must resolve live `main` and verify that its distributable package still matches the certified package before owner acceptance.

## 5. Program sequence — mandatory current decision

The approved enterprise sequence is:

```text
Approach 2 closed
      ↓
Fresh-clone owner technical acceptance
      ↓
Accept/freeze that exact baseline
      ↓
Approach 3 architecture modernization
      ↓
Re-certify Approach 3
      ↓
Full UI/UX + branding + accessibility + broad launch testing
      ↓
Version decision
      ↓
Tag / GitHub Release / WordPress.org publication only with explicit owner approval
```

### Current gate

**Owner fresh-clone technical acceptance is the next authorized substantive step.**

Approach 3 must **not** begin until the owner acceptance baseline is independently established.

This acceptance is intentionally bounded. It does **not** require finishing the final UI/UX, branding, broad accessibility certification or exhaustive launch test matrix before Approach 3.

The pre-Approach-3 owner acceptance should establish, at minimum:

- exact fresh-clone identity and clean Git state;
- Composer validation/audit/quality;
- high-value identity, migration, security and H12 harnesses;
- deterministic package build/verifier/checksum;
- disposable/staging install and activation;
- basic Classic and Blocks sanity;
- basic UPayments sandbox success/decline/cancel behavior where credentials/environment allow;
- enough merchant-facing smoke to prove the certified baseline behaves outside GitHub CI.

The purpose is to create a defensible regression boundary: if Approach 3 later introduces a defect, the team can distinguish it from a defect that already existed in the accepted Approach 2 baseline.

## 6. Approach 3 boundary

**Status: NOT STARTED / BLOCKED BY OWNER TECHNICAL ACCEPTANCE.**

When owner acceptance passes, Approach 3 becomes the next engineering program.

Approach 3 must begin with a fresh architecture assessment of the accepted source and classify proposed modernization as:

- **Keep**
- **Improve**
- **Replace**
- **Defer**
- **Never change without an approved compatibility migration**

Do not interpret “Approach 3” as permission to refactor everything.

Protected payment/provider/persisted identities remain compatibility contracts. Architectural modernization must preserve or explicitly migrate them with upgrade, rollback/failure and regression evidence.

Approach 3 must be bounded, branch-based, reviewable and re-certified before launch-facing work is treated as final.

## 7. Deferred launch/product work

The following remain real work, but they are intentionally **after Approach 3** unless a concrete blocker requires earlier treatment:

- full plugin/admin/checkout UI/UX review and polish;
- final Simplix Innovations / SUPCheckout visual branding application;
- full accessibility review/certification;
- broad browser/device/theme coverage;
- multilingual / RTL / WPML / WCML qualification where required;
- representative performance/load testing;
- expanded manual UPayments merchant/wallet/device qualification;
- final screenshots and WordPress.org presentation assets;
- final public-version decision;
- public tag, GitHub Release and WordPress.org submission.

Do not report these deferred items as Approach 2 defects unless fresh evidence shows they block the current technical acceptance.

## 8. Active-work ledger

| Field | Current value |
|---|---|
| Program phase | **Ready for fresh-clone owner technical acceptance after living-state reconciliation** |
| Approach 2 / pre-acceptance engineering | **DONE / VERIFIED** |
| Owner technical acceptance | **NOT ACCEPTED — fresh-clone re-acceptance required** |
| Accepted owner baseline | **NONE YET** |
| Approach 3 | **BLOCKED until owner technical acceptance passes** |
| Full UI/UX / branding / broad launch testing | **DEFERRED until after Approach 3** |
| Public release authorization | **NOT GRANTED** |
| Current program gate | **Fresh-clone owner technical acceptance** |
| Next substantive action | Fresh clone + owner acceptance per `OWNER-HANDOFF.md` |
| Latest runtime-bearing CI-certified `main` | `82d1fdaee91ee6bde6c26dfcc7ceb974d0d59847` — PR #97 |
| Runtime exact-head evidence | **41/41 post-merge checks; H12 1936/0 PHP + 150/0 Blocks; compatibility 20/20; Release 69/0; WordPress.org readiness 31/0** |
| Canonical runtime package | **51 files / SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`** |
| Expected closed topology outside temporary work | **`main` only; no open PRs/issues** |
| Local owner acceptance | **NOT ACCEPTED; prior B-X1 rejection remains historical evidence, all known repository pre-acceptance blockers now fixed/certified** |
| Source of live truth | **GitHub + exact source/check/package evidence** |

Documentation-only reconciliation may advance the live `main` SHA while leaving the runtime-bearing anchor and 51-file package byte-identical.

### Operational tracking model

Use three layers of truth:

1. **Program state — this file.** Phase, gates, accepted baseline, next program action and release authorization live here.
2. **Active task state — the open GitHub PR.** Every substantive task PR must identify its base SHA, current exact head, scope/non-scope, current verification gate, blockers/failures, next action and evidence used for merge readiness. The open PR is the canonical active-work ledger for that task.
3. **Durable history — merged PRs, commits, CI and retained project evidence.** Do not duplicate an endless event stream into this file.

Therefore, when a future session asks “what is happening right now?”, it must inspect live branches/open PRs first. If an open task exists, read its PR body/comments/checks together with this program-level ledger. If none exists, the next action in this table controls.

### Ledger maintenance rule

Update this table whenever one of these changes materially:

- active program phase;
- owner acceptance state or accepted owner baseline;
- Approach 3 state;
- release authorization;
- current program gate;
- the next substantive action;
- the runtime-bearing baseline or retained full-stack evidence anchor.

Do not append a forever-growing event log here. Durable event history belongs in PRs, commits, CI, project-status evidence and retained historical records.

A temporary work branch/PR does not need to be copied here unless it changes program state. GitHub remains the authority for currently active branches and PRs.

## 9. Permanent compatibility and safety boundaries

Do not mechanically rename or refactor protected identities, including:

- gateway/payment ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks / Store API identity `upayments`;
- callback `wc_upayments`;
- historical `_upay_*` metadata;
- `UPayments_order_id` and related provider-order identities;
- token/provenance/scope/generation state;
- `upay_process_subscriptions` and billing-attempt state;
- historical order payment-method values;
- frozen Phase 9I migration identities;
- public compatibility wrapper `getAPIUrlForRetreiveCards()`;
- normalized `whitelabled` compatibility shape.

`supcheckout/UPayments.php` is an intentional first-stable compatibility exception. A physical bootstrap rename requires its own migration project.

Payment/security ambiguity fails closed. Browser redirects or callback payloads are not financial truth by themselves. Non-idempotent payment/refund/auto-deduct operations are not blindly retried.

## 10. Repository governance target

Outside temporary active work, the desired repository state is:

- default branch `main`;
- remote topology `main` only;
- no unintended open PRs/issues;
- no public tags/releases before explicit publication approval;
- squash-only merge;
- linear history;
- deletion and non-fast-forward protection;
- required review-thread resolution;
- strict required checks:
  - `Governance`;
  - `H12 Regression Harness`;
  - `Compatibility Gate`;
  - `Release Gate`;
- no bypass actors.

Any future agent must verify these live before making a closure or release claim.

## 11. Change-tracking protocol for every future task

For substantive work:

1. establish the live baseline and scope;
2. create a dedicated branch from freshly verified `main`;
3. record the intended outcome in the PR/spec/plan appropriate to the task;
4. use evidence-first development and permanent regression protection for behavior changes;
5. keep compatibility-sensitive changes explicit;
6. update living status/continuity documents when project truth changes;
7. obtain exact-head verification;
8. resolve valid review findings;
9. squash-merge with an expected-head guard where available;
10. reverify post-merge `main`;
11. confirm topology/governance;
12. update this file only when the program-level state changed.

Never create a new numbered historical phase merely because work continued in a new chat.

## 12. What a new AI agent or developer should say before proceeding

A competent continuation should be able to state, from live evidence and these authorities:

- the exact current `main` SHA;
- whether that SHA changes runtime or only living documentation;
- the latest runtime-bearing certified baseline;
- the current program phase;
- whether owner acceptance has passed;
- whether Approach 3 is authorized to begin;
- the next substantive action;
- whether any PR/branch/tag/release currently changes the closed-state assumptions;
- which compatibility identities are protected;
- which tests/gates are required for the intended work.

If any of those answers are unknown, the session is **not bootstrapped yet**.

## 13. Immediate next step

Follow [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md) from a completely fresh clone and return the requested terminal output plus the bounded manual-smoke report.

Only after that evidence is reviewed and the exact baseline is accepted should Approach 3 begin.
