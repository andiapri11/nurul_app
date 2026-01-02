<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Terima #{{ str_pad($income->id, 5, '0', STR_PAD_LEFT) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #198754; /* Green for Income */
            --dark-color: #1e293b;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --text-main: #334155;
            --text-muted: #64748b;
        }

        /* Default page settings */
        @page {
            size: A4;
            margin: 0;
        }

        /* Named page for Voucher (Landscape Small) */
        @page voucher {
            size: 9.5in 5.5in;
            margin: 0;
        }

        /* Named page for Attachment (A4 Portrait) */
        @page attachment {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            margin: 0;
            padding: 20px;
            color: var(--text-main);
            line-height: 1.2;
        }

        .attachment-container {
            page: attachment;
            page-break-before: always;
            background: #fff;
            padding: 1.5cm;
            min-height: 29.7cm; /* A4 Height */
            box-sizing: border-box;
        }

        .invoice-wrapper {
            page: voucher;
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
            page-break-after: always;
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
        }
    </style>
</head>
<body onload="window.print()">

    <div class="actions">
        <a href="javascript:history.back()" class="btn btn-back">← Kembali</a>
        <button onclick="window.print()" class="btn btn-print">Cetak Bukti</button>
    </div>

    <div class="invoice-wrapper">
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
                <h2>BUKTI KAS MASUK</h2>
                <p>No. Transaksi: {{ $income->proof_code ? '#' . $income->proof_code : '#INC-' . str_pad($income->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p style="color: var(--text-muted);">Tanggal: {{ $income->transaction_date->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>

        <div class="divider"></div>

        <div class="row" style="display: flex; margin-bottom: 10px;">
            <div class="col-6" style="flex: 1;">
                <p style="margin-bottom: 2px; font-weight: 600; color: var(--text-muted); font-size: 9px; text-transform: uppercase;">DITERIMA DARI:</p>
                <h3 style="margin-top: 0; margin-bottom: 2px; font-size: 14px;">{{ $income->payer_name ?: ($income->unit->name ?? 'Umum/Internal') }}</h3>
                @if($income->unit)
                    <p style="margin: 0; font-size: 10px; color: var(--text-muted);">Unit Pelaksana: <strong>{{ $income->unit->name }}</strong></p>
                @endif
                <p style="margin: 0; font-size: 10px; color: var(--text-muted);">Admin/PJ: <strong>{{ $income->user->name ?? 'Admin Finance' }}</strong></p>
            </div>
            @if($income->procurement_request_code)
            @php
                $procMain = \App\Models\ProcurementRequest::where('request_code', $income->procurement_request_code)->first();
            @endphp
            <div class="col-6" style="flex: 1; border-left: 1px solid var(--border-color); padding-left: 15px;">
                <p style="margin-bottom: 2px; font-weight: 600; color: var(--text-muted); font-size: 9px; text-transform: uppercase;">DETAIL TERKAIT:</p>
                <p style="margin: 0; font-size: 10px; color: var(--text-dark);">Nama Kegiatan: <strong>{{ $procMain->activity_name ?? '-' }}</strong></p>
                <p style="margin-top: 2px; margin-bottom: 0; font-size: 10px; color: var(--text-dark);">Nomor Referensi: <strong>{{ $income->procurement_request_code }}</strong></p>
            </div>
            @endif
        </div>

        <table>
            <thead>
                @if($income->items && $income->items->count() > 0)
                <tr>
                    <th width="5%">No</th>
                    <th width="45%">Rincian Penerimaan</th>
                    <th width="15%" style="text-align: center;">Qty</th>
                    <th width="15%" style="text-align: right;">Harga Satuan</th>
                    <th width="20%" style="text-align: right;">Total (Rp)</th>
                </tr>
                @else
                <tr>
                    <th width="60%">Keterangan Pemasukan</th>
                    <th width="40%" style="text-align: right;">Jumlah (Rp)</th>
                </tr>
                @endif
            </thead>
            <tbody>
                @if($income->items && $income->items->count() > 0)
                    @foreach($income->items as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            <strong style="display: block; font-size: 13px;">{{ $item->item_name }}</strong>
                        </td>
                        <td style="text-align: center;">
                            {{ $item->quantity }} {{ $item->unit_name }}
                        </td>
                        <td style="text-align: right;">
                            {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right; font-weight: 600;">
                            {{ number_format($item->total_price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                @else
                <tr>
                    <td>
                        <strong style="display: block; font-size: 12px; margin-bottom: 2px;">{{ $income->category }}</strong>
                        <p style="margin: 0; color: var(--text-muted); font-size: 9px;">{{ $income->description ?? '-' }}</p>
                    </td>
                    <td style="text-align: right; font-weight: 600; font-size: 12px;">
                        {{ number_format($income->amount, 0, ',', '.') }}
                    </td>
                </tr>
                @endif
            </tbody>
        </table>

        <div class="total-section">
            <table class="total-table">
                <tr class="grand-total">
                    <td style="font-weight: 800; color: var(--primary-color);">TOTAL MASUK</td>
                    <td style="text-align: right;" class="price-big">Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="note-footer">
            <p><strong>Catatan:</strong> Dokumen ini adalah bukti sah penerimaan dana sekolah yang tercatat dalam sistem LPT Nurul Ilmi. Harap disimpan sebagai arsip pertanggungjawaban.</p>
        </div>

        <div class="signatures">
            <div class="sig-box">
                <p>&nbsp;<br>Mengetahui,</p>
                <div class="space"></div>
                <strong>( ................................ )</strong>
            </div>
            <div class="sig-box">
                <p>&nbsp;<br>Penyetor,</p>
                <div class="space"></div>
                <strong>( {{ $income->payer_name ?: '................................' }} )</strong>
            </div>
            <div class="sig-box">
                <p>{{ now()->translatedFormat('l, d F Y') }}<br>Diterima Oleh,</p>
                <div class="space"></div>
                <strong>( {{ $income->user->name ?? 'Admin Finance' }} )</strong>
            </div> <!-- end sig-box -->
        </div> <!-- end signatures -->

        <div style="text-align: center; margin-top: 10px; font-size: 8px; color: #94a3b8;">
            &copy; {{ date('Y') }} LPT NURUL ILMI. Digital Income Document Generated System.
        </div>
    </div> <!-- end invoice-wrapper -->

    @if($income->nota || $income->photo)
    <div class="attachment-container">
        <h2 style="text-align: center; font-size: 18px; color: var(--dark-color); margin-bottom: 20px; text-decoration: underline;">LAMPIRAN BUKTI TRANSAKSI</h2>

        @if($income->nota)
        <div style="text-align: center; margin-bottom: 20px; page-break-inside: avoid;">
            <div style="font-weight: bold; margin-bottom: 5px; border-bottom: 1px solid #ddd; display: inline-block; padding-bottom: 2px; font-size: 12px;">FOTO NOTA / BUKTI TERIMA</div>
            <div style="border: 1px solid #ddd; padding: 5px; display: block; background: #fff;">
                <img src="{{ asset('storage/' . $income->nota) }}" style="max-width: 95%; max-height: 110mm; object-fit: contain;">
            </div>
        </div>
        @endif

        @if($income->photo)
        <div style="text-align: center; margin-bottom: 15px; page-break-inside: avoid;">
            <div style="font-weight: bold; margin-bottom: 5px; border-bottom: 1px solid #ddd; display: inline-block; padding-bottom: 2px; font-size: 12px;">DOKUMENTASI DUKUNGAN</div>
            <div style="border: 1px solid #ddd; padding: 5px; display: block; background: #fff;">
                <img src="{{ asset('storage/' . $income->photo) }}" style="max-width: 95%; max-height: 110mm; object-fit: contain;">
            </div>
        </div>
        @endif
        
        <div style="margin-top: 10px; font-size: 10px; text-align: center; color: #aaa;">
            Lampiran ini adalah bagian tidak terpisahkan dari Bukti Kas Masuk {{ $income->proof_code ? '#' . $income->proof_code : '#INC-' . str_pad($income->id, 5, '0', STR_PAD_LEFT) }}
        </div>
    </div>
    @endif

</body>
</html>
