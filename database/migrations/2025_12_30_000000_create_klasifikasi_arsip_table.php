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
        Schema::create('klasifikasi_arsip', function (Blueprint $table) {
            $table->id();
            $table->string('kode_klasifikasi', 20)->unique();
            $table->string('nama_klasifikasi', 255);
            $table->text('deskripsi')->nullable();
            $table->integer('retensi_aktif')->default(0)->comment('Masa retensi aktif dalam tahun');
            $table->integer('retensi_inaktif')->default(0)->comment('Masa retensi inaktif dalam tahun');
            $table->enum('nasib_akhir', ['musnah', 'permanen'])->default('musnah');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('klasifikasi_arsip');
    }
};
