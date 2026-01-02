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
            $table->enum('jenis_jabatan', ['mengajar', 'tambahan'])->default('mengajar')->after('nama_jabatan');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('nip')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->dropColumn('jenis_jabatan');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nip');
        });
    }
};
