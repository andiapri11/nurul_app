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
        Schema::create('inventory_logs', function (Blueprint $col) {
            $col->id();
            $col->foreignId('inventory_id')->constrained()->onDelete('cascade');
            $col->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $col->string('action'); // Created, Updated, Moved, Damaged, Repaired, etc.
            $col->text('details')->nullable();
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
