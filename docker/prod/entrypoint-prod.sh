#!/bin/sh
set -e

echo "🚀 Starting Laravel production..."

# Run Migrates
php artisan migrate --force

# Cache Laravel optimizations
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction
php artisan event:cache --no-interaction

# Ensure storage link exists
php artisan storage:link --force 2>/dev/null || true

# Set proper permissions (in case volumes changed ownership)
chown -R app:app /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

echo "✅ Laravel ready. Starting unitd..."

exec "$@"
