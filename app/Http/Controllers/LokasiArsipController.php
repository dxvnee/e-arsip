<?php

namespace App\Http\Controllers;

use App\Http\Requests\LokasiArsipRequest;
use App\Models\LokasiArsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;


class LokasiArsipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LokasiArsip::query()->withCount('arsip');

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by gedung
        if ($request->filled('gedung')) {
            $query->byGedung($request->gedung);
        }

        // Filter by ruang
        if ($request->filled('ruang')) {
            $query->byRuang($request->ruang);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $lokasi = $query->orderBy('gedung', 'asc')
            ->orderBy('ruang', 'asc')
            ->orderBy('rak', 'asc')
            ->orderBy('boks', 'asc')
            ->paginate(10)
            ->withQueryString();

        // Get unique gedung and ruang for filters
        $gedungList = LokasiArsip::select('gedung')->distinct()->orderBy('gedung')->pluck('gedung');
        $ruangList = LokasiArsip::select('ruang')->distinct()->orderBy('ruang')->pluck('ruang');

        // Statistics
        $stats = [
            'total' => LokasiArsip::count(),
            'active' => LokasiArsip::where('is_active', true)->count(),
            'in_use' => LokasiArsip::whereHas('arsip')->count(),
            'total_gedung' => LokasiArsip::select('gedung')->distinct()->count(),
        ];

        return view('lokasi-arsip.index', compact('lokasi', 'stats', 'gedungList', 'ruangList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get existing gedung and ruang for suggestions
        $gedungList = LokasiArsip::select('gedung')->distinct()->orderBy('gedung')->pluck('gedung');
        $ruangList = LokasiArsip::select('ruang')->distinct()->orderBy('ruang')->pluck('ruang');

        return view('lokasi-arsip.create', compact('gedungList', 'ruangList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LokasiArsipRequest $request)
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');

            LokasiArsip::create($data);

            return redirect()
                ->route('lokasi-arsip.index')
                ->with('success', 'Lokasi arsip berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating lokasi arsip: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LokasiArsip $lokasiArsip)
    {
        $lokasiArsip->loadCount('arsip');

        // Get arsip in this location
        $arsipList = [];
        if (class_exists(\App\Models\Arsip::class) && Schema::hasTable('arsip')) {
            $arsipList = $lokasiArsip->arsip()->latest()->take(10)->get();
        }

        return view('lokasi-arsip.show', compact('lokasiArsip', 'arsipList'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LokasiArsip $lokasiArsip)
    {
        // Get existing gedung and ruang for suggestions
        $gedungList = LokasiArsip::select('gedung')->distinct()->orderBy('gedung')->pluck('gedung');
        $ruangList = LokasiArsip::select('ruang')->distinct()->orderBy('ruang')->pluck('ruang');

        return view('lokasi-arsip.edit', compact('lokasiArsip', 'gedungList', 'ruangList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LokasiArsipRequest $request, LokasiArsip $lokasiArsip)
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');

            $lokasiArsip->update($data);

            return redirect()
                ->route('lokasi-arsip.index')
                ->with('success', 'Lokasi arsip berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating lokasi arsip: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LokasiArsip $lokasiArsip)
    {
        try {
            // Check if location is in use
            if ($lokasiArsip->isInUse()) {
                return redirect()
                    ->back()
                    ->with('error', 'Lokasi tidak dapat dihapus karena masih digunakan oleh ' . $lokasiArsip->arsip_count . ' arsip.');
            }

            $lokasiArsip->delete();

            return redirect()
                ->route('lokasi-arsip.index')
                ->with('success', 'Lokasi arsip berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting lokasi arsip: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        try {
            $lokasiArsip = LokasiArsip::withTrashed()->findOrFail($id);
            $lokasiArsip->restore();

            return redirect()
                ->route('lokasi-arsip.index')
                ->with('success', 'Lokasi arsip berhasil dipulihkan.');
        } catch (\Exception $e) {
            Log::error('Error restoring lokasi arsip: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat memulihkan data.');
        }
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(LokasiArsip $lokasiArsip)
    {
        try {
            $lokasiArsip->update([
                'is_active' => !$lokasiArsip->is_active
            ]);

            $status = $lokasiArsip->is_active ? 'diaktifkan' : 'dinonaktifkan';

            return redirect()
                ->back()
                ->with('success', "Lokasi arsip berhasil {$status}.");
        } catch (\Exception $e) {
            Log::error('Error toggling status lokasi arsip: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat mengubah status.');
        }
    }

    /**
     * Get locations by gedung (AJAX)
     */
    public function getByGedung(Request $request)
    {
        $gedung = $request->get('gedung');
        $ruangList = LokasiArsip::where('gedung', $gedung)
            ->select('ruang')
            ->distinct()
            ->orderBy('ruang')
            ->pluck('ruang');

        return response()->json($ruangList);
    }

    /**
     * Get locations for dropdown (AJAX)
     */
    public function getLocations(Request $request)
    {
        $query = LokasiArsip::active();

        if ($request->filled('gedung')) {
            $query->byGedung($request->gedung);
        }

        if ($request->filled('ruang')) {
            $query->byRuang($request->ruang);
        }

        $locations = $query->orderBy('gedung')
            ->orderBy('ruang')
            ->orderBy('rak')
            ->orderBy('boks')
            ->get(['id', 'kode_lokasi', 'gedung', 'ruang', 'rak', 'boks']);

        return response()->json($locations);
    }
}
