<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    public function run()
    {
        // Disable FK checks to allow truncation
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Jabatan::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $jabatans = [
            // Struktural Utama
            ['nama_jabatan' => 'Kepala Sekolah', 'kode_jabatan' => 'kepala_sekolah', 'kategori' => 'tambahan'],
            
            // Wakil Kepala Sekolah Spesifik
            ['nama_jabatan' => 'Wakil Kurikulum', 'kode_jabatan' => 'wakil_kurikulum', 'kategori' => 'tambahan'],
            ['nama_jabatan' => 'Wakil Kesiswaan', 'kode_jabatan' => 'wakil_kesiswaan', 'kategori' => 'tambahan'],
            ['nama_jabatan' => 'Wakil Sarana Prasarana', 'kode_jabatan' => 'wakil_sarana_prasarana', 'kategori' => 'tambahan'],
            ['nama_jabatan' => 'Wakil Humas', 'kode_jabatan' => 'wakil_humas', 'kategori' => 'tambahan'],
            
            // Fungsional
            ['nama_jabatan' => 'Guru Mapel', 'kode_jabatan' => 'guru', 'kategori' => 'guru'],
            ['nama_jabatan' => 'Guru BK', 'kode_jabatan' => 'guru_bk', 'kategori' => 'tambahan'],
            ['nama_jabatan' => 'Wali Kelas', 'kode_jabatan' => 'wali_kelas', 'kategori' => 'tambahan'],
            ['nama_jabatan' => 'Pembina OSIS', 'kode_jabatan' => 'pembina_osis', 'kategori' => 'tambahan'],
            ['nama_jabatan' => 'Koordinator Ekstrakurikuler', 'kode_jabatan' => 'koordinator_ekstrakurikuler', 'kategori' => 'tambahan'],

            // Staff & Karyawan
            ['nama_jabatan' => 'Kepala TU', 'kode_jabatan' => 'kepala_tu', 'kategori' => 'staff'],
            ['nama_jabatan' => 'Staff TU', 'kode_jabatan' => 'staff_tu', 'kategori' => 'staff'],
            ['nama_jabatan' => 'Bendahara', 'kode_jabatan' => 'bendahara', 'kategori' => 'staff'],
            ['nama_jabatan' => 'Pustakawan', 'kode_jabatan' => 'pustakawan', 'kategori' => 'staff'],
            ['nama_jabatan' => 'Laboran', 'kode_jabatan' => 'laboran', 'kategori' => 'staff'],
            ['nama_jabatan' => 'Security', 'kode_jabatan' => 'security', 'kategori' => 'staff'],
            ['nama_jabatan' => 'Cleaning Service', 'kode_jabatan' => 'cleaning_service', 'kategori' => 'staff'],
        ];

        foreach ($jabatans as $jab) {
            Jabatan::updateOrCreate(
                ['nama_jabatan' => $jab['nama_jabatan']],
                [
                    'kode_jabatan' => $jab['kode_jabatan'],
                    'kategori' => $jab['kategori']
                ]
            );
        }
    }
}
