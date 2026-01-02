<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Unit;
use App\Models\UserJabatanUnit;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FinancialAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Create Jabatan 'Kepala Keuangan' if not exists
            $jabatan = Jabatan::firstOrCreate(
                ['nama_jabatan' => 'Kepala Keuangan'],
                ['kategori' => 'staff']
            );

            // 2. Create User 'Kepala Keuangan'
            // Using a unique email to avoid unique constraint violation
            $email = 'keuangan@nurulilmi.id';
            
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Kepala Keuangan',
                    'username' => 'admin_keuangan',
                    'password' => Hash::make('password'),
                    'role' => 'staff', // Using 'staff' as the role based on previous context
                    'status' => 'aktif',
                ]
            );

            // If user already existed, update role/password just in case
            if (!$user->wasRecentlyCreated) {
                $user->update([
                    'role' => 'staff',
                    'password' => Hash::make('password'),
                    'status' => 'aktif',
                ]);
            }

            // 3. Assign Jabatan to User with a Unit
            // Get a default unit or create one
            $unit = Unit::first();
            if (!$unit) {
                $unit = Unit::create([
                    'name' => 'Kantor Pusat',
                    'address' => 'Jl. Utama',
                    'phone' => '-'
                ]);
            }

            // Attach via user_jabatan_units
            UserJabatanUnit::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'jabatan_id' => $jabatan->id,
                    'unit_id' => $unit->id
                ]
            );
            
            // Also attach compatibility relation if needed (jabatan_user pivot)
            // But User model uses 'jabatanUnits' mainly now? 
            // The User model has 'jabatans()' belongsToMany('jabatan_user').
            // Let's populate that too just in case.
            if (!$user->jabatans()->where('jabatan_id', $jabatan->id)->exists()) {
                $user->jabatans()->attach($jabatan->id);
            }

            $this->command->info("User 'Kepala Keuangan' created successfully.");
            $this->command->info("Email: $email");
            $this->command->info("Password: password");
        });
    }
}
