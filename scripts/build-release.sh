#!/usr/bin/env bash
set -euo pipefail

if [[ $# -ne 1 ]]; then
  echo "Usage: $0 <output-directory>" >&2
  exit 64
fi

ROOT="$(git rev-parse --show-toplevel)"
OUT="$1"
IDENTITY_PATH="src/Release/Identity.php"
DISTIGNORE_PATH=".distignore"

git -C "$ROOT" cat-file -e "HEAD:$IDENTITY_PATH" 2>/dev/null || {
  echo "Missing release identity in Git HEAD: $IDENTITY_PATH" >&2
  exit 65
}
git -C "$ROOT" cat-file -e "HEAD:$DISTIGNORE_PATH" 2>/dev/null || {
  echo "Missing distribution contract in Git HEAD: $DISTIGNORE_PATH" >&2
  exit 66
}

VERSION="$(git -C "$ROOT" show "HEAD:$IDENTITY_PATH" | php -r '
$source = stream_get_contents(STDIN);
if (!is_string($source) || !preg_match("/public const VERSION = '\''([^'\'']+)'\'';/", $source, $m)) {
    exit(1);
}
echo $m[1];
')"
[[ -n "$VERSION" ]] || { echo "Invalid empty release version" >&2; exit 67; }

SLUG="sucheckout-upayments"
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
slug = "sucheckout-upayments"

patterns = []
distignore = subprocess.check_output(
    ["git", "-C", str(root), "show", "HEAD:.distignore"],
    text=True,
)
for raw in distignore.splitlines():
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
    ["git", "-C", str(root), "ls-tree", "-r", "-z", "HEAD"],
    check=True,
    stdout=subprocess.PIPE,
)
entries = []
for record in proc.stdout.split(b"\0"):
    if not record:
        continue
    metadata, path_bytes = record.split(b"\t", 1)
    mode, object_type, object_sha = metadata.decode("ascii").split(" ", 2)
    relative = path_bytes.decode("utf-8")
    if excluded(relative):
        continue
    if object_type != "blob" or mode not in {"100644", "100755"}:
        raise SystemExit(f"Unsupported release tree entry: {mode} {object_type} {relative}")
    entries.append((relative, object_sha))
entries.sort(key=lambda item: item[0])

if not entries:
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
    for relative, object_sha in entries:
        blob = subprocess.run(
            ["git", "-C", str(root), "cat-file", "blob", object_sha],
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
print(f"RELEASE FILES: {len(entries)}")
PY