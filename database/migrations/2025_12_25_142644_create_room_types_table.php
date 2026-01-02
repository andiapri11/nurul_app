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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label')->nullable();
            $table->string('color')->default('primary');
            $table->timestamps();
        });

        // Seed default types
        DB::table('room_types')->insert([
            ['name' => 'Classroom', 'label' => 'Ruang Kelas', 'color' => 'success'],
            ['name' => 'Lab', 'label' => 'Laboratorium', 'color' => 'info'],
            ['name' => 'Hall', 'label' => 'Aula', 'color' => 'primary'],
            ['name' => 'Office', 'label' => 'Kantor', 'color' => 'secondary'],
            ['name' => 'Library', 'label' => 'Perpustakaan', 'color' => 'warning'],
            ['name' => 'Other', 'label' => 'Lainnya', 'color' => 'dark'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
