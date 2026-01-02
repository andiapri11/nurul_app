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
        Schema::create('procurement_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Requester
            $table->foreignId('inventory_category_id')->constrained();
            $table->string('item_name');
            $table->integer('quantity');
            $table->string('unit_name');
            $table->decimal('estimated_price', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['Asset', 'Consumable']);
            $table->enum('status', ['Pending', 'Validated', 'Approved', 'Rejected', 'Processed'])->default('Pending');
            
            // Principal Validation
            $table->enum('principal_status', ['Pending', 'Validated', 'Rejected'])->default('Pending');
            $table->text('principal_note')->nullable();
            $table->timestamp('validated_at')->nullable();
            
            // Director Approval
            $table->enum('director_status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->text('director_note')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_requests');
    }
};
