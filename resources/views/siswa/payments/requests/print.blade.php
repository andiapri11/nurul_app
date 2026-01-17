<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tagihan #{{ $paymentRequest->reference_code }}</title>
    <style>
        /* PDF Specific - Ultra Compact for 9.5in x 5.5in */
        @page {
            size: 684pt 396pt;
            margin: 0;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #334155;
            width: 684pt;
            height: 396pt;
            overflow: hidden;
            line-height: 1.1;
        }

        .invoice-wrapper {
            width: 600pt; /* Even smaller width for maximum safety */
            margin: 0 auto;
            padding: 15pt 0; /* Reduced vertical padding */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .header-table td {
            vertical-align: top;
        }

        .school-info {
            width: 70%;
        }

        .invoice-meta {
            width: 30%;
            text-align: right;
        }

        /* Smaller Fonts Throughout */
        .school-info h1 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
            color: #1e293b;
        }

        .school-info p {
            margin: 1pt 0;
            font-size: 6.5pt;
            color: #64748b;
            padding-right: 40pt;
        }

        .invoice-meta h2 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
            color: #f59e0b;
        }

        .invoice-meta p {
            margin: 1pt 0;
            font-size: 7.5pt;
            font-weight: bold;
        }

        .divider {
            border-bottom: 0.5pt solid #e2e8f0;
            margin: 6pt 0;
        }

        .details-table td {
            width: 50%;
            vertical-align: top;
        }

        .details-box h4 {
            font-size: 6.5pt;
            color: #64748b;
            margin: 0 0 3pt 0;
            border-bottom: 0.5pt solid #f1f5f9;
            padding-bottom: 1pt;
            text-transform: uppercase;
        }

        .details-box p {
            margin: 1pt 0;
            font-size: 7.5pt;
            font-weight: bold;
        }

        .val-muted {
            font-weight: normal;
            color: #64748b;
            font-size: 7pt;
        }

        .items-table {
            margin-top: 4pt;
        }

        .items-table th {
            background-color: #1e293b;
            color: white;
            padding: 3pt 8pt;
            font-size: 7pt;
            text-align: left;
        }

        .items-table td {
            padding: 4pt 8pt;
            border-bottom: 0.5pt solid #f1f5f9;
            font-size: 7.5pt;
        }

        .total-wrapper {
            margin-top: 8pt;
        }

        .total-box-table {
            width: 160pt;
            margin-left: auto;
            background-color: #f8fafc;
            border-radius: 3pt;
        }

        .total-box-table td {
            padding: 5pt 8pt;
            vertical-align: middle;
        }

        .total-label {
            color: #f59e0b;
            font-weight: bold;
            font-size: 8pt;
            width: 50%;
        }

        .total-price {
            text-align: right;
            font-weight: bold;
            font-size: 11pt;
            color: #1e293b;
            width: 50%;
        }

        .warning-box {
            margin-top: 8pt;
            padding: 5pt 10pt;
            background-color: #fff8e1;
            border: 0.5pt solid #fde68a;
            font-size: 6.5pt;
            border-radius: 3pt;
        }

        .warning-box p {
            margin: 0;
            color: #92400e;
            font-weight: bold;
            line-height: 1.3;
        }

        .footer {
            margin-top: 8pt;
            padding-top: 4pt;
            border-top: 0.5pt dashed #e2e8f0;
            font-size: 6.5pt;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="invoice-wrapper">
        <table class="header-table">
            <tr>
                <td class="school-info">
                    @php
                        $schoolName = \App\Models\Setting::where('key', 'school_name')->value('value') ?? \App\Models\Setting::where('key', 'app_name')->value('value') ?? 'LPT NURUL ILMI';
                        $appAddress = \App\Models\Setting::where('key', 'app_address')->value('value') ?? 'Jl. Palembang - Betung No.KM.18 No.73, RT.09/RW.02, Sukamoro, Kec. Talang Klp., Kab. Banyuasin, Sumatera Selatan 30961';
                    @endphp
                    <h1>{{ $schoolName }}</h1>
                    <p>{{ $appAddress }}</p>
                </td>
                <td class="invoice-meta">
                    <h2>TAGIHAN</h2>
                    <p>Ref: #{{ $paymentRequest->reference_code }}</p>
                    <p class="val-muted" style="font-weight: normal;">Tanggal: {{ $paymentRequest->created_at->format('d M Y') }}</p>
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <table class="details-table">
            <tr>
                <td>
                    <div class="details-box">
                        <h4>DATA SISWA:</h4>
                        <p>{{ $paymentRequest->student->nama_lengkap }}</p>
                        <p class="val-muted">NIS: {{ $paymentRequest->student->nis }}</p>
                        <p class="val-muted">Unit: {{ $paymentRequest->student->unit->name }} | Kelas: {{ $paymentRequest->student->classes->first()->name ?? '-' }}</p>
                    </div>
                </td>
                <td style="padding-left: 15pt;">
                    <div class="details-box">
                        <h4>TUJUAN TRANSFER:</h4>
                        <p>{{ $paymentRequest->bankAccount->bank_name ?? '-' }}</p>
                        <p class="val-muted">Rek: <strong>{{ $paymentRequest->bankAccount->account_number ?? '-' }}</strong></p>
                        <p class="val-muted">a.n {{ $paymentRequest->bankAccount->account_holder ?? '-' }}</p>
                        @php
                            $statusLabels = [
                                'waiting_proof' => 'PENDING',
                                'pending' => 'PROSES',
                                'verified' => 'DIVERIFIKASI',
                                'rejected' => 'DITOLAK'
                            ];
                        @endphp
                        <p class="val-muted">Status: <strong style="color: #1e293b;">{{ $statusLabels[$paymentRequest->status] ?? $paymentRequest->status }}</strong></p>
                    </div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 75%;">DESKRIPSI ITEM TAGIHAN</th>
                    <th style="width: 25%; text-align: right;">JUMLAH (RP)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paymentRequest->items as $item)
                    <tr>
                        <td>
                            <div style="font-weight: bold; text-transform: uppercase;">{{ $item->studentBill->paymentType->name }}</div>
                            <div style="color: #64748b; font-size: 6.5pt;">
                                @php
                                    $bill = $item->studentBill;
                                    $yearDisplay = $bill->year;
                                    if (!$yearDisplay && $bill->academicYear) {
                                        $yearDisplay = ($bill->month >= 7) ? $bill->academicYear->start_year : ($bill->academicYear->start_year + 1);
                                    }
                                @endphp
                                ({{ $bill->month ? \Carbon\Carbon::create()->month((int)$bill->month)->translatedFormat('F') : '' }} {{ $yearDisplay }})
                            </div>
                        </td>
                        <td style="text-align: right; font-weight: bold; font-size: 8.5pt;">
                            {{ number_format($item->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-wrapper">
            <table class="total-box-table">
                <tr>
                    <td class="total-label">TOTAL TAGIHAN</td>
                    <td class="total-price">Rp {{ number_format($paymentRequest->total_amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="warning-box">
            <p>Disclaimer: Dana wajib ditransfer ke rekening resmi LPT Nurul Ilmi. Nota ini dicetak sistem pada {{ now()->format('d F Y H:i') }} WIB dan bukan merupakan bukti lunas (Kwitansi).</p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} LPT NURUL ILMI. DIGITAL INVOICE GENERATED SYSTEM.
        </div>
    </div>
</body>
</html>
