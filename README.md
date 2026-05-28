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

### 1. Clone Repository
```bash
git clone https://github.com/YOUR_USERNAME/laravel-frankenphp-docker-demo.git
cd laravel-frankenphp-docker-demo
```

### 2. Buat File Environment
```bash
cp .env.example .env
```

### 3. Build Docker Images
```bash
docker compose build
```

### 4. Generate Application Key (Otomatis via Entrypoint)
Key akan di-generate otomatis saat container pertama kali dijalankan.

### 5. Jalankan Container
```bash
docker compose up -d
```

### 6. Jalankan Migrasi Database (Otomatis)
Database akan di-migrate otomatis saat container startup. Jika ingin manual:
```bash
docker compose exec app php artisan migrate
```

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
- **MySQL**: localhost:3306

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

### 2. **MySQL Database (db)**
- Versi: MySQL 8.0
- Default database: `laravel`
- Port: 3306
- Health check: ✅ Included

### 3. **Redis Cache (redis)**
- Digunakan untuk caching dan queue
- Versi: Redis 7 (Alpine)
- Port: 6379
- Health check: ✅ Included

### 4. **phpMyAdmin (phpmyadmin)** (Optional)
- GUI untuk manage MySQL database
- Port: 8080
- Username: `laravel` / Password: `password`

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

## 🐛 Troubleshooting

### Port 80 sudah digunakan
Edit `docker-compose.yml` dan ubah port:
```yaml
ports:
  - "8080:80"  # Akses via http://localhost:8080
```

### Database connection error
Pastikan service `db` sudah running:
```bash
docker compose ps
```

### Permission denied errors
Jalankan:
```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

### Clear cache
```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
```

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs)
- [FrankenPHP Documentation](https://frankenphp.dev/)
- [Docker Documentation](https://docs.docker.com/)

## 📝 Lisensi

MIT License - silakan gunakan untuk project personal maupun komersial

## 👤 Author

Dibuat sebagai demo untuk mempelajari integrasi Laravel dengan FrankenPHP dan Docker.

---

**Selamat belajar! 🎉**
