# Laravel FrankenPHP Docker Demo

A complete demonstration of a Laravel application running with **FrankenPHP** (modern PHP runtime) using **Docker** and **Docker Compose**.

## 📋 Table of Contents

- [About FrankenPHP](#about-frankenphp)
- [Requirements](#requirements)
- [Installation & Setup](#installation--setup)
- [Running the Application](#running-the-application)
- [Folder Structure](#folder-structure)
- [Useful Commands](#useful-commands)
- [Troubleshooting](#troubleshooting)

## 🚀 About FrankenPHP

**FrankenPHP** is a modern PHP runtime that:
- ✅ Built on top of Caddy web server
- ✅ Supports async/await with Fiber
- ✅ Zero-downtime deployment
- ✅ Automatic HTTPS with Let's Encrypt
- ✅ Better performance compared to traditional PHP-FPM

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
NAME                          COMMAND                  SERVICE      STATUS
laravel-frankenphp-app        /bin/bash -c "chmod +x…   app          Up 2 seconds (healthy)
laravel-frankenphp-db         docker-entrypoint.sh…    db           Up 3 seconds (healthy)
laravel-frankenphp-redis      redis-server             redis        Up 2 seconds (healthy)
laravel-frankenphp-phpmyadmin…apache2-foreground       phpmyadmin   Up 2 seconds
laravel-frankenphp-jaeger     /go/bin/all-in-one-da…   jaeger       Up 1 second (healthy)
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

- 🌐 **Laravel App**: http://localhost
- 🗄️ **phpMyAdmin**: http://localhost:8080 (username: `laravel`, password: `password`)
- 📊 **Jaeger UI**: http://localhost:16686
- 💾 **Database**: localhost:3306 (host: `db`, user: `laravel`, password: `password`)
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

When containers start for the first time, the `docker/entrypoint.sh` script automatically:
1. ✅ Generates `APP_KEY` if not already present
2. ✅ Runs database migrations
3. ✅ Sets proper permissions for folders
4. ✅ Starts Laravel development server

**You don't need to run these commands manually, everything is automated!**

## ▶️ Running the Application

### Build and Start Containers
```bash
docker compose up -d
```

The application will be available at: `http://localhost`

### Access Services
- **Laravel Application**: http://localhost
- **phpMyAdmin**: http://localhost:8080
- **Redis**: localhost:6379
- **MariaDB**: localhost:3306
- **Jaeger UI**: http://localhost:16686

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
│   └── entrypoint.sh         # Container startup script
├── docker-compose.yml        # Docker services orchestration
├── .env.example              # Example environment file
└── README.md                 # This file
```

## 🐳 Docker Services

### 1. **FrankenPHP (app)**
- Modern PHP runtime with Fiber support
- Automatic HTTPS with Caddy
- Zero-downtime deployment
- Port: 80

### 2. **MariaDB Database (db)**
- Version: MariaDB Latest (drop-in replacement for MySQL)
- Default database: `laravel`
- Port: 3306
- Health check: ✅ Included

### 3. **Redis Cache (redis)**
- Used for caching and queue
- Version: Redis 7 (Alpine)
- Port: 6379
- Health check: ✅ Included

### 4. **phpMyAdmin (phpmyadmin)** (Optional)
- GUI for managing MariaDB database
- Port: 8080
- Username: `laravel` / Password: `password`

### 5. **Jaeger (jaeger)**
- Distributed tracing and monitoring for OpenTelemetry
- Query UI: http://localhost:16686
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
APP_NAME=Laravel                    # Application name
APP_ENV=local                       # Environment (local, staging, production)
APP_DEBUG=true                      # Debug mode
APP_URL=http://localhost            # Application URL
APP_PORT=80                         # Application port

# Database
DB_CONNECTION=mysql                 # Driver (mysql, pgsql)
DB_HOST=db                          # Database host
DB_PORT=3306                        # Database port
DB_DATABASE=laravel                 # Database name
DB_USERNAME=laravel                 # Database user
DB_PASSWORD=password                # Database password

# Cache & Queue
CACHE_DRIVER=redis                  # Cache driver (redis, file)
QUEUE_CONNECTION=redis              # Queue driver

# Redis
REDIS_HOST=redis                    # Redis host
REDIS_PORT=6379                     # Redis port
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

Open in browser: **http://localhost:16686**

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
If port 80 is already used by another service, edit `docker-compose.yml`:
```yaml
ports:
  - "8000:80"  # Change from 80 to 8000
```
Then restart: `docker compose up -d`

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
# Watch mode for CSS/JS
docker compose exec app npm run dev

# Or if using Vite
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
# http://localhost:8080
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
