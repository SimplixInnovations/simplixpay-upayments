# SimplixPay for UPayments — Enterprise Certification

**Status:** TASKS 1–7 DONE / VERIFIED; TASK 8 RELEASE-CANDIDATE CLOSEOUT CURRENT  
**Canonical repository:** `SimplixInnovations/simplixpay-upayments`

This record owns the reproducible enterprise certification evidence. It does not convert external/manual evidence into repository claims.

## Certified platform foundation

PR #47 established the permanent real WordPress/WooCommerce/MySQL certification matrix. PR #48 derived public support metadata and Woo feature declarations only from that executable evidence.

Permanent matrix: **16/16** WordPress/WooCommerce/PHP × legacy/HPOS cells:

| WordPress | WooCommerce | PHP | Legacy | HPOS |
|---|---|---:|---|---|
| 7.1 | 11.1.0 | 8.4 | **Verified** | **Verified** |
| 7.1 | 11.1.0 | 8.3 | **Verified** | **Verified** |
| 7.0.4 | 11.1.0 | 8.3 | **Verified** | **Verified** |
| 7.0.4 | 11.0.1 | 8.3 | **Verified** | **Verified** |
| 7.0.4 | 10.8.1 | 8.3 | **Verified** | **Verified** |
| 7.1 | 10.8.1 | 8.3 | **Verified** | **Verified** |
| 6.9.7 | 10.8.1 | 8.3 | **Verified** | **Verified** |
| 6.9.7 | 10.8.1 | 7.4 | **Verified** | **Verified** |

Every cell installs real WordPress/WooCommerce and verifies:

- activation with malformed pre-existing gateway settings without mutating the protected serialized option;
- Classic gateway ID `upayments` registration;
- standard Cart/Checkout Blocks registration and exact availability semantics;
- declared public support metadata;
- Woo feature registry compatibility for `cart_checkout_blocks` and `custom_order_tables`;
- real Woo order CRUD through the requested authoritative storage with protected SimplixPay/UPayments metadata.

Public declarations authorized by this matrix:

- WordPress minimum 6.9 / tested 7.1;
- WooCommerce minimum 10.8 / tested 11.1;
- PHP minimum 7.4;
- Cart/Checkout Blocks compatible;
- HPOS `custom_order_tables` compatible.

PHP 7.4 is an EOL compatibility floor, not the recommended modern runtime.

## Bounded provider public-sandbox certification — VERIFIED

PR #49 added `tests/provider/sandbox-charge-smoke.php` and its permanent workflow. It uses only UPayments' documented public non-whitelabel test bearer token and performs exactly one bounded sandbox Charge initialization.

Verified boundary:

- endpoint derived through Simplix `EndpointResolver(true)`;
- HTTPS `sandboxapi.upayments.com/api/v1/charge`;
- TLS verification and redirects disabled in transport;
- request JSON created successfully;
- exact HTTP 201;
- valid response JSON with strict `status === true` and structured data;
- returned payment link accepted by the production redirect normalizer and remains HTTPS on the bounded UPayments sandbox host.

The workflow never follows the payment link, enters card data, captures/completes payment, polls status, refunds, saves/retrieves a card, auto-deducts or uses a merchant production credential. It certifies **transport and Charge-initialization schema only**.

## Feature and operations certification — DONE / VERIFIED

Task 6 / PR #51 final head `355a871636f2df00c0bd7357a810289be284b58c` passed the complete 16-cell matrix plus Quality/H12, packaged release smoke and CodeQL, then merged as `6c19dbcfab607f81c4ff28f7bd088a87575adbf3`.

Permanent real-runtime evidence covers:

### Saved cards / token identity

- guest establishment rejected before provider transport or identity initialization;
- authenticated canonical token identity established in real WordPress user storage through a bounded deterministic provider fixture;
- exact customer-token binding for saved-card retrieval;
- exact selected-card membership succeeds;
- foreign card fails;
- malformed provenance fails closed before retrieval.

### Subscriptions

- real Woo subscription products/orders;
- exact `_upay_disable_subscription=yes` opt-out rejects before provider transport;
- mixed subscription/normal order rejects before provider transport;
- guest subscription rejects before provider transport;
- strict plan/interval contract;
- eligible Classic subscription advances only to the bounded token-initialization seam before the fixture blocks external mutation.

The challenge run discovered a real production defect: `CustomerTokenIdentity::inspect_bootstrap_history()` could query an out-of-range page after already scanning the exact reported total; WooCommerce could then reset pagination metadata and cause a false `total_changed` / `legacy_migration_required` blocker. Fix `d7176d7d028978d41de5c3c0516d7347bb14faec` terminates once the exact total is scanned and remains protected by the real-runtime test.

### One additional merchant

- exactly one `extraMerchantData` allocation;
- allocation amount equals exact order amount;
- IBAN and charge-type contract preserved;
- malformed configuration rejects before Charge.

No arbitrary marketplace split-routing claim is made.

### Operations / data retention

- merchant settings survive activation/deactivation/reactivation/uninstall-hook execution;
- historical payment/token metadata survives;
- canonical token identity secret and user provenance survive byte-for-byte;
- token/scope/generation binding survives lifecycle transitions;
- migration CLI/admin modules boot only in intended contexts;
- boot/runtime evidence exposes no merchant credential material;
- uninstall remains non-destructive by default.

## Deterministic artifact certification — DONE / VERIFIED

Task 5 / PR #50 established:

- canonical `simplixpay-upayments-0.1.0.zip`;
- exact distribution set and file bytes from Git `HEAD` tree/blobs;
- deterministic paths/timestamps/modes/compression within the defined toolchain;
- ZIP SHA-256 sidecar and sorted per-file manifest;
- independent PHP `ZipArchive` inspection;
- explicit release-path allowlist;
- exact ZIP-path equality with the HEAD distribution set;
- byte-for-byte source binding;
- self-consistent tampered ZIP rejection;
- dirty worktree/staged-index isolation;
- packaged real WordPress/WooCommerce legacy+HPOS runtime smoke.

Task 5 merged as `54b1fbcc280b92372bd93baf929d6a746cfd3959` after exact-head green evidence and passed post-merge verification.

## Existing-install / release-identity certification — DONE / VERIFIED

Task 7 / PR #52 final head `dd550eb6af86262aabfd50479407903172327726` ran current and floor upgrade cells. It verified:

- same-basename active force-upgrade;
- exact merchant-settings retention;
- historical gateway/provider/customer-token/subscription metadata retention;
- `upay_process_subscriptions` schedule continuity;
- `wc_upayments` callback continuity;
- deactivate/reactivate;
- rollback to the prior candidate and return to current;
- duplicate-root package characterization.

A controlled hypothetical package that changed only `UPayments.php` to `simplixpay-upayments.php` failed the safe active-upgrade contract in both cells: `active_plugins` retained the old basename, the target basename was inactive, and the runtime did not load.

First-stable decision:

- main file `UPayments.php`;
- basename `simplixpay-upayments/UPayments.php`;
- text domain `upayments`.

The eventual targets remain deferred migrations. The installable package still contains 70 explicit PHP translation calls bound to `upayments`; no coordinated WPML/String Translation migration has been certified.

Task 7 merged as `02b8d1c2851faabe020f23bbe84ebcca43a4827d`. Post-merge `main` passed Quality #545, Compatibility #73, Release Artifact #27 and CodeQL #349.

## External/manual evidence classification

The following are deliberately **not** converted into broad repository claims:

| Evidence | Classification |
|---|---|
| Production merchant payment completion | External/manual merchant-account evidence |
| Apple Pay / Google Pay / Samsung Pay completion | External account/device/provider evidence |
| WPML/WCML/String Translation | Dedicated commercial-plugin runtime evidence |
| Multicurrency | Dedicated WCML/store/provider economics evidence |
| Arabic/RTL | Real UI/runtime validation |
| Browser/device/theme interoperability | Real browser/device matrix |
| Accessibility | Keyboard/focus/screen-reader/contrast/error-state audit |
| Performance/stability | Representative store/load evidence with explicit thresholds |
| Penetration testing / PCI / legal compliance | External organizational evidence |
| Provider webhook signature | Deferred until a stable published signature contract exists |
| Live subscription auto-deduction | External/non-idempotent mutation evidence |

Automatic Woo refunds remain **unsupported** pending durable idempotency/reconciliation design. Arbitrary marketplace multi-split remains **unsupported**; only one additional merchant is certified.

## Task 8 — Enterprise Release Candidate Closeout

Current final gate:

1. reconcile living docs/governance;
2. verify repository hygiene;
3. run Quality/H12 + 16-cell Compatibility + Release Artifact/upgrade + bounded Provider Sandbox + CodeQL/dependency audit on one exact head;
4. run the reserved final whole-plugin Codex review after primary evidence is green;
5. fix every valid finding and rerun affected evidence;
6. exact-head squash merge and post-merge verification.

No public 1.0/tag/GitHub Release/WordPress.org publication is created by this engineering closeout alone.
