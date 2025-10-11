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
        Schema::create('arsip_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arsip_id')->constrained('arsip')->onDelete('cascade');
            $table->integer('version_number');
            $table->string('file_path'); // Path to file
            $table->string('file_type', 10)->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User who made this version
            $table->text('change_note')->nullable(); // Note about what changed
            $table->json('metadata')->nullable(); // Store metadata snapshot
            $table->timestamps();
            
            $table->index(['arsip_id', 'version_number']);
            $table->unique(['arsip_id', 'version_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_versions');
    }
};
