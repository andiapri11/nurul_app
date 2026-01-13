<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Jurnal Kelas - {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .info-table td {
            padding: 3px 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: left;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #f2f2f2;
            text-transform: uppercase;
            font-size: 8pt;
        }
        .text-center { text-align: center !important; }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border: 1px solid #666;
            font-size: 8pt;
            text-transform: uppercase;
        }
        @media print {
            @page {
                size: A4 landscape;
                margin: 1cm;
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
                <th width="10%">BUKTI</th>
                <th width="8%" class="text-center">STATUS</th>
                <th>KETERANGAN / MATERI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($checkins as $index => $c)
                <tr @if($c->status == 'absent') style="background-color: #fff0f0;" @elseif($c->status == 'break') style="background-color: #f0f7ff;" @endif>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ $c->schedule ? substr($c->schedule->start_time, 0, 5) . ' - ' . substr($c->schedule->end_time, 0, 5) : '-' }}
                    </td>
                    <td>
                        @if($c->status == 'break')
                            <b>{{ $c->notes }}</b>
                        @else
                            <b>{{ $c->schedule?->subject?->name ?? 'Mata Pelajaran Tidak Ditemukan' }}</b><br>
                            <small>{{ $c->schedule?->unit?->name ?? '-' }}</small>
                        @endif
                    </td>
                    <td>
                        @if($c->checkin_time)
                            {{ $c->checkin_time->format('H:i:s') }}<br>
                            <small>{{ $c->checkin_time->translatedFormat('d M Y') }}</small>
                        @else
                            <span style="color: #dc3545; font-weight: bold;">BELUM CHECK-IN</span>
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
                            <img src="{{ asset('storage/' . $c->photo) }}" style="max-width: 60px; max-height: 60px; border: 1px solid #ddd;">
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="badge" style="@if($c->status == 'absent') border-color: #dc3545; color: #dc3545; @elseif($c->status == 'break') border-color: #0d6efd; color: #0d6efd; @endif">
                            @if($c->status == 'ontime' || $c->status == 'present') HADIR @elseif($c->status == 'late') TERLAMBAT @elseif($c->status == 'absent') TIDAK HADIR @elseif($c->status == 'break') ISTIRAHAT @else {{ strtoupper($c->status) }} @endif
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

    <div style="margin-top: 50px; text-align: right;">
        <div style="display: inline-block; text-align: center;">
            Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}<br><br><br><br>
            ( ............................................ )
        </div>
    </div>
</body>
</html>
