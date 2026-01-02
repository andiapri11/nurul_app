<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Database - Mata Pelajaran</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .setup-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 800px;
            width: 100%;
            overflow: hidden;
        }
        .setup-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .setup-header h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .setup-body {
            padding: 40px;
        }
        .instruction-step {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .instruction-step h5 {
            color: #667eea;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .code-block {
            background: #2d3748;
            color: #f7fafc;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            position: relative;
            margin: 10px 0;
        }
        .copy-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #667eea;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.8rem;
        }
        .copy-btn:hover {
            background: #764ba2;
        }
        .alert-custom {
            border-left: 4px solid #ffc107;
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .success-check {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="setup-card">
        <div class="setup-header">
            <i class="bi bi-database-fill-gear" style="font-size: 3rem;"></i>
            <h1>Setup Database</h1>
            <p>Menambahkan Data Mata Pelajaran</p>
        </div>
        
        <div class="setup-body">
            <div class="alert-custom">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <strong>Perhatian!</strong> Jalankan seeder untuk menambahkan data mata pelajaran ke database.
            </div>

            <div class="instruction-step">
                <h5><i class="bi bi-1-circle-fill"></i> Opsi 1: Menggunakan Laragon Terminal (Recommended)</h5>
                <ol>
                    <li>Klik kanan icon Laragon di system tray</li>
                    <li>Pilih <strong>Terminal</strong></li>
                    <li>Ketik atau copy-paste command berikut:</li>
                </ol>
                <div class="code-block">
                    <button class="copy-btn" onclick="copyToClipboard('cmd1')">
                        <i class="bi bi-clipboard"></i> Copy
                    </button>
                    <code id="cmd1">cd c:\laragon\www\nurul && php artisan db:seed --class=SubjectSeeder</code>
                </div>
                <li>Tekan <kbd>Enter</kbd></li>
            </div>

            <div class="instruction-step">
                <h5><i class="bi bi-2-circle-fill"></i> Opsi 2: Menggunakan Command Prompt</h5>
                <ol>
                    <li>Tekan <kbd>Windows + R</kbd></li>
                    <li>Ketik <code>cmd</code> dan tekan <kbd>Enter</kbd></li>
                    <li>Copy-paste command berikut:</li>
                </ol>
                <div class="code-block">
                    <button class="copy-btn" onclick="copyToClipboard('cmd2')">
                        <i class="bi bi-clipboard"></i> Copy
                    </button>
                    <code id="cmd2">cd c:\laragon\www\nurul && c:\laragon\bin\php\php.exe artisan db:seed --class=SubjectSeeder</code>
                </div>
                <p class="mt-3"><em>Catatan: Sesuaikan path PHP jika berbeda di komputer Anda</em></p>
            </div>

            <div class="instruction-step">
                <h5><i class="bi bi-3-circle-fill"></i> Opsi 3: Menjalankan Semua Seeders</h5>
                <p>Jika ingin menjalankan semua seeder sekaligus:</p>
                <div class="code-block">
                    <button class="copy-btn" onclick="copyToClipboard('cmd3')">
                        <i class="bi bi-clipboard"></i> Copy
                    </button>
                    <code id="cmd3">cd c:\laragon\www\nurul && php artisan db:seed</code>
                </div>
            </div>

            <div class="success-check">
                <h5><i class="bi bi-check-circle-fill text-success"></i> Cara Mengecek Apakah Berhasil</h5>
                <p>Setelah menjalankan seeder, Anda akan melihat output seperti ini:</p>
                <div class="code-block">
<code>Subject 'Pendidikan Agama Islam' created for unit 'SD NURUL ILMI'
Subject 'Bahasa Indonesia' created for unit 'SD NURUL ILMI'
Subject 'Matematika' created for unit 'SD NURUL ILMI'
...</code>
                </div>
            </div>

            <div class="instruction-step">
                <h5><i class="bi bi-4-circle-fill"></i> Setelah Seeder Berhasil</h5>
                <ol>
                    <li>Kembali ke halaman Edit Guru/Karyawan</li>
                    <li>Refresh halaman (F5)</li>
                    <li>Buka Developer Console (F12) → Tab Console</li>
                    <li>Pilih Unit Pendidikan dari dropdown</li>
                    <li>Dropdown "Pilih Mata Pelajaran" seharusnya sudah muncul dan berisi data</li>
                </ol>
            </div>

            <div class="text-center mt-4">
                <a href="{{ url('/gurukaryawans') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-arrow-left"></i> Kembali ke Halaman Guru/Karyawan
                </a>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const text = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert('Command berhasil di-copy ke clipboard!');
            }).catch(err => {
                console.error('Error copying text: ', err);
            });
        }
    </script>
</body>
</html>
