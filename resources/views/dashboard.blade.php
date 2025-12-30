@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .chart-container {
        position: relative;
        height: 280px;
    }
    .progress-bar {
        transition: width 1s ease-in-out;
    }
    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-xl p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold mb-2">
                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard E-Arsip
                </h1>
                <p class="text-green-100 text-sm md:text-base">
                    Selamat datang, <span class="font-semibold">{{ auth()->user()->name ?? 'Admin' }}</span>! 
                    Berikut ringkasan sistem kearsipan Dinas Kesehatan.
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <div class="text-right hidden md:block">
                    <p class="text-sm text-green-100">Tanggal Hari Ini</p>
                    <p class="text-lg font-bold">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Total Berkas -->
        <div class="stat-card bg-white rounded-xl shadow-lg p-5 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total Berkas</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalBerkas) }}</p>
                    @if($berkasGrowth != 0)
                        <p class="text-xs mt-2 {{ $berkasGrowth > 0 ? 'text-green-600' : 'text-red-600' }}">
                            <i class="fas {{ $berkasGrowth > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                            {{ abs($berkasGrowth) }}% dari bulan lalu
                        </p>
                    @endif
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-folder-open text-xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Item -->
        <div class="stat-card bg-white rounded-xl shadow-lg p-5 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Total Item</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalItem) }}</p>
                    @if($itemGrowth != 0)
                        <p class="text-xs mt-2 {{ $itemGrowth > 0 ? 'text-green-600' : 'text-red-600' }}">
                            <i class="fas {{ $itemGrowth > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                            {{ abs($itemGrowth) }}% dari bulan lalu
                        </p>
                    @endif
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-alt text-xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total File Digital -->
        <div class="stat-card bg-white rounded-xl shadow-lg p-5 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">File Digital</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalFile) }}</p>
                    @if($fileGrowth != 0)
                        <p class="text-xs mt-2 {{ $fileGrowth > 0 ? 'text-green-600' : 'text-red-600' }}">
                            <i class="fas {{ $fileGrowth > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                            {{ abs($fileGrowth) }}% dari bulan lalu
                        </p>
                    @endif
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cloud-upload-alt text-xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Klasifikasi -->
        <div class="stat-card bg-white rounded-xl shadow-lg p-5 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Klasifikasi</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalKlasifikasi) }}</p>
                    <p class="text-xs mt-2 text-gray-500">Kategori aktif</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tags text-xl text-yellow-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Lokasi -->
        <div class="stat-card bg-white rounded-xl shadow-lg p-5 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Lokasi</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalLokasi) }}</p>
                    <p class="text-xs mt-2 text-gray-500">Penyimpanan aktif</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-xl text-orange-600"></i>
                </div>
            </div>
        </div>

        <!-- Storage -->
        <div class="stat-card bg-white rounded-xl shadow-lg p-5 border-l-4 border-pink-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Storage</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalStorageFormatted }}</p>
                    <p class="text-xs mt-2 text-gray-500">Terpakai</p>
                </div>
                <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-database text-xl text-pink-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Berkas & Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Status Arsip Cards -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                Status Arsip
            </h3>
            <div class="space-y-4">
                <!-- Aktif -->
                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Aktif</p>
                                <p class="text-xs text-gray-500">Arsip dalam penggunaan</p>
                            </div>
                        </div>
                        <span class="text-2xl font-bold text-green-600">{{ $berkasAktif }}</span>
                    </div>
                    <div class="w-full bg-green-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full progress-bar" style="width: {{ $totalBerkas > 0 ? ($berkasAktif / $totalBerkas) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <!-- Inaktif -->
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Inaktif</p>
                                <p class="text-xs text-gray-500">Arsip tidak aktif</p>
                            </div>
                        </div>
                        <span class="text-2xl font-bold text-yellow-600">{{ $berkasInaktif }}</span>
                    </div>
                    <div class="w-full bg-yellow-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full progress-bar" style="width: {{ $totalBerkas > 0 ? ($berkasInaktif / $totalBerkas) * 100 : 0 }}%"></div>
                    </div>
                </div>

                <!-- Permanen -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-archive text-white"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Permanen</p>
                                <p class="text-xs text-gray-500">Arsip statis</p>
                            </div>
                        </div>
                        <span class="text-2xl font-bold text-blue-600">{{ $berkasPermanen }}</span>
                    </div>
                    <div class="w-full bg-blue-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full progress-bar" style="width: {{ $totalBerkas > 0 ? ($berkasPermanen / $totalBerkas) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Trend Tahunan -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                Trend 5 Tahun Terakhir
            </h3>
            <div class="chart-container">
                <canvas id="yearlyChart"></canvas>
            </div>
        </div>

        <!-- Chart Status Pie -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-donut text-purple-600 mr-2"></i>
                Distribusi Status
            </h3>
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Klasifikasi & Unit Kerja -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Statistik Per Klasifikasi -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-layer-group text-green-600 mr-2"></i>
                    Berkas per Klasifikasi
                </h3>
                <a href="{{ route('klasifikasi-arsip.index') }}" class="text-sm text-green-600 hover:text-green-800">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-3">
                @forelse($klasifikasiStats as $stat)
                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700" title="{{ $stat['nama'] }}">
                                    <span class="text-green-600 font-bold">{{ $stat['kode'] }}</span>
                                    - {{ Str::limit($stat['nama'], 25) }}
                                </span>
                                <span class="text-sm font-bold text-gray-800">{{ $stat['count'] }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full progress-bar" 
                                    style="width: {{ $klasifikasiStats->max('count') > 0 ? ($stat['count'] / $klasifikasiStats->max('count')) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>Belum ada data klasifikasi</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Statistik Per Unit Kerja -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fas fa-building text-blue-600 mr-2"></i>
                    Berkas per Unit Kerja
                </h3>
            </div>
            <div class="space-y-3">
                @forelse($unitKerjaStats as $unit)
                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">{{ Str::limit($unit->unit_kerja, 30) }}</span>
                                <span class="text-sm font-bold text-gray-800">{{ $unit->total }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full progress-bar" 
                                    style="width: {{ $unitKerjaStats->max('total') > 0 ? ($unit->total / $unitKerjaStats->max('total')) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>Belum ada data unit kerja</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Activity & Item Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Aktivitas 7 Hari Terakhir -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-bar text-orange-600 mr-2"></i>
                Aktivitas 7 Hari Terakhir
            </h3>
            <div class="chart-container" style="height: 220px;">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <!-- Kondisi Fisik Item -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-heartbeat text-red-600 mr-2"></i>
                Kondisi Fisik Item
            </h3>
            <div class="flex flex-col items-center">
                <div class="relative w-40 h-40 mb-4">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#e5e7eb" stroke-width="12"/>
                        @php
                            $totalKondisi = $kondisiBaik + $kondisiRusak;
                            $baikPercent = $totalKondisi > 0 ? ($kondisiBaik / $totalKondisi) * 100 : 0;
                            $circumference = 2 * 3.14159 * 40;
                            $offset = $circumference - ($baikPercent / 100) * $circumference;
                        @endphp
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#22c55e" stroke-width="12"
                            stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}"
                            stroke-linecap="round"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-bold text-gray-800">{{ round($baikPercent) }}%</span>
                        <span class="text-xs text-gray-500">Baik</span>
                    </div>
                </div>
                <div class="flex space-x-6 text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-gray-600">Baik: <strong>{{ $kondisiBaik }}</strong></span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                        <span class="text-gray-600">Rusak: <strong>{{ $kondisiRusak }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jenis & Tingkat Perkembangan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Jenis Fisik -->
        @foreach($jenisFisikStats->take(2) as $jenis)
        <div class="bg-white rounded-xl shadow-lg p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">{{ $jenis->jenis_fisik }}</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($jenis->total) }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    @if(str_contains(strtolower($jenis->jenis_fisik), 'kertas'))
                        <i class="fas fa-file text-xl text-indigo-600"></i>
                    @elseif(str_contains(strtolower($jenis->jenis_fisik), 'foto'))
                        <i class="fas fa-image text-xl text-indigo-600"></i>
                    @else
                        <i class="fas fa-cube text-xl text-indigo-600"></i>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

        <!-- Tingkat Perkembangan -->
        @foreach($tingkatStats->take(2) as $tingkat)
        <div class="bg-white rounded-xl shadow-lg p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">{{ $tingkat->tingkat_perkembangan }}</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($tingkat->total) }}</p>
                </div>
                <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                    @if(str_contains(strtolower($tingkat->tingkat_perkembangan), 'asli'))
                        <i class="fas fa-certificate text-xl text-teal-600"></i>
                    @elseif(str_contains(strtolower($tingkat->tingkat_perkembangan), 'copy'))
                        <i class="fas fa-copy text-xl text-teal-600"></i>
                    @else
                        <i class="fas fa-file-signature text-xl text-teal-600"></i>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Latest Data & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Berkas Terbaru -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b-2 flex justify-between items-center" style="background-color: rgba(0, 142, 60, 0.05); border-color: #008e3c;">
                <h3 class="text-lg font-bold" style="color: #008e3c;">
                    <i class="fas fa-folder-open mr-2"></i>Berkas Terbaru
                </h3>
                <a href="{{ route('berkas-arsip.index') }}" class="text-sm text-green-600 hover:text-green-800">
                    Lihat Semua
                </a>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    @forelse($berkasLatest as $berkas)
                        <a href="{{ route('berkas-arsip.show', $berkas) }}" class="block p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-folder text-green-600"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $berkas->nomor_berkas }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Str::limit($berkas->uraian_berkas, 35) }}</p>
                                    <div class="flex items-center mt-1 text-xs text-gray-400">
                                        <span>{{ $berkas->created_at->diffForHumans() }}</span>
                                        <span class="mx-2">•</span>
                                        <span class="px-2 py-0.5 rounded text-xs {{ $berkas->status_arsip == 'Aktif' ? 'bg-green-100 text-green-700' : ($berkas->status_arsip == 'Inaktif' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                                            {{ $berkas->status_arsip }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p class="text-sm">Belum ada berkas</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Item Terbaru -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b-2 flex justify-between items-center" style="background-color: rgba(59, 130, 246, 0.05); border-color: #3b82f6;">
                <h3 class="text-lg font-bold text-blue-600">
                    <i class="fas fa-file-alt mr-2"></i>Item Terbaru
                </h3>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    @forelse($itemLatest as $item)
                        <div class="p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-file text-blue-600"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800">Item #{{ $item->nomor_item }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Str::limit($item->uraian_item, 35) }}</p>
                                    <div class="flex items-center mt-1 text-xs text-gray-400">
                                        <span>{{ $item->created_at->diffForHumans() }}</span>
                                        @if($item->nomor_surat)
                                            <span class="mx-2">•</span>
                                            <span>{{ Str::limit($item->nomor_surat, 15) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p class="text-sm">Belum ada item</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions & Info -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                    Aksi Cepat
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('berkas-arsip.create') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-xl hover:bg-green-100 transition-colors group">
                        <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-folder-plus text-white text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-700 text-center">Tambah Berkas</span>
                    </a>
                    <a href="{{ route('klasifikasi-arsip.create') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors group">
                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-plus-circle text-white text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-700 text-center">Klasifikasi Baru</span>
                    </a>
                    <a href="{{ route('lokasi-arsip.create') }}" class="flex flex-col items-center p-4 bg-orange-50 rounded-xl hover:bg-orange-100 transition-colors group">
                        <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-map-marker-alt text-white text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-700 text-center">Lokasi Baru</span>
                    </a>
                    <a href="{{ route('berkas-arsip.index') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors group">
                        <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-search text-white text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-700 text-center">Cari Arsip</span>
                    </a>
                </div>
            </div>

            <!-- Storage Info by Type -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-hdd text-pink-600 mr-2"></i>
                    Penggunaan Storage
                </h3>
                <div class="space-y-3">
                    @forelse($fileTypes as $type)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas {{ $type->tipe_file == 'pdf' ? 'fa-file-pdf text-red-500' : 'fa-file-image text-blue-500' }} mr-2"></i>
                                <span class="text-sm text-gray-600 uppercase">{{ $type->tipe_file }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-bold text-gray-800">{{ $type->total }} file</span>
                                <span class="text-xs text-gray-400 block">{{ $type->formatted_size }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-4">Belum ada file</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Lokasi Usage & Alert -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Lokasi Arsip Terpopuler -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-warehouse text-orange-600 mr-2"></i>
                Lokasi Penyimpanan Terbanyak
            </h3>
            <div class="space-y-3">
                @forelse($lokasiUsage as $lokasi)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-box text-orange-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $lokasi->gedung }} - {{ $lokasi->ruang }}</p>
                            <p class="text-xs text-gray-500">Rak {{ $lokasi->rak }} / Boks {{ $lokasi->boks }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-bold text-orange-600">{{ $lokasi->berkas_arsip_count }}</span>
                            <p class="text-xs text-gray-400">berkas</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-3xl mb-2"></i>
                        <p class="text-sm">Belum ada data lokasi</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Item Perlu Perhatian -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                Item Perlu Perhatian
            </h3>
            @if($itemRusakBerat->count() > 0)
                <div class="space-y-3">
                    @foreach($itemRusakBerat as $item)
                        <div class="flex items-center p-3 bg-red-50 rounded-lg border border-red-100">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-exclamation-circle text-red-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">Item #{{ $item->nomor_item }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Str::limit($item->uraian_item, 40) }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-700 rounded">
                                {{ $item->kondisi_fisik }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-3xl text-green-500"></i>
                    </div>
                    <p class="text-gray-500">Semua item dalam kondisi baik!</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart defaults
    Chart.defaults.font.family = 'Figtree, sans-serif';
    
    // Yearly Trend Chart
    const yearlyCtx = document.getElementById('yearlyChart').getContext('2d');
    new Chart(yearlyCtx, {
        type: 'line',
        data: {
            labels: @json($yearlyData['labels']),
            datasets: [{
                label: 'Berkas',
                data: @json($yearlyData['berkas']),
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#22c55e',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }, {
                label: 'Item',
                data: @json($yearlyData['items']),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Status Pie Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: @json($statusData['labels']),
            datasets: [{
                data: @json($statusData['data']),
                backgroundColor: @json($statusData['colors']),
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            cutout: '65%'
        }
    });

    // Activity Bar Chart
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    const activityData = @json($dailyActivity);
    new Chart(activityCtx, {
        type: 'bar',
        data: {
            labels: activityData.map(d => d.date),
            datasets: [{
                label: 'Berkas',
                data: activityData.map(d => d.berkas),
                backgroundColor: '#22c55e',
                borderRadius: 4
            }, {
                label: 'Item',
                data: activityData.map(d => d.items),
                backgroundColor: '#3b82f6',
                borderRadius: 4
            }, {
                label: 'File',
                data: activityData.map(d => d.files),
                backgroundColor: '#a855f7',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
@endpush
