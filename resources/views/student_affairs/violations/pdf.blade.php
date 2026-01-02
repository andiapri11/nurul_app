<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pelanggaran Siswa</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2, .header h3 {
            margin: 2px 0;
        }
        .badge {
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }
        .text-center { text-align: center; }
        
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 15px; cursor: pointer; background: #0d6efd; color: white; border: none; border-radius: 4px;">
            Cetak / Simpan PDF
        </button>
    </div>

    <div class="header">
        @php
            $appName = \App\Models\Setting::where('key', 'app_name')->value('value') ?? 'LPT NURUL ILMI';
            $appAddress = \App\Models\Setting::where('key', 'app_address')->value('value') ?? '';
        @endphp
        <h2>REKAPITULASI PELANGGARAN SISWA</h2>
        <h3>{{ $appName }}</h3>
        @if($appAddress) <p style="font-size: 10px; margin-top: -5px; margin-bottom: 2px;">{{ $appAddress }}</p> @endif
        <p style="margin-top: 10px; font-size: 12px;">
            Unit: <strong>{{ $filterSummary['unit'] }}</strong> | 
            Tahun Ajaran: <strong>{{ $filterSummary['academic_year'] }}</strong> | 
            Kelas: <strong>{{ $filterSummary['class'] }}</strong> | 
            Status: <strong>{{ $filterSummary['status'] }}</strong>
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Tanggal</th>
                <th width="20%">Nama Siswa</th>
                <th width="10%">Kelas</th>
                <th width="8%">Jenis</th>
                <th width="20%">Deskripsi Pelanggaran</th>
                <th width="5%">Poin</th>
                <th width="15%">Tindak Lanjut</th>
                <th width="8%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($violations as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                <td>{{ $item->student->nama_lengkap }}</td>
                <td class="text-center">{{ $item->student->schoolClass->first()->name ?? '-' }}</td>
                <td class="text-center">{{ $item->violation_type }}</td>
                <td>{{ $item->description }}</td>
                <td class="text-center">{{ $item->points }}</td>
                <td>
                    @if($item->follow_up)
                        <div><strong>Aksi:</strong> {{ $item->follow_up }}</div>
                    @endif
                    @if($item->follow_up_result)
                        <div style="margin-top: 4px;"><strong>Hasil:</strong> {{ $item->follow_up_result }}</div>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->follow_up_status == 'pending')
                         Pending
                    @elseif($item->follow_up_status == 'process')
                         Proses
                    @elseif($item->follow_up_status == 'done')
                         Selesai
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data pelanggaran.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
