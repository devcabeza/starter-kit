#!/bin/bash

# ============================================================================
# generate-mobile-icons.sh
# 
# Generates Android app icons and splash screen from public/mobile-icons/logo.png
# This script is part of the starter-kit and should be run after setting up
# a new project to ensure the mobile app has the correct branding.
#
# Usage: ./scripts/generate-mobile-icons.sh [path-to-logo]
# Default: uses public/mobile-icons/logo.png
# ============================================================================

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"

# Source logo (default to public/mobile-icons/logo.png)
SOURCE_LOGO="${1:-$PROJECT_ROOT/public/mobile-icons/logo.png}"
RES_DIR="$PROJECT_ROOT/android/app/src/main/res"

# Check if source logo exists
if [ ! -f "$SOURCE_LOGO" ]; then
    echo -e "${RED}Error: Logo file not found at $SOURCE_LOGO${NC}"
    echo "Usage: $0 [path-to-logo.png]"
    echo ""
    echo "Expected location: public/mobile-icons/logo.png"
    exit 1
fi

# Check if ImageMagick is installed
if ! command -v convert &> /dev/null && ! command -v magick &> /dev/null; then
    echo -e "${RED}Error: ImageMagick is not installed.${NC}"
    echo "Install it with:"
    echo "  Ubuntu/Debian: sudo apt-get install imagemagick"
    echo "  macOS: brew install imagemagick"
    exit 1
fi

# Use magick if available, otherwise use convert
if command -v magick &> /dev/null; then
    CONVERT="magick"
else
    CONVERT="convert"
fi

echo -e "${YELLOW}🎬 Generating mobile icons from: $SOURCE_LOGO${NC}"
echo ""

# ============================================================================
# Generate regular launcher icons
# ============================================================================
echo -e "${GREEN}📱 Generating launcher icons...${NC}"

declare -A ICON_SIZES=(
    ["mipmap-mdpi"]=48
    ["mipmap-hdpi"]=72
    ["mipmap-xhdpi"]=96
    ["mipmap-xxhdpi"]=144
    ["mipmap-xxxhdpi"]=192
)

for dir in "${!ICON_SIZES[@]}"; do
    size="${ICON_SIZES[$dir]}"
    echo "  → $dir: ${size}x${size}"
    $CONVERT "$SOURCE_LOGO" -resize ${size}x${size} -extent ${size}x${size} -gravity center "$RES_DIR/$dir/ic_launcher.png"
    $CONVERT "$SOURCE_LOGO" -resize ${size}x${size} -extent ${size}x${size} -gravity center "$RES_DIR/$dir/ic_launcher_round.png"
done

# ============================================================================
# Generate adaptive icon foreground (108x108 for all densities)
# ============================================================================
echo ""
echo -e "${GREEN}🎨 Generating adaptive icon foreground...${NC}"

for dir in "${!ICON_SIZES[@]}"; do
    echo "  → $dir/ic_launcher_foreground.png: 108x108"
    $CONVERT "$SOURCE_LOGO" -resize 72x72 -extent 108x108 -gravity center "$RES_DIR/$dir/ic_launcher_foreground.png"
done

# Copy to drawable directory only (not drawable-v24 to avoid XML conflict)
$CONVERT "$SOURCE_LOGO" -resize 72x72 -extent 108x108 -gravity center "$RES_DIR/drawable/ic_launcher_foreground.png"

# ============================================================================
# Generate splash screen (white background, centered logo)
# ============================================================================
echo ""
echo -e "${GREEN}Generating splash screen...${NC}"
echo "  → drawable/splash.png: 2880x1776"

$CONVERT -size 2880x1776 xc:white \
    \( "$SOURCE_LOGO" -resize 600x600 \) -gravity center -composite \
    "$RES_DIR/drawable/splash.png"

# ============================================================================
# Summary
# ============================================================================
echo ""
echo -e "${GREEN}All mobile icons generated successfully!${NC}"
echo ""
echo "Generated files:"
echo "  • Launcher icons (mdpi, hdpi, xhdpi, xxhdpi, xxxhdpi)"
echo "  • Adaptive icon foreground (108x108)"
echo "  • Splash screen (2880x1776, white background)"
echo ""
echo -e "${YELLOW}Tip: Rebuild your app to see the changes:${NC}"
echo "  npx cap sync android"
echo "  # or"
echo "  make capacitor-sync"
