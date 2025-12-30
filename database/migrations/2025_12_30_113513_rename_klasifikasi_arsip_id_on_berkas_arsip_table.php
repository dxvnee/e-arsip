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
        Schema::table('berkas_arsip', function (Blueprint $table) {
            $table->renameColumn('klasifikasi_arsip_id', 'kode_klasifikasi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berkas_arsip', function (Blueprint $table) {
            $table->renameColumn('kode_klasifikasi_id', 'klasifikasi_arsip_id');
        });
    }
};
