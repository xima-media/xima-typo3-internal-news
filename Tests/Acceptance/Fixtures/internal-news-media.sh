#!/usr/bin/env bash
#
# Fixture script: places the example media file into the default fileadmin
# storage so the sys_file row inserted by internal-news.sql resolves to a real
# file. Run automatically after the SQL import (see run_fixture_scripts in
# .ddev/.setup/scripts/utils.sh). $BASE_PATH is exported by that setup.
set -euo pipefail

FIXTURE_DIR="$(cd "$(dirname "$0")" && pwd)"
SOURCE="${FIXTURE_DIR}/files/internal-news-example.png"
TARGET_DIR="${BASE_PATH:-$(pwd)}/public/fileadmin"

mkdir -p "$TARGET_DIR"
cp "$SOURCE" "$TARGET_DIR/internal-news-example.png"
chmod 664 "$TARGET_DIR/internal-news-example.png"

echo "Copied example media to ${TARGET_DIR}/internal-news-example.png"
