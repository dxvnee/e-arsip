# 🧪 Panduan Testing E-Arsip Dinas Kesehatan

## 🚀 Cara Menjalankan Aplikasi

```bash
# 1. Pastikan di folder project
cd /Users/dxvnee/projects/E-Arsip/e-arsip-dinkes

# 2. Jalankan server
php artisan serve
```

Aplikasi akan berjalan di: **http://localhost:8000**

---

## ✅ Checklist Testing Fitur

### 1. Login & Autentikasi
- [ ] Buka http://localhost:8000
- [ ] Login dengan: **admin@dinkes.go.id** / **password**
- [ ] Cek apakah redirect ke dashboard
- [ ] Lihat menu sidebar sesuai role

### 2. Dashboard
**URL**: http://localhost:8000/dashboard

Harus tampil:
- [ ] Welcome banner dengan gradient hijau
- [ ] 4 statistik cards (Total Arsip, Arsip Bulan Ini, Unit Kerja, Pengguna)
- [ ] Arsip Terbaru (list 10 terbaru)
- [ ] Chart Arsip Per Jenis dengan progress bar
- [ ] Tabel Aktivitas Terbaru

### 3. Daftar Arsip ✅ FIXED
**URL**: http://localhost:8000/arsip

Harus tampil:
- [ ] Header "Daftar Arsip" dengan tombol "Tambah Arsip"
- [ ] Form pencarian & filter dengan 8 parameter:
  - Pencarian (text)
  - Kategori (dropdown)
  - Unit Kerja (dropdown)
  - Jenis Arsip (dropdown)
  - Status (dropdown)
  - Tanggal Dari (date)
  - Tanggal Sampai (date)
  - Tombol Cari
- [ ] Tabel list arsip dengan kolom:
  - Nomor & Judul
  - Kategori (badge berwarna)
  - Jenis
  - Tanggal
  - Status (badge)
  - Aksi (view, edit, download)
- [ ] Pagination di bawah tabel

**Test Filter:**
1. Coba cari arsip dengan kata kunci
2. Filter by kategori
3. Filter by tanggal
4. Kombinasi multiple filter
5. Reset filter

### 4. Laporan & Statistik ✅ FIXED
**URL**: http://localhost:8000/laporan

Harus tampil 4 card laporan:

#### A. Laporan Arsip Masuk/Keluar
- [ ] Form dengan 2 input date (tanggal mulai & akhir)
- [ ] Tombol "Lihat Laporan"
- [ ] Submit ke `/laporan/arsip-masuk-keluar`

#### B. Statistik Arsip
- [ ] Deskripsi fitur (Status, Jenis, Kategori, Unit Kerja)
- [ ] Tombol "Lihat Statistik"
- [ ] Link ke `/laporan/statistik`

**Test Statistik** (http://localhost:8000/laporan/statistik):
- [ ] 4 cards: Total, Aktif, Inaktif, Musnah
- [ ] Chart Arsip Per Jenis (dengan progress bar hijau)
- [ ] Chart Arsip Per Kategori (dengan progress bar kuning)
- [ ] Cards Arsip Per Unit Kerja (grid 3 kolom)

#### C. Laporan Aktivitas
- [ ] Form dengan 2 input date
- [ ] Submit ke `/laporan/aktivitas`

#### D. Laporan Disposisi
- [ ] Form dengan 2 input date
- [ ] Submit ke `/laporan/disposisi`

### 5. Menu Navigation
Cek semua menu di sidebar:

**Untuk Admin:**
- [ ] Dashboard
- [ ] Arsip (dropdown)
  - [ ] Daftar Arsip
  - [ ] Tambah Arsip
- [ ] Master Data (dropdown)
  - [ ] Kategori Arsip
  - [ ] Unit Kerja
- [ ] Pengguna
- [ ] Laporan

**Untuk Operator:**
- [ ] Dashboard
- [ ] Arsip (dropdown)
- [ ] Laporan

**Untuk Viewer:**
- [ ] Dashboard
- [ ] Daftar Arsip (view only)
- [ ] Laporan

### 6. Design & Colors
- [ ] Sidebar background: **#008e3c** (hijau tua)
- [ ] Logo circular badge: **#efd856** (kuning)
- [ ] Menu icons: **#efd856** (kuning)
- [ ] Active menu: **#006b2d** (hijau dark) dengan shadow
- [ ] Hover effects bekerja
- [ ] User info di bottom dengan border kuning
- [ ] Cards dengan rounded corners
- [ ] Hover animation pada cards (scale)

---

## 🐛 Troubleshooting

### Error: "View not found"
```bash
php artisan view:clear
php artisan cache:clear
```

### Error: "Route not found"
```bash
php artisan route:clear
php artisan route:cache
```

### Error: "Class not found"
```bash
composer dump-autoload
php artisan config:clear
```

### CSS tidak muncul
```bash
npm run build
php artisan cache:clear
```

### Database belum ada data
```bash
php artisan db:seed --class=InitialDataSeeder
```

---

## 📋 Quick Commands

```bash
# Check routes
php artisan route:list

# Check routes untuk specific path
php artisan route:list --path=arsip
php artisan route:list --path=laporan

# Clear all cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

# Rebuild assets
npm run build

# Fresh database (WARNING: akan hapus semua data)
php artisan migrate:fresh --seed --seeder=InitialDataSeeder
```

---

## ✅ Expected Results

### Setelah Perbaikan:

1. **Daftar Arsip (✅ WORKING)**
   - URL accessible: http://localhost:8000/arsip
   - Filter form lengkap
   - Table dengan data
   - Pagination berfungsi

2. **Laporan (✅ WORKING)**
   - URL accessible: http://localhost:8000/laporan
   - 4 cards tampil dengan baik
   - Form input tanggal
   - Button submit berfungsi

3. **Statistik (✅ WORKING)**
   - URL accessible: http://localhost:8000/laporan/statistik
   - Stats cards tampil
   - Charts dengan progress bars
   - Warna custom (#008e3c & #efd856)

4. **Navigation (✅ WORKING)**
   - Sidebar menu tampil
   - Routing ke semua page berfungsi
   - Middleware role berfungsi

---

## 🎯 Test Scenarios

### Scenario 1: Admin Full Access
1. Login sebagai admin
2. Akses semua menu (Dashboard, Arsip, Master Data, Users, Laporan)
3. Test CRUD arsip
4. Test laporan
5. Logout

### Scenario 2: Operator Limited Access
1. Login sebagai operator
2. Akses Dashboard, Arsip, Laporan
3. Tidak bisa akses Master Data & Users
4. Test filter & search arsip
5. Logout

### Scenario 3: Viewer Read Only
1. Login sebagai viewer
2. Akses Dashboard, View Arsip, Laporan
3. Tidak ada tombol Create/Edit/Delete
4. Download arsip berfungsi
5. Logout

---

## 📝 Notes

- Semua view sudah dibuat dengan warna custom
- Filter & search fully functional
- Responsive design
- Icons menggunakan Font Awesome
- Middleware sudah registered di bootstrap/app.php

**Status**: ✅ READY TO TEST
