<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\ArsipVersion;
use App\Models\KategoriArsip;
use App\Models\UnitKerja;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_arsip', 'like', "%$search%")
                    ->orWhere('nomor_surat', 'like', "%$search%")
                    ->orWhere('perihal', 'like', "%$search%")
                    ->orWhere('pengirim', 'like', "%$search%")
                    ->orWhere('penerima', 'like', "%$search%")
                    ->orWhere('deskripsi', 'like', "%$search%")
                    ->orWhere('tags', 'like', "%$search%");
            });
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

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_surat', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_surat', '<=', $request->date_to);
        }

        $arsip = $query->latest()->paginate(15)->withQueryString();

        $kategori = KategoriArsip::all(); // Get all categories for now
        $unitKerja = UnitKerja::all(); // Get all units for now

        return view('arsip.index', compact('arsip', 'kategori', 'unitKerja'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = KategoriArsip::all();
        $unitKerja = UnitKerja::all();

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
            'jenis_arsip' => 'required|in:surat_masuk,surat_keluar,dokumen_internal,laporan,peraturan,lainnya',
            'status' => 'required|in:aktif,inaktif,musnah',
            'tanggal_surat' => 'required|date',
            'tanggal_diterima' => 'nullable|date',
            'pengirim' => 'nullable|string|max:255',
            'penerima' => 'nullable|string|max:255',
            'perihal' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'isi_ringkas' => 'nullable|string',
            'kategori_id' => 'required|exists:kategori_arsip,id',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
            'lokasi_fisik' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
        ]);

        DB::beginTransaction();

        try {
            $validated['created_by'] = Auth::id();
            $validated['updated_by'] = Auth::id();

            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . Str::slug($validated['judul_arsip']) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('arsip/' . date('Y/m'), $fileName, 'public');

                $validated['file_arsip'] = $path;
                $validated['file_type'] = $file->getClientOriginalExtension();
                $validated['file_size'] = $file->getSize();
            }

            // Calculate retention date
            if ($kategori = KategoriArsip::find($validated['kategori_id'])) {
                $validated['tanggal_retensi'] = now()->addYears($kategori->masa_retensi);
            }

            // Generate nomor arsip if not provided
            if (empty($validated['nomor_surat'])) {
                $lastArsip = Arsip::whereYear('created_at', date('Y'))->count();
                $validated['nomor_arsip'] = 'ARS/' . date('Y') . '/' . str_pad($lastArsip + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $validated['nomor_arsip'] = $validated['nomor_surat'];
            }

            $arsip = Arsip::create($validated);

            // Create initial version if file exists
            if ($arsip->file_arsip) {
                ArsipVersion::create([
                    'arsip_id' => $arsip->id,
                    'version_number' => 1,
                    'file_path' => $arsip->file_arsip,
                    'file_size' => $arsip->file_size,
                    'file_type' => $arsip->file_type,
                    'user_id' => Auth::id(),
                    'change_note' => 'Versi awal dokumen',
                    'metadata' => json_encode([
                        'judul' => $arsip->judul_arsip,
                        'nomor_surat' => $arsip->nomor_surat,
                        'tanggal' => $arsip->tanggal_surat
                    ])
                ]);
            }

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aksi' => 'create',
                'deskripsi' => 'Menambah arsip: ' . $arsip->judul_arsip,
                'model_type' => 'App\\Models\\Arsip',
                'model_id' => $arsip->id
            ]);

            DB::commit();

            return redirect()->route('arsip.index')
                ->with('success', 'Arsip berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded file if exists
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $arsip = Arsip::with(['kategori', 'unitKerja', 'creator', 'updater', 'versions.user'])
            ->findOrFail($id);

        // Increment view count
        $arsip->increment('view_count');

        // Log activity
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'read',
            'deskripsi' => 'Melihat arsip: ' . $arsip->judul_arsip,
            'model_type' => 'App\\Models\\Arsip',
            'model_id' => $arsip->id
        ]);

        return view('arsip.show', compact('arsip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $arsip = Arsip::with('versions')->findOrFail($id);
        $kategori = KategoriArsip::all();
        $unitKerja = UnitKerja::all();

        return view('arsip.edit', compact('arsip', 'kategori', 'unitKerja'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $arsip = Arsip::findOrFail($id);

        $validated = $request->validate([
            'nomor_surat' => 'nullable|string|max:255',
            'judul_arsip' => 'required|string|max:255',
            'jenis_arsip' => 'required|in:surat_masuk,surat_keluar,dokumen_internal,laporan,peraturan,lainnya',
            'status' => 'required|in:aktif,inaktif,musnah',
            'tanggal_surat' => 'required|date',
            'tanggal_diterima' => 'nullable|date',
            'pengirim' => 'nullable|string|max:255',
            'penerima' => 'nullable|string|max:255',
            'perihal' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'isi_ringkas' => 'nullable|string',
            'kategori_id' => 'required|exists:kategori_arsip,id',
            'unit_kerja_id' => 'required|exists:unit_kerja,id',
            'lokasi_fisik' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'version_note' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $validated['updated_by'] = Auth::id();

            // Handle file upload and versioning
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . Str::slug($validated['judul_arsip']) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('arsip/' . date('Y/m'), $fileName, 'public');

                // Create new version
                $lastVersion = $arsip->versions()->orderBy('version_number', 'desc')->first();
                $newVersionNumber = $lastVersion ? $lastVersion->version_number + 1 : 1;

                ArsipVersion::create([
                    'arsip_id' => $arsip->id,
                    'version_number' => $newVersionNumber,
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'user_id' => Auth::id(),
                    'change_note' => $request->version_note ?? 'Perubahan dokumen versi ' . $newVersionNumber,
                    'metadata' => json_encode([
                        'judul' => $validated['judul_arsip'],
                        'nomor_surat' => $validated['nomor_surat'] ?? $arsip->nomor_surat,
                        'tanggal' => $validated['tanggal_surat']
                    ])
                ]);

                $validated['file_arsip'] = $path;
                $validated['file_type'] = $file->getClientOriginalExtension();
                $validated['file_size'] = $file->getSize();
            }

            // Update nomor_arsip if nomor_surat changed
            if (isset($validated['nomor_surat']) && $validated['nomor_surat'] !== $arsip->nomor_surat) {
                $validated['nomor_arsip'] = $validated['nomor_surat'];
            }

            // Remove version_note from validated data
            unset($validated['version_note']);

            $arsip->update($validated);

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aksi' => 'update',
                'deskripsi' => 'Mengupdate arsip: ' . $arsip->judul_arsip,
                'model_type' => 'App\\Models\\Arsip',
                'model_id' => $arsip->id
            ]);

            DB::commit();

            return redirect()->route('arsip.show', $arsip->id)
                ->with('success', 'Arsip berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Delete newly uploaded file if exists
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $arsip = Arsip::findOrFail($id);

        DB::beginTransaction();

        try {
            // Delete all version files
            foreach ($arsip->versions as $version) {
                if ($version->file_path && Storage::disk('public')->exists($version->file_path)) {
                    Storage::disk('public')->delete($version->file_path);
                }
            }

            // Delete main file
            if ($arsip->file_arsip && Storage::disk('public')->exists($arsip->file_arsip)) {
                Storage::disk('public')->delete($arsip->file_arsip);
            }

            $judul = $arsip->judul_arsip;

            // Log activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aksi' => 'delete',
                'deskripsi' => 'Menghapus arsip: ' . $judul,
                'model_type' => 'App\\Models\\Arsip',
                'model_id' => $arsip->id
            ]);

            // Delete arsip (will cascade delete versions)
            $arsip->delete();

            DB::commit();

            return redirect()->route('arsip.index')
                ->with('success', 'Arsip berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Download arsip file
     */
    public function download($id)
    {
        $arsip = Arsip::findOrFail($id);

        if (!$arsip->file_arsip || !Storage::disk('public')->exists($arsip->file_arsip)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        // Increment download count
        $arsip->increment('download_count');

        // Log activity
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'download',
            'deskripsi' => 'Download arsip: ' . $arsip->judul_arsip,
            'model_type' => 'App\\Models\\Arsip',
            'model_id' => $arsip->id
        ]);

        $fileName = Str::slug($arsip->judul_arsip) . '_' . date('Ymd') . '.' . $arsip->file_type;

        return Storage::disk('public')->download($arsip->file_arsip, $fileName);
    }

    /**
     * Preview arsip file
     */
    public function preview($id)
    {
        $arsip = Arsip::findOrFail($id);

        if (!$arsip->file_arsip || !Storage::disk('public')->exists($arsip->file_arsip)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $path = Storage::disk('public')->path($arsip->file_arsip);
        $mimeType = Storage::disk('public')->mimeType($arsip->file_arsip);

        // Log activity
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'read',
            'deskripsi' => 'Preview arsip: ' . $arsip->judul_arsip,
            'model_type' => 'App\\Models\\Arsip',
            'model_id' => $arsip->id
        ]);

        // For PDFs and images, display inline
        if (in_array($mimeType, ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'])) {
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($arsip->file_arsip) . '"'
            ]);
        }

        // For other files, download
        return $this->download($id);
    }

    /**
     * Show versions history
     */
    public function versions($id)
    {
        $arsip = Arsip::with(['versions.user'])->findOrFail($id);

        return view('arsip.versions', compact('arsip'));
    }

    /**
     * Download specific version
     */
    public function downloadVersion($arsipId, $versionId)
    {
        $version = ArsipVersion::where('arsip_id', $arsipId)
            ->where('id', $versionId)
            ->firstOrFail();

        if (!$version->file_path || !Storage::disk('public')->exists($version->file_path)) {
            return back()->with('error', 'File versi tidak ditemukan.');
        }

        // Log activity
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'download',
            'deskripsi' => 'Download versi ' . $version->version_number . ' dari arsip ID: ' . $arsipId,
            'model_type' => 'App\\Models\\ArsipVersion',
            'model_id' => $version->id
        ]);

        $arsip = $version->arsip;
        $fileName = Str::slug($arsip->judul_arsip) . '_v' . $version->version_number . '_' .
            date('Ymd', strtotime($version->created_at)) . '.' . $version->file_type;

        return Storage::disk('public')->download($version->file_path, $fileName);
    }
}
