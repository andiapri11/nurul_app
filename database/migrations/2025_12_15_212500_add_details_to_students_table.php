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
        Schema::table('students', function (Blueprint $table) {
            $table->string('agama')->nullable()->after('tanggal_lahir');
            $table->string('alamat_rt')->nullable()->after('alamat'); // RT
            $table->string('alamat_rw')->nullable()->after('alamat_rt'); // RW
            $table->string('desa')->nullable()->after('alamat_rw'); // Kelurahan/Desa
            $table->string('kecamatan')->nullable()->after('desa');
            $table->string('kota')->nullable()->after('kecamatan'); // Kabupaten/Kota
            $table->string('kode_pos')->nullable()->after('kota');
            $table->string('no_hp')->nullable()->after('kode_pos'); // HP Siswa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'agama',
                'alamat_rt',
                'alamat_rw',
                'desa',
                'kecamatan',
                'kota',
                'kode_pos',
                'no_hp'
            ]);
        });
    }
};
