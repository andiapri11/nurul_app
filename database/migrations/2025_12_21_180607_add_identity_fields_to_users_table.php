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
        Schema::table('users', function (Blueprint $table) {
            $table->string('birth_place')->nullable()->after('email');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->enum('gender', ['L', 'P'])->nullable()->after('birth_date');
            $table->text('address')->nullable()->after('gender');
            $table->string('phone')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['birth_place', 'birth_date', 'gender', 'address', 'phone']);
        });
    }
};
