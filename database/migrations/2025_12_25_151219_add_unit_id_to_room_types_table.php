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
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('cascade')->after('id');
            // We want unit_id + name to be unique, but only if unit_id is not null.
            // If we allow nulls, regular unique index allows multiple nulls.
            // But here we want strict separation, so we can enforce it.
            $table->unique(['unit_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropUnique(['unit_id', 'name']);
            $table->dropColumn('unit_id');
            $table->unique('name');
        });
    }
};
