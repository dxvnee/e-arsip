@extends('layouts.app')

@section('title', 'Berkas Arsip')

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
                    <h2 class="text-2xl font-bold" style="color: #008e3c;">📂 Berkas Arsip</h2>
                    <p class="text-gray-600 text-sm mt-1">Kelola data berkas dan map arsip</p>
                </div>
                <a href="{{ route('berkas-arsip.create') }}"
                    class="mt-4 lg:mt-0 inline-flex items-center px-6 py-3 rounded-lg text-white font-medium shadow-lg hover:shadow-xl transition-all duration-200"
                    style="background: linear-gradient(135deg, #008e3c 0%, #006b2d 100%);">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Berkas
                </a>
            </div>

            <!-- Search & Filter Form -->
            <form method="GET" action="{{ route('berkas-arsip.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nomor, uraian, atau unit kerja..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <select name="tahun"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Tahun</option>
                            @foreach($tahunList as $t)
                                <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Arsip</label>
                        <select name="status_arsip"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Status</option>
                            <option value="Aktif" {{ request('status_arsip') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Inaktif" {{ request('status_arsip') == 'Inaktif' ? 'selected' : '' }}>Inaktif
                            </option>
                            <option value="Permanen" {{ request('status_arsip') == 'Permanen' ? 'selected' : '' }}>Permanen
                            </option>
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

                @if(request()->hasAny(['search', 'tahun', 'status_arsip', 'klasifikasi', 'unit_kerja']))
                    <div class="flex items-center justify-between pt-2">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-filter mr-1"></i>
                            Filter aktif: {{ collect(request()->except('page'))->filter()->count() }} parameter
                        </p>
                        <a href="{{ route('berkas-arsip.index') }}" class="text-sm font-medium hover:underline"
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
            <!-- Total Berkas -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4" style="border-color: #008e3c;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Berkas</p>
                        <p class="text-2xl font-bold" style="color: #008e3c;">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center"
                        style="background-color: rgba(0, 142, 60, 0.1);">
                        <i class="fas fa-folder text-xl" style="color: #008e3c;"></i>
                    </div>
                </div>
            </div>

            <!-- Aktif -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Berkas Aktif</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['aktif'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Inaktif -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Berkas Inaktif</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['inaktif'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-xl text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <!-- Permanen -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Berkas Permanen</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['permanen'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-archive text-xl text-blue-600"></i>
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
                                Nomor Berkas
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Uraian Informasi
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Tahun
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Unit Kerja
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                                Item
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($berkas as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900">{{ $item->nomor_berkas }}</span>
                                        <span
                                            class="text-xs text-gray-500">{{ $item->klasifikasiArsip->kode_klasifikasi ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 font-medium">{{ Str::limit($item->uraian_berkas, 50) }}
                                    </div>
                                    @if($item->lokasiArsip)
                                        <div class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-map-marker-alt mr-1 text-red-400"></i>
                                            {{ $item->lokasiArsip->gedung }} - {{ $item->lokasiArsip->ruang }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $item->tahun }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ Str::limit($item->unit_kerja, 20) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php $badge = $item->status_badge; @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge['bg'] }} {{ $badge['text'] }}">
                                        <i class="fas {{ $badge['icon'] }} mr-1"></i> {{ $item->status_arsip }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $item->item_count }} Item
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- View -->
                                        <a href="{{ route('berkas-arsip.show', $item) }}"
                                            class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('berkas-arsip.edit', $item) }}"
                                            class="p-2 text-yellow-600 hover:bg-yellow-100 rounded-lg transition-colors"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Delete -->
                                        @if($item->canBeDeleted())
                                            <form action="{{ route('berkas-arsip.destroy', $item) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus berkas ini?');">
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
                                                title="Tidak dapat dihapus - masih memiliki item arsip">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4"
                                            style="background-color: rgba(0, 142, 60, 0.1);">
                                            <i class="fas fa-folder-open text-3xl" style="color: #008e3c;"></i>
                                        </div>
                                        <p class="text-gray-500 text-lg font-medium">Tidak ada data berkas</p>
                                        <p class="text-gray-400 text-sm mt-1">Belum ada berkas arsip yang terdaftar</p>
                                        <a href="{{ route('berkas-arsip.create') }}"
                                            class="mt-4 inline-flex items-center px-4 py-2 rounded-lg text-white font-medium"
                                            style="background-color: #008e3c;">
                                            <i class="fas fa-plus mr-2"></i>
                                            Tambah Berkas
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($berkas->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $berkas->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection