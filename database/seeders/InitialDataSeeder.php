<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\KategoriArsip;
use App\Models\Arsip;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Unit Kerja
        $units = [
            [
                'kode_unit' => 'UK001',
                'nama_unit' => 'Sekretariat',
                'keterangan' => 'Sekretariat Dinas Kesehatan',
                'kepala_unit' => 'Dr. Ahmad Suryadi',
                'nip_kepala' => '197501012000121001',
                'email' => 'sekretariat@dinkes.go.id',
                'phone' => '0211234567',
                'alamat' => 'Jl. Kesehatan No. 1',
                'is_active' => true,
            ],
            [
                'kode_unit' => 'UK002',
                'nama_unit' => 'Bidang Pelayanan Kesehatan',
                'keterangan' => 'Bidang yang menangani pelayanan kesehatan masyarakat',
                'kepala_unit' => 'dr. Siti Nurhaliza',
                'nip_kepala' => '198001012005122001',
                'email' => 'yankes@dinkes.go.id',
                'phone' => '0211234568',
                'alamat' => 'Jl. Kesehatan No. 1',
                'is_active' => true,
            ],
            [
                'kode_unit' => 'UK003',
                'nama_unit' => 'Bidang Pencegahan dan Pengendalian Penyakit',
                'keterangan' => 'Bidang P2P',
                'kepala_unit' => 'dr. Budi Santoso',
                'nip_kepala' => '197801012003121001',
                'email' => 'p2p@dinkes.go.id',
                'phone' => '0211234569',
                'alamat' => 'Jl. Kesehatan No. 1',
                'is_active' => true,
            ],
        ];

        foreach ($units as $unit) {
            UnitKerja::create($unit);
        }

        // Create Kategori Arsip
        $kategoris = [
            [
                'kode_kategori' => 'KAT001',
                'nama_kategori' => 'Surat Keputusan',
                'deskripsi' => 'Surat Keputusan dari Kepala Dinas',
                'masa_retensi' => 10,
                'tingkat_keamanan' => 'rahasia',
                'warna_label' => '#ff0000',
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'KAT002',
                'nama_kategori' => 'Surat Edaran',
                'deskripsi' => 'Surat Edaran Internal dan Eksternal',
                'masa_retensi' => 5,
                'tingkat_keamanan' => 'internal',
                'warna_label' => '#00ff00',
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'KAT003',
                'nama_kategori' => 'Laporan',
                'deskripsi' => 'Laporan Kegiatan dan Keuangan',
                'masa_retensi' => 7,
                'tingkat_keamanan' => 'internal',
                'warna_label' => '#0000ff',
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'KAT004',
                'nama_kategori' => 'Surat Masuk',
                'deskripsi' => 'Surat Masuk dari Instansi Lain',
                'masa_retensi' => 5,
                'tingkat_keamanan' => 'publik',
                'warna_label' => '#ffff00',
                'is_active' => true,
            ],
            [
                'kode_kategori' => 'KAT005',
                'nama_kategori' => 'Surat Keluar',
                'deskripsi' => 'Surat Keluar ke Instansi Lain',
                'masa_retensi' => 5,
                'tingkat_keamanan' => 'publik',
                'warna_label' => '#ff00ff',
                'is_active' => true,
            ],
        ];

        foreach ($kategoris as $kategori) {
            KategoriArsip::create($kategori);
        }

        // Create Users
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@dinkes.go.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'nip' => '199001012015011001',
                'jabatan' => 'Administrator Sistem',
                'unit_kerja_id' => 1,
                'phone' => '08123456789',
                'address' => 'Jl. Admin No. 1',
                'is_active' => true,
            ],
            [
                'name' => 'Operator Arsip',
                'email' => 'operator@dinkes.go.id',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'nip' => '199201012016011001',
                'jabatan' => 'Staff Arsip',
                'unit_kerja_id' => 1,
                'phone' => '08123456790',
                'address' => 'Jl. Operator No. 2',
                'is_active' => true,
            ],
            [
                'name' => 'Viewer User',
                'email' => 'viewer@dinkes.go.id',
                'password' => Hash::make('password'),
                'role' => 'viewer',
                'nip' => '199301012017011001',
                'jabatan' => 'Staff Umum',
                'unit_kerja_id' => 2,
                'phone' => '08123456791',
                'address' => 'Jl. Viewer No. 3',
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // Create Sample Arsip
        $arsips = [
            [
                'nomor_surat' => '001/SK/DINKES/2024',
                'judul_arsip' => 'SK Pembentukan Tim Penanganan COVID-19',
                'deskripsi' => 'Surat Keputusan tentang pembentukan tim khusus penanganan COVID-19',
                'kategori_id' => 1,
                'unit_kerja_id' => 1,
                'jenis_arsip' => 'surat_keluar',
                'tanggal_surat' => '2024-01-15',
                'tanggal_diterima' => '2024-01-16',
                'pengirim' => 'Kepala Dinas Kesehatan',
                'penerima' => 'Seluruh Unit Kerja',
                'perihal' => 'Pembentukan Tim',
                'isi_ringkas' => 'Membentuk tim khusus untuk penanganan pandemi COVID-19 di wilayah kerja Dinas Kesehatan',
                'lokasi_fisik' => 'Rak A1-01',
                'status' => 'aktif',
                'created_by' => 2,
                'tags' => 'covid,tim,sk',
            ],
            [
                'nomor_surat' => '002/SE/DINKES/2024',
                'judul_arsip' => 'Surat Edaran Protokol Kesehatan',
                'deskripsi' => 'Surat edaran tentang penerapan protokol kesehatan di lingkungan kerja',
                'kategori_id' => 2,
                'unit_kerja_id' => 3,
                'jenis_arsip' => 'surat_keluar',
                'tanggal_surat' => '2024-02-01',
                'tanggal_diterima' => '2024-02-02',
                'pengirim' => 'Bidang P2P',
                'penerima' => 'Seluruh Puskesmas',
                'perihal' => 'Protokol Kesehatan',
                'isi_ringkas' => 'Himbauan untuk menerapkan protokol kesehatan ketat di semua fasilitas kesehatan',
                'lokasi_fisik' => 'Rak A1-02',
                'status' => 'aktif',
                'created_by' => 2,
                'tags' => 'protokol,kesehatan,edaran',
            ],
            [
                'nomor_surat' => '003/LAP/DINKES/2024',
                'judul_arsip' => 'Laporan Kegiatan Triwulan I 2024',
                'deskripsi' => 'Laporan pelaksanaan kegiatan Dinas Kesehatan periode Januari-Maret 2024',
                'kategori_id' => 3,
                'unit_kerja_id' => 1,
                'jenis_arsip' => 'laporan',
                'tanggal_surat' => '2024-04-05',
                'tanggal_diterima' => '2024-04-05',
                'pengirim' => 'Sekretariat',
                'penerima' => 'Kepala Dinas',
                'perihal' => 'Laporan Triwulan',
                'isi_ringkas' => 'Laporan lengkap kegiatan dan capaian kinerja triwulan I tahun 2024',
                'lokasi_fisik' => 'Rak B1-01',
                'status' => 'aktif',
                'created_by' => 2,
                'tags' => 'laporan,triwulan,kegiatan',
            ],
        ];

        foreach ($arsips as $arsip) {
            Arsip::create($arsip);
        }

        $this->command->info('Initial data seeded successfully!');
        $this->command->info('Admin login: admin@dinkes.go.id / password');
        $this->command->info('Operator login: operator@dinkes.go.id / password');
        $this->command->info('Viewer login: viewer@dinkes.go.id / password');
    }
}
