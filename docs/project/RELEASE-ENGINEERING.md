# SimplixPay for UPayments — Release Engineering

**Status:** TASKS 5 & 7 DONE / VERIFIED; TASK 8 RELEASE-CANDIDATE CLOSEOUT CURRENT
**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

## First-stable package contract

The first stable release candidate intentionally uses:

- package root: `simplixpay-upayments/`;
- main file: `UPayments.php`;
- plugin basename: `simplixpay-upayments/UPayments.php`;
- text domain: `upayments`;
- version source: `Simplix\Pay\UPayments\Release\Identity::VERSION`;
- current development artifact: `simplixpay-upayments-0.1.0.zip`.

Task 7 proved that a direct physical rename to `simplixpay-upayments.php` does not preserve an already-active installation. The filename/text-domain targets remain future tested migrations, not cosmetic release cleanup.

## Deterministic artifact contract — VERIFIED

Task 5 / PR #50 established the permanent build system:

```bash
bash scripts/build-release.sh dist
bash scripts/verify-release.sh dist/simplixpay-upayments-0.1.0.zip
```

The builder/verifier contract requires:

- distribution policy, path set and bytes from the exact Git `HEAD` tree/blobs;
- no dependence on mutable worktree or staged-index state;
- sorted archive paths;
- fixed 1980-01-01 timestamps;
- fixed regular-file mode 0644;
- deterministic DEFLATE level 9 within the defined CI toolchain;
- ZIP SHA-256 sidecar;
- sorted per-file SHA-256 manifest;
- explicit release-path allowlist;
- exactly one canonical ZIP root;
- independent PHP `ZipArchive` inspection;
- exact path equality to the HEAD distribution set;
- byte-for-byte equality to source blobs;
- rejection of a self-consistent/rehashed ZIP whose bytes diverge from HEAD;
- dirty worktree/staged-index reproduction of the same artifact.

Development/control surfaces such as `.github/`, `tests/`, `docs/`, `scripts/`, `vendor/`, Composer development metadata, analysis configs and repository-agent/control files are excluded. Runtime plugin code/assets/templates and public license/readme/changelog/notice/security material are retained.

## Packaged runtime certification — VERIFIED

Release Artifact CI builds one exact artifact, independently verifies it, transfers it through immutable upload/download actions, verifies it again, then installs that ZIP into real WordPress/WooCommerce.

Permanent package smoke includes:

- activation and Classic gateway registration;
- support metadata and Woo feature declarations;
- Blocks registration/availability;
- real Woo order CRUD with legacy authoritative storage;
- real Woo order CRUD with HPOS authoritative storage.

Task 5 final PR head `27fb42b32051e4cd18db0c0231f782d3b4a8e932` passed Release Artifact #15 with the permanent release harness at **76 PASS / 0 FAIL**, Quality #533/H12, Compatibility #61 (16/16) and CodeQL. It squash-merged as `54b1fbcc280b92372bd93baf929d6a746cfd3959` and repeated required checks on `main`.

## Existing-install upgrade / rollback — VERIFIED

Task 7 / PR #52 permanently extends Release Artifact certification with two installed-package upgrade cells:

- WordPress 7.1 / WooCommerce 11.1.0 / PHP 8.3;
- WordPress 6.9.7 / WooCommerce 10.8.1 / PHP 8.3.

Each cell begins from prior verified candidate `54b1fbcc280b92372bd93baf929d6a746cfd3959` as an already-active merchant installation and verifies:

- force-upgrade to the current same-basename package keeps the plugin active;
- merchant settings remain byte-for-byte unchanged;
- historical payment method `upayments` remains intact;
- provider order identity, customer-token and subscription metadata remain intact;
- canonical `upay_process_subscriptions` timestamp remains unchanged;
- historical `wc_upayments` callback remains registered;
- explicit deactivate/reactivate is non-destructive;
- rollback to the prior package and return to current are safe;
- a duplicate-root package is a distinct inactive WordPress plugin identity.

### Unsafe-rename negative proof

The controlled candidate replaced only:

`simplixpay-upayments/UPayments.php`

with:

`simplixpay-upayments/simplixpay-upayments.php`.

In both upgrade cells WordPress retained the old basename in `active_plugins`, the target basename remained inactive and SimplixPay runtime did not load. The workflow permanently expects this negative result, restores the canonical package and re-verifies retained state.

The first stable therefore retains `UPayments.php`. Restoration after the negative probe did not require explicit reactivation when the historical main file returned.

### Text-domain decision

The package still contains 70 explicit PHP translation calls bound to `upayments`. There is no certified coordinated WPML/String Translation migration. The first stable therefore also retains text domain `upayments`.

Task 7 final head `dd550eb6af86262aabfd50479407903172327726` passed:

- Release Artifact #26 including both upgrade cells;
- Quality #544/H12;
- Compatibility #72 (16/16);
- CodeQL with no new alerts;
- zero unresolved review threads.

It squash-merged as `02b8d1c2851faabe020f23bbe84ebcca43a4827d`. Post-merge `main` passed Release Artifact #27, Quality #545, Compatibility #73 and CodeQL #349.

## Release evidence boundary

CI artifacts are verification artifacts, not public releases. A stable GitHub Release asset, public tag, WordPress.org upload or version promotion must be a separate owner release action after Task 8 is DONE / VERIFIED.

Do not publish an artifact from an unverified PR merge-ref, mutable worktree or unreviewed commit.

## Task 8 release-candidate closeout

The final engineering candidate must pass on one exact head:

1. current living documentation/governance reconciliation;
2. zero unjustified open issues/PRs and clean branch topology to the extent supported by repository controls;
3. Quality Gates including Composer validation/audit, analyzers, distributed syntax and H12;
4. permanent 16-cell Compatibility Certification;
5. Release Artifact including deterministic build, packaged legacy/HPOS and current/floor upgrade cells;
6. bounded Provider Sandbox Charge initialization;
7. CodeQL/security analysis;
8. zero unresolved valid review findings;
9. the reserved final whole-plugin Codex challenge after primary evidence is green;
10. exact-head squash merge followed by post-merge verification on `main`.

## External/manual release evidence

The repository must not claim these are automated release blockers already solved:

- production merchant payment completion / production credentials;
- real wallet completion;
- commercial WPML/WCML/multicurrency/RTL validation;
- browser/device/theme/accessibility matrix;
- representative-store performance/load thresholds;
- external penetration-test/PCI/legal-compliance evidence;
- live non-idempotent subscription auto-deduction;
- provider webhook signature trust without a stable provider contract.

Automatic Woo refunds and arbitrary marketplace multi-split remain unsupported unless separately designed/certified.

## Publication rule

**Task 8 DONE / VERIFIED means the repository has an enterprise-qualified release-candidate engineering state. It does not itself mean 1.0 has been publicly released.**
