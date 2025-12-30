@extends('layouts.app')

@section('title', 'Detail Berkas Arsip')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold" style="color: #008e3c;">
                        <i class="fas fa-folder-open mr-2"></i>Detail Berkas
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">Informasi lengkap berkas dan item arsip</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('berkas-arsip.edit', $berkasArsip) }}"
                        class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('berkas-arsip.index') }}"
                        class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Berkas -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold mb-6" style="color: #008e3c;">
                        <i class="fas fa-info-circle mr-2"></i>Informasi Berkas
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-start border-b pb-4">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Nomor Berkas</div>
                            <div class="w-2/3">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-lg font-bold"
                                    style="background-color: rgba(0, 142, 60, 0.1); color: #008e3c;">
                                    {{ $berkasArsip->nomor_berkas }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-start border-b pb-4">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Klasifikasi</div>
                            <div class="w-2/3">
                                <p class="font-medium text-gray-900">{{ $berkasArsip->klasifikasiArsip->kode_klasifikasi }}
                                </p>
                                <p class="text-sm text-gray-600">{{ $berkasArsip->klasifikasiArsip->nama_klasifikasi }}</p>
                            </div>
                        </div>

                        <div class="flex items-start border-b pb-4">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Uraian Berkas</div>
                            <div class="w-2/3 text-gray-900">{{ $berkasArsip->uraian_berkas }}</div>
                        </div>

                        <div class="flex items-start border-b pb-4">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Tahun</div>
                            <div class="w-2/3 text-gray-900">{{ $berkasArsip->tahun }}</div>
                        </div>

                        <div class="flex items-start border-b pb-4">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Unit Kerja</div>
                            <div class="w-2/3 text-gray-900">{{ $berkasArsip->unit_kerja }}</div>
                        </div>

                        <div class="flex items-start border-b pb-4">
                            <div class="w-1/3 text-sm font-medium text-gray-500">Status</div>
                            <div class="w-2/3">
                                @php $badge = $berkasArsip->status_badge; @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge['bg'] }} {{ $badge['text'] }}">
                                    <i class="fas {{ $badge['icon'] }} mr-1"></i> {{ $berkasArsip->status_arsip }}
                                </span>
                            </div>
                        </div>

                        @if($berkasArsip->lokasiArsip)
                            <div class="flex items-start border-b pb-4">
                                <div class="w-1/3 text-sm font-medium text-gray-500">Lokasi Simpan</div>
                                <div class="w-2/3">
                                    <div class="flex items-center text-gray-900">
                                        <i class="fas fa-building text-gray-400 mr-2"></i>
                                        {{ $berkasArsip->lokasiArsip->gedung }} - {{ $berkasArsip->lokasiArsip->ruang }}
                                    </div>
                                    <div class="flex items-center mt-1 text-sm text-gray-600">
                                        <span class="mr-3"><i class="fas fa-th-large mr-1"></i> Rak:
                                            {{ $berkasArsip->lokasiArsip->rak }}</span>
                                        <span><i class="fas fa-box mr-1"></i> Boks: {{ $berkasArsip->lokasiArsip->boks }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($berkasArsip->keterangan)
                            <div class="flex items-start">
                                <div class="w-1/3 text-sm font-medium text-gray-500">Keterangan</div>
                                <div class="w-2/3 text-gray-700">{{ $berkasArsip->keterangan }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Daftar Item Arsip -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b-2 flex justify-between items-center"
                        style="background-color: rgba(0, 142, 60, 0.05); border-color: #008e3c;">
                        <h3 class="text-lg font-bold" style="color: #008e3c;">
                            <i class="fas fa-file-alt mr-2"></i>Item Arsip
                        </h3>
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $berkasArsip->item_count }} Item
                        </span>
                    </div>
                    <div class="p-6">
                        @if(count($itemList) > 0)
                            <div class="space-y-4">
                                @foreach($itemList as $item)
                                    <div
                                        class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-100">
                                        <div class="flex-shrink-0 mr-4">
                                            <div
                                                class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-green-600">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between">
                                                <p class="text-sm font-bold text-gray-900 truncate">{{ $item->nomor_item }}</p>
                                                <span class="text-xs text-gray-500">{{ $item->formatted_tanggal }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">{{ $item->uraian_item }}</p>
                                            <div class="mt-2 flex items-center text-xs text-gray-500 space-x-3">
                                                <span><i class="fas fa-copy mr-1"></i> {{ $item->jumlah }}
                                                    {{ $item->satuan }}</span>
                                                <span><i class="fas fa-info-circle mr-1"></i> {{ $item->kondisi }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($itemList->hasPages())
                                <div class="mt-4">
                                    {{ $itemList->links() }}
                                </div>
                            @endif

                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4"
                                    style="background-color: rgba(0, 142, 60, 0.1);">
                                    <i class="fas fa-file-medical text-3xl" style="color: #008e3c;"></i>
                                </div>
                                <p class="text-gray-500">Belum ada item arsip dalam berkas ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold mb-4" style="color: #008e3c;">
                        <i class="fas fa-cogs mr-2"></i>Aksi
                    </h3>

                    <div class="space-y-3">
                        <!-- TODO: Add route for creating item arsip -->
                        <button disabled
                            class="w-full flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                            <i class="fas fa-plus mr-2"></i>Tambah Item Arsip
                        </button>

                        <a href="{{ route('berkas-arsip.edit', $berkasArsip) }}"
                            class="w-full flex items-center justify-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit Berkas
                        </a>

                        @if($berkasArsip->canBeDeleted())
                            <form action="{{ route('berkas-arsip.destroy', $berkasArsip) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus berkas ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                    <i class="fas fa-trash mr-2"></i>Hapus Berkas
                                </button>
                            </form>
                        @else
                            <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-2"></i>
                                    <p class="text-xs text-yellow-700">
                                        Berkas tidak dapat dihapus karena masih memiliki item arsip.
                                    </p>
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
                            <span class="font-medium text-gray-700">#{{ $berkasArsip->id }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b">
                            <span class="text-gray-500">Dibuat</span>
                            <span class="font-medium text-gray-700">{{ $berkasArsip->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b">
                            <span class="text-gray-500">Diperbarui</span>
                            <span class="font-medium text-gray-700">{{ $berkasArsip->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection