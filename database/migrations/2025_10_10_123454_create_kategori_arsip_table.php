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
        Schema::create('kategori_arsip', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kategori', 20)->unique();
            $table->string('nama_kategori');
            $table->text('deskripsi')->nullable();
            $table->integer('masa_retensi')->default(5)->comment('Dalam tahun');
            $table->enum('tingkat_keamanan', ['publik', 'internal', 'rahasia', 'sangat_rahasia'])->default('internal');
            $table->string('warna_label', 7)->default('#008e3c');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_arsip');
    }
};
