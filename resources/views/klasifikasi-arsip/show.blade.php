@extends('layouts.app')

@section('title', 'Detail Kode Klasifikasi Arsip')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold" style="color: #008e3c;">
                        <i class="fas fa-folder-tree mr-2"></i>Detail Klasifikasi
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">Informasi lengkap kode klasifikasi arsip</p>
                </div>
                <div class="flex space-x-2">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('klasifikasi-arsip.edit', $klasifikasiArsip) }}"
                            class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                    @endif
                    <a href="{{ route('klasifikasi-arsip.index') }}"
                        class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Klasifikasi -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                        <i class="fas fa-info-circle mr-2"></i>Informasi Klasifikasi
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-start border-b pb-4">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Kode Klasifikasi</div>
                            <div class="w-2/3">
                                <span class="inline-flex items-center px-4 py-2 rounded-lg text-lg font-bold"
                                    style="background-color: rgba(0, 142, 60, 0.1); color: #008e3c;">
                                    {{ $klasifikasiArsip->kode_klasifikasi }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-start border-b pb-4">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Nama Klasifikasi</div>
                            <div class="w-2/3 text-gray-900 font-medium">{{ $klasifikasiArsip->nama_klasifikasi }}</div>
                        </div>

                        <div class="flex items-start border-b pb-4">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Deskripsi</div>
                            <div class="w-2/3 text-gray-700">
                                {{ $klasifikasiArsip->deskripsi ?: '-' }}
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Status</div>
                            <div class="w-2/3">
                                @if($klasifikasiArsip->is_active)
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
                </div>

                <!-- Jadwal Retensi -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                        <i class="fas fa-clock mr-2"></i>Jadwal Retensi Arsip
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Retensi Aktif -->
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-blue-600">{{ $klasifikasiArsip->retensi_aktif }}</div>
                            <div class="text-sm text-blue-600 font-medium">Tahun</div>
                            <div class="text-xs text-blue-500 mt-1">Retensi Aktif</div>
                        </div>

                        <!-- Retensi Inaktif -->
                        <div class="bg-yellow-50 rounded-lg p-4 text-center">
                            <div class="text-3xl font-bold text-yellow-600">{{ $klasifikasiArsip->retensi_inaktif }}</div>
                            <div class="text-sm text-yellow-600 font-medium">Tahun</div>
                            <div class="text-xs text-yellow-500 mt-1">Retensi Inaktif</div>
                        </div>

                        <!-- Total Retensi -->
                        <div class="rounded-lg p-4 text-center" style="background-color: rgba(0, 142, 60, 0.1);">
                            <div class="text-3xl font-bold" style="color: #008e3c;">{{ $klasifikasiArsip->total_retensi }}
                            </div>
                            <div class="text-sm font-medium" style="color: #008e3c;">Tahun</div>
                            <div class="text-xs mt-1" style="color: #006b2d;">Total Retensi</div>
                        </div>
                    </div>

                    <!-- Nasib Akhir -->
                    <div
                        class="mt-6 p-4 rounded-lg {{ $klasifikasiArsip->nasib_akhir === 'musnah' ? 'bg-red-50 border border-red-200' : 'bg-blue-50 border border-blue-200' }}">
                        <div class="flex items-center justify-between">
                            <div>
                                <div
                                    class="text-sm font-medium {{ $klasifikasiArsip->nasib_akhir === 'musnah' ? 'text-red-700' : 'text-blue-700' }}">
                                    Nasib Akhir Arsip
                                </div>
                                <div
                                    class="text-lg font-bold mt-1 {{ $klasifikasiArsip->nasib_akhir === 'musnah' ? 'text-red-800' : 'text-blue-800' }}">
                                    @if($klasifikasiArsip->nasib_akhir === 'musnah')
                                        <i class="fas fa-fire mr-2"></i>MUSNAH
                                    @else
                                        <i class="fas fa-infinity mr-2"></i>PERMANEN
                                    @endif
                                </div>
                            </div>
                            <div
                                class="w-16 h-16 rounded-full flex items-center justify-center {{ $klasifikasiArsip->nasib_akhir === 'musnah' ? 'bg-red-100' : 'bg-blue-100' }}">
                                @if($klasifikasiArsip->nasib_akhir === 'musnah')
                                    <i class="fas fa-fire text-3xl text-red-500"></i>
                                @else
                                    <i class="fas fa-infinity text-3xl text-blue-500"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Info -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold mb-4" style="color: #008e3c;">
                        <i class="fas fa-history mr-2"></i>Informasi Sistem
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center py-2 border-b">
                            <span class="text-gray-500">ID</span>
                            <span class="font-medium text-gray-700">#{{ $klasifikasiArsip->id }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b">
                            <span class="text-gray-500">Dibuat</span>
                            <span
                                class="font-medium text-gray-700">{{ $klasifikasiArsip->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b">
                            <span class="text-gray-500">Diperbarui</span>
                            <span
                                class="font-medium text-gray-700">{{ $klasifikasiArsip->updated_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-500">Jam</span>
                            <span class="font-medium text-gray-700">{{ $klasifikasiArsip->updated_at->format('H:i') }}
                                WIB</span>
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
                            <a href="{{ route('klasifikasi-arsip.edit', $klasifikasiArsip) }}"
                                class="w-full flex items-center justify-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors">
                                <i class="fas fa-edit mr-2"></i>Edit Klasifikasi
                            </a>

                            <form action="{{ route('klasifikasi-arsip.toggle-status', $klasifikasiArsip) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="w-full flex items-center justify-center px-4 py-2 {{ $klasifikasiArsip->is_active ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg transition-colors">
                                    <i
                                        class="fas {{ $klasifikasiArsip->is_active ? 'fa-toggle-off' : 'fa-toggle-on' }} mr-2"></i>
                                    {{ $klasifikasiArsip->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            <form action="{{ route('klasifikasi-arsip.destroy', $klasifikasiArsip) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus klasifikasi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                    <i class="fas fa-trash mr-2"></i>Hapus Klasifikasi
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection