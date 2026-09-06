# SimplixPay for UPayments — Release Engineering

**Status:** IMPLEMENTATION / FINAL PR VERIFICATION

**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

**Release-engineering base:** provider-certified `main` `949c3fcbc69ba12bee66be3906eb48af0e344e79`

**Current branch:** `enterprise/release-artifact`

## Purpose

Establish a reproducible, independently verifiable, installable SimplixPay release artifact without changing the frozen Phase 0 physical plugin identity.

This tranche does **not** migrate the main file from `UPayments.php` to `simplixpay-upayments.php` and does **not** migrate the text domain from `upayments` to `simplixpay-upayments`. Those remain a separate evidence-driven upgrade decision.

## Frozen package contract

- canonical ZIP root: `simplixpay-upayments/`;
- current version source: `Simplix\\Pay\\UPayments\\Release\\Identity::VERSION`;
- current candidate filename: `simplixpay-upayments-0.1.0.zip`;
- transitional main file retained: `UPayments.php`;
- transitional text domain retained: `upayments`;
- package policy, file set and bytes come from the Git **HEAD tree/blobs**, never mutable working-tree or staged-index state;
- archive paths are sorted;
- archive timestamps are fixed to 1980-01-01 00:00:00;
- archived regular-file mode is fixed to 0644;
- deterministic DEFLATE level 9 is used within the defined CI toolchain;
- every build emits a ZIP SHA-256 sidecar and a sorted per-file SHA-256 manifest.

## Distribution exclusions

The release artifact excludes development/control surfaces including:

- `.github/`;
- `tests/`;
- `vendor/`;
- `docs/`;
- `scripts/`;
- Composer development metadata/lock;
- PHPUnit/PHPStan/PHPCS configuration;
- caches;
- `AGENTS.md`;
- repository/editor control files;
- contributor/maintainer/internal support/provenance control files.

The artifact retains the runtime plugin, `src/`, `includes/`, `assets/`, `templates/`, public README/changelog/license/notice/security material, `index.php`, and non-destructive `uninstall.php`.

## TDD evidence

### RED 1 — no deterministic builder/verifier

Test-only release contract on PR #50 produced:

- **16 PASS / 9 FAIL**;
- missing canonical builder;
- missing canonical verifier;
- missing stronger distribution exclusions;
- Quality Gates and the permanent compatibility platform remained green.

### GREEN 1 — deterministic Git-blob artifact

After `scripts/build-release.sh`, `scripts/verify-release.sh`, and the stronger `.distignore` were introduced, the release harness passed deterministic double-build, ZIP checksum, manifest and verifier checks.

### RED 2 — no packaged runtime proof

The permanent harness was then strengthened before implementation and produced:

- **36 PASS / 5 FAIL**;
- no packaged-ZIP installer mode;
- no immutable artifact upload;
- no immutable artifact download;
- no packaged legacy/HPOS matrix;
- no packaged activation/metadata/Blocks/order-CRUD smoke.

### GREEN 2 — packaged runtime certification

The existing real WordPress/WooCommerce installer gained an optional `SIMPLIXPAY_PLUGIN_ZIP` mode. Source-mode compatibility certification remains unchanged.

Release Artifact CI now:

1. builds one exact artifact;
2. independently verifies it;
3. uploads the ZIP/checksum/manifest through immutable `actions/upload-artifact`;
4. downloads the same artifact through immutable `actions/download-artifact`;
5. independently verifies the downloaded ZIP again;
6. installs the ZIP through WP-CLI into fresh WordPress 7.1 / WooCommerce 11.1.0 / PHP 8.3;
7. verifies packaged activation and Classic registration;
8. verifies packaged support metadata and Woo feature declarations;
9. verifies packaged Blocks registration/availability;
10. verifies real order CRUD with legacy storage;
11. repeats real order CRUD with HPOS authoritative storage.

### RED 3 — synthetic PR merge-ref source

Independent review of the first green artifact found that GitHub PR checkout packaged the synthetic merge ref rather than the exact branch head.

A test-only harness assertion then failed:

- **41 PASS / 1 FAIL**;
- exact failure: release checkout was not pinned to the candidate source SHA.

The release workflow now defines one `RELEASE_SOURCE_SHA`:
- pull request: exact `github.event.pull_request.head.sha`;
- push/main: exact `github.sha`.

Both artifact-build and packaged-runtime checkouts use that exact SHA, and artifact upload/download naming is keyed to it.

### Independent archive-verification hardening

Two independently reproducible test-harness weaknesses were fixed before merge:

- the harness now opens the ZIP itself with PHP `ZipArchive` rather than trusting `verify-release.sh` as its only archive oracle;
- the harness now requires the second ZIP, second checksum sidecar and second manifest to exist before reproducibility comparisons.

The independent harness validates:
- one canonical root;
- sorted unique safe archive paths;
- required runtime files/subtrees;
- absence of development/control paths;
- an explicit release-path allowlist;
- exact equality between ZIP paths and the Git HEAD distribution set;
- byte-for-byte equality between every packaged file and its Git HEAD blob;
- packaged product/version/text-domain/main-file identity;
- ZIP checksum sidecar;
- manifest path-set equality;
- every packaged file's SHA-256.

### Provenance hardening — worktree/index isolation and source binding

A final provenance challenge closes two false-certification classes:

- the builder may not read version, distribution policy, file membership, or file bytes from the mutable worktree/index;
- a ZIP with internally consistent checksum/manifest evidence must still fail verification if any packaged byte differs from Git HEAD.

The permanent harness now creates an isolated detached worktree, mutates and stages release identity/policy plus a staged-only file, and requires the resulting build to remain byte-identical to the clean Git HEAD build. It also creates a self-consistent tampered ZIP with regenerated sidecars and requires the verifier to reject it because the artifact bytes no longer match source.

## Recorded candidate evidence

The evidence below intentionally records a completed candidate rather than claiming that a Markdown file can embed its own commit SHA. Final merge-head truth is taken from the GitHub checks attached to the exact PR head.

Recorded candidate:

`4d501fc021846b585cec6c17e1e371296c24d174`

Release Artifact #12 artifact-build evidence:

- permanent release harness: **57 PASS / 0 FAIL**;
- exact release source: `4d501fc021846b585cec6c17e1e371296c24d174`;
- ZIP: `simplixpay-upayments-0.1.0.zip`;
- ZIP SHA-256: `75db8cefdc73bb6de032ec464cd21d49cd80ea80d8ee1d077a7f2e405d6b57b2`;
- packaged files: **74**;
- independent verifier: **SUCCESS**;
- independent PHP archive inspection: **SUCCESS**;
- packaged legacy runtime: **SUCCESS**;
- packaged HPOS runtime: **SUCCESS**.

The unchanged ZIP hash across release-tooling-only commits is expected: release tooling, tests, CI and engineering documents are excluded from the artifact, while runtime package blobs remained unchanged.

## Release evidence artifacts

CI uploads one short-retention verification artifact named by exact source SHA containing:

- versioned release ZIP;
- ZIP SHA-256 sidecar;
- sorted per-file SHA-256 manifest.

A public/stable GitHub Release asset must be created only from a separately approved release-candidate/tag workflow after all remaining enterprise certification and upgrade/identity decisions close.

## Existing-install upgrade and release-identity decision — VERIFIED

Task 7 characterizes an already-active prior release candidate against both the current and floor supported runtime cells.

Verified safe with the retained package identity:

- force-upgrade from prior candidate to current deterministic ZIP;
- explicit deactivate/reactivate;
- rollback to the prior candidate and return to current;
- byte-stable merchant settings;
- historical `upayments` gateway identity and provider/token/subscription order metadata;
- historical `wc_upayments` callback registration;
- unchanged canonical `upay_process_subscriptions` schedule;
- duplicate-root package detection as a distinct WordPress plugin identity.

The controlled hypothetical package that physically renamed `UPayments.php` to `simplixpay-upayments.php` failed the safe-upgrade RED contract in both runtime cells. WordPress retained `simplixpay-upayments/UPayments.php` in `active_plugins`; the target basename remained inactive and the plugin runtime did not load.

**Release decision:** the first stable release retains:

- main file: `UPayments.php`;
- plugin basename: `simplixpay-upayments/UPayments.php`;
- text domain: `upayments`.

The eventual frozen targets remain future migration goals, not first-stable release requirements. Task 7 also observes 70 explicit packaged PHP translation calls still bound to `upayments`, so text-domain migration remains coupled to a separately certified i18n/WPML transition.

## Remaining release blockers

Task 5 deterministic packaging, Task 6 feature/operations boundaries and Task 7 existing-install/identity characterization are complete. Remaining repository closeout work is:

- reconcile living status/readiness/changelog/readme documentation to the verified enterprise state;
- classify multilingual/RTL/browser/accessibility/performance/device evidence that cannot be honestly generalized from repository automation;
- run the full final source + packaged-artifact + provider/security verification on one release-candidate head;
- run one final whole-plugin external AI/Codex challenge after all primary evidence is complete;
- make the final release version/changelog/publication decision without creating a public release asset prematurely.

## Merge gate

PR #50 may merge only when one exact final head passes:

- Release Artifact including deterministic build and packaged legacy+HPOS smoke;
- Quality Gates including H12;
- permanent 16-cell Compatibility Certification;
- CodeQL;
- zero unresolved valid review threads.

After merge, all of those required workflows must pass again on canonical `main` before this tranche becomes **DONE / VERIFIED**.
