# SimplixPay for UPayments — Repository Agent Instructions

These instructions apply repository-wide. Nested `AGENTS.md` files may tighten but never weaken payment/security/release invariants.

## Read first

Before substantive work read:

1. `docs/project/PROJECT-STATUS.md`
2. `docs/project/NAMING-IDENTITY-STANDARD.md`
3. `docs/project/NEW-CHAT-HANDOFF.md`
4. `docs/project/ENTERPRISE-CERTIFICATION.md`
5. `docs/project/RELEASE-ENGINEERING.md`
6. relevant immutable historical records: Phase 0, Phase 9I, Provider Lifecycle, Security, Architecture, Quality Platform
7. `docs/project/BASELINE-H12.md` when token/saved-card/subscription identity is relevant
8. `docs/superpowers/plans/2026-09-06-enterprise-completion.md` for the enterprise Tasks 1–8 contract

## Canonical identity

- Repository: `SimplixInnovations/simplixpay-upayments`
- Formal product: **SimplixPay for UPayments**
- Short integration reference: **SimplixPay UPayments**
- Reserved broader family: **SimplixPay**
- Slug: `simplixpay-upayments`
- New PHP namespace root: `Simplix\Pay\UPayments`
- New global prefix: `simplixpay_upayments_`
- New constants: `SIMPLIXPAY_UPAYMENTS_*`

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

## Current phase gate

Repository Foundation, Phase 0, Phase 9I, Provider Lifecycle, Security, Architecture A1-A5, Quality Platform Q1-Q19 and Enterprise Tasks 1–7 are **DONE / VERIFIED**.

The numbered Quality Platform is closed at Q19. **Never invent Q20 for continuity.**

The current named gate is **Enterprise Release Candidate Closeout**. It is complete only after one exact head passes final primary evidence, the reserved whole-plugin Codex review has been independently resolved, the exact head is squash-merged and required checks pass again on `main`.

Task 8 does not itself authorize a public 1.0 tag, GitHub Release or WordPress.org publication.

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

The protected H12 job must always run and must fail when required upstream quality/syntax prerequisites fail or skip.

Compatibility headers and Woo declarations require real runtime evidence; static/unit/H12 success alone cannot broaden support claims.

Automated provider traffic may use only explicitly documented public sandbox test credentials or separately authorized repository test secrets. Never use production merchant credentials. Ordinary automated provider certification remains one bounded Charge initialization with no payment completion, polling loop, refund, saved-card mutation or auto-deduction.

## First-stable physical/text identity

Task 7 proved a direct physical main-file rename does not preserve an already-active WordPress plugin identity. The first stable release therefore **must retain**:

- main file `UPayments.php`;
- basename `simplixpay-upayments/UPayments.php`;
- text domain `upayments`.

Frozen eventual targets `simplixpay-upayments.php` and `simplixpay-upayments` are future migrations requiring dedicated upgrade/i18n evidence.

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

External AI/bot reports are evidence requests, not proof. The **one final whole-plugin Codex review is reserved for Task 8 after all primary automated evidence is green**. Independently reproduce/inspect every material finding; fix valid findings and add regression evidence where appropriate.

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
