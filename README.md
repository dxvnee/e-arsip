# 📁 Sistem Informasi E-Arsip Dinas Kesehatan

Sistem Informasi E-Arsip Dinas Kesehatan adalah aplikasi web berbasis Laravel untuk mengelola arsip digital dengan fitur lengkap dan desain profesional.

## 🎨 Desain & Tema

- **Warna Utama**: Putih (Base)
- **Warna Primer**: #008e3c (Hijau Tua)
- **Warna Sekunder**: #efd856 (Kuning Lembut)
- **Framework CSS**: Tailwind CSS
- **Icons**: Font Awesome 6

## ✨ Fitur Utama

### 1. **Autentikasi & Otorisasi**
- Login/Logout menggunakan Laravel Breeze
- 3 Level Pengguna:
  - **Admin**: Akses penuh ke semua fitur
  - **Operator**: Dapat mengelola arsip
  - **Viewer**: Hanya dapat melihat arsip
- Middleware role-based access control
- Validasi status aktif pengguna

### 2. **Dashboard Interaktif**
- Statistik ringkas (Total arsip, arsip bulan ini, unit kerja, pengguna)
- Grafik arsip per jenis
- Daftar 10 arsip terbaru
- Log aktivitas pengguna real-time
- Desain responsif dengan animasi hover

### 3. **Manajemen Arsip**
- CRUD arsip lengkap (Create, Read, Update, Delete)
- Upload file (PDF, Word, Excel, Gambar) max 10MB
- Auto-generate nomor arsip (Format: ARS/YYYY/MM/0001)
- Pencarian dan filter multi-parameter
- Download file arsip
- Tracking jumlah view dan download

## 🛠️ Teknologi

- **Backend**: Laravel 12.x (Latest)
- **Database**: MySQL 8.0+
- **Frontend**: Blade Templates + Tailwind CSS 3.x
- **JavaScript**: Alpine.js
- **Icons**: Font Awesome 6
- **Authentication**: Laravel Breeze

## 🚀 Instalasi

### 1. Konfigurasi Database

Edit file `.env` dan sesuaikan:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_arsip_dinkes
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 2. Buat Database

```bash
mysql -u root -p
```

```sql
CREATE DATABASE e_arsip_dinkes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 3. Jalankan Migrasi & Seeder

```bash
# Jalankan migrasi
php artisan migrate

# Jalankan seeder untuk data awal
php artisan db:seed --class=InitialDataSeeder

# Generate storage link
php artisan storage:link
```

### 4. Jalankan Aplikasi

```bash
# Jalankan server
php artisan serve
```

Aplikasi akan berjalan di: `http://localhost:8000`

## 👤 Akun Default

### Admin
- **Email**: admin@dinkes.go.id
- **Password**: password

### Operator
- **Email**: operator@dinkes.go.id
- **Password**: password

### Viewer
- **Email**: viewer@dinkes.go.id
- **Password**: password

## 🎯 Fitur Per Role

### Admin
✅ Dashboard  
✅ Manajemen Arsip (CRUD)  
✅ Manajemen Kategori Arsip  
✅ Manajemen Unit Kerja  
✅ Manajemen Pengguna  
✅ Laporan  

### Operator
✅ Dashboard  
✅ Manajemen Arsip (CRUD)  
✅ Laporan  

### Viewer
✅ Dashboard  
✅ Lihat Daftar Arsip  
✅ Download Arsip  
✅ Laporan  

## 🔧 Troubleshooting

### CSS tidak muncul atau warna tidak sesuai

```bash
# Clear cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Rebuild assets
npm run build
```

### Error Permission Storage

```bash
chmod -R 775 storage bootstrap/cache
```

---

**Dibuat dengan ❤️ menggunakan Laravel & Tailwind CSS**
