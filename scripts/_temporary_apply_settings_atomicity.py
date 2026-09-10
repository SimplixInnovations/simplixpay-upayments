from pathlib import Path
import re

gateway_path = Path('UPayments.php')
source = gateway_path.read_text()
if gateway_path.stat().st_size != 86457:
    raise SystemExit(f'unexpected UPayments.php size: {gateway_path.stat().st_size}')

pattern = re.compile(
    r"        public function process_admin_options\(\)\n"
    r"        \{\n.*?\n"
    r"        \}\n\n"
    r"        public function get_multimerchant_credentials\( \$order \) \{",
    re.S,
)
replacement = '''        public function process_admin_options()
        {
            $this->init_settings();
            $prepared = GatewaySettings::prepare_post_data($this->get_post_data());
            if ($prepared['api_key_missing'] || $prepared['multimerchant_missing']) {
                $message = $prepared['api_key_missing']
                    ? "Please enter UPayments API Key"
                    : "Please enter Multimerchant Configuration";
                WC_Admin_Settings::add_error(__($message, 'supcheckout'));
                return false;
            }

            $post_data = $prepared['post_data'];
            foreach ($this->get_form_fields() as $key => $field) {
                $this->settings[$key] = $this->get_field_value($key, $field, $post_data);
            }
            delete_option("upayments_maat");
            // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WooCommerce Settings API defines this dynamic core hook name.
            return update_option($this->get_option_key(), apply_filters("woocommerce_settings_api_sanitized_fields_" . $this->id, $this->settings));
        }

        public function get_multimerchant_credentials( $order ) {'''
source, count = pattern.subn(replacement, source)
if count != 1:
    raise SystemExit(f'process_admin_options replacement count={count}')
gateway_path.write_text(source, newline='\n')

new_size = gateway_path.stat().st_size
if new_size >= 86457:
    raise SystemExit(f'monolith did not shrink: {new_size}')

arch_path = Path('tests/harness/architecture-foundation-harness.php')
arch = arch_path.read_text()
old = '$acceptedGatewayBytes = 86457;'
if arch.count(old) != 1:
    raise SystemExit('architecture byte-ratchet marker count != 1')
arch = arch.replace(old, f'$acceptedGatewayBytes = {new_size};')
arch_path.write_text(arch, newline='\n')
print(f'UPAYMENTS_BYTES={new_size}')
