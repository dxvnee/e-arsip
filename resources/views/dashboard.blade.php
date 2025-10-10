@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-primary to-primary-dark rounded-lg p-6 text-white">
        <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}!</h2>
        <p class="text-gray-100">Sistem Informasi E-Arsip Dinas Kesehatan</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Arsip -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded-lg p-3">
                    <i class="fas fa-file-alt text-primary text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Arsip</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_arsip'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Arsip Bulan Ini -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-secondary bg-opacity-20 rounded-lg p-3">
                    <i class="fas fa-calendar-alt text-secondary-dark text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Arsip Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['arsip_bulan_ini'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Total Unit Kerja -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                    <i class="fas fa-building text-blue-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Unit Kerja</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_unit'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                    <i class="fas fa-users text-purple-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Pengguna Aktif</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Arsip Terbaru -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Arsip Terbaru</h3>
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
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Arsip Per Jenis</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
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
                                <div class="w-2 h-2 bg-primary rounded-full mr-2"></div>
                                <span class="text-sm text-gray-700">{{ $jenisLabels[$jenis->jenis_arsip] ?? $jenis->jenis_arsip }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary h-2 rounded-full" 
                                         style="width: {{ $totalArsip > 0 ? ($jenis->total / $totalArsip * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-800 w-10 text-right">{{ $jenis->total }}</span>
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
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
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
