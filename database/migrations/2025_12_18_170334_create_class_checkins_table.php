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
        Schema::create('class_checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Teacher
            $table->timestamp('checkin_time');
            $table->timestamp('checkout_time')->nullable();
            $table->enum('status', ['ontime', 'late', 'absent']);
            $table->string('notes')->nullable(); // Optional notes from teacher
            $table->string('photo')->nullable(); // Optional verification photo
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
            
            // Limit one checkin per schedule per date (derived from checkin_time in logic)
            // But user might check in multiple times if they leave and come back? 
            // Usually 1 checkin per session. Let's not enforce unique DB constraint on date yet to be flexible.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_checkins');
    }
};
