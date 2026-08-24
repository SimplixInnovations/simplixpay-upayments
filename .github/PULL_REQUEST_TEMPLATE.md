## Summary

Describe the change and merchant/developer impact.

## Project gate

- Phase/workstream:
- Required base SHA:
- Head SHA to review:
- Relevant `PROJECT-STATUS.md` gate:

## Required reading

- [ ] `AGENTS.md` followed
- [ ] naming/identity standard reviewed if identifiers are touched
- [ ] protected legacy identifiers unchanged, or an approved migration contract is linked

## Root cause / requirement

Explain the defect, requirement or evidence gap.

## Scope

List changed components and explicit out-of-scope items.

## Payment-flow risk

- [ ] No runtime payment-flow behavior changed
- [ ] Charge creation
- [ ] Redirect/return/cancel
- [ ] Webhook/callback/reconciliation
- [ ] Order status/metadata
- [ ] Saved-card/token identity
- [ ] Subscription/auto-deduction
- [ ] Multi-merchant/refunds
- [ ] Upgrade/update/install identity

Describe safeguards, idempotency implications and rollback/recovery for checked items.

## Compatibility validation

Record exact tested versions/modes where applicable: WordPress, WooCommerce, PHP, Classic/Blocks, HPOS, WPML/WCML, theme/browser/device and enabled payment features.

## Validation performed

List exact commands, test counts/results, static checks and runtime evidence. Do not write “tests pass” without identifying them.

## Security/privacy

- [ ] no secrets/API keys/bearer/card data/customer tokens/PII included
- [ ] validation/sanitization/escaping reviewed where relevant
- [ ] logs/diagnostics redact sensitive values
- [ ] security/payment ambiguity fails closed where required

## Documentation/state

- [ ] changelog updated if required
- [ ] compatibility docs updated if truth changed
- [ ] `docs/project/PROJECT-STATUS.md` updated if project state changes
- [ ] naming/playbook docs updated if a governing contract changes

## Reviewer gate

Do not merge based on implementation claims alone. Independent reviewer verification must be pinned to the exact head SHA.
