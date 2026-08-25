# Security Threat-Model Closure

**Status:** DONE / VERIFIED

**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

**Threat-model base:** verified closure `main` `08054a93c619f3c34fef747a6e530abce1e8986e`

**Base tree:** `5d11a9a7e72e110b12298a0d11b0e3740a6476f3`

**Implementation PR:** #17

**Final reviewed implementation head:** `fba12225899c3e01d6b23a6bba2f757a3b5f6a4a`

**Verified squash merge on `main`:** `01f3fc59eed8641b3e5372558f61a7a0f0cdfac9`

**Verified merge tree:** `e0027005f059fad03d8c08273b7aac6553c45f53`

**Merge parent:** `08054a93c619f3c34fef747a6e530abce1e8986e`

**GitHub signature:** **VERIFIED**

**Post-merge Quality Gates:** run #89 — **SUCCESS**

**Implementation branch:** **deleted after verified merge**

**Closure reconciliation PR:** #18

## Purpose

Close concrete security boundaries across the now-frozen ordinary payment lifecycle, H12 token identity, Phase 9I migration, subscription/customer actions, administration, external transport, rendering/logging and repository supply chain without turning the phase into an unauditable big-bang refactor.

This gate is not a claim of broad penetration-test certification, PCI certification, ecosystem compatibility, or public-production readiness. It is an engineering threat-model closure: enumerate meaningful assets and trust boundaries, fix verified P0/P1 security defects and bounded high-confidence defense-in-depth issues, add executable regression contracts, record unresolved provider/feature constraints, and leave a precise continuation record.

## Security principles

The gate follows the repository's existing fail-closed rules plus current WordPress security guidance:

- an object ID is never authorization;
- a WordPress nonce is CSRF intent evidence, not authorization;
- state-changing customer actions require both authorization and nonce verification;
- validation/rejection is preferred over permissive coercion;
- database/provider values remain untrusted when rendered and are escaped for their exact output context;
- public/browser/provider callback data is never payment truth by itself;
- provider credentials never cross to a browser or unapproved host;
- non-idempotent financial mutations are never blindly retried after an indeterminate outcome;
- security-sensitive compatibility behavior may be retained only behind an explicit authenticated/authorized boundary;
- third-party checkout dependencies are treated as supply-chain/privacy trust boundaries, not visual implementation details.

## Protected assets

1. UPayments API bearer credential and environment selection.
2. WooCommerce order ownership, order key and order lifecycle state.
3. Provider payment/track/customer/card tokens.
4. H12 identity secret, scope/generation and provenance.
5. Phase 9I migration result/checkpoint ledgers.
6. Subscription state, stored recurring-payment evidence and cycle journal.
7. Merchant gateway settings, IBAN/charge routing configuration and administrative actions.
8. Customer PII and order/payment metadata.
9. Repository/update/CI authority and immutable release identity.

## Actors and trust boundaries

| Actor / boundary | Trusted for | Not trusted for |
|---|---|---|
| Anonymous browser | routing to public endpoints | order ownership, payment truth, arbitrary object access |
| Logged-in customer | own WooCommerce account/order actions | another customer's orders/tokens; provider truth |
| WooCommerce manager/admin | merchant configuration within capabilities | bypassing provider/payment invariants; raw secrets in logs/public output |
| UPayments authenticated API response | provider-side transaction evidence after strict binding | arbitrary redirect hosts; undocumented semantics beyond frozen contract |
| UPayments browser/webhook callback | lookup/routing cursor only | payment success/failure authority by itself |
| WP-Cron | dispatch of locally scheduled bounded work | permission to duplicate ambiguous financial mutations |
| Database/object cache | persisted state to validate | automatically trusted security evidence merely because it is stored |
| GitHub Actions dependencies | CI execution at reviewed immutable commit pins | floating mutable tags/branches |
| Third-party frontend CDN | none required for payment correctness | checkout execution/style dependency by default |

## Entry-point census

### Public / customer

- `woocommerce_api_wc_upayments` browser return, provider webhook and historical status poll;
- Classic checkout POST fields;
- WooCommerce Store API / Blocks extension data;
- My Account subscription pause/resume/unsubscribe actions;
- My Account order filtering and subscription display;
- thank-you/order display metadata;
- saved-card rendering/retrieval for authenticated users.

### Privileged

- WooCommerce payment-gateway settings save;
- migration admin submenu (`manage_woocommerce` + nonce);
- product custom subscription metadata save;
- order-admin diagnostics/rendering;
- WP-CLI migration command.

### Background / machine

- `simplixpay_upayments_reconcile_order` bounded payment-status reconciliation;
- `upay_process_subscriptions` subscription scheduler and cycle journal;
- outbound UPayments Charge/Create/Retrieve/Status/auto-deduct transports;
- GitHub Actions quality workflow.

## Findings and disposition

### SEC-01 — Public historical order-status poll accepted order ID as authority

**Severity:** P1
**State:** DONE / VERIFIED

The inherited `?wc-api=wc_upayments&get_order_status=...&wc_order_id=N` path delegated around the hardened payment lifecycle and read `UPayments_WHS` by numeric post/order ID without an order key, exact logged-in ownership, or even an UPayments payment-method preflight.

This is an IDOR/privacy boundary defect even though the returned field is narrow. Numeric object identifiers are enumerable and cannot grant access.

Closure contract:

- hardened priority-5 callback intercepts the legacy poll before inherited priority 10;
- request is read-only GET;
- strict positive decimal order ID;
- target must be an UPayments order;
- access requires either exact logged-in owner or exact WooCommerce order key;
- guest compatibility is preserved only via order key;
- status output is allowlisted to `wait|pending|failed|completed|cancelled`;
- unknown/malformed stored values collapse to `wait`;
- unauthorized/nonexistent/non-UPayments targets share a generic unavailable response;
- inherited gateway method delegates to the same boundary as defense in depth.

### SEC-02 — Subscription state changes used nonce-bearing GET URLs

**Severity:** P1
**State:** DONE / VERIFIED

The current handler already checks exact logged-in order ownership and an action-specific WordPress nonce. That prevents ordinary cross-user IDOR and CSRF. The remaining problem is that pause/resume/unsubscribe are state-changing operations represented as GET URLs. Browsers, prefetchers, link scanners, history replays or copied URLs can re-request a valid nonce-bearing URL.

Closure contract:

- mutations use POST only;
- links become POST forms with hidden action/order ID and action-specific nonce;
- exact owner authorization remains mandatory;
- target must be an UPayments order;
- target must be a real non-auto-deduction subscription with a recognized plan/interval;
- transition must be valid for current subscription state;
- nonce is checked only after object/owner preflight;
- redirects remain local WooCommerce account URLs.

### SEC-03 — Third-party CSS/font dependencies loaded on checkout

**Severity:** P2
**State:** DONE / VERIFIED

Classic checkout currently requests Google Fonts and cdnjs Font Awesome. Neither is required for payment correctness. They add third-party availability, privacy and supply-chain boundaries on the payment page.

Closure contract:

- remove those external checkout stylesheet requests;
- use site/system typography;
- replace Font Awesome chevrons in both classic and Blocks renderers with local text/CSS presentation;
- do not add a replacement remote asset dependency.

### SEC-04 — Plain persisted/provider values permitted broader HTML than necessary

**Severity:** P2
**State:** DONE / VERIFIED

Several legacy display/settings surfaces are safe in normal operation but use broader output handling than their data contract warrants, including plain provider payment status/ID through `wp_kses_post()` and stored multimerchant values echoed into attributes without explicit `esc_attr()`.

Closure contract:

- plain payment metadata uses `esc_html()`;
- stored setting values in HTML attributes use `esc_attr()`;
- checkout templates use explicit GET markers instead of `$_REQUEST`;
- customer/provider data is never upgraded into trusted HTML merely because it came from storage.

### SEC-05 — Product custom-meta callback relied only on WooCommerce upstream save ordering

**Severity:** P2 defense in depth
**State:** DONE / VERIFIED

WooCommerce's current product meta-box pipeline verifies `woocommerce_meta_nonce` against `woocommerce_save_data`, binds `post_ID`, checks `edit_post`, then fires `woocommerce_process_product_meta`. The plugin's callback is therefore currently behind upstream authorization, but it performs its own write and should defend its boundary explicitly rather than relying solely on hook provenance.

Closure contract mirrors WooCommerce's save preconditions locally:

- WooCommerce nonce present and valid;
- posted product ID exactly matches callback ID;
- current user has `edit_post` for that product;
- field value is unslashed and sanitized before persistence.

## Verified existing controls retained

The security gate must not regress already-reviewed protections:

- `PaymentLifecycle` intercepts the historical callback at priority 5 before inherited priority 10;
- callback request values merge GET/POST conflict-safely and exclude cookies/`$_REQUEST`;
- browser/webhook fields are routing evidence only;
- `StatusVerifier` validates exact HTTPS UPayments host/path before applying the bearer token, disables redirects, verifies TLS and uses finite timeout;
- authenticated provider transaction binds track, provider order ID, Woo order reference, currency and exact canonical decimal amount;
- capture uses WooCommerce `payment_complete()` and cannot resurrect refunded/verified orders;
- indeterminate outcomes reconcile boundedly and never issue a new Charge;
- per-order lifecycle lock prevents concurrent state mutation/TOCTOU;
- H12 customer/card token identity and Phase 9I attribution contracts remain frozen;
- migration admin requires `manage_woocommerce` and nonce; CLI has no API-key argument and execute requires explicit confirmation;
- diagnostic gateway logging is opt-in and complex payloads are omitted;
- GitHub Actions in the quality workflow remain immutable full-SHA pins and run with read-only contents permission.

## Explicit boundaries not falsely closed here

### Provider webhook signature/HMAC

The reviewed public UPayments material has not established a complete stable signature-header/canonicalization/key contract. This gate must not invent one. Ordinary webhook/browser callback input remains non-authoritative: paid/failed/cancelled state is derived only after authenticated Get Payment Status and exact local binding.

A future first-party signature contract may add webhook-level authentication as defense in depth without replacing authenticated status verification.

### Subscription auto-deduction

The historical scheduler has substantial independent H12 safety work: persistent per-cycle claims, claimed→dispatching boundary before POST, no automatic retry after ambiguous dispatch, held-state recovery and persistence verification. However its provider response semantics are not the closed ordinary-checkout lifecycle contract and it retains historical token/order evidence by design.

This security gate records that surface and protects its no-duplicate-charge journal invariants; it does **not** turn auto-deduction into broadly feature-certified recurring billing. Provider semantics, identity upgrade behavior and end-to-end subscription certification remain later feature-certification work.

### Automatic refunds

Still unsupported. The security gate must not add a mutating refund transport before a durable provider idempotency/reconciliation contract exists.

## Required executable security gate

`tests/harness/security-threat-model-harness.php` is permanent once this gate merges. It covers:

- public order-status ID parsing, exact owner/order-key authorization and status allowlist;
- priority-5 interception and defense-in-depth delegation;
- POST-only subscription mutation, owner/nonce/order/subscription preflight;
- removal of remote checkout font/icon dependencies;
- plain-data escaping contracts;
- explicit Woo product-meta save authorization;
- preservation of provider status host/TLS/redirect/Bearer controls;
- preservation of migration capability/nonce controls;
- immutable full-SHA GitHub Actions pins;
- existence and base anchor of this security control record.

It must run inside the permanent `H12 Regression Harness` job together with every existing Phase 0, Phase 9I, Provider Lifecycle and H12 regression suite.

Final verified implementation-head security characterization: **81 PASS / 0 FAIL**. The exact cleaned PR #17 head `fba12225899c3e01d6b23a6bba2f757a3b5f6a4a` passed full merge-ref Quality Gates run #88; merged `main` `01f3fc59eed8641b3e5372558f61a7a0f0cdfac9` passed the complete push-triggered workflow again in run #89.

## Merge/closure process

1. implement only the characterized bounded fixes above;
2. run the security harness plus complete existing regression stack;
3. open PR from the exact verified base lineage;
4. inspect exact diff and automated review findings;
5. resolve every valid finding and rerun the exact final merge-ref;
6. squash-merge pinned exact head only;
7. verify `main`, tree, sole parent, signature, branch deletion and push-triggered full Quality Gates;
8. only then create the separate security closure reconciliation tranche;
9. closure tranche updates `PROJECT-STATUS.md`, `NEW-CHAT-HANDOFF.md`, the living Master Engineering Playbook, README/changelog/roadmap/audit and this file to exact post-merge truth;
10. closure merge must itself receive review, full regression and post-merge verification before Security Threat-Model Closure becomes DONE / VERIFIED.

## Verified implementation closure evidence

- implementation PR: **#17**;
- final reviewed implementation head: `fba12225899c3e01d6b23a6bba2f757a3b5f6a4a`;
- exact final merge-ref Quality Gates run #88: **SUCCESS**;
- final security harness: **81 PASS / 0 FAIL**;
- one valid automated P2 review finding (Checkout Blocks Font Awesome dependency) was fixed with permanent Blocks regression coverage before final approval;
- squash merge on `main`: `01f3fc59eed8641b3e5372558f61a7a0f0cdfac9`;
- tree: `e0027005f059fad03d8c08273b7aac6553c45f53`;
- sole parent: `08054a93c619f3c34fef747a6e530abce1e8986e`;
- GitHub signature: **VERIFIED**;
- push-triggered post-merge Quality Gates run #89: **SUCCESS**;
- implementation branch: **deleted after verified merge**;
- closure reconciliation: **PR #18**, documentation/control-plane only, with no payment/runtime behavior change.

The bounded threat-model gate is therefore closed. This remains deliberately narrower than penetration testing, PCI/compliance certification, broad platform/feature certification, performance certification, or public-production readiness.

## New-chat continuity requirement

This record is a required clean-chat source after implementation merges. A new conversation must be able to reconstruct, without hidden memory:

- exact latest verified `main` SHA/tree;
- exact security implementation/closure PRs;
- security harness counts;
- fixed findings and frozen invariants;
- unresolved provider/feature boundaries;
- current next gate: **Architecture & Code-Quality Foundation — DISCOVERY**, and what work is prohibited before it.

`PROJECT-STATUS.md` remains the first current-state authority, with `NEW-CHAT-HANDOFF.md` as the concise operational restart. The Master Engineering Playbook living state must agree with both while dated historical sections remain historical.
