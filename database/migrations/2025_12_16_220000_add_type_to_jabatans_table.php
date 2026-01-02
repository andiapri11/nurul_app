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
            // Adding 'type' column to distinguish structural vs functional
            // type: 'struktural' (e.g. Kepala Sekolah), 'fungsional' (e.g. Guru Mapel), 'tambahan' (e.g. Wali Kelas)
            $table->enum('tipe', ['struktural', 'fungsional', 'tambahan'])->default('fungsional')->after('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};
