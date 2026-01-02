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
        Schema::table('extracurricular_members', function (Blueprint $table) {
            $table->string('grade')->nullable()->after('role'); // e.g., A, B, C, D
            $table->text('description')->nullable()->after('grade'); // Qualitative description
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extracurricular_members', function (Blueprint $table) {
            $table->dropColumn(['grade', 'description']);
        });
    }
};
