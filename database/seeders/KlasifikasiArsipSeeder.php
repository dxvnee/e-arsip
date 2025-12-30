<?php

namespace Database\Seeders;

use App\Models\KlasifikasiArsip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KlasifikasiArsipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $klasifikasi = [
            [
                'kode_klasifikasi' => '443.1',
                'nama_klasifikasi' => 'Program Kesehatan Masyarakat',
                'deskripsi' => 'Arsip terkait program-program kesehatan masyarakat seperti imunisasi, promosi kesehatan, dan program kesehatan ibu dan anak',
                'retensi_aktif' => 2,
                'retensi_inaktif' => 8,
                'nasib_akhir' => 'musnah',
                'is_active' => true,
            ],
            [
                'kode_klasifikasi' => '421.2',
                'nama_klasifikasi' => 'Kepegawaian - Mutasi dan Promosi',
                'deskripsi' => 'Arsip terkait mutasi, promosi, dan penempatan pegawai di lingkungan Dinas Kesehatan',
                'retensi_aktif' => 5,
                'retensi_inaktif' => 10,
                'nasib_akhir' => 'permanen',
                'is_active' => true,
            ],
            [
                'kode_klasifikasi' => '024.1',
                'nama_klasifikasi' => 'Laporan Keuangan',
                'deskripsi' => 'Arsip laporan keuangan tahunan, bulanan, dan realisasi anggaran',
                'retensi_aktif' => 5,
                'retensi_inaktif' => 10,
                'nasib_akhir' => 'permanen',
                'is_active' => true,
            ],
            [
                'kode_klasifikasi' => '010.1',
                'nama_klasifikasi' => 'Peraturan dan Kebijakan',
                'deskripsi' => 'Arsip peraturan daerah, keputusan kepala dinas, dan kebijakan terkait kesehatan',
                'retensi_aktif' => 5,
                'retensi_inaktif' => 0,
                'nasib_akhir' => 'permanen',
                'is_active' => true,
            ],
            [
                'kode_klasifikasi' => '025.3',
                'nama_klasifikasi' => 'Surat Menyurat Biasa',
                'deskripsi' => 'Arsip surat menyurat rutin yang tidak memerlukan tindak lanjut khusus',
                'retensi_aktif' => 1,
                'retensi_inaktif' => 2,
                'nasib_akhir' => 'musnah',
                'is_active' => true,
            ],
            [
                'kode_klasifikasi' => '443.2',
                'nama_klasifikasi' => 'Pelayanan Kesehatan Dasar',
                'deskripsi' => 'Arsip terkait pelayanan kesehatan dasar di puskesmas dan jaringannya',
                'retensi_aktif' => 3,
                'retensi_inaktif' => 7,
                'nasib_akhir' => 'musnah',
                'is_active' => true,
            ],
            [
                'kode_klasifikasi' => '443.3',
                'nama_klasifikasi' => 'Pengendalian Penyakit',
                'deskripsi' => 'Arsip terkait surveilans, pengendalian penyakit menular dan tidak menular',
                'retensi_aktif' => 2,
                'retensi_inaktif' => 8,
                'nasib_akhir' => 'permanen',
                'is_active' => true,
            ],
            [
                'kode_klasifikasi' => '421.1',
                'nama_klasifikasi' => 'Kepegawaian - Data Pegawai',
                'deskripsi' => 'Arsip data pokok pegawai, SK pengangkatan, dan berkas kepegawaian',
                'retensi_aktif' => 5,
                'retensi_inaktif' => 25,
                'nasib_akhir' => 'permanen',
                'is_active' => true,
            ],
            [
                'kode_klasifikasi' => '030.2',
                'nama_klasifikasi' => 'Rencana Kerja dan Anggaran',
                'deskripsi' => 'Arsip dokumen perencanaan seperti RKA, DPA, dan rencana strategis',
                'retensi_aktif' => 5,
                'retensi_inaktif' => 5,
                'nasib_akhir' => 'musnah',
                'is_active' => true,
            ],
            [
                'kode_klasifikasi' => '443.4',
                'nama_klasifikasi' => 'Farmasi dan Alat Kesehatan',
                'deskripsi' => 'Arsip terkait pengelolaan obat, vaksin, dan alat kesehatan',
                'retensi_aktif' => 3,
                'retensi_inaktif' => 7,
                'nasib_akhir' => 'musnah',
                'is_active' => true,
            ],
        ];

        foreach ($klasifikasi as $item) {
            KlasifikasiArsip::create($item);
        }
    }
}
