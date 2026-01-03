<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportSqlData extends Command
{
    protected $signature = 'db:import-sql';
    protected $description = 'Import local SQL export file into database';

    public function handle()
    {
        $filename = 'data_nurul_ilmi.sql';
        $path = base_path($filename);

        if (!File::exists($path)) {
            $this->error("File $filename tidak ditemukan di folder root!");
            return;
        }

        $this->info("Sedang membaca file SQL... Harap tunggu.");
        
        try {
            // Read file content
            $sql = File::get($path);

            // Execute raw SQL
            // Note: This might fail if the file is extremely large (>50MB) 
            // but usually works for school apps.
            DB::unprepared($sql);

            $this->info("BERHASIL! Data telah diimpor ke database Dokploy.");
            
            // Optional: Delete file after import for security
            // File::delete($path);
            
        } catch (\Exception $e) {
            $this->error("Gagal impor: " . $e->getMessage());
        }
    }
}
