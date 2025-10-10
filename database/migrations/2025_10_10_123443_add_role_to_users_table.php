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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'operator', 'viewer'])->default('viewer')->after('email');
            $table->string('nip', 50)->nullable()->after('role');
            $table->string('jabatan')->nullable()->after('nip');
            $table->foreignId('unit_kerja_id')->nullable()->after('jabatan');
            $table->string('phone', 20)->nullable()->after('unit_kerja_id');
            $table->text('address')->nullable()->after('phone');
            $table->string('avatar')->nullable()->after('address');
            $table->boolean('is_active')->default(true)->after('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'nip', 'jabatan', 'unit_kerja_id', 'phone', 'address', 'avatar', 'is_active']);
        });
    }
};
