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

### 3. Generate Application Key
```bash
docker compose exec app php artisan key:generate
```

### 4. Jalankan Migrasi Database
```bash
docker compose exec app php artisan migrate
```

## ▶️ Menjalankan Aplikasi

### Build dan Jalankan Container
```bash
docker compose up -d
```

Aplikasi akan tersedia di: `http://localhost`

### Stop Container
```bash
docker compose down
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
│   ├── Dockerfile
│   └── entrypoint.sh
├── docker-compose.yml        # Docker Compose configuration
├── .env.example              # Example environment file
└── README.md                 # File ini
```

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
# Lihat logs
docker compose logs -f app

# Masuk ke container shell
docker compose exec app bash

# Rebuild images
docker compose build --no-cache

# Check status
docker compose ps
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
