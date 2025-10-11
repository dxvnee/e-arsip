@extends('layouts.app')

@section('title', 'Detail Arsip')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold" style="color: #008e3c;">
                    <i class="fas fa-file-alt mr-2"></i>Detail Arsip
                </h2>
                <p class="text-gray-600 text-sm mt-1">ID: #{{ $arsip->id }}</p>
            </div>
            <div class="flex space-x-2">
                @can('update', $arsip)
                <a href="{{ route('arsip.edit', $arsip->id) }}" 
                   class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                @endcan
                
                @can('delete', $arsip)
                <form action="{{ route('arsip.destroy', $arsip->id) }}" method="POST" class="inline"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </form>
                @endcan
                
                <a href="{{ route('arsip.index') }}" 
                   class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Document Preview -->
            @if($arsip->file_path)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-4" style="color: #008e3c;">
                    <i class="fas fa-eye mr-2"></i>Preview Dokumen
                </h3>
                
                <div class="bg-gray-50 rounded-lg p-4">
                    @if(in_array(pathinfo($arsip->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                        <!-- Image Preview -->
                        <img src="{{ Storage::url($arsip->file_path) }}" 
                             alt="{{ $arsip->judul_arsip }}"
                             class="w-full h-auto rounded-lg shadow-md">
                    @elseif(pathinfo($arsip->file_path, PATHINFO_EXTENSION) == 'pdf')
                        <!-- PDF Preview -->
                        <iframe src="{{ Storage::url($arsip->file_path) }}" 
                                class="w-full h-96 rounded-lg shadow-md"
                                frameborder="0"></iframe>
                        <p class="text-sm text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Jika preview tidak muncul, silakan 
                            <a href="{{ route('arsip.preview', $arsip->id) }}" target="_blank" 
                               class="text-blue-600 hover:underline">buka di tab baru</a>
                        </p>
                    @else
                        <!-- Other file types -->
                        <div class="text-center py-12">
                            <i class="fas fa-file text-6xl mb-4" style="color: #008e3c;"></i>
                            <p class="text-gray-600 mb-4">
                                Preview tidak tersedia untuk tipe file ini
                            </p>
                            <a href="{{ route('arsip.download', $arsip->id) }}" 
                               class="inline-block px-6 py-2 rounded-lg text-white font-medium hover:shadow-lg transition-all"
                               style="background-color: #008e3c;">
                                <i class="fas fa-download mr-2"></i>
                                Download untuk melihat
                            </a>
                        </div>
                    @endif
                </div>
                
                <!-- Download Button -->
                <div class="mt-4 flex justify-center">
                    <a href="{{ route('arsip.download', $arsip->id) }}" 
                       class="px-6 py-3 rounded-lg text-white font-medium shadow-lg hover:shadow-xl transition-all"
                       style="background-color: #008e3c;">
                        <i class="fas fa-download mr-2"></i>
                        Download Dokumen
                    </a>
                </div>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="text-center py-12">
                    <i class="fas fa-file-excel text-6xl mb-4 text-gray-300"></i>
                    <p class="text-gray-500">Tidak ada file digital untuk arsip ini</p>
                </div>
            </div>
            @endif

            <!-- Informasi Detail -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Lengkap
                </h3>
                
                <div class="space-y-4">
                    <!-- Judul -->
                    <div class="border-b pb-3">
                        <label class="text-sm font-medium text-gray-500">Judul Arsip</label>
                        <p class="text-gray-800 font-semibold">{{ $arsip->judul_arsip }}</p>
                    </div>

                    <!-- Nomor Surat -->
                    @if($arsip->nomor_surat)
                    <div class="border-b pb-3">
                        <label class="text-sm font-medium text-gray-500">Nomor Surat</label>
                        <p class="text-gray-800">{{ $arsip->nomor_surat }}</p>
                    </div>
                    @endif

                    <!-- Tanggal -->
                    <div class="grid grid-cols-2 gap-4 border-b pb-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tanggal Surat</label>
                            <p class="text-gray-800">{{ \Carbon\Carbon::parse($arsip->tanggal_surat)->format('d F Y') }}</p>
                        </div>
                        @if($arsip->tanggal_diterima)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tanggal Diterima</label>
                            <p class="text-gray-800">{{ \Carbon\Carbon::parse($arsip->tanggal_diterima)->format('d F Y') }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Pengirim/Penerima -->
                    <div class="grid grid-cols-2 gap-4 border-b pb-3">
                        @if($arsip->pengirim)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Pengirim/Asal</label>
                            <p class="text-gray-800">{{ $arsip->pengirim }}</p>
                        </div>
                        @endif
                        @if($arsip->penerima)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Penerima/Tujuan</label>
                            <p class="text-gray-800">{{ $arsip->penerima }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Perihal -->
                    @if($arsip->perihal)
                    <div class="border-b pb-3">
                        <label class="text-sm font-medium text-gray-500">Perihal</label>
                        <p class="text-gray-800">{{ $arsip->perihal }}</p>
                    </div>
                    @endif

                    <!-- Deskripsi -->
                    @if($arsip->deskripsi)
                    <div class="border-b pb-3">
                        <label class="text-sm font-medium text-gray-500">Deskripsi</label>
                        <p class="text-gray-800">{{ $arsip->deskripsi }}</p>
                    </div>
                    @endif

                    <!-- Isi Ringkas -->
                    @if($arsip->isi_ringkas)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Isi Ringkas/Keterangan</label>
                        <p class="text-gray-800 whitespace-pre-line">{{ $arsip->isi_ringkas }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Info -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-4" style="color: #008e3c;">
                    <i class="fas fa-tags mr-2"></i>Informasi Cepat
                </h3>
                
                <div class="space-y-3">
                    <!-- Jenis Arsip -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Jenis:</span>
                        <span class="px-2 py-1 text-xs rounded-full font-medium
                            @if($arsip->jenis_arsip == 'surat_masuk') bg-blue-100 text-blue-800
                            @elseif($arsip->jenis_arsip == 'surat_keluar') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucwords(str_replace('_', ' ', $arsip->jenis_arsip)) }}
                        </span>
                    </div>

                    <!-- Status -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full font-medium
                            @if($arsip->status == 'aktif') bg-green-100 text-green-800
                            @elseif($arsip->status == 'inaktif') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($arsip->status) }}
                        </span>
                    </div>

                    <!-- Kategori -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Kategori:</span>
                        <span class="text-sm font-medium text-gray-800">
                            {{ $arsip->kategori->nama_kategori ?? '-' }}
                        </span>
                    </div>

                    <!-- Unit Kerja -->
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Unit Kerja:</span>
                        <span class="text-sm font-medium text-gray-800">
                            {{ $arsip->unitKerja->nama_unit ?? '-' }}
                        </span>
                    </div>

                    <!-- Lokasi Fisik -->
                    @if($arsip->lokasi_fisik)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Lokasi:</span>
                        <span class="text-sm font-medium text-gray-800">
                            {{ $arsip->lokasi_fisik }}
                        </span>
                    </div>
                    @endif

                    <!-- Tags -->
                    @if($arsip->tags)
                    <div>
                        <span class="text-sm text-gray-600 block mb-2">Tags:</span>
                        <div class="flex flex-wrap gap-1">
                            @foreach(explode(',', $arsip->tags) as $tag)
                            <span class="px-2 py-1 text-xs rounded-full" 
                                  style="background-color: #efd856; color: #008e3c;">
                                {{ trim($tag) }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-4" style="color: #008e3c;">
                    <i class="fas fa-database mr-2"></i>Metadata
                </h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Dibuat:</span>
                        <span class="text-gray-800">
                            {{ $arsip->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Diperbarui:</span>
                        <span class="text-gray-800">
                            {{ $arsip->updated_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Oleh:</span>
                        <span class="text-gray-800">
                            {{ $arsip->user->name ?? 'System' }}
                        </span>
                    </div>
                    @if($arsip->file_size)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Ukuran File:</span>
                        <span class="text-gray-800">
                            {{ number_format($arsip->file_size / 1024 / 1024, 2) }} MB
                        </span>
                    </div>
                    @endif
                    @if($arsip->file_type)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Tipe File:</span>
                        <span class="text-gray-800">
                            {{ strtoupper(pathinfo($arsip->file_path, PATHINFO_EXTENSION)) }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Version History -->
            @if($arsip->versions && $arsip->versions->count() > 0)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-4" style="color: #008e3c;">
                    <i class="fas fa-history mr-2"></i>Riwayat Versi
                </h3>
                
                <div class="space-y-2">
                    @foreach($arsip->versions->sortByDesc('created_at')->take(5) as $version)
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                        <div class="flex items-center">
                            <span class="px-2 py-1 text-xs font-medium rounded bg-gray-200 text-gray-700 mr-2">
                                v{{ $version->version_number }}
                            </span>
                            <span class="text-xs text-gray-600">
                                {{ $version->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                        <a href="{{ route('arsip.version.download', ['arsip' => $arsip->id, 'version' => $version->id]) }}" 
                           class="text-blue-600 hover:text-blue-800 text-xs">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                    @endforeach
                    
                    @if($arsip->versions->count() > 5)
                    <a href="{{ route('arsip.versions', $arsip->id) }}" 
                       class="block text-center text-sm text-blue-600 hover:text-blue-800 mt-2">
                        Lihat semua versi ({{ $arsip->versions->count() }})
                    </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Related Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-4" style="color: #008e3c;">
                    <i class="fas fa-tasks mr-2"></i>Aksi Terkait
                </h3>
                
                <div class="space-y-2">
                    <a href="{{ route('disposisi.create', ['arsip_id' => $arsip->id]) }}" 
                       class="block w-full px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 text-center transition-colors">
                        <i class="fas fa-share mr-2"></i>Buat Disposisi
                    </a>
                    
                    <button onclick="window.print()" 
                            class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-print mr-2"></i>Cetak
                    </button>
                    
                    <button onclick="shareDocument()" 
                            class="w-full px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                        <i class="fas fa-share-alt mr-2"></i>Bagikan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function shareDocument() {
    const url = window.location.href;
    if (navigator.share) {
        navigator.share({
            title: '{{ $arsip->judul_arsip }}',
            url: url
        });
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(url);
        alert('Link dokumen telah disalin ke clipboard!');
    }
}
</script>
@endpush

@push('styles')
<style>
@media print {
    .no-print {
        display: none !important;
    }
}
</style>
@endpush
@endsection