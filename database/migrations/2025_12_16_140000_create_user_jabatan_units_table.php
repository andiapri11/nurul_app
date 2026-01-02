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
        Schema::create('user_jabatan_units', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Guru/User
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // Relasi ke Jabatan (Guru, Kepala Sekolah, dll)
            $table->foreignId('jabatan_id')
                  ->constrained('jabatans')
                  ->onDelete('cascade');
                  
            // Relasi ke Unit (SD, SMP, SMA, dll)
            $table->foreignId('unit_id')
                  ->constrained('units')
                  ->onDelete('cascade');
            
            $table->timestamps();
            
            // Mencegah duplikasi data yang sama (User A jabatan B di Unit C hanya boleh sekali)
            $table->unique(['user_id', 'jabatan_id', 'unit_id'], 'user_jab_unit_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_jabatan_units');
    }
};
