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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_type_id')->constrained()->cascadeOnDelete();
            
            $table->decimal('amount', 15, 2);
            $table->integer('month_paid')->nullable();
            $table->integer('year_paid')->nullable();
            
            $table->timestamps();
        });

        // Make columns nullable in transactions table because they are moved to transaction_items
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('payment_type_id')->nullable()->change();
            $table->integer('month_paid')->nullable()->change();
            $table->string('year_paid')->nullable()->change();
        });

        // Add bank_account_id to transactions if not exists (checked controller, it expects it)
        if (!Schema::hasColumn('transactions', 'bank_account_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreignId('bank_account_id')->nullable()->constrained()->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
