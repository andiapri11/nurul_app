<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi #{{ $transaction->invoice_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Epson LX-310 - Invoice Size (21cm x 14cm) Landscape */
        @page {
            size: 21cm 14cm;
            margin: 0.5cm;
        }

        /* Force pure black for dot matrix - Targeted to wrapper only */
        .invoice-wrapper, .invoice-wrapper * {
            color: #000 !important;
            border-color: #000 !important;
            -webkit-print-color-adjust: exact;
            background-color: transparent !important;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 8.5pt; /* Slightly smaller for tighter fit */
            margin: 0;
            padding: 0;
            width: 100%;
            line-height: 1.0; /* Super tight line height */
        }

        .invoice-wrapper {
            width: 100%;
            max-width: 20cm; 
            margin: 0 auto;
            position: relative;
        }

        /* Watermark - Lighter border */
        .paid-stamp {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            border: 1px solid #000;
            font-size: 45px;
            font-weight: 400;
            padding: 5px 25px;
            text-transform: uppercase;
            opacity: 0.08;
            border-radius: 10px;
            z-index: 0;
            white-space: nowrap;
            pointer-events: none;
        }

        /* Layout Tables */
        .full-table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Header */
        .school-info h1 {
            margin: 0;
            font-size: 13pt;
            font-weight: normal;
        }
        .school-info p {
            margin: 1px 0;
            font-size: 8pt;
            line-height: 1.1;
        }
        .kwitansi-title {
            font-size: 18pt;
            font-weight: normal;
            text-align: right;
            margin: 0;
            letter-spacing: 1px;
        }

        /* Info Sections */
        .section-header {
            font-size: 7pt;
            font-weight: normal;
            border-bottom: 0.1pt solid #000; /* Hairline */
            margin-bottom: 3px;
            padding-bottom: 1px;
            text-transform: uppercase;
        }

        .info-detail p {
            margin: 1px 0; /* Minimal vertical gap */
        }

        /* Items Table */
        .items-header {
            font-size: 8pt;
            border-bottom: 0.1pt solid #000; /* Hairline */
            text-transform: uppercase;
            padding: 4px 0;
            font-weight: normal;
        }
        
        .item-row td {
            padding: 3px 0; /* Reduced padding */
            border-bottom: 0.1pt solid #ccc; /* Hairline */
        }
        
        /* Totals Area */
        .total-label { text-align: right; font-size: 8.5pt; padding-right: 15px; }
        .total-value { text-align: right; font-weight: normal; width: 4cm; }
        .grand-total-row td { 
            font-weight: normal; 
            font-size: 10pt;
            border-top: 0.1pt solid #000; /* Hairline */
            padding-top: 3px;
        }

        /* Footer */
        .catatan {
            font-size: 7pt;
            margin-top: 10px;
            line-height: 1.2;
        }

        .signatures-table {
            margin-top: 15px;
            width: 100%;
        }
        .sig-container {
            text-align: center;
            width: 45%;
        }
        .sig-line {
            margin-top: 35px; /* Reduced space for signature */
            font-weight: normal;
            display: inline-block;
            min-width: 180px;
            padding-top: 4px;
        }

        .actions {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 12px;
            z-index: 9999;
        }
        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            display: inline-flex;
            align-items: center;
        }
        .btn-back {
            background-color: #ffffff;
            color: #1e293b !important;
            border: 1px solid #e2e8f0;
        }
        .btn-print {
            background-color: #0f172a;
            color: #ffffff !important;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .btn:active {
            transform: translateY(0);
        }

        @media print {
            .actions { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="actions">
        <a href="javascript:history.back()" class="btn btn-back">← KEMBALI</a>
        <button onclick="window.print()" class="btn btn-print">CETAK KWITANSI</button>
    </div>

    <div class="invoice-wrapper">
        <div class="paid-stamp">LUNAS / PAID</div>

        <!-- Header -->
        <table class="full-table">
            <tr>
                <td class="school-info" style="width: 65%;">
                    @php
                        $schoolName = \App\Models\Setting::where('key', 'school_name')->value('value') ?? 'LPT NURUL ILMI';
                        $appAddress = \App\Models\Setting::where('key', 'app_address')->value('value') ?? 'Alamat Sekolah...';
                    @endphp
                    <h1>{{ $schoolName }}</h1>
                    <p style="max-width: 12cm;">{{ $appAddress }}</p>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <h2 class="kwitansi-title">KWITANSI</h2>
                    <div style="font-size: 8.5pt; font-weight: normal; margin-top: 2px;">
                        #{{ $transaction->invoice_number }}<br>
                        Tgl: {{ $transaction->transaction_date->format('d/m/Y') }}
                    </div>
                </td>
            </tr>
        </table>

        <div style="height: 5px;"></div>

        <!-- Info Grid -->
        <table class="full-table">
            <tr>
                <td style="width: 48%; vertical-align: top;" class="info-detail">
                    <div class="section-header">DATA SISWA :</div>
                    <p>{{ $transaction->student->nama_lengkap }}</p>
                    <p>NIS: {{ $transaction->student->nis }}</p>
                    <p>Unit/Kelas: {{ $transaction->student->unit->name }} / {{ $transaction->student->classes->first()->name ?? '-' }}</p>
                </td>
                <td style="width: 4%;"></td>
                <td style="width: 48%; vertical-align: top;" class="info-detail">
                    <div class="section-header">METODE PEMBAYARAN:</div>
                    <p>Tipe: <span style="text-transform: uppercase;">{{ $transaction->payment_method }}</span></p>
                    <p>Petugas: {{ substr($transaction->user->name ?? 'Admin', 0, 20) }}</p>
                    <p>Status: PAID / LUNAS</p>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="full-table" style="margin-top: 10px;">
            <thead>
                <tr>
                    <th class="items-header" style="text-align: left; width: 60%;">Deskripsi Pembayaran</th>
                    <th class="items-header" style="text-align: center; width: 15%;">Periode</th>
                    <th class="items-header" style="text-align: right; width: 25%;">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->items as $item)
                <tr class="item-row">
                    <td>
                        <div>{{ $item->paymentType->name }}</div>
                        <div style="font-size: 7.5pt; color: #666;">{{ $transaction->notes ?? 'Pembayaran biaya pendidikan.' }}</div>
                    </td>
                    <td style="text-align: center; font-size: 8pt;">
                        @if($item->month_paid)
                            {{ [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'][$item->month_paid] }} {{ substr($item->year_paid, 2) }}
                        @else
                            -
                        @endif
                    </td>
                    <td style="text-align: right; font-size: 9pt;">
                        {{ number_format($item->amount, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Area -->
        <table class="full-table" style="margin-top: 5px;">
            <tr class="grand-total-row">
                <td style="width: 50%;">
                    <div class="catatan">
                        @php
                            function terbilang($angka) {
                                $angka = abs($angka);
                                $baca = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
                                $terbilang = "";
                                if ($angka < 12) {
                                    $terbilang = " " . $baca[$angka];
                                } else if ($angka < 20) {
                                    $terbilang = terbilang($angka - 10) . " belas";
                                } else if ($angka < 100) {
                                    $terbilang = terbilang($angka / 10) . " puluh" . terbilang($angka % 10);
                                } else if ($angka < 200) {
                                    $terbilang = " seratus" . terbilang($angka - 100);
                                } else if ($angka < 1000) {
                                    $terbilang = terbilang($angka / 100) . " ratus" . terbilang($angka % 100);
                                } else if ($angka < 2000) {
                                    $terbilang = " seribu" . terbilang($angka - 1000);
                                } else if ($angka < 1000000) {
                                    $terbilang = terbilang($angka / 1000) . " ribu" . terbilang($angka % 1000);
                                } else if ($angka < 1000000000) {
                                    $terbilang = terbilang($angka / 1000000) . " juta" . terbilang($angka % 1000000);
                                }
                                return $terbilang;
                            }
                        @endphp
                        <div style="font-style: italic; margin-bottom: 5px;">
                            <strong>Terbilang:</strong> {{ ucwords(terbilang($transaction->amount)) }} Rupiah
                        </div>
                        <strong>Ket:</strong> Bukti pembayaran sah LPT Nurul Ilmi.
                    </div>
                </td>
                <td class="total-label" style="font-weight: bold;">JUMLAH TOTAL</td>
                <td class="total-value" style="font-weight: bold;">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        <!-- Signatures -->
        <table class="signatures-table">
            <tr>
                <td class="sig-container" style="text-align: left; padding-left: 20px;">
                    <div style="font-size: 8.5pt;">Petugas Keuangan,</div>
                    <div class="sig-line">( {{ substr($transaction->user->name ?? 'Admin', 0, 20) }} )</div>
                </td>
                <td style="width: 10%;"></td>
                <td class="sig-container" style="text-align: right; padding-right: 20px;">
                    <div style="font-size: 8.5pt;">{{ now()->translatedFormat('d F Y') }}<br>Diterima Oleh,</div>
                    <div class="sig-line">( .................................... )</div>
                </td>
            </tr>
        </table>

        <div style="text-align: center; margin-top: 10px; font-size: 7pt; color: #666;">
            Generated by Portal Nurul Ilmi Finance System
        </div>
    </div>

</body>
</html>
