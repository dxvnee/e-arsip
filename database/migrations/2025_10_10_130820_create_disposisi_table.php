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
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arsip_id')->constrained('arsip')->onDelete('cascade');
            $table->foreignId('dari_user_id')->constrained('users');
            $table->foreignId('kepada_user_id')->constrained('users');
            $table->text('isi_disposisi');
            $table->enum('prioritas', ['biasa', 'segera', 'sangat_segera'])->default('biasa');
            $table->enum('sifat', ['biasa', 'rahasia', 'penting'])->default('biasa');
            $table->text('catatan')->nullable();
            $table->enum('status', ['baru', 'dibaca', 'diproses', 'selesai'])->default('baru');
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamp('diproses_pada')->nullable();
            $table->timestamp('selesai_pada')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->timestamps();
            
            $table->index(['kepada_user_id', 'status']);
            $table->index('arsip_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisi');
    }
};
