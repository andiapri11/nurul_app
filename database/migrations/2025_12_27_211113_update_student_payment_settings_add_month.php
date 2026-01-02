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
        Schema::table('student_payment_settings', function (Blueprint $table) {
            // Drop FK that relies on the unique index (student_id is the leading column)
            $table->dropForeign(['student_id']);
            
            // Drop old unique constraint
            $table->dropUnique('stud_pay_set_unique');
            
            // Add month column (1-12)
            $table->string('month')->nullable()->after('academic_year_id'); 

            // New Unique Constraint
            $table->unique(['student_id', 'payment_type_id', 'academic_year_id', 'month'], 'stud_pay_set_month_unique');
            
            // Re-add FK
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_payment_settings', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropUnique('stud_pay_set_month_unique');
            $table->dropColumn('month');
            
            $table->unique(['student_id', 'payment_type_id', 'academic_year_id'], 'stud_pay_set_unique');
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
        });
    }
};
