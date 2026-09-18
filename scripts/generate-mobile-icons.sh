#!/bin/bash

# ============================================================================
# generate-mobile-icons.sh
# 
# Generates Android (and iOS) app icons and splash screens from a brand logo.
# Part of the starter-kit: ensures every new project has proper branding
# without any leftover Capacitor default assets.
#
# Usage: ./scripts/generate-mobile-icons.sh [path-to-logo] [bg-color] [splash-bg-color]
# Defaults:
#   Logo: public/mobile-icons/logo.png (or public/logo.png, public/favicon.svg)
#   Icon BG: #ffffff (or CAPACITOR_ICON_BG_COLOR from .env)
#   Splash BG: #ffffff (or CAPACITOR_SPLASH_BG_COLOR from .env)
# ============================================================================

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Script directory and project root
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"

# Load .env if present
if [ -f "$PROJECT_ROOT/.env" ]; then
    ENV_ICON_BG=$(grep -E '^CAPACITOR_ICON_BG_COLOR=' "$PROJECT_ROOT/.env" | cut -d '=' -f2- | tr -d '"' | tr -d "'" || true)
    ENV_SPLASH_BG=$(grep -E '^CAPACITOR_SPLASH_BG_COLOR=' "$PROJECT_ROOT/.env" | cut -d '=' -f2- | tr -d '"' | tr -d "'" || true)
fi

# Detect source logo
SOURCE_LOGO="$1"
if [ -z "$SOURCE_LOGO" ]; then
    if [ -f "$PROJECT_ROOT/public/mobile-icons/logo.png" ]; then
        SOURCE_LOGO="$PROJECT_ROOT/public/mobile-icons/logo.png"
    elif [ -f "$PROJECT_ROOT/public/logo.png" ]; then
        SOURCE_LOGO="$PROJECT_ROOT/public/logo.png"
    elif [ -f "$PROJECT_ROOT/public/apple-touch-icon.png" ]; then
        SOURCE_LOGO="$PROJECT_ROOT/public/apple-touch-icon.png"
    elif [ -f "$PROJECT_ROOT/public/favicon.svg" ]; then
        SOURCE_LOGO="$PROJECT_ROOT/public/favicon.svg"
    fi
fi

if [ -z "$SOURCE_LOGO" ] || [ ! -f "$SOURCE_LOGO" ]; then
    echo -e "${RED}Error: Source logo not found.${NC}"
    echo "Please place your logo at public/mobile-icons/logo.png or provide path:"
    echo "  $0 [path-to-logo.png]"
    exit 1
fi

# Background colors
ICON_BG_COLOR="${2:-${ENV_ICON_BG:-#ffffff}}"
SPLASH_BG_COLOR="${3:-${ENV_SPLASH_BG:-#ffffff}}"

# Check ImageMagick
if command -v magick &> /dev/null; then
    CONVERT="magick"
elif command -v convert &> /dev/null; then
    CONVERT="convert"
else
    echo -e "${RED}Error: ImageMagick is not installed.${NC}"
    echo "Install it with:"
    echo "  Ubuntu/Debian: sudo apt-get install -y imagemagick"
    echo "  macOS: brew install imagemagick"
    exit 1
fi

echo -e "${BLUE}====================================================${NC}"
echo -e "${YELLOW}🎬 Mobile Assets Generator (Starter Kit)${NC}"
echo -e "${BLUE}====================================================${NC}"
echo -e "  • Logo:        $SOURCE_LOGO"
echo -e "  • Icon BG:     $ICON_BG_COLOR"
echo -e "  • Splash BG:   $SPLASH_BG_COLOR"
echo ""

# Handle SVG conversion if input is SVG
TEMP_PNG=""
if [[ "$SOURCE_LOGO" == *.svg ]]; then
    TEMP_PNG=$(mktemp --suffix=.png)
    echo -e "${YELLOW}Converting SVG logo to high-res PNG...${NC}"
    $CONVERT -background none -density 300 "$SOURCE_LOGO" -resize 1024x1024 "$TEMP_PNG"
    WORKING_LOGO="$TEMP_PNG"
else
    WORKING_LOGO="$SOURCE_LOGO"
fi

cleanup() {
    if [ -n "$TEMP_PNG" ] && [ -f "$TEMP_PNG" ]; then
        rm -f "$TEMP_PNG"
    fi
}
trap cleanup EXIT

# ============================================================================
# ANDROID ASSETS
# ============================================================================
ANDROID_RES="$PROJECT_ROOT/android/app/src/main/res"

if [ -d "$ANDROID_RES" ]; then
    echo -e "${GREEN}📱 Generating Android Assets...${NC}"

    # 1. Clean up default Capacitor vector drawables that override custom icons on API 24+
    echo "  → Removing default Capacitor vector drawables..."
    rm -f "$ANDROID_RES/drawable-v24/ic_launcher_foreground.xml"
    rm -f "$ANDROID_RES/drawable-v24/ic_launcher_foreground.png"
    rm -f "$ANDROID_RES/drawable/ic_launcher_foreground.png"
    rm -f "$ANDROID_RES/drawable/ic_launcher_background.xml"

    # 2. Configure adaptive icon background color in values/ic_launcher_background.xml
    mkdir -p "$ANDROID_RES/values"
    cat <<EOF > "$ANDROID_RES/values/ic_launcher_background.xml"
<?xml version="1.0" encoding="utf-8"?>
<resources>
    <color name="ic_launcher_background">${ICON_BG_COLOR}</color>
</resources>
EOF
    echo "  → Configured values/ic_launcher_background.xml ($ICON_BG_COLOR)"

    # 3. Configure adaptive icon definitions (API 26+)
    mkdir -p "$ANDROID_RES/mipmap-anydpi-v26"
    cat <<EOF > "$ANDROID_RES/mipmap-anydpi-v26/ic_launcher.xml"
<?xml version="1.0" encoding="utf-8"?>
<adaptive-icon xmlns:android="http://schemas.android.com/apk/res/android">
    <background android:drawable="@color/ic_launcher_background"/>
    <foreground android:drawable="@mipmap/ic_launcher_foreground"/>
</adaptive-icon>
EOF

    cat <<EOF > "$ANDROID_RES/mipmap-anydpi-v26/ic_launcher_round.xml"
<?xml version="1.0" encoding="utf-8"?>
<adaptive-icon xmlns:android="http://schemas.android.com/apk/res/android">
    <background android:drawable="@color/ic_launcher_background"/>
    <foreground android:drawable="@mipmap/ic_launcher_foreground"/>
</adaptive-icon>
EOF
    echo "  → Configured mipmap-anydpi-v26/ic_launcher.xml and ic_launcher_round.xml"

    # 4. Generate legacy launcher icons (API < 26)
    declare -A LEGACY_SIZES=(
        ["mipmap-mdpi"]=48
        ["mipmap-hdpi"]=72
        ["mipmap-xhdpi"]=96
        ["mipmap-xxhdpi"]=144
        ["mipmap-xxxhdpi"]=192
    )

    for dir in "${!LEGACY_SIZES[@]}"; do
        size="${LEGACY_SIZES[$dir]}"
        mkdir -p "$ANDROID_RES/$dir"
        echo "  → $dir legacy icons: ${size}x${size}"
        $CONVERT "$WORKING_LOGO" -resize ${size}x${size} -background none -gravity center -extent ${size}x${size} "$ANDROID_RES/$dir/ic_launcher.png"
        $CONVERT "$WORKING_LOGO" -resize ${size}x${size} -background none -gravity center -extent ${size}x${size} "$ANDROID_RES/$dir/ic_launcher_round.png"
    done

    # 5. Generate Adaptive Icon Foregrounds (API 26+)
    # Android spec: 108dp canvas with safe zone at inner 72dp (inner 66.7%)
    declare -A ADAPTIVE_DENSITIES=(
        ["mipmap-mdpi"]="108:72"
        ["mipmap-hdpi"]="162:108"
        ["mipmap-xhdpi"]="216:144"
        ["mipmap-xxhdpi"]="324:216"
        ["mipmap-xxxhdpi"]="432:288"
    )

    for dir in "${!ADAPTIVE_DENSITIES[@]}"; do
        sizes=(${ADAPTIVE_DENSITIES[$dir]//:/ })
        canvas="${sizes[0]}"
        logo="${sizes[1]}"
        echo "  → $dir adaptive foreground: ${canvas}x${canvas} (logo ${logo}x${logo})"
        $CONVERT "$WORKING_LOGO" -resize ${logo}x${logo} -background none -gravity center -extent ${canvas}x${canvas} "$ANDROID_RES/$dir/ic_launcher_foreground.png"
    done

    # 6. Generate Splash Screens (all densities & orientations)
    echo "  → Generating splash screens (BG: $SPLASH_BG_COLOR)..."

    # Generic fallback splash
    $CONVERT -size 2732x2732 xc:"$SPLASH_BG_COLOR" \
        \( "$WORKING_LOGO" -resize 800x800 \) -gravity center -composite \
        "$ANDROID_RES/drawable/splash.png"

    # Portrait splashes: "width:height:logo_size"
    declare -A PORTRAIT_SPLASH=(
        ["drawable-port-mdpi"]="320:480:160"
        ["drawable-port-hdpi"]="480:800:240"
        ["drawable-port-xhdpi"]="720:1280:360"
        ["drawable-port-xxhdpi"]="960:1600:480"
        ["drawable-port-xxxhdpi"]="1280:1920:640"
    )

    for dir in "${!PORTRAIT_SPLASH[@]}"; do
        params=(${PORTRAIT_SPLASH[$dir]//:/ })
        w="${params[0]}"
        h="${params[1]}"
        logo="${params[2]}"
        mkdir -p "$ANDROID_RES/$dir"
        $CONVERT -size ${w}x${h} xc:"$SPLASH_BG_COLOR" \
            \( "$WORKING_LOGO" -resize ${logo}x${logo} \) -gravity center -composite \
            "$ANDROID_RES/$dir/splash.png"
    done

    # Landscape splashes: "width:height:logo_size"
    declare -A LANDSCAPE_SPLASH=(
        ["drawable-land-mdpi"]="480:320:160"
        ["drawable-land-hdpi"]="800:480:240"
        ["drawable-land-xhdpi"]="1280:720:360"
        ["drawable-land-xxhdpi"]="1600:960:480"
        ["drawable-land-xxxhdpi"]="1920:1280:640"
    )

    for dir in "${!LANDSCAPE_SPLASH[@]}"; do
        params=(${LANDSCAPE_SPLASH[$dir]//:/ })
        w="${params[0]}"
        h="${params[1]}"
        logo="${params[2]}"
        mkdir -p "$ANDROID_RES/$dir"
        $CONVERT -size ${w}x${h} xc:"$SPLASH_BG_COLOR" \
            \( "$WORKING_LOGO" -resize ${logo}x${logo} \) -gravity center -composite \
            "$ANDROID_RES/$dir/splash.png"
    done

    echo -e "${GREEN}✓ Android assets generated successfully!${NC}"
else
    echo -e "${YELLOW}Android directory not found ($ANDROID_RES). Run 'npm run cap:add:android' to initialize.${NC}"
fi

# ============================================================================
# IOS ASSETS (if ios project exists)
# ============================================================================
IOS_APPICON_DIR="$PROJECT_ROOT/ios/App/App/Assets.xcassets/AppIcon.appiconset"
IOS_SPLASH_DIR="$PROJECT_ROOT/ios/App/App/Assets.xcassets/Splash.imageset"

if [ -d "$IOS_APPICON_DIR" ]; then
    echo -e "${GREEN}🍏 Generating iOS Assets...${NC}"
    $CONVERT "$WORKING_LOGO" -resize 1024x1024 -background "$ICON_BG_COLOR" -gravity center -extent 1024x1024 "$IOS_APPICON_DIR/AppIcon-512@2x.png"
    echo "  → AppIcon-512@2x.png: 1024x1024"
    if [ -d "$IOS_SPLASH_DIR" ]; then
        $CONVERT -size 2732x2732 xc:"$SPLASH_BG_COLOR" \
            \( "$WORKING_LOGO" -resize 800x800 \) -gravity center -composite \
            "$IOS_SPLASH_DIR/splash.png"
        echo "  → Splash.imageset/splash.png: 2732x2732"
    fi
    echo -e "${GREEN}✓ iOS assets generated successfully!${NC}"
fi

echo ""
echo -e "${GREEN}✨ All branding assets generated successfully!${NC}"
echo -e "${YELLOW}To sync with Capacitor, run:${NC}"
echo "  npx cap sync android"
echo "  # or"
echo "  npm run cap:sync"
