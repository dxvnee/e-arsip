<?php

namespace App\Http\Controllers;

use App\Http\Requests\BerkasArsipRequest;
use App\Models\BerkasArsip;
use App\Models\KlasifikasiArsip;
use App\Models\LokasiArsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BerkasArsipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BerkasArsip::with(['klasifikasiArsip', 'lokasiArsip'])
            ->withCount('itemArsip');

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by tahun
        if ($request->filled('tahun')) {
            $query->byTahun($request->tahun);
        }

        // Filter by klasifikasi
        if ($request->filled('klasifikasi')) {
            $query->byKlasifikasi($request->klasifikasi);
        }

        // Filter by status arsip
        if ($request->filled('status_arsip')) {
            $query->byStatus($request->status_arsip);
        }

        // Filter by unit kerja
        if ($request->filled('unit_kerja')) {
            $query->byUnitKerja($request->unit_kerja);
        }

        $berkas = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total' => BerkasArsip::count(),
            'aktif' => BerkasArsip::byStatus('Aktif')->count(),
            'inaktif' => BerkasArsip::byStatus('Inaktif')->count(),
            'permanen' => BerkasArsip::byStatus('Permanen')->count(),
        ];

        // Get filter options
        $tahunList = BerkasArsip::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $klasifikasiList = KlasifikasiArsip::active()
            ->orderBy('kode_klasifikasi')
            ->get(['id', 'kode_klasifikasi', 'nama_klasifikasi']);

        $unitKerjaList = BerkasArsip::select('unit_kerja')
            ->distinct()
            ->orderBy('unit_kerja')
            ->pluck('unit_kerja');

        return view('berkas-arsip.index', compact(
            'berkas',
            'stats',
            'tahunList',
            'klasifikasiList',
            'unitKerjaList'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $klasifikasiList = KlasifikasiArsip::active()
            ->orderBy('kode_klasifikasi')
            ->get();

        $lokasiList = LokasiArsip::active()
            ->orderBy('gedung')
            ->orderBy('ruang')
            ->orderBy('rak')
            ->orderBy('boks')
            ->get();

        $unitKerjaList = BerkasArsip::select('unit_kerja')
            ->distinct()
            ->orderBy('unit_kerja')
            ->pluck('unit_kerja');

        // Generate next nomor berkas
        $nomorBerkasSuggestion = BerkasArsip::generateNomorBerkas(date('Y'));

        return view('berkas-arsip.create', compact(
            'klasifikasiList',
            'lokasiList',
            'unitKerjaList',
            'nomorBerkasSuggestion'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BerkasArsipRequest $request)
    {
        try {
            $data = $request->validated();

            // Generate nomor berkas if not provided
            if (empty($data['nomor_berkas'])) {
                $data['nomor_berkas'] = BerkasArsip::generateNomorBerkas($data['tahun']);
            }

            $berkas = BerkasArsip::create($data);

            return redirect()
                ->route('berkas-arsip.show', $berkas)
                ->with('success', "Berkas arsip \"{$berkas->nomor_berkas}\" berhasil ditambahkan.");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan berkas arsip: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BerkasArsip $berkasArsip)
    {
        $berkasArsip->load(['klasifikasiArsip', 'lokasiArsip']);

        // Get item arsip in this berkas
        $itemList = $berkasArsip->itemArsip()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('berkas-arsip.show', compact('berkasArsip', 'itemList'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BerkasArsip $berkasArsip)
    {
        $klasifikasiList = KlasifikasiArsip::active()
            ->orderBy('kode_klasifikasi')
            ->get();

        $lokasiList = LokasiArsip::active()
            ->orderBy('gedung')
            ->orderBy('ruang')
            ->orderBy('rak')
            ->orderBy('boks')
            ->get();

        $unitKerjaList = BerkasArsip::select('unit_kerja')
            ->distinct()
            ->orderBy('unit_kerja')
            ->pluck('unit_kerja');

        return view('berkas-arsip.edit', compact(
            'berkasArsip',
            'klasifikasiList',
            'lokasiList',
            'unitKerjaList'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BerkasArsipRequest $request, BerkasArsip $berkasArsip)
    {
        try {
            $data = $request->validated();
            $berkasArsip->update($data);

            return redirect()
                ->route('berkas-arsip.show', $berkasArsip)
                ->with('success', "Berkas arsip \"{$berkasArsip->nomor_berkas}\" berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui berkas arsip: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BerkasArsip $berkasArsip)
    {
        try {
            // Check if berkas has item arsip
            if (!$berkasArsip->canBeDeleted()) {
                return back()->with(
                    'error',
                    "Berkas \"{$berkasArsip->nomor_berkas}\" tidak dapat dihapus karena masih memiliki {$berkasArsip->item_count} item arsip."
                );
            }

            $nomorBerkas = $berkasArsip->nomor_berkas;
            $berkasArsip->delete();

            return redirect()
                ->route('berkas-arsip.index')
                ->with('success', "Berkas arsip \"{$nomorBerkas}\" berhasil dihapus.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus berkas arsip: ' . $e->getMessage());
        }
    }

    /**
     * Restore a soft-deleted resource.
     */
    public function restore($id)
    {
        try {
            $berkas = BerkasArsip::withTrashed()->findOrFail($id);
            $berkas->restore();

            return redirect()
                ->route('berkas-arsip.index')
                ->with('success', "Berkas arsip \"{$berkas->nomor_berkas}\" berhasil dipulihkan.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulihkan berkas arsip: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the active status of the resource.
     */
    public function toggleStatus(BerkasArsip $berkasArsip)
    {
        try {
            $berkasArsip->update(['is_active' => !$berkasArsip->is_active]);
            $status = $berkasArsip->is_active ? 'diaktifkan' : 'dinonaktifkan';

            return back()->with('success', "Berkas \"{$berkasArsip->nomor_berkas}\" berhasil {$status}.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah status berkas arsip.');
        }
    }

    /**
     * Get berkas by klasifikasi (AJAX).
     */
    public function getByKlasifikasi(Request $request)
    {
        $klasifikasiId = $request->get('klasifikasi_id');

        $berkas = BerkasArsip::active()
            ->where('klasifikasi_arsip_id', $klasifikasiId)
            ->orderBy('nomor_berkas')
            ->get(['id', 'nomor_berkas', 'uraian_berkas', 'tahun']);

        return response()->json($berkas);
    }
}
