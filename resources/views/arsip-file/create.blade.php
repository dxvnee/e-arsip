@extends('layouts.app')

@section('title', 'Upload File Digital')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold" style="color: #008e3c;">
                        <i class="fas fa-cloud-upload-alt mr-2"></i>Upload File Digital
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">
                        Upload file untuk Item: <strong>#{{ $itemArsip->nomor_item }}</strong>
                    </p>
                </div>
                <a href="{{ route('berkas-arsip.show', $itemArsip->berkas_arsip_id) }}"
                    class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Item Info -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-lg font-bold mb-4" style="color: #008e3c;">
                <i class="fas fa-file-alt mr-2"></i>Informasi Item
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Nomor Berkas:</span>
                    <span class="font-medium ml-2">{{ $itemArsip->berkasArsip->nomor_berkas }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Nomor Item:</span>
                    <span class="font-medium ml-2">{{ $itemArsip->nomor_item }}</span>
                </div>
                <div class="md:col-span-2">
                    <span class="text-gray-500">Uraian:</span>
                    <span class="font-medium ml-2">{{ $itemArsip->uraian_item }}</span>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <form action="{{ route('arsip-file.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="item_arsip_id" value="{{ $itemArsip->id }}">

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                    <i class="fas fa-upload mr-2"></i>Pilih File
                </h3>

                <!-- Drag & Drop Area -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        File Digital <span class="text-red-500">*</span>
                    </label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-green-500 transition-colors"
                        id="drop-zone">
                        <input type="file" name="files[]" id="file-input" multiple accept=".pdf,.jpg,.jpeg,.png"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="space-y-2">
                            <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center"
                                style="background-color: rgba(0, 142, 60, 0.1);">
                                <i class="fas fa-cloud-upload-alt text-3xl" style="color: #008e3c;"></i>
                            </div>
                            <p class="text-gray-600">
                                <span class="font-medium" style="color: #008e3c;">Klik untuk memilih file</span>
                                atau drag & drop di sini
                            </p>
                            <p class="text-xs text-gray-500">PDF, JPG, JPEG, PNG (Maks. 10MB per file)</p>
                        </div>
                    </div>
                    @error('files')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    @error('files.*')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Preview -->
                <div id="file-preview" class="hidden mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">File yang Dipilih</label>
                    <div id="file-list" class="space-y-2"></div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan
                    </label>
                    <textarea name="keterangan" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Catatan untuk file yang diupload...">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('berkas-arsip.show', $itemArsip->berkas_arsip_id) }}"
                        class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" id="upload-btn" disabled
                        class="px-6 py-3 text-white rounded-lg font-medium shadow-lg hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        style="background: linear-gradient(135deg, #008e3c 0%, #006b2d 100%);">
                        <i class="fas fa-upload mr-2"></i>Upload File
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const fileInput = document.getElementById('file-input');
                const filePreview = document.getElementById('file-preview');
                const fileList = document.getElementById('file-list');
                const uploadBtn = document.getElementById('upload-btn');
                const dropZone = document.getElementById('drop-zone');

                // File input change handler
                fileInput.addEventListener('change', function () {
                    updateFileList(this.files);
                });

                // Drag & drop handlers
                dropZone.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    this.classList.add('border-green-500', 'bg-green-50');
                });

                dropZone.addEventListener('dragleave', function (e) {
                    e.preventDefault();
                    this.classList.remove('border-green-500', 'bg-green-50');
                });

                dropZone.addEventListener('drop', function (e) {
                    e.preventDefault();
                    this.classList.remove('border-green-500', 'bg-green-50');
                    fileInput.files = e.dataTransfer.files;
                    updateFileList(e.dataTransfer.files);
                });

                function updateFileList(files) {
                    fileList.innerHTML = '';

                    if (files.length > 0) {
                        filePreview.classList.remove('hidden');
                        uploadBtn.disabled = false;

                        Array.from(files).forEach((file, index) => {
                            const ext = file.name.split('.').pop().toLowerCase();
                            let icon = 'fa-file';
                            let iconColor = 'text-gray-500';

                            if (ext === 'pdf') {
                                icon = 'fa-file-pdf';
                                iconColor = 'text-red-500';
                            } else if (['jpg', 'jpeg', 'png'].includes(ext)) {
                                icon = 'fa-file-image';
                                iconColor = 'text-blue-500';
                            }

                            const size = formatFileSize(file.size);

                            const div = document.createElement('div');
                            div.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border';
                            div.innerHTML = `
                            <div class="flex items-center">
                                <i class="fas ${icon} ${iconColor} text-xl mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">${file.name}</p>
                                    <p class="text-xs text-gray-500">${size}</p>
                                </div>
                            </div>
                            <span class="text-green-500"><i class="fas fa-check-circle"></i></span>
                        `;
                            fileList.appendChild(div);
                        });
                    } else {
                        filePreview.classList.add('hidden');
                        uploadBtn.disabled = true;
                    }
                }

                function formatFileSize(bytes) {
                    if (bytes >= 1048576) {
                        return (bytes / 1048576).toFixed(2) + ' MB';
                    } else if (bytes >= 1024) {
                        return (bytes / 1024).toFixed(2) + ' KB';
                    }
                    return bytes + ' bytes';
                }
            });
        </script>
    @endpush
@endsection