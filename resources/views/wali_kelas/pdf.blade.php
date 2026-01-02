<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f0f0f0; }
        .text-left { text-align: left; }
        .footer { margin-top: 30px; text-align: right; }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        @media print {
            .print-btn { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <button class="print-btn" onclick="window.print()">Cetak / Simpan PDF</button>

    <div class="header">
        @php
            $appName = \App\Models\Setting::where('key', 'app_name')->value('value') ?? 'LPT NURUL ILMI';
            $appAddress = \App\Models\Setting::where('key', 'app_address')->value('value') ?? '';
        @endphp
        <h3>{{ $appName }}</h3>
        @if($appAddress) <p style="font-size: 10px; margin-top: -5px;">{{ $appAddress }}</p> @endif
        <h2>Laporan Absensi Siswa</h2>
        <p>{{ $myClass->unit->name }} - {{ $myClass->academicYear ? $myClass->academicYear->name : '' }}</p>
        <p>Kelas: {{ $myClass->name }} | Wali Kelas: {{ $myClass->teacher ? $myClass->teacher->name : '-' }}</p>
        <p><strong>{{ $title }}</strong></p>
    </div>

    @if($type === 'daily')
        <table>
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th>Nama Siswa</th>
                    <th width="100">Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    @php
                        $att = $data['attendances'][$student->id] ?? null;
                        $status = $att ? $att->status : '-';
                        $statusLabel = [
                            'present' => 'Hadir', 'school_activity' => 'Kegiatan', 'sick' => 'Sakit', 
                            'permission' => 'Izin', 'alpha' => 'Alpa', 'late' => 'Terlambat', 'missing' => '-'
                        ][$status] ?? '-';
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-left">{{ $student->nama_lengkap }}</td>
                        <td>{{ $statusLabel }}</td>
                        <td class="text-left">{{ $att->notes ?? '' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>

    @elseif($type === 'weekly')
        <table>
            <thead>
                <tr>
                    <th rowspan="2" width="30">No</th>
                    <th rowspan="2">Nama Siswa</th>
                    <th colspan="{{ count($data['dates_in_week']) }}">Tanggal</th>
                </tr>
                <tr>
                    @foreach($data['dates_in_week'] as $d)
                        @php
                            $dStr = $d->format('Y-m-d');
                            $isHoliday = isset($data['week_holidays'][$dStr]) && $data['week_holidays'][$dStr]->is_holiday;
                            $style = $isHoliday ? 'background-color: #ffcccc;' : '';
                        @endphp
                        <th style="{{ $style }}">{{ $d->format('d/m') }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-left">{{ $student->nama_lengkap }}</td>
                        @foreach($data['dates_in_week'] as $d)
                            @php
                                $dStr = $d->format('Y-m-d');
                                $isHoliday = isset($data['week_holidays'][$dStr]) && $data['week_holidays'][$dStr]->is_holiday;
                                
                                $colAtts = $data['attendances'][$student->id] ?? collect();
                                $rec = $colAtts->firstWhere('date', $d);
                                $s = $rec ? $rec->status : '';
                                $code = '-';
                                $bgStyle = '';
                                
                                if ($isHoliday) {
                                    $code = '-';
                                    $bgStyle = 'background-color: #efefef; color: #999;';
                                } else {
                                    switch($s){
                                        case 'present': $code = 'H'; break;
                                        case 'school_activity': $code = 'K'; break;
                                        case 'sick': $code = 'S'; break;
                                        case 'permission': $code = 'I'; break;
                                        case 'alpha': $code = 'A'; break;
                                        case 'late': $code = 'T'; break;
                                    }
                                }
                            @endphp
                            <td style="{{ $bgStyle }}">{{ $code }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr><td colspan="{{ 2 + count($data['dates_in_week']) }}">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
        <p style="font-size: 10px; margin-top: 5px;">Ket: H=Hadir, K=Kegiatan, S=Sakit, I=Izin, A=Alpa, T=Terlambat</p>

    @elseif($type === 'monthly' || $type === 'semester')
        @if(isset($data['total_effective_days']))
            <p style="margin-bottom: 5px; font-weight: bold;">Total Hari Efektif: {{ $data['total_effective_days'] }} Hari</p>
        @endif
        <table>
            <thead>
                <tr>
                    <th rowspan="2" width="30">No</th>
                    <th rowspan="2">Nama Siswa</th>
                    <th colspan="6">Rekapitulasi</th>
                    <th rowspan="2" width="50">%</th>
                </tr>
                <tr>
                    <th>H</th><th>K</th><th>S</th><th>I</th><th>A</th><th>T</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    @php
                        $stats = $data['summary'][$student->id];
                        $totalDays = array_sum($stats);
                        $presentCount = $stats['present'] + $stats['late'] + $stats['school_activity'];
                        $percentage = $totalDays > 0 ? round(($presentCount / $totalDays) * 100) : 0;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-left">{{ $student->nama_lengkap }}</td>
                        <td>{{ $stats['present'] }}</td>
                        <td>{{ $stats['school_activity'] }}</td>
                        <td>{{ $stats['sick'] }}</td>
                        <td>{{ $stats['permission'] }}</td>
                        <td>{{ $stats['alpha'] }}</td>
                        <td>{{ $stats['late'] }}</td>
                        <td>{{ $percentage }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="9">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y') }}</p>
        <p style="margin-top: 50px;">
            <u>{{ $myClass->teacher ? $myClass->teacher->name : '.........................' }}</u><br>
            Wali Kelas {{ $myClass->name }}
        </p>
    </div>

</body>
</html>
