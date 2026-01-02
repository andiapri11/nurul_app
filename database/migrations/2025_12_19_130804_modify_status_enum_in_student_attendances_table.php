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
        // We cannot easily modify ENUM in some DBs without raw SQL or dropping column.
        // For MySQL/MariaDB:
        DB::statement("ALTER TABLE student_attendances MODIFY COLUMN status ENUM('present', 'sick', 'permission', 'alpha', 'late', 'school_activity') DEFAULT 'present'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE student_attendances MODIFY COLUMN status ENUM('present', 'sick', 'permission', 'alpha', 'late') DEFAULT 'present'");
    }
};
