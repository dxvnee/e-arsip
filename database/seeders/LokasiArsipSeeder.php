<?php

namespace Database\Seeders;

use App\Models\LokasiArsip;
use Illuminate\Database\Seeder;

class LokasiArsipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokasiData = [
            // Gedung A - Ruang Arsip Aktif
            [
                'kode_lokasi' => 'LOK-001',
                'gedung' => 'Gedung A',
                'ruang' => 'Ruang Arsip Aktif',
                'rak' => 'R1',
                'boks' => 'B1',
                'kapasitas' => 50,
                'keterangan' => 'Lokasi penyimpanan arsip aktif bidang kepegawaian',
                'is_active' => true,
            ],
            [
                'kode_lokasi' => 'LOK-002',
                'gedung' => 'Gedung A',
                'ruang' => 'Ruang Arsip Aktif',
                'rak' => 'R1',
                'boks' => 'B2',
                'kapasitas' => 50,
                'keterangan' => null,
                'is_active' => true,
            ],
            [
                'kode_lokasi' => 'LOK-003',
                'gedung' => 'Gedung A',
                'ruang' => 'Ruang Arsip Aktif',
                'rak' => 'R2',
                'boks' => 'B1',
                'kapasitas' => 50,
                'keterangan' => 'Lokasi penyimpanan surat masuk',
                'is_active' => true,
            ],
            [
                'kode_lokasi' => 'LOK-004',
                'gedung' => 'Gedung A',
                'ruang' => 'Ruang Arsip Aktif',
                'rak' => 'R2',
                'boks' => 'B2',
                'kapasitas' => 50,
                'keterangan' => 'Lokasi penyimpanan surat keluar',
                'is_active' => true,
            ],

            // Gedung A - Ruang Arsip Inaktif
            [
                'kode_lokasi' => 'LOK-005',
                'gedung' => 'Gedung A',
                'ruang' => 'Ruang Arsip Inaktif',
                'rak' => 'R1',
                'boks' => 'B1',
                'kapasitas' => 100,
                'keterangan' => 'Arsip inaktif tahun 2020-2022',
                'is_active' => true,
            ],
            [
                'kode_lokasi' => 'LOK-006',
                'gedung' => 'Gedung A',
                'ruang' => 'Ruang Arsip Inaktif',
                'rak' => 'R1',
                'boks' => 'B2',
                'kapasitas' => 100,
                'keterangan' => 'Arsip inaktif tahun 2020-2022',
                'is_active' => true,
            ],

            // Gedung B - Ruang Dokumen
            [
                'kode_lokasi' => 'LOK-007',
                'gedung' => 'Gedung B',
                'ruang' => 'Ruang Dokumen',
                'rak' => 'A1',
                'boks' => '001',
                'kapasitas' => 75,
                'keterangan' => 'Dokumen program kesehatan',
                'is_active' => true,
            ],
            [
                'kode_lokasi' => 'LOK-008',
                'gedung' => 'Gedung B',
                'ruang' => 'Ruang Dokumen',
                'rak' => 'A1',
                'boks' => '002',
                'kapasitas' => 75,
                'keterangan' => null,
                'is_active' => true,
            ],
            [
                'kode_lokasi' => 'LOK-009',
                'gedung' => 'Gedung B',
                'ruang' => 'Ruang Dokumen',
                'rak' => 'A2',
                'boks' => '001',
                'kapasitas' => 75,
                'keterangan' => 'Dokumen keuangan',
                'is_active' => true,
            ],

            // Gedung B - Ruang Penyimpanan Khusus
            [
                'kode_lokasi' => 'LOK-010',
                'gedung' => 'Gedung B',
                'ruang' => 'Ruang Penyimpanan Khusus',
                'rak' => 'K1',
                'boks' => 'K-001',
                'kapasitas' => 25,
                'keterangan' => 'Dokumen rahasia dan vital',
                'is_active' => true,
            ],

            // Lokasi nonaktif (contoh)
            [
                'kode_lokasi' => 'LOK-011',
                'gedung' => 'Gedung C',
                'ruang' => 'Gudang Lama',
                'rak' => 'X1',
                'boks' => 'X001',
                'kapasitas' => 200,
                'keterangan' => 'Lokasi lama - sudah tidak digunakan',
                'is_active' => false,
            ],
        ];

        foreach ($lokasiData as $lokasi) {
            LokasiArsip::create($lokasi);
        }
    }
}
