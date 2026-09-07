# SUCheckout for UPayments — Release Engineering
**Current status:** pre-release SUCheckout engineering migration merged and post-merge certified; owner release administration pending
**Current repository:** `SimplixInnovations/simplixpay-upayments`
**Planned repository after owner/admin rename:** `SimplixInnovations/sucheckout-upayments`
## Latest certified release-engineering baseline

Current runtime-bearing certified `main` after PR #61 final first-party naming reconciliation: `efe937c67343242b7ccf3396a67b3cf2ce35ebac`.

Fresh exact-main evidence:

- Release Artifact #258 — **SUCCESS**;
- packaged WordPress 7.1 / WooCommerce 11.1.0 / PHP 8.3 legacy — **SUCCESS**;
- packaged WordPress 7.1 / WooCommerce 11.1.0 / PHP 8.3 HPOS — **SUCCESS**;
- legacy-root migration WordPress 7.1 / WooCommerce 11.1.0 — **SUCCESS**;
- legacy-root migration WordPress 6.9.7 / WooCommerce 10.8.1 — **SUCCESS**;
- Compatibility #309 — **16/16 SUCCESS**;
- WordPress.org Submission Check #116 — **SUCCESS**;
- Provider Sandbox #221 — **SUCCESS**;
- CodeQL/main-security #595 — **SUCCESS**;
- official packaged Plugin Check — **0 blocking errors**.

Earlier SUCheckout identity merge `6aabc4fcb0606567a11637ea07fe081fed4c7f85` and living-state closeout `9591c431e1eb56fe40ca60147afdf9f3f909a212` remain historical provenance. PR #61 is the latest runtime-bearing first-party naming baseline.

This evidence certifies the engineering artifact and migration contract. It does not create a public tag, GitHub Release or WordPress.org publication.

## Canonical package contract
The approved first-party release identity is:
- package root: `sucheckout-upayments/`;
- physical main file: `UPayments.php`;
- plugin basename: `sucheckout-upayments/UPayments.php`;
- text domain: `sucheckout-upayments`;
- namespace: `Simplixi\SUCheckout\UPayments`;
- current development artifact: `sucheckout-upayments-0.1.0.zip`.
The retained `UPayments.php` filename is deliberate. Earlier real-install qualification proved that directly renaming an already-active physical main file could strand WordPress's persisted plugin basename. The SUCheckout migration therefore changes the first-party package root, metadata, namespace and text domain while retaining that qualified bootstrap filename.
## Deterministic artifact contract
Build and verify the canonical artifact with:
```bash
bash scripts/build-release.sh dist
bash scripts/verify-release.sh dist/sucheckout-upayments-0.1.0.zip
```
The builder/verifier contract requires:
- distribution policy, path set and bytes from the exact Git `HEAD` tree/blobs;
- no dependence on mutable worktree or staged-index state;
- sorted archive paths;
- fixed archive timestamps and regular-file modes;
- deterministic DEFLATE settings within the defined toolchain;
- ZIP SHA-256 sidecar;
- sorted per-file SHA-256 manifest;
- explicit release-path allowlist;
- exactly one `sucheckout-upayments/` ZIP root;
- exact source-byte verification;
- rejection of a self-consistent/rehashed ZIP whose bytes diverge from Git HEAD;
- reproducible byte-identical output from the same source commit.
Development/control surfaces such as `.github/`, `tests/`, `docs/`, `scripts/`, `vendor/`, Composer development metadata and analysis configs are excluded.
## Packaged runtime certification
Release Artifact CI builds one exact candidate artifact, verifies it, transfers it through pinned upload/download actions, verifies it again, then installs that ZIP into real WordPress/WooCommerce.
Permanent package smoke includes:
- activation and Classic gateway registration;
- release support metadata and Woo feature declarations;
- Blocks registration/availability;
- real Woo order CRUD with legacy authoritative storage;
- real Woo order CRUD with HPOS authoritative storage.
The canonical packaged plugin is activated by WordPress slug `sucheckout-upayments` while the protected WooCommerce gateway/payment identity remains `upayments`.
## Pre-release legacy-root migration certification
Changing the package root from `simplixpay-upayments/` to `sucheckout-upayments/` changes the WordPress plugin basename. The release workflow therefore does not classify this as an invisible same-basename upgrade.
Two real-runtime migration cells are permanent:
- WordPress 7.1 / WooCommerce 11.1.0 / PHP 8.3;
- WordPress 6.9.7 / WooCommerce 10.8.1 / PHP 8.3.
Each cell:
1. builds the prior certified pre-rebrand package from historical source `54b1fbcc280b92372bd93baf929d6a746cfd3959`;
2. installs/activates it as `simplixpay-upayments/UPayments.php`;
3. seeds protected merchant settings, historical payment/order/token/subscription metadata and cron state;
4. deactivates the legacy plugin;
5. installs and activates canonical `sucheckout-upayments/UPayments.php`;
6. proves settings/data/provider IDs/callback/cron continuity;
7. proves rollback to the legacy package is non-destructive;
8. returns to canonical SUCheckout;
9. removes the inactive legacy package;
10. re-verifies canonical runtime and retained data.
This is the release path being certified for any internal/pre-release installation that used the old root. It is not represented as a transparent WordPress auto-update across plugin basenames.
## Protected compatibility contracts
Release identity migration must preserve:
- gateway/payment method ID `upayments`;
- settings option `woocommerce_upayments_settings`;
- callback route `wc_upayments`;
- Blocks / Store API identity `upayments`;
- historical `_upay_*` metadata;
- provider order/token provenance identities;
- cron hook `upay_process_subscriptions` and billing-attempt state;
- historical order payment-method values.
These are provider/data compatibility contracts, not first-party brand residue.
## WordPress.org submission gate
`.github/workflows/wordpress-org-submission-check.yml` is a permanent pre-submission gate. It:
- checks out the exact candidate head;
- runs the permanent submission harness;
- builds the deterministic canonical ZIP;
- verifies the ZIP before inspection;
- unpacks `sucheckout-upayments/`;
- runs the pinned official `WordPress/plugin-check-action` with slug `sucheckout-upayments` and `plugin_repo` categories.
A green submission check is necessary evidence but does not publish anything.
## Historical engineering evidence
Historical Task 5 established the deterministic Git-HEAD-bound packaging model. Historical Task 7 established same-basename data continuity and, crucially, the negative proof that an active physical bootstrap rename is unsafe. Historical Task 8 closed the pre-rebrand enterprise release-candidate program.
Those records remain historical truth. They are not rewritten to claim that the old `simplixpay-upayments` package/text-domain was already SUCheckout.
## Release evidence boundary
CI artifacts are verification artifacts, not public releases. The following remain separate owner/admin actions now that verified merge and post-merge certification are complete:
- repository rename to `SimplixInnovations/sucheckout-upayments`;
- public version/tag promotion;
- GitHub Release creation;
- WordPress.org submission/publication.
Do not publish an artifact from a synthetic PR merge ref, mutable worktree, unreviewed commit or a source head whose mandatory gates are not all green. Follow `OWNER-HANDOFF.md` for rename/local acceptance/release-administration order.