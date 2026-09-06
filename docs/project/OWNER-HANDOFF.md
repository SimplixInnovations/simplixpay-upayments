# SUCheckout for UPayments — Owner Handoff
**Engineering migration:** DONE / VERIFIED
**Certified runtime-bearing main baseline:** `6aabc4fcb0606567a11637ea07fe081fed4c7f85`
**Current GitHub repository:** `SimplixInnovations/simplixpay-upayments`
**Target repository:** `SimplixInnovations/sucheckout-upayments`
**Current development version:** `0.1.0`
**Public release:** NOT YET PUBLISHED
This document owns the remaining owner/admin actions after the SUCheckout engineering migration. It does not reopen Quality Platform Q1-Q19 or create a new engineering phase.
## 1. Required repository cleanup
Two obsolete remote branches remain after the certified SUCheckout merge:
- `release/wordpress-org-submission-readiness`
- `enterprise/release-identity-migration-decision`
Their earlier work is superseded by certified `main`. Delete them from a clean local clone:
```bash
git fetch --prune origin
git switch main
git pull --ff-only origin main
git push origin --delete release/wordpress-org-submission-readiness
git push origin --delete enterprise/release-identity-migration-decision
git fetch --prune origin
git branch -r
```
If matching local branches exist and are not checked out by another worktree:
```bash
git worktree list
git branch -D release/wordpress-org-submission-readiness 2>/dev/null || true
git branch -D enterprise/release-identity-migration-decision 2>/dev/null || true
git worktree prune
```
Expected remote result before the repository rename: only `origin/main` plus `origin/HEAD -> origin/main`.
## 2. Required GitHub repository rename
Rename the repository in GitHub:
```text
SimplixInnovations/simplixpay-upayments
→
SimplixInnovations/sucheckout-upayments
```
GitHub UI:
1. Open repository **Settings**.
2. Under **General → Repository name**, enter `sucheckout-upayments`.
3. Confirm the rename.
4. Re-open the repository from the new URL before making additional changes.
After the rename, update the GitHub **About** metadata to the canonical product identity:
- repository description: `SUCheckout for UPayments — independently engineered WooCommerce payment integration by Simplix Innovations.`;
- homepage: keep `https://simplixi.com` until a dedicated canonical SUCheckout product page exists;
- remove retired topic `simplixpay`;
- recommended discovery topics: `checkout-blocks`, `ecommerce`, `hpos`, `payment-gateway`, `payments`, `php`, `sucheckout`, `sucheckout-upayments`, `upayments`, `woocommerce`, `woocommerce-payment-gateway`, `wordpress`;
- do not use compatibility-claim topics such as `wpml-ready` unless separately certified.
The metadata audit before rename found a pre-rebrand product description and the retired `simplixpay` topic; both must be replaced during the owner rename.
After the rename, verify rather than assume:
- default branch is still `main`;
- the default-branch ruleset still requires the intended checks and squash-only history policy;
- Actions are enabled and workflow permissions remain read-minimal where configured;
- CodeQL/security scanning remains enabled;
- Dependabot settings remain enabled;
- Private Vulnerability Reporting remains configured as intended;
- repository secrets/environments, webhooks, GitHub Apps and external integrations still point at the correct repository;
- repository topics/description/homepage still describe SUCheckout accurately.
## 3. Required local remote update after rename
In the local clone:
```bash
git remote set-url origin https://github.com/SimplixInnovations/sucheckout-upayments.git
git remote -v
git fetch --prune --tags origin
git remote set-head origin -a
git branch -r
```
Do not create a fresh clone merely because GitHub can redirect the old URL. Updating the canonical remote removes ambiguity.
## 4. Required post-rename repository-coordinate reconciliation
After the GitHub rename, create a small dedicated branch/PR from fresh `main` and update only **current repository-coordinate references**.
Start with:
```bash
git grep -n "SimplixInnovations/simplixpay-upayments"
git grep -n "github.com/SimplixInnovations/simplixpay-upayments"
```
Expected living surfaces include README workflow badges/links, `AGENTS.md`, `PROJECT-STATUS.md`, this handoff, `NOTICE.md`, `UPSTREAM.md`, and current issue-template links such as `.github/ISSUE_TEMPLATE/config.yml`.
Also re-run:
```bash
git grep -n "simplixpay" -- ':!docs/history/**' ':!docs/superpowers/**'
```
Classify every hit. Historical evidence, the explicit legacy package-root migration, and protected persisted/provider compatibility contracts may remain; current first-party branding must not.
Do **not** blindly replace the token `simplixpay-upayments` repository-wide. It is still intentionally required in historical/pre-release package-root migration evidence such as:
- legacy package basename `simplixpay-upayments/UPayments.php`;
- migration/rollback test fixtures;
- historical source/branch/evidence records.
After the coordinate-only PR, rerun all workflows triggered by the changed files and merge only from an exact green head.
## 5. Recommended independent local acceptance
GitHub CI already certified the merged runtime baseline. Local acceptance is recommended as an independent owner check before publication; it does not replace CI.
### 5.1 Windows / Git Bash preflight
From Git Bash:
```bash
git --version
bash --version
php -v
composer --version
node --version
npm --version
py -3 --version
```
The release scripts invoke `python3`. If Git Bash has only the Windows `py` launcher, create a temporary shim for the current shell:
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
### 5.2 Isolated canonical acceptance worktree
Do not reset or clean the owner's normal working copy for acceptance. Create a disposable detached worktree from the exact fetched `origin/main` instead; this remains safe even when the normal clone contains local tracked changes or local-only commits.
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
Expected `git status --short` output: empty. The two SHA commands must match.

If the acceptance directory already exists, choose a different disposable path; do not delete an unknown directory merely to reuse the name.
### 5.3 Development quality
```bash
composer install --no-interaction --prefer-dist
composer validate --strict
composer audit --locked
composer quality
```
Then run the permanent high-value standalone contracts:
```bash
php tests/harness/sucheckout-identity-migration-harness.php
php tests/harness/sucheckout-namespace-migration-harness.php
php tests/harness/sucheckout-frontend-identity-harness.php
php tests/harness/sucheckout-residue-harness.php
php tests/harness/wordpress-org-runtime-harness.php
php tests/harness/sucheckout-http-transport-harness.php
php tests/harness/sucheckout-provenance-db-failure-harness.php
php tests/harness/phase-9g-h12-php-harness.php
node --check tests/harness/phase-9g-h12-blocks-harness.js
node tests/harness/phase-9g-h12-blocks-harness.js
```
Do not interpret PHP 7.2 syntax CI as PHP 7.2 runtime certification. The real supported runtime floor remains PHP 7.4 as documented in `docs/COMPATIBILITY.md`.
### 5.4 Deterministic artifact build and verification
```bash
rm -rf dist
bash scripts/build-release.sh dist
bash scripts/verify-release.sh dist/sucheckout-upayments-0.1.0.zip
sha256sum dist/sucheckout-upayments-0.1.0.zip
cat dist/sucheckout-upayments-0.1.0.zip.sha256
```
The generated ZIP must be treated as a verification artifact until a separate release/version decision is approved.
### 5.5 Optional real local WordPress acceptance
On a disposable local/staging WordPress + WooCommerce site:
```bash
wp plugin install ./dist/sucheckout-upayments-0.1.0.zip --force
wp plugin activate sucheckout-upayments
wp plugin status sucheckout-upayments
```
Recommended manual smoke:
- WooCommerce payment settings page loads without PHP/JS errors;
- SUCheckout/UPayments settings save and reload;
- Classic checkout gateway registration;
- Cart/Checkout Blocks gateway availability;
- HPOS enabled and disabled smoke if your local store supports switching safely;
- test/sandbox payment initialization using only non-production credentials;
- successful/failed/pending return handling without trusting browser data as financial truth;
- saved-card UI only if the provider account supports it;
- subscription eligibility UI only if the required Woo/provider setup exists;
- no secrets/tokens/customer data appear in logs.
### 5.6 Optional legacy-root migration smoke
Only if you actually have an old internal/pre-release `simplixpay-upayments` installation.
Back up the local database first. Then:
```bash
wp plugin deactivate simplixpay-upayments
wp plugin install ./dist/sucheckout-upayments-0.1.0.zip --force
wp plugin activate sucheckout-upayments
```
Before deleting the old plugin folder, verify merchant settings, historical orders, payment-method identity, tokens/provenance, subscription metadata and scheduled events are intact.
Only after verification:
```bash
wp plugin delete simplixpay-upayments
wp plugin status sucheckout-upayments
```
Do not change protected persisted/provider identifiers merely to make names look uniform.
### 5.7 Remove the disposable acceptance worktree
After local acceptance is complete:
```bash
cd "$SOURCE_REPO"
git worktree remove --force "$ACCEPTANCE_DIR"
git worktree prune
git worktree list
```
The `--force` here is intentionally bounded to the disposable worktree created in section 5.2, where Composer/build output may remain untracked or ignored. Never substitute the path of the owner's normal working copy.
## 6. Full compatibility matrix
The authoritative 16-cell WordPress/WooCommerce/PHP × legacy/HPOS matrix is intentionally CI-owned. Reproducing all 16 cells on one Windows workstation is not required for owner acceptance.
If a code/tooling change occurs after local acceptance, require fresh GitHub Compatibility Certification before release.
## 7. Release/version decision — separate approval required
Current repository version is `0.1.0`. Do not create a public tag merely because engineering certification is green.
Before publication, explicitly decide whether the first public release is:
- `0.1.0` as an early public release; or
- `1.0.0` as the first stable release.
A version-promotion PR must update all canonical version surfaces together, including at minimum:
- `src/Release/Identity.php`;
- plugin header in `UPayments.php`;
- `readme.txt` stable tag/changelog;
- root `CHANGELOG.md`;
- any release documentation or version badge tied to the release.
That version-promotion PR must pass the full release-sensitive exact-head gates before tagging.
## 8. Public release — only after explicit approval
When a version is approved and the exact version-promotion merge is green:
1. build the artifact from a clean exact `main`;
2. run `scripts/verify-release.sh`;
3. compare the artifact SHA-256 with its sidecar;
4. create tag `vX.Y.Z` on the exact certified main commit;
5. create the GitHub Release using the verified ZIP/checksum/manifest;
6. submit/publish the exact verified plugin package to WordPress.org under slug `sucheckout-upayments`;
7. verify the public directory metadata, installation package and version;
8. perform a post-publication install/upgrade smoke from the public channel.
Do not publish a mutable worktree build, synthetic PR merge ref, or artifact from a different SHA.
## 9. Items that remain intentionally external/manual
Repository certification does not replace:
- production merchant payment completion evidence;
- real wallet/account/device completion;
- WPML/WCML/multilingual/multicurrency/RTL validation;
- broad browser/device/theme/accessibility testing;
- representative-store performance/load testing;
- penetration testing, PCI or legal/compliance attestation;
- live non-idempotent subscription auto-deduction evidence;
- provider webhook-signature verification until a stable documented contract exists.
Automatic WooCommerce refunds and arbitrary marketplace multi-split remain unsupported.
## 10. Owner completion checklist
Repository/admin:
- [ ] delete the two obsolete remote branches;
- [ ] rename repository to `SimplixInnovations/sucheckout-upayments`;
- [ ] update local `origin`;
- [ ] verify GitHub rules/security/integrations after rename;
- [ ] merge a post-rename coordinate-only documentation/control PR.
Optional acceptance:
- [ ] create the isolated `origin/main` acceptance worktree;
- [ ] run local Composer quality inside that worktree;
- [ ] run H12 PHP + Blocks and focused SUCheckout harnesses;
- [ ] build + verify deterministic ZIP;
- [ ] install the ZIP into a disposable local/staging WooCommerce site;
- [ ] run a bounded sandbox checkout smoke.
Publication:
- [ ] explicitly approve first public version;
- [ ] exact-head version-promotion PR passes;
- [ ] tag/GitHub Release approved;
- [ ] WordPress.org submission/publication approved;
- [ ] public-channel post-release verification completed.