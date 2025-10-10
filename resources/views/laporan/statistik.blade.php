@extends('layouts.app')

@section('title', 'Statistik Arsip')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold" style="color: #008e3c;">📊 Statistik Arsip</h2>
                <p class="text-gray-600 text-sm mt-1">Overview lengkap data arsip</p>
            </div>
            <a href="{{ route('laporan.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <p class="text-sm font-medium text-gray-600 mb-2">Total Arsip</p>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4" style="border-color: #008e3c;">
            <p class="text-sm font-medium text-gray-600 mb-2">Aktif</p>
            <p class="text-3xl font-bold" style="color: #008e3c;">{{ $stats['aktif'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <p class="text-sm font-medium text-gray-600 mb-2">Inaktif</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['inaktif'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
            <p class="text-sm font-medium text-gray-600 mb-2">Musnah</p>
            <p class="text-3xl font-bold text-red-600">{{ $stats['musnah'] }}</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Per Jenis -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold mb-4" style="color: #008e3c;">Arsip Per Jenis</h3>
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
                @endphp
                @foreach($perJenis as $jenis)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700">{{ $jenisLabels[$jenis->jenis_arsip] ?? $jenis->jenis_arsip }}</span>
                        <span class="font-bold" style="color: #008e3c;">{{ $jenis->total }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full" style="background-color: #008e3c; width: {{ $stats['total'] > 0 ? ($jenis->total / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Per Kategori -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold mb-4" style="color: #d4b93a;">Arsip Per Kategori</h3>
            <div class="space-y-3">
                @foreach($perKategori as $kategori)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700">{{ $kategori->nama_kategori }}</span>
                        <span class="font-bold" style="color: #d4b93a;">{{ $kategori->total }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full" style="background-color: #d4b93a; width: {{ $stats['total'] > 0 ? ($kategori->total / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Per Unit Kerja -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-bold mb-4 text-blue-600">Arsip Per Unit Kerja</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($perUnitKerja as $unit)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $unit->nama_unit }}</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $unit->total }}</p>
                    </div>
                    <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-building text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
