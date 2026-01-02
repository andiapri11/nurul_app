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
        Schema::table('damage_reports', function (Blueprint $table) {
            $table->string('type')->default('Damaged')->after('inventory_id'); // Damaged, Lost
            $table->string('follow_up_action')->nullable(); // Repair, Replace, Disposal, Write-off
            $table->text('follow_up_description')->nullable();
            $table->string('principal_approval_status')->default('Pending'); // Pending, Approved, Rejected
            $table->foreignId('principal_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('principal_note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('damage_reports', function (Blueprint $table) {
            $table->dropForeign(['principal_id']);
            $table->dropColumn([
                'type', 
                'follow_up_action', 
                'follow_up_description', 
                'principal_approval_status', 
                'principal_id', 
                'principal_note'
            ]);
        });
    }
};
