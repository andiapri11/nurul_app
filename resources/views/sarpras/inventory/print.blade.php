<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Inventaris</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; margin: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 16px; }
        .header p { margin: 5px 0 0; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; vertical-align: middle; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .badge { padding: 2px 5px; border-radius: 3px; font-size: 10px; font-weight: bold; text-transform: uppercase; border: 1px solid #ccc; display: inline-block; }
        .footer { margin-top: 30px; width: 100%; page-break-inside: avoid; }
        .footer table { border: none; }
        .footer td { border: none; text-align: center; width: 33%; vertical-align: top; }
        .signature-space { height: 60px; }
        @media print {
            @page { size: landscape; margin: 10mm; }
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        @php
            $appName = \App\Models\Setting::where('key', 'app_name')->value('value') ?? 'LPT NURUL ILMI';
            $appAddress = \App\Models\Setting::where('key', 'app_address')->value('value') ?? '';
        @endphp
        <h2>{{ $appName }}</h2>
        @if($appAddress) <p>{{ $appAddress }}</p> @endif
        <p>LAPORAN DATA INVENTARIS BARANG</p>
        <p style="font-size: 10px; color: #666;">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Lokasi (Unit)</th>
                <th>Kondisi</th>
                <th>Thn Perolehan</th>
                <th>Sumber</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventories as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category->name ?? '-' }}</td>
                <td>{{ $item->room->name ?? '-' }} ({{ $item->room->unit->name ?? '-' }})</td>
                <td class="text-center">
                    {{ $item->condition == 'Good' ? 'Baik' : ($item->condition == 'Damaged' ? 'Rusak Ringan' : 'Rusak Berat') }}
                </td>
                <td class="text-center">{{ $item->purchase_date ? $item->purchase_date->format('Y') : '-' }}</td>
                <td>{{ $item->source }}</td>
                <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data inventaris.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table>
            <tr>
                <td>
                    Dibuat Oleh,<br>
                    Wakil Sarpras
                    <div class="signature-space"></div>
                    ( <strong>{{ $officials['sarpras'] ?? '.................................' }}</strong> )
                </td>
                <td>
                    Mengetahui,<br>
                    Kepala Sekolah
                    <div class="signature-space"></div>
                    ( <strong>{{ $officials['principal'] ?? '.................................' }}</strong> )
                </td>
                <td>
                    Menyetujui,<br>
                    Pimpinan Lembaga
                    <div class="signature-space"></div>
                    ( <strong>{{ $officials['director'] ?? '.................................' }}</strong> )
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
