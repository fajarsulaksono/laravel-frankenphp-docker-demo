# Laravel FrankenPHP Docker Demo

A complete demonstration of a **Laravel 13** application running in **Docker** with **FrankenPHP** (powered by Caddy web server), showcasing real-time service health monitoring, distributed tracing with Jaeger, and a modern frontend.

**Stack:** FrankenPHP · Laravel 13 · Tailwind CSS · Google Font Outfit · MariaDB · Redis · Jaeger (OTel)

## 📋 Table of Contents

- [About This Project](#about-this-project)
- [Requirements](#requirements)
- [Installation & Setup](#installation--setup)
- [Running the Application](#running-the-application)
- [Folder Structure](#folder-structure)
- [Useful Commands](#useful-commands)
- [Troubleshooting](#troubleshooting)

## �️ Tech Stack

### Backend
- **Laravel 13** - Latest PHP framework
- **FrankenPHP** - Modern PHP runtime with Fiber support
- **Alpine Linux** - Lightweight base images

### Frontend
- **Tailwind CSS** - Utility-first CSS framework
- **Google Font Outfit** - Professional typography
- **Responsive Design** - Mobile-friendly layouts

### Infrastructure
- **Caddy** - HTTP server with automatic configuration
- **MariaDB 11.4** - Alpine-based database
- **Redis 7** - Alpine-based cache and queue
- **Jaeger** - Distributed tracing and OpenTelemetry
- **Docker Compose** - Multi-container orchestration

## ℹ️ About FrankenPHP

**FrankenPHP** is a modern PHP runtime built on top of the [Caddy](https://caddyserver.com) web server:
- ✅ Native PHP runtime — no PHP-FPM needed
- ✅ Powered by Caddy with HTTP/2, HTTP/3 support
- ✅ Supports Fiber for async/concurrent PHP tasks
- ✅ `php_server` directive for zero-config PHP serving
- ✅ Better performance compared to traditional Nginx + PHP-FPM
- ✅ HTTP-only mode for local development (no HTTPS complexity)

This project uses FrankenPHP's `dunglas/frankenphp:latest-alpine` image with a custom `Caddyfile` configured for HTTP-only local development.

## 📦 Requirements

- Docker (version 20.10+)
- Docker Compose (version 1.29+)
- Git

## 🔧 Installation & Setup

### Quick Start (First Time Setup)

If this is your first time setting up this project, follow the steps below:

#### **Step 1: Clone Repository**
```bash
git clone https://github.com/YOUR_USERNAME/laravel-frankenphp-docker-demo.git
cd laravel-frankenphp-docker-demo
```

#### **Step 2: Setup Environment File**
```bash
cp .env.example .env
```

Edit the `.env` file if you need specific settings (port, password, etc.):
```bash
nano .env    # or use your favorite editor
```

#### **Step 3: Build Docker Images** ⏱️ *This may take 5-15 minutes*
```bash
docker compose build
```

**Expected output:**
```
...
[+] Building 45.3s (14/14) FINISHED
 => => naming to ghcr.io/dunglas/frankenphp:latest
[+] Building 22.4s (4/4) FINISHED
 => => naming to laravel-frankenphp-docker-demo-app:latest
```

#### **Step 4: Start All Containers**
```bash
docker compose up -d
```

**Check container status:**
```bash
docker compose ps
```

**Expected output:**
```
NAME                            SERVICE      STATUS
laravel-frankenphp-app          app          Up (healthy)
laravel-frankenphp-db           db           Up (healthy)
laravel-frankenphp-redis        redis        Up (healthy)
laravel-frankenphp-phpmyadmin   phpmyadmin   Up
laravel-frankenphp-jaeger       jaeger       Up (healthy)
```

#### **Step 5: Verify Installation**
Wait a few moments for all services to fully initialize, then verify:

```bash
# Check app logs
docker compose logs app

# Verify app is running
curl http://localhost

# Verify database connection
docker compose exec app php artisan tinker
> DB::connection()->getPdo()  # Press Enter, if successful will return PDO object
```

#### **Step 6: Access The Application**
Once all services are running, access the application in your browser:

- 🌐 **Laravel App**: http://localhost:9000 (FrankenPHP + Caddy)
- 🗄️ **phpMyAdmin**: http://localhost:9001 (username: `laravel`, password: `password`)
- 📊 **Jaeger UI**: http://localhost:9016
- 💾 **Database**: localhost:3307 (host: `db`, internal port: `3306`, user: `laravel`, password: `password`)
- 🔴 **Redis**: localhost:6379

---

### Stop and Remove Containers

If you want to stop development:

```bash
# Stop all containers (data is preserved)
docker compose stop

# Start again
docker compose start

# Stop and remove containers (but data remains in volumes)
docker compose down

# Stop and remove everything including volumes (WARNING: DATA WILL BE LOST!)
docker compose down -v
```

---

### Automated Setup (What happens automatically)

When containers start, the `docker/entrypoint-http.sh` script automatically:
1. ✅ Creates required directories (`bootstrap/cache`, `storage/*`)
2. ✅ Sets folder permissions (`chmod 777`)
3. ✅ Clears old config/view/cache
4. ✅ Warms up config cache (`php artisan config:cache`)
5. ✅ Starts FrankenPHP with the custom `Caddyfile`

**You don't need to run these commands manually, everything is automated!**

## ▶️ Running the Application

### Build and Start Containers
```bash
docker compose up -d
```

The application will be available at: `http://localhost`

### Access Services
- **Laravel Application**: http://localhost:9000 (FrankenPHP + Caddy, configurable via `APP_PORT`)
- **phpMyAdmin**: http://localhost:9001 (configurable via `PMA_PORT`)
- **Redis**: localhost:6379
- **MariaDB**: localhost:3307 (external), `db:3306` (internal Docker network)
- **Jaeger UI**: http://localhost:9016 (configurable via `JAEGER_QUERY_PORT`)
- **Jaeger OTLP gRPC**: localhost:4317 (internal)
- **Jaeger Collector HTTP**: localhost:14268

### Stop Containers
```bash
docker compose down
```

### View Application Logs
```bash
docker compose logs -f app
```

## 📁 Folder Structure

```
laravel-frankenphp-docker-demo/
├── app/                      # Laravel application code
├── bootstrap/                # Bootstrap files
├── config/                   # Application configuration
├── database/                 # Migrations & seeders
├── public/                   # Public assets
├── resources/                # Views, CSS, JS
├── routes/                   # Route definitions
├── storage/                  # Logs and cache
├── tests/                    # Test files
├── docker/                   # Docker configurations
│   ├── Dockerfile            # FrankenPHP setup
│   ├── Caddyfile             # Caddy HTTP configuration
│   └── entrypoint-http.sh    # Container startup script
├── docker-compose.yml        # Docker services orchestration
├── .env.example              # Example environment file
└── README.md                 # This file
```

## 🐳 Docker Services

### Architecture Overview
```
Client Request (HTTP) → Port 9000
         ↓
   Caddy Web Server
         ↓
   FrankenPHP (Laravel App)
         ↓
   Internal Network (laravel)
         ↓
   ┌──────────┬────────────┬──────────────┬──────────────┐
   ↓          ↓            ↓              ↓              ↓
MariaDB    Redis        Jaeger      phpMyAdmin      Network
(Database) (Cache)    (Tracing)   (Database GUI)    (Bridge)
```

### 1. **FrankenPHP (app)** - Application Server
- FrankenPHP with Caddy web server
- Laravel 13.12.0 PHP framework
- HTTP-only configuration for local development
- Port: 80 (internal) → 9000 (host/external)
- Automatically initializes cache and configurations
- Entry point: `/app/public/index.php`
- Supports Fiber for async/concurrent tasks

### 2. **MariaDB Database (db)**
- Version: MariaDB 11.4 Alpine (lightweight, drop-in replacement for MySQL)
- Default database: `laravel`
- Port: 3306 (internal) → 3307 (host)
- Health check: ✅ Included
- User: `laravel` / Password: `password`

### 3. **Redis Cache (redis)**
- Used for caching and queue
- Version: Redis 7 Alpine (lightweight)
- Port: 6379
- Health check: ✅ Included

### 4. **phpMyAdmin (phpmyadmin)** (Optional)
- GUI for managing MariaDB database
- Port: 9001 (access via http://localhost:9001)
- Login: `laravel` / `password`

### 5. **Jaeger (jaeger)**
- Distributed tracing and monitoring for OpenTelemetry
- Query UI: http://localhost:9016
- Collector Port: 14268 (HTTP)
- Agent Port: 6831 (UDP)

## 🎯 Useful Commands

### Artisan Commands
```bash
# Run migrations
docker compose exec app php artisan migrate

# Create model with migration
docker compose exec app php artisan make:model Post -m

# Create controller
docker compose exec app php artisan make:controller PostController

# Run seeding
docker compose exec app php artisan db:seed

# Access Laravel Tinker
docker compose exec app php artisan tinker
```

### Container Management
```bash
# View real-time logs
docker compose logs -f app

# View logs from specific service
docker compose logs -f db    # MariaDB logs
docker compose logs -f redis # Redis logs

# Enter container shell
docker compose exec app bash

# Rebuild images (if Dockerfile is updated)
docker compose build --no-cache

# Check status of all services
docker compose ps

# Restart specific service
docker compose restart app
```

### Database Management
```bash
# Access MariaDB CLI
docker compose exec db mariadb -u laravel -p -D laravel

# Reset database
docker compose exec app php artisan migrate:refresh

# Fresh migration with seeding
docker compose exec app php artisan migrate:fresh --seed
```

## 🔐 Environment Variables

Edit the `.env` file to configure settings:

```bash
# Application
APP_NAME="Laravel FrankenPHP"         # Application name
APP_ENV=local                          # Environment (local, staging, production)
APP_DEBUG=true                         # Debug mode
APP_URL=http://localhost:9000          # Application URL (include port)
APP_PORT=9000                          # Application port (use custom port to avoid conflicts)

# Database
DB_CONNECTION=mysql                    # Driver (mysql, pgsql)
DB_HOST=db                             # Database host
DB_PORT=3306                           # Database port
DB_DATABASE=laravel                    # Database name
DB_USERNAME=laravel                    # Database user
DB_PASSWORD=password                   # Database password
DB_ROOT_PASSWORD=root                  # Database root password

# Cache & Queue
CACHE_DRIVER=redis                     # Cache driver (redis, file)
QUEUE_CONNECTION=redis                 # Queue driver

# Redis
REDIS_HOST=redis                       # Redis host
REDIS_PORT=6379                        # Redis port

# phpMyAdmin
PMA_PORT=9001                          # phpMyAdmin port (use custom port to avoid conflicts)

# Jaeger
JAEGER_QUERY_PORT=9016                 # Jaeger UI port (use custom port to avoid conflicts)
```

## 📊 OpenTelemetry & Jaeger Distributed Tracing

### Setup OpenTelemetry in Laravel

To enable OpenTelemetry and send traces to Jaeger, install the packages:

```bash
docker compose exec app composer require \
    open-telemetry/api \
    open-telemetry/sdk \
    open-telemetry/exporter-jaeger \
    open-telemetry/instrumentation-laravel
```

### Configuration in `.env`:

```bash
OTEL_ENABLED=true                           # Enable OpenTelemetry
OTEL_SERVICE_NAME=laravel-frankenphp        # Service name for tracing
OTEL_EXPORTER_OTLP_ENDPOINT=http://jaeger:4317  # Jaeger collector endpoint
JAEGER_ENABLED=true                         # Enable Jaeger exporter
```

### Access Jaeger UI

Open in browser: **http://localhost:9016**

Here you can:
- ✅ View distributed traces from Laravel application
- ✅ Monitor performance of every request
- ✅ Identify bottlenecks & slow queries
- ✅ Trace database queries, Redis calls, HTTP requests

## 🐛 Troubleshooting

### Issues During First Time Setup

#### **Container not starting (exits immediately)**
```bash
# Check logs for error details
docker compose logs app

# Solution: Rebuild with fresh start
docker compose down -v  # Remove containers and volumes
docker compose build --no-cache
docker compose up -d
```

#### **Port already in use**
If any default port is already used by another service, you can easily change it in `.env` file:

```bash
# Change Nginx/Application port
APP_PORT=9000           # Default is 9000, change to 8000, 3000, etc.

# Change phpMyAdmin port
PMA_PORT=9001           # Default is 9001, change as needed

# Change Jaeger UI port
JAEGER_QUERY_PORT=9016  # Default is 9016, change as needed
```

Then restart: `docker compose down && docker compose up -d`

> **Tip:** The application is served by FrankenPHP (Caddy) on internal port 80, mapped to `APP_PORT` (default 9000) on the host. No Nginx or reverse proxy needed — Caddy handles everything.

#### **Database connection error**
```bash
# Wait for database to fully initialize
docker compose logs db | tail -20

# Manual check connection
docker compose exec app php artisan tinker
> DB::connection()->getPdo()

# If still error, try refresh:
docker compose exec app php artisan migrate:refresh
```

#### **Permission denied in storage folder**
```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

#### **Redis connection failed**
```bash
# Check Redis is running
docker compose ps redis

# Verify connection
docker compose exec app redis-cli -h redis ping
# Output should be: PONG
```

#### **APP_KEY not generated**
If you see error `No application encryption key has been specified.`:
```bash
docker compose exec app php artisan key:generate
docker compose restart app
```

### General Troubleshooting

#### **Clear cache and config**
```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan view:clear
```

#### **Jaeger not displaying traces**
```bash
# Check Jaeger is running
docker compose ps jaeger

# Check collector endpoint
docker compose logs jaeger | grep "listening on"

# Verify connection from app
docker compose exec app curl -v http://jaeger:14268/api/traces
```

#### **Containers stuck or not responding**
```bash
# Force stop all
docker compose down

# Remove all containers and volumes
docker compose down -v

# Start fresh
docker compose up -d
```

---

## 💡 Useful Commands Reference

### Accessing Services
```bash
# Enter application shell
docker compose exec app bash

# Database shell (MariaDB)
docker compose exec db mariadb -u laravel -p -D laravel

# Redis CLI
docker compose exec redis redis-cli

# Laravel Tinker REPL
docker compose exec app php artisan tinker
```

### Common Laravel Commands
```bash
# Create new model with migration
docker compose exec app php artisan make:model Post -m

# Create controller
docker compose exec app php artisan make:controller PostController

# Run specific migration
docker compose exec app php artisan migrate --path=database/migrations/2024_01_01_create_posts_table.php

# Rollback migrations
docker compose exec app php artisan migrate:rollback

# Fresh migration with seeding
docker compose exec app php artisan migrate:fresh --seed
```

### Debugging
```bash
# Real-time logs from app
docker compose logs -f app --tail=50

# Logs from specific service
docker compose logs -f db    # MariaDB
docker compose logs -f redis # Redis
docker compose logs -f jaeger # Jaeger

# Check container stats
docker compose stats

# Inspect container
docker compose exec app php -v
docker compose exec db mariadb --version
```

### Cleanup & Maintenance
```bash
# Remove unused images
docker image prune

# Remove unused volumes
docker volume prune

# Rebuild specific service
docker compose build --no-cache app

# Restart specific service
docker compose restart app

# Pull latest images
docker compose pull
docker compose up -d
```

## � Development Workflow

### Local Development Setup

All your application files are already mounted to the container, so local changes are automatically reflected:

```bash
# Edit files from host machine
nano app/Http/Controllers/HomeController.php

# Changes are automatically available in container
docker compose exec app php artisan tinker
> include 'app/Http/Controllers/HomeController.php'
```

### Develop with Hot Reload

For development with file watcher (auto-reload), use:

```bash
# This project uses Tailwind CSS via CDN — no build step needed for local dev
# If you add Vite/npm assets in the future:
docker compose exec app npm run dev -- --host 0.0.0.0
```

### Database Development

```bash
# Create new migration
docker compose exec app php artisan make:migration create_products_table

# Edit migration file (will auto-mount to container)
nano database/migrations/2024_*.php

# Run migration
docker compose exec app php artisan migrate

# Access database via phpMyAdmin
# http://localhost:9001
```

### Testing

```bash
# Run PHPUnit tests
docker compose exec app php artisan test

# Run specific test
docker compose exec app php artisan test tests/Feature/UserTest.php

# Run with coverage
docker compose exec app php artisan test --coverage
```

### Git Workflow in Docker

```bash
# If you want to use git from within container
docker compose exec app git status
docker compose exec app git add .
docker compose exec app git commit -m "feat: add new feature"

# Or use git from host (more recommended)
git status
git add .
git commit -m "feat: add new feature"
```

## �📚 Resources

- [Laravel Documentation](https://laravel.com/docs)
- [FrankenPHP Documentation](https://frankenphp.dev/)
- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [MariaDB Documentation](https://mariadb.com/docs/)
- [Redis Documentation](https://redis.io/documentation)
- [OpenTelemetry PHP](https://opentelemetry.io/docs/instrumentation/php/)
- [Jaeger Documentation](https://www.jaegertracing.io/docs/)

## 📝 License

MIT License - feel free to use for personal or commercial projects

## 👤 Author

Created as a demo to learn the integration of Laravel with FrankenPHP and Docker.

---

**Happy learning! 🎉**
