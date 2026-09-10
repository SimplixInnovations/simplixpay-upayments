from pathlib import Path

expected_size = 86323
gateway_path = Path('UPayments.php')
source = gateway_path.read_text()
if gateway_path.stat().st_size != expected_size:
    raise SystemExit(f'unexpected UPayments.php size: {gateway_path.stat().st_size}')

old_title = "            $this->title = '';\n"
new_title = '            $this->title = $this->get_option("title");\n'
if source.count(old_title) != 1:
    raise SystemExit(f'title target count={source.count(old_title)}')
source = source.replace(old_title, new_title, 1)

old_comment = '            // Define user set variables\n'
if source.count(old_comment) != 1:
    raise SystemExit(f'constructor comment target count={source.count(old_comment)}')
source = source.replace(old_comment, '', 1)

gateway_path.write_text(source, newline='\n')
new_size = gateway_path.stat().st_size
if new_size != 86306:
    raise SystemExit(f'unexpected corrected UPayments.php size: {new_size}')

arch_path = Path('tests/harness/architecture-foundation-harness.php')
arch = arch_path.read_text()
old_ratchet = '$acceptedGatewayBytes = 86323;'
if arch.count(old_ratchet) != 1:
    raise SystemExit(f'architecture ratchet count={arch.count(old_ratchet)}')
arch = arch.replace(old_ratchet, '$acceptedGatewayBytes = 86306;')
arch_path.write_text(arch, newline='\n')
print(f'UPAYMENTS_BYTES={new_size}')
