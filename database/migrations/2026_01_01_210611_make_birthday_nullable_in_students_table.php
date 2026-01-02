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
            $table->date('tanggal_lahir')->nullable()->change();
            $table->string('tempat_lahir')->nullable()->change();
            $table->string('nama_wali')->nullable()->change();
            $table->string('no_hp_wali')->nullable()->change();
            $table->text('alamat')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->date('tanggal_lahir')->nullable(false)->change();
            $table->string('tempat_lahir')->nullable(false)->change();
            $table->string('nama_wali')->nullable(false)->change();
            $table->string('no_hp_wali')->nullable(false)->change();
            $table->text('alamat')->nullable(false)->change();
        });
    }
};
