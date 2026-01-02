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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('payment_type_id')->nullable()->change();
            $table->integer('month_paid')->nullable()->change();
            $table->integer('year_paid')->nullable()->change();
            $table->dateTime('transaction_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting nullable is tricky without knowing original state logic, but mostly we don't need to revert strictness.
    }
};
