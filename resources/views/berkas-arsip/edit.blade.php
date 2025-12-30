@extends('layouts.app')

@section('title', 'Edit Berkas Arsip')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold" style="color: #008e3c;">
                        <i class="fas fa-edit mr-2"></i>Edit Berkas Arsip
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">Edit data berkas:
                        <strong>{{ $berkasArsip->nomor_berkas }}</strong></p>
                </div>
                <a href="{{ route('berkas-arsip.index') }}"
                    class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('berkas-arsip.update', $berkasArsip) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Informasi Utama -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Berkas
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Klasifikasi -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Klasifikasi Arsip <span class="text-red-500">*</span>
                        </label>
                        <select name="kode_klasifikasi_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 select2">
                            <option value="">-- Pilih Klasifikasi --</option>
                            @foreach($klasifikasiList as $klasifikasi)
                                <option value="{{ $klasifikasi->id }}" {{ old('kode_klasifikasi_id', $berkasArsip->kode_klasifikasi_id) == $klasifikasi->id ? 'selected' : '' }}>
                                    {{ $klasifikasi->kode_klasifikasi }} - {{ $klasifikasi->nama_klasifikasi }}
                                </option>
                            @endforeach
                        </select>
                        @error('kode_klasifikasi_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Berkas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Berkas
                        </label>
                        <input type="text" name="nomor_berkas" value="{{ old('nomor_berkas', $berkasArsip->nomor_berkas) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('nomor_berkas') border-red-500 @enderror">
                        @error('nomor_berkas')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tahun <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="tahun" value="{{ old('tahun', $berkasArsip->tahun) }}" required
                            min="1900" max="{{ date('Y') + 1 }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('tahun') border-red-500 @enderror">
                        @error('tahun')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Uraian Berkas -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Uraian Berkas <span class="text-red-500">*</span>
                        </label>
                        <textarea name="uraian_berkas" rows="3" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('uraian_berkas') border-red-500 @enderror"
                            placeholder="Deskripsi isi berkas...">{{ old('uraian_berkas', $berkasArsip->uraian_berkas) }}</textarea>
                        @error('uraian_berkas')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kurun Waktu -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kurun Waktu
                        </label>
                        <input type="text" name="kurun_waktu" value="{{ old('kurun_waktu', $berkasArsip->kurun_waktu) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('kurun_waktu') border-red-500 @enderror"
                            placeholder="Contoh: Januari - Desember 2023">
                        @error('kurun_waktu')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Kerja -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Unit Kerja / Pengolah <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="unit_kerja" value="{{ old('unit_kerja', $berkasArsip->unit_kerja) }}"
                            required list="unit-kerja-list"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('unit_kerja') border-red-500 @enderror"
                            placeholder="Nama Unit Kerja">
                        <datalist id="unit-kerja-list">
                            @foreach($unitKerjaList as $unit)
                                <option value="{{ $unit }}">
                            @endforeach
                        </datalist>
                        @error('unit_kerja')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status & Lokasi -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                    <i class="fas fa-map-marker-alt mr-2"></i>Status & Lokasi Simpan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status Arsip -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status Arsip <span class="text-red-500">*</span>
                        </label>
                        <select name="status_arsip" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="Aktif" {{ old('status_arsip', $berkasArsip->status_arsip) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Inaktif" {{ old('status_arsip', $berkasArsip->status_arsip) == 'Inaktif' ? 'selected' : '' }}>Inaktif</option>
                            <option value="Permanen" {{ old('status_arsip', $berkasArsip->status_arsip) == 'Permanen' ? 'selected' : '' }}>Permanen</option>
                        </select>
                        @error('status_arsip')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lokasi Arsip -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi Simpan
                        </label>
                        <select name="lokasi_arsip_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">-- Pilih Lokasi (Opsional) --</option>
                            @foreach($lokasiList as $lokasi)
                                <option value="{{ $lokasi->id }}" {{ old('lokasi_arsip_id', $berkasArsip->lokasi_arsip_id) == $lokasi->id ? 'selected' : '' }}>
                                    {{ $lokasi->gedung }} - {{ $lokasi->ruang }} (Rak: {{ $lokasi->rak }}, Boks:
                                    {{ $lokasi->boks }})
                                </option>
                            @endforeach
                        </select>
                        @error('lokasi_arsip_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan Tambahan
                        </label>
                        <textarea name="keterangan" rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            placeholder="Catatan tambahan...">{{ old('keterangan', $berkasArsip->keterangan) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('berkas-arsip.index') }}"
                        class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 text-white rounded-lg font-medium shadow-lg hover:shadow-xl transition-all"
                        style="background: linear-gradient(135deg, #008e3c 0%, #006b2d 100%);">
                        <i class="fas fa-save mr-2"></i>Perbarui Berkas
                    </button>
                </div>
            </div>
        </form>

        <!-- Info Terakhir Diperbarui -->
        <div class="mt-4 text-center text-sm text-gray-500">
            Terakhir diperbarui: {{ $berkasArsip->updated_at->format('d F Y, H:i') }} WIB
        </div>
    </div>
@endsection