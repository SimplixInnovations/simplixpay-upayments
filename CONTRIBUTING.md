# Contribution Policy

Thank you for helping improve **SUPCheckout for UPayments**.

## Current contribution model

SUPCheckout is maintained under the Simplix Innovations engineering and release process.

Public bug reports, compatibility reports, reproduction cases and technical evidence are welcome through GitHub Issues. External code pull requests are **not accepted by default** because payment-flow ownership, compatibility migrations and release responsibility remain inside the maintainer process.

Open a code pull request only when a maintainer explicitly requests one.

## Before reporting a defect

Provide the smallest reproducible case and include, where relevant:

- SUPCheckout version or exact commit;
- WordPress, WooCommerce and PHP versions;
- Classic or Blocks checkout;
- HPOS or legacy order storage;
- relevant theme/plugins and payment feature;
- expected vs actual behavior;
- sanitized logs or screenshots.

Never include API keys, bearer tokens, card data, customer/card tokens, customer PII, token-identity secrets/provenance or production database exports. Security findings belong in the private process described in [`SECURITY.md`](SECURITY.md).

## Engineering standard for requested changes

Read [`AGENTS.md`](AGENTS.md) and the relevant project-control documents before implementation.

Requested changes must be:

- narrowly scoped and reviewable;
- backward-conscious;
- explicit about payment/security impact;
- covered by reproducible validation;
- accompanied by migration and rollback/recovery reasoning when persistent identity or financial state is affected.

Payment-flow behavior, persisted IDs and provider contracts must not be changed for naming or cosmetic uniformity.
