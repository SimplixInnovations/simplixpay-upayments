# SUCheckout for UPayments — Owner Handoff

**Engineering migration:** DONE / VERIFIED  
**Runtime-bearing certified baseline:** `6aabc4fcb0606567a11637ea07fe081fed4c7f85`  
**Final control-plane closeout baseline:** `9591c431e1eb56fe40ca60147afdf9f3f909a212`  
**Current GitHub repository:** `SimplixInnovations/simplixpay-upayments`  
**Approved target repository:** `SimplixInnovations/sucheckout-upayments`  
**Development version:** `0.1.0`  
**Public release:** NOT YET PUBLISHED

This document is the authoritative owner/admin/local checklist after the SUCheckout engineering migration. It does not reopen Quality Platform Q1-Q19 and does not authorize publication by itself.

## Golden rule

Do not mechanically rename protected `upayments`, `_upay_*`, callback, token, subscription or provider-contract identifiers merely to make naming uniform. The canonical first-stable package is intentionally:

```text
sucheckout-upayments/UPayments.php
```

The technical slug/text domain/repository target is `sucheckout-upayments`. The word `for` belongs only in the human-facing name **SUCheckout for UPayments**.

---

# Part A — Required owner/admin repository actions

## A1. Bring the normal local clone up to date

Run from the existing repository clone before deleting branches or renaming GitHub.

### PowerShell

```powershell
cd C:\path\to\your\repo
git status --short
git branch --show-current
git fetch --prune --tags origin
git switch main
git pull --ff-only origin main
git status --short
git rev-parse HEAD
git rev-parse origin/main
```

### Git Bash

```bash
cd /path/to/your/repo
git status --short
git branch --show-current
git fetch --prune --tags origin
git switch main
git pull --ff-only origin main
git status --short
git rev-parse HEAD
git rev-parse origin/main
```

Do not use `git reset --hard` or `git clean -fdx` on the normal owner working copy merely to perform this handoff. If the working tree is not clean, preserve/commit/stash intentional work first.

Expected before proceeding:

- branch: `main`;
- `git status --short`: empty;
- `HEAD` equals `origin/main`.

## A2. Delete the two obsolete remote branches

These branches are superseded by certified `main`:

- `release/wordpress-org-submission-readiness`
- `enterprise/release-identity-migration-decision`

Run:

```bash
git push origin --delete release/wordpress-org-submission-readiness
git push origin --delete enterprise/release-identity-migration-decision
git fetch --prune origin
git branch -r
```

Expected remote result before repository rename:

```text
origin/HEAD -> origin/main
origin/main
```

If matching local branches exist, first make sure no worktree uses them:

```bash
git worktree list
```

Then delete only the obsolete local branches:

```bash
git branch -D release/wordpress-org-submission-readiness
git branch -D enterprise/release-identity-migration-decision
git worktree prune
```

If Git says a branch is checked out in another worktree, inspect that worktree before deleting anything. Do not force-remove an unknown worktree.

## A3. Rename the GitHub repository

In GitHub:

1. Open repository **Settings**.
2. Open **General**.
3. Under **Repository name**, change:

```text
simplixpay-upayments
```

to:

```text
sucheckout-upayments
```

4. Confirm the rename.
5. Re-open the repository from the new coordinate:

```text
SimplixInnovations/sucheckout-upayments
```

The repository ID/history should remain the same; the coordinate changes.

## A4. Update GitHub About metadata

Set:

**Description**

```text
SUCheckout for UPayments — independently engineered WooCommerce payment integration by Simplix Innovations.
```

**Homepage**

```text
https://simplixi.com
```

Keep that homepage until a dedicated canonical SUCheckout product page exists.

Remove retired topic:

```text
simplixpay
```

Recommended evidence-safe discovery topics:

```text
checkout-blocks
ecommerce
hpos
payment-gateway
payments
php
sucheckout
sucheckout-upayments
upayments
woocommerce
woocommerce-payment-gateway
wordpress
```

Do not add claim-like topics such as `wpml-ready`, `pci-compliant`, `accessibility-ready`, `all-wallets`, `refunds`, or `multicurrency` without separate evidence.

## A5. Update the local remote after GitHub rename

Run in the normal local clone:

```bash
git remote set-url origin https://github.com/SimplixInnovations/sucheckout-upayments.git
git remote -v
git fetch --prune --tags origin
git remote set-head origin -a
git branch -r
git status --short
```

Expected fetch/remote behavior must use the new canonical URL rather than relying on GitHub redirects from the old name.

## A6. Verify GitHub configuration after rename

Do not assume every repository-level integration followed the rename correctly. Verify:

- default branch remains `main`;
- squash merge remains the only allowed merge method;
- default-branch ruleset still blocks force-push/deletion and requires the intended checks;
- Actions are enabled;
- workflow permissions remain appropriately minimal;
- CodeQL/code scanning remains enabled;
- Dependabot version/security updates remain enabled;
- secret scanning/push protection remain enabled where available;
- Private Vulnerability Reporting remains configured as intended;
- repository secrets and environments still exist;
- webhooks/GitHub Apps/external integrations point at the renamed repo where they store explicit coordinates;
- About description/homepage/topics show SUCheckout, not SimplixPay;
- open issues/PRs are still as expected;
- only intended branches remain.

## A7. Tell ChatGPT the rename is complete

After A1-A6, provide the new repository URL or simply state that the rename to `SimplixInnovations/sucheckout-upayments` is complete.

A **small coordinate-only PR** is then required to update living repository links such as:

- README workflow badges/links;
- `AGENTS.md` current repository line;
- `PROJECT-STATUS.md` current repository line;
- `OWNER-HANDOFF.md` transition wording;
- `NOTICE.md` / `UPSTREAM.md` current source links where applicable;
- `.github/ISSUE_TEMPLATE/config.yml` links;
- other living GitHub URLs discovered by exact grep.

Do not blindly replace `simplixpay-upayments` repository-wide. The token remains valid in historical evidence and in the explicitly certified legacy package-root migration contract.

Recommended audit after rename:

```bash
git grep -n "SimplixInnovations/simplixpay-upayments"
git grep -n "github.com/SimplixInnovations/simplixpay-upayments"
git grep -n "simplixpay" -- ':!docs/history/**' ':!docs/superpowers/**'
```

Every hit must be classified as either:

- current repository coordinate → update;
- historical evidence → keep;
- legacy package/migration fixture → keep;
- obsolete first-party branding residue → remove/update.

---

# Part B — Independent local acceptance before release

GitHub CI already owns the authoritative cross-version matrix. Local acceptance is an independent owner verification of the exact canonical package and merchant-facing behavior.

## B1. Toolchain preflight

From Git Bash on Windows:

```bash
git --version
bash --version
php -v
composer --version
node --version
npm --version
wp --info
py -3 --version
```

Release scripts invoke `python3`. If Git Bash has only the Windows `py` launcher, create a temporary shell-local shim:

```bash
mkdir -p /tmp/sucheckout-python
cat > /tmp/sucheckout-python/python3 <<'EOF'
#!/usr/bin/env bash
exec py -3 "$@"
EOF
chmod +x /tmp/sucheckout-python/python3
export PATH="/tmp/sucheckout-python:$PATH"
python3 --version
```

Do not globally alter Windows Python just for this test.

## B2. Create an isolated acceptance worktree

Do not run destructive cleanup against the normal clone.

```bash
git fetch --prune --tags origin

SOURCE_REPO="$(git rev-parse --show-toplevel)"
ACCEPTANCE_DIR="${SOURCE_REPO}/../sucheckout-owner-acceptance"

test ! -e "$ACCEPTANCE_DIR"
git worktree add --detach "$ACCEPTANCE_DIR" origin/main
cd "$ACCEPTANCE_DIR"

git status --short
git rev-parse HEAD
git rev-parse origin/main
```

Expected:

- `git status --short` is empty;
- both SHA commands match exactly.

If the target directory already exists, choose another disposable path. Never delete an unknown directory just to reuse the name.

## B3. Development quality suite

Run:

```bash
composer install --no-interaction --prefer-dist
composer validate --strict
composer audit --locked
composer quality
```

Then run the permanent high-value SUCheckout contracts directly:

```bash
php tests/harness/sucheckout-identity-migration-harness.php
php tests/harness/sucheckout-namespace-migration-harness.php
php tests/harness/sucheckout-frontend-identity-harness.php
php tests/harness/sucheckout-residue-harness.php
php tests/harness/wordpress-org-runtime-harness.php
php tests/harness/wordpress-org-submission-harness.php
php tests/harness/sucheckout-http-transport-harness.php
php tests/harness/sucheckout-provenance-db-failure-harness.php
php tests/harness/phase-9g-h12-php-harness.php
node --check tests/harness/phase-9g-h12-blocks-harness.js
node tests/harness/phase-9g-h12-blocks-harness.js
```

Any failure is a release blocker until understood. Do not edit production code merely to silence a local environment mismatch; diagnose whether the failure is environment-specific or a real regression.

PHP 7.2 syntax CI is not PHP 7.2 runtime certification. Supported runtime floor remains PHP 7.4.

## B4. Build and verify the deterministic package

```bash
rm -rf dist
bash scripts/build-release.sh dist
bash scripts/verify-release.sh dist/sucheckout-upayments-0.1.0.zip
sha256sum dist/sucheckout-upayments-0.1.0.zip
cat dist/sucheckout-upayments-0.1.0.zip.sha256
```

The calculated SHA-256 must match the sidecar.

Treat this ZIP as a verification artifact until the public version/release decision is explicitly approved.

## B5. Inspect the package identity

Optional but recommended:

```bash
unzip -l dist/sucheckout-upayments-0.1.0.zip | head -80
```

Verify:

- one top-level `sucheckout-upayments/` directory;
- `sucheckout-upayments/UPayments.php` exists;
- `readme.txt` exists;
- no development-only `.git`, tests, CI or secret files are packaged;
- no second plugin root exists.

## B6. Install on a disposable local/staging WordPress + WooCommerce site

```bash
wp plugin install ./dist/sucheckout-upayments-0.1.0.zip --force
wp plugin activate sucheckout-upayments
wp plugin status sucheckout-upayments
```

Do not use a production store for first local acceptance.

## B7. Manual merchant/admin smoke

Verify all of the following:

### WordPress / plugin identity

- plugin displays **SUCheckout for UPayments**;
- no current UI presents SimplixPay as the product;
- plugin activates without PHP notices/warnings/fatals;
- WordPress identifies the installed root as `sucheckout-upayments`;
- the retained physical file `UPayments.php` causes no duplicate plugin entry.

### WooCommerce admin

- payment settings page loads cleanly;
- SUCheckout/UPayments settings save;
- reload confirms saved values;
- sensitive credentials are masked/not leaked;
- malformed/blank optional values do not create warnings;
- admin JS has no console errors.

### Classic checkout

- gateway registers under the protected payment ID `upayments`;
- enabled/disabled behavior matches settings;
- checkout renders without PHP/JS errors;
- failure states show safe user-facing messages;
- no secret/provider-token data appears in HTML or browser console.

### Cart / Checkout Blocks

- gateway registers and becomes available only when eligible;
- disabled/malformed settings fail closed;
- checkout JS has no errors;
- frontend text and styling remain usable.

### HPOS

If the disposable store can safely switch modes, smoke both:

- legacy order storage;
- HPOS authoritative storage.

Create an order in each mode and confirm SUCheckout payment metadata/order operations remain readable through WooCommerce APIs/admin.

### UPayments sandbox

Using non-production/test credentials only:

- initialize a bounded test payment;
- confirm the provider payment link/initial response is handled;
- exercise successful/failed/pending return paths only as supported by the sandbox;
- confirm browser return data alone never marks an order paid without provider-authenticated verification;
- confirm logs contain no secrets, full bearer tokens, card data or sensitive customer tokens.

### Saved-card / subscriptions / multi-merchant

Only test these when your sandbox/provider account and Woo setup support them:

- saved-card UI respects provenance/membership restrictions;
- guest/foreign/malformed token paths fail closed;
- subscription eligibility follows configured product/order rules;
- auto-deduction is not claimed as certified unless separately validated live;
- multi-merchant behavior remains limited to the certified single additional merchant.

## B8. Optional legacy-root migration smoke

Only if you have an actual old internal/pre-release `simplixpay-upayments` installation.

Back up the disposable database first.

```bash
wp plugin deactivate simplixpay-upayments
wp plugin install ./dist/sucheckout-upayments-0.1.0.zip --force
wp plugin activate sucheckout-upayments
```

Before deleting the old folder, verify:

- merchant settings retained;
- historical orders still show/read the expected payment method;
- protected metadata remains intact;
- token/provenance state remains intact;
- subscription metadata and scheduled events remain intact;
- callbacks/provider-order identities are unchanged.

Only after verification:

```bash
wp plugin delete simplixpay-upayments
wp plugin status sucheckout-upayments
```

## B9. Visual/accessibility/browser acceptance

Repository CI does not certify this. At minimum, manually check the actual target launch browser/theme combination for:

- desktop + mobile responsive checkout;
- keyboard navigation and visible focus;
- labels/error messages;
- contrast/readability;
- RTL/Arabic only if you intend to claim/support it at launch;
- no layout break in Classic and Blocks checkout;
- admin settings usability.

Broader browser/theme/accessibility certification remains a separate evidence track.

## B10. Remove the disposable acceptance worktree

After acceptance is complete:

```bash
cd "$SOURCE_REPO"
git worktree remove --force "$ACCEPTANCE_DIR"
git worktree prune
git worktree list
```

The `--force` is intentionally bounded to the disposable worktree created in B2 because Composer/build outputs may remain there. Never substitute the normal working-copy path.

---

# Part C — Release/version decision

Current development version is `0.1.0`. Do not create a tag merely because engineering gates are green.

Explicitly choose one of:

- **0.1.0** — early public/pre-1.0 release;
- **1.0.0** — first stable release.

If the release version changes, use a dedicated version-promotion PR and update all canonical surfaces together, including at minimum:

- `src/Release/Identity.php`;
- plugin header in `UPayments.php`;
- `readme.txt` stable tag + changelog;
- root `CHANGELOG.md`;
- README version badge/text;
- release documentation tied to the version.

That exact version-promotion head must pass all release-sensitive CI before merge, and merged `main` must be reverified before tagging.

---

# Part D — Public release / WordPress.org publication

Only after explicit owner approval and green exact-main evidence:

1. fetch the exact certified `main`;
2. build the deterministic artifact from that exact commit;
3. run `scripts/verify-release.sh`;
4. confirm ZIP SHA-256 equals the sidecar;
5. create `vX.Y.Z` on that exact certified commit;
6. create the GitHub Release using the verified ZIP/checksum/manifest;
7. submit/publish the exact verified package to WordPress.org under slug `sucheckout-upayments`;
8. verify public directory name, slug, stable tag, screenshots/assets and package contents;
9. install/upgrade from the actual public distribution channel on a disposable site;
10. run a post-publication checkout smoke.

Never publish:

- a mutable local working-tree build;
- a synthetic PR merge ref;
- a ZIP from a different SHA;
- a package with `sucheckout-for-upayments` technical identity;
- a package that changes protected persisted/provider IDs for branding uniformity.

---

# Part E — Explicit external/manual boundaries

Repository certification does not replace:

- production merchant payment completion evidence;
- real Apple Pay / Google Pay / Samsung Pay account/device completion;
- WPML/WCML/multilingual/multicurrency/RTL validation;
- broad browser/device/theme/accessibility certification;
- representative-store performance/load testing;
- penetration testing;
- PCI/legal/compliance attestation;
- live non-idempotent subscription auto-deduction evidence;
- provider webhook-signature verification until UPayments publishes a stable documented verification contract.

Automatic WooCommerce refunds and arbitrary marketplace multi-split remain unsupported.

---

# Owner completion checklist

## Repository/admin

- [ ] normal local clone clean and synchronized with `origin/main`
- [ ] obsolete remote branch `release/wordpress-org-submission-readiness` deleted
- [ ] obsolete remote branch `enterprise/release-identity-migration-decision` deleted
- [ ] GitHub repository renamed to `SimplixInnovations/sucheckout-upayments`
- [ ] GitHub About description/homepage/topics updated
- [ ] local `origin` updated to new repository URL
- [ ] ruleset/Actions/security/Dependabot/PVR/integrations verified after rename
- [ ] living old repository-coordinate references reconciled in a dedicated PR
- [ ] only intended branches remain
- [ ] no unexpected open PRs/issues remain

## Local acceptance

- [ ] isolated acceptance worktree created from exact `origin/main`
- [ ] Composer validation/audit/quality green
- [ ] focused SUCheckout/H12/WordPress.org harnesses green
- [ ] deterministic ZIP built and verified
- [ ] package structure manually inspected
- [ ] ZIP installed on disposable WordPress/WooCommerce site
- [ ] admin settings save/reload smoke green
- [ ] Classic checkout smoke green
- [ ] Blocks checkout smoke green
- [ ] HPOS/legacy smoke completed where feasible
- [ ] bounded UPayments sandbox initialization/return smoke completed
- [ ] logs/browser output checked for secrets/customer-token leakage
- [ ] optional legacy-root migration smoke completed if applicable
- [ ] intended launch browser/theme/mobile/accessibility smoke completed

## Publication

- [ ] first public version explicitly approved
- [ ] version-promotion PR completed if required
- [ ] exact merged `main` release-sensitive gates green
- [ ] exact deterministic ZIP/checksum/manifest verified
- [ ] tag/GitHub Release explicitly approved
- [ ] WordPress.org submission/publication explicitly approved
- [ ] public-channel install/upgrade smoke completed after publication
