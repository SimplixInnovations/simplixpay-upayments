# SimplixPay for UPayments — Project Status

**Status document:** canonical living state  
**Last updated:** 2026-08-24  
**Canonical repository:** `SimplixInnovations/simplixpay-upayments`  
**Historical audit archive:** `SimplixInnovations/upayments-woocommerce`  
**Upstream:** `upaymentskwt/woocommerce`

> Always verify live GitHub state before acting. SHA values below are verified milestones, not substitutes for a fresh remote check.

## Last independently verified milestone

| Item | Verified state |
|---|---|
| Governance merge | `cc565779c541178f63ae21f8e712f9708035361e` |
| Governance tree | `aa387ff76c300a12933c25932dece75e8def534e` |
| Governance PR | **#1 — DONE / VERIFIED** |
| Governance merge topology | squash commit; parent `1caf38410354322c1d842c28a40b0909ba31026d` |
| Governance signature | **GitHub verified** |
| Canonical product root | `1caf38410354322c1d842c28a40b0909ba31026d` — parentless |
| Historical H12 merge | `upayments-woocommerce@93e9925247a8bfade626cb822136852fd96eaea2` |
| H12 token identity | **DONE / VERIFIED** |
| H12 CI on governance PR | PHP 1927/0; Blocks 144/0 |
| Production readiness | **R0 — engineering hardening** |
| Public stable release | **NO** |
| WordPress.org release | **NO** |
| Active program | **Phase 0 — Release & Repository Safety** |

## Governance state

Repository governance code/files are established:

- root `AGENTS.md`;
- canonical project control docs under `docs/project/`;
- `.github/CODEOWNERS` owned by `@SimplixInnovationsAdmin`;
- GitHub Actions `Quality Gates` workflow;
- H12 regression CI on pull requests/main;
- Dependabot configuration for GitHub Actions;
- refreshed README/security/support/contribution/upstream/compatibility/issue/PR governance;
- canonical line-ending/editor policy.

Repository-settings controls that cannot be changed through the current connector remain a separate Phase 0 settings gate: branch rules/required checks, secret scanning/push protection/private vulnerability reporting, merge-policy cleanup, topics and unused feature toggles.

## Next permitted implementation

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
- `main` branch rules/required checks are not yet configured at repository-settings level;
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

Update this file after every independently verified milestone merge. Record the verified milestone SHA/topology, completed gate, newly opened/closed blockers and next permitted action. Do not claim a recorded SHA is still live without checking GitHub.
