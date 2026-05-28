#!/bin/bash

# Exit on any error
set -e

echo "=========================================="
echo "Starting Laravel FrankenPHP Application"
echo "=========================================="

# Navigate to app directory
cd /app

# Check if Laravel is already installed
if [ ! -f "artisan" ]; then
    echo "[SETUP] Laravel installation not found. Installing..."

    # Reinstall composer dependencies if composer.json exists
    if [ -f "composer.json" ]; then
        echo "[SETUP] Installing PHP dependencies with Composer..."
        composer install --no-interaction --optimize-autoloader
    fi

    # Reinstall npm dependencies if package.json exists
    if [ -f "package.json" ]; then
        echo "[SETUP] Installing Node dependencies..."
        npm ci
        echo "[SETUP] Building frontend assets..."
        npm run build
    fi
fi

# Clear cache in local environment
if [ "$APP_ENV" = "local" ]; then
    echo "[CONFIG] Clearing cache..."
    php artisan config:clear || true
    php artisan cache:clear || true
    php artisan view:clear || true
fi

# Generate APP_KEY if not exists
if [ -z "$APP_KEY" ]; then
    echo "[CONFIG] Generating application key..."
    php artisan key:generate --force
fi

# Wait for database to be ready
echo "[DB] Waiting for database to be ready..."
max_attempts=30
attempt=0

while [ $attempt -lt $max_attempts ]; do
    if php artisan migrate:status > /dev/null 2>&1; then
        echo "[DB] Database connection successful!"
        break
    fi

    attempt=$((attempt + 1))
    if [ $((attempt % 5)) -eq 0 ]; then
        echo "[DB] Waiting... (attempt $attempt/$max_attempts)"
    fi
    sleep 2
done

if [ $attempt -eq $max_attempts ]; then
    echo "[ERROR] Database connection failed after $max_attempts attempts"
fi

# Run migrations
if [ -f "database/migrations" ] || [ "$(ls -A database/migrations 2>/dev/null)" ]; then
    echo "[MIGRATION] Running database migrations..."
    php artisan migrate --force || true
fi

# Run database seeders (optional)
if [ "$APP_ENV" = "local" ] && [ "$DB_SEED" = "true" ]; then
    echo "[SEED] Seeding database..."
    php artisan db:seed || true
fi

# Set proper permissions
echo "[PERMISSIONS] Setting folder permissions..."
chmod -R 755 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

echo "=========================================="
echo "✅ Laravel FrankenPHP Ready!"
echo "=========================================="
echo "Application URL: ${APP_URL:-http://localhost:9000}"
echo "Environment: ${APP_ENV:-local}"
echo "=========================================="

echo "Laravel FrankenPHP is ready to serve!"

# Start PHP development server
exec php artisan serve --host=0.0.0.0 --port=80
