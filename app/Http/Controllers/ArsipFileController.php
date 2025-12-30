<?php

namespace App\Http\Controllers;

use App\Models\ArsipFile;
use App\Models\ItemArsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArsipFileController extends Controller
{
    /**
     * Store a newly created file.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_arsip_id' => 'required|exists:item_arsip,id',
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB per file
            'keterangan' => 'nullable|string|max:500',
        ], [
            'files.required' => 'Pilih minimal 1 file untuk diupload.',
            'files.*.mimes' => 'File harus berformat PDF, JPG, JPEG, atau PNG.',
            'files.*.max' => 'Ukuran file maksimal 10MB.',
        ]);

        try {
            $itemArsip = ItemArsip::findOrFail($request->item_arsip_id);
            $uploadedCount = 0;

            foreach ($request->file('files') as $file) {
                // Generate unique filename
                $extension = strtolower($file->getClientOriginalExtension());
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $fileName = Str::slug($originalName) . '_' . time() . '_' . uniqid() . '.' . $extension;

                // Create folder structure: arsip/{berkas_id}/{item_id}/
                $folderPath = 'arsip/' . $itemArsip->berkas_arsip_id . '/' . $itemArsip->id;

                // Store file
                $path = $file->storeAs($folderPath, $fileName, 'local');

                // Calculate hash
                $hash = hash_file('sha256', $file->getRealPath());

                // Save metadata
                ArsipFile::create([
                    'item_arsip_id' => $itemArsip->id,
                    'nama_file' => $file->getClientOriginalName(),
                    'path_file' => $path,
                    'ukuran' => $file->getSize(),
                    'tipe_file' => $extension,
                    'hash_file' => $hash,
                    'keterangan' => $request->keterangan,
                ]);

                $uploadedCount++;
            }

            return redirect()
                ->route('berkas-arsip.show', $itemArsip->berkas_arsip_id)
                ->with('success', "{$uploadedCount} file berhasil diupload.");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal upload file: ' . $e->getMessage());
        }
    }

    /**
     * Download file.
     */
    public function download(ArsipFile $arsipFile)
    {
        if (!$arsipFile->fileExists()) {
            return back()->with('error', 'File tidak ditemukan di storage.');
        }

        return Storage::disk('local')->download(
            $arsipFile->path_file,
            $arsipFile->nama_file
        );
    }

    /**
     * Preview file (for images and PDF).
     */
    public function preview(ArsipFile $arsipFile)
    {
        if (!$arsipFile->fileExists()) {
            abort(404, 'File tidak ditemukan.');
        }

        $mimeType = match ($arsipFile->tipe_file) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            default => 'application/octet-stream',
        };

        return response()->file(
            $arsipFile->full_path,
            ['Content-Type' => $mimeType]
        );
    }

    /**
     * Remove the specified file.
     */
    public function destroy(ArsipFile $arsipFile)
    {
        try {
            $itemArsip = $arsipFile->itemArsip;
            $fileName = $arsipFile->nama_file;

            // Delete file from storage
            $arsipFile->deleteFromStorage();

            // Soft delete the record
            $arsipFile->delete();

            return redirect()
                ->route('berkas-arsip.show', $itemArsip->berkas_arsip_id)
                ->with('success', "File \"{$fileName}\" berhasil dihapus.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus file: ' . $e->getMessage());
        }
    }

    /**
     * Show upload form for an item.
     */
    public function create(Request $request)
    {
        $itemId = $request->query('item');

        if (!$itemId) {
            return redirect()->route('berkas-arsip.index')->with('error', 'Item arsip tidak ditemukan.');
        }

        $itemArsip = ItemArsip::with('berkasArsip')->findOrFail($itemId);

        return view('arsip-file.create', compact('itemArsip'));
    }
}
