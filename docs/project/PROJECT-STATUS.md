# SUCheckout for UPayments — Project Status
**Status document:** canonical living engineering state
**Last updated:** 2026-09-07
**Current repository:** `SimplixInnovations/simplixpay-upayments`
**Planned canonical repository after owner/admin rename:** `SimplixInnovations/sucheckout-upayments`
> Live GitHub/source evidence wins over recorded SHAs. Historical phase records preserve what was true at their close; this file owns the current state.
## Current program state
| Item | State |
|---|---|
| Product | **SUCheckout for UPayments** |
| Canonical technical slug | `sucheckout-upayments` |
| WordPress text domain | `sucheckout-upayments` |
| Composer namespace | `Simplixi\SUCheckout\UPayments` |
| Current development version | **0.1.0** |
| Production maturity | **Pre-release / SUCheckout migration merged and post-merge certified** |
| Public stable release | **NO** |
| WordPress.org release | **NO** |
| Historical Quality Platform Q1-Q19 | **DONE / VERIFIED — numbered sequence permanently closed** |
| Historical Enterprise Tasks 1-8 | **DONE / VERIFIED evidence retained** |
| SUCheckout identity design | **APPROVED** |
| Namespace / metadata / text-domain migration | **DONE / VERIFIED** |
| Deterministic canonical `sucheckout-upayments` package | **DONE / VERIFIED — permanent exact-head gate** |
| Legacy-root → canonical-root migration regression | **DONE / VERIFIED — permanent exact-head gate** |
| Official WordPress Plugin Check gate | **DONE / VERIFIED — 0 blocking errors on certified merged main** |
| Repository rename | **OWNER/ADMIN ACTION — READY** |
| GitHub Release / tag / WordPress.org publication | **NOT PERFORMED — separate owner release decision** |
No Q20 is justified. The numbered Quality Platform remains closed at Q19. The SUCheckout engineering migration is complete; remaining pre-release work is owner-controlled repository cleanup/rename, optional local acceptance, and a separate release/publication decision.
## SUCheckout post-merge certification

Engineering identity migration is **DONE / VERIFIED**.

- PR #58 certified head: `5bf84dccb880733da45c1f922d43554af69a33dc`;
- squash merge on `main`: `6aabc4fcb0606567a11637ea07fe081fed4c7f85`;
- post-merge Quality Gates #764 — **SUCCESS**;
- Compatibility Certification #292 — **16/16 SUCCESS**;
- Release Artifact #243 — **SUCCESS**;
- Provider Sandbox Certification #207 — **SUCCESS**;
- WordPress.org Submission Check #101 — **SUCCESS**;
- CodeQL/main-security #579 — **SUCCESS**;
- official packaged Plugin Check — **0 blocking errors**;
- SUCheckout Production HTTP Transport — **27 PASS / 0 FAIL**;
- SUCheckout Provenance DB Failure — **3 PASS / 0 FAIL**;
- SUCheckout Residue — **17 PASS / 0 FAIL**;
- post-closeout open issues / open PRs / unresolved review threads — **0 / 0 / 0**.

The two older remote branches `release/wordpress-org-submission-readiness` and `enterprise/release-identity-migration-decision` remain owner cleanup because the available automation connector cannot delete branches. Their earlier work is superseded by certified `main`.

## Canonical first-party identity
The approved SUCheckout identity is:
- human name: **SUCheckout for UPayments**;
- technical slug: `sucheckout-upayments`;
- WordPress text domain: `sucheckout-upayments`;
- PHP namespace root: `Simplixi\SUCheckout\UPayments`;
- deterministic ZIP: `sucheckout-upayments-X.Y.Z.zip`;
- deterministic package root: `sucheckout-upayments/`;
- physical bootstrap: `UPayments.php` retained as a bounded compatibility exception.
The repository itself remains under its old GitHub name until the owner/admin rename. That temporary repository URL does not redefine the product/package identity.
## Protected compatibility identities
The rebrand must not mechanically rename provider-facing or persisted merchant identities. The protected set includes:
- WooCommerce gateway/payment-method ID `upayments`;
- settings option `woocommerce_upayments_settings`;
- Blocks / Store API payment identity `upayments`;
- callback route `wc_upayments`;
- historical `_upay_*` order/subscription metadata;
- provider order identity such as `UPayments_order_id`;
- token/provenance identities including `upayments_token_identity_secret_v2`;
- cron hook `upay_process_subscriptions` and billing-attempt state;
- historical order payment-method value `upayments`.
These remain compatibility contracts unless a future explicit migration proves otherwise.
## Physical bootstrap decision
Historical Task 7 qualification proved that directly renaming an already-active `UPayments.php` bootstrap could strand WordPress's stored plugin basename and leave runtime unloaded. The approved SUCheckout design therefore does **not** silently rename the physical bootstrap in the first stable package.
The canonical package instead becomes:
`sucheckout-upayments/UPayments.php`
This preserves the qualified main filename while moving first-party package root, product metadata, namespace and text domain to SUCheckout.
## Existing pre-release installation migration
Because changing the package root changes the WordPress plugin basename, the SUCheckout release certification does not pretend the transition is an in-place same-basename upgrade.
The permanent release workflow now characterizes the real pre-release path:
1. install and activate a legacy `simplixpay-upayments/UPayments.php` candidate;
2. seed merchant settings, historical order/payment data, tokens/subscription metadata and cron state;
3. deactivate the legacy package;
4. install and activate canonical `sucheckout-upayments/UPayments.php`;
5. prove the protected merchant/provider data contracts survive unchanged;
6. prove rollback to the legacy package remains non-destructive;
7. return to canonical SUCheckout;
8. delete the inactive legacy package;
9. re-verify canonical runtime and retained data.
This migration is tested in both the current and supported-floor WordPress/WooCommerce runtime combinations used by release certification.
## Platform and provider evidence retained from the enterprise program
The historical enterprise evidence remains authoritative for the protected runtime behavior that was not intentionally changed by the identity migration:
- WordPress 6.9 series through 7.1 in exact supported cells;
- WooCommerce 10.8 series through 11.1 in exact supported cells;
- PHP 7.4, 8.3 and 8.4 in exact runtime cells;
- Classic gateway registration;
- Cart / Checkout Blocks registration and availability;
- legacy and HPOS authoritative order CRUD;
- `cart_checkout_blocks` and `custom_order_tables` compatibility declarations;
- bounded public UPayments sandbox Charge initialization;
- saved-card/token provenance boundaries;
- subscription eligibility/pre-dispatch boundaries;
- one additional-merchant allocation boundary;
- non-destructive activation/deactivation/uninstall retention;
- deterministic source-bound release packaging.
PR #58 and the resulting merged `main` independently reran the permanent gates successfully; those gates remain mandatory for future candidates.
## Canonical release engineering contract
The current release tooling must produce only:
- `sucheckout-upayments-0.1.0.zip` for the current development version;
- one `sucheckout-upayments/` package root;
- a SHA-256 ZIP sidecar;
- a sorted per-file SHA-256 manifest;
- bytes taken exclusively from Git `HEAD` according to `.distignore`;
- reproducible byte-identical output from the same source commit.
The verifier must reject unsafe paths, forbidden development/control files, source-divergent bytes and malformed checksum/manifest evidence.
The release workflow additionally installs the exact package into real WordPress/WooCommerce, exercises legacy and HPOS storage, and runs the legacy-root migration/rollback certification.
## WordPress.org readiness
A permanent `WordPress.org Submission Check` workflow now owns the directory-specific gate. It must:
- build the canonical deterministic package;
- verify it before use;
- unpack `sucheckout-upayments/`;
- run the official pinned `WordPress/plugin-check-action` against that exact packaged artifact;
- use slug `sucheckout-upayments` and `plugin_repo` categories;
- fail on blocking Plugin Check findings.
Passing this gate does **not** publish the plugin. WordPress.org submission remains a separate owner action.
## External/manual or intentionally unsupported boundaries
These are not repository blockers that should be fabricated away:
- production merchant payment completion and production credentials;
- wallet completion across real provider accounts/devices;
- WPML/WCML, multilingual, multicurrency and RTL certification;
- broad browser/device/theme/accessibility certification;
- store-specific performance/load thresholds;
- penetration testing, PCI or legal/compliance attestation;
- provider webhook signature trust until UPayments publishes a stable contract;
- live subscription auto-deduction;
- automatic WooCommerce refunds;
- arbitrary marketplace multi-split routing.
## Historical Quality Platform closure ledger

The following rows and evidence are retained solely as historical closure records. They do **not** redefine the current SUCheckout program state above.

| Historical gate | Closure |
|---|---|
| Quality Platform Q16 migration-core analysis | **DONE / VERIFIED** |
| Quality Platform Q17 payment-runtime analysis | **DONE / VERIFIED** |

Historical Q16 closure evidence retained verbatim for permanent regression ownership:

- `3cff2fcc64053d79be7427696c86039f1b52bbfd`;
- `b9cc6eafb3c7f8df36b9c5db8b2e45bb330688d2`;
- `06a9ebd732c7cc3f062d4bb361aaef4054a1dfa3`;
- Quality Gates run #315;
- Quality Gates run #316;
- 160 tests / 987 assertions;
- Q16 Migration Core Analysis: **120/0**;
- implementation branch `quality/migration-core-analysis`: **deleted after verified merge**.

## Task 8 — DONE / VERIFIED (historical pre-rebrand release-candidate closeout)

Enterprise Task 8 qualified the former product identity as an enterprise release candidate before the approved SUCheckout identity migration. That qualification remains historical evidence; SUCheckout was later independently certified through PR #58 and merged-main verification recorded above.

## Historical evidence
The former SimplixPay engineering records remain history, not current identity. In particular:
- Quality Platform Q1-Q19 stays closed and preserved;
- Enterprise Tasks 1-8 stay preserved as the pre-rebrand release-candidate foundation;
- earlier same-basename upgrade evidence remains the reason `UPayments.php` is retained;
- historical SHAs and workflow runs in phase records are not rewritten to pretend they were SUCheckout certifications.
## Current completion rule
The SUCheckout engineering identity migration has satisfied its completion rule: exact-head PR certification, squash merge, fresh merged-main verification, whole-PR review, living-identity reconciliation and zero unresolved critical/high review findings.

Future code candidates must repeat the permanent gates appropriate to their scope. The remaining actions are explicitly outside this engineering migration:

1. delete the two obsolete remote branches;
2. rename the GitHub repository to `SimplixInnovations/sucheckout-upayments`;
3. reconcile repository-coordinate links after the rename;
4. optionally run the documented owner-local acceptance suite;
5. make a separate explicit version/tag/GitHub Release/WordPress.org publication decision.

See `docs/project/OWNER-HANDOFF.md`.