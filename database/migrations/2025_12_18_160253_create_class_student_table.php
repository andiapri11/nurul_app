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
        Schema::create('class_student', function (Blueprint $table) {
            $table->id();
            // Assuming 'students' table PK is 'id'. Wait, 'students' table PK is 'id' but it relates to 'users'.
            // The student model refers to 'students' table.
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['student_id', 'class_id']);
        });

        // Seed existing data
        $students = \DB::table('students')->whereNotNull('class_id')->get();
        foreach ($students as $student) {
            try {
                \DB::table('class_student')->insert([
                    'student_id' => $student->id,
                    'class_id' => $student->class_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Ignore duplicates if any
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_student');
    }
};
