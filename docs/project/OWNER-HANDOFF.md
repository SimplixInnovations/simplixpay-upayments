# SUPCheckout for UPayments — Owner Handoff

**Purpose:** authoritative fresh-clone, local-acceptance and release-decision sequence
**Canonical GitHub repository:** `SimplixInnovations/supcheckout`
**Development version:** `0.1.0`
**Latest runtime-bearing CI-certified main:** `82d1fdaee91ee6bde6c26dfcc7ceb974d0d59847`
**Owner technical acceptance:** **NOT ACCEPTED; all known repository pre-acceptance blockers through PR #97 are fixed/certified, fresh-clone re-acceptance is required**
**Canonical package at the PR #97 runtime tree:** **51 files; SHA-256 `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`**
**Public tag / GitHub Release / WordPress.org publication:** not yet created

The final pre-acceptance runtime hardening through PR #97 has merged and been post-merge certified. Living-document-only descendants may advance `main` without changing the runtime package. The owner must create a **completely fresh local clone** of the final reconciled `origin/main` for independent acceptance. This document does not authorize publication by itself.

Before using this procedure in a new chat, machine, clone or session, read [`START-HERE.md`](START-HERE.md) and verify live GitHub/source/check state.

## Program sequencing boundary

This owner acceptance is the mandatory technical gate between closed **Approach 2** and future **Approach 3** architecture modernization.

Approved sequence:

`Approach 2 closed → fresh-clone owner technical acceptance → accepted baseline → Approach 3 → Approach 3 re-certification → full UI/UX/branding/accessibility/broad launch testing → explicit release decision.`

Do **not** begin Approach 3 before this acceptance establishes the independent regression baseline.

This acceptance is intentionally bounded. Final UI/UX, branding, broad accessibility certification and exhaustive launch testing do **not** have to be finished before Approach 3. They remain post-Approach-3 launch work unless fresh evidence shows an earlier blocker.

## 1. Golden identity

Human-facing product:

**SUPCheckout for UPayments**

Technical identity:

`supcheckout`

Canonical first-stable package basename:

`supcheckout/UPayments.php`

The word `for` is relationship copy only. Do not encode it in the repository name, package slug, WordPress.org slug, text domain, namespace, CSS/JS roots or release artifact names.

Do not cosmetically rename protected provider/persisted identities such as `upayments`, `woocommerce_upayments_settings`, `wc_upayments`, `_upay_*`, token/provenance state, subscription/billing-attempt identities, provider fields or historical payment-method values.

See [`NAMING-IDENTITY-STANDARD.md`](NAMING-IDENTITY-STANDARD.md).

---

# A. Fresh local project bootstrap

## A1. Start from an empty local destination

Use a new folder. Do not copy an older checkout, `.git` directory, `vendor/`, `dist/`, test caches or worktree metadata into it.

### PowerShell

```powershell
cd C:\path\to\parent
Test-Path .\supcheckout
```

The cleanest case is `False`. If a folder already exists, inspect it manually and choose another path rather than deleting unknown content automatically.

Clone directly from the canonical repository:

```powershell
git clone https://github.com/SimplixInnovations/supcheckout.git supcheckout
cd .\supcheckout

git remote -v
git branch --show-current
git branch -r
git status --short
git rev-parse HEAD
git rev-parse origin/main
```

Required result:

- `origin` points directly to `https://github.com/SimplixInnovations/supcheckout.git`;
- current branch is `main`;
- remote topology shows only `origin/main` (plus `origin/HEAD -> origin/main` when displayed);
- `git status --short` is empty;
- `HEAD` exactly equals `origin/main`.

## A2. Confirm repository identity before installing dependencies

```powershell
git config --get remote.origin.url
git log -1 --show-signature --oneline
git status --short
git branch
git branch -r
git worktree list
git stash list
```

A brand-new clone should have:

- no local feature branches;
- no additional worktrees;
- no stashes;
- no untracked build artifacts.

Do not use `git reset --hard` or `git clean -fdx` as a substitute for understanding unexpected local state.

## A3. Toolchain preflight

The owner environment should expose:

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

On Windows, run release scripts with **Git for Windows Bash**, not WSL Bash, when the acceptance checkout is a Windows Git worktree. WSL cannot reliably resolve the Windows-path `.git` worktree pointer.

Release scripts invoke `python3`. If Windows Git Bash exposes `py` but not `python3`, use a temporary shell-local shim:

```bash
mkdir -p /tmp/supcheckout-python
cat > /tmp/supcheckout-python/python3 <<'EOF'
#!/usr/bin/env bash
exec py -3 "$@"
EOF
chmod +x /tmp/supcheckout-python/python3
export PATH="/tmp/supcheckout-python:$PATH"
python3 --version
```

Do not globally change Windows Python configuration only for this project.

---

# B. Independent local acceptance

GitHub CI owns the authoritative cross-version matrix. Local acceptance is an independent owner check of the exact final source/package and merchant-facing behavior.

## B1. Use a disposable detached acceptance worktree

From the fresh clone:

```bash
git fetch --prune --tags origin
SOURCE_REPO="$(git rev-parse --show-toplevel)"
ACCEPTANCE_DIR="${SOURCE_REPO}/../supcheckout-owner-acceptance"

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

If the directory already exists, use a different disposable path. Do not remove unknown content merely to reuse the name.

## B2. Development quality gate

```bash
composer install --no-interaction --prefer-dist
composer validate --strict
composer audit --locked
composer quality
```

Any failure is a release blocker until understood. First-party PHP/PHPUnit deprecations, notices and warnings are also blockers: the unit configuration must return a clean result with no reported issues on the current stable PHP quality lane. Do not suppress or baseline an issue merely to obtain a green acceptance result.

The owner should use the latest stable patch of the current PHP branch where practical. GitHub CI owns the exact current-stable quality lane and cross-version runtime matrix. Supported runtime floor is PHP 7.4; compatibility-only branches required for that floor must not execute deprecated behavior on current PHP. Older syntax-only checks do not broaden runtime certification.

## B3. High-value standalone contracts

```bash
php tests/harness/supcheckout-identity-migration-harness.php
php tests/harness/supcheckout-namespace-migration-harness.php
php tests/harness/supcheckout-frontend-identity-harness.php
php tests/harness/supcheckout-residue-harness.php
php tests/harness/wordpress-org-runtime-harness.php
php tests/harness/wordpress-org-submission-harness.php
php tests/harness/supcheckout-http-transport-harness.php
php tests/harness/supcheckout-provenance-db-failure-harness.php
php tests/harness/phase-9g-h12-php-harness.php

node --check tests/harness/phase-9g-h12-blocks-harness.js
node tests/harness/phase-9g-h12-blocks-harness.js
```

Do not change production behavior merely to silence an environment mismatch. Capture the full failing command/output first.

## B4. Build and verify the deterministic artifact

```bash
rm -rf dist

bash scripts/build-release.sh dist
bash scripts/verify-release.sh dist/supcheckout-0.1.0.zip

sha256sum dist/supcheckout-0.1.0.zip
cat dist/supcheckout-0.1.0.zip.sha256
```

Required:

- build succeeds;
- verifier succeeds;
- calculated ZIP hash exactly matches the generated `.sha256` sidecar;
- for the same exact Git tree, the local Windows/Linux owner artifact SHA-256 exactly matches the cross-platform CI artifact SHA-256;
- package contains one `supcheckout/` root;
- package includes `supcheckout/UPayments.php` and `readme.txt`;
- package excludes development/tests/CI/docs/tooling according to `.distignore`;
- package includes no secrets or local artifacts.

The final local sidecar is authoritative only when it agrees with cross-platform CI for the same exact Git tree. Documentation changes can legitimately change the ZIP hash between different commits, but platform/toolchain variation must not change the ZIP hash for one commit.

## B5. Install only into disposable/staging WordPress

```bash
wp plugin install ./dist/supcheckout-0.1.0.zip --force
wp plugin activate supcheckout
wp plugin status supcheckout
```

Do not use an uncontrolled production store for first local acceptance.

---

# C. Merchant-facing acceptance checklist

## C1. Plugin identity

Verify:

- one plugin entry only;
- display name **SUPCheckout for UPayments**;
- technical slug `supcheckout`;
- retained physical bootstrap does not create a duplicate plugin entry;
- no active user-facing SimplixPay/SUCheckout product branding remains.

## C2. Gateway settings

Verify:

- settings page loads;
- save/reload works;
- credentials are masked appropriately;
- credentials do not appear in page source, browser console or ordinary logs;
- malformed/blank optional settings fail safely;
- gateway enabled/disabled state behaves correctly;
- default-gateway option behaves correctly without unconditional debug logging.

## C3. Classic checkout

Verify:

- gateway ID remains `upayments`;
- expected enabled payment methods render;
- disabled/unavailable methods do not render;
- successful sandbox checkout reaches the expected WooCommerce flow;
- declined transaction remains unpaid;
- cancelled transaction remains unpaid/cancelled according to verified lifecycle rules;
- return/callback handling does not treat browser/provider payload alone as financial truth;
- verified provider transaction metadata binds to the correct WooCommerce order.

## C4. Cart / Checkout Blocks

Verify:

- payment method registers;
- availability follows account/settings/context capability;
- checkout can select the gateway;
- no browser console errors;
- no duplicate registration;
- unavailable methods fail closed.

## C5. Order storage

Where the staging environment safely allows it, verify both:

- HPOS;
- legacy order storage.

Create/read/update an order through normal WooCommerce APIs and confirm SUPCheckout metadata/lifecycle behavior remains consistent.

## C6. Saved cards / tokens

Verify only with safe test identities:

- eligible saved methods appear only for the correct user/account/mode scope;
- no cross-user leakage;
- provenance/identity ambiguity fails closed;
- no customer/card token appears in browser/log output beyond what the UI legitimately requires.

## C7. Subscription boundary

Verify:

- subscription eligibility rules;
- mixed-cart restrictions;
- account actions require the expected owner/nonce/state checks;
- no blind non-idempotent auto-deduction is triggered simply to prove acceptance.

Live auto-deduction remains an external/manual provider qualification.

## C8. Additional merchant boundary

Verify the certified **one additional merchant** allocation behavior only. Do not infer arbitrary marketplace split support.

## C9. UI/assets/accessibility smoke

Verify:

- payment icons load without broken URLs;
- Apple Pay / Google Pay / Samsung Pay images render only when the corresponding provider method is actually available;
- checkout controls remain keyboard-operable in the tested theme;
- no obvious contrast/label/focus regression is introduced by the plugin;
- no third-party font/icon CDN is required by checkout.

A full accessibility certification remains separate evidence.

---

# D. GitHub repository verification

Before local acceptance and any release decision, verify live GitHub state:

- default branch: `main`;
- remote branches: `main` only outside temporary active work;
- open PRs/issues: none unless intentionally opened after this closeout;
- tags/releases: none before explicit publication approval;
- About description/homepage/topics remain evidence-safe;
- squash-only merge policy remains enabled;
- Main Rule still requires:
  - `Governance`;
  - `H12 Regression Harness`;
  - `Compatibility Gate`;
  - `Release Gate`;
- no bypass actor has appeared;
- merged feature/audit branch auto-deletes.

---

# E. Post-acceptance and release decision

If the technical acceptance above passes, record the exact accepted Git SHA and local package checksum as the **Approach 2 owner-accepted baseline**. That acceptance authorizes planning/starting Approach 3; it does not authorize publication.

Approach 3 must then be independently scoped, implemented and re-certified before the launch-facing UI/UX/branding/broad-validation program is treated as final.

The repository may be engineering-ready while publication remains intentionally unapproved.

Before the first public release, decide explicitly:

1. whether `0.1.0` is an early public release or whether first public stable should be promoted to `1.0.0`;
2. whether launch branding/visual/accessibility acceptance is complete;
3. whether owner local acceptance is clean;
4. whether the exact final `main` package and checksum are accepted;
5. whether WordPress.org submission is authorized.

If a version promotion is required, do it in a dedicated PR and re-run the full release-sensitive gate stack before tagging.

Do not create a public tag, GitHub Release or WordPress.org submission until the owner explicitly authorizes publication.

---

# F. What to send back for owner-acceptance review

Provide the complete terminal output for:

```bash
git remote -v
git branch --show-current
git branch -r
git status --short
git rev-parse HEAD
git rev-parse origin/main
composer validate --strict
composer audit --locked
composer quality
php tests/harness/supcheckout-identity-migration-harness.php
php tests/harness/supcheckout-namespace-migration-harness.php
php tests/harness/supcheckout-frontend-identity-harness.php
php tests/harness/supcheckout-residue-harness.php
php tests/harness/wordpress-org-runtime-harness.php
php tests/harness/wordpress-org-submission-harness.php
php tests/harness/supcheckout-http-transport-harness.php
php tests/harness/supcheckout-provenance-db-failure-harness.php
php tests/harness/phase-9g-h12-php-harness.php
node --check tests/harness/phase-9g-h12-blocks-harness.js
node tests/harness/phase-9g-h12-blocks-harness.js
bash scripts/build-release.sh dist
bash scripts/verify-release.sh dist/supcheckout-0.1.0.zip
sha256sum dist/supcheckout-0.1.0.zip
cat dist/supcheckout-0.1.0.zip.sha256
```

Also provide a short manual-smoke report covering Classic, Blocks, HPOS/legacy where practical, sandbox success/decline/cancel, settings masking and browser/admin console errors.

For the acceptance review, also state explicitly:

- exact accepted `HEAD` / `origin/main` SHA;
- generated ZIP SHA-256;
- whether all required automated acceptance commands passed;
- whether bounded merchant-facing smoke passed;
- any environment limitation that prevented a requested check;
- final verdict: **ACCEPTED BASELINE** or **NOT ACCEPTED**.

Only **ACCEPTED BASELINE** unlocks Approach 3.
