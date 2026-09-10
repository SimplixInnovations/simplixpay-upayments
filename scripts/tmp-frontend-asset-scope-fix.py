from __future__ import annotations

import hashlib
from pathlib import Path

PATH = Path("UPayments.php")
EXPECTED_GIT_BLOB = "c23c8ef4f9302c853688b8ff7ab7dd8ba477bcaa"
EXPECTED_SIZE = 86171

raw = PATH.read_bytes()
actual_blob = hashlib.sha1(b"blob " + str(len(raw)).encode("ascii") + b"\0" + raw).hexdigest()
if actual_blob != EXPECTED_GIT_BLOB:
    raise SystemExit(f"refusing mutation: unexpected UPayments.php blob {actual_blob}")
if len(raw) != EXPECTED_SIZE:
    raise SystemExit(f"refusing mutation: unexpected UPayments.php size {len(raw)}")

text = raw.decode("utf-8")
old = """        public function enqueue_scripts() {
            $plugin_url = plugin_dir_url( __FILE__ );
            wp_enqueue_style('supcheckout-customer', $plugin_url . 'assets/css/customer.css', array(), SUPCHECKOUT_VERSION );
            // Check if we are on the checkout page AND the gateway is active
            if ( ! is_checkout() || ! $this->is_available() ) {
                return;
            }
            
"""
new = """        public function enqueue_scripts() {
            $plugin_url = plugin_dir_url( __FILE__ );
            // Check if we are on the checkout page AND the gateway is active
            if ( ! is_checkout() || ! $this->is_available() ) {
                return;
            }
            wp_enqueue_style('supcheckout-customer', $plugin_url . 'assets/css/customer.css', array(), SUPCHECKOUT_VERSION );
            
"""
if text.count(old) != 1:
    raise SystemExit(f"refusing mutation: expected exactly one enqueue preimage, found {text.count(old)}")

updated = text.replace(old, new, 1)
updated_raw = updated.encode("utf-8")
if len(updated_raw) != EXPECTED_SIZE:
    raise SystemExit(f"refusing mutation: byte size changed to {len(updated_raw)}")

fn = updated.index("        public function enqueue_scripts() {")
guard = updated.index("            if ( ! is_checkout() || ! $this->is_available() ) {", fn)
enqueue = updated.index("            wp_enqueue_style('supcheckout-customer'", fn)
if not guard < enqueue:
    raise SystemExit("refusing mutation: customer CSS is not behind the renderability guard")
if updated.count("wp_enqueue_style('supcheckout-customer'") != 1:
    raise SystemExit("refusing mutation: customer CSS enqueue count is not exactly one")

PATH.write_bytes(updated_raw)
print("PASS: moved customer CSS behind existing checkout/availability guard")
print(f"PASS: UPayments.php remains {EXPECTED_SIZE} bytes")
