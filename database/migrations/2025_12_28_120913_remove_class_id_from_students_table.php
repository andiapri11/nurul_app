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
            // Check if foreign key exists before dropping it
            // Assuming the foreign key name is students_class_id_foreign
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id')->nullable()->after('unit_id');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('set null');
        });
    }
};
