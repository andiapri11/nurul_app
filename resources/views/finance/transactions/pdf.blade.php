<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - {{ $date }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .table-bordered th, .table-bordered td { border: 1px solid #000 !important; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 20px; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container-fluid">
        <div class="no-print mb-4 py-3 bg-light text-center">
            <button onclick="window.print()" class="btn btn-primary btn-sm">Cetak Laporan</button>
            <button onclick="window.close()" class="btn btn-secondary btn-sm">Tutup</button>
        </div>

        <div class="header">
            @php
                $appName = \App\Models\Setting::where('key', 'app_name')->value('value') ?? 'Nurul Ilmi School System';
                $appAddress = \App\Models\Setting::where('key', 'app_address')->value('value') ?? '';
            @endphp
            <h4 class="mb-0 fw-bold">LAPORAN TRANSAKSI PEMBAYARAN</h4>
            <h5 class="mb-0">{{ $appName }}</h5>
            @if($appAddress) <p class="mb-0 small" style="font-size: 10px;">{{ $appAddress }}</p> @endif
            <p class="mb-0">Periode: {{ $date }}</p>
        </div>

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kode Pembayaran</th>
                    <th width="10%">Tanggal</th>
                    <th width="20%">Siswa</th>
                    <th>Uraian Detail</th>
                    <th width="12%" class="text-end">Nominal</th>
                    <th width="10%">Status</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($transactions as $index => $t)
                    @php if(!$t->is_void) $total += $t->amount; @endphp
                    <tr class="{{ $t->is_void ? 'text-muted italic' : '' }}">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $t->invoice_number }}</td>
                        <td>{{ $t->transaction_date->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ $t->student->nama_lengkap }}</strong><br>
                            <span class="small">{{ $t->student->nis }} - {{ $t->student->unit->name ?? '-' }}</span>
                        </td>
                        <td>
                            @foreach($t->items as $item)
                                <div class="small">• {{ $item->paymentType->name ?? '-' }}
                                    @if($item->month_paid)
                                        ({{ \Carbon\Carbon::create()->month((int)$item->month_paid)->translatedFormat('F') }})
                                    @endif
                                    : Rp{{ number_format($item->amount, 0, ',', '.') }}
                                </div>
                            @endforeach
                        </td>
                        <td class="text-end font-monospace">Rp{{ number_format($t->amount, 0, ',', '.') }}</td>
                        <td class="text-center">
                             <span class="badge @if($t->is_void) bg-danger @else bg-success @endif text-uppercase" style="font-size: 8px;">
                                {{ $t->is_void ? 'VOID' : 'AKTIF' }}
                             </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-dark">
                    <td colspan="5" class="text-end fw-bold">TOTAL PENERIMAAN BERSIH:</td>
                    <td class="text-end fw-bold">Rp{{ number_format($total, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <div class="row mt-5">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <p class="mb-5">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}<br>Administrator Keuangan,</p>
                <div style="height: 60px;"></div>
                <p class="fw-bold">( ____________________ )</p>
            </div>
        </div>
    </div>
</body>
</html>
