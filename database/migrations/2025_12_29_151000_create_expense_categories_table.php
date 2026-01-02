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
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed some defaults
        DB::table('expense_categories')->insert([
            ['name' => 'Gaji Guru & Karyawan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Operasional Sekolah', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pemeliharaan Gedung', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ATK & Perlengkapan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Listrik & Internet', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kegiatan Siswa', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lain-lain', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_categories');
    }
};
