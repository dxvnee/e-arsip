<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Disposisi;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function arsipMasukKeluar(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $arsipMasuk = Arsip::where('jenis_arsip', 'surat_masuk')
            ->whereBetween('tanggal_surat', [$request->tanggal_mulai, $request->tanggal_akhir])
            ->with(['kategori', 'unitKerja'])
            ->get();

        $arsipKeluar = Arsip::where('jenis_arsip', 'surat_keluar')
            ->whereBetween('tanggal_surat', [$request->tanggal_mulai, $request->tanggal_akhir])
            ->with(['kategori', 'unitKerja'])
            ->get();

        return view('laporan.arsip-masuk-keluar', compact('arsipMasuk', 'arsipKeluar', 'request'));
    }

    public function statistikArsip()
    {
        $stats = [
            'total' => Arsip::count(),
            'aktif' => Arsip::where('status', 'aktif')->count(),
            'inaktif' => Arsip::where('status', 'inaktif')->count(),
            'musnah' => Arsip::where('status', 'musnah')->count(),
        ];

        $perJenis = Arsip::select('jenis_arsip', DB::raw('count(*) as total'))
            ->groupBy('jenis_arsip')
            ->get();

        $perKategori = Arsip::select('kategori_arsip.nama_kategori', DB::raw('count(arsip.id) as total'))
            ->join('kategori_arsip', 'arsip.kategori_id', '=', 'kategori_arsip.id')
            ->groupBy('kategori_arsip.id', 'kategori_arsip.nama_kategori')
            ->get();

        $perUnitKerja = Arsip::select('unit_kerja.nama_unit', DB::raw('count(arsip.id) as total'))
            ->join('unit_kerja', 'arsip.unit_kerja_id', '=', 'unit_kerja.id')
            ->groupBy('unit_kerja.id', 'unit_kerja.nama_unit')
            ->get();

        return view('laporan.statistik', compact('stats', 'perJenis', 'perKategori', 'perUnitKerja'));
    }

    public function aktivitas(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $logs = LogAktivitas::with('user')
            ->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_akhir])
            ->latest()
            ->get();

        return view('laporan.aktivitas', compact('logs', 'request'));
    }
}
