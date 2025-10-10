@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="rounded-xl p-8 text-white shadow-lg" style="background: linear-gradient(135deg, #008e3c 0%, #006b2d 100%);">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-full flex items-center justify-center" style="background-color: #efd856;">
                <i class="fas fa-hand-sparkles text-3xl" style="color: #008e3c;"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold mb-1">Selamat Datang, {{ auth()->user()->name }}!</h2>
                <p class="text-sm" style="color: #efd856;">Sistem Informasi E-Arsip Dinas Kesehatan</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Arsip -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 transform hover:scale-105 transition-transform duration-200" style="border-color: #008e3c;">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-lg p-4" style="background-color: rgba(0, 142, 60, 0.1);">
                    <i class="fas fa-file-alt text-3xl" style="color: #008e3c;"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Arsip</p>
                    <p class="text-3xl font-bold" style="color: #008e3c;">{{ $stats['total_arsip'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Arsip Bulan Ini -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 transform hover:scale-105 transition-transform duration-200" style="border-color: #efd856;">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-lg p-4" style="background-color: rgba(239, 216, 86, 0.2);">
                    <i class="fas fa-calendar-alt text-3xl" style="color: #d4b93a;"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Arsip Bulan Ini</p>
                    <p class="text-3xl font-bold" style="color: #d4b93a;">{{ $stats['arsip_bulan_ini'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Total Unit Kerja -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500 transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-4">
                    <i class="fas fa-building text-blue-600 text-3xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Unit Kerja</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_unit'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500 transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-4">
                    <i class="fas fa-users text-purple-600 text-3xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pengguna Aktif</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['total_users'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Arsip Terbaru -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b-2" style="background-color: rgba(0, 142, 60, 0.05); border-color: #008e3c;">
                <h3 class="text-lg font-bold" style="color: #008e3c;">📄 Arsip Terbaru</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse($arsipTerbaru ?? [] as $arsip)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ Str::limit($arsip->judul_arsip, 40) }}</p>
                                <div class="flex items-center mt-1 text-xs text-gray-500 space-x-4">
                                    <span><i class="fas fa-folder mr-1"></i> {{ $arsip->kategori->nama_kategori ?? '-' }}</span>
                                    <span><i class="fas fa-calendar mr-1"></i> {{ $arsip->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <a href="{{ route('arsip.show', $arsip) }}" class="text-primary hover:text-primary-dark">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center">Belum ada arsip</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Statistik Per Jenis Arsip -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b-2" style="background-color: rgba(239, 216, 86, 0.2); border-color: #efd856;">
                <h3 class="text-lg font-bold" style="color: #d4b93a;">📊 Arsip Per Jenis</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @php
                        $jenisLabels = [
                            'surat_masuk' => 'Surat Masuk',
                            'surat_keluar' => 'Surat Keluar',
                            'dokumen_internal' => 'Dokumen Internal',
                            'laporan' => 'Laporan',
                            'peraturan' => 'Peraturan',
                            'lainnya' => 'Lainnya'
                        ];
                        $totalArsip = collect($arsipPerJenis ?? [])->sum('total');
                    @endphp
                    @forelse($arsipPerJenis ?? [] as $jenis)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-3" style="background-color: #008e3c;"></div>
                                <span class="text-sm font-medium text-gray-700">{{ $jenisLabels[$jenis->jenis_arsip] ?? $jenis->jenis_arsip }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-32 bg-gray-200 rounded-full h-3">
                                    <div class="h-3 rounded-full" 
                                         style="background-color: #008e3c; width: {{ $totalArsip > 0 ? ($jenis->total / $totalArsip * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-bold w-10 text-right" style="color: #008e3c;">{{ $jenis->total }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center">Belum ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b-2" style="background-color: rgba(0, 142, 60, 0.05); border-color: #008e3c;">
            <h3 class="text-lg font-bold" style="color: #008e3c;">🕒 Aktivitas Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($aktivitasTerbaru ?? [] as $aktivitas)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $aktivitas->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $aktivitas->user->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badges = [
                                        'create' => 'bg-green-100 text-green-800',
                                        'read' => 'bg-blue-100 text-blue-800',
                                        'update' => 'bg-yellow-100 text-yellow-800',
                                        'delete' => 'bg-red-100 text-red-800',
                                        'download' => 'bg-purple-100 text-purple-800',
                                        'login' => 'bg-gray-100 text-gray-800',
                                        'logout' => 'bg-gray-100 text-gray-800',
                                    ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badges[$aktivitas->aksi] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($aktivitas->aksi) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ Str::limit($aktivitas->deskripsi, 50) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada aktivitas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
