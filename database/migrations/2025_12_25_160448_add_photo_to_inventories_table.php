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
        Schema::table('inventories', function (Blueprint $table) {
            // Ensuring description exists, based on earlier steps I recall description exists?
            // Wait, I haven't seen the inventories schema recently. It usually has description.
            // If uncertain, just put it after 'price' or at the end. Step 4173 showed `price`.
            // I'll put it after `price` to be safe.
            if (!Schema::hasColumn('inventories', 'photo')) {
                $table->string('photo')->nullable()->after('price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
};
