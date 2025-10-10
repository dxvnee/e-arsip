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

## 🎯 Next Steps

1. Login dengan akun admin
2. Eksplorasi dashboard
3. Coba tambah arsip baru
4. Upload file dokumen
5. Test fitur pencarian
6. Lihat log aktivitas

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
