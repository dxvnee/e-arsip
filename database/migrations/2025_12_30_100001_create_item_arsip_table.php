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
        Schema::create('item_arsip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berkas_arsip_id')->constrained('berkas_arsip')->onDelete('cascade');
            $table->string('nomor_item', 100);
            $table->text('uraian_item');
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat')->nullable();
            $table->string('asal_surat')->nullable();
            $table->integer('jumlah_eksemplar')->default(1);
            $table->string('tingkat_perkembangan')->default('Asli');
            $table->string('jenis_fisik')->default('Tekstual');
            $table->string('kondisi_fisik')->default('Baik');
            $table->text('keterangan')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('nomor_item');
            $table->index('tanggal_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_arsip');
    }
};
