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
        Schema::table('teacher_document_requests', function (Blueprint $table) {
            // Drop old Foreign Keys first
            // Note: DB names for FKs are usually table_column_foreign
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['user_id']);
            
            // Drop old columns
            $table->dropColumn(['unit_id', 'subject_id', 'grade_level', 'user_id']);
            
            // Add new JSON columns
            $table->json('target_units')->nullable();
            $table->json('target_subjects')->nullable();
            $table->json('target_grades')->nullable();
            $table->json('target_users')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_document_requests', function (Blueprint $table) {
            $table->dropColumn(['target_units', 'target_subjects', 'target_grades', 'target_users']);
            
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('grade_level')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
};
