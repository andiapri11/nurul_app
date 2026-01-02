<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'role' => 'administrator',
            'password' => bcrypt('password'),
        ]);

        $roles = ['direktur', 'kepala sekolah', 'wakil kepala sekolah', 'staff'];

        foreach ($roles as $role) {
            User::factory()->create([
                'name' => ucfirst($role),
                'email' => str_replace(' ', '', $role) . '@admin.com',
                'password' => bcrypt('password'),
                'role' => $role,
            ]);
        }

        $this->call([
            JabatanSeeder::class,
            SubjectSeeder::class,
            StudentSeeder::class,
            SemesterSeeder::class,
            FinancialAdminSeeder::class,
            MadingUserSeeder::class,
            AnnouncementSeeder::class,
        ]);
    }
}
