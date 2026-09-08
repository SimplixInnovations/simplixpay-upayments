# SUPCheckout for UPayments — Continuation Handoff

**Mandatory first step for every new chat/session:** read [`START-HERE.md`](START-HERE.md) and verify live GitHub/source/check state before relying on this compact handoff.

Use this file with root [`AGENTS.md`](../../AGENTS.md), [`PROJECT-STATUS.md`](PROJECT-STATUS.md), [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md), [`NAMING-IDENTITY-STANDARD.md`](NAMING-IDENTITY-STANDARD.md), [`../COMPATIBILITY.md`](../COMPATIBILITY.md) and [`RELEASE-ENGINEERING.md`](RELEASE-ENGINEERING.md).

Chat memory is never authority. If this file conflicts with live evidence or another living authority, stop and reconcile current truth first.

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
- Final enterprise repository audit — **DONE / VERIFIED — PR #77**
- Current-stable PHP / owner-acceptance hardening — **DONE / VERIFIED — PR #80; owner acceptance itself remains pending**
- Cross-platform deterministic release hardening — **DONE / VERIFIED — PR #82; owner acceptance itself remains pending**
- Approach 2 — **DONE / VERIFIED / CLOSED for owner acceptance**
- Deterministic packaged release controls — **DONE / VERIFIED**
- Owner technical acceptance — **NOT ACCEPTED on 2026-09-08 — first-party defect B-X1 in retained `UPayments.php` enableUpaymentsGateway filter; narrowly-bounded hotfix branch `fix/malformed-gateway-settings-fail-closed` open against `main`; owner re-acceptance required after that fix lands on `main`**
- Approach 3 architecture modernization — **not started; blocked until owner technical acceptance passes**
- Full UI/UX / branding / broad launch testing — **deferred until after Approach 3**
- Public tag / GitHub Release — **not created**
- WordPress.org publication — **not performed**

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

Final enterprise repository audit PR #77 certified exact head `4b00ef838f8da0a14d5963697d2584dcd6d82f4d` and squash-merged as GitHub-verified `bf4a46195013edb7699d5142f2c1400d99357fe2`. Fresh post-merge Quality #978, Compatibility #506 (**16/16**), Release Artifact #454, Provider Sandbox #397, strict WordPress.org #309 and CodeQL/main-security #800 all succeeded. The deterministic package remained 56 files with SHA-256 `32776f23f02de2fa7be14a5c84ebdcddb9b2f2d348366deade826bca86e58da3`.

PR #80 is the latest current-stable PHP acceptance hardening. Final exact PR head `717c34d16a5fdc5548b045751bdf53dbdb936a76` passed **35/35 checks** with Quality + H12 on PHP 8.5, an **18/18** real compatibility matrix including PHP 8.5 legacy/HPOS, Release Gate and CodeQL. It squash-merged as `65e39c5da4fee6462e219bbb0ec21038f831c6b1`; the merged tree is identical to the certified PR tree, and post-merge `main` passed **26/26 triggered checks**. No production runtime/package-source file changed.

Latest release-engineering hardening is PR #82. Independent Windows acceptance proved the old DEFLATE container was not cross-platform byte-deterministic even though the 56-file manifest was identical. PR #82 final exact head `af309e8c668e9def7d94e533f1c553b1b937eae6` passed **40/40 checks** and squash-merged as `c1f70164ebcd13fc6e7a5d3c70830a9070ecf898`; the merge tree is identical to the certified PR tree and post-merge `main` passed **39/39 checks**. The canonical `ZIP_STORED` package is **56 files**, SHA-256 `24efa28f2803976f4f9437d6b66e55922c26c13143ea291634db31b8506ffd63`, reproduced identically by canonical Ubuntu, explicit Ubuntu and Windows builds. Owner acceptance must now be rerun against the final reconciled `main` before Approach 3.

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
- 18-cell compatibility certification, including current-stable PHP 8.5 legacy + HPOS;
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

PR #77 completed the final enterprise repository audit and its branch auto-deleted. The verified closed repository topology is `main` only, with no open PRs/issues/tags/releases before publication. Any later temporary work branch must be scoped, reviewed and removed after merge.

The Main Rule must continue to require:

- `Governance`
- `H12 Regression Harness`
- `Compatibility Gate`
- `Release Gate`

with squash-only merging, linear history, deletion/non-fast-forward protection and no bypass actors.

## Current program sequence

The approved sequence is:

`Approach 2 closed → fresh-clone owner technical acceptance → accepted baseline → Approach 3 architecture modernization → Approach 3 re-certification → full UI/UX/branding/accessibility/broad launch testing → explicit release decision.`

Do **not** start Approach 3 before owner technical acceptance. Do **not** require final UI/UX or branding completion before Approach 3; those remain post-Approach-3 launch work unless fresh evidence proves an earlier blocker.

## Next owner action

Before beginning owner acceptance, confirm the current `main` is fully green and the repository has returned to the required closed topology. Then:

1. create a completely fresh clone from `https://github.com/SimplixInnovations/supcheckout.git`;
2. follow [`OWNER-HANDOFF.md`](OWNER-HANDOFF.md) exactly;
3. run local Composer/standalone/H12 acceptance;
4. build and verify the deterministic ZIP from final `origin/main`;
5. perform bounded disposable/staging WooCommerce smoke;
6. send complete command output and manual-smoke findings back for owner-acceptance review;
7. if accepted, begin Approach 3 as a new bounded architecture program;
8. defer final UI/UX/branding/broad launch qualification until Approach 3 is re-certified;
9. choose the first public version and authorize publication separately.

## Historical evidence rule

Old SimplixPay/SUCheckout names, old repository coordinates and old SHAs may remain in historical phase/quality/spec/plan records when they were true at that milestone. Do not rewrite historical evidence into current branding.

Live evidence and living authority documents control current decisions.
