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
            // Drop the old ENUM and add the new one
            $table->dropColumn('jenis_jabatan');
        });
        
        Schema::table('jabatans', function (Blueprint $table) {
            $table->enum('kategori', ['guru', 'tambahan', 'staff'])->default('guru')->after('nama_jabatan');
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('cascade')->after('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
             $table->dropForeign(['unit_id']);
             $table->dropColumn('unit_id');
             $table->dropColumn('kategori');
             $table->enum('jenis_jabatan', ['mengajar', 'tambahan'])->default('mengajar');
        });
    }
};
