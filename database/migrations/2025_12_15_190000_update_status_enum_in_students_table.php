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
        // Altering enum column in Laravel/MySQL can be tricky. 
        // Using raw SQL is often the most reliable way for simple additions without doctrine/dbal.
        DB::statement("ALTER TABLE students MODIFY COLUMN status ENUM('aktif', 'lulus', 'keluar', 'pindah', 'non-aktif') DEFAULT 'aktif'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back, mapping 'non-aktif' to 'keluar' or just keeping it (data loss risk if strict revert)
        // For safety, we will just revert definition but this would fail if data exists.
        // In dev, we accept this risk or handle it.
        // We'll try to revert to original.
        DB::statement("ALTER TABLE students MODIFY COLUMN status ENUM('aktif', 'lulus', 'keluar', 'pindah') DEFAULT 'aktif'");
    }
};
