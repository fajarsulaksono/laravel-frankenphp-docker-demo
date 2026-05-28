# Laravel FrankenPHP Docker Demo — Wiki

Dokumentasi teknis lengkap untuk project ini. Lihat [README.md](README.md) untuk panduan quickstart.

---

## 📚 Table of Contents

- [Architecture Deep Dive](#architecture-deep-dive)
- [FrankenPHP & Caddyfile](#frankenphp--caddyfile)
- [Docker Services](#docker-services)
- [Entrypoint Script](#entrypoint-script)
- [Environment Variables Reference](#environment-variables-reference)
- [Service Health Monitoring](#service-health-monitoring)
- [OpenTelemetry & Jaeger Tracing](#opentelemetry--jaeger-tracing)
- [Frontend Stack](#frontend-stack)
- [Port Reference](#port-reference)
- [Common Workflows](#common-workflows)

---

## Architecture Deep Dive

### Request Flow

```
Browser / HTTP Client
        │
        │ HTTP (no HTTPS, no redirect)
        ▼
┌───────────────────────┐
│  Docker Host Port 9000 │
└───────────┬───────────┘
            │ maps to container port 80
            ▼
┌───────────────────────────────────────┐
│  FrankenPHP Container (app)            │
│                                       │
│  ┌─────────────────────────────────┐  │
│  │  Caddy (HTTP server)            │  │
│  │  - Listens on :80               │  │
│  │  - Serves static files directly │  │
│  │  - Hands PHP requests to        │  │
│  │    FrankenPHP runtime           │  │
│  └────────────┬────────────────────┘  │
│               │                       │
│  ┌────────────▼────────────────────┐  │
│  │  FrankenPHP (PHP 8.3.7 runtime) │  │
│  │  - Executes /app/public/index.php│ │
│  │  - Laravel bootstrap            │  │
│  │  - Fiber / async support        │  │
│  └─────────────────────────────────┘  │
└───────────────┬───────────────────────┘
                │ Internal Docker network: laravel
                ▼
   ┌────────────────────────────────────────────┐
   │              laravel (bridge network)       │
   └──┬──────────┬──────────┬──────────┬────────┘
      ▼          ▼          ▼          ▼
  ┌───────┐ ┌───────┐ ┌──────────┐ ┌────────┐
  │MariaDB│ │ Redis │ │  Jaeger  │ │phpMyAd.│
  │ :3306 │ │ :6379 │ │ :16686   │ │  :80   │
  └───────┘ └───────┘ │ :4317    │ └────────┘
                      │ :14268   │
                      └──────────┘
```

### Why FrankenPHP instead of Nginx + PHP-FPM?

| Aspect | Nginx + PHP-FPM | FrankenPHP |
|---|---|---|
| Process model | Two separate processes | Single unified process |
| Config files | nginx.conf + php-fpm.conf | Single Caddyfile |
| HTTP/2 & HTTP/3 | Manual config required | Built-in via Caddy |
| PHP Fibers (async) | Not supported | Native support |
| Container overhead | 2 containers (or 1 multi-process) | 1 container |
| Complexity | High | Low |

---

## FrankenPHP & Caddyfile

### docker/Caddyfile

```caddy
{
    frankenphp
    order php_server before file_server
}

http://localhost {
    root * /app/public
    encode zstd br gzip
    php_server
}
```

**Explanation:**

| Directive | Purpose |
|---|---|
| `frankenphp` | Enable FrankenPHP runtime in global block |
| `order php_server before file_server` | Ensure PHP files are processed before static serving |
| `http://localhost` | Bind to HTTP only — prevents Caddy's automatic HTTPS |
| `root * /app/public` | Set Laravel's public directory as web root |
| `encode zstd br gzip` | Enable response compression |
| `php_server` | FrankenPHP directive: serves all `.php` files, handles `try_files` for Laravel routing automatically |

> **Note:** `php_server` is a FrankenPHP-specific directive. It is NOT the same as Caddy's standard `php_fastcgi`. It sets up the full PHP serving pipeline including `try_files {path} /index.php` automatically.

### Why `http://localhost` instead of `:80`?

Using `http://localhost` explicitly tells Caddy this server is HTTP-only. Using `:80` alone can still trigger Caddy's HTTPS auto-redirect logic. The `http://` scheme completely disables TLS negotiation for this server block.

---

## Docker Services

### Service Overview

| Service | Container Name | Image | Internal Port | Host Port |
|---|---|---|---|---|
| app | laravel-frankenphp-app | `dunglas/frankenphp:latest-alpine` | 80 | 9000 |
| db | laravel-frankenphp-db | `mariadb:11.4` | 3306 | 3307 |
| redis | laravel-frankenphp-redis | `redis:7-alpine` | 6379 | 6379 |
| phpmyadmin | laravel-frankenphp-phpmyadmin | `phpmyadmin:latest` | 80 | 9001 |
| jaeger | laravel-frankenphp-jaeger | `jaegertracing/all-in-one:latest` | 16686, 4317, 14268 | 9016, 4317, 14268 |

### Health Checks

| Service | Health Check Command |
|---|---|
| app | `curl -f http://localhost/` |
| db | `mysqladmin ping -h localhost` |
| redis | `redis-cli ping` |
| jaeger | `curl -f http://localhost:16686/` |

### Port Separation: DB_PORT vs DB_EXTERNAL_PORT

The `.env` file separates internal and external DB ports to avoid conflicts:

```bash
DB_PORT=3306           # Port Laravel uses to connect internally (db:3306)
DB_EXTERNAL_PORT=3307  # Port exposed on the host machine
```

The `docker-compose.yml` maps:
```yaml
ports:
  - "${DB_EXTERNAL_PORT:-3307}:3306"
```

This means:
- **From the host**: connect to `localhost:3307`
- **From inside Docker**: connect to `db:3306`

---

## Entrypoint Script

### docker/entrypoint-http.sh

```bash
#!/bin/sh
set -e

echo "Initializing Laravel for FrankenPHP..."
cd /app

# 1. Create required directories
mkdir -p bootstrap/cache storage/logs \
         storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views
chmod -R 777 bootstrap/cache storage

# 2. Clear stale cache (ignore errors if DB not ready)
php artisan cache:clear  2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan view:clear   2>/dev/null || true

# 3. Warm config cache
php artisan config:cache 2>/dev/null || true

# 4. Start FrankenPHP
exec frankenphp run --config /app/docker/Caddyfile
```

**Why `cache:clear` may fail on first start:** `cache:clear` attempts a DB connection if the cache driver is set to database. Since MariaDB may still be initializing, the error is suppressed with `2>/dev/null || true`. Config and view cache do not require DB.

---

## Environment Variables Reference

```bash
# ── Application ──────────────────────────────────────────
APP_NAME="Laravel FrankenPHP"
APP_ENV=local                    # local | staging | production
APP_DEBUG=true
APP_URL=http://localhost:9000    # Must match APP_PORT
APP_PORT=9000                    # Host port for FrankenPHP

# ── Database ─────────────────────────────────────────────
DB_CONNECTION=mysql
DB_HOST=db                       # Docker service name (internal)
DB_PORT=3306                     # Internal container port
DB_EXTERNAL_PORT=3307            # Host-side port (for external tools)
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=password
DB_ROOT_PASSWORD=root

# ── Cache & Queue ─────────────────────────────────────────
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis

# ── Redis ─────────────────────────────────────────────────
REDIS_HOST=redis
REDIS_PORT=6379

# ── Session ───────────────────────────────────────────────
SESSION_SECURE_COOKIE=false      # Must be false for HTTP-only dev
SESSION_HTTP_ONLY=true

# ── phpMyAdmin ────────────────────────────────────────────
PMA_PORT=9001

# ── Jaeger ────────────────────────────────────────────────
JAEGER_AGENT_PORT=6831           # UDP agent port
JAEGER_COLLECTOR_PORT=14268      # HTTP collector port
JAEGER_QUERY_PORT=9016           # Jaeger UI host port
```

---

## Service Health Monitoring

The landing page at `http://localhost:9000` displays real-time status of all Docker services, checked on every page load.

### How It Works

`HomeController::checkServices()` performs:

| Service | Check Method | Detail Shown |
|---|---|---|
| FrankenPHP | Always true (we're running) | `PHP 8.3.7` |
| MariaDB | `DB::connection()->getPdo()` | Server version string |
| Redis | `Redis::ping()` | `Connected` |
| phpMyAdmin | `fsockopen('phpmyadmin', 80)` | `port :9001` |
| Jaeger (Tracing) | `fsockopen('jaeger', 16686)` | `port :9016` |
| OpenTelemetry (OTLP) | `fsockopen('jaeger', 4317)` | `gRPC :4317` |

Status indicators:
- 🟢 **Running** — green badge with animated pulse dot
- 🔴 **Down** — red badge with static dot

---

## OpenTelemetry & Jaeger Tracing

### Jaeger Ports

| Port | Protocol | Purpose |
|---|---|---|
| 6831 | UDP | Jaeger agent (legacy Thrift compact) |
| 14268 | HTTP | Jaeger collector (direct from app) |
| 16686 | HTTP | Jaeger Query UI |
| 4317 | gRPC | OTLP collector (OpenTelemetry native) |
| 4318 | HTTP | OTLP collector (HTTP alternative) |

### Access Jaeger UI

**http://localhost:9016**

### Enable OpenTelemetry in Laravel

```bash
docker compose exec app composer require \
    open-telemetry/api \
    open-telemetry/sdk \
    open-telemetry/exporter-otlp \
    open-telemetry/instrumentation-laravel
```

Add to `.env`:
```bash
OTEL_SERVICE_NAME=laravel-frankenphp
OTEL_EXPORTER_OTLP_ENDPOINT=http://jaeger:4317
OTEL_EXPORTER_OTLP_PROTOCOL=grpc
```

---

## Frontend Stack

| Technology | Usage | Source |
|---|---|---|
| Tailwind CSS | Utility-first styling | CDN (`cdn.tailwindcss.com`) |
| Google Font Outfit | Typography (weights 300–900) | Google Fonts API |
| Blade Templates | Server-side rendering | Laravel built-in |

> **No build step required.** Tailwind is loaded via CDN for simplicity. For production, replace with the Tailwind CLI or PostCSS plugin and run `npm run build`.

### View: `resources/views/app.blade.php`

The main landing page renders:
1. **App info grid** — name, environment, PHP version, Laravel version
2. **Docker Services panel** — real-time status of all 6 services
3. **CTA buttons** — API Demo, Laravel Docs

---

## Port Reference

| URL | Service | Notes |
|---|---|---|
| http://localhost:9000 | Laravel App (FrankenPHP) | Main application |
| http://localhost:9001 | phpMyAdmin | DB GUI, login: `laravel`/`password` |
| http://localhost:9016 | Jaeger UI | Distributed tracing |
| localhost:3307 | MariaDB | External access (MySQL clients) |
| localhost:6379 | Redis | Cache/queue |
| localhost:14268 | Jaeger Collector | HTTP traces ingestion |
| localhost:4317 | OTLP gRPC | OpenTelemetry traces |

---

## Common Workflows

### Start / Stop

```bash
# Start all services
docker compose up -d

# Stop all services (data preserved in volumes)
docker compose down

# Stop and wipe all data
docker compose down -v
```

### Rebuild After Dockerfile/Caddyfile Change

```bash
docker compose down
docker rmi laravel-frankenphp-app
docker compose build --no-cache
docker compose up -d
```

### Apply Code Changes (No Rebuild Needed)

Files are volume-mounted, so edits on the host are live. Just clear caches:

```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
docker compose exec app php artisan cache:clear
```

### Database Management

```bash
# Run migrations
docker compose exec app php artisan migrate

# Fresh migration + seed
docker compose exec app php artisan migrate:fresh --seed

# Access MariaDB shell
docker compose exec db mariadb -u laravel -ppassword laravel

# Or use phpMyAdmin at http://localhost:9001
```

### Debugging

```bash
# FrankenPHP / Caddy logs
docker compose logs -f app

# Check all service statuses
docker compose ps

# Inspect a container
docker compose exec app sh

# Test DB connection
docker compose exec app php artisan db:show

# Test Redis
docker compose exec redis redis-cli ping
```

### Check FrankenPHP Version

```bash
docker compose exec app frankenphp version
# FrankenPHP v1.x PHP 8.3.x Caddy v2.x
```
