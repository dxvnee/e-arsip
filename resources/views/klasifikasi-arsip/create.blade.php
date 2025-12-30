@extends('layouts.app')

@section('title', 'Tambah Kode Klasifikasi Arsip')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold" style="color: #008e3c;">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Kode Klasifikasi
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">Input kode klasifikasi arsip dan jadwal retensi</p>
                </div>
                <a href="{{ route('klasifikasi-arsip.index') }}"
                    class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('klasifikasi-arsip.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Informasi Klasifikasi -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                    <i class="fas fa-folder-tree mr-2"></i>Informasi Klasifikasi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Klasifikasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Klasifikasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode_klasifikasi" value="{{ old('kode_klasifikasi') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('kode_klasifikasi') border-red-500 @enderror"
                            placeholder="Contoh: 443.1, 421.2">
                        @error('kode_klasifikasi')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Format kode mengikuti tata naskah dinas</p>
                    </div>

                    <!-- Nama Klasifikasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Klasifikasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_klasifikasi" value="{{ old('nama_klasifikasi') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('nama_klasifikasi') border-red-500 @enderror"
                            placeholder="Masukkan nama klasifikasi">
                        @error('nama_klasifikasi')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('deskripsi') border-red-500 @enderror"
                            placeholder="Masukkan deskripsi klasifikasi (opsional)">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Jadwal Retensi -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                    <i class="fas fa-clock mr-2"></i>Jadwal Retensi Arsip
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Retensi Aktif -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Retensi Aktif (Tahun) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="retensi_aktif" value="{{ old('retensi_aktif', 0) }}" required min="0"
                                max="100"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('retensi_aktif') border-red-500 @enderror"
                                placeholder="0">
                            <span class="absolute right-4 top-2 text-gray-400">tahun</span>
                        </div>
                        @error('retensi_aktif')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Masa arsip di unit pengolah</p>
                    </div>

                    <!-- Retensi Inaktif -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Retensi Inaktif (Tahun) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="retensi_inaktif" value="{{ old('retensi_inaktif', 0) }}" required
                                min="0" max="100"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('retensi_inaktif') border-red-500 @enderror"
                                placeholder="0">
                            <span class="absolute right-4 top-2 text-gray-400">tahun</span>
                        </div>
                        @error('retensi_inaktif')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Masa arsip di records center</p>
                    </div>

                    <!-- Nasib Akhir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nasib Akhir <span class="text-red-500">*</span>
                        </label>
                        <div class="flex space-x-4">
                            <label
                                class="flex items-center p-3 border rounded-lg cursor-pointer transition-all {{ old('nasib_akhir', 'musnah') === 'musnah' ? 'border-red-500 bg-red-50' : 'border-gray-300 hover:border-red-300' }}">
                                <input type="radio" name="nasib_akhir" value="musnah"
                                    class="mr-2 text-red-600 focus:ring-red-500" {{ old('nasib_akhir', 'musnah') === 'musnah' ? 'checked' : '' }}>
                                <span class="text-sm">
                                    <i class="fas fa-fire text-red-500 mr-1"></i> Musnah
                                </span>
                            </label>
                            <label
                                class="flex items-center p-3 border rounded-lg cursor-pointer transition-all {{ old('nasib_akhir') === 'permanen' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-blue-300' }}">
                                <input type="radio" name="nasib_akhir" value="permanen"
                                    class="mr-2 text-blue-600 focus:ring-blue-500" {{ old('nasib_akhir') === 'permanen' ? 'checked' : '' }}>
                                <span class="text-sm">
                                    <i class="fas fa-infinity text-blue-500 mr-1"></i> Permanen
                                </span>
                            </label>
                        </div>
                        @error('nasib_akhir')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                        <div class="text-sm text-blue-700">
                            <p class="font-medium">Informasi Jadwal Retensi:</p>
                            <ul class="mt-1 list-disc list-inside space-y-1">
                                <li><strong>Retensi Aktif:</strong> Masa penyimpanan arsip di unit kerja pengolah</li>
                                <li><strong>Retensi Inaktif:</strong> Masa penyimpanan arsip di records center/pusat arsip
                                </li>
                                <li><strong>Musnah:</strong> Arsip akan dimusnahkan setelah masa retensi berakhir</li>
                                <li><strong>Permanen:</strong> Arsip akan disimpan selamanya sebagai arsip statis</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status & Submit -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked
                                class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan klasifikasi ini</span>
                        </label>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('klasifikasi-arsip.index') }}"
                            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-3 text-white rounded-lg font-medium shadow-lg hover:shadow-xl transition-all"
                            style="background: linear-gradient(135deg, #008e3c 0%, #006b2d 100%);">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection