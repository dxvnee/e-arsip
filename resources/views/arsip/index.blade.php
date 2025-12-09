@extends('layouts.app')

@section('title', 'Daftar Arsip')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
<div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md animate-slideDown">
    <div class="flex items-center">
        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
        <div>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md animate-slideDown">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
        <div>
            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
        <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
@endif

<div class="space-y-6">
    <!-- Header & Search -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold" style="color: #008e3c;">📁 Daftar Arsip</h2>
                <p class="text-gray-600 text-sm mt-1">Kelola dan cari arsip digital</p>
            </div>
            @if(auth()->user()->role !== 'viewer')
            <a href="{{ route('arsip.create') }}" 
               class="mt-4 lg:mt-0 inline-flex items-center px-6 py-3 rounded-lg text-white font-medium shadow-lg hover:shadow-xl transition-all duration-200"
               style="background: linear-gradient(135deg, #008e3c 0%, #006b2d 100%);">
                <i class="fas fa-plus mr-2"></i>
                Tambah Arsip
            </a>
            @endif
        </div>

        <!-- Search & Filter Form -->
        <form method="GET" action="{{ route('arsip.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari judul, nomor surat..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-opacity-50"
                           style="focus:ring-color: #008e3c;">
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Unit Kerja -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Kerja</label>
                    <select name="unit_kerja" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Semua Unit</option>
                        @foreach($unitKerja as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_kerja') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama_unit }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Arsip</label>
                    <select name="jenis" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Semua Jenis</option>
                        <option value="surat_masuk" {{ request('jenis') == 'surat_masuk' ? 'selected' : '' }}>Surat Masuk</option>
                        <option value="surat_keluar" {{ request('jenis') == 'surat_keluar' ? 'selected' : '' }}>Surat Keluar</option>
                        <option value="dokumen_internal" {{ request('jenis') == 'dokumen_internal' ? 'selected' : '' }}>Dokumen Internal</option>
                        <option value="laporan" {{ request('jenis') == 'laporan' ? 'selected' : '' }}>Laporan</option>
                        <option value="peraturan" {{ request('jenis') == 'peraturan' ? 'selected' : '' }}>Peraturan</option>
                        <option value="lainnya" {{ request('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="inaktif" {{ request('status') == 'inaktif' ? 'selected' : '' }}>Inaktif</option>
                        <option value="musnah" {{ request('status') == 'musnah' ? 'selected' : '' }}>Musnah</option>
                    </select>
                </div>

                <!-- Tanggal Dari -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Dari</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <!-- Tanggal Sampai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sampai</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <!-- Button Search -->
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full px-6 py-2 rounded-lg text-white font-medium shadow-md hover:shadow-lg transition-all"
                            style="background-color: #008e3c;">
                        <i class="fas fa-search mr-2"></i>
                        Cari
                    </button>
                </div>
            </div>

            @if(request()->hasAny(['search', 'kategori', 'unit_kerja', 'jenis', 'status', 'date_from', 'date_to']))
            <div class="flex items-center justify-between pt-2">
                <p class="text-sm text-gray-600">
                    <i class="fas fa-filter mr-1"></i>
                    Filter aktif: {{ collect(request()->except('page'))->filter()->count() }} parameter
                </p>
                <a href="{{ route('arsip.index') }}" class="text-sm font-medium hover:underline" style="color: #008e3c;">
                    <i class="fas fa-times-circle mr-1"></i>
                    Reset Filter
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Arsip -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4" style="border-color: #008e3c;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Arsip</p>
                    <p class="text-2xl font-bold" style="color: #008e3c;">{{ $arsip->total() }}</p>
                </div>
                <div class="w-12 h-12 rounded-full flex items-center justify-center"
                     style="background-color: rgba(0, 142, 60, 0.1);">
                    <i class="fas fa-archive text-xl" style="color: #008e3c;"></i>
                </div>
            </div>
        </div>
        
        <!-- Arsip Aktif -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Aktif</p>
                    <p class="text-2xl font-bold text-green-600">{{ \App\Models\Arsip::where('status', 'aktif')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl text-green-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Arsip Inaktif -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Inaktif</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ \App\Models\Arsip::where('status', 'inaktif')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-pause-circle text-xl text-yellow-600"></i>
                </div>
            </div>
        </div>
        
        <!-- File Digital -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">File Digital</p>
                    <p class="text-2xl font-bold text-blue-600">{{ \App\Models\Arsip::whereNotNull('file_arsip')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-file text-xl text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Arsip List -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b-2" style="background-color: rgba(0, 142, 60, 0.05); border-color: #008e3c;">
            <h3 class="text-lg font-bold" style="color: #008e3c;">
                Hasil: {{ $arsip->total() }} Arsip Ditemukan
            </h3>
        </div>

        @if($arsip->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead style="background-color: rgba(0, 142, 60, 0.05);">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nomor & Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($arsip as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-start justify-center items-center space-x-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">{{ $item->nomor_arsip }}</p>
                                    <p class="text-sm text-gray-600 truncate" title="{{ $item->judul_arsip }}">{{ Str::limit($item->judul_arsip, 50) }}</p>
                                    @if($item->nomor_surat)
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-hashtag mr-1"></i>{{ $item->nomor_surat }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                  style="background-color: {{ $item->kategori->warna_label ?? '#008e3c' }}22; color: {{ $item->kategori->warna_label ?? '#008e3c' }};">
                                {{ $item->kategori->nama_kategori ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ ucwords(str_replace('_', ' ', $item->jenis_arsip)) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $item->tanggal_surat->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'aktif' => 'bg-green-100 text-green-800',
                                    'inaktif' => 'bg-yellow-100 text-yellow-800',
                                    'musnah' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <!-- View Button -->
                                <a href="{{ route('arsip.show', $item->id) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs font-medium"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye mr-1.5"></i>
                                    <span class="hidden lg:inline">Detail</span>
                                </a>
                                
                                @if(auth()->user()->role !== 'viewer')
                                <!-- Edit Button -->
                                <a href="{{ route('arsip.edit', $item->id) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors text-xs font-medium"
                                   title="Edit Arsip">
                                    <i class="fas fa-edit mr-1.5"></i>
                                    <span class="hidden lg:inline">Edit</span>
                                </a>
                                @endif
                                
                                @if(auth()->user()->role === 'admin')
                                <!-- Delete Button -->
                                <form action="{{ route('arsip.destroy', $item->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs font-medium"
                                            title="Hapus Arsip">
                                        <i class="fas fa-trash mr-1.5"></i>
                                        <span class="hidden lg:inline">Hapus</span>
                                    </button>
                                </form>
                                @endif

                                @if($item->file_arsip)
                                    <!-- Download Button (icon only) -->
                                    <a href="{{ route('arsip.download', $item->id) }}" 
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-white hover:shadow-lg transition-all"
                                    style="background-color: #008e3c;"
                                    title="Download File">
                                        <i class="fas fa-download"></i>
                                    </a>

                                @endif

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $arsip->links() }}
        </div>
        @else
        <div class="px-6 py-12 text-center">
            <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Tidak ada arsip ditemukan</p>
            <p class="text-gray-400 text-sm mt-2">Coba ubah filter pencarian Anda</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slideDown {
        animation: slideDown 0.3s ease-out;
    }
    
    /* Hover effect for table rows */
    tbody tr:hover {
        background-color: rgba(0, 142, 60, 0.02);
    }
    
    /* Smooth transitions */
    a, button {
        transition: all 0.2s ease;
    }
    
    /* File type icon colors */
    .fa-file-pdf {
        color: #dc2626;
    }
    
    .fa-file-word {
        color: #2563eb;
    }
    
    .fa-file-excel {
        color: #16a34a;
    }
    
    .fa-file-image {
        color: #9333ea;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-hide success/error messages after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.animate-slideDown');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 5000);
    
    // Confirm delete with sweet alert style
    document.querySelectorAll('form[onsubmit*="confirm"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('⚠️ Apakah Anda yakin ingin menghapus arsip ini?\n\nTindakan ini tidak dapat dibatalkan!')) {
                this.submit();
            }
        });
    });
</script>
@endpush
