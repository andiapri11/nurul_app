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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->string('category')->nullable(); // e.g. "Gaji Guru", "Listrik", "Maintenance"
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->string('recipient')->nullable(); // Pihak penerima pembayaran
            $table->string('payment_method')->nullable();
            $table->text('description')->nullable();
             $table->string('reference_number')->nullable();
            $table->foreignId('user_id')->constrained()->comment('Petugas pencatat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
