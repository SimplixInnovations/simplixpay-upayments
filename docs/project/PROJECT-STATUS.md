# SimplixPay for UPayments — Project Status

**Status document:** canonical living state  
**Last updated:** 2026-08-24  
**Canonical repository:** `SimplixInnovations/simplixpay-upayments`  
**Historical audit archive:** `SimplixInnovations/upayments-woocommerce`  
**Upstream:** `upaymentskwt/woocommerce`

## Current verified baseline

| Item | Verified state |
|---|---|
| Canonical `main` | `1caf38410354322c1d842c28a40b0909ba31026d` |
| Canonical `main` tree | `34594c00d243b59345ec9fbb3a88d2e1ec8f3efc` |
| Canonical history | standalone parentless root |
| Historical H12 merge | `upayments-woocommerce@93e9925247a8bfade626cb822136852fd96eaea2` |
| H12 token identity | **DONE / VERIFIED** |
| Production readiness | **R0 — engineering hardening** |
| Public stable release | **NO** |
| WordPress.org release | **NO** |
| Active phase | **Phase 0 — Release & Repository Safety** |
| Active branch | `phase-0/repository-governance` |
| Active PR | **#1 — repository governance** |

## Current objective

Establish the permanent repository-governance layer without changing runtime payment behavior. The governance PR adds agent instructions, canonical project documentation, code ownership, initial CI, dependency-update policy for GitHub Actions, and accurate repository-facing documentation.

## Next permitted implementation after governance

**Phase 0 — release identity/updater ownership.** Required outcomes:

- upstream GitHub updater can no longer overwrite Simplix builds;
- SimplixPay owns its independent version line;
- plugin public metadata becomes `SimplixPay for UPayments`;
- folder/main-file/text-domain transition is designed/tested as an upgrade problem, not a cosmetic rename;
- protected persisted/runtime identifiers remain stable unless a dedicated migration explicitly changes them;
- updater/version/upgrade-path regression tests are added.

## Known P0 blockers

- upstream-controlled updater remains active in `UPayments.php`;
- plugin header still carries upstream identity/version/author/text domain;
- `main` protection/rulesets are not yet enabled;
- required CI checks cannot be configured until governance CI lands;
- GitHub security/settings layer still needs explicit verification/configuration;
- Phase 9I migration is open;
- provider/payment lifecycle/security audits are incomplete;
- broad compatibility and release certification are incomplete.

## Frozen H12 production blob anchors

- `UPayments.php` — `64c789e81ae4d292ef9b1d7382812c319a44bc25`
- `includes/Token/CustomerTokenIdentity.php` — `85430d37e9baf540842f5655b86ccf0eca3e6aea`
- `includes/class-wc-gateway-upayments-blocks.php` — `813d192d69c069eb7ee11df93acc9dbdf03e270a`
- `includes/Subscription/Cron/Scheduler.php` — `5251866d4df2d1326e7c09f0c8ec1d146c0bb325`
- `includes/Subscription/Cron/CycleClaim.php` — `c34d83e2d77cc65024fe663e4c378cecb2b17347`

These are evidence anchors, not permanent bans on future changes.

## Protected compatibility identities

Do not rename merely for branding: gateway/payment method ID `upayments`; `woocommerce_upayments_settings`; Blocks/Store API identity `upayments`; callback `wc_upayments`; existing `_upay_*` metadata; `upayments_token_identity_secret_v2`; H12 token/provenance keys; `upay_process_subscriptions`; existing billing-attempt tables/state; historical order payment-method identity.

## Phase 9I blockers

All 13 remain open: unscoped legacy tokens; current-scope orphan histories; cross-user conflicts; malformed scoped histories; secret generation mismatches; card-only history; prior-scope same-generation history; non-scalar evidence; orphan metadata; incomplete >200 history; unloadable orders; force-refresh failures; malformed-vs-missing secret distinction.

## Update rule

Update this file after every independently verified merge. Record the new `main` SHA, active phase, completed gate, newly opened/closed blockers and next permitted action. Do not rewrite historical evidence to make the project appear further along than it is.
