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
            if (!Schema::hasColumn('supervisions', 'subject_id')) {
                $table->foreignId('subject_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('supervisions', 'school_class_id')) {
                $table->foreignId('school_class_id')->nullable()->constrained('classes')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supervisions', function (Blueprint $table) {
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['school_class_id']);
            $table->dropColumn(['subject_id', 'school_class_id']);
        });
    }
};
