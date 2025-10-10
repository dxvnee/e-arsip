<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\Arsip;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    public function index(Request $request)
    {
        $query = Disposisi::with(['arsip', 'dariUser', 'kepadaUser'])
            ->untukUser(auth()->id());
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $disposisi = $query->latest()->paginate(15);
        
        $stats = [
            'baru' => Disposisi::untukUser(auth()->id())->where('status', 'baru')->count(),
            'dibaca' => Disposisi::untukUser(auth()->id())->where('status', 'dibaca')->count(),
            'diproses' => Disposisi::untukUser(auth()->id())->where('status', 'diproses')->count(),
            'selesai' => Disposisi::untukUser(auth()->id())->where('status', 'selesai')->count(),
        ];
        
        return view('disposisi.index', compact('disposisi', 'stats'));
    }

    public function create(Request $request)
    {
        $arsipId = $request->get('arsip_id');
        $arsip = null;
        
        if ($arsipId) {
            $arsip = Arsip::findOrFail($arsipId);
        }
        
        $users = User::where('id', '!=', auth()->id())
            ->where('is_active', true)
            ->whereIn('role', ['admin', 'operator', 'petugas'])
            ->get();
        
        return view('disposisi.create', compact('arsip', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'arsip_id' => 'required|exists:arsip,id',
            'kepada_user_id' => 'required|exists:users,id',
            'isi_disposisi' => 'required|string',
            'prioritas' => 'required|in:biasa,segera,sangat_segera',
            'sifat' => 'required|in:biasa,rahasia,penting',
            'catatan' => 'nullable|string',
        ]);
        
        $validated['dari_user_id'] = auth()->id();
        $disposisi = Disposisi::create($validated);
        
        LogAktivitas::log('create', 'Membuat disposisi untuk ' . $disposisi->kepadaUser->name, $disposisi);
        
        return redirect()->route('arsip.show', $disposisi->arsip_id)
            ->with('success', 'Disposisi berhasil dibuat.');
    }

    public function show(Disposisi $disposisi)
    {
        if ($disposisi->kepada_user_id != auth()->id() && $disposisi->dari_user_id != auth()->id()) {
            abort(403);
        }
        
        $disposisi->load(['arsip', 'dariUser', 'kepadaUser']);
        
        if ($disposisi->kepada_user_id == auth()->id() && $disposisi->status == 'baru') {
            $disposisi->markAsDibaca();
        }
        
        return view('disposisi.show', compact('disposisi'));
    }

    public function updateStatus(Request $request, Disposisi $disposisi)
    {
        if ($disposisi->kepada_user_id != auth()->id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'status' => 'required|in:dibaca,diproses,selesai',
            'tindak_lanjut' => 'required_if:status,selesai',
        ]);
        
        switch ($validated['status']) {
            case 'dibaca':
                $disposisi->markAsDibaca();
                break;
            case 'diproses':
                $disposisi->markAsDiproses();
                break;
            case 'selesai':
                $disposisi->markAsSelesai($validated['tindak_lanjut'] ?? null);
                break;
        }
        
        LogAktivitas::log('update', 'Update status disposisi: ' . $validated['status'], $disposisi);
        
        return redirect()->route('disposisi.show', $disposisi)
            ->with('success', 'Status berhasil diperbarui.');
    }
}
