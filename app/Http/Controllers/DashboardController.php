<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\ArsipFile;
use App\Models\BerkasArsip;
use App\Models\ItemArsip;
use App\Models\KlasifikasiArsip;
use App\Models\LokasiArsip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        // ==================== STATISTIK UTAMA ====================
        $totalBerkas = BerkasArsip::count();
        $totalItem = ItemArsip::count();
        $totalFile = ArsipFile::count();
        $totalKlasifikasi = KlasifikasiArsip::where('is_active', true)->count();
        $totalLokasi = LokasiArsip::where('is_active', true)->count();
        $totalUser = User::where('is_active', true)->count();

        // ==================== STATISTIK STATUS ARSIP ====================
        $berkasAktif = BerkasArsip::where('status_arsip', 'Aktif')->count();
        $berkasInaktif = BerkasArsip::where('status_arsip', 'Inaktif')->count();
        $berkasPermanen = BerkasArsip::where('status_arsip', 'Permanen')->count();

        $statusData = [
            'labels' => ['Aktif', 'Inaktif', 'Permanen'],
            'data' => [$berkasAktif, $berkasInaktif, $berkasPermanen],
            'colors' => ['#22c55e', '#eab308', '#3b82f6'],
        ];

        // ==================== STATISTIK PER TAHUN (5 tahun terakhir) ====================
        $currentYear = Carbon::now()->year;
        $years = [];
        $berkasPerTahun = [];
        $itemPerTahun = [];

        for ($i = 4; $i >= 0; $i--) {
            $year = $currentYear - $i;
            $years[] = $year;
            $berkasPerTahun[] = BerkasArsip::where('tahun', $year)->count();
            $itemPerTahun[] = ItemArsip::whereHas('berkasArsip', function ($q) use ($year) {
                $q->where('tahun', $year);
            })->count();
        }

        $yearlyData = [
            'labels' => $years,
            'berkas' => $berkasPerTahun,
            'items' => $itemPerTahun,
        ];

        // ==================== STATISTIK PER KLASIFIKASI ====================
        $klasifikasiStats = KlasifikasiArsip::withCount('berkasArsip')
            ->where('is_active', true)
            ->orderByDesc('berkas_arsip_count')
            ->take(8)
            ->get()
            ->map(function ($item) {
                return [
                    'kode' => $item->kode_klasifikasi,
                    'nama' => $item->nama_klasifikasi,
                    'count' => $item->berkas_arsip_count,
                ];
            });

        // ==================== STATISTIK PER UNIT KERJA ====================
        $unitKerjaStats = BerkasArsip::select('unit_kerja', DB::raw('count(*) as total'))
            ->whereNotNull('unit_kerja')
            ->groupBy('unit_kerja')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        // ==================== KONDISI FISIK ITEM ====================
        $kondisiBaik = ItemArsip::where('kondisi_fisik', 'Baik')->count();
        $kondisiRusak = ItemArsip::where('kondisi_fisik', 'Rusak Ringan')
            ->orWhere('kondisi_fisik', 'Rusak Sedang')
            ->orWhere('kondisi_fisik', 'Rusak Berat')->count();

        // ==================== JENIS FISIK ITEM ====================
        $jenisFisikStats = ItemArsip::select('jenis_fisik', DB::raw('count(*) as total'))
            ->whereNotNull('jenis_fisik')
            ->groupBy('jenis_fisik')
            ->orderByDesc('total')
            ->get();

        // ==================== TINGKAT PERKEMBANGAN ====================
        $tingkatStats = ItemArsip::select('tingkat_perkembangan', DB::raw('count(*) as total'))
            ->whereNotNull('tingkat_perkembangan')
            ->groupBy('tingkat_perkembangan')
            ->orderByDesc('total')
            ->get();

        // ==================== STORAGE INFO ====================
        $totalStorageBytes = ArsipFile::sum('ukuran');
        $totalStorageFormatted = $this->formatBytes($totalStorageBytes);
        
        $fileTypes = ArsipFile::select('tipe_file', DB::raw('count(*) as total'), DB::raw('sum(ukuran) as total_size'))
            ->groupBy('tipe_file')
            ->get()
            ->map(function ($item) {
                $item->formatted_size = $this->formatBytes($item->total_size ?? 0);
                return $item;
            });

        // ==================== BERKAS TERBARU ====================
        $berkasLatest = BerkasArsip::with(['klasifikasiArsip', 'lokasiArsip'])
            ->latest()
            ->take(5)
            ->get();

        // ==================== ITEM TERBARU ====================
        $itemLatest = ItemArsip::with('berkasArsip')
            ->latest()
            ->take(5)
            ->get();

        // ==================== AKTIVITAS HARIAN (7 hari terakhir) ====================
        $dailyActivity = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyActivity[] = [
                'date' => $date->format('d M'),
                'berkas' => BerkasArsip::whereDate('created_at', $date)->count(),
                'items' => ItemArsip::whereDate('created_at', $date)->count(),
                'files' => ArsipFile::whereDate('created_at', $date)->count(),
            ];
        }

        // ==================== LOKASI ARSIP USAGE ====================
        $lokasiUsage = LokasiArsip::withCount('berkasArsip')
            ->where('is_active', true)
            ->orderByDesc('berkas_arsip_count')
            ->take(5)
            ->get();

        // ==================== ARSIP BUTUH PERHATIAN ====================
        // Item dengan kondisi rusak
        $itemRusakBerat = ItemArsip::where('kondisi_fisik', 'Rusak Berat')
            ->with('berkasArsip')
            ->take(5)
            ->get();

        // ==================== PERTUMBUHAN DATA ====================
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        $berkasThisMonth = BerkasArsip::where('created_at', '>=', $thisMonth)->count();
        $berkasLastMonth = BerkasArsip::whereBetween('created_at', [$lastMonth, $thisMonth])->count();
        $berkasGrowth = $berkasLastMonth > 0 ? round((($berkasThisMonth - $berkasLastMonth) / $berkasLastMonth) * 100, 1) : 0;

        $itemThisMonth = ItemArsip::where('created_at', '>=', $thisMonth)->count();
        $itemLastMonth = ItemArsip::whereBetween('created_at', [$lastMonth, $thisMonth])->count();
        $itemGrowth = $itemLastMonth > 0 ? round((($itemThisMonth - $itemLastMonth) / $itemLastMonth) * 100, 1) : 0;

        $fileThisMonth = ArsipFile::where('created_at', '>=', $thisMonth)->count();
        $fileLastMonth = ArsipFile::whereBetween('created_at', [$lastMonth, $thisMonth])->count();
        $fileGrowth = $fileLastMonth > 0 ? round((($fileThisMonth - $fileLastMonth) / $fileLastMonth) * 100, 1) : 0;

        return view('dashboard', compact(
            'totalBerkas',
            'totalItem',
            'totalFile',
            'totalKlasifikasi',
            'totalLokasi',
            'totalUser',
            'berkasAktif',
            'berkasInaktif',
            'berkasPermanen',
            'statusData',
            'yearlyData',
            'klasifikasiStats',
            'unitKerjaStats',
            'kondisiBaik',
            'kondisiRusak',
            'jenisFisikStats',
            'tingkatStats',
            'totalStorageFormatted',
            'fileTypes',
            'berkasLatest',
            'itemLatest',
            'dailyActivity',
            'lokasiUsage',
            'itemRusakBerat',
            'berkasThisMonth',
            'berkasGrowth',
            'itemThisMonth',
            'itemGrowth',
            'fileThisMonth',
            'fileGrowth'
        ));
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
