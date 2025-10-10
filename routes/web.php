<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\KategoriArsipController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
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
    Route::get('arsip/{arsip}/download', [ArsipController::class, 'download'])->name('arsip.download');
    
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
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
});

require __DIR__.'/auth.php';
