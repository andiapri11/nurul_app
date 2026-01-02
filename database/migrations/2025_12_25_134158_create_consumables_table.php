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
        Schema::dropIfExists('consumables');
        Schema::create('consumables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->integer('stock')->default(0);
            $table->string('unit_name'); // e.g., Rim, Box, Pack, Liter
            $table->integer('min_stock')->default(0); // for low stock alerts
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumables');
    }
};
