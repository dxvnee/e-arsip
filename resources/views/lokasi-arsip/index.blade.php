@extends('layouts.app')

@section('title', 'Lokasi Arsip')

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
                    <h2 class="text-2xl font-bold" style="color: #008e3c;">📍 Lokasi Arsip</h2>
                    <p class="text-gray-600 text-sm mt-1">Kelola lokasi penyimpanan arsip fisik</p>
                </div>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('lokasi-arsip.create') }}"
                        class="mt-4 lg:mt-0 inline-flex items-center px-6 py-3 rounded-lg text-white font-medium shadow-lg hover:shadow-xl transition-all duration-200"
                        style="background: linear-gradient(135deg, #008e3c 0%, #006b2d 100%);">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Lokasi
                    </a>
                @endif
            </div>

            <!-- Search & Filter Form -->
            <form method="GET" action="{{ route('lokasi-arsip.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari lokasi..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <!-- Gedung -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gedung</label>
                        <select name="gedung"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Gedung</option>
                            @foreach($gedungList as $gedung)
                                <option value="{{ $gedung }}" {{ request('gedung') == $gedung ? 'selected' : '' }}>{{ $gedung }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ruang -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ruang</label>
                        <select name="ruang"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Ruang</option>
                            @foreach($ruangList as $ruang)
                                <option value="{{ $ruang }}" {{ request('ruang') == $ruang ? 'selected' : '' }}>{{ $ruang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
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

                @if(request()->hasAny(['search', 'gedung', 'ruang', 'status']))
                    <div class="flex items-center justify-between pt-2">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-filter mr-1"></i>
                            Filter aktif: {{ collect(request()->except('page'))->filter()->count() }} parameter
                        </p>
                        <a href="{{ route('lokasi-arsip.index') }}" class="text-sm font-medium hover:underline"
                            style="color: #008e3c;">
                            <i class="fas fa-times-circle mr-1"></i>
                            Reset Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Lokasi -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4" style="border-color: #008e3c;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Lokasi</p>
                        <p class="text-2xl font-bold" style="color: #008e3c;">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center"
                        style="background-color: rgba(0, 142, 60, 0.1);">
                        <i class="fas fa-map-marker-alt text-xl" style="color: #008e3c;"></i>
                    </div>
                </div>
            </div>

            <!-- Aktif -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Aktif</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Terpakai -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Terpakai</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['in_use'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-archive text-xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Gedung -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Gedung</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['total_gedung'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-building text-xl text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead style="background-color: #008e3c;">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Kode
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Gedung
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Ruang
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                                Rak
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                                Boks
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                                Arsip
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($lokasi as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold"
                                        style="background-color: rgba(0, 142, 60, 0.1); color: #008e3c;">
                                        {{ $item->kode_lokasi }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-building text-gray-400 mr-2"></i>
                                        <span class="text-sm font-medium text-gray-900">{{ $item->gedung }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-door-open text-gray-400 mr-2"></i>
                                        <span class="text-sm text-gray-700">{{ $item->ruang }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-th-large mr-1"></i> {{ $item->rak }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-box mr-1"></i> {{ $item->boks }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->arsip_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                        <i class="fas fa-file-alt mr-1"></i> {{ $item->arsip_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->is_active)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-times mr-1"></i> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- View -->
                                        <a href="{{ route('lokasi-arsip.show', $item) }}"
                                            class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if(auth()->user()->role === 'admin')
                                            <!-- Edit -->
                                            <a href="{{ route('lokasi-arsip.edit', $item) }}"
                                                class="p-2 text-yellow-600 hover:bg-yellow-100 rounded-lg transition-colors"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Toggle Status -->
                                            <form action="{{ route('lokasi-arsip.toggle-status', $item) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="p-2 {{ $item->is_active ? 'text-gray-600 hover:bg-gray-100' : 'text-green-600 hover:bg-green-100' }} rounded-lg transition-colors"
                                                    title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fas {{ $item->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                </button>
                                            </form>

                                            <!-- Delete -->
                                            @if($item->arsip_count == 0)
                                                <form action="{{ route('lokasi-arsip.destroy', $item) }}" method="POST" class="inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                                                        title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="p-2 text-gray-300 cursor-not-allowed"
                                                    title="Tidak dapat dihapus - masih digunakan">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4"
                                            style="background-color: rgba(0, 142, 60, 0.1);">
                                            <i class="fas fa-map-marker-alt text-3xl" style="color: #008e3c;"></i>
                                        </div>
                                        <p class="text-gray-500 text-lg font-medium">Tidak ada data lokasi</p>
                                        <p class="text-gray-400 text-sm mt-1">Belum ada lokasi arsip yang terdaftar</p>
                                        @if(auth()->user()->role === 'admin')
                                            <a href="{{ route('lokasi-arsip.create') }}"
                                                class="mt-4 inline-flex items-center px-4 py-2 rounded-lg text-white font-medium"
                                                style="background-color: #008e3c;">
                                                <i class="fas fa-plus mr-2"></i>
                                                Tambah Lokasi
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($lokasi->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $lokasi->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection