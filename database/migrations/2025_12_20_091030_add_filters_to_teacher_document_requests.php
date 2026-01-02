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
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('grade_level')->nullable(); // e.g. "10", "7", "1"
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->comment('Target Specific Teacher'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_document_requests', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['unit_id', 'subject_id', 'grade_level', 'user_id']);
        });
    }
};
