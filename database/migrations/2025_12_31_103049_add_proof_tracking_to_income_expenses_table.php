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
        Schema::table('income_expenses', function (Blueprint $table) {
            $table->boolean('is_proof_needed')->default(false)->after('photo');
            $table->string('proof_status')->default('Pending')->after('is_proof_needed')->comment('Pending, Reported, Verified');
            $table->string('proof_code')->nullable()->after('proof_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('income_expenses', function (Blueprint $table) {
            $table->dropColumn(['is_proof_needed', 'proof_status', 'proof_code']);
        });
    }
};
