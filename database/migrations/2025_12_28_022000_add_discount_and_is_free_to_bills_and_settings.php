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
            $table->decimal('discount_amount', 15, 2)->default(0)->after('amount');
            $table->boolean('is_free')->default(false)->after('discount_amount');
        });

        Schema::table('student_bills', function (Blueprint $table) {
            $table->decimal('discount_amount', 15, 2)->default(0)->after('amount');
            $table->boolean('is_free')->default(false)->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_payment_settings', function (Blueprint $table) {
            $table->dropColumn(['discount_amount', 'is_free']);
        });

        Schema::table('student_bills', function (Blueprint $table) {
            $table->dropColumn(['discount_amount', 'is_free']);
        });
    }
};
