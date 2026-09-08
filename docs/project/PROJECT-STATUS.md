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
| Quality Platform Q1-Q19 | **DONE / VERIFIED — permanently closed at Q19** |
| Enterprise Tasks 1-8 | **DONE / VERIFIED — historical evidence retained** |
| Public Git tag / GitHub Release | **NOT CREATED** |
| WordPress.org publication | **NOT PERFORMED** |
| Owner local acceptance | **PENDING on a fresh clone of final certified `main`** |

Historical Quality Platform Q1-Q19 is the retained numbered engineering program and is permanently closed. No Q20 is justified. New work must use named, bounded engineering/release tasks rather than extending the historical numbered platform for continuity.

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
- ZIP SHA-256 sidecar;
- per-file SHA-256 manifest;
- source-byte/tamper verification;
- packaged WordPress/WooCommerce smoke;
- packaged HPOS and legacy-storage smoke;
- historical-root migration/rollback qualification;
- official packaged WordPress Plugin Check with `strict: true`;
- dependency audit where applicable;
- CodeQL/security analysis.

The exact package hash is intentionally generated from the final Git `HEAD`; documentation included in the distribution can change that hash without changing runtime code. For owner acceptance, the locally generated `.sha256` sidecar on the exact final `origin/main` is authoritative.

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
3. Run the isolated local acceptance suite in [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md).
4. Build and verify the deterministic `supcheckout-0.1.0.zip` from exact final `main`.
5. Perform disposable/staging WooCommerce merchant-facing smoke, including Classic, Blocks, HPOS/legacy where practical and UPayments sandbox behavior.
6. Complete launch-branding/visual/accessibility acceptance.
7. Explicitly choose the first public version (`0.1.0` early release or a separately approved `1.0.0` first stable).
8. Tag/release/submit to WordPress.org only after explicit owner approval.

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
