#!/usr/bin/env python3
from pathlib import Path
import hashlib

GATEWAY_PATH = Path('UPayments.php')
ARCHITECTURE_PATH = Path('tests/harness/architecture-foundation-harness.php')
EXPECTED_GATEWAY_BLOB = 'f52dc13612b2cb152140b1d292a14296dae1f47f'


def git_blob_sha(data: bytes) -> str:
    return hashlib.sha1(b'blob ' + str(len(data)).encode() + b'\0' + data).hexdigest()


gateway = GATEWAY_PATH.read_bytes()
actual_blob = git_blob_sha(gateway)
if actual_blob != EXPECTED_GATEWAY_BLOB:
    raise SystemExit(f'Unexpected UPayments.php preimage: {actual_blob}')

text = gateway.decode('utf-8')
old_assignment = "            $this->isOrderComplete = $this->get_option('is_order_complete');"
new_assignment = """            $this->isOrderComplete = array_key_exists('is_order_complete', $this->settings)
              ? $this->settings['is_order_complete']
              : 'yes';"""
old_helper = """        public function getIsOrderComplete() {  
            $flag = true;   
            if ($this->isOrderComplete == 'no') { 
                $flag = false;  
            }   
            return $flag;   
        }"""
new_helper = """        public function getIsOrderComplete() {
            return $this->isOrderComplete === 'yes';
        }"""

if text.count(old_assignment) != 1:
    raise SystemExit('Expected exactly one is_order_complete assignment preimage')
if text.count(old_helper) != 1:
    raise SystemExit('Expected exactly one getIsOrderComplete helper preimage')

text = text.replace(old_assignment, new_assignment, 1)
text = text.replace(old_helper, new_helper, 1)
new_gateway = text.encode('utf-8')
GATEWAY_PATH.write_bytes(new_gateway)

architecture = ARCHITECTURE_PATH.read_text(encoding='utf-8')
old_ratchet = '$acceptedGatewayBytes = 86212;'
if architecture.count(old_ratchet) != 1:
    raise SystemExit('Expected exactly one architecture byte-ratchet preimage')
ARCHITECTURE_PATH.write_text(
    architecture.replace(old_ratchet, f'$acceptedGatewayBytes = {len(new_gateway)};', 1),
    encoding='utf-8',
)

print(f'UPayments.php bytes: {len(gateway)} -> {len(new_gateway)}')
print(f'UPayments.php preimage blob: {actual_blob}')
