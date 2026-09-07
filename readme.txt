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

SUCheckout for UPayments connects WooCommerce stores to the external UPayments payment service.

The plugin is independently engineered and maintained by Simplix Innovations. UPayments is the payment provider and owner of its respective names and trademarks. SUCheckout is not presented as an official UPayments plugin. This plugin does not imply endorsement or official distribution by UPayments.

= Current certified integration boundary =

The current pre-release engineering line includes verified support for:

* WooCommerce Classic Checkout gateway registration.
* WooCommerce Cart and Checkout Blocks registration and availability.
* High-Performance Order Storage (HPOS).
* WordPress 6.9 through 7.1 in the exact certified matrix.
* WooCommerce 10.8 through 11.1 in the exact certified matrix.
* PHP 7.4, 8.3, and 8.4 in the exact certified matrix.
* Deterministic release packaging and official WordPress Plugin Check gating.
* Provider Charge initialization and payment-status verification within the documented security boundaries.
* Saved-card/token and subscription eligibility safeguards within the documented runtime boundaries.
* One additional-merchant allocation configuration.

Payment-method availability depends on the merchant's UPayments account, provider configuration, plugin configuration, checkout context, and provider-side enablement. The plugin does not promise that every UPayments payment method is available to every merchant.

Automatic WooCommerce refunds and arbitrary marketplace multi-split routing are not supported in the current certified boundary. Live subscription auto-deduction requires separately validated provider/account setup and is not claimed as repository-CI-certified payment completion.

= External service =

This plugin communicates with UPayments APIs to initialize and verify payment operations and, when enabled and supported by the merchant's provider configuration, related payment features.

Depending on the operation, information required to create or verify a provider transaction may be transmitted to UPayments, including order/payment identifiers, transaction amounts and currency, callback/return information, customer/order fields required by the provider, and provider token identifiers where the merchant has enabled supported tokenized features.

A UPayments merchant account and API credentials are required for production use.

* UPayments API documentation: https://developers.upayments.com/reference/overview
* UPayments WooCommerce documentation: https://developers.upayments.com/reference/woocommerce
* UPayments terms and policies: https://upayments.com/en/terms-of-service
* Simplix Innovations: https://simplixi.com

Do not place production credentials, card data, customer tokens, or private customer information in public support reports.

== Installation ==

1. Install and activate WooCommerce.
2. Install and activate SUCheckout for UPayments.
3. Open WooCommerce payment settings.
4. Configure the UPayments credentials and gateway options appropriate to your merchant account.
5. Use UPayments sandbox/test configuration before enabling production transactions.
6. Confirm the gateway appears in the intended Classic or Blocks checkout flow before accepting live orders.

For an internal/pre-release installation that used the historical Simplix package root, follow the controlled migration guidance in the project documentation rather than copying folders over an active installation.

== Frequently Asked Questions ==

= Is SUCheckout an official UPayments plugin? =

No. SUCheckout for UPayments is independently engineered and maintained by Simplix Innovations. UPayments is the external payment provider.

= Does SUCheckout process or settle the payment itself? =

No. SUCheckout integrates WooCommerce with UPayments. Payment processing, merchant account services, settlement, provider availability, acquiring relationships, and provider-side account configuration remain UPayments responsibilities.

= Does every UPayments payment method automatically become available? =

No. Availability depends on the merchant's UPayments account, provider configuration, plugin settings, checkout context, and the capabilities enabled for that merchant.

= Are automatic WooCommerce refunds supported? =

No. Automatic WooCommerce refunds are outside the current certified feature boundary.

= Are subscriptions supported? =

The plugin contains bounded subscription eligibility and pre-dispatch support. Live non-idempotent subscription auto-deduction requires separately validated provider/account setup and is not represented as universally certified.

= Is HPOS supported? =

Yes, HPOS is declared compatible and verified in the exact supported WordPress/WooCommerce/PHP matrix documented by the project.

= Does the plugin support Checkout Blocks? =

Yes. Cart/Checkout Blocks registration and availability are verified in the exact certified matrix.

= What data should I include in a support report? =

Include exact WordPress, WooCommerce, PHP, SUCheckout version/commit, checkout mode, HPOS state, and sanitized reproduction details. Never include API keys, bearer tokens, card data, customer tokens, production database exports, or unnecessary personal information.

== Privacy ==

SUCheckout itself is an integration layer. Payment and provider operations send the data required for the selected transaction to UPayments as described above.

Store owners are responsible for reviewing their UPayments agreement, privacy obligations, applicable laws, and their own store privacy notice. Repository certification does not constitute PCI, legal, privacy, or regulatory attestation.

== Support ==

For reproducible plugin defects and compatibility reports, use the canonical GitHub repository after its public repository rename.

Security-sensitive findings must not be posted publicly. Use the repository Security Policy or contact Simplix Innovations through https://simplixi.com.

Provider account onboarding, KYC, settlement, pricing, production enablement, and provider outages belong to UPayments support channels.

== Changelog ==

= 0.1.0 =
* Pre-release SUCheckout identity migration and enterprise engineering closeout.
* Canonical technical slug and text domain migrated to sucheckout-upayments.
* Remaining retired SimplixPay first-party constant/control prefixes removed under the permanent residue gate.
* Certified package-root migration preserves protected historical WooCommerce/provider data identities.
* WordPress HTTP transport, HPOS/Blocks compatibility, deterministic packaging, and official WordPress Plugin Check gating are permanent release controls.
* No public stable release has been published from this development line yet.
