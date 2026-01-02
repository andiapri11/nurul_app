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
        // 1. Add academic_year_id
        if (!Schema::hasColumn('class_student', 'academic_year_id')) {
            Schema::table('class_student', function (Blueprint $table) {
                $table->foreignId('academic_year_id')->nullable()->after('class_id')->constrained('academic_years')->onDelete('cascade');
            });
        }

        // 2. Populating academic_year_id from classes table
        DB::statement("UPDATE class_student cs 
                       JOIN classes c ON cs.class_id = c.id 
                       SET cs.academic_year_id = c.academic_year_id
                       WHERE cs.academic_year_id IS NULL");

        // 3. Add new unique constraint: One student, one academic year.
        // We use a different name to avoid index conflicts if any.
        Schema::table('class_student', function (Blueprint $table) {
            try {
                $table->unique(['student_id', 'academic_year_id'], 'student_academic_year_unique');
            } catch (\Exception $e) {
                // Ignore if already exists
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_student', function (Blueprint $table) {
            try {
                $table->dropUnique('student_academic_year_unique');
            } catch (\Exception $e) {}
            
            if (Schema::hasColumn('class_student', 'academic_year_id')) {
                $table->dropForeign(['academic_year_id']);
                $table->dropColumn('academic_year_id');
            }
        });
    }
};
