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
        Schema::dropIfExists('inventories');
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('condition')->default('Good'); // Good, Damaged, Repairing, Broken
            $table->decimal('price', 15, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
