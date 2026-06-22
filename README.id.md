# SIMKAR

[English](README.md) | **Bahasa Indonesia**

SIMKAR (**Sistem Informasi Mutasi Kamar**) adalah aplikasi web untuk mencatat, mengelola, dan memantau perpindahan Warga Binaan Pemasyarakatan (WBP) antar kamar. Aplikasi ini membantu menggantikan pencatatan manual dengan riwayat mutasi yang mudah dicari, dipantau, dan diekspor.

## Fitur utama

- Dashboard ringkasan WBP, kamar, kapasitas, dan mutasi terbaru
- Pengelolaan data WBP dan penempatan kamar
- Pengelolaan kamar beserta pemantauan kapasitas
- Pencatatan dan riwayat mutasi kamar
- QR code umum atau per kamar untuk mempercepat input mutasi
- Filter laporan berdasarkan tanggal, petugas, dan kamar
- Ekspor laporan ke PDF dan Excel
- Manajemen pengguna dengan hak akses Admin dan Petugas
- Pengelolaan profil dan perubahan kata sandi

## Hak akses

| Fitur | Admin | Petugas |
| --- | :---: | :---: |
| Dashboard | ✓ | ✓ |
| Melihat data kamar dan WBP | ✓ | ✓ |
| Mengelola data kamar dan WBP | ✓ | — |
| Membuat dan melihat mutasi | ✓ | ✓ |
| Melihat dan mengekspor laporan | ✓ | ✓ |
| Mengelola pengguna | ✓ | — |

## Teknologi

- PHP 8.3+
- Laravel 13
- Livewire 4 dan Blade
- Tailwind CSS 4
- Vite 8
- MySQL
- DomPDF, Laravel Excel, dan Endroid QR Code

## Persyaratan

Pastikan perangkat pengembangan telah memiliki:

- PHP 8.3 atau lebih baru beserta ekstensi yang diperlukan Composer
- Composer
- Node.js dan npm
- MySQL

## Instalasi

1. Clone repositori dan masuk ke direktorinya.

   ```bash
   git clone <repository-url> simkar
   cd simkar
   ```

2. Instal dependensi PHP dan salin konfigurasi lingkungan.

   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

   Pada Windows PowerShell, gunakan `Copy-Item .env.example .env` sebagai pengganti perintah `cp`.

3. Buat database MySQL, lalu sesuaikan bagian berikut pada `.env`.

   ```dotenv
   APP_NAME=SIMKAR
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=simkar
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Jalankan migrasi dan buat akun administrator awal.

   ```bash
   php artisan migrate --seed
   ```

5. Instal dependensi frontend dan bangun aset.

   ```bash
   npm install
   npm run build
   ```

6. Jalankan aplikasi.

   ```bash
   composer run dev
   ```

   Aplikasi tersedia di [http://localhost:8000](http://localhost:8000).

### Data demo opsional

Untuk menambahkan contoh petugas, kamar, WBP, dan riwayat mutasi setelah menjalankan seeder utama:

```bash
php artisan db:seed --class=DevSeeder
```

Jangan menjalankan `DevSeeder` pada lingkungan produksi karena data yang dibuat bersifat contoh.

## Akun awal

Seeder utama membuat akun berikut khusus untuk instalasi lokal:

| Email | Kata sandi | Peran |
| --- | --- | --- |
| `admin@simkar.test` | `password` | Admin |

Segera ubah kata sandi melalui halaman profil, terutama jika aplikasi dapat diakses dari jaringan lain.

## Perintah pengembangan

```bash
# Menjalankan server, worker antrean, log viewer, dan Vite
composer run dev

# Menjalankan seluruh pengujian
composer test

# Memformat kode PHP
./vendor/bin/pint

# Memformat Blade
npm run format

# Membuat aset untuk produksi
npm run build
```

## Deployment singkat

Pada lingkungan produksi:

```bash
composer install --no-dev --optimize-autoloader
npm install --ignore-scripts
npm run build
php artisan migrate --force
php artisan optimize
```

Pastikan `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` sesuai domain, web server mengarah ke direktori `public`, serta direktori `storage` dan `bootstrap/cache` dapat ditulis oleh proses web server. Siapkan worker antrean jika `QUEUE_CONNECTION` tidak menggunakan `sync`.

## Dokumentasi

- [Product Requirements Document — Indonesia](docs/SIMKAR-PRD-ID.md)
- [Product Requirements Document — English](docs/SIMKAR-PRD-EN.md)
- [Daftar halaman — Indonesia](docs/SIMKAR-Pages-ID.md)
- [Daftar halaman — English](docs/SIMKAR-Pages-EN.md)

## Diagram basis data

[![Diagram basis data SIMKAR](docs/db/dbdiagram.png)](docs/db/dbdiagram.png)

## Struktur penting

```text
app/Livewire/          Komponen halaman dan logika antarmuka
app/Models/            Model data aplikasi
database/migrations/  Struktur tabel basis data
database/seeders/     Seeder akun awal dan data demo
resources/views/      Tampilan Blade
routes/web.php         Daftar route aplikasi
tests/                 Pengujian unit dan fitur
```

## Lisensi

Proyek ini menggunakan lisensi MIT sebagaimana dinyatakan dalam `composer.json`.
