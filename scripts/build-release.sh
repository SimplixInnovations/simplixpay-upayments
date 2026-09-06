#!/usr/bin/env bash
set -euo pipefail

if [[ $# -ne 1 ]]; then
  echo "Usage: $0 <output-directory>" >&2
  exit 64
fi

ROOT="$(git rev-parse --show-toplevel)"
OUT="$1"
IDENTITY="$ROOT/src/Release/Identity.php"
DISTIGNORE="$ROOT/.distignore"

[[ -f "$IDENTITY" ]] || { echo "Missing release identity: $IDENTITY" >&2; exit 65; }
[[ -f "$DISTIGNORE" ]] || { echo "Missing distribution contract: $DISTIGNORE" >&2; exit 66; }

VERSION="$(php -r '
$source = file_get_contents($argv[1]);
if (!is_string($source) || !preg_match("/public const VERSION = '\''([^'\'']+)'\'';/", $source, $m)) {
    exit(1);
}
echo $m[1];
' "$IDENTITY")"
[[ -n "$VERSION" ]] || { echo "Invalid empty release version" >&2; exit 67; }

SLUG="simplixpay-upayments"
ZIP="$OUT/$SLUG-$VERSION.zip"
CHECKSUM="$ZIP.sha256"
MANIFEST="$OUT/$SLUG-$VERSION.manifest.sha256"

mkdir -p "$OUT"
rm -f "$ZIP" "$CHECKSUM" "$MANIFEST"

ROOT="$ROOT" ZIP="$ZIP" CHECKSUM="$CHECKSUM" MANIFEST="$MANIFEST" python3 <<'PY'
import hashlib
import os
import pathlib
import subprocess
import zipfile

root = pathlib.Path(os.environ["ROOT"])
zip_path = pathlib.Path(os.environ["ZIP"])
checksum_path = pathlib.Path(os.environ["CHECKSUM"])
manifest_path = pathlib.Path(os.environ["MANIFEST"])
slug = "simplixpay-upayments"

patterns = []
for raw in (root / ".distignore").read_text(encoding="utf-8").splitlines():
    value = raw.strip()
    if not value or value.startswith("#"):
        continue
    if not value.startswith("/"):
        raise SystemExit(f"Unsupported non-root .distignore rule: {value}")
    patterns.append(value)

def excluded(path: str) -> bool:
    candidate = "/" + path
    for pattern in patterns:
        if pattern.endswith("/"):
            if candidate.startswith(pattern):
                return True
        elif candidate == pattern:
            return True
    return False

proc = subprocess.run(
    ["git", "-C", str(root), "ls-files", "-z"],
    check=True,
    stdout=subprocess.PIPE,
)
tracked = [p.decode("utf-8") for p in proc.stdout.split(b"\0") if p]
files = sorted(p for p in tracked if not excluded(p))

if not files:
    raise SystemExit("No release files selected")

manifest_lines = []
zip_path.parent.mkdir(parents=True, exist_ok=True)

with zipfile.ZipFile(
    zip_path,
    mode="w",
    compression=zipfile.ZIP_DEFLATED,
    compresslevel=9,
    strict_timestamps=True,
) as archive:
    for relative in files:
        blob = subprocess.run(
            ["git", "-C", str(root), "show", f"HEAD:{relative}"],
            check=True,
            stdout=subprocess.PIPE,
        ).stdout
        archive_name = f"{slug}/{relative}"
        info = zipfile.ZipInfo(archive_name, date_time=(1980, 1, 1, 0, 0, 0))
        info.create_system = 3
        info.external_attr = (0o100644 << 16)
        info.compress_type = zipfile.ZIP_DEFLATED
        archive.writestr(info, blob, compress_type=zipfile.ZIP_DEFLATED, compresslevel=9)
        manifest_lines.append(f"{hashlib.sha256(blob).hexdigest()}  {archive_name}\n")

manifest_path.write_text("".join(manifest_lines), encoding="utf-8", newline="\n")
zip_hash = hashlib.sha256(zip_path.read_bytes()).hexdigest()
checksum_path.write_text(f"{zip_hash}  {zip_path.name}\n", encoding="utf-8", newline="\n")

source = subprocess.check_output(["git", "-C", str(root), "rev-parse", "HEAD"], text=True).strip()
print(f"RELEASE SOURCE: {source}")
print(f"RELEASE ZIP: {zip_path}")
print(f"RELEASE SHA256: {zip_hash}")
print(f"RELEASE FILES: {len(files)}")
PY
