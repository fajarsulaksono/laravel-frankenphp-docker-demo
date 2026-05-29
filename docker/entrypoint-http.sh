#!/bin/sh
set -e

echo "Initializing Laravel for FrankenPHP..."
cd /app

# Create necessary directories
mkdir -p bootstrap/cache storage/logs storage/framework/cache/data storage/framework/sessions storage/framework/views
chmod -R 755 bootstrap/cache storage

# Clear old cache
php artisan cache:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Cache configuration for faster boot
php artisan config:cache 2>/dev/null || true

echo "Starting FrankenPHP on 0.0.0.0:80..."
echo "Listening on http://0.0.0.0:80 (mapped to port 9000 on host)"

# Start FrankenPHP without custom Caddyfile (uses defaults for HTTP-only)
exec frankenphp run --config /app/docker/Caddyfile
