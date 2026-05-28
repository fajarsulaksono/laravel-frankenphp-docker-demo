# FrankenPHP Base Image (Alpine Linux for lightweight)
FROM dunglas/frankenphp:latest-alpine

# Set working directory
WORKDIR /app

# Install system dependencies (Alpine Linux) + Node.js for npm/yarn
RUN apk add --no-cache \
    curl \
    git \
    zip \
    unzip \
    postgresql-client \
    mysql-client \
    nodejs \
    npm

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

# Copy composer files first for better caching
COPY composer.json composer.lock* /app/

# Install PHP dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader || true

# Copy package files
COPY package.json package-lock.json* /app/

# Install Node dependencies
RUN npm ci --omit=dev || true

# Copy the rest of application files
COPY . /app

# Build frontend assets
RUN npm run build || true

# Set permissions
RUN chmod -R 755 /app/storage && \
    chmod -R 755 /app/bootstrap/cache

# Expose port
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
