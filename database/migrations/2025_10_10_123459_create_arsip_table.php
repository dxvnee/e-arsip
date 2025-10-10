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
        Schema::create('arsip', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_arsip')->unique();
            $table->string('nomor_surat')->nullable();
            $table->string('judul_arsip');
            $table->text('deskripsi')->nullable();
            $table->foreignId('kategori_id')->constrained('kategori_arsip');
            $table->foreignId('unit_kerja_id')->constrained('unit_kerja');
            $table->enum('jenis_arsip', ['surat_masuk', 'surat_keluar', 'dokumen_internal', 'laporan', 'peraturan', 'lainnya']);
            $table->date('tanggal_surat');
            $table->date('tanggal_diterima')->nullable();
            $table->string('pengirim')->nullable();
            $table->string('penerima')->nullable();
            $table->string('perihal')->nullable();
            $table->text('isi_ringkas')->nullable();
            $table->string('file_arsip')->nullable();
            $table->string('file_type', 10)->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('lokasi_fisik')->nullable();
            $table->enum('status', ['aktif', 'inaktif', 'musnah'])->default('aktif');
            $table->date('tanggal_retensi')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->text('tags')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('download_count')->default(0);
            $table->timestamps();
            
            $table->index(['nomor_arsip', 'nomor_surat']);
            $table->index('tanggal_surat');
            $table->index('jenis_arsip');
            $table->fullText(['judul_arsip', 'deskripsi', 'isi_ringkas', 'tags']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip');
    }
};
