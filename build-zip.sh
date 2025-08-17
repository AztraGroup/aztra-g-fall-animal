#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT_DIR/aztra-g-fall-animal"
zip -r "$ROOT_DIR/aztra-g.zip" .
