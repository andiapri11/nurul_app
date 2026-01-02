<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Fix - Dropdown Mata Pelajaran</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .step-card {
            background: white;
            border-left: 5px solid #667eea;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 10px;
        }
        .btn-large {
            padding: 15px 30px;
            font-size: 1.2rem;
            border-radius: 10px;
        }
        pre {
            background: #2d3748;
            color: #48bb78;
            padding: 15px;
            border-radius: 8px;
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h1><i class="bi bi-tools"></i> Quick Fix - Dropdown Mata Pelajaran</h1>
                        <p class="mb-0">Solusi Cepat untuk Masalah Dropdown Tidak Muncul</p>
                    </div>
                    <div class="card-body p-4">
                        
                        <div class="step-card">
                            <h3><i class="bi bi-1-circle-fill text-primary"></i> Status Saat Ini</h3>
                            <?php
                            // Check database status
                            try {
                                $pdo = new PDO('mysql:host=localhost;dbname=lpt_nurul_ilmi', 'root', '');
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                
                                // Count units
                                $stmt = $pdo->query("SELECT COUNT(*) as count FROM units");
                                $unitsCount = $stmt->fetch()['count'];
                                
                                // Count subjects
                                $stmt = $pdo->query("SELECT COUNT(*) as count FROM subjects");
                                $subjectsCount = $stmt->fetch()['count'];
                                
                                // Get units with subjects
                                $stmt = $pdo->query("
                                    SELECT u.id, u.name, COUNT(s.id) as subject_count 
                                    FROM units u 
                                    LEFT JOIN subjects s ON u.id = s.unit_id 
                                    GROUP BY u.id, u.name
                                ");
                                $units = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                
                                echo "<div class='alert alert-info'>";
                                echo "<strong>Database Connected!</strong><br>";
                                echo "Total Units: <strong>$unitsCount</strong><br>";
                                echo "Total Subjects: <strong>$subjectsCount</strong>";
                                echo "</div>";
                                
                                if ($subjectsCount == 0) {
                                    echo "<div class='alert alert-danger'>";
                                    echo "<i class='bi bi-exclamation-triangle-fill'></i> <strong>MASALAH DITEMUKAN!</strong><br>";
                                    echo "Tidak ada mata pelajaran (subjects) di database.<br>";
                                    echo "Ini sebabnya dropdown tidak muncul!";
                                    echo "</div>";
                                    $needsSeed = true;
                                } else {
                                    echo "<div class='alert alert-success'>";
                                    echo "<i class='bi bi-check-circle-fill'></i> Database sudah memiliki $subjectsCount mata pelajaran.";
                                    echo "</div>";
                                    
                                    echo "<table class='table table-striped'>";
                                    echo "<thead><tr><th>Unit</th><th>Jumlah Mata Pelajaran</th></tr></thead>";
                                    echo "<tbody>";
                                    foreach ($units as $unit) {
                                        $color = $unit['subject_count'] > 0 ? 'text-success' : 'text-danger';
                                        echo "<tr>";
                                        echo "<td>{$unit['name']}</td>";
                                        echo "<td class='$color'><strong>{$unit['subject_count']}</strong></td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody></table>";
                                    
                                    $needsSeed = false;
                                }
                                
                            } catch (PDOException $e) {
                                echo "<div class='alert alert-danger'>";
                                echo "<strong>Database Error:</strong><br>";
                                echo htmlspecialchars($e->getMessage());
                                echo "</div>";
                                $needsSeed = true;
                            }
                            ?>
                        </div>

                        <?php if (isset($needsSeed) && $subjectsCount == 0): ?>
                        <div class="step-card">
                            <h3><i class="bi bi-2-circle-fill text-warning"></i> Solusi: Jalankan Seeder</h3>
                            <p><strong>Klik tombol di bawah untuk menambahkan data mata pelajaran:</strong></p>
                            <div class="text-center my-4">
                                <a href="/seed-dummy-data" class="btn btn-primary btn-large">
                                    <i class="bi bi-play-circle-fill"></i> Jalankan Seeder Sekarang
                                </a>
                            </div>
                            <p class="text-muted">Seeder akan menambahkan Units, Subjects, dan Classes ke database.</p>
                        </div>
                        <?php endif; ?>

                        <div class="step-card">
                            <h3><i class="bi bi-3-circle-fill text-info"></i> Test Dropdown</h3>
                            <p>Setelah menjalankan seeder, test dropdown di sini:</p>
                            <div class="text-center my-3">
                                <a href="/test-dropdown.html" class="btn btn-info btn-large" target="_blank">
                                    <i class="bi bi-bug-fill"></i> Buka Halaman Test
                                </a>
                            </div>
                        </div>

                        <div class="step-card">
                            <h3><i class="bi bi-4-circle-fill text-success"></i> Kembali ke Sistem</h3>
                            <div class="d-flex gap-3 justify-content-center flex-wrap">
                                <a href="/gurukaryawans" class="btn btn-success">
                                    <i class="bi bi-people-fill"></i> Halaman Guru/Karyawan
                                </a>
                                <a href="/dashboard" class="btn btn-primary">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                                <a href="/debug-db" class="btn btn-secondary">
                                    <i class="bi bi-database"></i> Debug Database
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
