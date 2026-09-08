# Support Policy

## Scope

This repository covers **SUPCheckout for UPayments**, the WooCommerce integration maintained by Simplix Innovations.

Appropriate reports include reproducible checkout failures; WordPress/WooCommerce/PHP regressions; Classic or Blocks defects; HPOS issues; callback/reconciliation/order-status defects; frontend asset conflicts; saved-card/tokenization/subscription/multi-merchant integration defects; performance regressions; and plugin logging/diagnostics behavior.

Compatibility reports for multilingual, multicurrency, RTL, browser, theme or device-specific behavior are welcome when they include a reproducible environment and sanitized evidence.

## Provider/account matters

UPayments merchant onboarding, KYC, settlement, acquiring, pricing, account suspension, production API enablement, provider incidents and commercial/provider-account questions belong to UPayments support channels.

## Sensitive information

Never publish API keys, bearer tokens, merchant credentials, card data, customer/card tokens, token-identity secrets/provenance, customer PII, private webhook payloads, database exports or session secrets. Redact screenshots and logs before posting.

Security-sensitive findings must use the private process in [`SECURITY.md`](SECURITY.md), not a public issue.

## Reporting a plugin problem

Include the smallest reproducible case together with:

- SUPCheckout version or exact commit;
- WordPress, WooCommerce and PHP versions;
- Classic or Blocks checkout;
- HPOS or legacy order storage;
- relevant theme/plugins and payment feature;
- expected vs actual behavior;
- sanitized logs or screenshots when useful.

## Professional WooCommerce support

Commercial WooCommerce engineering, production debugging, implementation assistance and ongoing maintenance are available through **Simplix Innovations**:

- Website: https://simplixi.com
- Email: info@simplixi.com
- WooCommerce Agency Partner profile: https://woocommerce.com/development-services/simplix-innovations-woocommerce-full-service-agency/232995338/

The agency listing describes Simplix Innovations' broader WooCommerce practice. SUPCheckout support, maintenance and release responsibility remain with Simplix Innovations.
