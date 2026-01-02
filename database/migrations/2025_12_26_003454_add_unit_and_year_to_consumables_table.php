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
        Schema::table('consumables', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('cascade')->after('inventory_category_id');
            $table->foreignId('academic_year_id')->nullable()->constrained()->onDelete('cascade')->after('unit_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consumables', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn(['unit_id', 'academic_year_id']);
        });
    }
};
