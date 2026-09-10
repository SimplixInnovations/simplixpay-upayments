#!/usr/bin/env python3
from pathlib import Path
import hashlib

GATEWAY_PATH = Path('UPayments.php')
ARCHITECTURE_PATH = Path('tests/harness/architecture-foundation-harness.php')
EXPECTED_GATEWAY_BLOB = 'ab54ec1eb7241e9107d6d4fc55ffc4682c40e96d'


def git_blob_sha(data: bytes) -> str:
    return hashlib.sha1(b'blob ' + str(len(data)).encode() + b'\0' + data).hexdigest()


gateway = GATEWAY_PATH.read_bytes()
actual_blob = git_blob_sha(gateway)
if actual_blob != EXPECTED_GATEWAY_BLOB:
    raise SystemExit(f'Unexpected UPayments.php preimage: {actual_blob}')

text = gateway.decode('utf-8')
replacements = {
    "            $save_card_enabled  = ('yes' == $this->get_option('enable_save_card'));":
        "            $save_card_enabled = ($this->get_option('enable_save_card') === 'yes');",
    "            $template_args = array('gateway' => $this,'save_card_enabled' => ('yes' == $save_card_enabled));":
        "            $template_args = array('gateway' => $this, 'save_card_enabled' => $save_card_enabled);",
    "            $use_new_design = ($this->get_option('use_new_design') == 'yes') ? true : false;":
        "            $use_new_design = ($this->get_option('use_new_design') === 'yes');",
}

for old, new in replacements.items():
    if text.count(old) != 1:
        raise SystemExit(f'Expected exactly one presentation preimage: {old}')
    text = text.replace(old, new, 1)

new_gateway = text.encode('utf-8')
GATEWAY_PATH.write_bytes(new_gateway)

architecture = ARCHITECTURE_PATH.read_text(encoding='utf-8')
old_ratchet = '$acceptedGatewayBytes = 86195;'
if architecture.count(old_ratchet) != 1:
    raise SystemExit('Expected exactly one architecture byte-ratchet preimage')
ARCHITECTURE_PATH.write_text(
    architecture.replace(old_ratchet, f'$acceptedGatewayBytes = {len(new_gateway)};', 1),
    encoding='utf-8',
)

print(f'UPayments.php bytes: {len(gateway)} -> {len(new_gateway)}')
print(f'UPayments.php preimage blob: {actual_blob}')
