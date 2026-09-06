#!/usr/bin/env bash
set -euo pipefail

if [[ $# -ne 1 ]]; then
  echo "Usage: $0 <release-zip>" >&2
  exit 64
fi

ROOT="$(git rev-parse --show-toplevel)"
ZIP="$1"
[[ -f "$ZIP" ]] || { echo "Release ZIP not found: $ZIP" >&2; exit 65; }

ROOT="$ROOT" ZIP="$ZIP" python3 <<'PY'
import hashlib
import os
import pathlib
import posixpath
import re
import zipfile

root = pathlib.Path(os.environ["ROOT"])
zip_path = pathlib.Path(os.environ["ZIP"])
slug = "simplixpay-upayments"

identity = (root / "src/Release/Identity.php").read_text(encoding="utf-8")
match = re.search(r"public const VERSION = '([^']+)';", identity)
if not match:
    raise SystemExit("Cannot read canonical version")
version = match.group(1)

expected_name = f"{slug}-{version}.zip"
if zip_path.name != expected_name:
    raise SystemExit(f"Unexpected ZIP filename: {zip_path.name} != {expected_name}")

checksum_path = pathlib.Path(str(zip_path) + ".sha256")
manifest_path = zip_path.with_name(f"{slug}-{version}.manifest.sha256")
if not checksum_path.is_file():
    raise SystemExit("Missing ZIP checksum sidecar")
if not manifest_path.is_file():
    raise SystemExit("Missing release manifest sidecar")

actual_zip_hash = hashlib.sha256(zip_path.read_bytes()).hexdigest()
if checksum_path.read_text(encoding="utf-8") != f"{actual_zip_hash}  {zip_path.name}\n":
    raise SystemExit("ZIP checksum sidecar does not match artifact bytes")

with zipfile.ZipFile(zip_path, "r") as archive:
    infos = archive.infolist()
    names = [i.filename for i in infos]
    if not names:
        raise SystemExit("Release ZIP is empty")
    if len(names) != len(set(names)):
        raise SystemExit("Release ZIP contains duplicate paths")
    if names != sorted(names):
        raise SystemExit("Release ZIP paths are not sorted deterministically")

    prefix = slug + "/"
    for info in infos:
        name = info.filename
        if info.is_dir():
            raise SystemExit(f"Unexpected directory entry: {name}")
        if not name.startswith(prefix):
            raise SystemExit(f"Path escapes canonical root: {name}")
        if "\\" in name or name.startswith("/") or "\x00" in name:
            raise SystemExit(f"Unsafe artifact path: {name}")
        rel = name[len(prefix):]
        if posixpath.normpath(rel) != rel or rel in ("", ".", "..") or rel.startswith("../"):
            raise SystemExit(f"Unsafe normalized path: {name}")

    forbidden_exact = {
        ".distignore", ".editorconfig", ".gitattributes", ".gitignore",
        "AGENTS.md", "composer.json", "composer.lock", "phpcs.xml.dist",
        "phpstan.neon.dist", "phpunit.xml.dist", "CONTRIBUTING.md",
        "MAINTAINERS.md", "SUPPORT.md", "UPSTREAM.md",
    }
    forbidden_prefixes = (".github/", ".cache/", ".phpunit.cache/", "tests/", "vendor/", "docs/", "scripts/")
    rel_names = [n[len(prefix):] for n in names]
    for rel in rel_names:
        if rel in forbidden_exact or rel.startswith(forbidden_prefixes):
            raise SystemExit(f"Forbidden development/control path: {rel}")

    required = {
        "UPayments.php", "index.php", "uninstall.php", "LICENSE",
        "README.md", "CHANGELOG.md", "NOTICE.md", "SECURITY.md",
        "src/Release/Identity.php",
        "includes/class-wc-gateway-upayments-blocks.php",
    }
    missing = sorted(required.difference(rel_names))
    if missing:
        raise SystemExit("Missing required release files: " + ", ".join(missing))
    for subtree in ("src/", "includes/", "assets/", "templates/"):
        if not any(rel.startswith(subtree) for rel in rel_names):
            raise SystemExit(f"Missing required runtime subtree: {subtree}")

    plugin_source = archive.read(prefix + "UPayments.php").decode("utf-8")
    identity_source = archive.read(prefix + "src/Release/Identity.php").decode("utf-8")
    if "Plugin Name: SimplixPay for UPayments" not in plugin_source:
        raise SystemExit("Packaged plugin name is invalid")
    if f"Version: {version}" not in plugin_source:
        raise SystemExit("Packaged plugin version mismatches canonical identity")
    if "Text Domain: upayments" not in plugin_source:
        raise SystemExit("Transitional text domain changed during packaging")
    if "public const LEGACY_MAIN_FILE = 'UPayments.php';" not in identity_source:
        raise SystemExit("Transitional main-file identity changed during packaging")

    parsed = {}
    for line in manifest_path.read_text(encoding="utf-8").splitlines():
        m = re.fullmatch(r"([0-9a-f]{64})  (.+)", line)
        if not m:
            raise SystemExit(f"Malformed manifest line: {line}")
        digest, name = m.groups()
        if name in parsed:
            raise SystemExit(f"Duplicate manifest entry: {name}")
        parsed[name] = digest

    if set(parsed) != set(names):
        raise SystemExit("Manifest paths do not match ZIP contents")
    for name in names:
        digest = hashlib.sha256(archive.read(name)).hexdigest()
        if parsed[name] != digest:
            raise SystemExit(f"Manifest digest mismatch: {name}")

print(f"VERIFIED RELEASE ZIP: {zip_path.name}")
print(f"VERIFIED RELEASE SHA256: {actual_zip_hash}")
print(f"VERIFIED RELEASE FILES: {len(names)}")
PY
