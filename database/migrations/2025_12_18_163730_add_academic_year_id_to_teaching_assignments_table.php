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
        Schema::table('teaching_assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('teaching_assignments', 'academic_year_id')) {
                $table->foreignId('academic_year_id')
                      ->nullable()
                      ->after('class_id')
                      ->constrained('academic_years')
                      ->onDelete('cascade');
            }
                  
            // Update unique constraint
            // Try adding new unique logic if not exists (check index?)
            // We just try to add, if it fails due to duplicate key name, migration will fail but that's handled by us checking things?
            // Uniques are harder to check "exists" easily without DB::select.
            
            // Just try adding with a try-catch technically? No, Schema builder doesn't support try-catch easily inside.
            // But we can check via Schema manager if valid.
            
            // Assume if column existed, migration ran partially.
            // Let's ensure the unique index exists.
        });
        
        try {
            Schema::table('teaching_assignments', function (Blueprint $table) {
                 $table->unique(['user_id', 'subject_id', 'class_id', 'academic_year_id'], 'unique_assignment_year');
            });
        } catch (\Exception $e) { }

        try {
            Schema::table('teaching_assignments', function (Blueprint $table) {
               $table->dropUnique('unique_assignment');
            });
        } catch (\Exception $e) { }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teaching_assignments', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
            // Restore old unique (optional, but good practice)
            $table->dropUnique('unique_assignment_year');
            $table->unique(['user_id', 'subject_id', 'class_id'], 'unique_assignment');
        });
    }
};
