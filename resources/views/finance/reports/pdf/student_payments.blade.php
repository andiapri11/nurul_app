<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembayaran Siswa</title>
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
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Laporan Pembayaran Siswa</div>
        <div class="meta">
            Periode: 
            @if($period == 'daily') 
                Harian ({{ \Carbon\Carbon::parse($date)->format('d/m/Y') }})
            @elseif($period == 'weekly')
                Mingguan ({{ \Carbon\Carbon::parse($date)->startOfWeek()->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($date)->endOfWeek()->format('d/m/Y') }})
            @else
                Bulanan ({{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }})
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Invoice</th>
                <th>Tanggal</th>
                <th>NIS/Nama Siswa</th>
                <th>Unit</th>
                <th>Metode</th>
                <th>Penerima</th>
                <th>Rincian</th>
                <th style="text-align: right;">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($transactions as $index => $trx)
            @php $grandTotal += $trx->amount; @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $trx->invoice_number }}</td>
                <td>{{ $trx->transaction_date->format('d/m/y H:i') }}</td>
                <td>
                    <strong>{{ $trx->student->nis ?? '-' }}</strong><br>
                    {{ $trx->student->nama_lengkap ?? '-' }}
                </td>
                <td>{{ $trx->student->unit->name ?? '-' }}</td>
                <td>{{ strtoupper($trx->payment_method) }}</td>
                <td>{{ $trx->user->name ?? '-' }}</td>
                <td>
                    @foreach($trx->items as $item)
                        {{ $item->paymentType->name }}{{ $item->month_paid ? ' ('.$item->month_paid.'/'.$item->year_paid.')' : '' }}@if(!$loop->last), @endif
                    @endforeach
                </td>
                <td style="text-align: right;">{{ number_format($trx->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="8" style="text-align: right;">TOTAL KESELURUHAN</td>
                <td style="text-align: right;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y H:i:s') }}
    </div>
</body>
</html>
