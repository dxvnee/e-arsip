<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\KategoriArsipController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DisposisiController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Protected Routes - Require Authentication
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Arsip Management
    Route::resource('arsip', ArsipController::class);
    Route::get('/arsip/{arsip}/download', [ArsipController::class, 'download'])->name('arsip.download');
    Route::get('/arsip/{arsip}/preview', [ArsipController::class, 'preview'])->name('arsip.preview');
    Route::get('/arsip/{arsip}/versions', [ArsipController::class, 'versions'])->name('arsip.versions');
    Route::get('/arsip/{arsip}/version/{version}/download', [ArsipController::class, 'downloadVersion'])->name('arsip.version.download');
    Route::get('arsip/{arsip}/download', [ArsipController::class, 'download'])->name('arsip.download');
    
    // Disposisi
    Route::resource('disposisi', DisposisiController::class)->except(['edit', 'update', 'destroy']);
    Route::post('disposisi/{disposisi}/update-status', [DisposisiController::class, 'updateStatus'])->name('disposisi.update-status');
    
    // Admin Only Routes
    Route::middleware(['role:admin'])->group(function () {
        // Master Data
        Route::resource('kategori', KategoriArsipController::class);
        Route::resource('unit-kerja', UnitKerjaController::class);
        
        // User Management
        Route::resource('users', UserController::class);
    });
    
    // Laporan - All authenticated users can access
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/arsip-masuk-keluar', [LaporanController::class, 'arsipMasukKeluar'])->name('laporan.arsip-masuk-keluar');
    Route::get('/laporan/statistik', [LaporanController::class, 'statistikArsip'])->name('laporan.statistik');
    Route::post('/laporan/aktivitas', [LaporanController::class, 'aktivitas'])->name('laporan.aktivitas');
    Route::post('/laporan/disposisi', [LaporanController::class, 'disposisi'])->name('laporan.disposisi');
});

require __DIR__.'/auth.php';
