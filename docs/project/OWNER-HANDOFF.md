# SUCheckout for UPayments — Owner Handoff

**Engineering migration:** DONE / VERIFIED
**Migration scope:** identity/runtime
**Latest certified pre-docs `main` baseline:** `efe937c67343242b7ccf3396a67b3cf2ce35ebac`
**Current GitHub repository pending owner rename:** `SimplixInnovations/simplixpay-upayments`
**Approved repository target:** `SimplixInnovations/sucheckout-upayments`
**Development version:** `0.1.0`
**Public tag / GitHub Release / WordPress.org publication:** NOT YET CREATED

This is the authoritative owner/admin/local/release sequence. It does not reopen Quality Platform Q1-Q19 and it does not authorize publication by itself.

Fresh verification on pre-docs `main` `efe937c67343242b7ccf3396a67b3cf2ce35ebac`:

- Quality #781 — **SUCCESS**
- Compatibility #309 — **16/16 SUCCESS**
- Release Artifact #258 — **SUCCESS**
- Provider Sandbox #221 — **SUCCESS**
- WordPress.org Submission Check #116 — **SUCCESS**
- CodeQL/main-security #595 — **SUCCESS**
- official packaged Plugin Check — **0 blocking errors**

The final documentation PR must itself be exact-green and merged before you begin the owner sequence below.

## Golden identity rule

Human-facing product:

**SUCheckout for UPayments**

Technical identity:

`sucheckout-upayments`

Never encode the human-only relationship word `for` in repository URLs, WordPress.org slug, text domain, package names, namespaces, CSS/JS roots, REST namespaces or release artifacts.

The first-stable physical bootstrap is intentionally:

`sucheckout-upayments/UPayments.php`

Do not rename protected provider/persisted identities (`upayments`, `woocommerce_upayments_settings`, `wc_upayments`, `_upay_*`, token/provenance keys, subscription hooks, billing-attempt state, provider fields) merely for cosmetic uniformity.

---

# A. Required repository/admin actions

## A1. Synchronize the owner's normal clone

Run after the final docs PR has merged.

### PowerShell

```powershell
cd C:\path\to\repo
git status --short
git fetch --prune --tags origin
git switch main
git pull --ff-only origin main
git status --short
git rev-parse HEAD
git rev-parse origin/main
```

### Git Bash

```bash
cd /path/to/repo
git status --short
git fetch --prune --tags origin
git switch main
git pull --ff-only origin main
git status --short
git rev-parse HEAD
git rev-parse origin/main
```

Required result:

- current branch `main`;
- `git status --short` empty;
- `HEAD` exactly equals `origin/main`.

Do **not** use `git reset --hard` or `git clean -fdx` on the normal owner working copy merely to perform acceptance. Preserve intentional local work first.

## A2. Confirm there are no legitimate open PRs before deleting branches

In GitHub, confirm the final docs PR is merged and there are no other intended open PRs.

Then locally:

```bash
git fetch --prune origin
git branch -r
git worktree list
```

As of the final documentation audit, non-main branches included closeout/historical/Dependabot branches such as:

- `dependabot/github_actions/github-actions-055219aa09`
- `docs/final-sucheckout-owner-closeout`
- `docs/sucheckout-final-documentation-hardening`
- `docs/sucheckout-final-documentation-hardening-v2`
- `enterprise/release-identity-migration-decision`
- `release/wordpress-org-submission-readiness`

This list is a snapshot, not a command to delete blindly. **Live branch/PR state wins.**

Delete every non-`main` remote branch only after confirming it is superseded and has no intended open PR. For the known superseded branches:

```bash
git push origin --delete dependabot/github_actions/github-actions-055219aa09
git push origin --delete docs/final-sucheckout-owner-closeout
git push origin --delete docs/sucheckout-final-documentation-hardening
git push origin --delete docs/sucheckout-final-documentation-hardening-v2
git push origin --delete enterprise/release-identity-migration-decision
git push origin --delete release/wordpress-org-submission-readiness
git fetch --prune origin
git branch -r
```

If GitHub auto-deleted any branch, a delete command may report that the remote ref does not exist; that is harmless. Do not create it again.

Expected final remote state before repository rename:

```text
origin/HEAD -> origin/main
origin/main
```

For matching local branches, first inspect `git worktree list`. Delete only branches not used by a worktree and not containing needed local work.

PowerShell-safe example:

```powershell
git worktree list
git branch
# Delete only verified obsolete local branches, one at a time:
git branch -D docs/final-sucheckout-owner-closeout
git branch -D docs/sucheckout-final-documentation-hardening
git branch -D docs/sucheckout-final-documentation-hardening-v2
git branch -D enterprise/release-identity-migration-decision
git branch -D release/wordpress-org-submission-readiness
git worktree prune
```

The Dependabot branch normally exists only remotely; delete a local counterpart only if one actually exists.

## A3. Rename the GitHub repository

GitHub UI:

1. Repository → **Settings**.
2. **General** → Repository name.
3. Change:

```text
simplixpay-upayments
```

to:

```text
sucheckout-upayments
```

4. Confirm.
5. Re-open the repository at the new coordinate:

```text
SimplixInnovations/sucheckout-upayments
```

Do not encode the human-only relationship word in the technical repository name.

## A4. Update GitHub About metadata

Description:

```text
SUCheckout for UPayments — independently engineered WooCommerce payment integration by Simplix Innovations.
```

Homepage until a dedicated product page exists:

```text
https://simplixi.com
```

Remove retired topic:

```text
simplixpay
```

Recommended evidence-safe topics:

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

Do not add unsupported/uncertified claim topics such as `wpml-ready`, `pci-compliant`, `all-wallets`, `refunds`, `multicurrency` or `accessibility-ready` without separate evidence.

## A5. Update local `origin` after rename

```bash
git remote set-url origin https://github.com/SimplixInnovations/sucheckout-upayments.git
git remote -v
git fetch --prune --tags origin
git remote set-head origin -a
git branch -r
git status --short
```

Use the new URL directly rather than relying on GitHub's old-name redirect.

## A6. Verify repository controls after rename

Verify rather than assume:

- default branch is still `main`;
- squash merge remains the intended/only merge method;
- default-branch rules/ruleset still require the intended checks and block force-push/deletion;
- Actions remain enabled;
- workflow permissions remain appropriately minimal;
- CodeQL/code scanning remains enabled;
- Dependabot settings remain enabled;
- secret scanning / push protection remain enabled where available;
- Private Vulnerability Reporting remains configured as intended;
- repository secrets and environments still exist;
- webhooks, GitHub Apps and external integrations use the new coordinate where they store one explicitly;
- About description/homepage/topics are correct;
- no unexpected open PRs/issues remain;
- only intended branches remain.

## A7. Post-rename coordinate-only PR

After the GitHub rename, tell ChatGPT the new URL/that the rename is complete. A small dedicated PR must update **living repository-coordinate references only**.

Audit:

```bash
git grep -n "SimplixInnovations/simplixpay-upayments"
git grep -n "github.com/SimplixInnovations/simplixpay-upayments"
git grep -n "simplixpay" -- ':!docs/history/**' ':!docs/superpowers/**'
```

Expected current-coordinate surfaces can include README badges/links, `AGENTS.md`, project status/handoff, `NOTICE.md`, `UPSTREAM.md` and issue-template links.

Classify every hit:

- living repository URL → update;
- historical evidence → retain;
- certified legacy package-root migration fixture → retain;
- protected persisted/provider identity → retain;
- obsolete first-party branding residue → remove/update.

Never bulk-replace `simplixpay-upayments` repository-wide.

Merge the coordinate-only PR only from an exact green head, then verify the resulting `main` again.

---

# B. Independent local acceptance

GitHub CI owns the authoritative 16-cell cross-version matrix. Local acceptance is an independent owner check of the exact final package and merchant-facing behavior.

## B1. Toolchain preflight — Windows Git Bash

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

Release scripts invoke `python3`. If Git Bash only has Windows `py`, create a temporary shell-local shim:

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

Do not globally modify Windows Python just for this acceptance run.

## B2. Use a disposable exact-main worktree

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

Required:

- empty `git status --short`;
- both SHAs identical.

If the directory exists, choose another disposable path; never remove an unknown directory just to reuse the name.

## B3. Development quality

```bash
composer install --no-interaction --prefer-dist
composer validate --strict
composer audit --locked
composer quality
```

High-value standalone contracts:

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

A failure is a release blocker until understood. Do not alter production behavior merely to silence an environment mismatch.

Supported runtime floor is PHP 7.4; syntax checks on older PHP do not broaden runtime certification.

## B4. Build and verify deterministic artifact

```bash
rm -rf dist
bash scripts/build-release.sh dist
bash scripts/verify-release.sh dist/sucheckout-upayments-0.1.0.zip
sha256sum dist/sucheckout-upayments-0.1.0.zip
cat dist/sucheckout-upayments-0.1.0.zip.sha256
```

The calculated hash must match the sidecar.

Optional package inspection:

```bash
unzip -l dist/sucheckout-upayments-0.1.0.zip | head -100
```

Verify:

- exactly one `sucheckout-upayments/` root;
- `sucheckout-upayments/UPayments.php` exists;
- packaged `readme.txt` exists;
- no development/tests/CI/secrets are packaged;
- no second plugin root exists.

## B5. Install on disposable WordPress + WooCommerce

```bash
wp plugin install ./dist/sucheckout-upayments-0.1.0.zip --force
wp plugin activate sucheckout-upayments
wp plugin status sucheckout-upayments
```

Do not make the first acceptance installation on a production store.

## B6. Merchant/admin/manual smoke

Verify:

### Plugin identity

- display name is **SUCheckout for UPayments**;
- no active current UI presents SimplixPay as the product;
- no PHP notices/warnings/fatals;
- package root is `sucheckout-upayments`;
- retained `UPayments.php` does not create a duplicate plugin entry.

### WooCommerce admin

- payment settings page loads;
- settings save/reload correctly;
- credentials remain masked/not leaked;
- malformed/blank optional settings fail safely;
- no admin-console JS errors.

### Classic checkout

- protected gateway ID `upayments` registers correctly;
- enabled/disabled behavior matches settings;
- checkout renders and errors safely;
- no credentials/tokens appear in HTML or browser console.

### Cart / Checkout Blocks

- registration/availability is correct;
- disabled/malformed settings fail closed;
- no checkout JS errors.

### HPOS / legacy order storage

Where your disposable site allows safe switching, smoke both storage modes and confirm WooCommerce order CRUD/payment metadata remains readable.

### UPayments sandbox

Using **non-production credentials only**:

- initialize a bounded test payment;
- confirm payment-link/initial response handling;
- exercise available success/failure/pending return paths;
- verify browser return data alone cannot mark an order paid without provider-authenticated status verification;
- verify logs contain no secrets, full bearer tokens, card data or sensitive customer tokens.

### Optional saved-card/subscription/multi-merchant checks

Only when your provider/Woo setup supports them:

- saved-card membership/provenance restrictions fail closed;
- guest/foreign/malformed token paths fail closed;
- subscription eligibility UI matches configured rules;
- live auto-deduction is **not** treated as certified unless separately validated;
- multi-merchant remains limited to one additional merchant.

## B7. Legacy-root migration smoke — only if applicable

If you actually have an old internal/pre-release `simplixpay-upayments` installation, back up the disposable database first.

```bash
wp plugin deactivate simplixpay-upayments
wp plugin install ./dist/sucheckout-upayments-0.1.0.zip --force
wp plugin activate sucheckout-upayments
```

Before deleting the old package, verify settings, historical orders/payment method, metadata, token/provenance, subscription state, scheduled events and provider/callback identities remain intact.

Only after verification:

```bash
wp plugin delete simplixpay-upayments
wp plugin status sucheckout-upayments
```

## B8. Branding / visual / accessibility acceptance

Share/apply the approved Simplixi branding before final public screenshots/marketing assets are produced.

Validate the actual launch UI for:

- SUCheckout-specific logo/icon usage;
- brand colors/tokens/typography where appropriate;
- admin settings visual hierarchy;
- Classic and Blocks checkout presentation;
- desktop/mobile responsive behavior;
- keyboard navigation / visible focus;
- labels and error-state clarity;
- contrast/readability;
- target theme/browser combinations;
- RTL/Arabic only if you intend to claim/support it at launch.

Do not convert this into a broad compatibility claim until the relevant evidence exists.

## B9. Remove disposable worktree

```bash
cd "$SOURCE_REPO"
git worktree remove --force "$ACCEPTANCE_DIR"
git worktree prune
git worktree list
```

The force flag is bounded to the disposable acceptance worktree where Composer/build output may remain. Never substitute the owner's normal working-copy path.

---

# C. First public version decision

Current development version: `0.1.0`.

Explicitly choose:

- `0.1.0` — early public/pre-1.0 release; or
- `1.0.0` — first stable release.

Do not create a public tag merely because engineering checks are green.

If the version changes, use a dedicated version-promotion PR and update all canonical version surfaces together, including at minimum:

- `src/Release/Identity.php`;
- plugin header in `UPayments.php`;
- `readme.txt` Stable Tag/changelog;
- root `CHANGELOG.md`;
- README version badge/text;
- release documentation tied to the release version.

Require full release-sensitive exact-head CI and fresh merged-main verification before tagging.

---

# D. Public release / WordPress.org publication

Only after explicit owner approval:

1. fetch exact certified `main`;
2. build the deterministic artifact from that exact commit;
3. run `scripts/verify-release.sh`;
4. confirm ZIP SHA-256 equals the sidecar;
5. create `vX.Y.Z` on that exact certified commit;
6. create GitHub Release using the verified ZIP/checksum/manifest;
7. submit/publish the exact verified package to WordPress.org under slug `sucheckout-upayments`;
8. verify public directory name/slug/version/metadata/screenshots/package;
9. install/upgrade from the actual public channel on a disposable site;
10. run a post-publication checkout smoke.

Never publish a mutable worktree build, synthetic PR merge ref, artifact from a different SHA, or a package whose technical identity encodes the human-only relationship word.

---

# E. External/manual and unsupported boundaries

Repository certification does not replace:

- production merchant payment completion;
- real Apple Pay / Google Pay / Samsung Pay account/device completion;
- WPML/WCML/multilingual/multicurrency/RTL certification;
- broad browser/device/theme/accessibility matrix;
- representative-store performance/load testing;
- penetration testing;
- PCI/legal/compliance attestation;
- live non-idempotent subscription auto-deduction;
- provider webhook-signature verification until UPayments publishes a stable documented verification contract.

Unsupported in the current certified feature set:

- automatic WooCommerce refunds;
- arbitrary marketplace multi-split beyond one additional merchant.

---

# Final owner checklist

## Repository/admin

- [ ] final documentation PR merged from exact green head
- [ ] normal clone synchronized; `HEAD == origin/main`; worktree clean
- [ ] no legitimate open PRs remain
- [ ] all superseded non-main branches removed
- [ ] repository renamed to `SimplixInnovations/sucheckout-upayments`
- [ ] About description/homepage/topics updated
- [ ] local `origin` updated to new URL
- [ ] ruleset/Actions/CodeQL/Dependabot/secret scanning/PVR/integrations verified after rename
- [ ] coordinate-only living-link PR merged from exact green head
- [ ] final branch audit shows only intended branch(es), ideally `main`

## Local acceptance

- [ ] isolated exact-main worktree created
- [ ] Composer validate/audit/quality green
- [ ] focused SUCheckout/H12/WordPress.org harnesses green
- [ ] deterministic ZIP built and verified
- [ ] package structure inspected
- [ ] disposable WordPress/WooCommerce installation green
- [ ] admin settings save/reload green
- [ ] Classic checkout green
- [ ] Blocks checkout green
- [ ] HPOS/legacy smoke complete where feasible
- [ ] bounded sandbox smoke complete
- [ ] no secrets/token leakage found
- [ ] optional legacy-root migration smoke complete if applicable
- [ ] approved Simplixi/SUCheckout branding applied and launch UI/browser/mobile/accessibility smoke complete

## Publication

- [ ] first public version explicitly approved
- [ ] version-promotion PR completed if required
- [ ] exact merged-main release gates green
- [ ] release ZIP/checksum/manifest verified
- [ ] tag/GitHub Release explicitly approved
- [ ] WordPress.org submission/publication explicitly approved
- [ ] public-channel post-release install/upgrade smoke complete
