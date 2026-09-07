# SUCheckout for UPayments — Project Status

**Status document:** canonical living engineering state  
**Last reconciled:** 2026-09-07  
**Current GitHub repository pending owner rename:** `SimplixInnovations/simplixpay-upayments`  
**Approved canonical repository:** `SimplixInnovations/sucheckout-upayments`  
**Development version:** `0.1.0`

> Live GitHub/source evidence wins over recorded SHAs. Historical phase records preserve what was true at their close. This file owns the current program state; `OWNER-HANDOFF.md` owns the remaining owner/admin/local sequence.

## Executive state

| Item | State |
|---|---|
| Product | **SUCheckout for UPayments** |
| Product family | **SUCheckout** |
| Technical slug / text domain | `sucheckout-upayments` |
| PHP namespace | `Simplixi\SUCheckout\UPayments` |
| Canonical release root | `sucheckout-upayments/` |
| First-stable physical bootstrap | `UPayments.php` — protected compatibility exception |
| Runtime-bearing SUCheckout migration | **DONE / VERIFIED** |
| First-party naming cleanup | **DONE / VERIFIED** |
| Documentation/control-plane hardening | **IN FINAL EXACT-HEAD CERTIFICATION** |
| Historical Quality Platform Q1-Q19 | **DONE / VERIFIED — permanently closed at Q19** |
| Historical Enterprise Tasks 1-8 | **DONE / VERIFIED evidence retained** |
| Deterministic release artifact | **DONE / VERIFIED — permanent exact-head gate** |
| Legacy-root → canonical-root migration | **DONE / VERIFIED — permanent release gate** |
| WordPress.org Plugin Check | **DONE / VERIFIED — 0 blocking errors on certified package** |
| Repository rename | **OWNER/ADMIN ACTION — READY AFTER THIS DOC PR MERGES** |
| Public Git tag / GitHub Release | **NOT CREATED** |
| WordPress.org publication | **NOT PERFORMED** |

No Q20 is justified. Do not create additional numbered quality phases merely for continuity.

## Certification anchors

Different SHAs below own different milestones and must not be conflated.

### Runtime-bearing SUCheckout identity baseline

PR #58 carried the product/runtime identity migration and was certified at head:

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
- SUCheckout Production HTTP Transport — **27 PASS / 0 FAIL**;
- SUCheckout Provenance DB Failure — **3 PASS / 0 FAIL**;
- SUCheckout Residue — **17 PASS / 0 FAIL**.

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

### Latest first-party naming cleanup baseline

PR #61 completed the remaining safe first-party runtime/control naming cleanup, migrating retired first-party implementation/control symbols to canonical SUCheckout equivalents while preserving all protected UPayments/provider/persisted identities.

It merged to `main` as:

`efe937c67343242b7ccf3396a67b3cf2ce35ebac`

Fresh exact-main evidence:

- Quality Gates #781 — **SUCCESS**;
- Compatibility Certification #309 — **16/16 SUCCESS**;
- Release Artifact #258 — **SUCCESS**;
- Provider Sandbox Certification #221 — **SUCCESS**;
- WordPress.org Submission Check #116 — **SUCCESS**;
- CodeQL/main-security #595 — **SUCCESS**.

This `efe937c6...` commit is the base of the current final documentation-hardening branch. The final docs merge may advance `main` without redefining the runtime-bearing milestone above.

## Canonical identity

The approved identity is:

- human-facing name: **SUCheckout for UPayments**;
- short product reference: **SUCheckout**;
- technical slug: `sucheckout-upayments`;
- WordPress text domain: `sucheckout-upayments`;
- PHP namespace root: `Simplixi\SUCheckout\UPayments`;
- deterministic package: `sucheckout-upayments-X.Y.Z.zip`;
- package root: `sucheckout-upayments/`;
- first-stable bootstrap: `UPayments.php`;
- target repository after owner rename: `SimplixInnovations/sucheckout-upayments`.

The word **for** appears in human-facing relationship copy only. It does not appear in technical slugs, URLs, package identities, text domains or namespaces.

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
sucheckout-upayments/UPayments.php
```

Real WordPress qualification showed that directly deleting/renaming an already-active `UPayments.php` can strand WordPress's stored plugin basename. A future physical rename to `sucheckout-upayments.php` therefore remains a separately approved migration, not remaining work for this first release.

## Existing pre-release installation migration

Changing the package root changes WordPress's plugin basename. The release workflow therefore certifies the explicit pre-release path:

1. install/activate legacy `simplixpay-upayments/UPayments.php`;
2. seed merchant settings, historical order/payment data, tokens/subscription metadata and cron state;
3. deactivate the legacy package;
4. install/activate canonical `sucheckout-upayments/UPayments.php`;
5. verify protected merchant/provider data continuity;
6. prove rollback remains non-destructive;
7. return to canonical SUCheckout;
8. remove the inactive legacy package;
9. re-verify canonical runtime and retained data.

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

- `sucheckout-upayments-0.1.0.zip`;
- one `sucheckout-upayments/` root;
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
- unpacks `sucheckout-upayments/`;
- runs the pinned official `WordPress/plugin-check-action` against that exact package;
- uses slug `sucheckout-upayments` and `plugin_repo` checks;
- fails on blocking findings.

Passing this gate does **not** publish the plugin or guarantee manual directory approval.

## Remaining owner/admin/local work

After this final documentation-hardening PR is exact-green and merged, the remaining sequence is:

1. synchronize the owner's local clone to the final `origin/main`;
2. delete obsolete remote branches;
3. rename GitHub repository to `SimplixInnovations/sucheckout-upayments`;
4. update repository About metadata and local `origin`;
5. verify rulesets/security/integrations after rename;
6. create/merge one coordinate-only PR updating living old-repository URLs;
7. run the documented isolated local acceptance suite;
8. choose the first public version explicitly (`0.1.0` early release vs `1.0.0` first stable);
9. run a version-promotion PR if required;
10. tag/release/submit to WordPress.org only after explicit approval.

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

These entries are retained because permanent regression harnesses use them as audit evidence. They do not redefine the current SUCheckout program state.

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

Former SimplixPay/Q/Task phase records are retained as historical evidence. Do not rewrite historical SHAs, names or then-current claims to pretend those milestones originally occurred under SUCheckout.

Current truth precedence:

1. fresh live GitHub/source/provider evidence;
2. this `PROJECT-STATUS.md`;
3. `OWNER-HANDOFF.md` for owner/admin actions;
4. `NAMING-IDENTITY-STANDARD.md` for identity;
5. `RELEASE-ENGINEERING.md` for artifact/release contracts;
6. historical phase/quality records for milestone evidence only.
