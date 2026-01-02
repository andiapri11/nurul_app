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
            $table->string('director_status')->default('Pending')->after('principal_note'); // Pending, Approved, Rejected
            $table->foreignId('director_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('director_note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('damage_reports', function (Blueprint $table) {
            //
        });
    }
};
