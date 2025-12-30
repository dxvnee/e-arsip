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
            $table->date('tanggal_arsip')->nullable();
            $table->integer('jumlah')->default(1);
            $table->string('satuan', 50)->default('lembar');
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->default('Baik');
            $table->text('keterangan')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('tanggal_arsip');
            $table->index('kondisi');
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
