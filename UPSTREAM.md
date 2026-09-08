# Upstream Relationship and Provenance

## Source lineage

**SUPCheckout for UPayments** is maintained by Simplix Innovations and derives from the UPayments WooCommerce integration.

- Canonical SUPCheckout repository: https://github.com/SimplixInnovations/supcheckout
- Historical Simplix engineering archive: https://github.com/SimplixInnovations/upayments-woocommerce
- Provider upstream repository: https://github.com/upaymentskwt/woocommerce
- Provider documentation: https://developers.upayments.com/reference/woocommerce

The canonical SUPCheckout repository is deliberately **standalone**, not a GitHub fork. The earlier engineering archive remains separate so historical PR/review/commit provenance stays auditable without making upstream Git history the product's release authority.

## Maintenance model

Upstream changes are inputs for review, not automatic updates. Nothing from upstream is merged or distributed merely because it is newer.

A proposed upstream change must be evaluated for:

- payment-flow and financial-state impact;
- provider-contract changes;
- backward compatibility and persisted identities;
- security/privacy implications;
- WordPress/WooCommerce/PHP compatibility;
- regression and release-artifact evidence.

SUPCheckout owns its own release/update channel and must not be silently replaceable by an upstream distribution.

## Service and trademark boundary

UPayments provides the external payment platform and retains its names, logos and trademarks. Simplix Innovations is responsible for SUPCheckout modifications, maintenance and releases it publishes.

## Compatibility claims

UPayments documentation is the provider capability baseline. SUPCheckout marks a capability **Verified** only after independent reproducible validation. See [`docs/COMPATIBILITY.md`](docs/COMPATIBILITY.md).
