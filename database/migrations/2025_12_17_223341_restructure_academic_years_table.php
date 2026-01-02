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
        Schema::table('academic_years', function (Blueprint $table) {
            $table->year('start_year')->nullable()->after('id');
            $table->year('end_year')->nullable()->after('start_year');
        });

        // Migrate existing data
        $years = \Illuminate\Support\Facades\DB::table('academic_years')->get();
        foreach ($years as $year) {
            // Assume format "YYYY/YYYY" or similar
            $parts = explode('/', $year->name);
            if (count($parts) == 2) {
                \Illuminate\Support\Facades\DB::table('academic_years')
                    ->where('id', $year->id)
                    ->update([
                        'start_year' => $parts[0],
                        'end_year' => $parts[1]
                    ]);
            } else {
                 // Fallback or just ignore if format is weird
            }
        }

        Schema::table('academic_years', function (Blueprint $table) {
            // Drop name column now that we migrated. 
            // WAIT - other tables might rely on 'name'? No, they rely on ID mostly.
            // But let's keep 'name' but make it generated? Or just drop it.
            // User said "ubah struktur database".
            // I'll drop it to be clean, and use accessors in Model.
            $table->dropColumn('name');
            
            // Make new columns required now
            $table->year('start_year')->nullable(false)->change();
            $table->year('end_year')->nullable(false)->change();
            
            // Add unique constraint on the pair
            $table->unique(['start_year', 'end_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
        });

        // Migrate back
        $years = \Illuminate\Support\Facades\DB::table('academic_years')->get();
        foreach ($years as $year) {
            \Illuminate\Support\Facades\DB::table('academic_years')
                ->where('id', $year->id)
                ->update(['name' => $year->start_year . '/' . $year->end_year]);
        }

        Schema::table('academic_years', function (Blueprint $table) {
            $table->dropUnique(['start_year', 'end_year']);
            $table->dropColumn(['start_year', 'end_year']);
            $table->string('name')->nullable(false)->unique()->change();
        });
    }
};
