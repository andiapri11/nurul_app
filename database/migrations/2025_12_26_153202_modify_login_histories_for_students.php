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
        Schema::table('login_histories', function (Blueprint $table) {
            // 1. Drop old foreign key
            $table->dropForeign(['user_id']);
            
            // 2. Modify user_id to be nullable
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            // 3. Re-add foreign key for user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // 4. Add user_siswa_id for students
            $table->foreignId('user_siswa_id')->nullable()->after('user_id')->constrained('user_siswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('login_histories', function (Blueprint $table) {
            $table->dropForeign(['user_siswa_id']);
            $table->dropColumn('user_siswa_id');
            
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
