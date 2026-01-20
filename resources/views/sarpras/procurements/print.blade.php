<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pengajuan Pengadaan - {{ $mainReq->request_code }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; background: #fff; }
        .print-container { width: 210mm; min-height: 297mm; padding: 20mm; margin: auto; border: 1px solid #ddd; background: #fff; }
        .kop-surat { text-align: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-logo { height: 80px; width: auto; margin-bottom: 10px; }
        .kop-title { font-size: 20pt; font-weight: bold; margin: 0; color: #1a237e; }
        .kop-subtitle { font-size: 11pt; margin: 0; font-style: italic; color: #555; }
        .section-title { text-align: center; text-decoration: underline; font-weight: bold; margin-bottom: 20px; font-size: 14pt; margin-top: 10px; }
        .table-custom th { background-color: #f0f0f0 !important; text-align: center; vertical-align: middle; font-size: 9pt; text-transform: uppercase; border: 1px solid #000; }
        .table-custom td { vertical-align: middle; font-size: 9pt; border: 1px solid #000; }
        .summary-box { border: 2px solid #000; padding: 10px; margin-bottom: 20px; background-color: #f9f9f9; }
        .footer-sign { margin-top: 50px; width: 100%; page-break-inside: avoid; }
        .footer-sign table { border: none !important; width: 100%; }
        .footer-sign td { border: none !important; text-align: center; width: 33.3%; vertical-align: top; }
        .signature-space { height: 70px; }
        @media print {
            body { background: none; }
            .print-container { border: none; padding: 0; margin: 0; width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="{% if request('auto_print') %}window.print(){% endif %}">
    <div class="no-print p-3 text-center bg-dark text-white mb-3">
        <button onclick="window.print()" class="btn btn-primary btn-lg px-5 shadow-sm"><i class="bi bi-printer"></i> CETAK DOKUMEN</button>
        <button onclick="window.history.back()" class="btn btn-secondary btn-lg px-4 ms-2">KEMBALI</button>
    </div>

    <div class="print-container shadow-sm">
        <div class="kop-surat">
            <h1 class="kop-title">{{ strtoupper($mainReq->unit->name) }}</h1>
            @php
                $appAddress = \App\Models\Setting::where('key', 'app_address')->value('value') ?? '';
            @endphp
            @if($appAddress) <p style="margin: 0; font-size: 10pt;">{{ $appAddress }}</p> @endif
            <p class="kop-subtitle">Pengajuan Pengadaan Barang</p>
        </div>

        @if($mainReq->report_status === 'Verified')
            <h4 class="section-title">BUKTI TRANSAKSI & REALISASI PENGADAAN</h4>
        @else
            <h4 class="section-title">FORM PERSETUJUAN PENGADAAN BARANG</h4>
        @endif

        <div class="row g-0 mb-4 border p-3 bg-white">
            <div class="col-6">
                <table class="table table-sm table-borderless small mb-0">
                    <tr><td width="130">Nomor Registrasi</td><td>: <strong>{{ $mainReq->request_code }}</strong></td></tr>
                    <tr><td>Unit Kerja</td><td>: {{ $mainReq->unit->name }}</td></tr>
                    <tr><td>Nama Kegiatan</td><td>: {{ $mainReq->activity_name }}</td></tr>
                    <tr><td>Pemohon (Staff)</td><td>: {{ $mainReq->user->name }}</td></tr>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-sm table-borderless small mb-0">
                    <tr><td width="130">Tanggal Pengajuan</td><td>: {{ $mainReq->created_at->translatedFormat('d F Y') }}</td></tr>
                    <tr><td>Tipe Pendanaan</td><td>: <span class="badge border text-dark">{{ $mainReq->type }}</span></td></tr>
                    <tr><td>Status Validasi Kepsek</td><td>: 
                        @if($mainReq->principal_status === 'Validated')
                            <span class="text-success fw-bold">TERVALIDASI</span>
                        @else
                            <span class="text-danger fw-bold">{{ $mainReq->principal_status }}</span>
                        @endif
                    </td></tr>
                    <tr><td>Status Akhir Direktur</td><td>: 
                        @if($overallDirectorStatus === 'Approved')
                            <span class="text-success fw-bold">DISETUJUI</span>
                        @elseif($overallDirectorStatus === 'Rejected')
                            <span class="text-danger fw-bold">DITOLAK</span>
                        @else
                            <span class="text-warning fw-bold">PROSES</span>
                        @endif
                    </td></tr>
                </table>
            </div>
        </div>

        <table class="table table-bordered table-custom">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th>Nama Barang & Spesifikasi</th>
                    <th width="80">Jumlah Rev.</th>
                    <th width="120">Est. Harga Satuan</th>
                    <th width="120">Harga Disetujui</th>
                    <th width="120">Subtotal Final</th>
                    <th width="80">Status</th>
                </tr>
            </thead>
            <tbody>
                @php $totalFinal = 0; @endphp
                @foreach($items as $index => $item)
                @php 
                    $priceFinal = $item->approved_price ?: $item->estimated_price;
                    $qtyFinal = $item->approved_quantity ?: $item->quantity;
                    $subtotal = $item->director_status === 'Approved' ? ($qtyFinal * $priceFinal) : 0;
                    $totalFinal += $subtotal;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="fw-bold">{{ $item->item_name }}</div>
                        <div class="small text-muted">{{ $item->description ?: '-' }}</div>
                    </td>
                    <td class="text-center">
                        @if($item->director_status === 'Approved')
                            {{ $item->approved_quantity ?: $item->quantity }}
                        @else
                            {{ $item->quantity }}
                        @endif
                        {{ $item->unit_name }}
                    </td>
                    <td class="text-end">Rp {{ number_format($item->estimated_price, 0, ',', '.') }}</td>
                    <td class="text-end">
                        @if($item->director_status === 'Approved')
                            Rp {{ number_format($priceFinal, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-end fw-bold {{ $item->director_status === 'Approved' ? 'text-dark' : 'text-muted' }}">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </td>
                    <td class="text-center small">
                        @if($item->director_status === 'Approved')
                            <span class="text-success fw-bold">SETUJU</span>
                        @elseif($item->director_status === 'Rejected')
                            <span class="text-danger fw-bold">DITOLAK</span>
                        @else
                            <span class="text-muted">PENDING</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="5" class="text-end py-2">TOTAL ESTIMASI ANGGARAN (PENGAJUAN)</td>
                    <td class="text-end py-2">Rp {{ number_format($totalEstimated, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
                <tr class="fw-bold table-light">
                    <td colspan="5" class="text-end py-2">TOTAL REALISASI ANGGARAN (DISETUJUI)</td>
                    <td class="text-end py-2 border-primary" style="background-color: #e3f2fd !important; font-size: 11pt;">
                        Rp {{ number_format($totalApproved, 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <div class="row mt-4 mb-4">
            <div class="col-12">
                <div class="border p-2 small">
                    <div class="fw-bold mb-1 border-bottom pb-1">CATATAN & INSTRUKSI PIMPINAN:</div>
                    @if($mainReq->director_note)
                        <div class="italic">"{{ $mainReq->director_note }}"</div>
                    @else
                        <div class="text-muted">Tidak ada catatan tambahan dari pimpinan.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="footer-sign">
            <table>
                <tr>
                    <td>
                        Disetujui Oleh (Final),<br>
                        Pimpinan Lembaga
                        <div class="signature-space"></div>
                        <div style="display: flex; justify-content: center; align-items: flex-start;">
                            <strong style="display: inline-block; max-width: 250px; line-height: 1.1; font-size: 10pt; text-decoration: underline;">{{ $officials['director'] }}</strong>
                        </div>
                    </td>
                    <td>
                        Mengetahui (Validasi),<br>
                        Kepala Sekolah
                        <div class="signature-space"></div>
                        <div style="display: flex; justify-content: center; align-items: flex-start;">
                            <strong style="display: inline-block; max-width: 250px; line-height: 1.1; font-size: 10pt; text-decoration: underline;">{{ $officials['principal'] }}</strong>
                        </div>
                    </td>
                    <td>
                        Diajukan Oleh (Sarpras),<br>
                        Staff Sarpras Unit
                        <div class="signature-space"></div>
                        <div style="display: flex; justify-content: center; align-items: flex-start;">
                            <strong style="display: inline-block; max-width: 250px; line-height: 1.1; font-size: 10pt; text-decoration: underline;">{{ $officials['sarpras'] }}</strong>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

    <div class="mt-5 text-center small text-muted border-top pt-2 no-print">
            Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} oleh {{ Auth::user()->name }}
        </div>
    </div>

    @if($mainReq->report_nota || $mainReq->report_photo)
    <div class="print-container shadow-sm" style="page-break-before: always;">
        <div class="kop-surat">
            <h1 class="kop-title">{{ strtoupper($mainReq->unit->name) }}</h1>
            @if($appAddress) <p style="margin: 0; font-size: 10pt;">{{ $appAddress }}</p> @endif
            <p class="kop-subtitle">Lampiran Bukti Realisasi</p>
        </div>

        <h4 class="section-title">BUKTI NOTA & REALISASI</h4>

        <div class="row">
            @if($mainReq->report_nota)
            <div class="col-12 mb-4 text-center">
                <div class="border p-1 bg-white">
                    <h5 class="fw-bold fs-6 mb-2 text-start px-2 py-1 bg-light border-bottom">FOTO NOTA / BUKTI BAYAR</h5>
                    <img src="{{ asset('storage/' . $mainReq->report_nota) }}" style="max-width: 100%; max-height: 120mm; object-fit: contain;">
                </div>
            </div>
            @endif

            @if($mainReq->report_photo)
            <div class="col-12 mb-4 text-center">
                <div class="border p-1 bg-white">
                    <h5 class="fw-bold fs-6 mb-2 text-start px-2 py-1 bg-light border-bottom">DOKUMENTASI KEGIATAN</h5>
                    <img src="{{ asset('storage/' . $mainReq->report_photo) }}" style="max-width: 100%; max-height: 120mm; object-fit: contain;">
                </div>
            </div>
            @endif

             <div class="col-12">
                <div class="border p-3 small bg-white">
                    <div class="fw-bold mb-1 border-bottom pb-1">CATATAN PELAPORAN:</div>
                    @if($mainReq->report_note)
                         <div class="italic">"{{ $mainReq->report_note }}"</div>
                    @else
                         <div class="text-muted">Tidak ada catatan pelaporan.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="footer-sign mt-5">
             <div class="text-center w-100 mb-5">
                <small class="text-muted">Bukti ini adalah bagian tidak terpisahkan dari dokumen pengajuan #{{ $mainReq->request_code }}</small>
            </div>
        </div>
    </div>
    @endif
</body>
</html>
