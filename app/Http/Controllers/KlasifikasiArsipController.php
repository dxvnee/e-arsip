<?php

namespace App\Http\Controllers;

use App\Http\Requests\KlasifikasiArsipRequest;
use App\Models\KlasifikasiArsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class KlasifikasiArsipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = KlasifikasiArsip::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by nasib_akhir
        if ($request->filled('nasib_akhir')) {
            $query->where('nasib_akhir', $request->nasib_akhir);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $klasifikasi = $query->orderBy('kode_klasifikasi', 'asc')
            ->paginate(10)
            ->withQueryString();

        // Statistics
        $stats = [
            'total' => KlasifikasiArsip::count(),
            'active' => KlasifikasiArsip::where('is_active', true)->count(),
            'musnah' => KlasifikasiArsip::where('nasib_akhir', 'musnah')->count(),
            'permanen' => KlasifikasiArsip::where('nasib_akhir', 'permanen')->count(),
        ];

        return view('klasifikasi-arsip.index', compact('klasifikasi', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('klasifikasi-arsip.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KlasifikasiArsipRequest $request)
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');

            KlasifikasiArsip::create($data);

            return redirect()
                ->route('klasifikasi-arsip.index')
                ->with('success', 'Kode klasifikasi arsip berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating klasifikasi arsip: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(KlasifikasiArsip $klasifikasiArsip)
    {
        return view('klasifikasi-arsip.show', compact('klasifikasiArsip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KlasifikasiArsip $klasifikasiArsip)
    {
        return view('klasifikasi-arsip.edit', compact('klasifikasiArsip'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KlasifikasiArsipRequest $request, KlasifikasiArsip $klasifikasiArsip)
    {
        try {
            $data = $request->validated();
            $data['is_active'] = $request->has('is_active');

            $klasifikasiArsip->update($data);

            return redirect()
                ->route('klasifikasi-arsip.index')
                ->with('success', 'Kode klasifikasi arsip berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating klasifikasi arsip: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(KlasifikasiArsip $klasifikasiArsip)
    {
        try {
            $klasifikasiArsip->delete();

            return redirect()
                ->route('klasifikasi-arsip.index')
                ->with('success', 'Kode klasifikasi arsip berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting klasifikasi arsip: ' . $e->getMessage());

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
            $klasifikasiArsip = KlasifikasiArsip::withTrashed()->findOrFail($id);
            $klasifikasiArsip->restore();

            return redirect()
                ->route('klasifikasi-arsip.index')
                ->with('success', 'Kode klasifikasi arsip berhasil dipulihkan.');
        } catch (\Exception $e) {
            Log::error('Error restoring klasifikasi arsip: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat memulihkan data.');
        }
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(KlasifikasiArsip $klasifikasiArsip)
    {
        try {
            $klasifikasiArsip->update([
                'is_active' => !$klasifikasiArsip->is_active
            ]);

            $status = $klasifikasiArsip->is_active ? 'diaktifkan' : 'dinonaktifkan';

            return redirect()
                ->back()
                ->with('success', "Kode klasifikasi berhasil {$status}.");
        } catch (\Exception $e) {
            Log::error('Error toggling status klasifikasi arsip: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat mengubah status.');
        }
    }
}
