@extends('layouts.app')

@section('title', 'Detail Lokasi Arsip')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold" style="color: #008e3c;">
                        <i class="fas fa-map-marker-alt mr-2"></i>Detail Lokasi
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">Informasi lengkap lokasi penyimpanan arsip</p>
                </div>
                <div class="flex space-x-2">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('lokasi-arsip.edit', $lokasiArsip) }}"
                            class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                    @endif
                    <a href="{{ route('lokasi-arsip.index') }}"
                        class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Lokasi -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                        <i class="fas fa-info-circle mr-2"></i>Informasi Lokasi
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-start border-b pb-4">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Kode Lokasi</div>
                            <div class="w-2/3">
                                <span class="inline-flex items-center px-4 py-2 rounded-lg text-lg font-bold"
                                    style="background-color: rgba(0, 142, 60, 0.1); color: #008e3c;">
                                    {{ $lokasiArsip->kode_lokasi }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-start border-b pb-4">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Status</div>
                            <div class="w-2/3">
                                @if($lokasiArsip->is_active)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-times-circle mr-1"></i> Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Location Path -->
                    <div class="mt-6 p-4 rounded-lg" style="background-color: rgba(0, 142, 60, 0.05);">
                        <p class="text-sm font-medium text-gray-600 mb-3">Struktur Lokasi:</p>
                        <div class="flex items-center flex-wrap gap-2">
                            <div class="flex items-center bg-white px-3 py-2 rounded-lg shadow-sm">
                                <i class="fas fa-building text-blue-500 mr-2"></i>
                                <span class="text-sm font-medium">{{ $lokasiArsip->gedung }}</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                            <div class="flex items-center bg-white px-3 py-2 rounded-lg shadow-sm">
                                <i class="fas fa-door-open text-green-500 mr-2"></i>
                                <span class="text-sm font-medium">{{ $lokasiArsip->ruang }}</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                            <div class="flex items-center bg-white px-3 py-2 rounded-lg shadow-sm">
                                <i class="fas fa-th-large text-yellow-500 mr-2"></i>
                                <span class="text-sm font-medium">Rak {{ $lokasiArsip->rak }}</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                            <div class="flex items-center bg-white px-3 py-2 rounded-lg shadow-sm">
                                <i class="fas fa-box text-purple-500 mr-2"></i>
                                <span class="text-sm font-medium">Boks {{ $lokasiArsip->boks }}</span>
                            </div>
                        </div>
                    </div>

                    @if($lokasiArsip->keterangan)
                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-500 mb-2">Keterangan:</p>
                            <p class="text-gray-700">{{ $lokasiArsip->keterangan }}</p>
                        </div>
                    @endif
                </div>

                <!-- Daftar Arsip di Lokasi Ini -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b-2"
                        style="background-color: rgba(0, 142, 60, 0.05); border-color: #008e3c;">
                        <h3 class="text-lg font-bold" style="color: #008e3c;">
                            <i class="fas fa-file-alt mr-2"></i>Arsip di Lokasi Ini
                        </h3>
                    </div>
                    <div class="p-6">
                        @if(count($arsipList) > 0)
                            <div class="space-y-3">
                                @foreach($arsipList as $arsip)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ Str::limit($arsip->judul_arsip, 50) }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <span class="mr-3"><i
                                                        class="fas fa-hashtag mr-1"></i>{{ $arsip->nomor_arsip }}</span>
                                                <span><i
                                                        class="fas fa-calendar mr-1"></i>{{ $arsip->created_at->format('d/m/Y') }}</span>
                                            </p>
                                        </div>
                                        @if(Route::has('arsip.show'))
                                            <a href="{{ route('arsip.show', $arsip) }}" class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if($lokasiArsip->arsip_count > 10)
                                <div class="mt-4 text-center">
                                    <p class="text-sm text-gray-500">
                                        Menampilkan 10 dari {{ $lokasiArsip->arsip_count }} arsip
                                    </p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4"
                                    style="background-color: rgba(0, 142, 60, 0.1);">
                                    <i class="fas fa-folder-open text-3xl" style="color: #008e3c;"></i>
                                </div>
                                <p class="text-gray-500">Belum ada arsip di lokasi ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold mb-4" style="color: #008e3c;">
                        <i class="fas fa-chart-pie mr-2"></i>Statistik
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-file-alt text-blue-600"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Total Arsip</span>
                            </div>
                            <span class="text-xl font-bold text-blue-600">{{ $lokasiArsip->arsip_count }}</span>
                        </div>

                        @if($lokasiArsip->kapasitas > 0)
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-box text-green-600"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Kapasitas</span>
                                </div>
                                <span class="text-xl font-bold text-green-600">{{ $lokasiArsip->kapasitas }}</span>
                            </div>

                            <div class="p-3 bg-gray-50 rounded-lg">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Terpakai</span>
                                    <span
                                        class="font-medium">{{ round(($lokasiArsip->arsip_count / $lokasiArsip->kapasitas) * 100) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full"
                                        style="background-color: #008e3c; width: {{ min(100, ($lokasiArsip->arsip_count / $lokasiArsip->kapasitas) * 100) }}%">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- System Info -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold mb-4" style="color: #008e3c;">
                        <i class="fas fa-history mr-2"></i>Informasi Sistem
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center py-2 border-b">
                            <span class="text-gray-500">ID</span>
                            <span class="font-medium text-gray-700">#{{ $lokasiArsip->id }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b">
                            <span class="text-gray-500">Dibuat</span>
                            <span class="font-medium text-gray-700">{{ $lokasiArsip->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b">
                            <span class="text-gray-500">Diperbarui</span>
                            <span class="font-medium text-gray-700">{{ $lokasiArsip->updated_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-500">Jam</span>
                            <span class="font-medium text-gray-700">{{ $lokasiArsip->updated_at->format('H:i') }} WIB</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if(auth()->user()->role === 'admin')
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold mb-4" style="color: #008e3c;">
                            <i class="fas fa-cogs mr-2"></i>Aksi
                        </h3>

                        <div class="space-y-3">
                            <a href="{{ route('lokasi-arsip.edit', $lokasiArsip) }}"
                                class="w-full flex items-center justify-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors">
                                <i class="fas fa-edit mr-2"></i>Edit Lokasi
                            </a>

                            <form action="{{ route('lokasi-arsip.toggle-status', $lokasiArsip) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full flex items-center justify-center px-4 py-2 {{ $lokasiArsip->is_active ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg transition-colors">
                                    <i class="fas {{ $lokasiArsip->is_active ? 'fa-toggle-off' : 'fa-toggle-on' }} mr-2"></i>
                                    {{ $lokasiArsip->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            @if($lokasiArsip->arsip_count == 0)
                                <form action="{{ route('lokasi-arsip.destroy', $lokasiArsip) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                        <i class="fas fa-trash mr-2"></i>Hapus Lokasi
                                    </button>
                                </form>
                            @else
                                <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-2"></i>
                                        <p class="text-xs text-yellow-700">
                                            Lokasi tidak dapat dihapus karena masih digunakan oleh {{ $lokasiArsip->arsip_count }}
                                            arsip.
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection