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
COPY vite.config.js tailwind.config.js postcss.config.js /app/

# Install Node dependencies
RUN npm ci --omit=dev || true

# Copy the rest of application files (including resources/ for frontend)
COPY . /app

# Make entrypoint executable
RUN chmod +x /app/docker/entrypoint.sh

# Create Laravel directories if they don't exist
RUN mkdir -p /app/bootstrap/cache /app/storage/logs /app/storage/app /app/storage/framework

# Build frontend assets (after resources/ is copied)
RUN npm run build 2>/dev/null || true

# Set permissions
RUN chmod -R 755 /app/storage && \
    chmod -R 755 /app/bootstrap/cache

# Expose port
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1
