<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\Unit;
use Carbon\Carbon;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $units = Unit::all();

        foreach ($units as $unit) {
            // Running text
            Announcement::create([
                'unit_id' => $unit->id,
                'title' => 'Info Penting',
                'content' => 'Selamat datang di ' . $unit->name . '. Jagalah kebersihan dan kedisiplinan. Ujian Tengah Semester akan dilaksanakan mulai tanggal 20 Desember.',
                'type' => 'running_text',
                'is_active' => true,
            ]);

            // News / Poster
            Announcement::create([
                'unit_id' => $unit->id,
                'title' => 'Kegiatan Ekskul',
                'content' => 'Pendaftaran kegiatan ekstrakurikuler dibuka sampai hari Jumat ini.',
                'type' => 'news',
                'is_active' => true,
            ]);
        }
    }
}
