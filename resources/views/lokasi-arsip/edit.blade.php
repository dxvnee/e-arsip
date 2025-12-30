@extends('layouts.app')

@section('title', 'Edit Lokasi Arsip')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold" style="color: #008e3c;">
                        <i class="fas fa-edit mr-2"></i>Edit Lokasi Arsip
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">Edit lokasi: <strong>{{ $lokasiArsip->kode_lokasi }}</strong></p>
                </div>
                <a href="{{ route('lokasi-arsip.index') }}"
                    class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('lokasi-arsip.update', $lokasiArsip) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Informasi Lokasi -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                    <i class="fas fa-map-marker-alt mr-2"></i>Informasi Lokasi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Gedung -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Gedung <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="gedung" value="{{ old('gedung', $lokasiArsip->gedung) }}" required
                            list="gedung-list"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('gedung') border-red-500 @enderror"
                            placeholder="Contoh: Gedung A, Gedung Utama">
                        <datalist id="gedung-list">
                            @foreach($gedungList as $gedung)
                                <option value="{{ $gedung }}">
                            @endforeach
                        </datalist>
                        @error('gedung')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ruang -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ruang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="ruang" value="{{ old('ruang', $lokasiArsip->ruang) }}" required
                            list="ruang-list"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('ruang') border-red-500 @enderror"
                            placeholder="Contoh: Ruang Arsip 1, Ruang Server">
                        <datalist id="ruang-list">
                            @foreach($ruangList as $ruang)
                                <option value="{{ $ruang }}">
                            @endforeach
                        </datalist>
                        @error('ruang')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rak -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Rak <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="rak" value="{{ old('rak', $lokasiArsip->rak) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('rak') border-red-500 @enderror"
                            placeholder="Contoh: R1, A01, Rak-1">
                        @error('rak')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Nomor atau kode rak penyimpanan</p>
                    </div>

                    <!-- Boks -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Boks <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="boks" value="{{ old('boks', $lokasiArsip->boks) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('boks') border-red-500 @enderror"
                            placeholder="Contoh: B1, 001, Boks-A">
                        @error('boks')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Nomor atau kode boks/kotak arsip</p>
                    </div>

                    <!-- Kapasitas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kapasitas
                        </label>
                        <div class="relative">
                            <input type="number" name="kapasitas" value="{{ old('kapasitas', $lokasiArsip->kapasitas) }}"
                                min="0" max="9999"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('kapasitas') border-red-500 @enderror"
                                placeholder="0">
                            <span class="absolute right-4 top-2 text-gray-400">arsip</span>
                        </div>
                        @error('kapasitas')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Kapasitas maksimal arsip (opsional)</p>
                    </div>

                    <!-- Kode Lokasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Lokasi
                        </label>
                        <input type="text" name="kode_lokasi" value="{{ old('kode_lokasi', $lokasiArsip->kode_lokasi) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('kode_lokasi') border-red-500 @enderror"
                            placeholder="Kode lokasi">
                        @error('kode_lokasi')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea name="keterangan" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('keterangan') border-red-500 @enderror"
                            placeholder="Keterangan tambahan tentang lokasi (opsional)">{{ old('keterangan', $lokasiArsip->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status & Submit -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $lokasiArsip->is_active) ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan lokasi ini</span>
                        </label>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('lokasi-arsip.index') }}"
                            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-3 text-white rounded-lg font-medium shadow-lg hover:shadow-xl transition-all"
                            style="background: linear-gradient(135deg, #008e3c 0%, #006b2d 100%);">
                            <i class="fas fa-save mr-2"></i>Perbarui
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Info Terakhir Diperbarui -->
        <div class="mt-4 text-center text-sm text-gray-500">
            Terakhir diperbarui: {{ $lokasiArsip->updated_at->format('d F Y, H:i') }} WIB
        </div>
    </div>
@endsection