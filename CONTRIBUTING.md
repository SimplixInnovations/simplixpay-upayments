# Contribution Policy

Thank you for helping improve **SUCheckout for UPayments**.

## Current contribution model

The canonical repository is maintained under the Simplix Innovations engineering program. Public bug reports, compatibility reports, reproduction cases and technical evidence are welcome through GitHub Issues.

External code pull requests are **not accepted by default**. Payment-risk ownership, canonical authorship and long-term release responsibility remain inside the Simplix Innovations maintenance process. Open a code PR only when a maintainer explicitly requests it.

## Before reporting a defect

Record the smallest reproducible case and include WordPress, WooCommerce, PHP, plugin version/commit, checkout mode, HPOS state, multilingual stack, theme, payment feature, expected/actual behavior and sanitized evidence.

Never include secrets, card data, customer tokens, customer PII or production database exports. Security findings belong in the private process described in `SECURITY.md`.

## Engineering standard for requested changes

Read `AGENTS.md` and the project control documents first. Requested changes must be phase-scoped, reviewable, backward-conscious and supported by evidence. Payment-flow behavior, persisted IDs and provider contracts must not be changed casually.

Every change should document requirement/root cause, scope, payment/security risk, compatibility impact, validation, rollback/recovery considerations and documentation updates.
