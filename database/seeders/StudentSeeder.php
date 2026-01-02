<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Unit;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Ensure a Unit exists
            $unit = Unit::firstOrCreate(
                ['name' => 'SD NURUL ILMI'],
                ['address' => 'Jl. Contoh No. 123', 'phone' => '08123456789']
            );

            // Ensure a Class exists
            $class = SchoolClass::firstOrCreate(
                ['name' => '1 SD', 'unit_id' => $unit->id]
            );

            // Create User
            $user = User::create([
                'name' => 'siswa_demo',
                'email' => 'siswa@demo.com',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]);

            // Create Student
            Student::create([
                'user_id' => $user->id,
                'unit_id' => $unit->id,
                'class_id' => $class->id,
                'nis' => '1001',
                'nisn' => '001001001',
                'nama_lengkap' => 'Siswa Demo',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2015-01-01',
                'alamat' => 'Jl. Kebahagiaan No. 1',
                'nama_wali' => 'Wali Demo',
                'no_hp_wali' => '081298765432',
                'status' => 'aktif',
            ]);
            
            $this->command->info('Sample student created: siswa@demo.com / password');
        });
    }
}
