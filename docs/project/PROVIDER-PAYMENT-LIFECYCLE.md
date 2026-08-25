# Provider Contract & Payment Lifecycle

**Status:** READY FOR IMPLEMENTATION

**Repository:** `SimplixInnovations/simplixpay-upayments`

**Discovery base:** `8e5a93ceb4f133663fdf433cc1a10b8b36c13d97`

**Discovery date:** 2026-08-25

**Purpose:** freeze the evidence-backed UPayments/WooCommerce payment-lifecycle contract before changing payment-critical runtime behavior.

## Scope

This gate covers the ordinary checkout payment lifecycle only:

- Charge request/response behavior already exercised by the H12 baseline;
- browser return and server-to-server notification handling;
- authenticated Get Payment Status reconciliation;
- deterministic provider-result classification;
- WooCommerce paid/failed/cancelled transitions;
- replay/idempotency and bounded reconciliation;
- status-query rate limiting;
- current refund and multi-merchant capability boundaries.

Historical token migration remains governed by the closed H12/Phase 9I contracts. Subscription auto-deduction retains its separately characterized scheduler/attempt-journal path and is not silently rewritten by this tranche.

## Evidence registry

Reviewed current official UPayments documentation on 2026-08-25:

- Make Charge: `https://developers.upayments.com/reference/addcharge`
- Webhook: `https://developers.upayments.com/reference/webhook`
- Get Payment Status: `https://developers.upayments.com/reference/checkpaymentstatus`
- Possible Gateway Responses: `https://developers.upayments.com/reference/possible-gateway-responses`
- Refund: `https://developers.upayments.com/reference/refund`
- Multi-Merchant API: `https://developers.upayments.com/reference/multi-merchant-api`
- FAQ: `https://developers.upayments.com/reference/faqs`
- Test Environment: `https://developers.upayments.com/reference/test-environment-details`

Reviewed current WooCommerce documentation on 2026-08-25:

- Payment Gateway API: `https://developer.woocommerce.com/docs/features/payments/payment-gateway-api`
- Order statuses: `https://woocommerce.com/document/managing-orders/order-statuses/`
- `WC_Order::payment_complete()` current code reference.

Live source base reviewed:

- `UPayments.php` blob `0f9ee544afcd846e01ff0bee4072175b93e49c60`;
- existing callback route `wc_upayments`;
- existing Bearer-authenticated `get-payment-status/{track_id}` verifier;
- existing H12/Phase 9I regression suites.

## Provider facts frozen for this gate

### Charge

- POST `/api/v1/charge`.
- Bearer authentication.
- `notificationUrl` is mandatory.
- Current plugin requires HTTP `201`, boolean `status === true`, a structurally valid response and a valid external redirect URL.
- A successful Charge response creates/initiates a payment session; it is **not** proof that funds were captured.
- The plugin must not retry an indeterminate Charge automatically. No provider idempotency contract for Charge is assumed.

### Callback/webhook

UPayments documents `track_id` on return/cancel/notification callbacks and directs integrators to use it with Get Payment Status. Callback fields such as `result`, `payment_id`, `ref` and `auth` are routing/diagnostic input only; they are not payment truth in this plugin.

The public Webhook documentation does not specify a stable request method/content type contract. The implementation therefore accepts query/form callback fields only through an explicit conflict-safe GET/POST merge and excludes cookies from payment routing.

### Authoritative payment truth

The local truth hierarchy is:

1. Bearer-authenticated Get Payment Status response;
2. strict schema validation;
3. strict binding to the WooCommerce order;
4. deterministic provider-result classification;
5. WooCommerce transition.

Browser return parameters and webhook payload values never authorize paid state by themselves.

### Required transaction binding

Every authenticated status response used to mutate an order must bind:

- `track_id` exactly to the reconciliation cursor;
- `merchant_requested_order_id` exactly to local `UPayments_order_id`;
- `reference` exactly to the WooCommerce order ID;
- `currency_type` exactly to the order currency after the existing UPayments currency mapping;
- `total_price` exactly to the order total using decimal-safe comparison.

For `CAPTURED`, a non-empty `payment_id` is additionally required before paid completion.

### Provider-result classification

Only exact documented values receive state-changing meaning:

| Provider result | Local class | Woo behavior |
|---|---|---|
| `CAPTURED` | `CAPTURED` | Canonical Woo payment completion |
| `PENDING` | `PENDING` | Keep unpaid; reconcile |
| `AUTHORIZED` | `PENDING` | Keep unpaid; reconcile |
| `APPROVED` | `PENDING` | Keep unpaid; reconcile |
| `NOT CAPTURED` | `FAILED` | Mark failed if capture was never verified |
| `FAILED` | `FAILED` | Mark failed if capture was never verified |
| `ERROR` | `FAILED` | Mark failed if capture was never verified |
| `CANCELED` | `CANCELLED` | Mark cancelled if capture was never verified |
| `REFUND` | `INDETERMINATE` for an unverified initial payment | Never infer an initial successful capture |
| `VOIDED` | `INDETERMINATE` for an unverified initial payment | Never infer an initial successful capture |
| empty/unknown/other | `INDETERMINATE` | Keep unpaid; bounded reconciliation |

UPayments explicitly says pending/processing/NULL-style outcomes must not be treated as failure. Unknown future values therefore fail closed as `INDETERMINATE` rather than being guessed into a terminal state.

### WooCommerce paid completion

For authenticated `CAPTURED` transactions the integration must call:

`$order->payment_complete($verified_payment_id)`

not direct paid-state `update_status()`.

This preserves WooCommerce's canonical transaction-ID storage, paid-date/status decision path and `woocommerce_payment_complete` lifecycle hook. The existing merchant option “Show paid orders as Completed?” may still force the target status through WooCommerce's `woocommerce_payment_complete_order_status` filter for this exact order; otherwise WooCommerce chooses the normal paid status.

The verified-capture flag remains a post-success idempotency barrier. A replay after `_upay_verified_capture = 1` must never re-drive lifecycle state.

### Non-captured WooCommerce state

Unresolved authenticated states remain in their current unpaid state during bounded reconciliation. They are not automatically moved to `on-hold`, because WooCommerce documents `on-hold` as a stock-reducing status. Authoritative terminal states may move an unverified order to `failed` or `cancelled`.

A refunded order is never resurrected by a later callback/reconciliation event.

## Reconciliation contract

A locally preflighted callback `track_id` may be stored only as an **unverified reconciliation cursor**. It is not provider proof until a successful authenticated/bound status response returns it exactly.

For `PENDING`, `INDETERMINATE`, transport failure, HTTP failure, malformed response or rate-gate denial when a safe cursor exists:

- schedule one bounded single-event reconciliation for the order;
- deduplicate scheduled events;
- use exponential delays `60, 120, 240, 480` seconds;
- maximum four scheduled reconciliation attempts;
- every attempt repeats the full authenticated status query and binding;
- never create a new charge as a reconciliation action;
- stop scheduling after CAPTURED, FAILED, CANCELLED, refund state, verified-capture flag, cursor conflict or attempt exhaustion.

After exhaustion, leave the order unpaid with a sanitized merchant note/reason code. Do not guess the financial outcome.

## Status-query rate limit

UPayments current documentation is contradictory:

- dedicated Get Payment Status documentation: **30 requests/minute** with temporary blocking and exponential backoff;
- FAQ: status-query/refund endpoints **800 requests/minute**.

Until UPayments resolves the contradiction, this project uses the stricter **30 requests/minute** contract for its own automated reconciliation. Automated scheduling is far below that ceiling.

Inbound callback storms must not become blind provider mutation/retry loops. The callback itself remains fail closed and full payment truth always comes from the authenticated status API.

## Webhook signature/HMAC boundary

The UPayments FAQ states that server-to-server webhooks are signed and that HMAC signatures are being rolled out. The current public documentation reviewed for this gate does **not** define enough stable details to implement an exact verifier (header name, canonical payload, algorithm/key derivation/rotation contract).

Therefore:

- no signature verifier is fabricated;
- webhook payload values remain non-authoritative;
- the handler requires local order/requested-order preflight before a provider query;
- every financial transition still requires Bearer-authenticated status lookup + strict binding;
- exact webhook HMAC verification remains **PROVIDER-DOC-UNRESOLVED** until UPayments publishes or supplies the full contract.

## Refund boundary

Current plugin behavior: automatic WooCommerce refunds are not implemented or advertised.

UPayments documents:

- full and multiple partial refunds;
- asynchronous refund completion;
- no refund webhook after creation;
- final state must be polled;
- no current idempotency-key support; merchants are responsible for preventing duplicate requests.

This gate therefore deliberately keeps automatic gateway refunds **UNSUPPORTED**. Implementing `process_refund()` without a durable local refund-intent/idempotency/reconciliation journal would create duplicate-refund risk. A later refund-certification tranche must design that durable contract first.

## Multi-merchant boundary

UPayments supports `extraMerchantData` as a multi-entry allocation array. Current SimplixPay source implements exactly one entry whose amount equals the full order amount.

Frozen current support claim:

> **Single additional merchant allocation only. Arbitrary multi-split marketplace routing is not certified.**

This gate preserves the existing validated single-entry behavior. Expansion to multiple merchant allocations requires a separate routing/allocation/refund characterization.

## Implementation shape

Use a strangler architecture rather than refactoring the inherited gateway bootstrap:

- new code under `Simplix\Pay\UPayments\Payment`;
- retain callback route `wc_upayments` for compatibility;
- register the new callback controller before the inherited priority-10 handler;
- leave the legacy `get_order_status` display-poll request to its existing handler;
- handle provider browser/webhook lifecycle through the new controller;
- keep protected H12 token/subscription identifiers untouched.

## Acceptance gate

Implementation is not approved until all are independently verified on the exact PR head:

- deterministic result classifier coverage;
- strict status-response schema and B1-B5 binding coverage;
- CAPTURED calls Woo `payment_complete()` with verified payment ID;
- standard Woo transaction ID is persisted;
- duplicate CAPTURED callbacks do not re-complete payment;
- pending/authorized/approved/unknown do not become paid or failed;
- documented terminal failure/cancel states transition only unverified orders;
- refunded/verified-paid orders cannot be resurrected;
- reconciliation is bounded, deduplicated and never creates a Charge;
- callback routing excludes cookies and rejects GET/POST conflicts;
- provider payload/result/payment ID cannot directly authorize payment;
- status lookup keeps TLS verification, no redirects and finite timeout;
- automatic refunds remain unsupported;
- current single-entry multi-merchant restriction is documented;
- Phase 0, all Phase 9I, H12 PHP, H12 Blocks, PHP syntax, Blocks syntax and Governance remain green.

## Non-claims

Closing this gate will certify the reviewed local provider/lifecycle contract and its executable regression evidence. It will not by itself constitute broad WordPress/WooCommerce/PHP/WPML/security/performance/feature certification or a stable public release.
