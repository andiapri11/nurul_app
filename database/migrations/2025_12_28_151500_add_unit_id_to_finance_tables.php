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
        // Fix Receipts Table
        if (Schema::hasTable('receipts')) {
            Schema::table('receipts', function (Blueprint $table) {
                if (!Schema::hasColumn('receipts', 'unit_id')) {
                    $table->foreignId('unit_id')->after('id')->nullable()->constrained('units')->cascadeOnDelete();
                }
            });
        }

        // Fix Payments Table
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
               if (!Schema::hasColumn('payments', 'unit_id')) {
                    $table->foreignId('unit_id')->after('id')->nullable()->constrained('units')->cascadeOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('receipts')) {
            Schema::table('receipts', function (Blueprint $table) {
                if (Schema::hasColumn('receipts', 'unit_id')) {
                    $table->dropForeign(['unit_id']);
                    $table->dropColumn('unit_id');
                }
            });
        }

        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (Schema::hasColumn('payments', 'unit_id')) {
                    $table->dropForeign(['unit_id']);
                    $table->dropColumn('unit_id');
                }
            });
        }
    }
};
