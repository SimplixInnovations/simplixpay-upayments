from pathlib import Path
import hashlib

path = Path('UPayments.php')
raw = path.read_bytes()
expected_sha1 = 'b747dc926f6c2ad5b092fc27029363350f55a962'
actual_sha1 = hashlib.sha1(b'blob %d\0' % len(raw) + raw).hexdigest()
if actual_sha1 != expected_sha1:
    raise SystemExit(f'Unexpected UPayments.php blob preimage: {actual_sha1}')

text = raw.decode('utf-8')
marker = "            $this->has_fields         = true; // Required for custom forms like Save Card/Design variations.\n\n"
late = "            // Load settings and hooks\n            $this->init_form_fields();\n            $this->init_settings();\n\n"
if text.count(marker) != 1:
    raise SystemExit('Constructor metadata marker count is not exactly one')
if text.count(late) != 1:
    raise SystemExit('Late initialization block count is not exactly one')

# Preserve byte content except for moving the existing initialization block.
text = text.replace(late, '', 1)
text = text.replace(marker, marker + late, 1)

# Exact behavioral-order invariants.
pos_fields = text.find('$this->init_form_fields();')
pos_settings = text.find('$this->init_settings();')
pos_title = text.find('$this->title = $this->get_option("title");')
if min(pos_fields, pos_settings, pos_title) < 0:
    raise SystemExit('Required constructor statements missing after transform')
if not (pos_fields < pos_settings < pos_title):
    raise SystemExit('Initialization does not precede option-backed property hydration')
if text.count('$this->init_form_fields();') != 1 or text.count('$this->init_settings();') != 1:
    raise SystemExit('Initialization calls are not exactly once')

out = text.encode('utf-8')
if len(out) != len(raw):
    raise SystemExit(f'Pure move unexpectedly changed byte length: {len(raw)} -> {len(out)}')
path.write_bytes(out)
print(f'OK: moved form/settings initialization before property reads at unchanged {len(out)} bytes')
