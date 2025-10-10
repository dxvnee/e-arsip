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
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('aksi', ['create', 'read', 'update', 'delete', 'download', 'login', 'logout']);
            $table->string('model_type')->nullable();
            $table->bigInteger('model_id')->nullable();
            $table->string('deskripsi');
            $table->text('data_lama')->nullable();
            $table->text('data_baru')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'aksi']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
