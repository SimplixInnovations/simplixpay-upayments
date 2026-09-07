=== SUCheckout for UPayments ===
Tags: woocommerce, payments, payment gateway, upayments
Requires at least: 6.9
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.1.0
License: MIT
License URI: https://opensource.org/license/mit/

Independent UPayments payment gateway integration for WooCommerce by Simplix Innovations.

== Description ==

**SUCheckout for UPayments** connects WooCommerce stores to the external UPayments payment service.

SUCheckout is independently engineered and maintained by Simplix Innovations. UPayments is the payment provider and owner of its respective names and trademarks. This plugin does not imply UPayments sponsorship, endorsement, ownership, or official distribution.

The canonical technical slug and text domain are `sucheckout-upayments`. The word "for" is part of the human-facing product name only.

= Current certified boundaries =

The current release engineering program includes evidence for:

* WooCommerce Classic checkout registration.
* Cart / Checkout Blocks registration and availability.
* WooCommerce HPOS and legacy order storage in the documented compatibility matrix.
* Authenticated provider-status verification before financial order-state transitions.
* Saved-card/token provenance and eligibility boundaries.
* Subscription eligibility/pre-dispatch boundaries.
* One additional-merchant allocation boundary.
* Deterministic release packaging and official WordPress Plugin Check against the packaged artifact.

Payment-method and wallet availability depends on the merchant's UPayments account, provider configuration, plugin settings, checkout context, and provider/device eligibility.

Automatic WooCommerce refunds and arbitrary marketplace multi-split routing are not supported by the current certified feature boundary. Live subscription auto-deduction requires separately validated provider setup and evidence.

= External service =

This plugin communicates with UPayments APIs to initialize and verify payment operations and, when enabled and supported by the merchant's provider configuration, related payment features.

Data sent to UPayments may include payment/order/customer information and provider-token data required for the selected API operation. The exact fields depend on the transaction and enabled feature.

A UPayments merchant account and API credentials are required for production use.

* UPayments API documentation: https://developers.upayments.com/reference/overview
* UPayments terms and policies: https://upayments.com/en/terms-of-service

Do not use production credentials while performing initial test/sandbox validation.

== Installation ==

1. Install and activate WooCommerce.
2. Install and activate SUCheckout for UPayments.
3. Open WooCommerce payment settings and configure your UPayments credentials and required gateway options.
4. Validate the integration using UPayments sandbox/test mode before enabling production transactions.
5. Confirm your UPayments account is enabled for any payment methods or wallet features you intend to offer.

== Frequently Asked Questions ==

= Does SUCheckout process payments itself? =

No. SUCheckout integrates WooCommerce with the external UPayments payment service. UPayments is the payment provider.

= Is this an official UPayments plugin? =

SUCheckout is independently engineered and maintained by Simplix Innovations. It does not imply UPayments sponsorship, endorsement, ownership, or official distribution.

= Does every UPayments payment method automatically become available? =

No. Availability depends on the merchant's UPayments account, provider configuration, plugin settings, checkout context, and the capabilities supported by the account/device.

= Does SUCheckout support WooCommerce Blocks and HPOS? =

The current certified compatibility matrix includes Cart / Checkout Blocks registration/availability and both HPOS and legacy order storage in exact tested WordPress/WooCommerce/PHP cells.

= Are automatic WooCommerce refunds supported? =

No. Automatic WooCommerce refunds are outside the current supported feature boundary.

= Does SUCheckout support marketplace split payments? =

The current certified boundary supports one additional merchant allocation only. Arbitrary multi-split marketplace routing is not supported.

= Does SUCheckout support subscription auto-deduction? =

Subscription eligibility and pre-dispatch behavior are covered by repository tests. Live non-idempotent auto-deduction requires separately validated UPayments provider/account setup and is not claimed as broadly certified by repository CI.

= Is WPML, WCML, multicurrency or RTL certified? =

The source uses the canonical `sucheckout-upayments` text domain, but WPML/WCML, multicurrency, multilingual and RTL behavior require separate real-environment validation before those compatibility claims are made.

== Changelog ==

= 0.1.0 =
* Pre-release SUCheckout identity and release-engineering closeout.
* Canonical package/text-domain identity: `sucheckout-upayments`.
* Certified Classic and Blocks registration plus HPOS/legacy order-storage matrix.
* Added deterministic package verification, legacy package-root migration/rollback certification, WordPress HTTP transport hardening, and permanent official WordPress Plugin Check gating.
* Retained required historical UPayments payment/settings/token/subscription identifiers for compatibility rather than renaming persisted merchant data cosmetically.
