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
        Schema::table('teacher_document_submissions', function (Blueprint $table) {
            // Modify status enum. 
            // In Laravel/MySQL, modifying enum is tricky. We can just change using DB statement or make it a string.
            // Let's change column type to string to be flexible, or modify enum.
            // Using raw statement for Enum modification is safest for MySQL.
            DB::statement("ALTER TABLE teacher_document_submissions MODIFY COLUMN status ENUM('pending', 'validated', 'approved', 'rejected') DEFAULT 'pending'");
            
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_document_submissions', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['validated_by', 'validated_at', 'approved_by', 'approved_at']);
            
            // Revert status
             DB::statement("ALTER TABLE teacher_document_submissions MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
        });
    }
};
