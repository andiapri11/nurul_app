<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kerusakan & Kehilangan Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .badge { padding: 2px 5px; border-radius: 3px; font-size: 10px; font-weight: bold; text-transform: uppercase; border: 1px solid #ccc; }
        .footer { margin-top: 50px; width: 100%; }
        .footer table { border: none; }
        .footer td { border: none; text-align: center; width: 33%; }
        .signature-space { height: 70px; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        @php
            $schoolName = \App\Models\Setting::where('key', 'school_name')->value('value') ?? \App\Models\Setting::where('key', 'app_name')->value('value') ?? 'LPT NURUL ILMI';
            $appAddress = \App\Models\Setting::where('key', 'app_address')->value('value') ?? '';
        @endphp
        <h2>{{ $schoolName }}</h2>
        @if($appAddress) <p>{{ $appAddress }}</p> @endif
        <p>LAPORAN KERUSAKAN DAN KEHILANGAN BARANG INVENTARIS</p>
        <p style="font-size: 10px; color: #666;">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="80">Tanggal</th>
                <th>Barang</th>
                <th>Masalah / Detail</th>
                <th>Saran Sarpras</th>
                <th>Validasi KS</th>
                <th>Final Pimpinan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $index => $report)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $report->created_at->format('d/m/Y') }}</td>
                <td>
                    <strong>{{ $report->inventory->name }}</strong><br>
                    <small>{{ $report->inventory->code }}</small>
                </td>
                <td>
                    @if($report->type == 'Lost')
                        <span class="badge">HILANG</span>
                    @else
                        <span class="badge">RUSAK</span>
                    @endif
                    <br>
                    {{ $report->description }}
                    
                    @if($report->photo)
                        <div style="margin-top: 5px;">
                            <img src="{{ asset('storage/' . $report->photo) }}" style="max-width: 80px; max-height: 80px; border: 1px solid #ddd;">
                        </div>
                    @endif
                </td>
                <td>
                    @php
                        $actions = [
                            'Repair' => 'Perbaikan', 
                            'Replacement' => 'Ganti Baru', 
                            'Disposal' => 'Penghapusan', 
                            'Write-off' => 'Pemutihan'
                        ];
                    @endphp
                    <strong>{{ $actions[$report->follow_up_action] ?? $report->follow_up_action }}</strong><br>
                    <small>{{ $report->follow_up_description }}</small>
                </td>
                <td style="text-align: center;">
                    @if($report->principal_approval_status == 'Approved') VALID @elseif($report->principal_approval_status == 'Rejected') DITOLAK @else - @endif
                </td>
                <td style="text-align: center;">
                    @if($report->director_status == 'Approved') SETUJU @elseif($report->director_status == 'Rejected') TOLAK @else - @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <table>
            <tr>
                <td>
                    Dibuat Oleh,<br>
                    Wakil Sarpras
                    <div class="signature-space"></div>
                    ( {{ $officials['sarpras'] ?? '.................................' }} )
                </td>
                <td>
                    Mengetahui,<br>
                    Kepala Sekolah
                    <div class="signature-space"></div>
                    ( {{ $officials['principal'] ?? '.................................' }} )
                </td>
                <td>
                    Menyetujui,<br>
                    Pimpinan Lembaga
                    <div class="signature-space"></div>
                    ( {{ $officials['director'] ?? '.................................' }} )
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
