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
        Schema::table('academic_calendars', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('cascade')->after('id');
            // Remove unique constraint on date because now it depends on unit
            $table->dropUnique(['date']);
            // Add composite unique index
            $table->unique(['date', 'unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_calendars', function (Blueprint $table) {
            //
        });
    }
};
