from pathlib import Path


def replace_once(path, old, new, label):
    target = Path(path)
    source = target.read_text(encoding="utf-8")
    count = source.count(old)
    if count != 1:
        raise SystemExit(f"{label}: expected exactly one anchor, found {count}")
    target.write_text(source.replace(old, new, 1), encoding="utf-8")


replace_once(
    "UPayments.php",
    "require_once __DIR__ . '/src/Release/Identity.php';\nrequire_once __DIR__ . '/src/Admin/GatewaySettings.php';",
    "require_once __DIR__ . '/src/Release/Identity.php';\nrequire_once __DIR__ . '/src/Provider/MultiMerchantContract.php';\nrequire_once __DIR__ . '/src/Admin/GatewaySettings.php';",
    "bootstrap provider contract",
)

replace_once(
    "src/Admin/GatewaySettings.php",
    "namespace Simplixi\\SUPCheckout\\Admin;\n\n/**",
    "namespace Simplixi\\SUPCheckout\\Admin;\n\nuse Simplixi\\SUPCheckout\\Provider\\MultiMerchantContract;\n\n/**",
    "GatewaySettings import",
)

old_admin = """                foreach ($required as $key) {
                    if (!array_key_exists($key, $post_data)
                        || !is_string($post_data[$key])
                        || $post_data[$key] === ''
                    ) {
                        $multimerchant_missing = true;
                        break;
                    }
                }
"""
new_admin = old_admin + """                if (!$multimerchant_missing) {
                    $iban = $post_data['woocommerce_upayments_iban_number'];
                    $cc_charge = $post_data['woocommerce_upayments_cc_charge'];
                    $cc_charge_type = $post_data['woocommerce_upayments_cc_charge_type'];
                    $knet_charge = $post_data['woocommerce_upayments_knet_charge'];
                    $knet_charge_type = $post_data['woocommerce_upayments_knet_charge_type'];

                    if (!MultiMerchantContract::is_valid_iban($iban)
                        || !MultiMerchantContract::is_valid_commission_token($cc_charge)
                        || !MultiMerchantContract::is_valid_charge_type($cc_charge_type)
                        || !MultiMerchantContract::is_valid_commission_token($knet_charge)
                        || !MultiMerchantContract::is_valid_charge_type($knet_charge_type)
                    ) {
                        $multimerchant_invalid = true;
                        $multimerchant_missing = true;
                    }
                }
"""
replace_once("src/Admin/GatewaySettings.php", old_admin, new_admin, "GatewaySettings validation")

replace_once(
    "src/Payment/CheckoutPayload.php",
    "namespace Simplixi\\SUPCheckout\\Payment;\n\n/**",
    "namespace Simplixi\\SUPCheckout\\Payment;\n\nuse Simplixi\\SUPCheckout\\Provider\\MultiMerchantContract;\n\n/**",
    "CheckoutPayload import",
)
old_builder = """    public static function build_nonnegative_json_number_token($amount_str) {
        if (!is_string($amount_str)) {
            return null;
        }
        if (!preg_match('/^(?:0|[1-9][0-9]*)(?:\\.[0-9]+)?$/', $amount_str)) {
            return null;
        }
        if (preg_match('/\\s/', $amount_str)) {
            return null;
        }
        if (strlen($amount_str) > 22) {
            return null;
        }
        return $amount_str;
    }
"""
new_builder = """    public static function build_nonnegative_json_number_token($amount_str) {
        if (!MultiMerchantContract::is_valid_commission_token($amount_str)) {
            return null;
        }
        return $amount_str;
    }
"""
replace_once("src/Payment/CheckoutPayload.php", old_builder, new_builder, "CheckoutPayload token builder")

replace_once(
    "src/Payment/CheckoutOrchestrator.php",
    "namespace Simplixi\\SUPCheckout\\Payment;\n\nuse UPayments\\Token\\CustomerTokenIdentity;",
    "namespace Simplixi\\SUPCheckout\\Payment;\n\nuse Simplixi\\SUPCheckout\\Provider\\MultiMerchantContract;\nuse UPayments\\Token\\CustomerTokenIdentity;",
    "CheckoutOrchestrator import",
)
replace_once(
    "src/Payment/CheckoutOrchestrator.php",
    "if (!preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{11,30}\\\\z/', $iban)) {",
    "if (!MultiMerchantContract::is_valid_iban($iban)) {",
    "checkout IBAN validation",
)
replace_once(
    "src/Payment/CheckoutOrchestrator.php",
    "if (!preg_match('/^(?:0|[1-9][0-9]*)(?:\\.[0-9]+)?$/', $knet_charge_raw)) {",
    "if (!MultiMerchantContract::is_valid_commission_lexeme($knet_charge_raw)) {",
    "checkout KNET commission validation",
)
replace_once(
    "src/Payment/CheckoutOrchestrator.php",
    "if (!preg_match('/^(?:0|[1-9][0-9]*)(?:\\.[0-9]+)?$/', $cc_charge_raw)) {",
    "if (!MultiMerchantContract::is_valid_commission_lexeme($cc_charge_raw)) {",
    "checkout card commission validation",
)
old_types = """                // Reject non-canonical charge-type forms exactly.
                $valid_charge_types = array('fixed', 'percentage');
                if (!in_array($knet_charge_type, $valid_charge_types, true)) {
                    $gateway->log('MultiMerchant: invalid knetChargeType.', 'warning');
                    wc_add_notice(__('Payment request could not be completed. Please try again.', 'supcheckout'), 'error');
                    return array('result' => 'failure', 'redirect' => wc_get_checkout_url());
                }
                if (!in_array($cc_charge_type, $valid_charge_types, true)) {
"""
new_types = """                // Reject non-canonical charge-type forms exactly.
                if (!MultiMerchantContract::is_valid_charge_type($knet_charge_type)) {
                    $gateway->log('MultiMerchant: invalid knetChargeType.', 'warning');
                    wc_add_notice(__('Payment request could not be completed. Please try again.', 'supcheckout'), 'error');
                    return array('result' => 'failure', 'redirect' => wc_get_checkout_url());
                }
                if (!MultiMerchantContract::is_valid_charge_type($cc_charge_type)) {
"""
replace_once("src/Payment/CheckoutOrchestrator.php", old_types, new_types, "checkout charge-type validation")
