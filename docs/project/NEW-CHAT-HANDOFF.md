# SUPCheckout for UPayments — Clean Chat Handoff

Use this file with root `AGENTS.md`, `PROJECT-STATUS.md`, `OWNER-HANDOFF.md`, `NAMING-IDENTITY-STANDARD.md`, `docs/COMPATIBILITY.md` and `RELEASE-ENGINEERING.md`.

## Identity

- Formal product: **SUPCheckout for UPayments**
- Short product/family: **SUPCheckout**
- Provider: **UPayments**
- Maintainer: **Simplix Innovations**
- Technical slug / text domain: `supcheckout`
- PHP namespace root: `Simplixi\SUPCheckout`
- Package root: `supcheckout/`
- First-stable physical bootstrap: `UPayments.php`
- Canonical first-stable basename: `supcheckout/UPayments.php`
- Development version: **0.1.0**
- Canonical GitHub repository: `SimplixInnovations/supcheckout`
- Canonical plugin/package slug: `supcheckout`

The word `for` is human-facing relationship copy only and must never be encoded into technical identifiers.

## Program state

- Repository Foundation & Readiness — **DONE / VERIFIED**
- Phase 0 release identity/updater ownership — **DONE / VERIFIED**
- Phase 9I historical identity migration — **DONE / VERIFIED**
- Provider Contract & Payment Lifecycle — **DONE / VERIFIED**
- Security Threat-Model Closure — **DONE / VERIFIED**
- Architecture A1-A5 — **DONE / VERIFIED**
- Quality Platform Q1-Q19 — **DONE / VERIFIED; closed at Q19**
- Quality Platform Q16 migration-core analysis: **DONE / VERIFIED**
- Quality Platform Q17 payment-runtime analysis: **DONE / VERIFIED**
- Enterprise Task 8 release-candidate closeout — **DONE / VERIFIED**
- Enterprise Tasks 1-8 — **DONE / VERIFIED**
- Final SUPCheckout product/namespace/text-domain/package migration — **DONE / VERIFIED — PR #67 merged and post-merge certified**
- Deterministic release + packaged runtime — **DONE / VERIFIED**
- Both real pre-stable package roots → SUPCheckout migration/rollback — **CI-CERTIFIED on current candidate**
- WordPress.org packaged Plugin Check — **DONE / VERIFIED / strict zero-warning/error gate**
- Public stable release — **NO**
- WordPress.org publication — **NO**
- Repository rename — **DONE / VERIFIED — `SimplixInnovations/supcheckout`**

Never invent Q20. Live GitHub evidence wins over recorded SHAs.

## Certification anchors

### Historical pre-stable SUCheckout baseline

- PR #58 certified head: `5bf84dccb880733da45c1f922d43554af69a33dc`;
- squash merge: `6aabc4fcb0606567a11637ea07fe081fed4c7f85`;
- post-merge Quality #764 — **SUCCESS**;
- Compatibility #292 — **16/16 SUCCESS**;
- Release Artifact #243 — **SUCCESS**;
- Provider Sandbox #207 — **SUCCESS**;
- WordPress.org #101 — **SUCCESS**;
- CodeQL/main-security #579 — **SUCCESS**;
- official packaged Plugin Check — **0 blocking errors**.

### Final SUPCheckout identity migration

- PR #67 certified head: `0059f365883fa4edd6a2d623c7b370d38d3f565c`;
- squash merge: `7547e59a2d5ef6d49b059851c6899a2d9987b16a`;
- post-merge Quality #896 — **SUCCESS**;
- Compatibility #424 — **16/16 SUCCESS**;
- Release Artifact #373 — **SUCCESS**;
- Provider Sandbox #334 — **SUCCESS**;
- WordPress.org #231 — **SUCCESS**;
- CodeQL/main-security #717 — **SUCCESS**;
- repository rename — **COMPLETE**;
- obsolete remote branches — **CLEANED; main-only topology verified before coordinate reconciliation**.
- coordinate-closure PR #68 exact head: `0e6ef6334282a83a428da7ee793daa98360c2bcc` — **FULL EXACT-HEAD STACK SUCCESS**;
- PR #68 squash merge: `05fec942cc8fbeb58cfd0bd41f0ef5fdb86f966f`;
- post-merge Quality #901, Compatibility #429 (**16/16**), Release Artifact #378, Provider Sandbox #339, WordPress.org #236 and CodeQL #723 — **SUCCESS**;
- PR #68 closeout state before documentation-only PR #69: **main only**, with open PRs/issues, tags and releases **empty**.

### Final control-plane closeout

- PR #59 squash merge: `9591c431e1eb56fe40ca60147afdf9f3f909a212`;
- fresh main Quality #773 — **SUCCESS**;
- Compatibility #301 — **all 16 cells SUCCESS**;
- Release Artifact #252 — **SUCCESS**;
- Provider Sandbox #216 — **SUCCESS**;
- WordPress.org #110 — **SUCCESS**;
- CodeQL/main-security #588 — **SUCCESS**.

Later docs-only commits may advance `main`; always verify live release evidence.

## Protected compatibility identities

Do not search/replace these for cosmetic naming:

- gateway/payment ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks / Store API identity `upayments`;
- callback `wc_upayments`;
- historical `_upay_*` metadata;
- provider order identities such as `UPayments_order_id`;
- `upayments_token_identity_secret_v2` and provenance/scope/generation state;
- `upay_process_subscriptions`;
- billing-attempt table/state;
- historical order payment method `upayments`;
- provider API request/response/schema terminology.

## First-stable bootstrap decision

The first-stable package intentionally uses `UPayments.php`.

Real WordPress qualification proved that directly renaming an already-active physical bootstrap can strand the stored plugin basename. A future physical filename `supcheckout.php` is separately gated and is **not** required before the first release.

## Verified enterprise surfaces

- 16-cell real WordPress/WooCommerce/PHP × legacy/HPOS matrix;
- Classic and Cart/Checkout Blocks registration/availability;
- real Woo order CRUD;
- bounded public-sandbox Charge initialization;
- provider-authenticated payment status binding;
- saved-card/token provenance boundaries;
- subscription eligibility/pre-dispatch boundaries;
- one additional-merchant allocation only;
- non-destructive activation/deactivation/uninstall retention;
- Git-HEAD-bound deterministic ZIP + checksum + per-file manifest;
- packaged legacy/HPOS runtime smoke;
- legacy-root → canonical-root migration and rollback;
- official Plugin Check on the exact unpacked release artifact.

## Final repository-admin closure

Live GitHub state is reconciled: About/topics are correct; Main Rule requires `Governance`, `H12 Regression Harness`, `Compatibility Gate` and `Release Gate`; only `main` remains; no open PRs/issues/tags/releases exist. Latest certified `main` is `bfadff34142a3a676258e8dc0774bd31287c0138` with Quality #927, Compatibility #455 (**16/16**), Release Artifact #404, Provider Sandbox #365, strict WordPress.org #262 and CodeQL #749 all **SUCCESS**. PR #71 made official packaged Plugin Check `strict: true`; the exact-main result reports no findings. Current deterministic ZIP SHA-256 is `0436256b16605b9b2db91aa8a7865ecec6cae7ef00fa515048d9643e50ad990a` (74 files).

## What remains

Owner/admin/local sequence is controlled by `OWNER-HANDOFF.md`:

1. verify local `origin` points directly to the canonical repository;
2. run isolated local acceptance and real WooCommerce smoke;
3. apply approved launch branding and visual/accessibility acceptance;
4. explicitly choose the first public version;
5. release/tag/WordPress.org only after exact-main verification and owner approval.

## External/manual and unsupported boundaries

External/manual:

- production merchant payment completion;
- real wallet/account/device completion;
- WPML/WCML/multilingual/multicurrency/RTL;
- browser/device/theme/accessibility;
- representative performance/load;
- penetration testing / PCI / legal-compliance attestation;
- live subscription auto-deduction;
- provider webhook signature until a stable documented contract exists.

Unsupported:

- automatic Woo refunds;
- arbitrary marketplace multi-split beyond one additional merchant.

## Historical evidence rule

Former SimplixPay identity, old repository names and historical run numbers may remain inside historical phase/quality evidence where they were true at the time. Do not rewrite history to remove every old token.

## Merge discipline

External AI/bot output is evidence to reproduce, not authority. Future changes require exact-head review, applicable permanent checks, valid-thread resolution, squash merge and post-merge verification. Runtime/release-sensitive changes require the full permanent release-sensitive suite.
