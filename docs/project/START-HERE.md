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

## 4. Current runtime certification and accepted regression reference

Latest runtime-bearing CI-certified `main`:

`047cc86060efb97761d7a0cc4a3806f971ab6fe1`

This is Approach 3 T2 PR #104's squash merge from exact certified head `4cff2dc6e6d11a4b3232a6d3d70d6280a59741c4`. The PR head completed **42/42 checks successfully** and fresh merged `main` completed **41/41 checks successfully**. Current runtime evidence includes T1 dependency/provider-egress guardrails **11/0**, T1 active callback characterization **41/0**, T2 direct legacy-fallback characterization **25/0**, the full compatibility matrix + Compatibility Gate, Release Gate, Provider Sandbox, WordPress.org packaged Plugin Check and CodeQL/security. The deterministic current runtime candidate package is **51 files**, SHA-256 `368aaa5cb1a75e6df41ff17bb2e2126431b49da5e6b8dfe508c718433009fc04`, byte-identical across canonical/Linux/Windows builds.

The separately frozen **owner-accepted Approach 2 regression reference** remains `0c883d609906676966002eb022a82a9656eeacc5` with package SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`. T2 is not owner-accepted until Approach 3 closeout re-acceptance.

The bounded pre-acceptance hardening chain after the pre-acceptance B-X1 owner run is closed:

- PR #84 — B-X1 malformed gateway-settings fail-closed fix;
- PR #91 — Blocks and Classic runtime eligibility parity;
- PR #92 — admin isolation and dead multimerchant repeater cleanup;
- PR #93 — PHP 8.2 current-stack certification, expanding the real matrix to 20 cells;
- PR #94 — installable-package cleanup of repository-only documents;
- PR #95 — last-four-only saved-card presentation;
- PR #96 — opaque saved-card browser handles so provider card tokens do not enter browser state;
- PR #97 — removal of the unnecessary numeric WordPress user ID from subscription browser localization.

The installable package contains `readme.txt` and `LICENSE`; repository-only `README.md`, `CHANGELOG.md` and `SECURITY.md` are excluded.

Owner technical acceptance is **ACCEPTED** for this repository against the following frozen coordinates:

- **Accepted Approach 2 baseline:** `0c883d609906676966002eb022a82a9656eeacc5`
- **Accepted package:** `supcheckout-0.1.0.zip` — 51 files
- **Accepted package SHA-256:** `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`

A new session must resolve live `main` and confirm the accepted-baseline coordinates above are still authoritative before treating any related work as acceptable. Living-document-only descendants may advance `main` after the accepted runtime anchor without changing distributable bytes.

The pre-acceptance B-X1 malformed-settings rejection closed by PR #84 remains historical evidence that documented the gaps closed by PRs #91-#97; the current owner technical acceptance verdict supersedes it but does not erase it.

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

**Owner fresh-clone technical acceptance has been completed and the accepted Approach 2 baseline is frozen.**

The current substantive program is **Approach 3 architecture modernization**. Runtime-neutral T1 `t01-architecture-guardrails-and-active-callback-characterization` is **DONE / VERIFIED** on merged main `beb89ac0c4d8c0e9b7c8b2de1e13c237bbd37b15`. Runtime-bearing T2 `legacy-callback-routing-consolidation` is also **DONE / VERIFIED** on merged main `047cc86060efb97761d7a0cc4a3806f971ab6fe1`: the historical priority-10 `check_ipn_response()` fallback now delegates to the proven `PaymentLifecycle` authority while preserving the public hook/method identity and leaving the direct legacy return/webhook/private verification methods untouched.

This acceptance was intentionally bounded. It did **not** require finishing the final UI/UX, branding, broad accessibility certification or exhaustive launch test matrix before Approach 3.

## 6. Approach 3 boundary

**Status: ARCHITECTURE APPROVED / T1 DONE / VERIFIED / T2 DONE / VERIFIED / RUNTIME MODERNIZATION IN PROGRESS.**

Owner technical acceptance has completed and the accepted Approach 2 baseline `0c883d609906676966002eb022a82a9656eeacc5` is the regression reference coordinate for all Approach 3 work. Any change to runtime/package bytes requires a new acceptance event.

The fresh architecture assessment is complete and recorded in `.ai-architect/`. Approach 3 implementation must follow the approved incremental-strangler contract and classify each proposed modernization as:

- **Keep**
- **Improve**
- **Replace**
- **Defer**
- **Never change without an approved compatibility migration**

Do not interpret “Approach 3” as permission to refactor everything.

Protected payment/provider/persisted identities remain compatibility contracts. Architectural modernization must preserve or explicitly migrate them with upgrade, rollback/failure and regression evidence.

Approach 3 must be bounded, branch-based, reviewable and re-certified against the accepted baseline before launch-facing work is treated as final.

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
| Program phase | **Approach 3 architecture modernization — T1 and T2 done / verified; next tranche requires direct legacy-method characterization before further consolidation** |
| Approach 2 / pre-acceptance engineering | **DONE / VERIFIED** |
| Owner technical acceptance | **ACCEPTED** |
| Accepted Approach 2 baseline | **`0c883d609906676966002eb022a82a9656eeacc5`** |
| Accepted package | **`supcheckout-0.1.0.zip` — 51 files / SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`** |
| Approach 3 | **ARCHITECTURE APPROVED / T1 DONE / VERIFIED / T2 DONE / VERIFIED / RUNTIME MODERNIZATION IN PROGRESS** |
| Full UI/UX / branding / broad launch testing | **DEFERRED until after Approach 3** |
| Public release authorization | **NOT GRANTED** |
| Current program gate | **Approach 3 post-T2 evidence review / direct legacy-method characterization** |
| Next substantive action | Characterize direct `return_from_upayments()`, `web_hook_handler()` and legacy `verify_payment_status()` compatibility behavior/call surface before deciding whether any further consolidation is safe |
| Latest runtime-bearing CI-certified `main` | `047cc86060efb97761d7a0cc4a3806f971ab6fe1` — Approach 3 T2 PR #104 |
| Runtime exact-head evidence | **T2 PR head 42/42; merged main 41/41; T1 dependency 11/0; active callback 41/0; T2 direct fallback 25/0; compatibility + Release Gate + sandbox + Plugin Check + CodeQL green** |
| Current runtime candidate package | **51 files / SHA-256 `368aaa5cb1a75e6df41ff17bb2e2126431b49da5e6b8dfe508c718433009fc04`** |
| Expected closed topology outside temporary work | **`main` only; no open PRs/issues** |
| Local owner acceptance | **ACCEPTED for baseline `0c883d609906676966002eb022a82a9656eeacc5` (pre-acceptance B-X1 malformed-settings rejection closed by PR #84 remains historical evidence)** |
| Source of live truth | **GitHub + exact source/check/package evidence** |

Documentation-only reconciliation may advance live `main` after the T2 runtime anchor while leaving the current 51-file T2 candidate package byte-identical. The separately accepted Approach 2 regression reference remains frozen until re-acceptance.

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

Continue Approach 3 from verified T2. The next bounded tranche must first characterize the direct public legacy browser/webhook methods and old private verification path before any further consolidation. Do not delete, broadly delegate, or rewrite those compatibility surfaces by assumption.
