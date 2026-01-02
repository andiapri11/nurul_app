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
        Schema::create('student_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            
            $table->integer('month')->nullable(); // 1-12 for monthly fees
            $table->integer('year')->nullable(); // Actual year context if needed
            
            $table->decimal('amount', 15, 2); 
            $table->decimal('paid_amount', 15, 2)->default(0);
            
            // unpaid, partial, paid
            $table->string('status')->default('unpaid');
            
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();

            // Index for faster searching
            $table->unique(['student_id', 'payment_type_id', 'academic_year_id', 'month'], 'student_bill_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_bills');
    }
};
