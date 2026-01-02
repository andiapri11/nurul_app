<?php

/**
 * Additional Setup Routes
 * File ini berisi routes untuk setup dan debugging yang bisa di-include di web.php
 */

// Route untuk halaman setup subjects
Route::get('/setup-subjects', function() {
    return view('setup-subjects');
})->name('setup.subjects');

// Route untuk menjalankan SubjectSeeder dari web
Route::get('/run-subject-seeder', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'SubjectSeeder',
            '--force' => true
        ]);
        
        $output = \Illuminate\Support\Facades\Artisan::output();
        
        $html = "
        <html>
        <head>
            <title>Subject Seeder Result</title>
            <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>
        </head>
        <body class='p-5'>
            <div class='container'>
                <div class='alert alert-success'>
                    <h1><i class='bi bi-check-circle-fill'></i> Subject Seeder berhasil dijalankan!</h1>
                </div>
                <div class='card'>
                    <div class='card-header bg-dark text-white'>
                        <h5>Output:</h5>
                    </div>
                    <div class='card-body'>
                        <pre style='background: #2d3748; color: #48bb78; padding: 20px; border-radius: 5px; max-height: 400px; overflow-y: auto;'>" . 
                        htmlspecialchars($output) . 
                        "</pre>
                    </div>
                </div>
                <div class='mt-4'>
                    <a href='/debug-db' class='btn btn-info me-2'>
                        <i class='bi bi-database'></i> Cek Database
                    </a>
                    <a href='/gurukaryawans' class='btn btn-primary me-2'>
                        <i class='bi bi-people'></i> Kembali ke Guru/Karyawan
                    </a>
                    <a href='/setup-subjects' class='btn btn-secondary'>
                        <i class='bi bi-arrow-left'></i> Kembali ke Setup
                    </a>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $html;
        
    } catch (\Exception $e) {
        $html = "
        <html>
        <head>
            <title>Seeder Error</title>
            <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>
        </head>
        <body class='p-5'>
            <div class='container'>
                <div class='alert alert-danger'>
                    <h1><i class='bi bi-x-circle-fill'></i> Error menjalankan SubjectSeeder!</h1>
                </div>
                <div class='card'>
                    <div class='card-header bg-danger text-white'>
                        <h5>Error Message:</h5>
                    </div>
                    <div class='card-body'>
                        <pre style='background: #fee; color: #c00; padding: 20px; border-radius: 5px;'>" . 
                        htmlspecialchars($e->getMessage()) . 
                        "\n\nStack Trace:\n" .
                        htmlspecialchars($e->getTraceAsString()) .
                        "</pre>
                    </div>
                </div>
                <div class='mt-4'>
                    <a href='/setup-subjects' class='btn btn-secondary'>
                        <i class='bi bi-arrow-left'></i> Kembali ke Setup
                    </a>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $html;
    }
})->name('run.subject.seeder');

// Route untuk menjalankan migration khusus
Route::get('/run-migration-jabatan', function() {
    try {
        // 1. Run Migration
        \Illuminate\Support\Facades\Artisan::call('migrate', [
            '--path' => 'database/migrations/2025_12_16_140000_create_user_jabatan_units_table.php',
            '--force' => true
        ]);
        $migOutput = \Illuminate\Support\Facades\Artisan::output();

        // 2. Run Jabatan Seeder
        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'JabatanSeeder',
            '--force' => true
        ]);
        $seedOutput = \Illuminate\Support\Facades\Artisan::output();
        
        return "<h1>Setup Jabatan Berhasil!</h1>" .
               "<h3>Migration Output:</h3><pre>" . $migOutput . "</pre>" .
               "<h3>Seeder Output:</h3><pre>" . $seedOutput . "</pre>" .
               "<p><a href='/gurukaryawans'>Kembali ke Menu Guru</a></p>";
    } catch (\Exception $e) {
        return "<h1>Setup Gagal!</h1><pre>" . $e->getMessage() . "</pre>";
    }
});

// Route untuk menjalankan migration schedules
Route::get('/run-migration-schedule', function() {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--path' => 'database/migrations/2025_12_16_150000_create_schedules_table.php', '--force' => true]);
    return "<h1>Migration Schedule Berhasil!</h1><p>Tabel 'schedules' sudah dibuat.</p><a href='/schedules'>Lanjut ke Menu Jadwal</a>";
});

// Route untuk MENGHAPUS tipe kelas
Route::get('/run-migration-tipe-kelas', function() {
    try {
        if (!Schema::hasColumn('students', 'tipe_kelas')) {
            return "<h1>Kolom Sudah Terhapus.</h1><p>Database bersih.</p><a href='/dashboard'>Kembali</a>";
        }

        \Illuminate\Support\Facades\Artisan::call('migrate', [
            '--path' => 'database/migrations/2025_12_16_164500_drop_tipe_kelas_from_students.php', 
            '--force' => true
        ]);
        return "<h1>Fitur Dihapus!</h1><p>Kolom 'tipe_kelas' berhasil dihapus dari database.</p><a href='/dashboard'>Kembali</a>";
    } catch (\Exception $e) {
        return "<h1>Gagal Hapus!</h1><pre>" . $e->getMessage() . "</pre>";
    }
});


Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
    return "<h1>Cache Cleared!</h1>";
});

Route::get('/debug-db-student', function() {
    $table = 'students';
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing($table);
    $hasColumn = in_array('tipe_kelas', $columns);
    $type = 'N/A';
    
    if ($hasColumn) {
        $type = \Illuminate\Support\Facades\Schema::getColumnType($table, 'tipe_kelas'); // string (karena enum dibaca string di dbal lama) atau enum
    }

    echo "<h1>Debug Table Students</h1>";
    echo "<p>Has Column 'tipe_kelas': " . ($hasColumn ? '<b>YES</b>' : '<b style="color:red">NO</b>') . "</p>";
    echo "<p>Column Type: $type</p>";
    echo "<p>All Columns: " . implode(', ', $columns) . "</p>";
    
    // Cek value student id 1 (sample)
    $s = \App\Models\Student::first();
    if($s) {
        echo "<hr><h3>Sample Data (ID: $s->id)</h3>";
        echo "Tipe Kelas Value: " . $s->tipe_kelas;
        echo "<br>Raw Attributes: <pre>" . print_r($s->getAttributes(), true) . "</pre>";
    }
});
