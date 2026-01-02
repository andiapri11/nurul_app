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
        Schema::create('teacher_document_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->onDelete('set null');
            $table->string('semester')->nullable(); // Ganjil/Genap
            $table->date('due_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users'); // Who created this request
            $table->timestamps();
        });

        Schema::create('teacher_document_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('teacher_document_requests')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // The teacher
            $table->string('file_path');
            $table->string('original_filename')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_document_submissions');
        Schema::dropIfExists('teacher_document_requests');
    }
};
