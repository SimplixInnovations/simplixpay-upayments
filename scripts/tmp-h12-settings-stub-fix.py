#!/usr/bin/env python3
from pathlib import Path
import hashlib

PATH = Path('tests/harness/_bootstrap.php')
EXPECTED_BLOB = '22a162bf4ea2a07fd255f176ee6dc4939533690f'


def git_blob_sha(data: bytes) -> str:
    return hashlib.sha1(b'blob ' + str(len(data)).encode() + b'\0' + data).hexdigest()


data = PATH.read_bytes()
actual = git_blob_sha(data)
if actual != EXPECTED_BLOB:
    raise SystemExit(f'Unexpected _bootstrap.php preimage: {actual}')

text = data.decode('utf-8')
old = """    class WC_Payment_Gateway {
        public $id; public $icon; public $method_title; public $method_description;
        public $has_fields; public $title; public $description; public $debug;
"""
new = """    class WC_Payment_Gateway {
        public $id; public $icon; public $method_title; public $method_description;
        // Mirrors WC_Settings_API in WooCommerce 10.8.1 and 11.1.0.
        public $settings = array();
        public $has_fields; public $title; public $description; public $debug;
"""
if text.count(old) != 1:
    raise SystemExit('Expected exactly one shared WC_Payment_Gateway stub preimage')

PATH.write_text(text.replace(old, new, 1), encoding='utf-8')
print(f'_bootstrap.php preimage blob: {actual}')
print('Added real WooCommerce settings-array contract to shared payment-gateway double')
