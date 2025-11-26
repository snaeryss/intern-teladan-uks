# UKS-Teladan
UKS & Medical Record For Student - Sekolah Teladan

## 🩺 Fitur Utama
* **Multi-role**: Supervisor, Doctor, Student
* **MCU & DCU**: Pemeriksaan lengkap (vital signs, gigi, OHIS, BMI, dsb.)
* **Medical Records**: Riwayat kunjungan, waiting list, export/cetak
* **Academic**: Tahun ajaran, siswa, kelas, sinkronisasi
* **Reporting**: Statistik & laporan kesehatan
* **Master Data**: Dokter, lokasi, periode, referensi medis

## 💻 Teknologi
* **Backend**: Laravel 11, PHP 8.2.x
* **Frontend**: Blade, Vite, Axios, Bootstrap, Tailwind

## 🚀 Quick Start
### Prerequisites
```bash
> PHP >= 8.2
> Composer
> Node.js & NPM
> MySQL
```

### Installation
# 1. Clone repository
```bash
git clone https://github.com/username/teladan-uks-laravel.git
cd teladan-uks-laravel
```

# 2. Install dependencies
```bash
composer install
npm install
```

# 3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

# 4. Database setup (SQLite default)
```bash
touch database/database.sqlite
php artisan migrate
```

# 5. Seed data (optional)
```bash
php artisan db:seed
```

# 6. Build assets
```bash
npm run build
```

# 7. Run application
```bash
php artisan serve
```




