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
        Schema::create('student_payment_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            
            // The agreed amount for this student (Monthly Rate or Total Annual Bill)
            $table->decimal('amount', 15, 2); 
            
            // Optional: Link to class context when this was set? 
            // Better to rely on student->class relation for display, but storing here ensures history?
            // User said "Dari Kelas dan Tahun Pelajaran", imply context is important.
            // Let's rely on student id.
            
            $table->timestamps();
            
            // Unique constraint: One setting per type per student per year
            $table->unique(['student_id', 'payment_type_id', 'academic_year_id'], 'stud_pay_set_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_payment_settings');
    }
};
