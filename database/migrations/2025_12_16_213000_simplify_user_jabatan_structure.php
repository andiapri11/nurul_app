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
        // 1. Drop old pivot table (jabatan_user) that is causing confusion
        Schema::dropIfExists('jabatan_user');

        // 2. Remove redundant foreign keys/columns from primary tables
        Schema::table('users', function (Blueprint $table) {
            // Check if column exists before dropping to be safe
            if (Schema::hasColumn('users', 'jabatan_id')) {
                // Drop FK first if it exists. Note: Constraint name might vary, trying standard convention
                // or just dropColumn might throw error if FK exists.
                // Best effort: drop foreign key then column.
                // Assuming standard naming 'users_jabatan_id_foreign'
                try {
                    $table->dropForeign(['jabatan_id']);
                } catch (\Exception $e) {
                    // Ignore if FK doesn't exist
                }
                $table->dropColumn('jabatan_id');
            }
        });

        // 3. Remove redundant logic from jabatans table if any
        // Assuming 'unit_id' in 'jabatans' meant "Jabatan ini hanya untuk unit X".
        // We can keep it or drop it, but for strict "User X has Jabatan Y at Unit Z", use user_jabatan_units.
        // Let's decide to KEEP unit_id in jabatans as a "Default Unit context" if needed, or remove if cleaner.
        // For now, let's just focus on cleaning the User <-> Jabatan link.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans')->nullOnDelete();
        });

        Schema::create('jabatan_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jabatan_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }
};
