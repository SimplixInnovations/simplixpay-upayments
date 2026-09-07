# SUCheckout for UPayments — Release Engineering

**Current status:** pre-release SUCheckout engineering migration merged and certified; owner repository rename/local acceptance/release administration pending  
**Current repository pending rename:** `SimplixInnovations/simplixpay-upayments`  
**Approved repository target:** `SimplixInnovations/sucheckout-upayments`  
**Development version:** `0.1.0`

## Certification anchors

### Runtime-bearing release baseline

PR #58 certified head:

`5bf84dccb880733da45c1f922d43554af69a33dc`

Runtime-bearing merge:

`6aabc4fcb0606567a11637ea07fe081fed4c7f85`

Post-merge evidence:

- Quality #764 — **SUCCESS**;
- Compatibility #292 — **16/16 SUCCESS**;
- Release Artifact #243 — **SUCCESS**;
- packaged WordPress 7.1 / WooCommerce 11.1.0 / PHP 8.3 legacy — **SUCCESS**;
- packaged WordPress 7.1 / WooCommerce 11.1.0 / PHP 8.3 HPOS — **SUCCESS**;
- legacy-root migration WordPress 7.1 / WooCommerce 11.1.0 — **SUCCESS**;
- legacy-root migration WordPress 6.9.7 / WooCommerce 10.8.1 — **SUCCESS**;
- WordPress.org Submission Check #101 — **SUCCESS**;
- Provider Sandbox #207 — **SUCCESS**;
- CodeQL/main-security #579 — **SUCCESS**;
- official packaged Plugin Check — **0 blocking errors**.

### Final control-plane closeout

PR #59 merged as:

`9591c431e1eb56fe40ca60147afdf9f3f909a212`

Fresh push-triggered evidence on that exact `main` SHA:

- Quality #773 — **SUCCESS**;
- Compatibility #301 — **all 16 cells SUCCESS**;
- Release Artifact #252 — **SUCCESS**;
- Provider Sandbox #216 — **SUCCESS**;
- WordPress.org Submission Check #110 — **SUCCESS**;
- CodeQL/main-security #588 — **SUCCESS**.

This evidence certifies the engineering artifact and migration contract. It does not create a public tag, GitHub Release or WordPress.org publication.

## Canonical package contract

The first-stable release identity is:

- human product: **SUCheckout for UPayments**;
- package root: `sucheckout-upayments/`;
- physical main file: `UPayments.php`;
- plugin basename: `sucheckout-upayments/UPayments.php`;
- text domain: `sucheckout-upayments`;
- namespace: `Simplixi\SUCheckout\UPayments`;
- current development artifact: `sucheckout-upayments-0.1.0.zip`.

The retained `UPayments.php` filename is deliberate. Real-install qualification proved that directly renaming an already-active physical main file can strand WordPress's persisted plugin basename.

A future physical filename `sucheckout-upayments.php` is a separately gated migration target, not a first-stable requirement.

## Deterministic artifact contract

Build and verify:

```bash
bash scripts/build-release.sh dist
bash scripts/verify-release.sh dist/sucheckout-upayments-0.1.0.zip
sha256sum dist/sucheckout-upayments-0.1.0.zip
cat dist/sucheckout-upayments-0.1.0.zip.sha256
```

The builder/verifier requires:

- distribution path set and bytes from exact Git `HEAD` tree/blobs;
- no dependence on mutable worktree/staged-index state;
- sorted archive paths;
- fixed timestamps and file modes;
- deterministic compression settings within the defined toolchain;
- ZIP SHA-256 sidecar;
- sorted per-file SHA-256 manifest;
- explicit release-path allowlist;
- exactly one `sucheckout-upayments/` ZIP root;
- exact source-byte verification;
- rejection of a rehashed/self-consistent ZIP whose bytes diverge from Git HEAD;
- reproducible byte-identical output from the same source commit.

Development/control surfaces such as `.github/`, `tests/`, `docs/`, `scripts/`, Composer development metadata and analysis configs are excluded according to `.distignore` and the release allowlist.

## Packaged runtime certification

Release Artifact CI builds one exact candidate artifact, verifies it, transfers it through pinned upload/download actions, verifies it again, then installs that exact ZIP into real WordPress/WooCommerce.

Permanent packaged smoke includes:

- activation and Classic gateway registration;
- release support metadata and Woo feature declarations;
- Blocks registration/availability;
- real Woo order CRUD with legacy authoritative storage;
- real Woo order CRUD with HPOS authoritative storage.

The WordPress plugin is installed under slug `sucheckout-upayments` while the protected WooCommerce gateway/payment identity remains `upayments`.

## Pre-release legacy-root migration certification

Changing package root from `simplixpay-upayments/` to `sucheckout-upayments/` changes the WordPress plugin basename. This is therefore not represented as an invisible same-basename auto-update.

Permanent migration cells include:

- WordPress 7.1 / WooCommerce 11.1.0 / PHP 8.3;
- WordPress 6.9.7 / WooCommerce 10.8.1 / PHP 8.3.

Each cell:

1. builds the prior certified pre-rebrand package from historical source `54b1fbcc280b92372bd93baf929d6a746cfd3959`;
2. installs/activates it as `simplixpay-upayments/UPayments.php`;
3. seeds protected merchant settings, order/payment/token/subscription metadata and cron state;
4. deactivates the legacy package;
5. installs/activates canonical `sucheckout-upayments/UPayments.php`;
6. verifies settings/data/provider IDs/callback/cron continuity;
7. proves rollback to the legacy package remains non-destructive;
8. returns to canonical SUCheckout;
9. removes the inactive legacy package;
10. re-verifies canonical runtime and retained data.

This is the certified path for internal/pre-release installations that used the old root.

## Protected compatibility contracts

Release rebranding must preserve, unless a future separately proven migration explicitly supersedes them:

- gateway/payment method ID `upayments`;
- settings option `woocommerce_upayments_settings`;
- callback route `wc_upayments`;
- Blocks / Store API identity `upayments`;
- historical `_upay_*` metadata;
- provider-order/token provenance identities;
- `upayments_token_identity_secret_v2` and related provenance/scope/generation state;
- cron hook `upay_process_subscriptions`;
- billing-attempt state/table;
- historical order payment-method values.

These are provider/data compatibility contracts, not first-party branding residue.

## WordPress.org submission gate

`.github/workflows/wordpress-org-submission-check.yml` is a permanent pre-submission gate. It:

- checks out the exact candidate head;
- runs the permanent submission harness;
- builds the deterministic canonical ZIP;
- verifies the ZIP before inspection;
- unpacks `sucheckout-upayments/`;
- runs the pinned official `WordPress/plugin-check-action` with slug `sucheckout-upayments` and `plugin_repo` checks;
- fails on blocking findings.

No blanket Plugin Check ignore list is allowed.

A green submission check is necessary engineering evidence but does not publish anything and does not guarantee WordPress.org manual-review approval.

## Version-promotion contract

The repository remains at development version `0.1.0` until the owner explicitly chooses the first public version.

If the version changes, use a dedicated PR and update all canonical version surfaces together, including at minimum:

- `src/Release/Identity.php`;
- plugin header in `UPayments.php`;
- `readme.txt` stable tag/changelog;
- root `CHANGELOG.md`;
- README version badge/text;
- release documentation tied to the version.

The exact version-promotion head must pass the complete release-sensitive stack. Merged `main` must then be reverified before tagging.

## Public release contract

Only after explicit owner approval:

1. fetch exact certified `main`;
2. build deterministic ZIP from that commit;
3. verify ZIP with `scripts/verify-release.sh`;
4. verify ZIP SHA-256 against sidecar;
5. create `vX.Y.Z` on that exact commit;
6. create GitHub Release with verified ZIP/checksum/manifest;
7. submit/publish that exact package to WordPress.org under slug `sucheckout-upayments`;
8. verify the public directory metadata/package/version;
9. perform post-publication install/upgrade smoke from the real public channel.

Do not publish an artifact from:

- a synthetic PR merge ref;
- a mutable local worktree;
- an unreviewed commit;
- a different SHA than the tag;
- a candidate with mandatory checks still pending/failed/skipped.

## Repository-rename boundary

The GitHub repository coordinate remains temporarily `SimplixInnovations/simplixpay-upayments` until owner/admin rename.

After rename to `SimplixInnovations/sucheckout-upayments`, update only current repository-coordinate references in a dedicated PR. Do not rewrite historical evidence or legacy migration fixtures where `simplixpay-upayments` is semantically required.

## Historical evidence

Historical Task 5 established deterministic Git-HEAD-bound packaging. Historical Task 7 established same-basename continuity and the negative proof that a physical bootstrap rename is unsafe. Historical Task 8 closed the pre-rebrand enterprise release-candidate program.

These records remain historical truth. They are not rewritten to claim that old `simplixpay-upayments` identities were already SUCheckout.

## Release evidence boundary

CI artifacts are verification artifacts, not public releases.

Remaining owner/admin actions are controlled by `OWNER-HANDOFF.md`:

- delete obsolete remote branches;
- rename repository;
- reconcile living repository coordinates;
- perform independent local acceptance;
- explicitly choose version/publication;
- tag/release/submit only after exact-main verification.
