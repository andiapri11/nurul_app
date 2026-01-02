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
            $table->dropColumn(['grade', 'description']);
            $table->string('grade_ganjil')->nullable()->after('role');
            $table->text('description_ganjil')->nullable()->after('grade_ganjil');
            $table->string('grade_genap')->nullable()->after('description_ganjil');
            $table->text('description_genap')->nullable()->after('grade_genap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extracurricular_members', function (Blueprint $table) {
            $table->string('grade')->nullable()->after('role');
            $table->text('description')->nullable()->after('grade');
            $table->dropColumn(['grade_ganjil', 'description_ganjil', 'grade_genap', 'description_genap']);
        });
    }
};
