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
        Schema::create('student_graduation_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('graduation_announcement_id')->constrained('graduation_announcements')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->enum('status', ['lulus', 'tidak_lulus', 'pending'])->default('pending');
            $table->text('message')->nullable();
            $table->timestamps();
            
            $table->unique(['graduation_announcement_id', 'student_id'], 'unique_announcement_student');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_graduation_results');
    }
};
