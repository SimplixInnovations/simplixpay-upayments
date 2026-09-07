# SUCheckout for UPayments — Repository Agent Instructions

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

- Formal product: **SUCheckout for UPayments**
- Short product reference/family: **SUCheckout**
- Provider: **UPayments**
- Maintainer: **Simplix Innovations**
- Canonical slug/text domain: `sucheckout-upayments`
- PHP namespace root: `Simplixi\SUCheckout\UPayments`
- Global PHP prefix for new first-party symbols: `sucheckout_upayments_`
- Constants: `SUCHECKOUT_UPAYMENTS_*`
- Current GitHub coordinate pending owner/admin rename: `SimplixInnovations/simplixpay-upayments`
- Approved target GitHub coordinate: `SimplixInnovations/sucheckout-upayments`

The word **for** is human-facing relationship copy only. Never create `sucheckout-for-upayments` URLs, slugs, text domains, package names, namespaces, CSS/JS roots or release artifacts.

Do not invent alternate product names/slugs/prefixes/namespaces.

## Freshness rule

Live evidence beats recorded status.

Before implementation, review or release:

- verify live `main`;
- inspect open PRs/issues/branches;
- inspect exact source/diff;
- inspect exact-head CI/check state;
- distinguish the runtime-bearing baseline from later docs/control-plane-only commits;
- reconcile `PROJECT-STATUS.md` with reality when project truth changes;
- use current official provider/platform documentation when behavior depends on it.

Historical records preserve milestone truth and may intentionally contain SimplixPay names, old repository coordinates and then-current gate wording. They are evidence, not current branding.

## Current engineering state

Repository Foundation, Phase 0, Phase 9I, Provider Lifecycle, Security, Architecture A1-A5, Quality Platform Q1-Q19 and Enterprise Tasks 1-8 are **DONE / VERIFIED**.

The numbered Quality Platform is permanently closed at Q19. **Never invent Q20 for continuity.**

Runtime-bearing SUCheckout identity migration:

- PR #58 certified head: `5bf84dccb880733da45c1f922d43554af69a33dc`;
- squash merge: `6aabc4fcb0606567a11637ea07fe081fed4c7f85`;
- post-merge Quality #764, Compatibility #292 (**16/16**), Release Artifact #243, Provider Sandbox #207, WordPress.org #101 and CodeQL #579 — **SUCCESS**.

Final documentation/control-plane closeout:

- PR #59 squash merge: `9591c431e1eb56fe40ca60147afdf9f3f909a212`;
- fresh main Quality #773, Compatibility #301 (**all 16 cells**), Release Artifact #252, Provider Sandbox #216, WordPress.org #110 and CodeQL #588 — **SUCCESS**.

Every future candidate must pass the permanent exact-head gates appropriate to its scope before merge or release.

No public tag, GitHub Release or WordPress.org publication exists yet. Repository rename/local owner acceptance/publication are separate owner/admin actions documented in `OWNER-HANDOFF.md`.

## First-stable plugin identity

The first-stable package uses:

```text
sucheckout-upayments/UPayments.php
```

Task 7/real-install qualification proved a direct physical main-file rename can strand WordPress's stored plugin basename. Therefore:

- `UPayments.php` is a protected first-stable compatibility exception;
- `sucheckout-upayments/UPayments.php` is the canonical first-stable basename;
- `sucheckout-upayments.php` is only a possible future separately gated migration target;
- the text domain is `sucheckout-upayments`;
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
- SUCheckout identity/namespace/frontend/residue/HTTP/provenance harnesses;
- real integration fixtures for activation, metadata, Blocks, HPOS, saved cards, subscriptions, multi-merchant, operations and upgrade compatibility;
- deterministic artifact builder/verifier/harness;
- official packaged Plugin Check.

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

UPayments may appear as the provider/integration in human-facing copy. SUCheckout must remain the first-party product identity.

## Repository rename discipline

Until the owner/admin rename occurs, living GitHub links may still point at `SimplixInnovations/simplixpay-upayments` because that is the live coordinate.

After rename to `SimplixInnovations/sucheckout-upayments`:

1. create a dedicated coordinate-only branch/PR;
2. update only current/living repository URLs, badges and issue/support links;
3. audit every `simplixpay` hit;
4. retain historical milestone records and legacy package-root migration fixtures where the old token is semantically required;
5. rerun all workflows triggered by those changes;
6. merge only an exact green head and verify `main` again.

Never bulk-replace the old token repository-wide.

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
