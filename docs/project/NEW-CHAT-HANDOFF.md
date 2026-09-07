# SUCheckout for UPayments — Clean Chat Handoff

Use this file with root `AGENTS.md`, `PROJECT-STATUS.md`, `OWNER-HANDOFF.md`, `NAMING-IDENTITY-STANDARD.md`, `docs/COMPATIBILITY.md` and `RELEASE-ENGINEERING.md`.

## Identity

- Formal product: **SUCheckout for UPayments**
- Short product/family: **SUCheckout**
- Provider: **UPayments**
- Maintainer: **Simplix Innovations**
- Technical slug / text domain: `sucheckout-upayments`
- PHP namespace root: `Simplixi\SUCheckout\UPayments`
- Package root: `sucheckout-upayments/`
- First-stable physical bootstrap: `UPayments.php`
- Canonical first-stable basename: `sucheckout-upayments/UPayments.php`
- Development version: **0.1.0**
- Current GitHub coordinate pending owner rename: `SimplixInnovations/simplixpay-upayments`
- Approved target repository: `SimplixInnovations/sucheckout-upayments`

The word `for` is human-facing relationship copy only. Never use `sucheckout-for-upayments` technically.

## Program state

- Repository Foundation & Readiness — **DONE / VERIFIED**
- Phase 0 release identity/updater ownership — **DONE / VERIFIED**
- Phase 9I historical identity migration — **DONE / VERIFIED**
- Provider Contract & Payment Lifecycle — **DONE / VERIFIED**
- Security Threat-Model Closure — **DONE / VERIFIED**
- Architecture A1-A5 — **DONE / VERIFIED**
- Quality Platform Q1-Q19 — **DONE / VERIFIED / permanently closed at Q19**
- Enterprise Tasks 1-8 — **DONE / VERIFIED**
- SUCheckout product/namespace/text-domain/package migration — **DONE / VERIFIED**
- Deterministic release + packaged runtime — **DONE / VERIFIED**
- Legacy package-root → SUCheckout package-root migration/rollback — **DONE / VERIFIED**
- WordPress.org packaged Plugin Check — **DONE / VERIFIED / 0 blocking errors**
- Public stable release — **NO**
- WordPress.org publication — **NO**
- Repository rename — **owner/admin action pending**

Never invent Q20. Live GitHub evidence wins over recorded SHAs.

## Certification anchors

### Runtime-bearing SUCheckout baseline

- PR #58 certified head: `5bf84dccb880733da45c1f922d43554af69a33dc`;
- squash merge: `6aabc4fcb0606567a11637ea07fe081fed4c7f85`;
- post-merge Quality #764 — **SUCCESS**;
- Compatibility #292 — **16/16 SUCCESS**;
- Release Artifact #243 — **SUCCESS**;
- Provider Sandbox #207 — **SUCCESS**;
- WordPress.org #101 — **SUCCESS**;
- CodeQL/main-security #579 — **SUCCESS**;
- official packaged Plugin Check — **0 blocking errors**.

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

Real WordPress qualification proved that directly renaming an already-active physical bootstrap can strand the stored plugin basename. A future physical filename `sucheckout-upayments.php` is separately gated and is **not** required before the first release.

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

## What remains

Owner/admin/local sequence is controlled by `OWNER-HANDOFF.md`:

1. delete two obsolete remote branches;
2. rename repository to `SimplixInnovations/sucheckout-upayments`;
3. update GitHub About/security/integrations and local `origin`;
4. run a coordinate-only PR for living old-repository links;
5. run isolated local acceptance and real WooCommerce smoke;
6. explicitly choose the first public version;
7. release/tag/WordPress.org only after exact-main verification and owner approval.

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
