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
        Schema::create('berkas_arsip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('klasifikasi_arsip_id')->constrained('klasifikasi_arsip')->onDelete('restrict');
            $table->string('nomor_berkas', 100)->unique();
            $table->text('uraian_berkas');
            $table->string('kurun_waktu', 100)->nullable(); // e.g., "2020-2024", "Januari - Desember 2023"
            $table->year('tahun');
            $table->string('unit_kerja', 150);
            $table->enum('status_arsip', ['Aktif', 'Inaktif', 'Permanen'])->default('Aktif');
            $table->foreignId('lokasi_arsip_id')->nullable()->constrained('lokasi_arsip')->onDelete('set null');
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('tahun');
            $table->index('status_arsip');
            $table->index('unit_kerja');
        });

        // Add berkas_arsip_id foreign key to arsip table if exists
        if (Schema::hasTable('arsip')) {
            Schema::table('arsip', function (Blueprint $table) {
                if (!Schema::hasColumn('arsip', 'berkas_arsip_id')) {
                    $table->foreignId('berkas_arsip_id')->nullable()->after('id')
                        ->constrained('berkas_arsip')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove FK from arsip table if exists
        if (Schema::hasTable('arsip') && Schema::hasColumn('arsip', 'berkas_arsip_id')) {
            Schema::table('arsip', function (Blueprint $table) {
                $table->dropForeign(['berkas_arsip_id']);
                $table->dropColumn('berkas_arsip_id');
            });
        }

        Schema::dropIfExists('berkas_arsip');
    }
};
