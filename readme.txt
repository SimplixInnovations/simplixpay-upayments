=== SUCheckout for UPayments ===
Tags: woocommerce, payments, payment gateway, upayments
Requires at least: 6.9
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.1.0
License: MIT
License URI: https://opensource.org/license/mit/

An independently engineered UPayments payment gateway integration for WooCommerce by Simplix Innovations.

== Description ==

SUCheckout for UPayments connects WooCommerce stores to the UPayments payment service.

The plugin is independently engineered and maintained by Simplix Innovations. UPayments is the external payment provider and owner of its respective names and trademarks. This plugin does not imply endorsement or official distribution by UPayments.

= External service =

This plugin communicates with UPayments APIs to initialize and verify payment operations and, when enabled and supported by the merchant's provider configuration, related payment features. Payment, order, customer, and provider-token data required for the selected API operation may be transmitted to UPayments.

A UPayments merchant account and API credentials are required for production use.

* UPayments API documentation: https://developers.upayments.com/reference/overview
* UPayments terms and policies: https://upayments.com/en/terms-of-service

== Installation ==

1. Install and activate WooCommerce.
2. Install and activate SUCheckout for UPayments.
3. Open WooCommerce payment settings and configure your UPayments credentials and required gateway options.
4. Use UPayments sandbox/test mode before enabling production transactions.

== Frequently Asked Questions ==

= Does SUCheckout process payments itself? =

No. SUCheckout integrates WooCommerce with the external UPayments payment service. UPayments is the payment provider.

= Does every UPayments payment method automatically become available? =

No. Availability depends on the merchant's UPayments account, provider configuration, plugin settings, checkout context, and the capabilities certified by this release.

= Are automatic WooCommerce refunds supported? =

Not in the current certified feature boundary.

== Changelog ==

= 0.1.0 =
* Pre-release SUCheckout identity and enterprise qualification work.
