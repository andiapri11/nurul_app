<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Jurnal Kelas - {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 8.5pt;
            margin: 0;
            padding: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            text-transform: uppercase;
            font-size: 14pt;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            font-size: 8pt;
        }
        .info-table td {
            padding: 2px 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 4px 3px;
            text-align: left;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #f2f2f2;
            text-transform: uppercase;
            font-size: 7.5pt;
        }
        .text-center { text-align: center !important; }
        .badge {
            display: inline-block;
            padding: 2px 4px;
            border: 1px solid #666;
            font-size: 7pt;
            text-transform: uppercase;
            white-space: nowrap;
            border-radius: 3px;
            font-weight: bold;
            min-width: 60px;
        }
        @media print {
            @page {
                size: A4 landscape;
                margin: 0.5cm;
            }
            .no-print { display: none; }
        }
        .no-print {
            background: #444;
            color: white;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .no-print button {
            padding: 5px 15px;
            cursor: pointer;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print">
        Halaman ini khusus untuk dicetak. Jika dialog cetak tidak otomatis muncul, klik: 
        <button onclick="window.print()">CETAK SEKARANG</button>
    </div>

    <div class="header">
        <h2>LAPORAN JURNAL MENGAJAR GURU</h2>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><b>UNIT SEKOLAH</b></td>
            <td width="35%">: {{ $unitId ? (\App\Models\Unit::find($unitId)->name ?? 'Unit Tidak Ditemukan') : 'Semua Unit' }}</td>
            <td width="15%"><b>KELAS</b></td>
            <td width="35%">: {{ $classId ? (\App\Models\SchoolClass::find($classId)->name ?? 'Kelas Tidak Ditemukan') : 'Semua Kelas' }}</td>
        </tr>
        <tr>
            <td><b>TAHUN PELAJARAN</b></td>
            <td>: {{ $academicYearId ? (\App\Models\AcademicYear::find($academicYearId)->name ?? '-') : '-' }}</td>
            <td><b>TANGGAL</b></td>
            <td>: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="3%" class="text-center">NO</th>
                <th width="12%">JAM JADWAL</th>
                <th width="15%">MATA PELAJARAN</th>
                <th width="12%">WAKTU CHECK-IN</th>
                <th width="8%">KELAS</th>
                <th width="12%">GURU PENGAJAR</th>
                <th width="8%">BUKTI</th>
                <th width="12%" class="text-center">STATUS</th>
                <th>KETERANGAN / MATERI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($checkins as $index => $c)
                @if($c->status == 'holiday')
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 30px; background-color: #fff5f5;">
                            <h3 style="margin: 0; color: #dc3545;">HARI LIBUR</h3>
                            <p style="margin: 5px 0 0 0; font-size: 11pt;">{{ $c->holiday_name }}</p>
                        </td>
                    </tr>
                    @break
                @endif
                <tr @if($c->status == 'absent') style="background-color: #fff0f0;" @elseif($c->status == 'break') style="background-color: #fff9db;" @endif>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="font-size: 7.5pt; white-space: nowrap;">
                        {{ $c->schedule ? substr($c->schedule->start_time, 0, 5) . ' - ' . substr($c->schedule->end_time, 0, 5) : '-' }}
                    </td>
                    <td>
                        @if($c->status == 'break')
                            <b>{{ $c->notes }}</b>
                        @else
                            <b>{{ $c->schedule?->subject?->name ?? 'Mata Pelajaran Tidak Ditemukan' }}</b><br>
                            <small style="font-size: 7pt; color: #666;">{{ $c->schedule?->unit?->name ?? '-' }}</small>
                        @endif
                    </td>
                    <td class="@if($c->status == 'break') text-center @endif" style="font-size: 7.5pt;">
                        @if($c->checkin_time)
                            {{ $c->checkin_time->format('H:i:s') }}<br>
                            <small style="font-size: 6.5pt;">{{ $c->checkin_time->translatedFormat('d M Y') }}</small>
                        @elseif($c->status == 'break')
                            -
                        @elseif($c->status == 'future')
                            <span style="color: #6c757d; font-size: 7pt;">BELUM MULAI</span>
                        @else
                            <span style="color: #dc3545; font-size: 7pt;">BELUM CHECK-IN</span>
                        @endif
                    </td>
                    <td>{{ $c->schedule?->schoolClass?->name ?? '-' }}</td>
                    <td>
                        @if($c->status != 'break')
                            @php
                                $userName = $c->user?->name ?? $c->schedule?->teacher?->name;
                                $userNip = $c->user?->nip ?? $c->schedule?->teacher?->nip;
                            @endphp
                            <b>{{ $userName ?? 'Guru Tidak Ditemukan' }}</b><br>
                            <small>NIP: {{ $userNip ?? '-' }}</small>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if($c->photo)
                            <img src="{{ asset('storage/' . $c->photo) }}" style="max-width: 50px; max-height: 50px; border: 1px solid #ddd;">
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="badge" style="
                            @if($c->status == 'absent') border-color: #dc3545; color: #721c24; background-color: #f8d7da;
                            @elseif($c->status == 'break') border-color: #0d6efd; color: #084298; background-color: #cfe2ff;
                            @elseif($c->status == 'ontime' || $c->status == 'present') border-color: #198754; color: #0f5132; background-color: #d1e7dd;
                            @elseif($c->status == 'late') border-color: #fd7e14; color: #664d03; background-color: #fff3cd;
                            @elseif($c->status == 'future') border-color: #6c757d; color: #6c757d; background-color: #f8f9fa;
                            @endif">
                            @if($c->status == 'ontime' || $c->status == 'present') HADIR @elseif($c->status == 'late') TERLAMBAT @elseif($c->status == 'absent') TIDAK HADIR @elseif($c->status == 'break') ISTIRAHAT @elseif($c->status == 'future') BELUM MULAI @else {{ strtoupper($c->status) }} @endif
                        </div>
                    </td>
                    <td>
                        @if($c->status == 'break')
                            <small><b>ISTIRAHAT</b></small>
                        @else
                            {{ $c->notes ?: '-' }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 50px;">Tidak ada data jadwal atau jurnal pada filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 15px; text-align: right; font-size: 8pt;">
        <div style="display: inline-block; text-align: center;">
            Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}<br><br><br>
            ( ............................................ )
        </div>
    </div>
</body>
</html>
