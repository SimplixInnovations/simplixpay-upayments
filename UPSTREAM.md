# Upstream Relationship and Provenance

## Source lineage

**SimplixPay for UPayments** is independently maintained by Simplix Innovations and derives from the UPayments WooCommerce integration:

- canonical Simplix repository: https://github.com/SimplixInnovations/simplixpay-upayments
- historical Simplix engineering/audit archive: https://github.com/SimplixInnovations/upayments-woocommerce
- provider upstream repository: https://github.com/upaymentskwt/woocommerce
- provider documentation: https://developers.upayments.com/reference/woocommerce

The canonical repository is deliberately **standalone**, not a GitHub fork. The former fork remains preserved separately so PR/review/commit provenance for hardening work remains auditable.

## Maintenance model

Upstream changes are inputs for review, not automatic updates. Nothing from upstream should be merged or distributed merely because it is newer. Changes require payment-flow, compatibility, security and regression analysis.

The canonical Simplix distribution must own its release/update channel; it must never be silently replaceable by the upstream repository.

## Attribution and trademarks

UPayments and related names/logos/trademarks belong to their respective owners. SimplixPay for UPayments does not imply UPayments sponsorship, endorsement or official maintenance status unless explicitly announced by the relevant parties. Simplix Innovations is responsible for modifications/releases it publishes.

## Compatibility claims

UPayments documentation is the provider capability baseline. Simplix Innovations marks a capability **Verified** only after independent reproducible validation. See `docs/COMPATIBILITY.md`.
