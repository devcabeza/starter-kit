#!/bin/bash
set -e

echo "🚀 Starting Laravel production..."

# Run Migrations
php artisan migrate --force

# Cache Laravel optimizations
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction
php artisan event:cache --no-interaction

# Ensure storage link exists
php artisan storage:link --force 2>/dev/null || true

# Set proper permissions
chown -R app:app /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

echo "✅ Laravel ready. Starting nginx + php-fpm..."

# Start php-fpm in background
php-fpm -D

# Start nginx in foreground (this keeps the container running)
exec nginx -g "daemon off;"
