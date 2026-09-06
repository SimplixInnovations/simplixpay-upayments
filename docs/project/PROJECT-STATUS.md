# SUCheckout for UPayments — Project Status
**Status document:** canonical living engineering state
**Last updated:** 2026-09-06
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
| Production maturity | **Pre-release / identity migration and exact-head re-certification** |
| Public stable release | **NO** |
| WordPress.org release | **NO** |
| Historical Quality Platform Q1-Q19 | **DONE / VERIFIED — numbered sequence permanently closed** |
| Historical Enterprise Tasks 1-8 | **DONE / VERIFIED evidence retained** |
| SUCheckout identity design | **APPROVED** |
| Namespace / metadata / text-domain migration | **IMPLEMENTED** |
| Deterministic canonical `sucheckout-upayments` package | **IMPLEMENTED — CI certification required on exact head** |
| Legacy-root → canonical-root migration regression | **IMPLEMENTED — CI certification required on exact head** |
| Official WordPress Plugin Check gate | **IMPLEMENTED — CI certification required on exact head** |
| Repository rename | **OWNER/ADMIN ACTION AFTER MERGE** |
| GitHub Release / tag / WordPress.org publication | **NOT AUTHORIZED BY THIS ENGINEERING MIGRATION** |
No Q20 is justified. The numbered Quality Platform remains closed at Q19. The active work is a bounded pre-release product-identity migration and re-certification, not a new Q phase.
## Canonical first-party identity
The approved SUCheckout identity is:
- human name: **SUCheckout for UPayments**;
- technical slug: `sucheckout-upayments`;
- WordPress text domain: `sucheckout-upayments`;
- PHP namespace root: `Simplixi\SUCheckout\UPayments`;
- deterministic ZIP: `sucheckout-upayments-X.Y.Z.zip`;
- deterministic package root: `sucheckout-upayments/`;
- physical bootstrap: `UPayments.php` retained as a bounded compatibility exception.
The repository itself remains under its old GitHub name until a separate owner/admin rename after merge. That temporary repository URL does not redefine the product/package identity.
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
The SUCheckout PR must rerun the permanent gates at its exact final head before this migration can be considered complete.
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
## Historical evidence
The former SimplixPay engineering records remain history, not current identity. In particular:
- Quality Platform Q1-Q19 stays closed and preserved;
- Enterprise Tasks 1-8 stay preserved as the pre-rebrand release-candidate foundation;
- earlier same-basename upgrade evidence remains the reason `UPayments.php` is retained;
- historical SHAs and workflow runs in phase records are not rewritten to pretend they were SUCheckout certifications.
## Current completion rule
The SUCheckout identity migration is complete only when the exact final PR head has:
- all permanent Quality/H12 gates green;
- Compatibility Certification green;
- deterministic Release Artifact certification green;
- legacy-root migration/rollback cells green;
- Provider Sandbox Certification green where applicable;
- official WordPress.org Plugin Check green with no blocking errors;
- CodeQL/security checks green;
- no unresolved critical/high review findings;
- living documentation reconciled with canonical SUCheckout identity;
- no unintended first-party SimplixPay residue outside historical/contextual records.
After a verified merge, the same required checks must pass on canonical `main`. Repository rename, public tag/release and WordPress.org publication remain separate owner/admin actions.