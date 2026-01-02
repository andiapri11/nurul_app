<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create user_siswa table
        Schema::create('user_siswa', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('plain_password')->nullable();
            $table->string('photo')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Add user_siswa_id to students table
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('user_siswa_id')->nullable()->after('id')->constrained('user_siswa')->onDelete('cascade');
        });

        // 3. Migrate Data
        $studentUsers = DB::table('users')->where('role', 'siswa')->get();

        foreach ($studentUsers as $user) {
            // Insert into user_siswa
            $userSiswaId = DB::table('user_siswa')->insertGetId([
                'name' => $user->name,
                'username' => $user->username ?? null,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'plain_password' => $user->plain_password ?? null,
                'photo' => $user->photo,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);

            // Update students table
            DB::table('students')->where('user_id', $user->id)->update(['user_siswa_id' => $userSiswaId]);
            
            // Delete from users table
            DB::table('users')->where('id', $user->id)->delete();
        }

        // 4. Cleanup students table
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add user_id back to students
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
        });

        // Move data back to users
        $studentUsers = DB::table('user_siswa')->get();

        foreach ($studentUsers as $userSiswa) {
            $userId = DB::table('users')->insertGetId([
                'name' => $userSiswa->name,
                'username' => $userSiswa->username,
                'email' => $userSiswa->email,
                'password' => $userSiswa->password,
                'plain_password' => $userSiswa->plain_password,
                'photo' => $userSiswa->photo,
                'role' => 'siswa',
                'created_at' => $userSiswa->created_at,
                'updated_at' => $userSiswa->updated_at,
            ]);

            DB::table('students')->where('user_siswa_id', $userSiswa->id)->update(['user_id' => $userId]);
        }

        // Drop user_siswa table and column
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['user_siswa_id']);
            $table->dropColumn('user_siswa_id');
        });

        Schema::dropIfExists('user_siswa');
    }
};
