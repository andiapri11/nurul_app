<?php
/**
 * Migration to add proof fields (nota and photo) to income_expenses table.
 * This ensures that expenses recorded from procurements carry their reported proofs.
 */

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
            $table->string('nota')->nullable()->after('description');
            $table->string('photo')->nullable()->after('nota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('income_expenses', function (Blueprint $table) {
            $table->dropColumn(['nota', 'photo']);
        });
    }
};
