<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\KategoriArsip;
use App\Models\UnitKerja;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_arsip' => Arsip::count(),
            'arsip_bulan_ini' => Arsip::whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->count(),
            'total_kategori' => KategoriArsip::where('is_active', true)->count(),
            'total_unit' => UnitKerja::where('is_active', true)->count(),
            'total_users' => User::where('is_active', true)->count(),
        ];
        
        // Arsip terbaru
        $arsipTerbaru = Arsip::with(['kategori', 'unitKerja', 'creator'])
            ->latest()
            ->limit(10)
            ->get();
        
        // Statistik per jenis arsip
        $arsipPerJenis = Arsip::select('jenis_arsip', DB::raw('count(*) as total'))
            ->groupBy('jenis_arsip')
            ->get();
        
        // Statistik per bulan (6 bulan terakhir)
        $arsipPerBulan = Arsip::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as bulan'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
        
        // Aktivitas terbaru
        $aktivitasTerbaru = LogAktivitas::with('user')
            ->latest()
            ->limit(15)
            ->get();
        
        // Top unit kerja
        $topUnitKerja = UnitKerja::withCount('arsip')
            ->orderByDesc('arsip_count')
            ->limit(5)
            ->get();
        
        return view('dashboard', compact(
            'stats',
            'arsipTerbaru',
            'arsipPerJenis',
            'arsipPerBulan',
            'aktivitasTerbaru',
            'topUnitKerja'
        ));
    }
}
