<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Tugas Mengajar</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #444;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #000;
        }
        .header p {
            margin: 3px 0;
            font-size: 12px;
        }
        .footer {
            margin-top: 30px;
            width: 100%;
        }
        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
        }
        .tag-badge {
            display: inline-block;
            padding: 2px 6px;
            background: #eee;
            border-radius: 4px;
            margin-right: 4px;
            margin-bottom: 4px;
            font-weight: bold;
            font-size: 9px;
        }
        .assignment-row {
            margin-bottom: 6px;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 4px;
        }
        .assignment-row:last-child {
            border-bottom: none;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            Cetak / Simpan PDF
        </button>
        <button onclick="window.history.back()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Kembali
        </button>
    </div>

    <div class="container">
        <div class="header">
            @php
                $headerTitle = $selectedUnit ? $selectedUnit->name : (\App\Models\Setting::where('key', 'app_name')->value('value') ?? 'LPT NURUL ILMI');
            @endphp
            <h1>REKAPITULASI PEMBAGIAN TUGAS MENGAJAR</h1>
            <p style="font-weight: bold; font-size: 14px; text-transform: uppercase;">{{ $headerTitle }}</p>
            <p>
                Tahun Pelajaran: {{ $selectedYear->start_year }}/{{ $selectedYear->end_year }} 
            </p>
            <p style="font-size: 10px; color: #666;">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="20%">Nama Guru / NIP</th>
                    <th width="15%">Unit Homebase</th>
                    <th>Detail Tugas Mengajar (Mapel & Kelas)</th>
                    <th width="10%">Total Kelas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $index => $teacher)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            <div style="font-weight: bold;">{{ $teacher->name }}</div>
                            <div style="font-size: 10px; color: #666;">NIP: {{ $teacher->nip ?? '-' }}</div>
                        </td>
                        <td>{{ $teacher->unit->name ?? '-' }}</td>
                        <td>
                            @php
                                $assignments = $teacher->teachingAssignments;
                            @endphp
                            @if($assignments->count() > 0)
                                @foreach($assignments as $assign)
                                    <div class="assignment-row">
                                        <span style="font-weight: 600;">{{ $assign->subject->name ?? '-' }}</span> 
                                        <span class="tag-badge">{{ $assign->schoolClass->name ?? '-' }}</span>
                                        <span style="font-size: 9px; color: #888;">({{ $assign->schoolClass->unit->name ?? '-' }})</span>
                                    </div>
                                @endforeach
                            @else
                                <span style="font-style: italic; color: #999;">Tidak ada tugas mengajar yang tercatat.</span>
                            @endif
                        </td>
                        <td style="text-align: center; font-weight: bold;">
                            {{ $assignments->count() }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">Tidak ada data guru untuk filter ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <div style="float: left; width: 300px;">
                <p>Keterangan:</p>
                <div style="font-size: 10px; color: #666;">
                    * Laporan ini dihasilkan secara otomatis oleh Sistem Informasi Akademik.<br>
                    * Data merupakan distribusi tugas mengajar aktif tahun akademik berjalan.
                </div>
            </div>
            
            <div class="signature-box">
                <p>Mengetahui,</p>
                <p style="margin-bottom: 50px;">
                    Wakasek Kurikulum {{ $selectedUnit ? $selectedUnit->name : '' }}
                </p>
                <p style="font-weight: bold; text-decoration: underline;">
                    ( {{ $wakasekName ?? '______________________' }} )
                </p>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Uncomment to auto-trigger print on load
            // window.print();
        }
    </script>
</body>
</html>
