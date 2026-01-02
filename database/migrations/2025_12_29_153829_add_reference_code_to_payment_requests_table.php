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
        Schema::table('payment_requests', function (Blueprint $table) {
            $table->string('reference_code')->unique()->after('total_amount');
            $table->string('proof_image')->nullable()->change();
            $table->enum('status', ['pending', 'verified', 'rejected', 'waiting_proof'])->default('waiting_proof')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_requests', function (Blueprint $table) {
            $table->dropColumn('reference_code');
            $table->string('proof_image')->nullable(false)->change();
        });
    }
};
