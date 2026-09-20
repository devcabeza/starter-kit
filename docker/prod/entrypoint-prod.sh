#!/bin/bash
set -e

echo "🚀 Starting Laravel production..."

# Ensure cache directories exist (safety net for .dockerignore exclusions)
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p bootstrap/cache
chown -R app:app storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Ensure php-fpm run directory has correct ownership (safety net)
mkdir -p /var/run/php
chown app:app /var/run/php

# Fix APP_URL scheme: force https when behind reverse proxy
# Coolify auto-generates APP_URL with http:// but the actual traffic is https://
if [ -n "$APP_URL" ]; then
    if [[ "$APP_URL" == http://* ]]; then
        export APP_URL="${APP_URL/http:\/\//https://}"
        echo "⚠️  APP_URL was http://, corrected to: $APP_URL"
    fi
fi

# Check if container role is worker/horizon via environment variable (Coolify Dockerfile support)
if [ "$CONTAINER_ROLE" = "worker" ] || [ "$CONTAINER_ROLE" = "horizon" ]; then
    echo "⚡ Worker role detected via CONTAINER_ROLE: $CONTAINER_ROLE"
    echo "🚀 Starting Horizon as PID 1..."
    exec php artisan horizon
fi

if [ -n "$CUSTOM_COMMAND" ]; then
    echo "⚡ Custom command detected via CUSTOM_COMMAND: $CUSTOM_COMMAND"
    echo "🚀 Starting process as PID 1..."
    exec $CUSTOM_COMMAND
fi

# Check if a custom command was passed (e.g. php artisan horizon)
if [ $# -gt 0 ] && [ "$1" != "nginx" ]; then
    echo "⚡ Custom command detected: $@"
    echo "🚀 Starting process as PID 1..."
    exec "$@"
fi

# Run Migrations (only web app handles migrations)
php artisan migrate --force

# Cache Laravel optimizations
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction
php artisan event:cache --no-interaction

# Ensure storage link exists
php artisan storage:link --force 2>/dev/null || true

# Ensure Livewire static assets exist
php artisan livewire:publish --assets --no-interaction 2>/dev/null || true

# Set proper permissions
chown -R app:app /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

echo "✅ Laravel ready. Starting nginx + php-fpm..."

# Start php-fpm in background
php-fpm -D

# Start nginx in foreground (this keeps the container running)
exec nginx -g "daemon off;"

