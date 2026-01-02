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
        Schema::table('jabatans', function (Blueprint $table) {
            // Drop the existing simple unique index
            $table->dropUnique(['kode_jabatan']);
            
            // Add a composite unique index for (unit_id, kode_jabatan)
            $table->unique(['unit_id', 'kode_jabatan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->dropUnique(['unit_id', 'kode_jabatan']);
            $table->unique('kode_jabatan');
        });
    }
};
