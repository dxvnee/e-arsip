<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\KategoriArsipController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\KlasifikasiArsipController;
use App\Http\Controllers\LokasiArsipController;
use App\Http\Controllers\BerkasArsipController;
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

    // Klasifikasi Arsip (Master Data)
    Route::resource('klasifikasi-arsip', KlasifikasiArsipController::class);
    Route::patch('klasifikasi-arsip/{klasifikasiArsip}/toggle-status', [KlasifikasiArsipController::class, 'toggleStatus'])
        ->name('klasifikasi-arsip.toggle-status');
    Route::post('klasifikasi-arsip/{id}/restore', [KlasifikasiArsipController::class, 'restore'])
        ->name('klasifikasi-arsip.restore');

    // Lokasi Arsip (Master Data)
    Route::resource('lokasi-arsip', LokasiArsipController::class);
    Route::patch('lokasi-arsip/{lokasiArsip}/toggle-status', [LokasiArsipController::class, 'toggleStatus'])
        ->name('lokasi-arsip.toggle-status');
    Route::post('lokasi-arsip/{id}/restore', [LokasiArsipController::class, 'restore'])
        ->name('lokasi-arsip.restore');
    // AJAX endpoints for Lokasi Arsip dropdowns
    Route::get('lokasi-arsip/ajax/gedung', [LokasiArsipController::class, 'getByGedung'])
        ->name('lokasi-arsip.by-gedung');
    Route::get('lokasi-arsip/ajax/locations', [LokasiArsipController::class, 'getLocations'])
        ->name('lokasi-arsip.locations');

    // Berkas Arsip (Main Transaction)
    Route::resource('berkas-arsip', BerkasArsipController::class);
});

require __DIR__ . '/auth.php';
