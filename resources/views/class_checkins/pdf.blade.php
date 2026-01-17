<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Check-in Kelas</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .badge {
            padding: 2px 5px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }
        .bg-success { background-color: #d1e7dd; color: #0f5132; }
        .bg-warning { background-color: #fff3cd; color: #664d03; }
        .bg-secondary { background-color: #e2e3e5; color: #41464b; }
        
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="header">
        @php
            $schoolName = \App\Models\Setting::where('key', 'school_name')->value('value') ?? \App\Models\Setting::where('key', 'app_name')->value('value') ?? 'LPT NURUL ILMI';
            $appAddress = \App\Models\Setting::where('key', 'app_address')->value('value') ?? '';
        @endphp
        <h2>LAPORAN CHECK-IN KELAS</h2>
        <h3>{{ $schoolName }}</h3>
        @if($appAddress) <p style="font-size: 10px; margin-top: -5px; margin-bottom: 2px;">{{ $appAddress }}</p> @endif
        <p style="margin-bottom: 5px;">
            Tahun Ajaran: {{ $filterSummary['academic_year'] ?? '-' }} | 
            Unit: {{ $filterSummary['unit'] ?? '-' }} | 
            Kelas: {{ $filterSummary['class'] ?? '-' }}
        </p>
        <p style="margin-top: 0;">
            Periode: 
            @if($filterSummary['start_date'])
                {{ \Carbon\Carbon::parse($filterSummary['start_date'])->format('d M Y') }}
                s/d 
                {{ $filterSummary['end_date'] ? \Carbon\Carbon::parse($filterSummary['end_date'])->format('d M Y') : 'Seterusnya' }}
            @else
                Semua Waktu
            @endif
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Waktu</th>
                <th width="15%">Guru</th>
                <th width="10%">Kelas</th>
                <th width="15%">Mapel</th>
                <th width="10%">Status</th>
                <th>Keterangan</th>
                <th width="10%">Bukti</th>
            </tr>
        </thead>
        <tbody>
            @forelse($checkins as $index => $checkin)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        {{ $checkin->checkin_time->format('d/m/Y') }}<br>
                        {{ $checkin->checkin_time->format('H:i') }}
                    </td>
                    <td>{{ $checkin->user->name ?? '-' }}</td>
                    <td>{{ $checkin->schedule->schoolClass->name ?? '-' }}</td>
                    <td>{{ $checkin->schedule->subject->name ?? '-' }}</td>
                    <td>
                        @if($checkin->status == 'ontime')
                            <span class="badge bg-success">Tepat Waktu</span>
                        @elseif($checkin->status == 'late')
                            <span class="badge bg-warning">Terlambat</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($checkin->status) }}</span>
                        @endif
                    </td>
                    <td>{{ $checkin->notes ?? '-' }}</td>
                    <td style="text-align: center;">
                        @if($checkin->photo)
                            <img src="{{ asset('storage/' . $checkin->photo) }}" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #ddd;">
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data check-in untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
