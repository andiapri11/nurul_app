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
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade'); // Snapshot of class at that time
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('set null');
            $table->date('date');
            $table->enum('status', ['present', 'sick', 'permission', 'alpha', 'late'])->default('present');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users'); // Wali Kelas ID
            $table->timestamps();

            // Constraint: One status per student per day? 
            // Yes, usually.
            $table->unique(['student_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendances');
    }
};
