# FrankenPHP Base Image (Alpine Linux for lightweight)
FROM dunglas/frankenphp:latest-alpine

# Set working directory
WORKDIR /app

# Install system dependencies (Alpine Linux)
RUN apk add --no-cache \
    curl \
    git \
    zip \
    unzip \
    postgresql-client \
    mysql-client

# Install PHP extensions
RUN install-php-extensions \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    bcmath \
    ctype \
    fileinfo \
    json \
    mbstring \
    openssl \
    pcntl \
    tokenizer \
    redis \
    gd \
    curl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /app

# Set permissions
RUN chmod -R 755 /app/storage && \
    chmod -R 755 /app/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Install OpenTelemetry PHP packages (dapat diinstall nanti via Composer untuk production)
# RUN composer require \
#     open-telemetry/api \
#     open-telemetry/sdk \
#     open-telemetry/exporter-jaeger \
#     open-telemetry/instrumentation-laravel

# Expose port
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Run the application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
