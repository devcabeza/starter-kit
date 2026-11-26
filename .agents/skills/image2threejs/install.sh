#!/usr/bin/env bash
# Install dsh-skill-image2threejs into the DSH user skills root.
set -euo pipefail
DEST="${DSH_HOME:-$HOME/.dsh}/skills/image2threejs"
mkdir -p "$(dirname "$DEST")"
if [ -d "$DEST" ]; then
  echo "Updating existing $DEST"
  git -C "$DEST" pull --ff-only
else
  git clone https://github.com/lijiejoy/dsh-skill-image2threejs.git "$DEST"
fi
echo "Installed. Restart your DSH session (or wait for the catalog refresh) to use the 'image2threejs' skill."
