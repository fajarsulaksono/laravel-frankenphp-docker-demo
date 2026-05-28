# FrankenPHP Base Image
FROM dunglas/frankenphp:latest

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    git \
    zip \
    unzip \
    postgresql-client \
    mysql-client \
    && rm -rf /var/lib/apt/lists/*

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
    gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /app

# Set permissions
RUN chmod -R 755 /app/storage && \
    chmod -R 755 /app/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Expose port
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Run the application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
