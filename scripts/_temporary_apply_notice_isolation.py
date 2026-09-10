from pathlib import Path

expected_size = 86359
gateway_path = Path('UPayments.php')
source = gateway_path.read_text()
if gateway_path.stat().st_size != expected_size:
    raise SystemExit(f'unexpected UPayments.php size: {gateway_path.stat().st_size}')

target = '                wc_clear_notices();\n'
if source.count(target) != 1:
    raise SystemExit(f'notice-clear target count={source.count(target)}')
source = source.replace(target, '', 1)
gateway_path.write_text(source, newline='\n')
new_size = gateway_path.stat().st_size
if new_size != 86323:
    raise SystemExit(f'unexpected corrected UPayments.php size: {new_size}')

arch_path = Path('tests/harness/architecture-foundation-harness.php')
arch = arch_path.read_text()
old_ratchet = '$acceptedGatewayBytes = 86359;'
if arch.count(old_ratchet) != 1:
    raise SystemExit(f'architecture ratchet count={arch.count(old_ratchet)}')
arch = arch.replace(old_ratchet, '$acceptedGatewayBytes = 86323;')
arch_path.write_text(arch, newline='\n')
print(f'UPAYMENTS_BYTES={new_size}')
