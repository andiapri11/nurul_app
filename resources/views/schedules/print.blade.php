<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Pelajaran - {{ $class->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            margin: 0.5cm;
        }
        body {
            font-family: 'Inter', sans-serif;
            font-size: 8.5pt;
            color: #1a202c;
            margin: 0;
            padding: 0;
            background-color: #fff;
            -webkit-print-color-adjust: exact;
        }
        .container {
            width: 100%;
            max-width: 100%;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2d3748;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header h1 {
            margin: 0;
            font-size: 14pt;
            font-weight: 800;
            color: #1a202c;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0 0;
            font-size: 9pt;
            color: #4a5568;
            font-weight: 600;
        }

        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 9pt;
            background: #f8fafc;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }
        .info-item strong { color: #2d3748; }

        /* Two columns for portrait: Mon-Wed and Thu-Sat/Sun */
        .schedule-layout {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .day-section {
            flex: 1 1 31%; /* Roughly 3 columns per row for portrait */
            min-width: 0;
            margin-bottom: 10px;
        }

        .day-header {
            background-color: #2d3748;
            color: white;
            padding: 5px;
            text-align: center;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 9pt;
            border-radius: 4px;
            margin-bottom: 6px;
        }

        .card {
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 6px 8px;
            margin-bottom: 4px;
            position: relative;
        }
        .card.is-break {
            background-color: #fffaf0;
            border: 1px dashed #f6ad55;
            text-align: center;
        }
        .card .time {
            font-size: 7.5pt;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 2px;
        }
        .card .subject {
            font-size: 9pt;
            font-weight: 700;
            color: #1a202c;
            line-height: 1.1;
        }
        .card .teacher {
            font-size: 7.5pt;
            color: #718096;
            margin-top: 2px;
            border-top: 1px solid #f1f5f9;
            padding-top: 2px;
        }

        .no-print {
            position: fixed;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
        }
        .btn-print {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .day-section {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">
             CETAK SEKARANG (PORTRAIT)
        </button>
    </div>

    <div class="container">
        <div class="header">
            @php
                $appName = \App\Models\Setting::where('key', 'app_name')->value('value') ?? 'LPT NURUL ILMI';
                $appAddress = \App\Models\Setting::where('key', 'app_address')->value('value') ?? '';
            @endphp
            <h1>{{ $appName }}</h1>
            @if($appAddress) <p style="font-weight: normal; margin-bottom: 5px;">{{ $appAddress }}</p> @endif
            <h2 style="font-size: 12pt; margin: 5px 0 0; text-transform: uppercase;">Jadwal Pelajaran Mingguan</h2>
            <p>Unit: {{ $class->unit->name }} • Kelas: {{ $class->name }}</p>
        </div>

        <div class="info-grid">
            <div class="info-item"><strong>Wali Kelas:</strong> {{ $class->teacher->name ?? '-' }}</div>
            <div class="info-item"><strong>Tahun Pelajaran:</strong> {{ $class->academicYear->name ?? '-' }}</div>
        </div>

        <div class="schedule-layout">
            @foreach($days as $day)
                <div class="day-section">
                    <div class="day-header">{{ $day }}</div>
                    @if(isset($schedules[$day]) && count($schedules[$day]) > 0)
                        @foreach($schedules[$day] as $item)
                            <div class="card {{ $item->is_break ? 'is-break' : '' }}">
                                <div class="time">
                                    {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}
                                </div>
                                <div class="subject">
                                    {{ $item->is_break ? ($item->break_name ?? 'Istirahat') : ($item->subject->name ?? '-') }}
                                </div>
                                @if(!$item->is_break && $item->teacher)
                                    <div class="teacher">{{ Str::limit($item->teacher->name, 25) }}</div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div style="text-align: center; padding: 10px; color: #cbd5e0; font-style: italic;">Libur</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
