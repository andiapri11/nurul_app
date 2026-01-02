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
        Schema::table('income_expenses', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->change();
            $table->string('payment_method')->default('tunai')->after('amount'); // tunai, transfer
            $table->foreignId('bank_account_id')->nullable()->after('payment_method')->constrained()->nullOnDelete();
            $table->string('payer_name')->nullable()->after('bank_account_id'); // donor/source name
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('income_expenses', function (Blueprint $table) {
            //
        });
    }
};
