<?php

namespace App\Http\Controllers;

use App\Models\BerkasArsip;
use App\Models\ItemArsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemArsipController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $berkasId = $request->query('berkas');
        $berkas = null;

        if ($berkasId) {
            $berkas = BerkasArsip::findOrFail($berkasId);
        } else {
            // If no berkas ID provided, maybe redirect back or show error?
            // Or allow selecting berkas (but user flow usually starts from Berkas Detail)
            return redirect()->route('berkas-arsip.index')->with('error', 'Silakan pilih berkas terlebih dahulu.');
        }

        // Auto-generate nomor item suggestion (e.g., count + 1)
        $lastItem = ItemArsip::where('berkas_arsip_id', $berkasId)->orderBy('id', 'desc')->first();
        $nextNumber = $lastItem ? (intval($lastItem->nomor_item) + 1) : 1;

        return view('item-arsip.create', compact('berkas', 'nextNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'berkas_arsip_id' => 'required|exists:berkas_arsip,id',
            'nomor_item' => 'required|string|max:100',
            'uraian_item' => 'required|string',
            'nomor_surat' => 'nullable|string|max:255',
            'tanggal_surat' => 'required|date',
            'asal_surat' => 'nullable|string|max:255',
            'jumlah_eksemplar' => 'required|integer|min:1',
            'tingkat_perkembangan' => 'required|in:Asli,Copy,Tembusan,Salinan,Petikan',
            'jenis_fisik' => 'required|in:Tekstual,Kartografik,Kearsitekturan,Audio Visual,Elektronik',
            'kondisi_fisik' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'keterangan' => 'nullable|string',
            // 'file_digital' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // Future implementation
        ]);

        try {
            $data = $request->all();

            // Handle file upload if implemented later
            // if ($request->hasFile('file_digital')) {
            //     $path = $request->file('file_digital')->store('arsip-digital');
            //     $data['file_path'] = $path;
            // }

            ItemArsip::create($data);

            return redirect()
                ->route('berkas-arsip.show', $request->berkas_arsip_id)
                ->with('success', 'Item arsip berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan item arsip: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemArsip $itemArsip)
    {
        $itemArsip->load('berkasArsip');
        return view('item-arsip.edit', compact('itemArsip'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemArsip $itemArsip)
    {
        $request->validate([
            'nomor_item' => 'required|string|max:100',
            'uraian_item' => 'required|string',
            'nomor_surat' => 'nullable|string|max:255',
            'tanggal_surat' => 'required|date',
            'asal_surat' => 'nullable|string|max:255',
            'jumlah_eksemplar' => 'required|integer|min:1',
            'tingkat_perkembangan' => 'required|in:Asli,Copy,Tembusan,Salinan,Petikan',
            'jenis_fisik' => 'required|in:Tekstual,Kartografik,Kearsitekturan,Audio Visual,Elektronik',
            'kondisi_fisik' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $data = $request->all();
            $itemArsip->update($data);

            return redirect()
                ->route('berkas-arsip.show', $itemArsip->berkas_arsip_id)
                ->with('success', 'Item arsip berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui item arsip: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemArsip $itemArsip)
    {
        // Check if file exists
        if ($itemArsip->hasFile()) {
            return back()->with('error', 'Item arsip tidak dapat dihapus karena memiliki file digital. Hapus file terlebih dahulu.');
        }

        try {
            $berkasId = $itemArsip->berkas_arsip_id;
            $itemArsip->delete();

            return redirect()
                ->route('berkas-arsip.show', $berkasId)
                ->with('success', 'Item arsip berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus item arsip: ' . $e->getMessage());
        }
    }
}
