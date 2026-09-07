# SUPCheckout for UPayments — Project Status

**Status document:** canonical living engineering state
**Last reconciled:** 2026-09-07
**Canonical GitHub repository:** `SimplixInnovations/supcheckout`
**Canonical plugin/package slug:** `supcheckout`
**Development version:** `0.1.0`

> Live GitHub/source evidence wins over recorded SHAs. Historical phase records preserve what was true at their close. This file owns the current program state; `OWNER-HANDOFF.md` owns the remaining owner/admin/local sequence.

## Executive state

| Item | State |
|---|---|
| Product | **SUPCheckout for UPayments** |
| Product family | **SUPCheckout** |
| Production maturity | **Pre-release / final SUPCheckout identity merged and post-merge certified** |
| Canonical technical slug | `supcheckout` |
| WordPress text domain | `supcheckout` |
| PHP namespace | `Simplixi\SUPCheckout` |
| Canonical release root | `supcheckout/` |
| First-stable physical bootstrap | `UPayments.php` — protected compatibility exception |
| Historical pre-stable SUCheckout runtime migration | **DONE / VERIFIED — retained as evidence** |
| Final SUPCheckout identity migration | **DONE / VERIFIED — PR #67 merged and post-merge certified** |
| First-party naming cleanup | **DONE / VERIFIED** |
| Documentation/control-plane hardening | **DONE / VERIFIED — PR #65 merged and post-merge certified** |
| Historical Quality Platform Q1-Q19 | **DONE / VERIFIED — permanently closed at Q19** |
| Historical Enterprise Tasks 1-8 | **DONE / VERIFIED evidence retained** |
| Deterministic release artifact | **DONE / VERIFIED — permanent exact-head gate** |
| Legacy-root → canonical-root migration | **DONE / VERIFIED — permanent release gate** |
| WordPress.org Plugin Check | **DONE / VERIFIED — 0 blocking errors on certified package** |
| SUPCheckout repository rename + living-coordinate closure | **DONE / VERIFIED — canonical `SimplixInnovations/supcheckout`, PR #68 merged/post-merge green** |
| Public Git tag / GitHub Release | **NOT CREATED** |
| WordPress.org publication | **NOT PERFORMED** |

No Q20 is justified. Do not create additional numbered quality phases merely for continuity.

## Certification anchors

Different SHAs below own different milestones and must not be conflated.

### Final SUPCheckout identity + canonical repository baseline

PR #67 certified head:

`0059f365883fa4edd6a2d623c7b370d38d3f565c`

It squash-merged to `main` as:

`7547e59a2d5ef6d49b059851c6899a2d9987b16a`

Fresh exact-main evidence:

- Quality Gates #896 — **SUCCESS**;
- Compatibility Certification #424 — **16/16 SUCCESS**;
- Release Artifact #373 — **SUCCESS**, including packaged legacy/HPOS and both pre-stable root migration/rollback families;
- Provider Sandbox Certification #334 — **SUCCESS**;
- WordPress.org Submission Check #231 — **SUCCESS**;
- CodeQL/main-security #717 — **SUCCESS**.

After this exact-main certification, the repository was renamed to `SimplixInnovations/supcheckout`. Remote branch topology was verified as `main` only before the dedicated post-rename coordinate-reconciliation branch was created.

Post-rename coordinate closure:

- PR #68 exact head `0e6ef6334282a83a428da7ee793daa98360c2bcc` passed Quality #900, Compatibility #428 (**16/16**), Release Artifact #377, Provider Sandbox #338, WordPress.org #235 and CodeQL #722;
- squash merge: `05fec942cc8fbeb58cfd0bd41f0ef5fdb86f966f`;
- post-merge Quality #901, Compatibility #429 (**16/16**), Release Artifact #378, Provider Sandbox #339, WordPress.org #236 and CodeQL #723 — **SUCCESS**;
- PR #68 closeout remote topology before documentation-only PR #69: **main only**;
- open PRs/issues at that closeout point: **0 / 0**;
- tags/releases: **0 / 0**.


### Historical runtime-bearing SUCheckout identity baseline

PR #58 carried the earlier pre-stable SUCheckout product/runtime identity migration and was certified at head:

`5bf84dccb880733da45c1f922d43554af69a33dc`

It squash-merged to `main` as:

`6aabc4fcb0606567a11637ea07fe081fed4c7f85`

Post-merge runtime evidence:

- Quality Gates #764 — **SUCCESS**;
- Compatibility Certification #292 — **16/16 SUCCESS**;
- Release Artifact #243 — **SUCCESS**;
- Provider Sandbox Certification #207 — **SUCCESS**;
- WordPress.org Submission Check #101 — **SUCCESS**;
- CodeQL/main-security #579 — **SUCCESS**;
- official packaged Plugin Check — **0 blocking errors**;
- SUPCheckout Production HTTP Transport — **27 PASS / 0 FAIL**;
- SUPCheckout Provenance DB Failure — **3 PASS / 0 FAIL**;
- SUPCheckout Residue — **17 PASS / 0 FAIL**.

### Documentation/control-plane closeout baseline

PR #59 reconciled the owner handoff and living documentation without changing runtime behavior. It squash-merged as:

`9591c431e1eb56fe40ca60147afdf9f3f909a212`

Fresh push-triggered evidence on that exact `main` SHA:

- Quality Gates #773 — **SUCCESS**;
- Compatibility Certification #301 — **SUCCESS, all 16 runtime cells**;
- Release Artifact #252 — **SUCCESS**;
- Provider Sandbox Certification #216 — **SUCCESS**;
- WordPress.org Submission Check #110 — **SUCCESS**;
- CodeQL/main-security #588 — **SUCCESS**.

### Final documentation/control-plane baseline

PR #65 upgraded and reconciled the complete living documentation/control plane. It squash-merged as:

`24d868b0388a76654b35b3c9d79535aa2eb74678`

Fresh exact-main evidence:

- Quality Gates #814 — **SUCCESS**;
- Compatibility Certification #342 — **16/16 SUCCESS**;
- Release Artifact #291 — **SUCCESS**;
- Provider Sandbox Certification #253 — **SUCCESS**;
- WordPress.org Submission Check #149 — **SUCCESS**;
- CodeQL/main-security #630 — **SUCCESS**.

### Latest first-party naming cleanup baseline

PR #61 completed the remaining safe first-party runtime/control naming cleanup, migrating then-retired first-party implementation/control symbols to the pre-stable SUCheckout equivalents while preserving all protected UPayments/provider/persisted identities.

It merged to `main` as:

`efe937c67343242b7ccf3396a67b3cf2ce35ebac`

Fresh exact-main evidence:

- Quality Gates #781 — **SUCCESS**;
- Compatibility Certification #309 — **16/16 SUCCESS**;
- Release Artifact #258 — **SUCCESS**;
- Provider Sandbox Certification #221 — **SUCCESS**;
- WordPress.org Submission Check #116 — **SUCCESS**;
- CodeQL/main-security #595 — **SUCCESS**.

This historical baseline remains evidence only; the current SUPCheckout migration is certified independently on PR #67.

## Canonical identity

The approved identity is:

- human-facing name: **SUPCheckout for UPayments**;
- short product reference: **SUPCheckout**;
- technical slug: `supcheckout`;
- WordPress text domain: `supcheckout`;
- PHP namespace root: `Simplixi\SUPCheckout`;
- deterministic package: `supcheckout-X.Y.Z.zip`;
- package root: `supcheckout/`;
- first-stable bootstrap: `UPayments.php`;
- canonical GitHub repository: `SimplixInnovations/supcheckout`.

The word **for** appears in human-facing relationship copy only. It does not appear in technical slugs, URLs, package identities, text domains or namespaces.

## Provider-specific scope

SUPCheckout is permanently UPayments-only. Other provider integrations are separate Simplix products/repositories. Cross-provider routing, orchestration and fraud-platform capabilities belong to the separate payments-platform project and are not added to this runtime.

## Protected compatibility identities

Do not mechanically rename provider-facing or persisted merchant identities. Protected examples include:

- gateway/payment method ID `upayments`;
- settings option `woocommerce_upayments_settings`;
- Blocks / Store API payment identity `upayments`;
- callback route `wc_upayments`;
- historical `_upay_*` metadata;
- provider-order identity such as `UPayments_order_id`;
- `upayments_token_identity_secret_v2` and token provenance/scope/generation state;
- `upay_process_subscriptions`;
- billing-attempt state/table;
- historical order payment-method value `upayments`.

These are compatibility contracts, not stale branding residue.

## Physical bootstrap decision

The canonical first-stable package is:

```text
supcheckout/UPayments.php
```

Real WordPress qualification showed that directly deleting/renaming an already-active `UPayments.php` can strand WordPress's stored plugin basename. A future physical rename to `supcheckout.php` therefore remains a separately approved migration, not remaining work for this first release.

## Existing pre-release installation migration

Changing the package root changes WordPress's plugin basename. The release workflow therefore certifies **both real pre-stable package roots** against the final SUPCheckout package:

1. `simplixpay-upayments/UPayments.php` from immutable source `54b1fbcc280b92372bd93baf929d6a746cfd3959`;
2. transitional `sucheckout-upayments/UPayments.php` from immutable source `e9f953b0c3a881ad2d4b74390d0b5394b766a3fa`;
3. final `supcheckout/UPayments.php`.

For each pre-stable root, CI installs/activates the old package, seeds merchant settings and historical order/payment/token/subscription state, deactivates it, installs/activates SUPCheckout, verifies byte/data continuity, proves rollback is non-destructive, returns to SUPCheckout, deletes the inactive pre-stable package, and verifies final runtime again.

No `sucheckout/UPayments.php` migration fixture is invented because that identity existed only on an unmerged superseded refactor branch.

This is permanent release evidence; it is not permission to rename protected stored IDs.

## Certified platform boundary

The permanent real WordPress/WooCommerce matrix covers 16 cells across:

- WordPress 6.9 series through 7.1;
- WooCommerce 10.8 series through 11.1;
- PHP 7.4, 8.3 and 8.4;
- legacy order storage and HPOS;
- Classic checkout registration;
- Cart / Checkout Blocks registration and availability;
- real WooCommerce order CRUD;
- saved-card/token provenance boundaries;
- subscription eligibility/pre-dispatch boundaries;
- one additional-merchant allocation;
- activation/deactivation/uninstall retention.

See `docs/COMPATIBILITY.md` for the public evidence matrix and exact exclusions.

## Canonical release engineering contract

For development version `0.1.0`, the release tooling produces:

- `supcheckout-0.1.0.zip`;
- one `supcheckout/` root;
- SHA-256 ZIP sidecar;
- sorted per-file SHA-256 manifest;
- Git-HEAD-bound bytes according to `.distignore`;
- reproducible output from the same source commit;
- verifier rejection of unsafe paths, forbidden control files, source-divergent bytes and malformed checksum/manifest evidence.

The release workflow installs the exact built package into real WordPress/WooCommerce and runs packaged legacy/HPOS plus legacy-root migration/rollback checks.

## WordPress.org readiness

The permanent `WordPress.org Submission Check`:

- builds the canonical deterministic package;
- verifies the ZIP before use;
- unpacks `supcheckout/`;
- runs the pinned official `WordPress/plugin-check-action` against that exact package;
- uses slug `supcheckout` and `plugin_repo` checks;
- fails on blocking findings.

Passing this gate does **not** publish the plugin or guarantee manual directory approval.

## Final repository-admin closure

Live verification on 2026-09-07 confirms:

- canonical repository `SimplixInnovations/supcheckout`;
- `main` as the only remote branch;
- zero open PRs/issues, zero tags and zero GitHub Releases;
- evidence-safe About description/homepage/topics;
- squash-only merge policy with merged-branch deletion;
- active Main Rule requiring `Governance`, `H12 Regression Harness`, `Compatibility Gate` and `Release Gate` under strict up-to-date checking;
- deletion, non-fast-forward/force-push and linear-history protections retained;
- PR flow and review-thread resolution retained.

Latest certified `main`: `a0794c1f968e7bb97d3a6589aa109b6236fa307f`.

Fresh post-merge evidence on that exact SHA:

- Quality #911 — **SUCCESS**;
- Compatibility #439 — **16/16 SUCCESS**;
- Release Artifact #388 — **SUCCESS**;
- Provider Sandbox #349 — **SUCCESS**;
- WordPress.org Submission Check #246 — **SUCCESS**;
- CodeQL/main-security #733 — **SUCCESS**.

## Remaining owner/admin/local work

Repository rename, metadata/topics, Main Rule hardening, branch cleanup and living-coordinate reconciliation are complete. Remaining work is:

1. verify the owner's local `origin` points directly to `SimplixInnovations/supcheckout`;
2. run the documented isolated local acceptance suite;
3. apply approved SUPCheckout launch branding and visual/accessibility acceptance;
4. choose the first public version explicitly (`0.1.0` early release vs `1.0.0` first stable);
5. run a version-promotion PR if required;
6. tag/release/submit to WordPress.org only after explicit approval.

The exact commands and checks are in `docs/project/OWNER-HANDOFF.md`.

## External/manual or intentionally unsupported boundaries

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

## Permanent historical closure markers

These entries are retained because permanent regression harnesses use them as audit evidence. They do not redefine the current SUPCheckout program state.

| Historical gate | Closure |
|---|---|
| Quality Platform Q16 migration-core analysis | **DONE / VERIFIED** |
| Quality Platform Q17 payment-runtime analysis | **DONE / VERIFIED** |

Historical Q16 closure evidence retained verbatim for permanent regression ownership:

- 3cff2fcc64053d79be7427696c86039f1b52bbfd
- b9cc6eafb3c7f8df36b9c5db8b2e45bb330688d2
- 06a9ebd732c7cc3f062d4bb361aaef4054a1dfa3
- Quality Gates run #315
- Quality Gates run #316
- 160 tests / 987 assertions
- Q16 Migration Core Analysis: **120/0**
- implementation branch `quality/migration-core-analysis`: **deleted after verified merge**

## Task 8 — DONE / VERIFIED

Enterprise Task 8 release-candidate closeout remains historical pre-rebrand evidence and is preserved without rewriting its original milestone identity.

## Historical evidence rule

Former SimplixPay/Q/Task phase records are retained as historical evidence. Do not rewrite historical SHAs, names or then-current claims to pretend those milestones originally occurred under SUPCheckout.

Current truth precedence:

1. fresh live GitHub/source/provider evidence;
2. this `PROJECT-STATUS.md`;
3. `OWNER-HANDOFF.md` for owner/admin actions;
4. `NAMING-IDENTITY-STANDARD.md` for identity;
5. `RELEASE-ENGINEERING.md` for artifact/release contracts;
6. historical phase/quality records for milestone evidence only.
