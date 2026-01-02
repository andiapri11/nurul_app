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
                if (!Schema::hasColumn('receipts', 'category')) {
                    $table->string('category')->nullable()->after('unit_id');
                }
                if (!Schema::hasColumn('receipts', 'amount')) {
                    $table->decimal('amount', 15, 2)->default(0)->after('category');
                }
                if (!Schema::hasColumn('receipts', 'transaction_date')) {
                    $table->date('transaction_date')->nullable()->after('amount');
                }
                if (!Schema::hasColumn('receipts', 'payment_method')) {
                    $table->string('payment_method')->nullable()->after('transaction_date');
                }
                if (!Schema::hasColumn('receipts', 'description')) {
                    $table->text('description')->nullable()->after('payment_method');
                }
                if (!Schema::hasColumn('receipts', 'reference_number')) {
                    $table->string('reference_number')->nullable()->after('description');
                }
                if (!Schema::hasColumn('receipts', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained('users')->after('reference_number');
                }
            });
        }

        // Fix Payments Table
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'category')) {
                    $table->string('category')->nullable()->after('unit_id');
                }
                if (!Schema::hasColumn('payments', 'amount')) {
                    $table->decimal('amount', 15, 2)->default(0)->after('category');
                }
                if (!Schema::hasColumn('payments', 'transaction_date')) {
                    $table->date('transaction_date')->nullable()->after('amount');
                }
                if (!Schema::hasColumn('payments', 'recipient')) {
                    $table->string('recipient')->nullable()->after('transaction_date');
                }
                if (!Schema::hasColumn('payments', 'payment_method')) {
                    $table->string('payment_method')->nullable()->after('recipient');
                }
                if (!Schema::hasColumn('payments', 'description')) {
                    $table->text('description')->nullable()->after('payment_method');
                }
                if (!Schema::hasColumn('payments', 'reference_number')) {
                    $table->string('reference_number')->nullable()->after('description');
                }
                if (!Schema::hasColumn('payments', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained('users')->after('reference_number');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Dropping columns individually is tedious and usually unnecessary for "fix" migrations
    }
};
