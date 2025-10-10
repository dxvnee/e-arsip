@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-bold" style="color: #008e3c;">📊 Laporan & Statistik</h2>
        <p class="text-gray-600 text-sm mt-1">Generate dan lihat berbagai laporan arsip</p>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Laporan Arsip Masuk/Keluar -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(0, 142, 60, 0.1);">
                        <i class="fas fa-exchange-alt text-2xl" style="color: #008e3c;"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-gray-900">Arsip Masuk/Keluar</h3>
                        <p class="text-sm text-gray-600">Laporan per periode</p>
                    </div>
                </div>

                <form action="{{ route('laporan.arsip-masuk-keluar') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2"
                               style="focus:ring-color: #008e3c;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2"
                               style="focus:ring-color: #008e3c;">
                    </div>
                    <button type="submit" 
                            class="w-full px-4 py-2 rounded-lg text-white font-medium shadow-md hover:shadow-lg transition-all"
                            style="background-color: #008e3c;">
                        <i class="fas fa-file-alt mr-2"></i>
                        Lihat Laporan
                    </button>
                </form>
            </div>
        </div>

        <!-- Statistik Arsip -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(239, 216, 86, 0.2);">
                        <i class="fas fa-chart-pie text-2xl" style="color: #d4b93a;"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-gray-900">Statistik Arsip</h3>
                        <p class="text-sm text-gray-600">Breakdown lengkap</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="text-sm text-gray-600">
                        Lihat statistik arsip berdasarkan:
                    </p>
                    <ul class="text-sm text-gray-700 space-y-1 ml-4">
                        <li><i class="fas fa-check-circle mr-2" style="color: #008e3c;"></i>Status (Aktif, Inaktif, Musnah)</li>
                        <li><i class="fas fa-check-circle mr-2" style="color: #008e3c;"></i>Jenis Arsip</li>
                        <li><i class="fas fa-check-circle mr-2" style="color: #008e3c;"></i>Kategori</li>
                        <li><i class="fas fa-check-circle mr-2" style="color: #008e3c;"></i>Unit Kerja</li>
                    </ul>
                    <a href="{{ route('laporan.statistik') }}" 
                       class="block w-full px-4 py-2 rounded-lg text-white font-medium text-center shadow-md hover:shadow-lg transition-all"
                       style="background-color: #d4b93a;">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Lihat Statistik
                    </a>
                </div>
            </div>
        </div>

        <!-- Laporan Aktivitas -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-blue-100">
                        <i class="fas fa-history text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-gray-900">Log Aktivitas</h3>
                        <p class="text-sm text-gray-600">Riwayat aktivitas user</p>
                    </div>
                </div>

                <form action="{{ route('laporan.aktivitas') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-blue-600 rounded-lg text-white font-medium shadow-md hover:bg-blue-700 transition-all">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Aktivitas
                    </button>
                </form>
            </div>
        </div>

        <!-- Laporan Disposisi -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-purple-100">
                        <i class="fas fa-paper-plane text-2xl text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-gray-900">Laporan Disposisi</h3>
                        <p class="text-sm text-gray-600">Status & tracking</p>
                    </div>
                </div>

                <form action="{{ route('laporan.disposisi') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    </div>
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-purple-600 rounded-lg text-white font-medium shadow-md hover:bg-purple-700 transition-all">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Lihat Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Laporan dapat diunduh dalam format PDF atau Excel (coming soon)</li>
                        <li>Data laporan diupdate secara real-time</li>
                        <li>Semua aktivitas tercatat dalam log sistem</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
