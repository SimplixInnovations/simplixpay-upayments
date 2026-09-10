from pathlib import Path

branch_size = 86377
gateway = Path('UPayments.php')
source = gateway.read_text()
if gateway.stat().st_size != branch_size:
    raise SystemExit(f'unexpected UPayments.php size: {gateway.stat().st_size}')

old = '''            if ($prepared['api_key_missing'] || $prepared['multimerchant_missing']) {
                $message = $prepared['api_key_missing']
                    ? "Please enter UPayments API Key"
                    : "Please enter Multimerchant Configuration";
                WC_Admin_Settings::add_error(__($message, 'supcheckout'));
                return false;
            }
'''
new = '''            if ($prepared['api_key_missing'] || $prepared['multimerchant_missing']) {
                WC_Admin_Settings::add_error($prepared['api_key_missing']
                    ? __('Please enter UPayments API Key', 'supcheckout')
                    : __('Please enter Multimerchant Configuration', 'supcheckout'));
                return false;
            }
'''
if source.count(old) != 1:
    raise SystemExit(f'i18n target count={source.count(old)}')
source = source.replace(old, new)
gateway.write_text(source, newline='\n')
new_size = gateway.stat().st_size
if new_size != 86359:
    raise SystemExit(f'unexpected corrected UPayments.php size: {new_size}')

arch_path = Path('tests/harness/architecture-foundation-harness.php')
arch = arch_path.read_text()
old_ratchet = '$acceptedGatewayBytes = 86377;'
if arch.count(old_ratchet) != 1:
    raise SystemExit(f'architecture ratchet count={arch.count(old_ratchet)}')
arch = arch.replace(old_ratchet, '$acceptedGatewayBytes = 86359;')
arch_path.write_text(arch, newline='\n')
print(f'UPAYMENTS_BYTES={new_size}')
