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
        Schema::create('income_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed some defaults
        DB::table('income_categories')->insert([
            ['name' => 'Dana BOS', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sumbangan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Penjualan Seragam', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lain-lain', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_categories');
    }
};
