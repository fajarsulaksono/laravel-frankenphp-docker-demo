# Laravel FrankenPHP Docker Demo

Demonstrasi lengkap aplikasi Laravel yang dijalankan dengan **FrankenPHP** (PHP runtime modern) menggunakan **Docker** dan **Docker Compose**.

## 📋 Daftar Isi

- [Tentang FrankenPHP](#tentang-frankenphp)
- [Persyaratan](#persyaratan)
- [Instalasi & Setup](#instalasi--setup)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Struktur Folder](#struktur-folder)
- [Perintah Berguna](#perintah-berguna)
- [Troubleshooting](#troubleshooting)

## 🚀 Tentang FrankenPHP

**FrankenPHP** adalah runtime PHP modern yang:
- ✅ Built on top of Caddy web server
- ✅ Support async/await dengan Fiber
- ✅ Zero-downtime deployment
- ✅ Otomatis HTTPS dengan Let's Encrypt
- ✅ Performance yang lebih baik dibanding PHP-FPM tradisional

## 📦 Persyaratan

- Docker (versi 20.10+)
- Docker Compose (versi 1.29+)
- Git

## 🔧 Instalasi & Setup

### Quick Start (First Time Setup)

Jika ini pertama kali Anda setup project ini, ikuti langkah-langkah di bawah:

#### **Step 1: Clone Repository**
```bash
git clone https://github.com/YOUR_USERNAME/laravel-frankenphp-docker-demo.git
cd laravel-frankenphp-docker-demo
```

#### **Step 2: Setup Environment File**
```bash
cp .env.example .env
```

Edit file `.env` jika ada pengaturan khusus (port, password, dll):
```bash
nano .env    # atau gunakan editor favorit Anda
```

#### **Step 3: Build Docker Images** ⏱️ *Ini bisa memakan waktu 5-15 menit*
```bash
docker compose build
```

**Output yang diharapkan:**
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

**Check status containers:**
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
Tunggu beberapa saat agar semua service fully initialized, lalu cek:

```bash
# Check app logs
docker compose logs app

# Verify app is running
curl http://localhost

# Verify database connection
docker compose exec app php artisan tinker
> DB::connection()->getPdo()  # Tekan Enter, jika berhasil akan return PDO object
```

#### **Step 6: Access The Application**
Setelah semua service running, akses aplikasi di browser:

- 🌐 **Laravel App**: http://localhost
- 🗄️ **phpMyAdmin**: http://localhost:8080 (username: `laravel`, password: `password`)
- 📊 **Jaeger UI**: http://localhost:16686
- 💾 **Database**: localhost:3306 (host: `db`, user: `laravel`, password: `password`)
- 🔴 **Redis**: localhost:6379

---

### Stop dan Remove Containers

Jika ingin menghentikan development:

```bash
# Stop all containers (data tetap tersimpan)
docker compose stop

# Start kembali
docker compose start

# Stop dan remove containers (tapi data tetap di volume)
docker compose down

# Stop dan remove semuanya termasuk volumes (WARNING: DATA AKAN HILANG!)
docker compose down -v
```

---

### Automated Setup (Apa yang otomatis terjadi)

Saat container pertama kali dimulai, file `docker/entrypoint.sh` secara otomatis akan:
1. ✅ Menggenerate `APP_KEY` jika belum ada
2. ✅ Menjalankan database migrations
3. ✅ Set proper permissions untuk folders
4. ✅ Start Laravel development server

**Jadi Anda tidak perlu manual jalankan perintah ini, semua sudah otomatis!**

## ▶️ Menjalankan Aplikasi

### Build dan Jalankan Container
```bash
docker compose up -d
```

Aplikasi akan tersedia di: `http://localhost`

### Akses Services
- **Laravel Application**: http://localhost
- **phpMyAdmin**: http://localhost:8080
- **Redis**: localhost:6379
- **MariaDB**: localhost:3306
- **Jaeger UI**: http://localhost:16686

### Stop Container
```bash
docker compose down
```

### Lihat Log Application
```bash
docker compose logs -f app
```

## 📁 Struktur Folder

```
laravel-frankenphp-docker-demo/
├── app/                      # Kode aplikasi Laravel
├── bootstrap/                # Bootstrap files
├── config/                   # Konfigurasi aplikasi
├── database/                 # Migrations & seeders
├── public/                   # Public assets
├── resources/                # Views, CSS, JS
├── routes/                   # Route definitions
├── storage/                  # Logs dan cache
├── tests/                    # Test files
├── docker/                   # Docker configurations
│   ├── Dockerfile            # FrankenPHP setup
│   └── entrypoint.sh         # Container startup script
├── docker-compose.yml        # Docker services orchestration
├── .env.example              # Example environment file
└── README.md                 # File ini
```

## 🐳 Docker Services

### 1. **FrankenPHP (app)**
- Runtime PHP modern dengan Fiber support
- Automatic HTTPS dengan Caddy
- Zero-downtime deployment
- Port: 80

### 2. **MariaDB Database (db)**
- Versi: MariaDB Latest (drop-in replacement untuk MySQL)
- Default database: `laravel`
- Port: 3306
- Health check: ✅ Included

### 3. **Redis Cache (redis)**
- Digunakan untuk caching dan queue
- Versi: Redis 7 (Alpine)
- Port: 6379
- Health check: ✅ Included

### 4. **phpMyAdmin (phpmyadmin)** (Optional)
- GUI untuk manage MariaDB database
- Port: 8080
- Username: `laravel` / Password: `password`

### 5. **Jaeger (jaeger)**
- Distributed tracing dan monitoring untuk OpenTelemetry
- Query UI: http://localhost:16686
- Collector Port: 14268 (HTTP)
- Agent Port: 6831 (UDP)

## 🎯 Perintah Berguna

### Artisan Commands
```bash
# Jalankan migrations
docker compose exec app php artisan migrate

# Create model dengan migration
docker compose exec app php artisan make:model Post -m

# Create controller
docker compose exec app php artisan make:controller PostController

# Jalankan seeding
docker compose exec app php artisan db:seed

# Akses Laravel Tinker
docker compose exec app php artisan tinker
```

### Container Management
```bash
# Lihat logs real-time
docker compose logs -f app

# Lihat logs service tertentu
docker compose logs -f db    # MySQL logs
docker compose logs -f redis # Redis logs

# Masuk ke container shell
docker compose exec app bash

# Rebuild images (jika update Dockerfile)
docker compose build --no-cache

# Check status semua services
docker compose ps

# Restart specific service
docker compose restart app
```

### Database Management
```bash
# Akses MySQL CLI
docker compose exec db mysql -u laravel -p -D laravel

# Reset database
docker compose exec app php artisan migrate:refresh

# Fresh migration dengan seeding
docker compose exec app php artisan migrate:fresh --seed
```

## 🔐 Environment Variables

Edit file `.env` untuk konfigurasi:

```bash
# Application
APP_NAME=Laravel                    # Nama aplikasi
APP_ENV=local                      # Environment (local, staging, production)
APP_DEBUG=true                     # Debug mode
APP_URL=http://localhost           # URL aplikasi
APP_PORT=80                        # Port aplikasi

# Database
DB_CONNECTION=mysql                # Driver (mysql, pgsql)
DB_HOST=db                         # Host database
DB_PORT=3306                       # Port database
DB_DATABASE=laravel                # Database name
DB_USERNAME=laravel                # Database user
DB_PASSWORD=password               # Database password

# Cache & Queue
CACHE_DRIVER=redis                 # Cache driver (redis, file)
QUEUE_CONNECTION=redis             # Queue driver

# Redis
REDIS_HOST=redis                   # Redis host
REDIS_PORT=6379                    # Redis port
```

## 📊 OpenTelemetry & Jaeger Distributed Tracing

### Setup OpenTelemetry di Laravel

Untuk mengaktifkan OpenTelemetry dan mengirim trace ke Jaeger, install packages:

```bash
docker compose exec app composer require \
    open-telemetry/api \
    open-telemetry/sdk \
    open-telemetry/exporter-jaeger \
    open-telemetry/instrumentation-laravel
```

### Konfigurasi di `.env`:

```bash
OTEL_ENABLED=true                           # Aktifkan OpenTelemetry
OTEL_SERVICE_NAME=laravel-frankenphp        # Nama service untuk tracing
OTEL_EXPORTER_OTLP_ENDPOINT=http://jaeger:4317  # Jaeger collector endpoint
JAEGER_ENABLED=true                         # Aktifkan Jaeger exporter
```

### Akses Jaeger UI

Buka browser: **http://localhost:16686**

Di sini Anda bisa:
- ✅ Lihat distributed traces dari Laravel application
- ✅ Monitor performance setiap request
- ✅ Identify bottlenecks & slow queries
- ✅ Trace database queries, Redis calls, HTTP requests

## 🐛 Troubleshooting

### Masalah Saat First Time Setup

#### **Container tidak starting (exit immediately)**
```bash
# Check logs untuk error details
docker compose logs app

# Solution: Rebuild dengan fresh start
docker compose down -v  # Remove containers dan volumes
docker compose build --no-cache
docker compose up -d
```

#### **Port sudah digunakan**
Jika port 80 sudah digunakan oleh service lain, edit `docker-compose.yml`:
```yaml
ports:
  - "8000:80"  # Ubah dari 80 ke 8000
```
Kemudian restart: `docker compose up -d`

#### **Database connection error**
```bash
# Wait database fully initialized
docker compose logs db | tail -20

# Manual check connection
docker compose exec app php artisan tinker
> DB::connection()->getPdo()

# If still error, try refresh:
docker compose exec app php artisan migrate:refresh
```

#### **Permission denied di storage folder**
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

#### **APP_KEY belum di-generate**
Jika Anda lihat error `No application encryption key has been specified.`:
```bash
docker compose exec app php artisan key:generate
docker compose restart app
```

### Umum Troubleshooting

#### **Clear cache dan config**
```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan view:clear
```

#### **Jaeger tidak menampilkan traces**
```bash
# Check Jaeger is running
docker compose ps jaeger

# Check collector endpoint
docker compose logs jaeger | grep "listening on"

# Verify connection dari app
docker compose exec app curl -v http://jaeger:14268/api/traces
```

#### **Containers stuck atau not responding**
```bash
# Force stop all
docker compose down

# Remove semua containers dan volumes
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

Semua file aplikasi Anda sudah di-mount ke container, jadi changes lokal otomatis reflected:

```bash
# Edit files dari host machine
nano app/Http/Controllers/HomeController.php

# Changes otomatis available di container
docker compose exec app php artisan tinker
> include 'app/Http/Controllers/HomeController.php'
```

### Develop dengan Hot Reload

Untuk development dengan file watcher (auto-reload), gunakan:

```bash
# Watch mode untuk CSS/JS
docker compose exec app npm run dev

# Atau jika pakai Vite
docker compose exec app npm run dev -- --host 0.0.0.0
```

### Database Development

```bash
# Buat migration baru
docker compose exec app php artisan make:migration create_products_table

# Edit migration file (akan automount ke container)
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

### Git Workflow dalam Docker

```bash
# Jika ingin gunakan git dari dalam container
docker compose exec app git status
docker compose exec app git add .
docker compose exec app git commit -m "feat: add new feature"

# Atau pakai git dari host (lebih recommended)
git status
git add .
git commit -m "feat: add new feature"
```

## �📚 Resources

- [Laravel Documentation](https://laravel.com/docs)
- [FrankenPHP Documentation](https://frankenphp.dev/)
- [Docker Documentation](https://docs.docker.com/)- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [MariaDB Documentation](https://mariadb.com/docs/)
- [Redis Documentation](https://redis.io/documentation)
- [OpenTelemetry PHP](https://opentelemetry.io/docs/instrumentation/php/)
- [Jaeger Documentation](https://www.jaegertracing.io/docs/)
## 📝 Lisensi

MIT License - silakan gunakan untuk project personal maupun komersial

## 👤 Author

Dibuat sebagai demo untuk mempelajari integrasi Laravel dengan FrankenPHP dan Docker.

---

**Selamat belajar! 🎉**
