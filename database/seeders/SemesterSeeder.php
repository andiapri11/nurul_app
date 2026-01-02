<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Semester::firstOrCreate(['name' => 'Ganjil'], ['status' => 'active']);
        Semester::firstOrCreate(['name' => 'Genap'], ['status' => 'inactive']);
    }
}
