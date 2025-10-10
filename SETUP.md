# 🚀 Panduan Setup Cepat E-Arsip Dinas Kesehatan

## Langkah-langkah Setup

### 1️⃣ Setup Database MySQL

```bash
# Buka MySQL
mysql -u root -p

# Buat database
CREATE DATABASE e_arsip_dinkes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Keluar dari MySQL
EXIT;
```

### 2️⃣ Konfigurasi .env

File `.env` sudah ada, edit jika perlu mengubah password MySQL:

```env
DB_PASSWORD=your_mysql_password_here
```

### 3️⃣ Jalankan Migrasi & Seeder

```bash
# Jalankan migrasi database
php artisan migrate

# Isi database dengan data awal
php artisan db:seed --class=InitialDataSeeder

# Buat symbolic link untuk storage
php artisan storage:link
```

### 4️⃣ Jalankan Aplikasi

```bash
# Start Laravel development server
php artisan serve
```

Buka browser: **http://localhost:8000**

## 🔑 Login Credentials

Gunakan salah satu akun berikut untuk login:

### 👨‍💼 Admin (Full Access)
```
Email: admin@dinkes.go.id
Password: password
```

### 👤 Operator (Manage Archives)
```
Email: operator@dinkes.go.id
Password: password
```

### 👁️ Viewer (View Only)
```
Email: viewer@dinkes.go.id
Password: password
```

## 🎨 Fitur Desain yang Sudah Diterapkan

✅ Warna Primer: **#008e3c** (Hijau Tua)  
✅ Warna Sekunder: **#efd856** (Kuning Lembut)  
✅ Logo dengan circular badge kuning  
✅ Sidebar dengan background hijau tua  
✅ Menu icons berwarna kuning  
✅ Active menu dengan shadow effect  
✅ Hover effect pada semua menu  
✅ User info section dengan border kuning  
✅ Dashboard cards dengan gradient hijau  
✅ Statistics cards dengan border warna custom  
✅ Hover animation pada cards  
✅ Progress bars dengan warna hijau  
✅ Header sections dengan background subtle  

## 🔄 Rebuild CSS (Jika Perlu)

Jika warna tidak muncul atau tampilan berubah:

```bash
# Clear all cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Rebuild assets
npm run build
```

## 📝 Data Sample yang Tersedia

Setelah menjalankan seeder, sistem akan memiliki:

- ✅ 3 Pengguna (Admin, Operator, Viewer)
- ✅ 3 Unit Kerja (Sekretariat, Yankes, P2P)
- ✅ 5 Kategori Arsip (SK, SE, Laporan, Surat Masuk, Surat Keluar)
- ✅ 3 Arsip Sample

## ✨ Fitur Lengkap Sistem

### 1. 📁 Manajemen Arsip
- ✅ Input arsip surat masuk, keluar, dokumen administrasi
- ✅ Upload file digital (PDF, Word, Excel, Gambar - max 10MB)
- ✅ Metadata lengkap (nomor, tanggal, asal, tujuan, perihal, kategori)
- ✅ Klasifikasi status (aktif, inaktif, musnah)
- ✅ **Update & Versioning** dokumen dengan history
- ✅ Auto-generate nomor arsip (ARS/YYYY/MM/0001)
- ✅ Tracking view & download count

### 2. 🔍 Pencarian & Penelusuran
- ✅ Pencarian cepat by kata kunci (judul, nomor surat, tanggal)
- ✅ Filter multi-parameter:
  - Kategori arsip
  - Unit kerja
  - Jenis arsip
  - Rentang tanggal
  - Status arsip
- ✅ Hasil terstruktur dengan pagination

### 3. 📋 Disposisi Digital
- ✅ Pembuatan disposisi elektronik
- ✅ Penerusan ke pegawai dengan catatan
- ✅ Status tracking (baru, dibaca, diproses, selesai)
- ✅ Prioritas (biasa, segera, sangat segera)
- ✅ Sifat (biasa, rahasia, penting)
- ✅ Tindak lanjut & timestamp lengkap

### 4. 👥 Manajemen Pengguna & Hak Akses
- ✅ Role-based access:
  - **Admin**: Full access
  - **Operator**: Manage arsip & disposisi
  - **Petugas**: Manage arsip
  - **Viewer**: View only
- ✅ Login & autentikasi (Laravel Breeze)
- ✅ Status aktif/nonaktif user
- ✅ Assignment unit kerja

### 5. 🔐 Keamanan & Audit
- ✅ Autentikasi user dengan password hash
- ✅ Hak akses per level (middleware)
- ✅ Log aktivitas lengkap:
  - Create, Read, Update, Delete
  - Download, Login, Logout
  - IP Address & User Agent tracking
- ✅ CSRF Protection
- ✅ XSS Protection
- ✅ SQL Injection protection

### 6. 📊 Laporan & Statistik
- ✅ Laporan arsip masuk/keluar per periode
- ✅ Statistik lengkap:
  - Jumlah arsip aktif, inaktif, musnah
  - Per jenis arsip
  - Per kategori
  - Per unit kerja
- ✅ Laporan disposisi
- ✅ Laporan aktivitas user
- ✅ Dashboard analytics real-time

### 7. 🎨 User Interface
- ✅ Design modern & responsive
- ✅ Warna kustom (#008e3c & #efd856)
- ✅ Dark mode sidebar
- ✅ Hover animations
- ✅ Mobile-friendly

## 🎯 Next Steps

1. Login dengan akun admin
2. Eksplorasi dashboard dengan statistik
3. Tambah arsip baru dengan upload file
4. Buat disposisi untuk arsip
5. Test pencarian & filter
6. Lihat laporan & statistik
7. Check log aktivitas
8. Test versioning dokumen

## 🆘 Troubleshooting

### Error: "Access denied for user 'root'@'localhost'"
**Solusi**: Update password MySQL di file `.env`

### Error: "No such file or directory"
**Solusi**: Pastikan MySQL service sudah running
```bash
# MacOS
brew services start mysql

# Linux
sudo service mysql start
```

### CSS tidak muncul
**Solusi**: 
```bash
npm run build
php artisan cache:clear
```

### Permission denied pada storage
**Solusi**:
```bash
chmod -R 775 storage bootstrap/cache
```

---

## ✨ Selamat! Aplikasi E-Arsip Siap Digunakan!

Jika ada pertanyaan, hubungi admin sistem.
