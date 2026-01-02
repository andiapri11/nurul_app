<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah kolom sementara
        Schema::table('students', function (Blueprint $table) {
            $table->tinyInteger('tipe_kelas_bool')->default(0)->after('tipe_kelas')->comment('0=Regular, 1=Eksekutif');
        });

        // 2. Migrasi data lama (jika ada)
        // Eksekutif -> 1
        DB::table('students')->where('tipe_kelas', 'Eksekutif')->update(['tipe_kelas_bool' => 1]);
        // Regular -> 0 (sudah default)

        // 3. Hapus kolom lama
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('tipe_kelas');
        });

        // 4. Rename kolom baru ke nama asli
        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('tipe_kelas_bool', 'tipe_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke ENUM
        Schema::table('students', function (Blueprint $table) {
            $table->enum('tipe_kelas_enum', ['Regular', 'Eksekutif'])->default('Regular')->after('tipe_kelas');
        });

        DB::table('students')->where('tipe_kelas', 1)->update(['tipe_kelas_enum' => 'Eksekutif']);
        DB::table('students')->where('tipe_kelas', 0)->update(['tipe_kelas_enum' => 'Regular']);

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('tipe_kelas');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('tipe_kelas_enum', 'tipe_kelas');
        });
    }
};
