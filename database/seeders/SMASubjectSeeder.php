<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class SMASubjectSeeder extends Seeder
{
    /**
     * Run the database seeds for SMA unit.
     */
    public function run(): void
    {
        // 1. Find the SMA unit
        $smaUnit = Unit::where('name', 'LIKE', '%SMA%')->first();

        if (!$smaUnit) {
            $this->command->error("Unit SMA tidak ditemukan!");
            return;
        }

        $this->command->info("Mengupdate data pembelajaran untuk unit: {$smaUnit->name}");

        // 2. Delete existing subjects for this unit
        // We use DB::transaction to ensure data integrity
        DB::transaction(function () use ($smaUnit) {
            Subject::where('unit_id', $smaUnit->id)->delete();

            $subjects = [
                ['name' => 'PAI dan Budi Pekerti', 'code' => 'PAI'],
                ['name' => 'Pendidikan Pancasila', 'code' => 'PP'],
                ['name' => 'Bahasa Indonesia', 'code' => 'BIND'],
                ['name' => 'Matematika', 'code' => 'MTK'],
                ['name' => 'Fisika', 'code' => 'FIS'],
                ['name' => 'Kimia', 'code' => 'KIM'],
                ['name' => 'Biologi', 'code' => 'BIO'],
                ['name' => 'Sosiologi', 'code' => 'SOS'],
                ['name' => 'Ekonomi', 'code' => 'EKO'],
                ['name' => 'Sejarah', 'code' => 'SEJ'],
                ['name' => 'Geografi', 'code' => 'GEO'],
                ['name' => 'B.Inggris', 'code' => 'BING'],
                ['name' => 'PJOK', 'code' => 'PJOK'],
                ['name' => 'Informatika', 'code' => 'INF'],
                ['name' => 'Seni Budaya', 'code' => 'SB'],
                ['name' => 'B.Arab', 'code' => 'BARB'],
                ['name' => 'PKWU', 'code' => 'PKWU'],
                ['name' => 'Mulok Ketahan Pangan Lokal', 'code' => 'MULOK'],
                ['name' => 'Coding dan AI', 'code' => 'COAI'],
                ['name' => 'TTQ', 'code' => 'TTQ'],
                ['name' => 'Prakib', 'code' => 'PKB'],
                ['name' => 'Hadits', 'code' => 'HDT'],
                ['name' => 'Sirah', 'code' => 'SRH'],
            ];

            foreach ($subjects as $subject) {
                Subject::create([
                    'unit_id' => $smaUnit->id,
                    'name' => $subject['name'],
                    'code' => $subject['code'],
                ]);
            }
        });

        $this->command->info("Data pembelajaran SMA berhasil diupdate.");
    }
}
