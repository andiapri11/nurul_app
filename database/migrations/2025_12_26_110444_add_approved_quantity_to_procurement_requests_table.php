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
        Schema::table('procurement_requests', function (Blueprint $blueprint) {
            $blueprint->integer('approved_quantity')->nullable()->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('procurement_requests', function (Blueprint $blueprint) {
            $blueprint->dropColumn('approved_quantity');
        });
    }
};
