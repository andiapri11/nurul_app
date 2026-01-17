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
            // Drop old unique index that restricted one entry per date/unit
            $table->dropUnique(['date', 'unit_id']);
            
            // Add new unique index that allows one Holiday AND one Activity row per date/unit
            // This supports the 'Mix' status where some classes have an activity and others are holiday
            $table->unique(['date', 'unit_id', 'is_holiday']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_calendars', function (Blueprint $table) {
            $table->dropUnique(['date', 'unit_id', 'is_holiday']);
            $table->unique(['date', 'unit_id']);
        });
    }
};
