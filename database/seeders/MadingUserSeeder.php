<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MadingUserSeeder extends Seeder
{
    public function run()
    {
        User::firstOrCreate(
            ['username' => 'mading'],
            [
                'name' => 'Display Mading',
                'email' => 'mading@nurulilmi.id',
                'password' => Hash::make('password'), // Password default
                'role' => 'mading', // Role khusus mading
                'status' => 'aktif'
            ]
        );
    }
}
