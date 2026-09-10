from pathlib import Path

expected_size = 86306
gateway_path = Path('UPayments.php')
source = gateway_path.read_text()
if gateway_path.stat().st_size != expected_size:
    raise SystemExit(f'unexpected UPayments.php size: {gateway_path.stat().st_size}')

old = """        public function getMode() {
            $mode = true;
            if ($this->testMode == 'no') {
                $mode = false;
            }
            return $mode;
        }
"""
new = """        public function getMode() {
            return $this->testMode === 'yes';
        }
"""
if source.count(old) != 1:
    raise SystemExit(f'getMode target count={source.count(old)}')
source = source.replace(old, new, 1)
gateway_path.write_text(source, newline='\n')

new_size = gateway_path.stat().st_size
if new_size != 86212:
    raise SystemExit(f'unexpected corrected UPayments.php size: {new_size}')

arch_path = Path('tests/harness/architecture-foundation-harness.php')
arch = arch_path.read_text()
old_ratchet = '$acceptedGatewayBytes = 86306;'
if arch.count(old_ratchet) != 1:
    raise SystemExit(f'architecture ratchet count={arch.count(old_ratchet)}')
arch = arch.replace(old_ratchet, '$acceptedGatewayBytes = 86212;')
arch_path.write_text(arch, newline='\n')

print(f'UPAYMENTS_BYTES={new_size}')
