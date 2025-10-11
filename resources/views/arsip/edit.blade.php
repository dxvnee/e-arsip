@extends('layouts.app')

@section('title', 'Edit Arsip')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold" style="color: #008e3c;">
                    <i class="fas fa-edit mr-2"></i>Edit Arsip
                </h2>
                <p class="text-gray-600 text-sm mt-1">Perbarui data arsip #{{ $arsip->id }}</p>
            </div>
            <a href="{{ route('arsip.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Version Warning if exists -->
    @if($arsip->versions && $arsip->versions->count() > 0)
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-400 text-xl mr-3"></i>
            <div>
                <p class="text-sm font-medium text-blue-800">
                    Arsip ini memiliki {{ $arsip->versions->count() }} versi dokumen
                </p>
                <p class="text-xs text-blue-600 mt-1">
                    Perubahan akan membuat versi baru dari dokumen ini
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Form -->
    <form action="{{ route('arsip.update', $arsip->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Informasi Dasar -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                <i class="fas fa-info-circle mr-2"></i>Informasi Dasar
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nomor Surat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Surat <span class="text-gray-400">(Optional)</span>
                    </label>
                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $arsip->nomor_surat) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('nomor_surat') border-red-500 @enderror"
                           placeholder="Contoh: 001/SK/2024">
                    @error('nomor_surat')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Judul Arsip -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Arsip <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul_arsip" value="{{ old('judul_arsip', $arsip->judul_arsip) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('judul_arsip') border-red-500 @enderror"
                           placeholder="Masukkan judul arsip">
                    @error('judul_arsip')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Arsip -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Arsip <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_arsip" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('jenis_arsip') border-red-500 @enderror">
                        <option value="">-- Pilih Jenis Arsip --</option>
                        <option value="surat_masuk" {{ old('jenis_arsip', $arsip->jenis_arsip) == 'surat_masuk' ? 'selected' : '' }}>
                            📥 Surat Masuk
                        </option>
                        <option value="surat_keluar" {{ old('jenis_arsip', $arsip->jenis_arsip) == 'surat_keluar' ? 'selected' : '' }}>
                            📤 Surat Keluar
                        </option>
                        <option value="dokumen_internal" {{ old('jenis_arsip', $arsip->jenis_arsip) == 'dokumen_internal' ? 'selected' : '' }}>
                            📋 Dokumen Internal
                        </option>
                        <option value="laporan" {{ old('jenis_arsip', $arsip->jenis_arsip) == 'laporan' ? 'selected' : '' }}>
                            📊 Laporan
                        </option>
                        <option value="peraturan" {{ old('jenis_arsip', $arsip->jenis_arsip) == 'peraturan' ? 'selected' : '' }}>
                            📜 Peraturan
                        </option>
                        <option value="lainnya" {{ old('jenis_arsip', $arsip->jenis_arsip) == 'lainnya' ? 'selected' : '' }}>
                            📄 Lainnya
                        </option>
                    </select>
                    @error('jenis_arsip')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Arsip -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status Arsip <span class="text-red-500">*</span>
                    </label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="aktif" 
                                   {{ old('status', $arsip->status) == 'aktif' ? 'checked' : '' }}
                                   class="mr-2 text-green-600 focus:ring-green-500">
                            <span class="text-sm">🟢 Aktif</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="inaktif" 
                                   {{ old('status', $arsip->status) == 'inaktif' ? 'checked' : '' }}
                                   class="mr-2 text-yellow-600 focus:ring-yellow-500">
                            <span class="text-sm">🟡 Inaktif</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="musnah" 
                                   {{ old('status', $arsip->status) == 'musnah' ? 'checked' : '' }}
                                   class="mr-2 text-red-600 focus:ring-red-500">
                            <span class="text-sm">🔴 Musnah</span>
                        </label>
                    </div>
                    @error('status')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Detail Surat -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                <i class="fas fa-envelope mr-2"></i>Detail Surat
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tanggal Surat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $arsip->tanggal_surat) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('tanggal_surat') border-red-500 @enderror">
                    @error('tanggal_surat')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Diterima -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Diterima
                    </label>
                    <input type="date" name="tanggal_diterima" value="{{ old('tanggal_diterima', $arsip->tanggal_diterima) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Pengirim/Asal Surat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pengirim/Asal Surat
                    </label>
                    <input type="text" name="pengirim" value="{{ old('pengirim', $arsip->pengirim) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                           placeholder="Nama pengirim atau instansi asal">
                </div>

                <!-- Penerima/Tujuan Surat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Penerima/Tujuan Surat
                    </label>
                    <input type="text" name="penerima" value="{{ old('penerima', $arsip->penerima) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                           placeholder="Nama penerima atau unit tujuan">
                </div>

                <!-- Perihal -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Perihal
                    </label>
                    <input type="text" name="perihal" value="{{ old('perihal', $arsip->perihal) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                           placeholder="Perihal surat">
                </div>
            </div>
        </div>

        <!-- Klasifikasi -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                <i class="fas fa-folder mr-2"></i>Klasifikasi
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori Arsip <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('kategori_id') border-red-500 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id', $arsip->kategori_id) == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                                (Retensi: {{ $kat->masa_retensi }} tahun)
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit Kerja -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Unit Kerja <span class="text-red-500">*</span>
                    </label>
                    <select name="unit_kerja_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('unit_kerja_id') border-red-500 @enderror">
                        <option value="">-- Pilih Unit Kerja --</option>
                        @foreach($unitKerja as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_kerja_id', $arsip->unit_kerja_id) == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama_unit }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_kerja_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lokasi Fisik -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Lokasi Fisik Arsip
                    </label>
                    <input type="text" name="lokasi_fisik" value="{{ old('lokasi_fisik', $arsip->lokasi_fisik) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                           placeholder="Contoh: Rak A1-02">
                </div>

                <!-- Tags -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tags/Label
                    </label>
                    <input type="text" name="tags" value="{{ old('tags', $arsip->tags) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                           placeholder="Contoh: covid, urgent, laporan">
                    <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma</p>
                </div>
            </div>
        </div>

        <!-- Deskripsi & Keterangan -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                <i class="fas fa-align-left mr-2"></i>Deskripsi & Keterangan
            </h3>
            
            <div class="space-y-6">
                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Arsip
                    </label>
                    <textarea name="deskripsi" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Deskripsi singkat tentang arsip ini">{{ old('deskripsi', $arsip->deskripsi) }}</textarea>
                </div>

                <!-- Isi Ringkas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Isi Ringkas/Keterangan Tambahan
                    </label>
                    <textarea name="isi_ringkas" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Ringkasan isi dokumen atau keterangan tambahan yang diperlukan">{{ old('isi_ringkas', $arsip->isi_ringkas) }}</textarea>
                </div>
            </div>
        </div>

        <!-- File Management -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                <i class="fas fa-file mr-2"></i>Dokumen Digital
            </h3>
            
            <!-- Current File -->
            @if($arsip->file_path)
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    File Saat Ini
                </label>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-file-pdf text-red-500 text-3xl mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ basename($arsip->file_path) }}</p>
                            <p class="text-xs text-gray-500">Ukuran: {{ $arsip->file_size ? number_format($arsip->file_size / 1024 / 1024, 2) . ' MB' : 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('arsip.preview', $arsip->id) }}" target="_blank" 
                           class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                            <i class="fas fa-eye mr-1"></i>Preview
                        </a>
                        <a href="{{ route('arsip.download', $arsip->id) }}" 
                           class="px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200">
                            <i class="fas fa-download mr-1"></i>Download
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Upload New Version -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Upload Versi Baru 
                    <span class="text-gray-400">(Optional - Biarkan kosong jika tidak ingin mengubah file)</span>
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-500 transition-colors"
                     id="dropZone">
                    <i class="fas fa-cloud-upload-alt text-4xl mb-3" style="color: #008e3c;"></i>
                    <p class="text-sm text-gray-600 mb-3">
                        Upload file baru akan membuat versi baru dari dokumen
                    </p>
                    <input type="file" name="file" id="fileInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                           class="hidden" onchange="displayFileName(this)">
                    <label for="fileInput" class="inline-block px-4 py-2 rounded-lg text-white font-medium cursor-pointer hover:shadow-lg transition-all"
                           style="background-color: #008e3c;">
                        <i class="fas fa-upload mr-2"></i>
                        Pilih File Baru
                    </label>
                    <p class="text-xs text-gray-400 mt-2">Maximum file size: 10MB</p>
                </div>

                <!-- New File Preview -->
                <div id="filePreview" class="hidden mt-4">
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-file text-3xl mr-3" style="color: #008e3c;"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-700" id="fileName"></p>
                                <p class="text-xs text-gray-500" id="fileSize"></p>
                            </div>
                        </div>
                        <button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times-circle text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Version Note -->
                <div class="mt-4" id="versionNote" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Perubahan Versi
                    </label>
                    <textarea name="version_note" rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                              placeholder="Jelaskan perubahan yang dilakukan pada dokumen ini"></textarea>
                </div>
            </div>
        </div>

        <!-- Document Versions History -->
        @if($arsip->versions && $arsip->versions->count() > 0)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                <i class="fas fa-history mr-2"></i>Riwayat Versi Dokumen
            </h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Versi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diubah Oleh</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">File</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($arsip->versions->sortByDesc('created_at') as $version)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                    v{{ $version->version_number }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $version->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $version->user->name ?? 'System' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $version->change_note ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <a href="{{ route('arsip.version.download', ['arsip' => $arsip->id, 'version' => $version->id]) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-500">
                    <span class="text-red-500">*</span> Wajib diisi
                </p>
                <div class="space-x-3">
                    <a href="{{ route('arsip.index') }}" 
                       class="inline-block px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 rounded-lg text-white font-medium shadow-lg hover:shadow-xl transition-all"
                            style="background-color: #008e3c;">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function displayFileName(input) {
    const file = input.files[0];
    if (file) {
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        document.getElementById('filePreview').classList.remove('hidden');
        document.getElementById('versionNote').style.display = 'block';
    }
}

function removeFile() {
    document.getElementById('fileInput').value = '';
    document.getElementById('filePreview').classList.add('hidden');
    document.getElementById('versionNote').style.display = 'none';
}

// Drag and Drop
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('fileInput');

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-green-500', 'bg-green-50');
});

dropZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-green-500', 'bg-green-50');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-green-500', 'bg-green-50');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        displayFileName(fileInput);
    }
});
</script>
@endpush
@endsection