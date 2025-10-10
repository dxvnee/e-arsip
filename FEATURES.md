# 📋 Dokumentasi Lengkap Fitur E-Arsip Dinas Kesehatan

## 1. 📁 MANAJEMEN ARSIP

### Fitur Input Arsip
- **Jenis Arsip**: Surat Masuk, Surat Keluar, Dokumen Internal, Laporan, Peraturan, Lainnya
- **Upload File**: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG (Max 10MB)
- **Metadata Lengkap**:
  - Nomor Arsip (Auto-generate: ARS/YYYY/MM/0001)
  - Nomor Surat
  - Judul Arsip
  - Deskripsi
  - Kategori Arsip
  - Unit Kerja
  - Tanggal Surat
  - Tanggal Diterima
  - Pengirim
  - Penerima
  - Perihal
  - Isi Ringkas
  - Lokasi Fisik
  - Tags (comma-separated)

### Klasifikasi & Status
- **Status Arsip**:
  - Aktif: Arsip yang masih digunakan
  - Inaktif: Arsip yang sudah jarang diakses
  - Musnah: Arsip yang sudah dimusnahkan
  
- **Masa Retensi**: Otomatis dihitung berdasarkan kategori arsip
- **Tingkat Keamanan**: Publik, Internal, Rahasia, Sangat Rahasia

### Update & Versioning
```
Database: arsip_versions
- Menyimpan setiap perubahan dokumen
- Version numbering otomatis
- Change notes untuk tracking
- Metadata changes (JSON)
- Old file preservation
```

**Cara Kerja**:
1. Saat update arsip dengan file baru, sistem create version baru
2. File lama disimpan di arsip_versions
3. Metadata perubahan dicatat
4. History dapat dilihat kapan saja

### Tracking & Analytics
- **View Count**: Jumlah arsip dibuka
- **Download Count**: Jumlah file diunduh
- **Last Updated By**: Siapa yang terakhir update
- **Created By**: Siapa yang buat arsip

---

## 2. 🔍 PENCARIAN & PENELUSURAN ARSIP

### Pencarian Cepat
```php
// Controller: ArsipController@index
// Method: GET /arsip
```

**Parameter Search**:
- `search`: Kata kunci (judul, nomor surat, deskripsi, perihal, tags)
- `kategori`: Filter by kategori_id
- `unit_kerja`: Filter by unit_kerja_id
- `jenis`: Filter by jenis_arsip
- `date_from`: Tanggal mulai
- `date_to`: Tanggal akhir
- `status`: aktif/inaktif/musnah

### Full-Text Search
```sql
-- Di migration sudah ditambahkan:
$table->fullText(['judul_arsip', 'deskripsi', 'isi_ringkas', 'tags']);
```

### Hasil Terstruktur
- Pagination 15 items per page
- Sorting by latest
- Eager loading relations (kategori, unitKerja, creator)
- Query string preserved saat pagination

---

## 3. 📋 DISPOSISI DIGITAL

### Database Schema
```
Table: disposisi
- arsip_id (FK)
- dari_user_id (FK)
- kepada_user_id (FK)
- isi_disposisi (text)
- prioritas (enum: biasa, segera, sangat_segera)
- sifat (enum: biasa, rahasia, penting)
- catatan (text, nullable)
- status (enum: baru, dibaca, diproses, selesai)
- dibaca_pada (timestamp, nullable)
- diproses_pada (timestamp, nullable)
- selesai_pada (timestamp, nullable)
- tindak_lanjut (text, nullable)
```

### Flow Disposisi
1. **Buat Disposisi** (Admin/Operator)
   - Pilih arsip
   - Pilih penerima
   - Isi disposisi & prioritas
   - Submit → Status: BARU

2. **Terima Disposisi** (Penerima)
   - Notifikasi disposisi baru
   - Buka disposisi → Auto mark: DIBACA
   - Timestamp `dibaca_pada` tercatat

3. **Proses Disposisi** (Penerima)
   - Update status → DIPROSES
   - Timestamp `diproses_pada` tercatat
   - Dapat tambah catatan

4. **Selesaikan Disposisi** (Penerima)
   - Update status → SELESAI
   - Wajib isi tindak lanjut
   - Timestamp `selesai_pada` tercatat

### Routes
```php
GET    /disposisi              // List disposisi masuk
POST   /disposisi              // Buat disposisi baru
GET    /disposisi/create       // Form buat disposisi
GET    /disposisi/{id}         // Detail disposisi
POST   /disposisi/{id}/update-status  // Update status
```

---

## 4. 👥 MANAJEMEN PENGGUNA & HAK AKSES

### Roles & Permissions

#### **Admin**
✅ Dashboard full access
✅ Manage Arsip (CRUD)
✅ Buat Disposisi
✅ Manage Kategori Arsip
✅ Manage Unit Kerja
✅ Manage Users
✅ Lihat semua laporan
✅ Access log aktivitas

#### **Operator**
✅ Dashboard access
✅ Manage Arsip (CRUD)
✅ Buat Disposisi
✅ Lihat laporan
❌ Manage master data
❌ Manage users

#### **Petugas**
✅ Dashboard access
✅ Manage Arsip (CRUD)
✅ Terima Disposisi
✅ Lihat laporan
❌ Buat disposisi
❌ Manage master data

#### **Viewer**
✅ Dashboard access
✅ View Arsip (Read only)
✅ Download Arsip
✅ Lihat laporan
❌ Create/Update/Delete
❌ Disposisi

### Middleware Implementation
```php
// routes/web.php

// Admin only
Route::middleware(['role:admin'])->group(function () {
    Route::resource('kategori', KategoriArsipController::class);
    Route::resource('unit-kerja', UnitKerjaController::class);
    Route::resource('users', UserController::class);
});

// Admin & Operator
Route::middleware(['role:admin,operator'])->group(function () {
    Route::post('disposisi', [DisposisiController::class, 'store']);
});
```

---

## 5. 🔐 KEAMANAN & AUDIT

### Autentikasi
- Laravel Breeze implementation
- Password hashing (bcrypt)
- Remember me token
- Email verification (optional)

### Hak Akses
```php
// Middleware: CheckRole
- Validasi role user
- Abort 403 jika tidak berhak

// Middleware: IsActive  
- Check is_active status
- Logout jika user dinonaktifkan
```

### Log Aktivitas

**Table: log_aktivitas**
```
- user_id
- aksi (create, read, update, delete, download, login, logout)
- model_type (polymorphic)
- model_id
- deskripsi
- data_lama (JSON)
- data_baru (JSON)
- ip_address
- user_agent
- timestamps
```

**Usage**:
```php
use App\Models\LogAktivitas;

// Log any activity
LogAktivitas::log('create', 'Membuat arsip baru', $arsip);
LogAktivitas::log('update', 'Mengubah status', $disposisi, $oldData, $newData);
LogAktivitas::log('download', 'Download file', $arsip);
```

### Security Features
- ✅ CSRF Token pada semua form
- ✅ XSS Protection (Blade escaping)
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ File Upload Validation
- ✅ File Type Restriction
- ✅ File Size Limit (10MB)
- ✅ Secure file storage (storage/app/public)

---

## 6. 📊 LAPORAN & STATISTIK

### A. Laporan Arsip Masuk/Keluar
**Route**: `POST /laporan/arsip-masuk-keluar`

**Parameter**:
- tanggal_mulai (required)
- tanggal_akhir (required)

**Output**:
- List surat masuk dalam periode
- List surat keluar dalam periode
- Total per kategori
- Grafik trends

### B. Statistik Arsip
**Route**: `GET /laporan/statistik`

**Data**:
```php
[
    'total' => Total seluruh arsip,
    'aktif' => Arsip status aktif,
    'inaktif' => Arsip status inaktif,
    'musnah' => Arsip status musnah,
    'perJenis' => Breakdown per jenis,
    'perKategori' => Breakdown per kategori,
    'perUnitKerja' => Breakdown per unit kerja,
]
```

### C. Laporan Aktivitas
**Route**: `POST /laporan/aktivitas`

**Parameter**:
- tanggal_mulai (required)
- tanggal_akhir (required)

**Output**:
- List aktivitas user dalam periode
- Breakdown per aksi (create, update, delete, etc)
- Top users by activity

### D. Laporan Disposisi
**Route**: `POST /laporan/disposisi`

**Parameter**:
- tanggal_mulai (required)
- tanggal_akhir (required)

**Output**:
- List disposisi dalam periode
- Status breakdown (baru, dibaca, diproses, selesai)
- Average resolution time
- Top senders & receivers

### Dashboard Analytics
- Real-time statistics cards
- Chart arsip per jenis
- 10 arsip terbaru
- 15 aktivitas terbaru
- Top 5 unit kerja by arsip count

---

## 7. 🎨 USER INTERFACE & DESIGN

### Color Scheme
```css
Primary: #008e3c (Hijau Tua)
Primary Dark: #006b2d
Secondary: #efd856 (Kuning Lembut)
Secondary Dark: #d4b93a
Background: #FFFFFF (Putih)
```

### Components
- Sidebar dengan logo custom
- Navigation menu dengan icons
- Statistics cards dengan hover animation
- Tables dengan sorting & pagination
- Forms dengan validation feedback
- Modal dialogs
- Alert notifications
- Progress bars
- Badge status

### Responsive Design
- Mobile-first approach
- Breakpoints: sm, md, lg, xl
- Hamburger menu untuk mobile
- Touch-friendly buttons
- Optimized untuk tablet

---

## 🔧 TECHNICAL STACK

**Backend**:
- Laravel 12.x
- PHP 8.2+
- MySQL 8.0+
- Eloquent ORM

**Frontend**:
- Blade Templates
- Tailwind CSS 3.x
- Alpine.js
- Font Awesome 6

**Security**:
- Laravel Breeze (Auth)
- CSRF Protection
- XSS Protection  
- SQL Injection Protection

**Features**:
- File Upload & Storage
- Full-Text Search
- Polymorphic Relations
- JSON Fields
- Soft Deletes (optional)
- Activity Logging

---

## 📝 DATABASE STRUCTURE

### Core Tables
1. **users** - User accounts
2. **unit_kerja** - Organization units
3. **kategori_arsip** - Archive categories
4. **arsip** - Main archive data
5. **arsip_versions** - Version history
6. **disposisi** - Digital disposition
7. **log_aktivitas** - Activity logs
8. **cache**, **jobs**, **sessions** - Laravel system tables

### Relations
```
users 
  - belongsTo UnitKerja
  - hasMany Arsip (creator)
  - hasMany Disposisi (sender/receiver)
  - hasMany LogAktivitas

arsip
  - belongsTo KategoriArsip
  - belongsTo UnitKerja
  - belongsTo User (creator)
  - hasMany ArsipVersion
  - hasMany Disposisi

disposisi
  - belongsTo Arsip
  - belongsTo User (dari/kepada)
```

---

## ✅ CHECKLIST IMPLEMENTASI

### ✅ Sudah Selesai
- [x] Setup Laravel & Database
- [x] Authentication System
- [x] Role-based Access Control
- [x] Manajemen Arsip (CRUD)
- [x] Upload & Download File
- [x] Pencarian & Filter
- [x] Disposisi Digital
- [x] Versioning System
- [x] Log Aktivitas
- [x] Dashboard Analytics
- [x] Laporan & Statistik
- [x] UI/UX Design
- [x] Responsive Layout

### 📌 Catatan Developer

**File Structure**:
```
app/
├── Http/Controllers/
│   ├── ArsipController.php
│   ├── DisposisiController.php
│   ├── DashboardController.php
│   ├── LaporanController.php
│   └── ...
├── Models/
│   ├── Arsip.php
│   ├── Disposisi.php
│   ├── ArsipVersion.php
│   ├── LogAktivitas.php
│   └── ...
└── Http/Middleware/
    ├── CheckRole.php
    └── IsActive.php

database/migrations/
├── *_create_arsip_table.php
├── *_create_disposisi_table.php
├── *_create_arsip_versions_table.php
└── ...

resources/views/
├── layouts/app.blade.php
├── dashboard.blade.php
├── arsip/
├── disposisi/
└── laporan/
```

---

**Sistem E-Arsip Dinas Kesehatan** - Dokumentasi Lengkap v1.0
