# SUPCheckout for UPayments — Owner Handoff

**Purpose:** authoritative fresh-clone, local-acceptance and release-decision sequence
**Canonical GitHub repository:** `SimplixInnovations/supcheckout`
**Development version:** `0.1.0`
**Owner technical acceptance:** **ACCEPTED for the frozen Approach 2 baseline**
**Accepted Approach 2 baseline:** **`0c883d609906676966002eb022a82a9656eeacc5`**
**Accepted package SHA-256:** **`58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`**
**Accepted package:** `supcheckout-0.1.0.zip` — **51 files**
**Latest merged Approach 3 main:** **`a7a8bbfc3a1dc551127b7ead897c964e95c7cec9`**
**Approach 3 architecture modernization:** **ARCHITECTURE APPROVED / T1 DONE / VERIFIED / T2 DONE / VERIFIED / T3 DONE / VERIFIED / POST-T3 HARDENING IN PROGRESS**
**Active post-T3 integration:** **draft PR #108 / `audit/post-t3-ecosystem-hardening`**
**Public tag / GitHub Release / WordPress.org publication:** **not yet created — publication remains NOT AUTHORIZED**

The final pre-acceptance runtime hardening through PR #97 merged and was post-merge certified. The owner-accepted technical baseline is the exact Approach 2 commit above and remains the independent regression reference until a new explicit acceptance event. Approach 3 has since advanced through T3; this handoff records the current merged coordinate without redefining the accepted baseline. Living-document-only descendants may advance without changing the runtime package. This document remains a reusable freshness/regression procedure and does not authorize publication by itself.

Before using this procedure in a new chat, machine, clone or session, read [`START-HERE.md`](START-HERE.md) and verify live GitHub/source/check state.

## Program sequencing boundary

The owner acceptance recorded here is the mandatory technical gate that closed **Approach 2** and authorized **Approach 3** architecture modernization.

Approved sequence:

`Approach 2 closed → fresh-clone owner technical acceptance → accepted baseline → Approach 3 → Approach 3 re-certification → full UI/UX/branding/accessibility/broad launch testing → explicit release decision.`

Approach 3 is already in progress. Do not rerun or redefine the Approach 2 acceptance unless fresh evidence invalidates it; instead preserve it as the frozen regression baseline while the approved Approach 3 tranches execute.

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

Required result at an owner-acceptance/release boundary:

- `origin` points directly to `https://github.com/SimplixInnovations/supcheckout.git`;
- current branch is `main`;
- no unintended remote feature/audit branch remains after closeout;
- `git status --short` is empty;
- `HEAD` exactly equals `origin/main`.

During active engineering, temporary scoped branches such as PR #108 are expected; verify them live rather than falsely claiming a `main`-only topology.

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

A brand-new release/acceptance clone should have:

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

For Approach 3 closeout this smoke must be extended by the post-T3 plan to Store API, `wc-ajax`, `admin-ajax`, REST/sessionless and embedded/custom checkout request shapes before compatibility is claimed broadly.

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
- selected-card binding is explicit where a saved card is charged;
- no customer/card token appears in browser/log output beyond what the UI legitimately requires.

## C7. Subscription boundary

Verify:

- subscription eligibility rules;
- mixed-cart restrictions;
- account actions require the expected owner/nonce/state checks;
- no first-card fallback is accepted as a substitute for explicit selected-card identity;
- no blind non-idempotent auto-deduction is triggered simply to prove acceptance.

Live auto-deduction remains an external/manual provider qualification.

## C8. Additional merchant boundary

Verify the certified **one additional merchant** allocation behavior only. Do not infer arbitrary marketplace split support. Malformed enabled additional-merchant configuration must fail closed.

## C9. Economics/product boundary

At Approach 3 closeout verify the finalized WooCommerce order amount/currency remains payment authority, including zero-total, coupon, fee, shipping, tax/VAT and supported product-extension scenarios. Provider `products[]` is descriptive only. Do not silently mutate economics after Charge dispatch.

## C10. UI/assets/accessibility smoke

Verify:

- payment icons load without broken URLs;
- Apple Pay / Google Pay / Samsung Pay images render only when the corresponding provider method is actually available;
- checkout controls remain keyboard-operable in the tested theme;
- save-card controls have valid text associations and no nested labels;
- status/live-region markup remains valid;
- no obvious contrast/label/focus regression is introduced by the plugin;
- no third-party font/icon CDN is required by checkout;
- plugin assets are scoped to intended checkout/admin surfaces and do not overwrite unrelated handles.

A full accessibility certification remains separate evidence.

---

# D. GitHub repository verification

Before local acceptance and any release decision, verify live GitHub state:

- default branch: `main`;
- remote branches: `main` only outside temporary active work;
- open PRs/issues: none unless intentionally opened for active work;
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

As of the current post-T3 program, PR #108 is an intentional active draft; do not misclassify it as hygiene failure while it remains the authorized work branch.

---

# E. Post-acceptance and release decision

The technical acceptance above has been completed for the **Approach 2 owner-accepted baseline**:

- **Accepted baseline:** `0c883d609906676966002eb022a82a9656eeacc5`
- **Accepted package:** `supcheckout-0.1.0.zip` — 51 files
- **Accepted package SHA-256:** `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655`

That acceptance authorized Approach 3 architecture modernization. Runtime-neutral T1 `t01-architecture-guardrails-and-active-callback-characterization` is **DONE / VERIFIED** on merged main `beb89ac0c4d8c0e9b7c8b2de1e13c237bbd37b15`. Runtime-bearing T2 `legacy-callback-routing-consolidation` is **DONE / VERIFIED** on merged main `047cc86060efb97761d7a0cc4a3806f971ab6fe1`; its deterministic candidate package is 51 files / SHA-256 `368aaa5cb1a75e6df41ff17bb2e2126431b49da5e6b8dfe508c718433009fc04`.

Runtime-neutral T3 `legacy-direct-callback-characterization` is **DONE / VERIFIED**. PR #107 exact certified head was `f7c7d596dc4a2c8464d1acdf13dfe51028a5f9e0`; squash-merged main is `a7a8bbfc3a1dc551127b7ead897c964e95c7cec9`. T3 changed no production runtime files and preserved the T2 51-file package/checksum. It characterized the direct browser return, direct webhook and private legacy verifier and recorded the direct-browser request-shape constraint that blocks naïve T4 delegation.

The current successor is `post-t3-ecosystem-hardening`, recorded in `docs/superpowers/plans/2026-09-10-post-t3-ecosystem-hardening.md`, with active draft PR #108. R0, R1 and E1 are DONE / VERIFIED on that branch. Its latest fully certified generic/core E2 checkpoint is `5d8d954ffb8c2e6646dadc4aa1f994b5d56c462e`; Quality/H12, all 20 Compatibility cells plus Compatibility Gate, Provider Sandbox, WordPress.org Submission Check, Release Artifact and CodeQL succeeded, and its deterministic 55-file candidate package SHA-256 is `470db11afb2869bc187f0e920aeae4de02e92f6c1f7ca478ef5cb2ac62151b74`. Residual E2 and later tranches remain open.

Neither T2, T3 nor PR #108 silently redefines the owner-accepted Approach 2 baseline. A new acceptance event is required at Approach 3 closeout. T4 callback consolidation remains separately gated. This does **not** authorize publication.

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

Also provide a short manual-smoke report covering Classic, Blocks, HPOS/legacy where practical, sandbox success/decline/cancel, settings masking and browser/admin console errors, plus every additional E1/E2/E3/R2/R3/R4/R6 qualification the post-T3 plan ultimately marks required.

For the acceptance review, also state explicitly:

- exact accepted `HEAD` / `origin/main` SHA;
- generated ZIP SHA-256;
- whether all required automated acceptance commands passed;
- whether bounded merchant-facing smoke passed;
- any environment limitation that prevented a requested check;
- final verdict: **ACCEPTED BASELINE** or **NOT ACCEPTED**.

The historical **ACCEPTED BASELINE** verdict unlocked Approach 3. A future **ACCEPTED BASELINE** verdict at Approach 3 closeout must create a new explicitly recorded source/package coordinate; it must not overwrite the historical Approach 2 anchor.

### Current accepted baseline (recorded)

The verdict **ACCEPTED BASELINE** has been issued for this repository against the following frozen coordinates:

| Field | Value |
|---|---|
| Accepted Approach 2 baseline SHA | `0c883d609906676966002eb022a82a9656eeacc5` |
| Accepted artifact | `supcheckout-0.1.0.zip` |
| Accepted package SHA-256 | `58eba75019416f39a09211c87e7ccbcbb635834fb20bc890e9efbd5fec859655` |
| Accepted package file count | `51` |

The pre-acceptance B-X1 malformed-settings rejection closed by PR #84 remains historical evidence that documented the gaps closed by PRs #91-#97.
