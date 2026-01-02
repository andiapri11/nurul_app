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
        Schema::table('students', function (Blueprint $table) {
            // Drop the existing foreign key
            // Note: Laravel default naming is table_column_foreign, but might differ if manually named.
            // Assuming standard convention 'students_class_id_foreign'.
            $table->dropForeign(['class_id']);

            // Re-add with set null on delete
            $table->foreign('class_id')
                  ->references('id')
                  ->on('classes')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            
            $table->foreign('class_id')
                  ->references('id')
                  ->on('classes')
                  ->onDelete('cascade');
        });
    }
};
