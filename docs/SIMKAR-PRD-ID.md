# SIMKAR - Product Requirements Document (PRD)

## Sistem Informasi Mutasi Kamar

### 1. Ringkasan Produk
SIMKAR adalah aplikasi berbasis web untuk mencatat, mengelola, dan memonitor proses perpindahan WBP antar kamar secara digital.

### 2. Latar Belakang
Sistem dibuat untuk menggantikan pencatatan manual sehingga data mutasi terdokumentasi dengan baik, mudah dicari, dan dapat menghasilkan laporan secara cepat.

### 3. Tujuan Sistem
- Mendigitalisasi proses mutasi kamar
- Menyimpan riwayat perpindahan penghuni
- Mempermudah pencarian data mutasi
- Mempermudah monitoring kamar dan penghuni
- Mempermudah pembuatan laporan
- Meningkatkan akurasi dan keamanan data

### 4. Pengguna Sistem
#### Admin
- Kelola pengguna
- Kelola data WBP
- Kelola data kamar
- Kelola mutasi
- Melihat laporan

#### Petugas
- Input mutasi
- Melihat data WBP
- Melihat data kamar
- Melihat riwayat mutasi
- Mencetak laporan

### 5. Fitur Utama
#### Login
- Login
- Logout
- Manajemen sesi pengguna

#### Dashboard
- Total WBP aktif
- Total kamar
- Kamar terisi
- Kamar tersedia
- Jumlah mutasi hari ini
- Aktivitas terbaru

#### Data WBP
- Tambah, edit, hapus
- Pencarian
- Filter

#### Data Kamar
- Tambah, edit, hapus
- Monitoring kapasitas

#### Input Mutasi
- Nama WBP
- Kamar Asal
- Kamar Tujuan
- Waktu Mutasi
- Nama Petugas
- Catatan
- Tanda Tangan

#### Riwayat Mutasi
- Pencarian
- Filter tanggal
- Detail mutasi

#### Laporan
- PDF
- Excel

### 6. Teknologi
#### Backend
- Laravel

#### Frontend
- Laravel Livewire
- Blade
- Tailwind CSS
- Alpine.js

#### Database
- MySQL

### 7. MVP
- Login
- Dashboard
- Data WBP
- Data Kamar
- Input Mutasi
- Riwayat Mutasi
- Laporan PDF
- Export Excel
- Tanda Tangan Digital
