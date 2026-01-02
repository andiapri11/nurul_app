<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi #{{ $transaction->invoice_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0d6efd;
            --dark-color: #1e293b;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --text-main: #334155;
            --text-muted: #64748b;
        }

        @page {
            size: 9.5in 5.5in;
            margin: 0;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            margin: 0;
            padding: 20px;
            color: var(--text-main);
            line-height: 1.2; /* Tighter line height */
        }

        .invoice-wrapper {
            background: #fff;
            width: 9.5in;
            height: 5.5in;
            margin: 0 auto;
            padding: 0.5cm 1.5cm; /* Reduced padding */
            border-radius: 0;
            box-shadow: none;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
        }

        /* PAID Watermark - slightly smaller and moved */
        .paid-stamp {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            border: 5px solid #10b981;
            color: #10b981;
            font-size: 60px;
            font-weight: 800;
            padding: 10px 30px;
            text-transform: uppercase;
            opacity: 0.1;
            border-radius: 12px;
            user-select: none;
            z-index: 0;
            white-space: nowrap;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px; /* Reduced margin */
            position: relative;
            z-index: 1;
        }

        .school-brand h1 {
            margin: 0;
            font-size: 18px; /* Smaller font */
            font-weight: 800;
            color: var(--dark-color);
            letter-spacing: -0.5px;
            margin-bottom: 2px;
        }

        .school-brand p {
            margin: 0;
            color: var(--text-muted);
            font-size: 9px; /* Smaller font */
            max-width: 350px;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-meta h2 {
            margin: 0;
            font-size: 18px; /* Smaller font */
            font-weight: 700;
            color: var(--primary-color);
            text-transform: uppercase;
        }

        .invoice-meta p {
            margin: 1px 0;
            font-size: 10px; /* Smaller font */
            font-weight: 600;
        }

        .divider {
            height: 1px;
            background-color: var(--border-color);
            margin: 5px 0; /* Tight margin */
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 15px;
            margin-bottom: 10px; /* Reduced margin */
        }

        .details-box h4 {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 3px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 2px;
        }

        .details-box p {
            margin: 1px 0;
            font-size: 10px;
            font-weight: 600;
        }

        .details-box .val-muted {
            font-weight: 400;
            color: var(--text-muted);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th {
            background-color: var(--dark-color);
            color: white;
            text-align: left;
            padding: 5px 8px; /* Reduced padding */
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 5px 8px; /* Reduced padding */
            border-bottom: 1px solid var(--border-color);
            font-size: 10px;
        }

        .total-section {
            margin-top: 5px;
            display: flex;
            justify-content: flex-end;
        }

        .total-table {
            width: 220px;
        }

        .total-table tr td {
            padding: 2px 8px;
            border: none;
            font-size: 10px;
        }

        .grand-total {
            background-color: var(--light-bg);
            border-radius: 4px;
        }

        .grand-total td {
            padding-top: 8px !important; /* Reduced padding */
            padding-bottom: 8px !important;
        }

        .price-big {
            font-size: 14px; /* Smaller font */
            font-weight: 800;
            color: var(--dark-color);
        }

        .note-footer {
            margin-top: 10px; /* Reduced margin */
            font-size: 8px;
            color: var(--text-muted);
            border-top: 1px dashed var(--border-color);
            padding-top: 5px;
        }

        .signatures {
            margin-top: 15px; /* Reduced margin */
            display: flex;
            justify-content: space-between;
            padding: 0 10px;
        }

        .sig-box {
            text-align: center;
            width: 140px;
            font-size: 9px;
        }

        .sig-box .space {
            height: 35px; /* Reduced height */
        }

        .sig-box strong {
            display: block;
            border-top: 1px solid var(--text-main);
            padding-top: 3px;
        }

        .actions {
            max-width: 850px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: space-between;
        }

        .btn {
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            border: none;
        }

        .btn-print { background: var(--dark-color); color: white; }
        .btn-back { background: #fff; color: var(--text-muted); border: 1px solid var(--border-color); }

        @media print {
            .actions { display: none; }
            body { padding: 0; background: white; }
            .invoice-wrapper { width: 9.5in; height: 5.5in; padding: 0.5cm 1.5cm; border: none; }
            .paid-stamp { opacity: 0.1; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="actions">
        <a href="javascript:history.back()" class="btn btn-back">← Kembali</a>
        <button onclick="window.print()" class="btn btn-print">Cetak Kwitansi</button>
    </div>

    <div class="invoice-wrapper">
        <div class="paid-stamp">LUNAS / PAID</div>

        <div class="header">
            <div class="school-brand">
                @php
                    $appName = \App\Models\Setting::where('key', 'app_name')->value('value') ?? 'LPT NURUL ILMI';
                    $appAddress = \App\Models\Setting::where('key', 'app_address')->value('value') ?? 'Jl. Raya Utama No. 123, Kel. Bekasi Jaya, Kec. Bekasi Timur, Kota Bekasi, Jawa Barat 17112';
                @endphp
                <h1>{{ $appName }}</h1>
                <p>{{ $appAddress }}</p>
            </div>
            <div class="invoice-meta">
                <h2>KWITANSI</h2>
                <p>Kode Pembayaran: #{{ $transaction->invoice_number }}</p>
                <p class="val-muted">Tanggal: {{ $transaction->transaction_date->format('d M Y') }}</p>
                <p class="val-muted">Waktu: {{ $transaction->created_at->format('H:i') }} WIB</p>
            </div>
        </div>

        <div class="divider"></div>

        <div class="details-grid">
            <div class="details-box">
                <h4>DITAGIHKAN KEPADA:</h4>
                <p>{{ $transaction->student->nama_lengkap }}</p>
                <p class="val-muted">NIS: {{ $transaction->student->nis }}</p>
                <p class="val-muted">Unit: {{ $transaction->student->unit->name }}</p>
                <p class="val-muted">Kelas: {{ $transaction->student->classes->first()->name ?? '-' }}</p>
            </div>
            <div class="details-box">
                <h4>INFO PEMBAYARAN:</h4>
                <p>Metode: <span class="val-muted text-uppercase">{{ $transaction->payment_method }}</span></p>
                <p>Petugas: <span class="val-muted">{{ $transaction->user->name ?? 'Admin Finance' }}</span></p>
                <p>Status: <span style="color: #10b981;">SUKSES (TERBAYAR)</span></p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="50%">Deskripsi Item Pembayaran</th>
                    <th width="20%">Periode</th>
                    <th width="30%" style="text-align: right;">Subtotal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->items as $item)
                    <tr>
                        @php
                            $bill = \App\Models\StudentBill::where('student_id', $transaction->student_id)
                                ->where('payment_type_id', $item->payment_type_id)
                                ->where('month', $item->month_paid)
                                ->whereHas('academicYear', function($q) use ($item) {
                                    $q->where('start_year', $item->year_paid);
                                })
                                ->first();
                            $isPartial = $bill && ($item->amount < $bill->amount);
                        @endphp
                        <td>
                            <strong>{{ $item->paymentType->name }}</strong>
                            @if($isPartial)
                                <span style="background: #fff8e1; color: #b45309; font-size: 10px; padding: 2px 6px; border-radius: 4px; border: 1px solid #fde68a; margin-left: 5px; font-weight: 700;">CICILAN</span>
                            @endif
                            <br>
                            <small style="color: var(--text-muted);">{{ $transaction->notes ?? 'Penerimaan dana iuran sekolah.' }}</small>
                        </td>
                        <td>
                            @if($item->month_paid)
                                {{ [7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember', 1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni'][$item->month_paid] ?? 'Sekali Bayar' }} {{ $item->year_paid }}
                            @else
                                Sekali Bayar
                            @endif
                        </td>
                        <td style="text-align: right; font-weight: 600;">
                            {{ number_format($item->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <table class="total-table">
                <tr>
                    <td class="val-muted">Subtotal</td>
                    <td style="text-align: right; font-weight: 600;">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                </tr>

                <tr class="grand-total">
                    <td style="font-weight: 800; color: var(--primary-color);">TOTAL AKHIR</td>
                    <td style="text-align: right;" class="price-big">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>



        <div class="note-footer">
            <p><strong>Catatan:</strong> Bukti ini sah dan dikeluarkan secara otomatis oleh sistem LPT Nurul Ilmi. Pembayaran yang sudah dilakukan tidak dapat ditarik kembali atau dibatalkan tanpa persetujuan manajemen.</p>
        </div>

        <div class="signatures">
            <div class="sig-box">
                <p>Bag. Keuangan,</p>
                <div class="space"></div>
                <strong>( {{ $transaction->user->name ?? 'Admin Finance' }} )</strong>
            </div>
            <div class="sig-box">
                <p>{{ now()->translatedFormat('l, d F Y') }}<br>Diterima Oleh,</p>
                <div class="space"></div>
                <strong>( .................................... )</strong>
            </div>
        </div>
        <div style="text-align: center; margin-top: 15px; font-size: 8px; color: #94a3b8;">
            &copy; {{ date('Y') }} LPT NURUL ILMI. Digital Receipt Generated System.
        </div>
    </div>

</body>
</html>
