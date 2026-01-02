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
        Schema::table('user_jabatan_units', function (Blueprint $table) {
            // Check if column exists to avoid error on re-run (though this migration is now marked as run)
            if (!Schema::hasColumn('user_jabatan_units', 'academic_year_id')) {
                $table->foreignId('academic_year_id')
                      ->nullable()
                      ->after('unit_id')
                      ->constrained('academic_years')
                      ->onDelete('cascade');
                
                // Update unique constraint to include academic_year_id
                try {
                    // First drop existing unique
                    $table->dropUnique('user_jab_unit_unique');
                } catch (\Exception $e) {
                    // Ignore if not found
                }
                
                // Add new unique
                $table->unique(['user_id', 'jabatan_id', 'unit_id', 'academic_year_id'], 'user_jab_unit_year_unique');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_jabatan_units', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropUnique('user_jab_unit_year_unique');
            $table->dropColumn('academic_year_id');
            
            // Restore old unique (might need to handle data duplicates first manually if rolling back, but for schema definition:)
            $table->unique(['user_id', 'jabatan_id', 'unit_id'], 'user_jab_unit_unique');
        });
    }
};
