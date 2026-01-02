<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Unit;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua unit
        $units = Unit::all();

        // Jika tidak ada unit, buat sample unit
        if ($units->isEmpty()) {
            echo "Tidak ada unit ditemukan. Jalankan UnitSeeder terlebih dahulu.\n";
            return;
        }

        // Daftar mata pelajaran umum untuk SD
        $sdSubjects = [
            ['name' => 'Pendidikan Agama Islam', 'code' => 'PAI'],
            ['name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'code' => 'PPKn'],
            ['name' => 'Bahasa Indonesia', 'code' => 'B.IND'],
            ['name' => 'Matematika', 'code' => 'MTK'],
            ['name' => 'Ilmu Pengetahuan Alam', 'code' => 'IPA'],
            ['name' => 'Ilmu Pengetahuan Sosial', 'code' => 'IPS'],
            ['name' => 'Seni Budaya dan Prakarya', 'code' => 'SBdP'],
            ['name' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'code' => 'PJOK'],
            ['name' => 'Bahasa Inggris', 'code' => 'B.ING'],
            ['name' => 'Bahasa Arab', 'code' => 'B.ARB'],
            ['name' => 'Al-Quran Hadist', 'code' => 'QH'],
            ['name' => 'Akidah Akhlak', 'code' => 'AA'],
            ['name' => 'Fiqih', 'code' => 'FQH'],
            ['name' => 'Sejarah Kebudayaan Islam', 'code' => 'SKI'],
        ];

        // Daftar mata pelajaran untuk SMP
        $smpSubjects = [
            ['name' => 'Pendidikan Agama Islam', 'code' => 'PAI'],
            ['name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'code' => 'PPKn'],
            ['name' => 'Bahasa Indonesia', 'code' => 'B.IND'],
            ['name' => 'Matematika', 'code' => 'MTK'],
            ['name' => 'Ilmu Pengetahuan Alam', 'code' => 'IPA'],
            ['name' => 'Ilmu Pengetahuan Sosial', 'code' => 'IPS'],
            ['name' => 'Bahasa Inggris', 'code' => 'B.ING'],
            ['name' => 'Seni Budaya', 'code' => 'SB'],
            ['name' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'code' => 'PJOK'],
            ['name' => 'Prakarya', 'code' => 'PKY'],
            ['name' => 'Bahasa Arab', 'code' => 'B.ARB'],
            ['name' => 'Al-Quran Hadist', 'code' => 'QH'],
            ['name' => 'Akidah Akhlak', 'code' => 'AA'],
            ['name' => 'Fiqih', 'code' => 'FQH'],
            ['name' => 'Sejarah Kebudayaan Islam', 'code' => 'SKI'],
        ];

        // Daftar mata pelajaran untuk SMA
        $smaSubjects = [
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

        foreach ($units as $unit) {
            $subjects = [];

            // Tentukan mata pelajaran berdasarkan nama unit
            if (stripos($unit->name, 'SD') !== false || stripos($unit->name, 'MI') !== false) {
                $subjects = $sdSubjects;
            } elseif (stripos($unit->name, 'SMP') !== false || stripos($unit->name, 'MTs') !== false) {
                $subjects = $smpSubjects;
            } elseif (stripos($unit->name, 'SMA') !== false || stripos($unit->name, 'MA') !== false || stripos($unit->name, 'SMK') !== false) {
                $subjects = $smaSubjects;
            } else {
                // Default ke SD jika tidak diketahui
                $subjects = $sdSubjects;
            }

            foreach ($subjects as $subject) {
                Subject::firstOrCreate([
                    'unit_id' => $unit->id,
                    'code' => $subject['code'],
                ], [
                    'name' => $subject['name'],
                ]);

                echo "Subject '{$subject['name']}' created for unit '{$unit->name}'\n";
            }
        }
    }
}
