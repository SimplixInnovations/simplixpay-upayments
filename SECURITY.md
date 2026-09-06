# Security Policy

Payment integrations handle business-critical workflows. Security-sensitive findings must be reported privately.

## Reporting a vulnerability

Do **not** open a public GitHub issue for a suspected vulnerability.

Preferred channels:

1. GitHub Private Vulnerability Reporting, when enabled for this repository; or
2. **info@simplixi.com** with subject **`[Security] SUCheckout for UPayments`**.

Include only what is required to reproduce the issue. Never send live API keys, bearer tokens, full card data, customer unique/card tokens, token-identity secrets/provenance material, customer databases or unnecessary personal information. If a secret is directly involved, describe its role and coordinate a secure exchange method first.

## Useful details

- affected SUCheckout version or exact commit;
- WordPress/WooCommerce/PHP versions;
- checkout/HPOS/multilingual state where relevant;
- concise reproduction;
- impact and required privileges;
- minimal sanitized logs/stack traces;
- suggested remediation if available.

## Disclosure and release handling

Allow reasonable time for validation, remediation and coordinated release. Public advisories/release notes will avoid operational detail that unnecessarily increases exploitation risk. Security fixes require the same exact-SHA review discipline as other payment-critical changes.
