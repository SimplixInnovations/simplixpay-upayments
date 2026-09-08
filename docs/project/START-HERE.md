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

## 4. Exact program state at the Approach 2 handoff

### Repository baseline entering this continuity bootstrap

The last fully closed `main` before this continuity-documentation task was:

`148296e5ac1f6a6748661781522e3a840a27d3de`

That commit is PR #78's documentation-only reconciliation after the final enterprise repository audit.

The latest runtime-bearing **CI-certified `main`** is:

`902c23caad1461e63c816fdc5252855d9dc4f9e4`

That is PR #84's squash merge from exact certified head `199d4a4f2f17cd982b2c869c9ef6eee794c05fd1`. The PR head passed **40/40 checks** and the byte-identical squash tree passed **39/39 post-merge checks**. The canonical runtime package is **56 files**, SHA-256 `90ac844d37938e5cbb7e9a9ee3e8923b904bf8cf978c62adf2a8994a4b7462ce`. Owner technical acceptance has **not** yet accepted this fixed baseline; a new fresh-clone owner acceptance is required.

### Approach 2

**Status: DONE / VERIFIED / CLOSED for owner acceptance.**

Approach 2 completed the repository-wide enterprise audit, release-control reconciliation and presentation closeout. It did not identify an evidence-backed reason for another speculative production-runtime change.

PR #77 completed the final enterprise repository audit from certified head:

`4b00ef838f8da0a14d5963697d2584dcd6d82f4d`

and squash-merged as:

`bf4a46195013edb7699d5142f2c1400d99357fe2`

Recorded post-merge evidence for that runtime/package-equivalent state includes:

- Quality Gates #978 — **SUCCESS**;
- H12 Regression Harness — **SUCCESS**;
- Compatibility Certification #506 — **16/16 SUCCESS**;
- Release Artifact #454 / Release Gate — **SUCCESS**;
- Provider Sandbox #397 — **SUCCESS**;
- WordPress.org Submission Check #309 — **SUCCESS / strict packaged Plugin Check**;
- CodeQL/main-security #800 — **SUCCESS**;
- deterministic `supcheckout-0.1.0.zip` — SHA-256 `32776f23f02de2fa7be14a5c84ebdcddb9b2f2d348366deade826bca86e58da3`, 56 files.

PR #78 then reconciled the living status/owner handoff only. Any future session must still verify the live current `main` and current checks rather than treating the evidence above as a substitute for freshness.

PR #80 then hardened the **owner-acceptance and permanent current-PHP evidence layer** after fresh local PHP 8.5 acceptance exposed deprecated/stale test constructs. Its final exact head `717c34d16a5fdc5548b045751bdf53dbdb936a76` passed **35/35 checks**, including PHP 8.5 Quality, PHP 8.5 H12, the expanded **18/18** real compatibility matrix, Release Gate and CodeQL. It squash-merged as `65e39c5da4fee6462e219bbb0ec21038f831c6b1`. The merged Git tree is byte-identical to the certified PR-head tree; post-merge `main` then passed **26/26 triggered checks**, including PHP 8.5 Quality/H12, Compatibility Gate and CodeQL. Release Gate did not re-trigger on the post-merge push and must not be falsely reported as a post-merge run. PR #80 changed **no production plugin runtime/package-source file**. The deterministic package at that historical packaging contract remained **56 files**, SHA-256 `32776f23f02de2fa7be14a5c84ebdcddb9b2f2d348366deade826bca86e58da3`.

PR #82 then closed a release-engineering defect discovered by independent Windows owner acceptance: the exact same **56-file / 56-path per-file manifest** produced different DEFLATE ZIP bytes on Windows and Ubuntu. PR #82 replaced environment-dependent DEFLATE output with deterministic `ZIP_STORED` entries, strengthened ZIP metadata verification, and made Release Gate depend on canonical + Ubuntu + Windows artifact equality. Final exact PR head `af309e8c668e9def7d94e533f1c553b1b937eae6` passed **40/40 checks**. It squash-merged as `c1f70164ebcd13fc6e7a5d3c70830a9070ecf898`; the merge tree is byte-identical to the certified PR-head tree, and merged `main` passed **39/39 checks**, including the new cross-platform determinism gate and Release Gate. The canonical package is **56 files**, SHA-256 `24efa28f2803976f4f9437d6b66e55922c26c13143ea291634db31b8506ffd63`, reproduced identically by Ubuntu and Windows builds. PR #82 changed release tooling/control/test/docs only and no production plugin runtime/package-source file.

PR #84 then fixed the first-party **B-X1 malformed gateway-settings defect** found by the 2026-09-08 fresh-clone owner acceptance. The exact PR head `199d4a4f2f17cd982b2c869c9ef6eee794c05fd1` passed **40/40 checks**. The permanent regression now executes against the exact packaged ZIP in both legacy and HPOS `packaged-runtime` jobs and is a transitive dependency of Release Gate. PR #84 squash-merged as `902c23caad1461e63c816fdc5252855d9dc4f9e4`; its tree is byte-identical to the reviewed PR head and post-merge `main` passed **39/39 checks**. The canonical package is **56 files**, SHA-256 `90ac844d37938e5cbb7e9a9ee3e8923b904bf8cf978c62adf2a8994a4b7462ce`.

Owner technical acceptance remains **NOT ACCEPTED**. The prior rejection is valid evidence that B-X1 existed before PR #84; PR #84 fixes and CI-certifies that defect, but it does not substitute for the required fresh-clone owner re-acceptance. Approach 3 remains blocked until that re-acceptance returns **ACCEPTED BASELINE**.

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

This table is the compact operational state. It is not a substitute for GitHub PRs, commits or CI.

| Field | Current value |
|---|---|
| Program phase | **Owner technical acceptance preparation** |
| Approach 2 | **DONE / VERIFIED / CLOSED** |
| Owner technical acceptance | **NOT ACCEPTED on 2026-09-08 — B-X1 is fixed and CI-certified on `main` by PR #84; fresh-clone owner re-acceptance required** |
| Accepted owner baseline | **NONE YET** |
| Approach 3 | **BLOCKED until owner technical acceptance passes** |
| Full UI/UX / branding / broad launch testing | **DEFERRED until after Approach 3** |
| Public release authorization | **NOT GRANTED** |
| Current program gate | **Fresh-clone owner technical acceptance** |
| Next substantive action | Fresh clone + owner acceptance per `OWNER-HANDOFF.md` |
| Latest runtime-bearing CI-certified `main` | `902c23caad1461e63c816fdc5252855d9dc4f9e4` — PR #84; owner re-acceptance pending |
| Latest current-stack hardening merge | `902c23caad1461e63c816fdc5252855d9dc4f9e4` — PR #84 |
| Expected active branch / PR outside temporary work | **None — verified post-PR #84; repository topology returned to `main` only** |
| Local fresh-clone owner acceptance (2026-09-08) | **NOT ACCEPTED — B-X1 found. PR #84 fixes B-X1 on `main`; a new fresh-clone acceptance is now required to establish an accepted baseline** |
| Last retained full runtime/package evidence | **PR #77 post-merge: Quality #978, Compatibility #506, Release #454, Provider #397, WordPress.org #309, CodeQL #800** |
| Latest current-stack release/QA evidence | **PR #84 exact head `199d4a4f2f17cd982b2c869c9ef6eee794c05fd1`: 40/40 SUCCESS; B-X1 permanently gated in packaged legacy + HPOS; merge `902c23caad1461e63c816fdc5252855d9dc4f9e4`: byte-identical tree + 39/39 post-merge checks; compatibility remains 18/18; canonical ZIP 56 files / SHA-256 `90ac844d37938e5cbb7e9a9ee3e8923b904bf8cf978c62adf2a8994a4b7462ce`** |
| Source of live truth | **GitHub + exact source/check evidence** |

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
