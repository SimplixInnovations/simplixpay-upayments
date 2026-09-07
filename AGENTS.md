# SUCheckout for UPayments — Repository Agent Instructions

These instructions apply repository-wide. Nested `AGENTS.md` files may tighten but never weaken payment/security/release invariants.

## Read first

Before substantive work read:

1. `docs/project/PROJECT-STATUS.md`
2. `docs/project/NAMING-IDENTITY-STANDARD.md`
3. `docs/project/NEW-CHAT-HANDOFF.md`
4. `docs/project/ENTERPRISE-CERTIFICATION.md`
5. `docs/project/RELEASE-ENGINEERING.md`
6. `docs/project/OWNER-HANDOFF.md` for rename/local acceptance/release-administration actions
7. relevant immutable historical records: Phase 0, Phase 9I, Provider Lifecycle, Security, Architecture, Quality Platform
8. `docs/project/BASELINE-H12.md` when token/saved-card/subscription identity is relevant
9. `docs/superpowers/plans/2026-09-06-enterprise-completion.md` for the enterprise Tasks 1–8 contract

## Canonical identity

- Current GitHub repository pending approved owner/admin rename: `SimplixInnovations/simplixpay-upayments`
- Target canonical repository: `SimplixInnovations/sucheckout-upayments`
- Formal product: **SUCheckout for UPayments**
- Short product reference: **SUCheckout**
- Product family: **SUCheckout**
- Slug / text domain: `sucheckout-upayments`
- PHP namespace root: `Simplixi\SUCheckout\UPayments`
- Global PHP prefix: `sucheckout_upayments_`
- Constants: `SUCHECKOUT_UPAYMENTS_*`

Do not invent alternate names/slugs/prefixes/namespaces.

## Freshness rule

Live evidence beats recorded status. Before implementation/review:

- verify live `main`;
- inspect open PRs/issues/branches;
- inspect exact source/diff;
- inspect exact-head CI/check state;
- reconcile `PROJECT-STATUS.md` with reality;
- use current official provider/platform documentation when behavior depends on it.

Historical records preserve milestone truth and may contain then-current gate wording. They are not current-gate owners.

## Current engineering state

Repository Foundation, Phase 0, Phase 9I, Provider Lifecycle, Security, Architecture A1-A5, Quality Platform Q1-Q19 and Enterprise Tasks 1–8 are **DONE / VERIFIED**.

The numbered Quality Platform is closed at Q19. **Never invent Q20 for continuity.**

The former product identity reached Enterprise Release Candidate Closeout through PR #54, final reviewed head `5a24944617f7ee482c381e5e899f687b77d81d09`, squash merge `2ddb1790fead37c6055256847dc7c827e165af4a`, and successful post-merge main Quality #553, Compatibility #81, Release Artifact #35, Provider Sandbox #13 and CodeQL/main-security #358. That is retained historical evidence.

The approved **SUCheckout identity migration is DONE / VERIFIED** through PR #58. Certified head `5bf84dccb880733da45c1f922d43554af69a33dc` squash-merged as `6aabc4fcb0606567a11637ea07fe081fed4c7f85`; post-merge Quality #764, Compatibility #292 (16/16), Release Artifact #243, Provider Sandbox #207, WordPress.org Submission Check #101 and CodeQL/main-security #579 all succeeded.

PR #59 reconciled living-state/owner documentation without runtime behavior changes and merged as `9591c431e1eb56fe40ca60147afdf9f3f909a212`.

PR #61 completed the remaining retired `SIMPLIXPAY_*` first-party constant and certification-control prefix migration while preserving protected UPayments compatibility identities. Current runtime-bearing certified `main` is `efe937c67343242b7ccf3396a67b3cf2ce35ebac`; post-merge Quality #781, Compatibility #309 (**16/16**), Release Artifact #258, Provider Sandbox #221, WordPress.org Submission Check #116 and CodeQL/main-security #595 all succeeded, with official packaged Plugin Check at **0 blocking errors**.

Every future candidate must still pass the permanent exact-head gates appropriate to its scope before merge or release.

No public tag, GitHub Release or WordPress.org publication exists yet. Repository rename to `SimplixInnovations/sucheckout-upayments`, local owner acceptance and any publication are separate owner/admin actions documented in `docs/project/OWNER-HANDOFF.md`.

## Permanent quality controls

Do not remove or weaken:

- `.github/workflows/quality-gates.yml`;
- `.github/workflows/compatibility-certification.yml`;
- `.github/workflows/provider-sandbox-certification.yml`;
- `.github/workflows/release-artifact.yml`;
- all permanent architecture harnesses;
- all Q1-Q19 quality harnesses;
- Security Threat-Model harness;
- Phase 0 / Phase 9I / Provider Lifecycle harnesses;
- H12 PHP and Blocks harnesses;
- real integration fixtures for activation, metadata, Blocks, HPOS, saved cards, subscriptions, multi-merchant, operations and upgrade compatibility;
- deterministic artifact builder/verifier/harness.

Permanent numbered-platform harness ratchet:

- keep `tests/harness/quality-platform-migration-cli-harness.php` mandatory after Q13;
- keep `tests/harness/quality-platform-migration-admin-harness.php` mandatory after Q14;
- keep `tests/harness/quality-platform-subscription-presentation-harness.php` mandatory after Q15;
- keep `tests/harness/quality-platform-migration-core-harness.php` mandatory after Q16;
- keep `tests/harness/quality-platform-payment-runtime-harness.php` mandatory after Q17;
- keep `tests/harness/quality-platform-blocks-availability-harness.php` mandatory permanently after Q18;
- keep `tests/harness/quality-platform-subscription-product-eligibility-harness.php` mandatory permanently after Q19.

The protected H12 job must always run and must fail when required upstream quality/syntax prerequisites fail or skip.

Compatibility headers and Woo declarations require real runtime evidence; static/unit/H12 success alone cannot broaden support claims.

Automated provider traffic may use only explicitly documented public sandbox test credentials or separately authorized repository test secrets. Never use production merchant credentials. Ordinary automated provider certification remains one bounded Charge initialization with no payment completion, polling loop, refund, saved-card mutation or auto-deduction.

## First-stable physical/text identity

Task 7 proved a direct physical main-file rename does not preserve an already-active WordPress plugin identity. The SUCheckout package therefore **retains the physical main file** `UPayments.php` while migrating the first-party package/text identity:

- canonical package basename: `sucheckout-upayments/UPayments.php`;
- canonical text domain: `sucheckout-upayments`;
- legacy pre-release basename `simplixpay-upayments/UPayments.php` is migration/rollback evidence only;
- the eventual physical main-file target `sucheckout-upayments.php` remains a separately gated future migration.

## Protected compatibility identities

Never mechanically/global-replace `upayments` or `_upay_*`.

Protected by default:

- gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks identity `upayments`;
- Store API extension key `upayments`;
- callback route `wc_upayments`;
- existing `_upay_*` order/user/product metadata;
- `upayments_token_identity_secret_v2`;
- H12 provenance/scope/generation keys;
- `upay_process_subscriptions`;
- billing-attempt tables/state;
- historical order payment-method identity.

Changing one requires an explicitly approved migration contract, old/new precedence, fallback/rollback semantics and tests.

## Payment/security rules

- Evidence before claims.
- Characterize before changing behavior.
- Fail closed on ambiguous security/payment identity.
- Never blindly retry non-idempotent Charge/refund/auto-deduct operations.
- Browser redirects/webhook prose are not financial truth.
- Preserve authenticated provider-status binding and Woo payment semantics.
- Preserve H12 token/provenance contracts unless an approved later migration supersedes them.
- Never expose merchant API secrets/bearer tokens, card data, customer/card tokens, H12 secrets/provenance, unnecessary PII or production database exports.
- Uninstall remains non-destructive by default.

## Explicit unsupported/external boundaries

Do not mislabel these as green repository features:

- automatic Woo refunds — **unsupported**;
- arbitrary marketplace multi-split — **unsupported**, one additional merchant only;
- live subscription auto-deduction — external/non-idempotent provider evidence;
- provider webhook signature trust — deferred until stable provider documentation exists;
- production merchant payment completion — external/manual;
- wallets, WPML/WCML/multilingual/multicurrency/RTL, browser/device/theme/accessibility, store-specific performance and penetration/PCI/compliance — external/manual evidence tracks.

## Public claims

Do not add compatibility/security/performance/compliance badges or prose beyond `docs/COMPATIBILITY.md` exact evidence. Do not imply WooCommerce or UPayments endorsement.

## Change discipline

- Dedicated branch from freshly verified base.
- TDD for production behavior/bug fixes: test RED first, minimal GREEN, rerun affected and permanent regression evidence.
- Keep release-closeout changes phase-scoped; no drive-by payment refactors.
- Do not grow `UPayments.php` with new responsibilities.
- Update living state/docs when verified project truth changes.
- Preserve historical records rather than rewriting their milestone facts.

## Final review and merge

External AI/bot reports are evidence requests, not proof. Task 8's reserved final whole-plugin Codex review completed on PR #54 after primary evidence was green; its valid P2 was independently reproduced, fixed and regression-guarded before merge. Do not request a second Task 8 whole-plugin review. Future changes follow their normal review requirements.

Final merge requires:

- exact immutable head;
- Quality/H12 green;
- Compatibility 16/16 green;
- Release Artifact including packaged + upgrade cells green;
- bounded Provider Sandbox green;
- CodeQL/security green;
- locked dependency audit green;
- zero unresolved valid review threads;
- exact-head mergeability;
- squash-only merge;
- post-merge verification on `main`.

If verification fails: **NOT APPROVED. DO NOT MERGE.**
