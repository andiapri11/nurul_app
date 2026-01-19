<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi #{{ $paymentRequest->reference_code }}</title>
    <style>
        /* Epson LX-310 - Vertical Slip Optimization */
        @page {
            size: portrait;
            margin: 0;
        }

        * {
            color: #000 !important;
            background: transparent !important;
            box-sizing: border-box;
        }

        body {
            /* Monospace is the native language of Dot-Matrix printers. Results ARE SHARP */
            font-family: 'Courier New', Courier, monospace;
            font-size: 10pt;
            margin: 0;
            padding: 1cm;
            width: 12cm; /* Very narrow to lock Portrait position */
        }

        .receipt-container {
            width: 100%;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .big { font-size: 14pt; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        .double-divider { border-top: 2px solid #000; margin: 10px 0; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td { vertical-align: top; padding: 2px 0; }

        @media print {
            body { width: 12cm; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt-container">
        <!-- HEADER -->
        <div class="text-center">
            @php
                $schoolName = \App\Models\Setting::where('key', 'school_name')->value('value') ?? 'LPT NURUL ILMI';
                $address = \App\Models\Setting::where('key', 'app_address')->value('value') ?? 'Alamat Sekolah...';
            @endphp
            <div class="bold big">{{ strtoupper($schoolName) }}</div>
            <div style="font-size: 8pt;">{{ $address }}</div>
        </div>

        <div class="divider"></div>
        <div class="text-center bold">KWITANSI PEMBAYARAN</div>
        <div class="text-center">NO: {{ $paymentRequest->reference_code }}</div>
        <div class="divider"></div>

        <!-- TRANSACTION INFO -->
        <table>
            <tr><td style="width: 3.5cm;">Tanggal</td><td>: {{ $paymentRequest->created_at->format('d/m/Y H:i') }}</td></tr>
            <tr><td>Siswa</td><td class="bold">: {{ $paymentRequest->student->nama_lengkap }}</td></tr>
            <tr><td>NIS</td><td>: {{ $paymentRequest->student->nis }}</td></tr>
            <tr><td>Unit/Kls</td><td>: {{ $paymentRequest->student->unit->name }} / {{ $paymentRequest->student->classes->first()->name ?? '-' }}</td></tr>
            <tr><td>Metode</td><td>: {{ $paymentRequest->bankAccount->bank_name ?? 'Tunai' }}</td></tr>
        </table>

        <div class="double-divider"></div>

        <!-- DETAILS -->
        <div class="bold" style="margin-bottom: 5px;">RINCIAN ITEM:</div>
        @foreach($paymentRequest->items as $item)
            @php
                $bill = $item->studentBill;
                $yr = $bill->year ?: (($bill->month >= 7) ? $bill->academicYear->start_year : $bill->academicYear->start_year + 1);
                $period = $bill->month ? \Carbon\Carbon::create()->month((int)$bill->month)->translatedFormat('M') : '';
            @endphp
            <div style="margin-bottom: 8px;">
                <div class="bold">- {{ $item->studentBill->paymentType->name }}</div>
                <div style="padding-left: 10px;">
                    Periode: {{ $period }} {{ $yr }}
                    <span style="float: right;">{{ number_format($item->amount, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach

        <div class="divider"></div>

        <!-- TOTAL -->
        <table>
            <tr class="bold big">
                <td>TOTAL BAYAR</td>
                <td class="text-right">Rp {{ number_format($paymentRequest->total_amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- SIGNATURE -->
        <table style="margin-top: 30px;">
            <tr>
                <td class="text-center" style="width: 50%;">
                    Petugas,<br><br><br><br>
                    ( __________ )
                </td>
                <td class="text-center" style="width: 50%;">
                    Siswa/Wali,<br><br><br><br>
                    ( {{ substr($paymentRequest->student->nama_lengkap, 0, 15) }} )
                </td>
            </tr>
        </table>

        <div class="divider" style="margin-top: 20px;"></div>
        <div class="text-center" style="font-size: 8pt;">
            Terima kasih atas pembayarannya.<br>
            Dicetak: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>
</body>
</html>
