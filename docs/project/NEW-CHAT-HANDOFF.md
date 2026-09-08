# SUPCheckout for UPayments — Continuation Handoff

Use this file with root [`AGENTS.md`](../../AGENTS.md), [`PROJECT-STATUS.md`](PROJECT-STATUS.md), [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md), [`NAMING-IDENTITY-STANDARD.md`](NAMING-IDENTITY-STANDARD.md), [`../COMPATIBILITY.md`](../COMPATIBILITY.md) and [`RELEASE-ENGINEERING.md`](RELEASE-ENGINEERING.md).

## Identity

- Product: **SUPCheckout for UPayments**
- Short name: **SUPCheckout**
- Maintainer: **Simplix Innovations**
- Provider: **UPayments**
- Repository: `SimplixInnovations/supcheckout`
- Technical slug / text domain: `supcheckout`
- PHP namespace: `Simplixi\SUPCheckout`
- Package root: `supcheckout/`
- First-stable bootstrap: `supcheckout/UPayments.php`
- Development version: `0.1.0`

`for` is human-facing relationship wording only. Never encode it into repository, package, WordPress.org, namespace, REST, CSS/JS or artifact identifiers.

## Current state

The engineering program is pre-release but mature:

- Repository Foundation — **DONE / VERIFIED**
- Phase 0 release identity — **DONE / VERIFIED**
- Phase 9I historical identity migration — **DONE / VERIFIED**
- Provider payment lifecycle — **DONE / VERIFIED**
- Security threat-model closure — **DONE / VERIFIED**
- Architecture A1-A5 — **DONE / VERIFIED**
- Quality Platform Q1-Q19 — **DONE / VERIFIED; permanently closed at Q19**
- Enterprise Tasks 1-8 — **DONE / VERIFIED**
- SUPCheckout identity/repository migration — **DONE / VERIFIED**
- Final pre-clone runtime/QA closure — **DONE / VERIFIED**
- Deterministic packaged release controls — **DONE / VERIFIED**
- Public tag / GitHub Release — **not created**
- WordPress.org publication — **not performed**
- Owner local acceptance — **pending after final enterprise-audit merge**

Do not invent Q20. New work uses named, bounded tasks.

## Latest runtime-bearing certified baseline

Runtime-bearing merge:

`1354b8e6f801a847a5fa9b5b657e77647384bdbc`

Certified PR #75 head:

`9474955e2d5438ccc9c0334b52dc0f72be557a86`

Fresh post-merge evidence:

- Quality #958 — **SUCCESS**
- H12 Regression Harness — **SUCCESS**
- Compatibility #486 — **16/16 SUCCESS**
- Release Artifact #434 — **SUCCESS**
- Provider Sandbox #386 — **SUCCESS**
- WordPress.org #289 — **SUCCESS / strict Plugin Check**
- CodeQL/main-security #780 — **SUCCESS**

Later documentation/presentation-only commits may advance `main` without changing that runtime baseline. Always inspect live GitHub/source/CI before making a release claim.

## Protected compatibility identities

Never mechanically rename:

- gateway/payment ID `upayments`;
- `woocommerce_upayments_settings`;
- Blocks / Store API identity `upayments`;
- callback `wc_upayments`;
- `_upay_*` historical metadata;
- provider order identity such as `UPayments_order_id`;
- token/provenance/scope/generation identities;
- `upay_process_subscriptions` and billing-attempt state;
- historical order payment-method values;
- frozen Phase 9I migration identities;
- public compatibility wrapper `getAPIUrlForRetreiveCards()`;
- normalized `whitelabled` compatibility shape.

Changing one requires an approved migration with precedence, upgrade, rollback/failure semantics and regression evidence.

## First-stable bootstrap

`UPayments.php` is intentionally retained.

Real WordPress upgrade qualification proved that physically renaming an already-active plugin main file can strand WordPress's stored plugin basename. A future `supcheckout.php` migration is a separate release-sensitive project, not unfinished first-release cleanup.

## Permanent engineering controls

Do not weaken or bypass:

- Quality/H12 gates;
- 16-cell compatibility certification;
- deterministic release artifact/verifier;
- packaged legacy + HPOS smoke;
- historical package-root migration/rollback matrix;
- strict official WordPress Plugin Check;
- CodeQL/security analysis;
- architecture, provider, security, Quality Platform and SUPCheckout-specific regression harnesses.

Payment/security ambiguity fails closed. Browser redirect/callback payload alone is never financial truth. Non-idempotent operations are not blindly retried.

## Supported and unsupported boundaries

Repository evidence includes Classic checkout, Blocks registration/availability, HPOS/legacy storage, authenticated provider status binding, saved-card/token provenance boundaries, subscription eligibility/pre-dispatch safeguards and one additional merchant allocation.

External/manual qualification remains required for production payment completion, real wallet/device completion, WPML/WCML/multilingual/multicurrency/RTL, broad browser/theme/accessibility, performance/load, penetration/PCI/legal evidence and live subscription auto-deduction.

Automatic WooCommerce refunds and arbitrary marketplace multi-split are unsupported.

## Repository state rule

The owner deleted the last stale remote branch before the final enterprise audit. Outside temporary active audit work, the desired topology is `main` only, with no open PRs/issues/tags/releases before publication.

The Main Rule must continue to require:

- `Governance`
- `H12 Regression Harness`
- `Compatibility Gate`
- `Release Gate`

with squash-only merging, linear history, deletion/non-fast-forward protection and no bypass actors.

## Next owner action

After the final enterprise-audit PR is merged and post-merge certified:

1. create a completely fresh clone from `https://github.com/SimplixInnovations/supcheckout.git`;
2. follow [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md) exactly;
3. run local Composer/standalone/H12 acceptance;
4. build and verify the deterministic ZIP from final `origin/main`;
5. perform disposable/staging WooCommerce smoke;
6. send complete command output and manual-smoke findings back for final audit;
7. choose the first public version and authorize publication separately.

## Historical evidence rule

Old SimplixPay/SUCheckout names, old repository coordinates and old SHAs may remain in historical phase/quality/spec/plan records when they were true at that milestone. Do not rewrite historical evidence into current branding.

Live evidence and living authority documents control current decisions.
