<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('berkas_arsip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kode_klasifikasi_id')->constrained('klasifikasi_arsip')->onDelete('restrict');
            $table->string('nomor_berkas', 100)->unique();
            $table->text('uraian_berkas');
            $table->string('kurun_waktu', 100)->nullable(); // e.g., "2020-2024", "Januari - Desember 2023"
            $table->year('tahun');
            $table->string('unit_kerja', 150);
            $table->enum('status_arsip', ['Aktif', 'Inaktif', 'Permanen'])->default('Aktif');
            $table->foreignId('lokasi_arsip_id')->nullable()->constrained('lokasi_arsip')->onDelete('set null');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('tahun');
            $table->index('status_arsip');
            $table->index('unit_kerja');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas_arsip');
    }
};
