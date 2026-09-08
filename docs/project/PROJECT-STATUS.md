# SUPCheckout for UPayments — Project Status

**Status document:** canonical living engineering state
**Last reconciled:** 2026-09-08
**Canonical repository:** `SimplixInnovations/supcheckout`
**Development version:** `0.1.0`

> Fresh repository/source/CI/provider evidence wins over recorded status. Historical documents preserve milestone truth and are not rewritten to match later branding.

## Executive status

| Area | Current state |
|---|---|
| Product | **SUPCheckout for UPayments** |
| Product family | **SUPCheckout** |
| Technical slug / text domain | `supcheckout` |
| PHP namespace | `Simplixi\SUPCheckout` |
| Package root | `supcheckout/` |
| First-stable bootstrap | `supcheckout/UPayments.php` — intentional compatibility exception |
| Provider scope | **UPayments only** |
| Development version | `0.1.0` |
| Runtime/payment hardening | **DONE / VERIFIED** |
| Final pre-clone runtime/QA closure | **DONE / VERIFIED — PR #75** |
| Repository-control/docs closeout | **DONE / VERIFIED — PR #76** |
| Final enterprise repository audit | **DONE / VERIFIED — PR #77** |
| Current-stable PHP acceptance hardening | **DONE / VERIFIED — PR #80; independent owner acceptance still pending** |
| Cross-platform deterministic release hardening | **DONE / VERIFIED — PR #82; independent owner acceptance still pending** |
| Approach 2 | **DONE / VERIFIED / CLOSED for owner acceptance** |
| Continuity bootstrap / session authority | **ACTIVE — `START-HERE.md` is mandatory** |
| Quality Platform Q1-Q19 | **DONE / VERIFIED — permanently closed at Q19** |
| Enterprise Tasks 1-8 | **DONE / VERIFIED — historical evidence retained** |
| Owner technical acceptance | **PENDING on a fresh clone of final certified `main`** |
| Approach 3 architecture modernization | **NOT STARTED — blocked until owner technical acceptance passes** |
| Full UI/UX / branding / broad launch testing | **DEFERRED until after Approach 3** |
| Public Git tag / GitHub Release | **NOT CREATED** |
| WordPress.org publication | **NOT PERFORMED** |

Historical Quality Platform Q1-Q19 is the retained numbered engineering program and is permanently closed. No Q20 is justified. New work must use named, bounded engineering/release tasks rather than extending the historical numbered platform for continuity.

## Current program sequence

The approved enterprise sequence is:

`Approach 2 closed → fresh-clone owner technical acceptance → accept/freeze the exact baseline → Approach 3 architecture modernization → re-certify Approach 3 → full UI/UX/branding/accessibility/broad launch testing → explicit version/release decision.`

This sequencing is mandatory because it preserves a clean regression boundary. If a defect appears during Approach 3, the team must be able to determine whether it existed on the independently accepted Approach 2 baseline or was introduced by modernization.

The owner acceptance before Approach 3 is deliberately **technical and bounded**. It does not require completion of final UI/UX, branding, broad accessibility certification or exhaustive launch testing. Those remain real launch work, intentionally scheduled after Approach 3 unless fresh evidence makes one an earlier blocker.

Every new session must start with [`START-HERE.md`](START-HERE.md) and verify live GitHub/source/check state before relying on this recorded status.

## Current certified runtime baseline

The latest runtime-bearing certified `main` is:

`1354b8e6f801a847a5fa9b5b657e77647384bdbc`

This is the squash merge of PR #75 from exact certified head:

`9474955e2d5438ccc9c0334b52dc0f72be557a86`

Fresh post-merge runtime evidence:

- Quality Gates #958 — **SUCCESS**;
- H12 Regression Harness — **SUCCESS**;
- Compatibility Certification #486 — **16/16 SUCCESS**;
- Release Artifact #434 / Release Gate — **SUCCESS**;
- Provider Sandbox #386 — **SUCCESS**;
- WordPress.org Submission Check #289 — **SUCCESS / strict packaged Plugin Check**;
- CodeQL/main-security #780 — **SUCCESS**.

PR #76 subsequently reconciled living owner/project documentation without changing plugin runtime behavior.

PR #77 completed the final enterprise repository audit and presentation closeout from exact certified head `4b00ef838f8da0a14d5963697d2584dcd6d82f4d`, squash-merging as GitHub-verified `bf4a46195013edb7699d5142f2c1400d99357fe2`. The PR changed no production runtime/package execution path: 62 runtime/package paths were compared against its base with zero blob differences.

Fresh PR #77 post-merge evidence on exact `main`:

- Quality Gates #978 — **SUCCESS**;
- H12 Regression Harness — **SUCCESS**;
- Compatibility Certification #506 — **16/16 SUCCESS**;
- Release Artifact #454 / Release Gate — **SUCCESS**;
- Provider Sandbox #397 — **SUCCESS**;
- WordPress.org Submission Check #309 — **SUCCESS / strict packaged Plugin Check**;
- CodeQL/main-security #800 — **SUCCESS**;
- deterministic `supcheckout-0.1.0.zip` — SHA-256 `32776f23f02de2fa7be14a5c84ebdcddb9b2f2d348366deade826bca86e58da3`, **56 files**.

Documentation/control-plane descendants may advance `main` without changing the runtime-bearing baseline. Live GitHub evidence is authoritative for the exact current commit.

PR #80 closed the fresh-owner-acceptance PHP 8.5 cleanliness gap without changing production plugin runtime/package-source files. Final certified PR head `717c34d16a5fdc5548b045751bdf53dbdb936a76` passed **35/35 checks**: PHP 8.5 Quality, PHP 8.5 H12 with emitted PHP deprecations/warnings/notices treated as failures, the expanded **18/18** compatibility matrix including PHP 8.5 legacy + HPOS, Release Gate and CodeQL. Its deterministic ZIP remained **56 files**, SHA-256 `32776f23f02de2fa7be14a5c84ebdcddb9b2f2d348366deade826bca86e58da3`.

PR #80 squash-merged as `65e39c5da4fee6462e219bbb0ec21038f831c6b1`. GitHub verified that the merged tree is identical to the 35/35-certified PR-head tree. The merged `main` then passed **26/26 triggered checks**, including Quality/H12 on PHP 8.5, Compatibility Gate and CodeQL. Release Gate was successful on the identical PR tree but did not trigger again on the post-merge push. One migration job on the PR initially hit a GitHub artifact-service HTTP 403 before migration execution; a job-only rerun succeeded and Release Gate then passed. No product defect was waived.

PR #82 closed a cross-platform release determinism defect exposed during independent Windows owner acceptance. The exact same 56-file manifest was reproduced locally and in Ubuntu CI, but DEFLATE ZIP bytes differed by platform. PR #82 switched canonical release entries to `ZIP_STORED`, made the verifier enforce compression method/timestamp/creator-system/mode, and added canonical + Ubuntu + Windows byte-equality evidence as a Release Gate dependency. Final exact PR head `af309e8c668e9def7d94e533f1c553b1b937eae6` passed **40/40 checks** and squash-merged as `c1f70164ebcd13fc6e7a5d3c70830a9070ecf898`; the merge tree is identical to the certified PR tree. Post-merge `main` passed **39/39 checks**. The current canonical package is **56 files**, SHA-256 `24efa28f2803976f4f9437d6b66e55922c26c13143ea291634db31b8506ffd63`, identical across canonical Ubuntu, explicit Ubuntu and explicit Windows builds. No production plugin runtime/package-source file changed.

## Repository state

The owner deleted the final stale remote cleanup branch on 2026-09-08. After PR #77 merged and its branch auto-deleted, verified remote topology returned to:

```text
main
```

Repository controls remain:

- default branch `main`;
- squash-only merging;
- auto-delete merged branches;
- deletion protection on `main`;
- non-fast-forward/force-push protection;
- required linear history;
- required review-thread resolution;
- strict required checks:
  - `Governance`;
  - `H12 Regression Harness`;
  - `Compatibility Gate`;
  - `Release Gate`;
- no bypass actors for the Main Rule.

At PR #77 closure there were **0 open PRs, 0 open issues, 0 Git tags and 0 GitHub Releases**.

## Product and compatibility boundary

SUPCheckout is permanently UPayments-specific. This repository must not grow adapters for unrelated providers or become a generic payment-routing platform.

Protected compatibility identities include, by default:

- gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks / Store API identity `upayments`;
- callback `wc_upayments`;
- historical `_upay_*` metadata;
- provider-order identities such as `UPayments_order_id`;
- token/provenance/scope/generation state;
- `upay_process_subscriptions` and billing-attempt state;
- historical order payment-method values;
- frozen Phase 9I migration identities;
- compatibility wrapper `getAPIUrlForRetreiveCards()`;
- normalized `whitelabled` compatibility shape.

These are merchant/provider contracts, not branding residue. Changing one requires an explicitly approved migration with upgrade, rollback and regression evidence.

## Certified platform boundary

The public compatibility source of truth is [`../COMPATIBILITY.md`](../COMPATIBILITY.md).

Current exact-matrix evidence includes:

- WordPress 6.9.x, 7.0.x and 7.1 cells;
- WooCommerce 10.8.x, 11.0.x and 11.1.x cells;
- PHP 7.4, 8.3 and 8.4 cells;
- legacy order storage and HPOS;
- Classic checkout registration;
- Cart / Checkout Blocks registration/availability;
- historical package-root → canonical package-root migration/rollback.

Compatibility headers and public claims must not be broadened beyond real matrix evidence.

## Permanent release controls

Every release-sensitive candidate must preserve:

- deterministic Git-HEAD-bound ZIP construction;
- byte-identical canonical/Ubuntu/Windows ZIP output for the same exact Git tree;
- `ZIP_STORED` entries plus fixed timestamp/creator-system/file-mode metadata;
- ZIP SHA-256 sidecar;
- per-file SHA-256 manifest;
- source-byte/tamper verification;
- packaged WordPress/WooCommerce smoke;
- packaged HPOS and legacy-storage smoke;
- historical-root migration/rollback qualification;
- official packaged WordPress Plugin Check with `strict: true`;
- dependency audit where applicable;
- CodeQL/security analysis.

The exact package hash is intentionally generated from the final Git `HEAD`; documentation included in the distribution can change that hash between commits without changing runtime code. For one exact Git tree, the local owner artifact must match the canonical/Ubuntu/Windows CI SHA-256. A platform-dependent hash difference is a release blocker.

## Security/payment invariants

- Browser redirects and callback/webhook request bodies are not financial truth by themselves.
- Paid state requires authenticated provider verification plus exact transaction/order/economic binding.
- Ambiguous payment/security identity fails closed.
- Non-idempotent Charge/refund/auto-deduct operations are not blindly retried.
- Merchant secrets, bearer tokens, card data, customer/card tokens, token-provenance secrets and unnecessary PII must not appear in diagnostics, browser output or CI logs.
- Uninstall remains non-destructive by default.

## External/manual or unsupported boundaries

Repository certification does not replace:

- production merchant payment completion;
- real wallet/account/device completion;
- WPML/WCML, multilingual, multicurrency and RTL validation;
- broad browser/device/theme/accessibility testing;
- representative-store performance/load testing;
- penetration testing, PCI or legal/compliance attestation;
- live non-idempotent subscription auto-deduction evidence;
- provider webhook-signature verification until a stable documented contract exists.

Automatic WooCommerce refunds and arbitrary marketplace multi-split remain unsupported.

## Remaining owner work

1. Create a **fresh clone** directly from `https://github.com/SimplixInnovations/supcheckout.git`.
2. Verify the new clone is exact `origin/main` with no inherited local branches, worktrees, stashes or build residue.
3. Run the isolated technical acceptance suite in [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md).
4. Build and verify the deterministic `supcheckout-0.1.0.zip` from that exact final `main`.
5. Perform bounded disposable/staging WooCommerce smoke, including Classic, Blocks, HPOS/legacy where practical and UPayments sandbox behavior.
6. Return the complete automated output and bounded manual-smoke evidence for owner-acceptance review.
7. If that exact baseline is accepted, begin **Approach 3** as a separately scoped architecture-modernization program.
8. Re-certify the completed Approach 3 result against the accepted baseline and all permanent payment/security/compatibility controls.
9. Then complete full UI/UX, branding, accessibility, broad browser/device/theme/manual qualification and other launch-facing work.
10. Explicitly choose the first public version (`0.1.0` early release or a separately approved `1.0.0` first stable).
11. Tag/release/submit to WordPress.org only after explicit owner approval.

## Historical program anchors

Historical records remain available for audit and regression context:

| Program | State |
|---|---|
| Repository Foundation | DONE / VERIFIED |
| Phase 0 Release Identity | DONE / VERIFIED |
| Phase 9I migration | DONE / VERIFIED |
| Provider Payment Lifecycle | DONE / VERIFIED |
| Security Threat Model | DONE / VERIFIED |
| Architecture A1-A5 | DONE / VERIFIED |
| Quality Platform Q1-Q19 | DONE / VERIFIED |
| Enterprise Tasks 1-8 | DONE / VERIFIED |
| SUPCheckout identity/repository migration | DONE / VERIFIED |
| Final pre-clone runtime/QA closure | DONE / VERIFIED |
| Final enterprise repository audit | DONE / VERIFIED |

See [`README.md`](README.md) for document precedence and [`ENTERPRISE-CERTIFICATION.md`](ENTERPRISE-CERTIFICATION.md) for retained certification evidence.
