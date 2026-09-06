# Enterprise Compatibility Certification

**Status:** IMPLEMENTATION — no runtime matrix cell is VERIFIED yet.

**Verified source base:** `93cc66f47847384540cc4ad293e54bc8dc2d6d12`

**Program branch:** `enterprise/compatibility-certification`

## Evidence rule

A matrix cell becomes **VERIFIED** only after the exact WordPress, WooCommerce and PHP versions run the real plugin inside an installed WordPress/WooCommerce site and the required activation, checkout-registration and HPOS assertions all pass. Source inspection, syntax-only jobs, Q1-Q19 or H12 do not substitute for this evidence.

## Initial current-runtime characterization

The first RED target is:

- WordPress **7.1**
- WooCommerce **11.1.0**
- PHP **8.3**
- MySQL **8.0**
- real WooCommerce activation
- real SimplixPay activation hook
- malformed object-valued `woocommerce_upayments_settings`
- Classic gateway registration

Expected characterization before any production fix: activation must demonstrate whether `myPaymentPluginSetupCheckout()` safely handles malformed persisted settings without rewriting protected merchant data.

## Planned matrix

| WordPress | WooCommerce | PHP | Purpose | Status |
|---|---|---:|---|---|
| 7.1 | 11.1.0 | 8.3 | Current recommended runtime | **RED characterization pending** |
| 7.1 | 11.1.0 | 8.4 | Current modern PHP | Pending |
| 7.0.4 | 11.0.1 | 8.3 | Previous WordPress/Woo line | Pending |
| 6.9.7 | 10.8.1 | 8.3 | Woo 10.8 minimum WordPress line | Pending |
| 6.9.7 | 10.8.1 | 7.4 | Legacy ecosystem floor characterization only | Pending |

A passing legacy cell does not by itself make an end-of-life PHP version a recommended SimplixPay target.

## Feature declarations

The repository already contains a `cart_checkout_blocks` compatibility declaration. It is treated as **provisional debt** until real Blocks registration/runtime tests pass. No HPOS `custom_order_tables` compatibility declaration is authorized until HPOS enabled/disabled order CRUD certification passes.

## Non-claims

No current row in this document is a public support claim until it is marked VERIFIED with exact CI evidence.
