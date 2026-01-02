<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kas Umum</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { text-align: right; margin-top: 20px; font-size: 8px; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .title { font-size: 16px; font-weight: bold; text-transform: uppercase; }
        .meta { margin-top: 5px; color: #555; }
        .type-income { color: #198754; font-weight: bold; }
        .type-expense { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Laporan Kas Umum (Pemasukan & Pengeluaran)</div>
        <div class="meta">
            Periode: 
            @if($period == 'daily') 
                Harian ({{ \Carbon\Carbon::parse($date)->format('d/m/Y') }})
            @elseif($period == 'weekly')
                Mingguan ({{ \Carbon\Carbon::parse($date)->startOfWeek()->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($date)->endOfWeek()->format('d/m/Y') }})
            @else
                Bulanan ({{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }})
            @endif
            @if(isset($category) && $category)
                | Kategori: {{ $category }}
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Kategori</th>
                <th>Keterangan / Pihak Kedua</th>
                <th>Unit</th>
                <th>Metode</th>
                <th style="text-align: right;">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalIncome = 0; $totalExpense = 0; @endphp
            @foreach($records as $index => $row)
            @php 
                if($row->type == 'income') $totalIncome += $row->amount;
                else $totalExpense += $row->amount;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->transaction_date->format('d/m/Y') }}</td>
                <td class="{{ $row->type == 'income' ? 'type-income' : 'type-expense' }}">
                    {{ strtoupper($row->type == 'income' ? 'MASUK' : 'KELUAR') }}
                </td>
                <td>{{ $row->category }}</td>
                <td>
                    <strong>{{ $row->payer_name ?: '-' }}</strong><br>
                    <small>{{ $row->description ?: '-' }}</small>
                </td>
                <td>{{ $row->unit->name ?? 'Pusat/Umum' }}</td>
                <td>{{ strtoupper($row->payment_method) }}</td>
                <td style="text-align: right;">{{ number_format($row->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="7" style="text-align: right; color: #198754;">TOTAL PEMASUKAN</td>
                <td style="text-align: right; color: #198754;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="7" style="text-align: right; color: #dc3545;">TOTAL PENGELUARAN</td>
                <td style="text-align: right; color: #dc3545;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row" style="background-color: #eee;">
                <td colspan="7" style="text-align: right; font-size: 12px;">SALDO BERSIH (NET)</td>
                <td style="text-align: right; font-size: 12px;">Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y H:i:s') }}
    </div>
</body>
</html>
