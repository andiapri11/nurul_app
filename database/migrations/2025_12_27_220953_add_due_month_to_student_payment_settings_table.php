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
        Schema::table('student_payment_settings', function (Blueprint $table) {
            $table->integer('due_month')->nullable()->after('month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_payment_settings', function (Blueprint $table) {
            $table->dropColumn('due_month');
        });
    }
};
