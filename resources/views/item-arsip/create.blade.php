@extends('layouts.app')

@section('title', 'Tambah Item Arsip')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold" style="color: #008e3c;">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Item Arsip
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">
                        Menambahkan item ke berkas: <strong>{{ $berkas->nomor_berkas }}</strong>
                    </p>
                </div>
                <a href="{{ route('berkas-arsip.show', $berkas->id) }}"
                    class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('item-arsip.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="berkas_arsip_id" value="{{ $berkas->id }}">

            <!-- Informasi Item -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                    <i class="fas fa-file-alt mr-2"></i>Informasi Item
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nomor Item -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Item <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nomor_item" value="{{ old('nomor_item', $nextNumber) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('nomor_item') border-red-500 @enderror">
                        @error('nomor_item')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Surat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('tanggal_surat') border-red-500 @enderror">
                        @error('tanggal_surat')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Surat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Surat
                        </label>
                        <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('nomor_surat') border-red-500 @enderror"
                            placeholder="Nomor surat (jika ada)">
                        @error('nomor_surat')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Asal Surat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Asal Surat
                        </label>
                        <input type="text" name="asal_surat" value="{{ old('asal_surat') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('asal_surat') border-red-500 @enderror"
                            placeholder="Instansi / Pengirim">
                        @error('asal_surat')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Uraian Item -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Uraian Item <span class="text-red-500">*</span>
                        </label>
                        <textarea name="uraian_item" rows="3" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('uraian_item') border-red-500 @enderror"
                            placeholder="Deskripsi isi item arsip...">{{ old('uraian_item') }}</textarea>
                        @error('uraian_item')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Detail Fisik -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                    <i class="fas fa-box-open mr-2"></i>Detail Fisik
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Jumlah Eksemplar -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Eksemplar <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="jumlah_eksemplar" value="{{ old('jumlah_eksemplar', 1) }}" required
                            min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('jumlah_eksemplar') border-red-500 @enderror">
                        @error('jumlah_eksemplar')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tingkat Perkembangan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tingkat Perkembangan <span class="text-red-500">*</span>
                        </label>
                        <select name="tingkat_perkembangan" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="Asli" {{ old('tingkat_perkembangan') == 'Asli' ? 'selected' : '' }}>Asli</option>
                            <option value="Copy" {{ old('tingkat_perkembangan') == 'Copy' ? 'selected' : '' }}>Copy</option>
                            <option value="Tembusan" {{ old('tingkat_perkembangan') == 'Tembusan' ? 'selected' : '' }}>
                                Tembusan</option>
                            <option value="Salinan" {{ old('tingkat_perkembangan') == 'Salinan' ? 'selected' : '' }}>Salinan
                            </option>
                            <option value="Petikan" {{ old('tingkat_perkembangan') == 'Petikan' ? 'selected' : '' }}>Petikan
                            </option>
                        </select>
                        @error('tingkat_perkembangan')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Fisik -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Fisik <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_fisik" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="Tekstual" {{ old('jenis_fisik') == 'Tekstual' ? 'selected' : '' }}>Tekstual
                            </option>
                            <option value="Kartografik" {{ old('jenis_fisik') == 'Kartografik' ? 'selected' : '' }}>
                                Kartografik</option>
                            <option value="Kearsitekturan" {{ old('jenis_fisik') == 'Kearsitekturan' ? 'selected' : '' }}>
                                Kearsitekturan</option>
                            <option value="Audio Visual" {{ old('jenis_fisik') == 'Audio Visual' ? 'selected' : '' }}>Audio
                                Visual</option>
                            <option value="Elektronik" {{ old('jenis_fisik') == 'Elektronik' ? 'selected' : '' }}>Elektronik
                            </option>
                        </select>
                        @error('jenis_fisik')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kondisi Fisik -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kondisi Fisik <span class="text-red-500">*</span>
                        </label>
                        <select name="kondisi_fisik" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="Baik" {{ old('kondisi_fisik') == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ old('kondisi_fisik') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak
                                Ringan</option>
                            <option value="Rusak Berat" {{ old('kondisi_fisik') == 'Rusak Berat' ? 'selected' : '' }}>Rusak
                                Berat</option>
                        </select>
                        @error('kondisi_fisik')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea name="keterangan" rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('berkas-arsip.show', $berkas->id) }}"
                        class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 text-white rounded-lg font-medium shadow-lg hover:shadow-xl transition-all"
                        style="background: linear-gradient(135deg, #008e3c 0%, #006b2d 100%);">
                        <i class="fas fa-save mr-2"></i>Simpan Item
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection