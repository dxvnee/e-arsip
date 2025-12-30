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
        Schema::create('arsip_file', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_arsip_id')->constrained('item_arsip')->onDelete('cascade');
            $table->string('nama_file');
            $table->string('path_file');
            $table->bigInteger('ukuran'); // in bytes
            $table->string('tipe_file'); // pdf, jpg, jpeg, png
            $table->string('hash_file', 64); // SHA-256 hash
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('tipe_file');
            $table->index('hash_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_file');
    }
};
