# H12 Baseline and Canonical Repository Root

## Canonical root

On 2026-08-24, `SimplixInnovations/simplixpay-upayments` was established as a standalone canonical repository with a clean parentless root commit.

- canonical root: `1caf38410354322c1d842c28a40b0909ba31026d`
- root tree: `34594c00d243b59345ec9fbb3a88d2e1ec8f3efc`
- parents: none

The clean root intentionally contains the exact tree of the independently verified historical H12 merge while starting a new SimplixPay product history.

## Historical audit archive

The complete pre-product engineering history, pull-request discussions and H12 verification trail remain preserved at:

- repository: `SimplixInnovations/upayments-woocommerce`
- verified H12 merge: `93e9925247a8bfade626cb822136852fd96eaea2`
- merge tree: `34594c00d243b59345ec9fbb3a88d2e1ec8f3efc`

The archive should not be rewritten or deleted as part of normal SimplixPay development.

## Frozen production blobs copied into the root

- `UPayments.php`: `64c789e81ae4d292ef9b1d7382812c319a44bc25`
- `includes/Token/CustomerTokenIdentity.php`: `85430d37e9baf540842f5655b86ccf0eca3e6aea`
- `includes/class-wc-gateway-upayments-blocks.php`: `813d192d69c069eb7ee11df93acc9dbdf03e270a`
- `includes/Subscription/Cron/Scheduler.php`: `5251866d4df2d1326e7c09f0c8ec1d146c0bb325`
- `includes/Subscription/Cron/CycleClaim.php`: `c34d83e2d77cc65024fe663e4c378cecb2b17347`

These are evidence anchors, not permanent bans on future modifications. A later phase changing one must establish new exact reviewed evidence.
