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
        Schema::table('supervisions', function (Blueprint $table) {
            $table->renameColumn('document_path', 'teacher_document_path');
            $table->string('supervisor_document_path')->nullable()->after('notes');
            $table->enum('document_status', ['pending', 'approved', 'rejected'])->default('pending')->after('teacher_document_path');
        });
    }

    public function down(): void
    {
        Schema::table('supervisions', function (Blueprint $table) {
            $table->renameColumn('teacher_document_path', 'document_path');
            $table->dropColumn(['supervisor_document_path', 'document_status']);
        });
    }
};
