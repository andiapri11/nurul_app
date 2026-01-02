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
        // Types of payments (e.g. SPP, Uang Gedung, Seragam)
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g., "SPP SD", "Uang Pangkal"
            $table->enum('type', ['monthly', 'one_time']); // monthly = SPP, one_time = Bebas
            $table->decimal('nominal', 15, 2)->default(0); // Standard amount
            $table->timestamps();
        });

        // The actual payment transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->comment('Receiver/Admin'); // Admin who accepted payment
            
            $table->string('invoice_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            
            // For Monthly: store which month/year paid (e.g., 7-2024)
            // For One Time: maybe null, or tracking remaining?
            $table->integer('month_paid')->nullable(); 
            $table->integer('year_paid')->nullable();
            
            $table->string('payment_method')->default('cash'); // cash, transfer
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('payment_types');
    }
};
