# SUPCheckout for UPayments — Repository Agent Instructions

These instructions apply repository-wide. Nested `AGENTS.md` files may tighten but never weaken payment, security, compatibility or release invariants.

## Read first

Before substantive work read, in this order:

1. `docs/project/PROJECT-STATUS.md` — living current state
2. `docs/project/OWNER-HANDOFF.md` — owner/admin/local/release sequence
3. `docs/project/NAMING-IDENTITY-STANDARD.md` — canonical naming and protected IDs
4. `docs/COMPATIBILITY.md` — public compatibility/evidence boundary
5. `docs/project/NEW-CHAT-HANDOFF.md` — compact continuation context
6. `docs/project/RELEASE-ENGINEERING.md` — deterministic package/migration contract
7. `docs/project/ENTERPRISE-CERTIFICATION.md` — retained certification evidence
8. relevant immutable historical phase/quality records when touching their contracts
9. `docs/project/BASELINE-H12.md` when token/saved-card/subscription identity is relevant

## Canonical identity

- Formal product: **SUPCheckout for UPayments**
- Short product reference/family: **SUPCheckout**
- Provider: **UPayments**
- Maintainer: **Simplix Innovations**
- Canonical slug/text domain: `supcheckout`
- PHP namespace root: `Simplixi\SUPCheckout`
- Global PHP prefix for new first-party symbols: `supcheckout_`
- Constants: `SUPCHECKOUT_*`
- Canonical GitHub coordinate: `SimplixInnovations/supcheckout`
- Canonical plugin/package slug remains: `supcheckout`

The word **for** is human-facing relationship copy only and must never be encoded into URLs, slugs, text domains, package names, namespaces, CSS/JS roots or release artifacts.

Do not invent alternate product names/slugs/prefixes/namespaces.

## Provider-specific product boundary

SUPCheckout is permanently a **UPayments-only** product.

- Do not add PayTabs, Tap, MyFatoorah, Stripe, Tabby, Tamara or other provider adapters to this repository.
- Do not add cross-provider routing, failover, unified fraud scoring or payment orchestration here.
- Future provider integrations are independent repositories/products with independent release and security boundaries.
- Shared engineering templates may standardize CI/testing/release practice, but runtime sharing requires separate evidence and approval.

## Freshness rule

Live evidence beats recorded status.

Before implementation, review or release:

- verify live `main`;
- inspect open PRs/issues/branches;
- inspect exact source/diff;
- inspect exact-head CI/check state;
- distinguish the runtime-bearing baseline from later cleanup/docs/control-plane commits;
- reconcile `PROJECT-STATUS.md` with reality when project truth changes;
- use current official provider/platform documentation when behavior depends on it.

Historical records preserve milestone truth and may intentionally contain SimplixPay names, old repository coordinates and then-current gate wording. They are evidence, not current branding.

## Current engineering state

Repository Foundation, Phase 0, Phase 9I, Provider Lifecycle, Security, Architecture A1-A5, Quality Platform Q1-Q19 and Enterprise Tasks 1-8 are **DONE / VERIFIED**.

The numbered Quality Platform is permanently closed at Q19. **Never invent Q20 for continuity.**

Runtime-bearing SUPCheckout identity migration:

- PR #58 certified head: `5bf84dccb880733da45c1f922d43554af69a33dc`;
- squash merge: `6aabc4fcb0606567a11637ea07fe081fed4c7f85`;
- post-merge Quality #764, Compatibility #292 (**16/16**), Release Artifact #243, Provider Sandbox #207, WordPress.org #101 and CodeQL #579 — **SUCCESS**.

Documentation/control-plane closeout:

- PR #59 squash merge: `9591c431e1eb56fe40ca60147afdf9f3f909a212`;
- fresh main Quality #773, Compatibility #301 (**all 16 cells**), Release Artifact #252, Provider Sandbox #216, WordPress.org #110 and CodeQL #588 — **SUCCESS**.

Latest first-party naming cleanup:

- PR #61 squash merge: `efe937c67343242b7ccf3396a67b3cf2ce35ebac`;
- remaining safe retired first-party runtime/control identities migrated to canonical SUPCheckout equivalents while protected UPayments/persisted contracts remained unchanged;
- fresh main Quality #781, Compatibility #309 (**16/16**), Release Artifact #258, Provider Sandbox #221, WordPress.org #116 and CodeQL #595 — **SUCCESS**.

Final SUPCheckout identity migration:

- PR #67 certified head: `0059f365883fa4edd6a2d623c7b370d38d3f565c`;
- squash merge: `7547e59a2d5ef6d49b059851c6899a2d9987b16a`;
- post-merge Quality #896, Compatibility #424 (**16/16**), Release Artifact #373, Provider Sandbox #334, WordPress.org #231 and CodeQL #717 — **SUCCESS**;
- repository rename to `SimplixInnovations/supcheckout` is complete;
- canonical remote topology was verified as `main` only before post-rename coordinate reconciliation.

Post-rename coordinate closure:

- PR #68 exact head: `0e6ef6334282a83a428da7ee793daa98360c2bcc`;
- squash merge: `05fec942cc8fbeb58cfd0bd41f0ef5fdb86f966f`;
- post-merge Quality #901, Compatibility #429 (**16/16**), Release Artifact #378, Provider Sandbox #339, WordPress.org #236 and CodeQL #723 — **SUCCESS**;
- final remote topology is `main` only; open PRs/issues, tags and releases are empty;
- remaining repository-admin deltas are limited to live topic cleanup and adding `Compatibility Gate` + `Release Gate` to Main Rule; local acceptance/branding/publication remain owner actions.



Every future candidate must pass the permanent exact-head gates appropriate to its scope before merge or release.

No public tag, GitHub Release or WordPress.org publication exists yet. Repository rename is complete; local owner acceptance, remaining repository-admin verification and publication are separate owner actions documented in `OWNER-HANDOFF.md`.

## First-stable plugin identity

The first-stable package uses:

```text
supcheckout/UPayments.php
```

Task 7/real-install qualification proved a direct physical main-file rename can strand WordPress's stored plugin basename. Therefore:

- `UPayments.php` is a protected first-stable compatibility exception;
- `supcheckout/UPayments.php` is the canonical first-stable basename;
- `supcheckout.php` is only a possible future separately gated migration target;
- the text domain is `supcheckout`;
- legacy pre-release basename `simplixpay-upayments/UPayments.php` exists only as migration/rollback evidence.

Do not treat the retained physical filename as unfinished cosmetic work.

## Protected compatibility identities

Never mechanically/global-replace `upayments`, `_upay_*`, `UPayments` or `simplixpay-upayments`.

Protected by default:

- gateway/payment method ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks payment identity `upayments`;
- Store API extension key `upayments`;
- callback route `wc_upayments`;
- existing `_upay_*` order/user/product/subscription metadata;
- provider-order identities such as `UPayments_order_id`;
- `upayments_token_identity_secret_v2`;
- H12 provenance/scope/generation keys;
- `upay_process_subscriptions`;
- billing-attempt table/state;
- historical order payment-method identity;
- provider API field/path/schema terminology.

Changing one requires an explicitly approved migration contract with old/new precedence, upgrade, rollback and failure semantics plus regression evidence.

## Permanent quality controls

Do not remove, skip, soften or blanket-ignore:

- `.github/workflows/quality-gates.yml`;
- `.github/workflows/compatibility-certification.yml`;
- `.github/workflows/provider-sandbox-certification.yml`;
- `.github/workflows/release-artifact.yml`;
- `.github/workflows/wordpress-org-submission-check.yml`;
- CodeQL/security scanning;
- architecture harnesses;
- Quality Platform Q1-Q19 harnesses;
- Security Threat-Model harness;
- Phase 0 / Phase 9I / Provider Lifecycle harnesses;
- H12 PHP and Blocks harnesses;
- SUPCheckout identity/namespace/frontend/residue/HTTP/provenance harnesses;
- real integration fixtures for activation, metadata, Blocks, HPOS, saved cards, subscriptions, multi-merchant, operations and upgrade compatibility;
- deterministic artifact builder/verifier/harness;
- official packaged Plugin Check.

Permanent numbered-platform harness ratchet:

- keep `tests/harness/quality-platform-migration-cli-harness.php` mandatory after Q13;
- keep `tests/harness/quality-platform-migration-admin-harness.php` mandatory after Q14;
- keep `tests/harness/quality-platform-subscription-presentation-harness.php` mandatory after Q15;
- keep `tests/harness/quality-platform-migration-core-harness.php` mandatory after Q16;
- keep `tests/harness/quality-platform-payment-runtime-harness.php` mandatory after Q17;
- keep `tests/harness/quality-platform-blocks-availability-harness.php` mandatory permanently after Q18;
- keep `tests/harness/quality-platform-subscription-product-eligibility-harness.php` mandatory permanently after Q19.

The protected H12 job must run and must fail when required upstream quality/syntax prerequisites fail or skip.

Compatibility headers and Woo declarations require real runtime evidence. Static/unit/H12 success alone cannot broaden support claims.

## Provider automation boundary

Automated provider traffic may use only explicitly documented public sandbox/test credentials or separately authorized repository test secrets.

Never use production merchant credentials in CI.

Ordinary automated provider certification remains a bounded Charge initialization. Do not add payment completion, polling loops, refund mutation, saved-card mutation or subscription auto-deduction merely to make CI look broader.

## Payment/security rules

- Evidence before claims.
- Characterize before changing behavior.
- Fail closed on ambiguous payment/security identity.
- Never blindly retry non-idempotent Charge/refund/auto-deduct operations.
- Browser redirects/webhook prose are not financial truth.
- Preserve authenticated provider-status binding and Woo payment semantics.
- Preserve H12 token/provenance contracts unless a separately approved migration supersedes them.
- Never expose merchant API secrets/bearer tokens, card data, customer/card tokens, H12 secrets/provenance, unnecessary PII or production database exports.
- Uninstall remains non-destructive by default.

## Explicit unsupported/external boundaries

Do not mislabel these as repository-certified features:

- automatic Woo refunds — **unsupported**;
- arbitrary marketplace multi-split — **unsupported**, only one additional merchant is certified;
- live subscription auto-deduction — **external/manual**;
- provider webhook signature trust — **deferred until stable provider documentation exists**;
- production merchant payment completion — **external/manual**;
- wallets on real accounts/devices — **external/manual**;
- WPML/WCML/multilingual/multicurrency/RTL — **external/manual**;
- browser/device/theme/accessibility — **external/manual**;
- representative performance/load — **external/manual/store-specific**;
- penetration testing/PCI/legal/compliance — **external organizational evidence**.

## Public claims

Do not add compatibility/security/performance/compliance badges, topics or prose beyond `docs/COMPATIBILITY.md` exact evidence.

Do not imply WooCommerce or UPayments endorsement.

UPayments may appear as the provider/integration in human-facing copy. SUPCheckout must remain the first-party product identity.

## Repository coordinate discipline

The canonical GitHub coordinate is `SimplixInnovations/supcheckout`. All living repository URLs, badges, issue/support links and release-identity references must use it.

Historical milestone records and certified legacy package-root fixtures may retain former repository/package tokens where they record true past state. Never reinterpret provider/persisted compatibility identifiers as repository branding, and never perform blind repository-wide replacement of historical or protected tokens.

Any future repository-coordinate change must be exact-head reviewed, pass all triggered gates, and be reverified on merged `main`.

## Change discipline

- Use a dedicated branch from freshly verified `main`.
- TDD for production behavior/bug fixes: RED first, minimal GREEN, affected + permanent regressions.
- Keep changes scope-bounded; no drive-by payment refactors.
- Do not grow `UPayments.php` with new responsibilities.
- Update living state/docs when verified project truth changes.
- Preserve historical records rather than rewriting their milestone facts.
- Do not create a new phase merely because a document needs maintenance.

## Merge and release discipline

External AI/bot output is an evidence request, not authority.

Before merge, require the exact head to satisfy all controls appropriate to the changed scope. Runtime/release-sensitive changes require at minimum:

- Quality/H12 green;
- Compatibility 16/16 green;
- Release Artifact including packaged + migration cells green;
- bounded Provider Sandbox green;
- WordPress.org Submission Check green;
- CodeQL/security green;
- locked dependency audit green where applicable;
- zero unresolved valid review threads;
- exact-head mergeability;
- squash-only merge;
- post-merge verification on `main`.

Documentation-only changes still require their triggered required checks and exact-head verification; do not fabricate unnecessary runtime changes to force additional work.

If required verification fails:

`NOT APPROVED.`
`DO NOT MERGE.`
