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
        Schema::table('student_achievements', function (Blueprint $table) {
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('cascade');
        });

        // Data migration
        $activeYear = DB::table('academic_years')->where('status', 'active')->first();
        if ($activeYear) {
            DB::table('student_achievements')
                ->whereNull('academic_year_id')
                ->update(['academic_year_id' => $activeYear->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_achievements', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });
    }
};
