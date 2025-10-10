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
        Schema::create('unit_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unit', 20)->unique();
            $table->string('nama_unit');
            $table->text('keterangan')->nullable();
            $table->string('kepala_unit')->nullable();
            $table->string('nip_kepala', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_kerja');
    }
};
