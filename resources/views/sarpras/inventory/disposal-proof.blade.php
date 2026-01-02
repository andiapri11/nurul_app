<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Penghapusan Barang</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12px; margin: 40px; color: #000; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px double #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 18px; }
        .header p { margin: 5px 0 0; font-size: 12px; }
        .doc-title { text-align: center; margin-bottom: 25px; }
        .doc-title h3 { text-decoration: underline; margin-bottom: 5px; text-transform: uppercase; }
        .content { margin-bottom: 20px; text-align: justify; }
        .item-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .item-table th, .item-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .item-table th { background-color: #f2f2f2; text-align: center; }
        .footer { margin-top: 50px; width: 100%; page-break-inside: avoid; }
        .footer table { border: none; width: 100%; }
        .footer td { border: none; text-align: center; width: 33%; vertical-align: top; }
        .signature-space { height: 70px; }
        .text-center { text-align: center; }
        @media print {
            body { margin: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>LPT NURUL ILMI</h2>
        <p>Jl. Jend. Sudirman No. 123, Indonesia</p>
    </div>

    <div class="doc-title">
        <h3>BERITA ACARA PENGHAPUSAN BARANG INVENTARIS</h3>
        <p>Nomor: {{ $report ? 'BA-INV/'.$report->id.'/'.now()->format('Y') : 'BA-INV/AUT/'.now()->format('Y') }}</p>
    </div>

    <div class="content">
        Pada hari ini <strong>{{ now()->translatedFormat('l') }}</strong>, tanggal <strong>{{ now()->translatedFormat('d F Y') }}</strong>, 
        telah dilakukan penghapusan/pemusnahan terhadap aset/barang inventaris lembaga dengan rincian sebagai berikut:
    </div>

    <table class="item-table">
        <tr>
            <th width="30%">Nama Barang</th>
            <td>{{ $inventory->name }}</td>
        </tr>
        <tr>
            <th>Kode Barang</th>
            <td><strong>{{ $inventory->code }}</strong></td>
        </tr>
        <tr>
            <th>Kategori</th>
            <td>{{ $inventory->category->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Lokasi Terakhir</th>
            <td>{{ $inventory->room->name ?? '-' }} ({{ $inventory->room->unit->name ?? '-' }})</td>
        </tr>
        <tr>
            <th>Tanggal Perolehan</th>
            <td>{{ $inventory->purchase_date ? $inventory->purchase_date->format('d/m/Y') : '-' }}</td>
        </tr>
        <tr>
            <th>Harga Perolehan</th>
            <td>Rp {{ number_format($inventory->price, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Alasan Penghapusan</th>
            <td>{{ $inventory->disposal_reason }}</td>
        </tr>
    </table>

    <div class="content">
        Demikian Berita Acara ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya. 
        Penghapusan ini telah melalui prosedur pelaporan, validasi oleh Kepala Sekolah, dan persetujuan dari Pimpinan Lembaga.
    </div>

    <div class="footer">
        <table>
            <tr>
                <td>
                    Menyetujui,<br>
                    Pimpinan Lembaga
                    <div class="signature-space"></div>
                    <div style="display: flex; justify-content: center; align-items: flex-start;">
                        <strong style="display: inline-block; max-width: 250px; line-height: 1.1; font-size: 10pt; text-decoration: underline;">{{ $officials['director'] }}</strong>
                    </div>
                </td>
                <td>
                    Mengetahui,<br>
                    Kepala Sekolah
                    <div class="signature-space"></div>
                    <div style="display: flex; justify-content: center; align-items: flex-start;">
                        <strong style="display: inline-block; max-width: 250px; line-height: 1.1; font-size: 10pt; text-decoration: underline;">{{ $officials['principal'] }}</strong>
                    </div>
                </td>
                <td>
                    Dibuat Oleh,<br>
                    Wakil Sarpras
                    <div class="signature-space"></div>
                    <div style="display: flex; justify-content: center; align-items: flex-start;">
                        <strong style="display: inline-block; max-width: 250px; line-height: 1.1; font-size: 10pt; text-decoration: underline;">{{ $officials['sarpras'] }}</strong>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Attachment Page -->
    <div style="page-break-before: always;"></div>
    <div class="header">
        <h2>LPT NURUL ILMI</h2>
        <p>Lampiran Berita Acara Penghapusan Barang</p>
    </div>

    <div class="doc-title">
        <h3>LAMPIRAN BUKTI FISIK PEMUSNAHAN</h3>
        <p>Barang: {{ $inventory->name }} ({{ $inventory->code }})</p>
    </div>

    <div class="text-center" style="margin-top: 30px;">
        @if($inventory->disposal_photo)
            <div style="border: 1px solid #000; padding: 10px; display: inline-block; background-color: #fff;">
                <img src="{{ asset('storage/' . $inventory->disposal_photo) }}" style="max-width: 100%; max-height: 350px; object-fit: contain;">
            </div>
            <p style="margin-top: 10px; font-style: italic;">Foto bukti fisik pemusnahan barang yang diunggah pada sistem.</p>
        @else
            <div style="border: 2px dashed #ccc; padding: 50px; color: #999;">
                <p>Tidak ada foto bukti fisik yang dilampirkan.</p>
            </div>
        @endif
    </div>

    <div class="text-center no-print" style="margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Ulang Dokumen</button>
    </div>
</body>
</html>
