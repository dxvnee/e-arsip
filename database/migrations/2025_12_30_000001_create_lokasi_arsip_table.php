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
        Schema::create('lokasi_arsip', function (Blueprint $table) {
            $table->id();
            $table->string('kode_lokasi', 30)->unique();
            $table->string('gedung', 100);
            $table->string('ruang', 100);
            $table->string('rak', 50);
            $table->string('boks', 50);
            $table->text('keterangan')->nullable();
            $table->integer('kapasitas')->default(0)->comment('Kapasitas maksimal arsip');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['gedung', 'ruang', 'rak', 'boks']);
        });

        // Add lokasi_arsip_id to arsip table
        Schema::table('arsip', function (Blueprint $table) {
            $table->foreignId('lokasi_arsip_id')->nullable()->after('lokasi_fisik')
                ->constrained('lokasi_arsip')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arsip', function (Blueprint $table) {
            $table->dropForeign(['lokasi_arsip_id']);
            $table->dropColumn('lokasi_arsip_id');
        });

        Schema::dropIfExists('lokasi_arsip');
    }
};
