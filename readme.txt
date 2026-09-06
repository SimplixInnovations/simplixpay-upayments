=== SimplixPay for UPayments ===
Tags: woocommerce, payments, payment gateway, checkout, ecommerce
Requires at least: 6.9
Tested up to: 7.1
Stable tag: 0.1.0
Requires PHP: 7.4
License: MIT
License URI: https://opensource.org/license/mit

Independent UPayments payment integration for WooCommerce with Classic/Blocks checkout and HPOS support.

== Description ==

SimplixPay for UPayments is an independently engineered WooCommerce payment integration by Simplix Innovations.

It connects WooCommerce stores to the UPayments payment service while preserving WooCommerce order state, supporting Classic and Cart/Checkout Blocks registration, and supporting both legacy WooCommerce order storage and HPOS.

An active UPayments merchant account and API credentials are required. Available payment methods depend on your UPayments account and provider configuration.

= External service =

This plugin communicates with UPayments when payment-related features are used. Data required for the requested payment operation can be sent to UPayments, including order/transaction references, amount and currency, customer/contact information, return/cancel/notification URLs, and feature-specific payment data.

UPayments API documentation:
https://developers.upayments.com/reference/overview

UPayments terms and policies:
https://upayments.com/en/terms-of-service

The maintained source and engineering evidence are public at:
https://github.com/SimplixInnovations/simplixpay-upayments

= Certified boundaries =

* WordPress 6.9 minimum; tested through WordPress 7.1.
* WooCommerce 10.8 minimum; tested through WooCommerce 11.1.
* PHP 7.4 minimum.
* Classic checkout and WooCommerce Cart/Checkout Blocks registration.
* Legacy WooCommerce order storage and HPOS.
* One additional merchant allocation is supported by the certified multi-merchant contract.

Available payment methods depend on your UPayments account and provider configuration.

Subscription auto-deduction requires separately validated provider setup.

Automatic WooCommerce refunds are not supported. Arbitrary marketplace multi-split routing is not supported.

== Installation ==

1. Upload the plugin ZIP through Plugins > Add New > Upload Plugin, or install it through the WordPress.org Plugin Directory after publication.
2. Activate SimplixPay for UPayments.
3. Open WooCommerce > Settings > Payments > UPayments.
4. Enter the UPayments API credentials for the intended environment and configure the gateway options required by your merchant account.
5. Test the complete checkout and return/callback flow before enabling production payments.

== Frequently Asked Questions ==

= Do I need a UPayments account? =

Yes. The plugin is an integration with the external UPayments payment service and requires merchant API credentials.

= Which payment methods are supported? =

The plugin exposes methods returned by UPayments where the relevant integration path is available. Actual availability and successful completion depend on the merchant account, provider configuration, environment, device, and payment method.

= Does it support WooCommerce Cart and Checkout Blocks? =

Yes. Blocks registration/availability and real checkout runtime behavior are covered by the repository compatibility certification.

= Does it support HPOS? =

Yes. Real WooCommerce order CRUD is certified under both legacy and HPOS authoritative storage.

= Does it automatically process WooCommerce refunds? =

No. Automatic WooCommerce refunds are intentionally unsupported until a separately designed idempotent refund/reconciliation contract is implemented and certified.

= Is subscription auto-deduction fully certified for production? =

No. Subscription auto-deduction requires separately validated provider setup and live non-idempotent provider mutation remains external evidence.

= Where is the source code? =

Source, build tooling, tests, and engineering records are maintained at:
https://github.com/SimplixInnovations/simplixpay-upayments

== Changelog ==

= 0.1.0 =

* Initial WordPress.org submission candidate for the independently engineered SimplixPay for UPayments integration.
