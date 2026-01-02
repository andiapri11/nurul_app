<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Tagihan Siswa TP {{ $tp }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11pt; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; color: #000; }
        .header p { margin: 5px 0; color: #666; font-size: 10pt; }
        .info-table { width: 100%; margin-bottom: 20px; font-size: 10pt; }
        .info-table td { padding: 3px 0; }
        table.main-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.main-table th { background: #f2f2f2; color: #000; font-weight: bold; text-transform: uppercase; font-size: 9pt; border: 1px solid #ccc; padding: 10px 5px; }
        table.main-table td { border: 1px solid #ccc; padding: 8px 5px; font-size: 9pt; vertical-align: middle; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .status-badge { padding: 3px 8px; border-radius: 10px; font-size: 8pt; font-weight: bold; display: inline-block; }
        .bg-success { background-color: #d1e7dd; color: #0f513b; }
        .bg-warning { background-color: #fff3cd; color: #664d03; }
        .bg-danger { background-color: #f8d7da; color: #842029; }
        .footer { margin-top: 40px; text-align: right; font-size: 10pt; }
        .signature-box { margin-top: 60px; display: inline-block; width: 200px; border-top: 1px solid #000; text-align: center; padding-top: 5px; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        @php
            $appName = \App\Models\Setting::where('key', 'app_name')->value('value') ?? 'Lembaga Pendidikan Tahfidz Nurul Ilmi';
            $appAddress = \App\Models\Setting::where('key', 'app_address')->value('value') ?? '';
        @endphp
        <h2>REKAPITULASI TAGIHAN SISWA</h2>
        <p>{{ $appName }}</p>
        @if($appAddress) <p style="font-size: 9pt; margin-top: 2px;">{{ $appAddress }}</p> @endif
    </div>

    <table class="info-table">
        <tr>
            <td width="120">Tahun Pelajaran</td>
            <td width="10">:</td>
            <td class="fw-bold">{{ $tp }}</td>
            <td width="120">Unit</td>
            <td width="10">:</td>
            <td class="fw-bold">{{ $unit }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>:</td>
            <td class="fw-bold">{{ $class }}</td>
            <td>Tanggal Cetak</td>
            <td>:</td>
            <td>{{ now()->translatedFormat('d F Y H:i') }} WIB</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Nama Siswa</th>
                <th width="80">NIS</th>
                <th width="100">Total Tagihan</th>
                <th width="100">Terbayar</th>
                <th width="100">Sisa Hutang</th>
                <th width="90">Status</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $grandTotalBill = 0;
                $grandTotalPaid = 0;
            @endphp
            @foreach($students as $idx => $s)
                @php
                    $bill = $s->bills_sum_amount ?? 0;
                    $paid = $s->bills_sum_paid_amount ?? 0;
                    $debt = $bill - $paid;
                    $grandTotalBill += $bill;
                    $grandTotalPaid += $paid;
                    
                    $stLabel = 'LUNAS';
                    $stClass = 'bg-success';
                    if ($debt > 0 && $paid > 0) { $stLabel = 'MENCICIL'; $stClass = 'bg-warning'; }
                    elseif ($debt > 0 && $paid == 0) { $stLabel = 'BELUM BAYAR'; $stClass = 'bg-danger'; }
                    elseif ($bill == 0) { $stLabel = '-'; $stClass = ''; }
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="fw-bold">{{ $s->nama_lengkap }}</td>
                    <td class="text-center">{{ $s->nis }}</td>
                    <td class="text-end">Rp {{ number_format($bill, 0, ',', '.') }}</td>
                    <td class="text-end" style="color: #0f513b;">Rp {{ number_format($paid, 0, ',', '.') }}</td>
                    <td class="text-end" style="color: #842029;">Rp {{ number_format($debt, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="status-badge {{ $stClass }}">{{ $stLabel }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f9f9f9; font-weight: bold;">
                <td colspan="3" class="text-center">TOTAL KESELURUHAN</td>
                <td class="text-end">Rp {{ number_format($grandTotalBill, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($grandTotalPaid, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($grandTotalBill - $grandTotalPaid, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Jambi, {{ now()->translatedFormat('d F Y') }}</p>
        <p style="margin-bottom: 60px;">Bendahara Keuangan,</p>
        <div class="signature-box">
            ( .................................... )
        </div>
    </div>

    <div class="no-print text-center" style="margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Laporan</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; margin-left: 10px;">Tutup</button>
    </div>
</body>
</html>
