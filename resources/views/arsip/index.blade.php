@extends('layouts.app')

@section('title', 'Daftar Arsip')

@section('content')
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
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $item->nomor_arsip }}</p>
                                <p class="text-sm text-gray-600">{{ Str::limit($item->judul_arsip, 50) }}</p>
                                @if($item->nomor_surat)
                                <p class="text-xs text-gray-500 mt-1">No. Surat: {{ $item->nomor_surat }}</p>
                                @endif
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
                        <td class="px-6 py-4 text-sm font-medium space-x-2">
                            <a href="{{ route('arsip.show', $item) }}" 
                               class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(auth()->user()->role !== 'viewer')
                            <a href="{{ route('arsip.edit', $item) }}" 
                               class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            @if($item->file_arsip)
                            <a href="{{ route('arsip.download', $item) }}" 
                               class="hover:text-green-900" style="color: #008e3c;" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            @endif
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
