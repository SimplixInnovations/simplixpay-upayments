# SimplixPay for UPayments — Master Engineering Playbook

This playbook defines the living engineering/release discipline. Exact milestone evidence lives in the dedicated project records and Git history.

## Current state

| Program | State | Release significance |
|---|---|---|
| Repository Foundation & Readiness | **DONE / VERIFIED** | Required |
| Phase 0 release identity/updater ownership | **DONE / VERIFIED** | Required |
| Phase 9I historical token-identity migration | **DONE / VERIFIED** | Required |
| Provider Contract & Payment Lifecycle | **DONE / VERIFIED** | Critical |
| Security threat-model audit | **DONE / VERIFIED** | Critical |
| Architecture/code quality | **DONE / VERIFIED (A1-A5)** | High |
| Full automated quality platform | **DONE / VERIFIED (Q1-Q19)** | Critical before public stable |
| Enterprise Tasks 1–7 | **DONE / VERIFIED** | Critical |
| Enterprise Release Candidate Closeout | **CURRENT / FINAL VERIFICATION** | Final engineering gate before owner publication decision |

No Q20 is authorized. A public stable release has not yet been created.

## Engineering principles

1. **Evidence before claims.** Platform/provider/feature/release claims require the exact evidence category capable of proving them.
2. **Characterize before changing payment/security behavior.** Production fixes use RED→GREEN regression evidence.
3. **Fail closed on ambiguous financial/security identity.** Never guess token provenance, provider success or historical migration state.
4. **Do not blindly retry non-idempotent provider operations.** Charge, refund and auto-deduction need explicit idempotency/reconciliation contracts.
5. **Preserve compatibility identities.** Historical gateway/settings/callback/meta/token/scheduler/order identities are migrations, not rename targets.
6. **Keep repository automation honest.** Static/unit/harness success does not equal real WordPress/WooCommerce/provider/browser/compliance certification.
7. **One exact merge head.** Final approval is SHA-bound and followed by post-merge verification.

## Permanent quality stack

The repository permanently retains:

- Composer metadata validation and locked dependency audit;
- PHPUnit, PHPStan and PHPCS/WPCS/Woo standards;
- distributed PHP syntax lanes;
- Phase 0, Phase 9I, Provider Lifecycle and Security harnesses;
- architecture A1-A5 harnesses;
- Quality Platform Q1-Q19 harnesses;
- H12 PHP + Blocks regression baselines;
- 16-cell real WordPress/WooCommerce/PHP × legacy/HPOS compatibility certification;
- bounded provider public-sandbox Charge initialization;
- deterministic release-artifact/source verification and packaged runtime smoke;
- current/floor existing-install upgrade/rollback certification;
- CodeQL/security scanning.

Required checks may expand but must not silently lose these semantic controls.

## First-stable identity contract

Task 7 proved direct physical main-file migration does not preserve an already-active installation. The first stable must retain:

- root `simplixpay-upayments/`;
- main file `UPayments.php`;
- basename `simplixpay-upayments/UPayments.php`;
- text domain `upayments`;
- gateway/payment method `upayments`.

Future `simplixpay-upayments.php` / `simplixpay-upayments` migration requires its own upgrade + i18n/WPML contract.

## Protected identities

Protected unless an approved migration explicitly supersedes them:

- `upayments` gateway/Blocks/Store API identity;
- `woocommerce_upayments_settings`;
- `wc_upayments` callback;
- `_upay_*` order/user/product metadata;
- `upayments_token_identity_secret_v2` and H12 provenance/scope/generation;
- `upay_process_subscriptions`;
- billing-attempt state/tables;
- historical order payment method values.

Every identity migration needs old/new precedence, fallback, rollback, existing-install evidence and permanent regression coverage.

## Payment truth contract

- Browser redirects and webhook bodies cannot establish paid state.
- Ordinary payment success requires authenticated provider status plus exact transaction/order/reference/currency/amount binding.
- CAPTURED uses WooCommerce `payment_complete()` semantics.
- Replay/duplicate success does not re-complete payment.
- Paid/refunded orders are not downgraded or resurrected.
- Reconciliation is bounded and never retries Charge.
- Provider rate/transport/endpoint checks remain explicit.

## Supported / external / unsupported distinction

### Repository-verified bounded capabilities

- Classic/Blocks registration and availability;
- HPOS/legacy order CRUD;
- platform support headers/declarations;
- bounded sandbox Charge initialization;
- saved-card/token provenance boundaries;
- subscription eligibility/pre-dispatch;
- one additional merchant allocation;
- non-destructive lifecycle retention;
- deterministic package and installed upgrade/rollback.

### External/manual evidence

- production merchant payment completion;
- wallet account/device completion;
- WPML/WCML/multilingual/multicurrency/RTL;
- browser/device/theme/accessibility;
- representative-store performance/load;
- penetration testing / PCI / legal-compliance;
- live subscription auto-deduction;
- webhook signature until UPayments provides a stable contract.

### Unsupported

- automatic Woo refunds until durable idempotency/reconciliation design exists;
- arbitrary marketplace multi-split; only one additional merchant allocation is certified.

## Enterprise Release Candidate Closeout gate

One immutable candidate must satisfy:

1. living documentation/governance reconciled;
2. repository hygiene independently checked;
3. Quality/H12 green including locked dependency audit;
4. Compatibility 16/16 green;
5. Release Artifact green including packaged legacy/HPOS + current/floor upgrade cells;
6. Provider Sandbox green;
7. CodeQL/security green;
8. all valid ordinary review findings fixed;
9. **then** the reserved one final whole-plugin Codex review;
10. all valid final-review findings independently fixed and regression-guarded;
11. exact-head squash merge;
12. required post-merge checks green on canonical `main`;
13. branch/topology cleanup verified as far as repository tooling permits.

Only after all 13 may Task 8 become **DONE / VERIFIED**.

## Publication boundary

Engineering closeout qualifies a release-candidate repository/artifact state. It does not itself:

- change `0.1.0` to `1.0.0`;
- create a stable tag;
- create a public GitHub Release;
- upload to WordPress.org;
- assert production-merchant/compliance evidence that has not been supplied.

Those are owner release actions after Task 8 closes.

## Review discipline

Bot/AI comments are evidence requests, not authority. Reproduce or inspect every material finding before changing source. Never weaken a test solely to make CI green. Final review and merge decisions are bound to the exact head SHA.
