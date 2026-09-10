# SUPCheckout for UPayments — Repository Agent Instructions

These instructions apply repository-wide. Nested instructions may tighten them but must never weaken payment, security, compatibility or release invariants.

## Read first

Before substantive work, read in this order:

1. `docs/project/START-HERE.md` — mandatory session bootstrap, program sequence and current operational gate
2. `docs/project/PROJECT-STATUS.md` — current verified engineering state
3. `docs/project/OWNER-HANDOFF.md` — fresh-clone/local/release sequence
4. `docs/project/NAMING-IDENTITY-STANDARD.md` — canonical identity and protected IDs
5. `docs/COMPATIBILITY.md` — public compatibility/evidence boundary
6. `docs/project/NEW-CHAT-HANDOFF.md` — compact continuation context
7. `docs/project/RELEASE-ENGINEERING.md` — deterministic package/migration contract
8. `docs/project/ENTERPRISE-CERTIFICATION.md` — retained certification evidence
9. relevant historical phase/quality records when touching their contracts
10. `docs/project/BASELINE-H12.md` when token/saved-card/subscription identity is relevant

### Session-bootstrap rule

Never begin substantive work from chat memory, an old handoff message or a copied SHA alone.

Every new chat, machine, clone, worktree or developer/AI-agent session must first follow `docs/project/START-HERE.md` and verify live GitHub/source/check state. If live evidence and a living document differ, stop and reconcile current truth before implementation or release claims.

When program-level truth changes—owner acceptance state, Approach 3 authorization, release authorization, next substantive action or runtime-bearing baseline—update the living continuity/status authorities in the same bounded workstream. The current program-level ledger is anchored at the owner-accepted Approach 2 baseline `0c883d609906676966002eb022a82a9656eeacc5`; any change to runtime/package bytes requires a new acceptance event.

For every substantive active task, the open GitHub PR is the canonical task-level work ledger. Its body/comments/checks must make the work reconstructable from a new session by recording the base SHA, current exact head, scope/non-scope, current verification gate, blockers/failures, next action and merge-readiness evidence. `START-HERE.md` remains the program-level ledger; do not turn it into an append-only task diary.

## Canonical identity

- Product: **SUPCheckout for UPayments**
- Short name: **SUPCheckout**
- Maintainer: **Simplix Innovations**
- Provider: **UPayments**
- Repository: `SimplixInnovations/supcheckout`
- Slug / text domain: `supcheckout`
- PHP namespace: `Simplixi\SUPCheckout`
- New first-party global prefix: `supcheckout_`
- Constants: `SUPCHECKOUT_*`
- Package root: `supcheckout/`
- First-stable bootstrap: `supcheckout/UPayments.php`
- Development version: `0.1.0`

The word **for** is relationship copy only. Never encode it into repository URLs, WordPress.org slug, package names, namespaces, CSS/JS roots, REST namespaces or release artifacts.

Do not invent alternate product names, slugs, prefixes or namespaces.

## Provider boundary

SUPCheckout is permanently **UPayments-only**.

- Do not add other payment-provider adapters here.
- Do not turn this repository into cross-provider routing/orchestration.
- Future provider integrations are independent products/repositories.
- Shared engineering practices may be reused; payment runtime ownership stays isolated unless separately designed and approved.

## Freshness rule

Live evidence beats recorded status.

Before implementation, review or release:

- verify live `main`;
- inspect branches, open PRs/issues, tags/releases;
- inspect exact source/diff;
- inspect exact-head CI/check state;
- distinguish runtime-bearing baselines from later docs/presentation descendants;
- reconcile living status docs when project truth changes;
- use current provider/platform documentation where behavior depends on it.

Historical records may intentionally contain former product names, repository coordinates and old SHAs. They are evidence, not current branding guidance.

## Current engineering state

Repository Foundation, Phase 0, Phase 9I, Provider Payment Lifecycle, Security Threat Model, Architecture A1-A5, Quality Platform Q1-Q19 and Enterprise Tasks 1-8 are **DONE / VERIFIED**.

Approach 2 and the bounded pre-acceptance hardening sequence are **DONE / VERIFIED**. The numbered Quality Platform is permanently closed at Q19. **Never invent Q20 for continuity.**

Mandatory sequence:

`pre-acceptance engineering closed → fresh-clone owner technical acceptance → accepted baseline → Approach 3 architecture modernization → Approach 3 re-certification → full UI/UX/branding/accessibility/broad launch testing → explicit release decision.`

Approach 3 architecture is **APPROVED / RECORDED**. T1 `t01-architecture-guardrails-and-active-callback-characterization` is **DONE / VERIFIED** on merged main `beb89ac0c4d8c0e9b7c8b2de1e13c237bbd37b15`. Runtime-bearing T2 `legacy-callback-routing-consolidation` is **DONE / VERIFIED** on merged main `047cc86060efb97761d7a0cc4a3806f971ab6fe1`, with fresh post-merge 41/41 SUCCESS and deterministic 51-file candidate package SHA-256 `368aaa5cb1a75e6df41ff17bb2e2126431b49da5e6b8dfe508c718433009fc04`. The frozen owner-accepted Approach 2 package remains the regression reference until Approach 3 closeout re-acceptance. The next substantive action is direct characterization of the legacy return/webhook/private verification surfaces before any further consolidation. Read `.ai-architect/` before any Approach 3 runtime implementation.

Latest runtime-bearing CI-certified `main`:

`047cc86060efb97761d7a0cc4a3806f971ab6fe1`

This is Approach 3 T2 PR #104's squash merge from exact certified head `4cff2dc6e6d11a4b3232a6d3d70d6280a59741c4`. The PR head completed **42/42 checks successfully** and fresh merged `main` completed **41/41 checks successfully**. Current runtime evidence includes T1 dependency/provider-egress guardrails **11/0**, T1 active callback characterization **41/0**, T2 direct legacy-fallback characterization **25/0**, the full compatibility matrix + Compatibility Gate, Release Gate, Provider Sandbox, WordPress.org packaged Plugin Check and CodeQL/security. The deterministic current runtime candidate package is **51 files**, SHA-256 `368aaa5cb1a75e6df41ff17bb2e2126431b49da5e6b8dfe508c718433009fc04`, byte-identical across canonical/Linux/Windows builds.

The separately frozen **owner-accepted Approach 2 regression reference** remains `0c883d609906676966002eb022a82a9656eeacc5` with package SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`. T2 is not owner-accepted until Approach 3 closeout re-acceptance.

PRs #91-#97 closed the post-owner-rejection pre-acceptance gaps: Classic/Blocks runtime eligibility parity, admin isolation/dead repeater cleanup, PHP 8.2 current-stack certification, distribution cleanup, saved-card last-four presentation, opaque browser saved-card handles and removal of the unnecessary numeric WordPress user ID from browser localization.

Owner technical acceptance is **ACCEPTED** for the frozen Approach 2 baseline `0c883d609906676966002eb022a82a9656eeacc5` and accepted package SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`. The pre-acceptance B-X1 malformed-settings rejection closed by PR #84 remains historical evidence that documented the gaps closed by PRs #91-#97; the current owner technical acceptance verdict supersedes it but does not erase it.

Outside bounded active work, required remote topology is **main only**. The superseded #98 branch was explicitly cleaned up after #97 merged.

No public GitHub Release or WordPress.org publication exists. Tag/publication decisions remain explicit owner actions.

## Protected compatibility identities

Never mechanically/global-replace provider/payment/persisted identities.

Protected by default:

- gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks / Store API identity `upayments`;
- callback route `wc_upayments`;
- `_upay_*` historical metadata;
- `UPayments_order_id` and related provider-order identities;
- `upayments_token_identity_secret_v2` and H12 provenance/scope/generation keys;
- `upay_process_subscriptions` and billing-attempt state;
- historical order payment-method identity;
- frozen Phase 9I migration identities;
- provider API field/path/schema terminology;
- public compatibility wrapper `getAPIUrlForRetreiveCards()`;
- normalized `whitelabled` compatibility shape.

Changing one requires an explicitly approved migration contract with old/new precedence, upgrade, rollback/failure semantics and regression evidence.

## First-stable bootstrap exception

`supcheckout/UPayments.php` is intentional.

Real WordPress qualification proved that directly renaming an already-active physical main file can strand WordPress's stored plugin basename. A future `supcheckout.php` physical rename requires a separately approved migration. Do not treat the retained file name as unfinished cosmetic work.

## Permanent quality controls

Do not remove, skip, soften or blanket-ignore:

- `.github/workflows/quality-gates.yml`;
- `.github/workflows/compatibility-certification.yml`;
- `.github/workflows/provider-sandbox-certification.yml`;
- `.github/workflows/release-artifact.yml`;
- `.github/workflows/wordpress-org-submission-check.yml`;
- CodeQL/security analysis;
- architecture harnesses;
- Quality Platform Q1-Q19 harnesses;
- security threat-model harness;
- Phase 0 / Phase 9I / Provider Lifecycle harnesses;
- H12 PHP and Blocks harnesses;
- SUPCheckout identity/namespace/frontend/residue/HTTP/provenance harnesses;
- real integration fixtures for activation, metadata, Blocks, HPOS, saved cards, subscriptions, multi-merchant, operations and upgrade compatibility;
- deterministic artifact builder/verifier/harness;
- official packaged Plugin Check.

The H12 job must fail when required upstream quality/syntax prerequisites fail or skip.

Compatibility headers and public claims require real runtime evidence. Static/unit/H12 success alone cannot broaden support claims.

## Provider automation boundary

Automated provider traffic may use only explicitly documented public sandbox/test credentials or separately authorized repository test secrets.

Never use production merchant credentials in CI.

Routine provider certification remains bounded. Do not add payment completion, polling loops, refunds, saved-card mutation or subscription auto-deduction merely to make CI look broader.

## Payment/security rules

- Evidence before claims.
- Characterize before changing behavior.
- Fail closed on ambiguous payment/security identity.
- Never blindly retry non-idempotent Charge/refund/auto-deduct operations.
- Browser redirects/webhook prose are not financial truth.
- Preserve authenticated provider-status binding and WooCommerce payment semantics.
- Preserve H12 token/provenance contracts unless an approved migration supersedes them.
- Never expose merchant API secrets/bearer tokens, card data, customer/card tokens, provenance secrets, unnecessary PII or production database exports.
- Uninstall remains non-destructive by default.

## Public compatibility boundaries

Do not imply certification beyond `docs/COMPATIBILITY.md`.

External/manual unless separately proven:

- production merchant payment completion;
- real wallet/account/device completion;
- WPML/WCML/multilingual/multicurrency/RTL;
- broad browser/device/theme/accessibility;
- representative performance/load;
- penetration testing, PCI or legal/compliance attestation;
- live subscription auto-deduction;
- provider webhook signature verification until a stable documented contract exists.

Unsupported:

- automatic WooCommerce refunds;
- arbitrary marketplace multi-split beyond one additional merchant.

## Repository presentation rules

The root README is a public product landing page, not an internal certification ledger.

- Keep public copy concise, factual and product-first.
- Put deep run/SHA evidence in project-control docs.
- Keep legal/provenance language professional and centralized in `NOTICE.md` / `UPSTREAM.md` rather than repeating defensive disclaimers everywhere.
- Do not add badges or topics that imply unsupported certification, publication or endorsement.
- Do not publish stale screenshots as current product documentation.

## Change discipline

- Use a dedicated branch from freshly verified `main`.
- Use TDD for production behavior/bug fixes: RED first, minimal GREEN, affected + permanent regressions.
- Keep changes bounded; no drive-by payment refactors.
- Do not grow `UPayments.php` with new responsibilities.
- Large compatibility-sensitive files are not refactored solely for aesthetics before release; require a concrete maintainability/behavior goal and regression plan.
- Update living state docs when verified project truth changes.
- Preserve historical records instead of rewriting milestone facts.
- Do not create a new phase merely because documentation needs maintenance.

## Merge and release discipline

External AI/bot output is an evidence request, not authority.

Before merging runtime/release-sensitive work require the exact head to satisfy, at minimum:

- Quality/H12 green;
- Compatibility Gate green with all 20 runtime cells successful;
- Release Artifact including packaged + migration cells green;
- bounded Provider Sandbox green when applicable;
- WordPress.org Submission Check green;
- CodeQL/security green;
- locked dependency audit where applicable;
- zero unresolved valid review threads;
- exact-head mergeability;
- squash-only merge;
- post-merge verification on `main`.

Documentation/presentation-only changes still require all workflows triggered by their paths. Do not fabricate runtime changes to force unrelated work.

If required verification fails:

`NOT APPROVED.`
`DO NOT MERGE.`
