<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\KategoriArsip;
use App\Models\UnitKerja;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArsipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Arsip::with(['kategori', 'unitKerja', 'creator']);
        
        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }
        
        // Filter by unit kerja
        if ($request->filled('unit_kerja')) {
            $query->where('unit_kerja_id', $request->unit_kerja);
        }
        
        // Filter by jenis arsip
        if ($request->filled('jenis')) {
            $query->where('jenis_arsip', $request->jenis);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_surat', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_surat', '<=', $request->date_to);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $arsip = $query->latest()->paginate(15)->withQueryString();
        
        $kategori = KategoriArsip::active()->get();
        $unitKerja = UnitKerja::active()->get();
        
        return view('arsip.index', compact('arsip', 'kategori', 'unitKerja'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = KategoriArsip::active()->get();
        $unitKerja = UnitKerja::active()->get();
        
        return view('arsip.create', compact('kategori', 'unitKerja'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'nullable|string|max:255',
            'judul_arsip' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'required|exists:kategori_arsip,id',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
            'jenis_arsip' => 'required|in:surat_masuk,surat_keluar,dokumen_internal,laporan,peraturan,lainnya',
            'tanggal_surat' => 'required|date',
            'tanggal_diterima' => 'nullable|date',
            'pengirim' => 'nullable|string|max:255',
            'penerima' => 'nullable|string|max:255',
            'perihal' => 'nullable|string|max:255',
            'isi_ringkas' => 'nullable|string',
            'lokasi_fisik' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240', // Max 10MB
        ]);
        
        $validated['created_by'] = auth()->id();
        
        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = Str::random(20) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('arsip', $fileName, 'public');
            
            $validated['file_arsip'] = $path;
            $validated['file_type'] = $file->getClientOriginalExtension();
            $validated['file_size'] = $file->getSize();
        }
        
        // Calculate retention date
        if ($kategori = KategoriArsip::find($validated['kategori_id'])) {
            $validated['tanggal_retensi'] = now()->addYears($kategori->masa_retensi);
        }
        
        $arsip = Arsip::create($validated);
        
        // Log activity
        LogAktivitas::log('create', 'Membuat arsip baru: ' . $arsip->judul_arsip, $arsip);
        
        return redirect()->route('arsip.index')
            ->with('success', 'Arsip berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Arsip $arsip)
    {
        $arsip->load(['kategori', 'unitKerja', 'creator', 'updater']);
        
        // Increment view count
        $arsip->increment('view_count');
        
        // Log activity
        LogAktivitas::log('read', 'Melihat arsip: ' . $arsip->judul_arsip, $arsip);
        
        return view('arsip.show', compact('arsip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Arsip $arsip)
    {
        $kategori = KategoriArsip::active()->get();
        $unitKerja = UnitKerja::active()->get();
        
        return view('arsip.edit', compact('arsip', 'kategori', 'unitKerja'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Arsip $arsip)
    {
        $validated = $request->validate([
            'nomor_surat' => 'nullable|string|max:255',
            'judul_arsip' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'required|exists:kategori_arsip,id',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
            'jenis_arsip' => 'required|in:surat_masuk,surat_keluar,dokumen_internal,laporan,peraturan,lainnya',
            'tanggal_surat' => 'required|date',
            'tanggal_diterima' => 'nullable|date',
            'pengirim' => 'nullable|string|max:255',
            'penerima' => 'nullable|string|max:255',
            'perihal' => 'nullable|string|max:255',
            'isi_ringkas' => 'nullable|string',
            'lokasi_fisik' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,inaktif,musnah',
            'tags' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ]);
        
        $oldData = $arsip->toArray();
        $validated['updated_by'] = auth()->id();
        
        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($arsip->file_arsip) {
                Storage::disk('public')->delete($arsip->file_arsip);
            }
            
            $file = $request->file('file');
            $fileName = Str::random(20) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('arsip', $fileName, 'public');
            
            $validated['file_arsip'] = $path;
            $validated['file_type'] = $file->getClientOriginalExtension();
            $validated['file_size'] = $file->getSize();
        }
        
        $arsip->update($validated);
        
        // Log activity
        LogAktivitas::log('update', 'Mengubah arsip: ' . $arsip->judul_arsip, $arsip, $oldData, $validated);
        
        return redirect()->route('arsip.index')
            ->with('success', 'Arsip berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Arsip $arsip)
    {
        // Delete file
        if ($arsip->file_arsip) {
            Storage::disk('public')->delete($arsip->file_arsip);
        }
        
        $judul = $arsip->judul_arsip;
        
        // Log activity
        LogAktivitas::log('delete', 'Menghapus arsip: ' . $judul, $arsip);
        
        $arsip->delete();
        
        return redirect()->route('arsip.index')
            ->with('success', 'Arsip berhasil dihapus.');
    }
    
    /**
     * Download arsip file
     */
    public function download(Arsip $arsip)
    {
        if (!$arsip->file_arsip || !Storage::disk('public')->exists($arsip->file_arsip)) {
            return back()->with('error', 'File tidak ditemukan.');
        }
        
        // Increment download count
        $arsip->increment('download_count');
        
        // Log activity
        LogAktivitas::log('download', 'Mengunduh arsip: ' . $arsip->judul_arsip, $arsip);
        
        return Storage::disk('public')->download(
            $arsip->file_arsip,
            $arsip->nomor_arsip . '_' . Str::slug($arsip->judul_arsip) . '.' . $arsip->file_type
        );
    }
}
