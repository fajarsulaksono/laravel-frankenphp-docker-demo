#!/bin/bash

# Exit on any error
set -e

echo "Starting Laravel FrankenPHP Docker application..."

# Navigate to app directory
cd /app

# Clear existing cache (optional but recommended for development)
if [ "$APP_ENV" = "local" ]; then
    echo "Clearing cache..."
    php artisan config:clear || true
    php artisan cache:clear || true
fi

# Generate APP_KEY if not exists
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Wait for database to be ready
echo "Waiting for database to be ready..."
max_attempts=30
attempt=0

while [ $attempt -lt $max_attempts ]; do
    if php artisan tinker --execute "exit;" 2>/dev/null; then
        echo "Database is ready!"
        break
    fi

    attempt=$((attempt + 1))
    echo "Waiting for database... (attempt $attempt/$max_attempts)"
    sleep 2
done

if [ $attempt -eq $max_attempts ]; then
    echo "Database connection failed after $max_attempts attempts"
    exit 1
fi

# Run migrations
if [ "$APP_ENV" != "production" ]; then
    echo "Running migrations..."
    php artisan migrate --force || true
fi

# Run database seeders (optional)
if [ "$APP_ENV" = "local" ] && [ "$DB_SEED" = "true" ]; then
    echo "Seeding database..."
    php artisan db:seed || true
fi

# Set proper permissions
echo "Setting permissions..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

echo "Laravel FrankenPHP is ready to serve!"

# Start PHP development server
exec php artisan serve --host=0.0.0.0 --port=80
