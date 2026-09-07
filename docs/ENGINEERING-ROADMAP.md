# SUCheckout for UPayments — Engineering Roadmap

This is the public high-level sequence. `docs/project/PROJECT-STATUS.md` owns current verified state. Historical phase/quality records preserve detailed closeout evidence and are not rewritten into current branding.

## Completed engineering foundations

1. **Repository Foundation & Readiness — DONE / VERIFIED**
2. **Phase 0 — release identity and updater ownership — DONE / VERIFIED**
3. **Phase 9I — historical identity migration — DONE / VERIFIED**
4. **Provider Contract & Payment Lifecycle — DONE / VERIFIED**
5. **Security Threat-Model Closure — DONE / VERIFIED**
6. **Architecture & Code-Quality Foundation A1-A5 — DONE / VERIFIED**
7. **Automated Quality Platform Q1-Q19 — DONE / VERIFIED / CLOSED**

The numbered Quality Platform is permanently closed at Q19. No Q20 is justified merely because administrative, documentation, local-acceptance or release work remains.

## Enterprise completion program — DONE / VERIFIED

The enterprise completion plan is retained at `docs/superpowers/plans/2026-09-06-enterprise-completion.md`.

### Task 1 — quality-program closeout

**DONE / VERIFIED.** Closed the numbered quality sequence at Q19 while retaining the permanent regression stack.

### Task 2 — real platform compatibility

**DONE / VERIFIED.** Permanent 16-cell real WordPress/WooCommerce/PHP × legacy/HPOS matrix covering activation, Classic registration, Blocks registration/availability and Woo order CRUD.

### Task 3 — evidence-derived public declarations

**DONE / VERIFIED.** Public WordPress/WooCommerce/PHP support headers plus Woo `cart_checkout_blocks` / `custom_order_tables` declarations are derived from real runtime evidence.

### Task 4 — bounded provider sandbox

**DONE / VERIFIED.** Controlled public UPayments sandbox Charge initialization verifies the bounded endpoint/transport/schema/payment-link contract without production credentials or broad non-idempotent mutation.

### Task 5 — deterministic installable artifact

**DONE / VERIFIED.** Git-HEAD-bound deterministic ZIP, checksum, per-file manifest, source-byte verification, tamper rejection and packaged real-runtime smoke.

### Task 6 — feature and operational boundaries

**DONE / VERIFIED.** Saved-card/token provenance, subscription eligibility/pre-dispatch, one-additional-merchant allocation and non-destructive lifecycle/data retention.

### Task 7 — existing-install / release identity

**DONE / VERIFIED.** Upgrade/rollback/data/callback/cron continuity and duplicate-package characterization.

Task 7 also produced the important negative proof that changing the physical main filename alone does not preserve WordPress active-plugin identity. That is why first-stable SUCheckout intentionally uses:

```text
sucheckout-upayments/UPayments.php
```

A future physical rename to `sucheckout-upayments.php` remains separately gated.

### Task 8 — Enterprise Release Candidate Closeout

**DONE / VERIFIED.** The pre-rebrand enterprise engineering foundation was closed with full exact-head quality, compatibility, artifact, provider and security evidence. Those records remain historical evidence.

## SUCheckout identity migration — DONE / VERIFIED

The first-party identity is now:

- human product: **SUCheckout for UPayments**;
- technical slug/text domain: `sucheckout-upayments`;
- PHP namespace: `Simplixi\SUCheckout\UPayments`;
- package root: `sucheckout-upayments/`;
- first-stable physical bootstrap: `UPayments.php`;
- target GitHub repository: `SimplixInnovations/sucheckout-upayments`.

The word `for` is human-facing relationship wording only and never appears in technical identifiers.

### Runtime-bearing certification

PR #58 certified head `5bf84dccb880733da45c1f922d43554af69a33dc` squash-merged as `6aabc4fcb0606567a11637ea07fe081fed4c7f85`.

Post-merge:

- Quality #764 — **SUCCESS**
- Compatibility #292 — **16/16 SUCCESS**
- Release Artifact #243 — **SUCCESS**
- Provider Sandbox #207 — **SUCCESS**
- WordPress.org #101 — **SUCCESS**
- CodeQL #579 — **SUCCESS**
- official packaged Plugin Check — **0 blocking errors**

### Final documentation/control-plane closeout

PR #59 squash-merged as `9591c431e1eb56fe40ca60147afdf9f3f909a212`.

Fresh main evidence:

- Quality #773 — **SUCCESS**
- Compatibility #301 — **all 16 cells SUCCESS**
- Release Artifact #252 — **SUCCESS**
- Provider Sandbox #216 — **SUCCESS**
- WordPress.org #110 — **SUCCESS**
- CodeQL #588 — **SUCCESS**

Later documentation-only maintenance may advance `main` without redefining the runtime-bearing SUCheckout baseline.

## Current owner/admin/local stage

Engineering does not need another invented phase. The remaining sequence is controlled by `docs/project/OWNER-HANDOFF.md`:

1. synchronize the owner's normal clone with `origin/main`;
2. delete obsolete remote branches;
3. rename GitHub repository to `SimplixInnovations/sucheckout-upayments`;
4. update About metadata and local `origin`;
5. verify rulesets, Actions, security controls, Dependabot, PVR, secrets/environments and external integrations after rename;
6. create one coordinate-only PR for living old-repository URLs/badges/issue links;
7. run isolated local owner acceptance from exact `origin/main`;
8. build and verify the deterministic ZIP;
9. perform disposable WordPress/WooCommerce Classic/Blocks/HPOS/bounded-sandbox smoke;
10. explicitly choose the first public version;
11. tag/GitHub Release/WordPress.org publication only after exact-main certification and owner approval.

## External/manual evidence track

These are not repository-CI claims:

- production merchant payment completion;
- wallet completion on real provider-enabled accounts/devices;
- WPML/WCML/multilingual/multicurrency/RTL certification;
- browser/device/theme/accessibility matrix;
- representative-store performance/load thresholds;
- penetration testing / PCI / legal-compliance attestations;
- live non-idempotent subscription auto-deduction;
- provider webhook signature verification until a stable published verification contract exists.

Automatic Woo refunds and arbitrary marketplace multi-split remain intentionally unsupported unless separately designed and certified.

## Continuous maintenance after release-candidate closeout

After the owner/release stage, normal maintenance includes:

- supported WordPress/WooCommerce/PHP version monitoring;
- UPayments API/documentation monitoring;
- dependency/security updates;
- permanent compatibility regression matrix;
- release/support lifecycle;
- public issue/security handling;
- future explicit migrations only when evidence justifies them.

## Completion rule

A roadmap item is complete only after exact implementation/review evidence, required checks, merge, post-merge verification and living-state reconciliation.

Branch existence, a bot report, a single green workflow or optimistic prose is never sufficient.
