@extends('layouts.app')

@section('title', 'Laporan Realisasi')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 fw-bold text-dark"><i class="bi bi-file-earmark-medical-fill text-info me-2"></i>Laporan Realisasi</h1>
                <p class="text-muted small mb-0">Manajemen pencairan dana dan verifikasi bukti nota pengadaan barang.</p>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        @if($activeAcademicYear)
        <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
            <div>
                Menampilkan data untuk <strong>Tahun Pelajaran: {{ $activeAcademicYear->name }}</strong> (Aktif). 
                Gunakan filter di bawah untuk melihat periode lainnya.
            </div>
        </div>
        @endif

        <!-- Filters -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <form action="{{ route('finance.realization.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label-premium">Unit Sekolah</label>
                        <select name="unit_id" class="form-select form-control-premium">
                            <option value="">Semua Unit</option>
                            @foreach($units as $u)
                                <option value="{{ $u->id }}" {{ request('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-premium">Tahun Pelajaran</label>
                        <select name="academic_year_id" class="form-select form-control-premium">
                            <option value="">Semua Tahun</option>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ ($academicYearId ?? request('academic_year_id')) == $ay->id ? 'selected' : '' }}>{{ $ay->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-premium btn-info text-white flex-grow-1">
                            <i class="bi bi-filter me-2"></i>Filter Data
                        </button>
                        <a href="{{ route('finance.realization.index') }}" class="btn btn-premium btn-light border flex-grow-1">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Dashboard Tabs Style -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                <ul class="nav nav-pills gap-2" id="realizationTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-4 fw-bold shadow-sm-hover transition-all" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                            <i class="bi bi-hourglass-split me-2"></i>Belum Lapor (Cairkan)
                            @if(count($pendingDisbursements) > 0)
                                <span class="badge bg-danger ms-2">{{ count($pendingDisbursements) }}</span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 fw-bold shadow-sm-hover transition-all" id="reported-tab" data-bs-toggle="tab" data-bs-target="#reported" type="button" role="tab">
                            <i class="bi bi-check2-square me-2"></i>Sudah Lapor (Verifikasi)
                            @if(count($pendingReports) > 0)
                                <span class="badge bg-warning text-dark ms-2">{{ count($pendingReports) }}</span>
                            @endif
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="card-body p-0">
                <div class="tab-content" id="realizationTabsContent">
                    <!-- Tab 1: Pending Disbursement -->
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary small text-uppercase">
                                    <tr>
                                        <th class="ps-4 py-3">Kode / Nama Kegiatan</th>
                                        <th>Unit Sekolah</th>
                                        <th class="text-end">Total Disetujui</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                    <!-- Tab 1: Belum Lapor -->
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary small text-uppercase">
                                    <tr>
                                        <th class="ps-4 py-3">Kode / Nama Kegiatan</th>
                                        <th>Unit</th>
                                        <th class="text-center">Status Kas</th>
                                        <th class="text-end">Rencana Anggaran</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingDisbursements as $proc)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="fw-bold text-success" style="font-size: 0.8rem;">#{{ $proc->request_code }}</div>
                                            <div class="fw-bold text-dark fs-6">{{ $proc->activity_name }}</div>
                                            <div class="small text-muted">{{ $proc->items_count }} item barang</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark fw-bold border">{{ $proc->unit_name }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if(isset($proc->report_status) && $proc->report_status === 'Reported')
                                                <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">Menunggu Verifikasi</span>
                                            @elseif($proc->is_cair)
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Sudah Cair</span>
                                                <div class="x-small text-danger fw-bold mt-1">Belum Lapor</div>
                                            @else
                                                <span class="badge bg-warning bg-opacity-10 text-warning text-dark rounded-pill px-3">Belum Cair</span>
                                            @endif
                                        </td>
                                        <td class="text-end fw-extrabold text-dark">
                                            Rp {{ number_format($proc->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            @if(isset($proc->report_status) && $proc->report_status === 'Reported')
                                                <button 
                                                    data-items="{{ json_encode($proc->items ?? []) }}"
                                                    onclick="openVerifyModal(this, '{{ $proc->request_code }}', '{{ $proc->activity_name }}', '{{ $proc->report_nota }}', '{{ $proc->report_photo }}', '{{ addslashes($proc->report_note ?? '') }}')" 
                                                    class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                                    <i class="bi bi-check-circle me-1"></i> Verifikasi
                                                </button>
                                            @elseif(isset($proc->is_general_expense) && $proc->is_general_expense)
                                                <button onclick="openUploadModal('{{ $proc->id }}', '{{ $proc->request_code }}')" class="btn btn-warning btn-sm rounded-pill px-4 fw-bold shadow-sm">
                                                    <i class="bi bi-upload me-1"></i> Upload Nota
                                                </button>
                                            @else
                                                <a href="{{ route('sarpras.procurements.print', $proc->request_code) }}" target="_blank" class="btn btn-premium btn-light border btn-sm rounded-pill px-4">
                                                    <i class="bi bi-printer me-1"></i> Cetak Pengajuan
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="py-4 text-muted opacity-25">
                                                <i class="bi bi-check2-circle display-1 mb-3 d-block"></i>
                                                <h5 class="fw-bold">Semua Aman!</h5>
                                                <p class="small">Tidak ada pengadaan tertunda untuk pelaporan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 2: Reported -->
                    <div class="tab-pane fade" id="reported" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary small text-uppercase">
                                    <tr>
                                        <th class="ps-4 py-3">Kode / Nama Kegiatan</th>
                                        <th>Tanggal Lapor</th>
                                        <th class="text-center">Status Laporan</th>
                                        <th class="text-end">Total Realisasi</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingReports as $report)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="fw-bold text-warning" style="font-size: 0.8rem;">#{{ $report->request_code }}</div>
                                            <div class="fw-bold text-dark fs-6">{{ $report->activity_name }}</div>
                                            <div class="small text-muted">{{ $report->unit_name }}</div>
                                        </td>
                                        <td>
                                            <span class="small text-muted">
                                                {{ $report->report_at ? \Carbon\Carbon::parse($report->report_at)->translatedFormat('d M Y') : '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($report->report_status === 'Verified')
                                                <span class="badge bg-success rounded-pill px-3">Terverifikasi</span>
                                            @else
                                                <span class="badge bg-info text-dark rounded-pill px-3">Menunggu Verifikasi</span>
                                            @endif
                                        </td>
                                        <td class="text-end fw-extrabold text-dark">
                                            Rp {{ number_format($report->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            @if($report->report_status !== 'Verified' && !($report->is_general_expense ?? false))
                                                <button 
                                                    data-items="{{ json_encode($report->items ?? []) }}"
                                                    onclick="openVerifyModal(this, '{{ $report->request_code }}', '{{ $report->activity_name }}', '{{ $report->report_nota }}', '{{ $report->report_photo }}', '{{ addslashes($report->report_note) }}')" 
                                                    class="btn btn-warning btn-sm rounded-pill px-3 fw-bold shadow-sm me-1">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Verifikasi
                                                </button>
                                            @endif
                                            
                                            @if(isset($report->is_general_expense) && $report->is_general_expense)
                                                <a href="{{ route('finance.expense.print', $report->id) }}" target="_blank" class="btn btn-premium btn-light border btn-sm rounded-pill px-4">
                                                    <i class="bi bi-printer me-1"></i> Cetak Bukti
                                                </a>
                                                
                                                @if(auth()->user()->hasRole('administrator'))
                                                <form action="{{ route('finance.realization.cancel') }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Batalkan verifikasi pengeluaran ini? User harus upload ulang nota.')">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $report->id }}">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm">
                                                        <i class="bi bi-x-circle me-1"></i> Batal
                                                    </button>
                                                </form>
                                                @endif
                                            @elseif($report->report_status === 'Verified' && isset($report->expense_id))
                                                <a href="{{ route('finance.expense.print', $report->expense_id) }}" target="_blank" class="btn btn-premium btn-success border btn-sm rounded-pill px-4">
                                                    <i class="bi bi-printer me-1"></i> Cetak Bukti Kas
                                                </a>
                                                
                                                @if(auth()->user()->hasRole('administrator'))
                                                <form action="{{ route('finance.realization.cancel') }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan verifikasi ini? Status akan kembali ke Menunggu Verifikasi.')">
                                                    @csrf
                                                    <input type="hidden" name="request_code" value="{{ $report->request_code }}">
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm">
                                                        <i class="bi bi-x-circle me-1"></i> Batal
                                                    </button>
                                                </form>
                                                @endif
                                            @else
                                                <a href="{{ route('sarpras.procurements.print', $report->request_code) }}" target="_blank" class="btn btn-premium btn-light border btn-sm rounded-pill px-4">
                                                    <i class="bi bi-printer me-1"></i> Cetak Laporan
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="py-4 text-muted opacity-25">
                                                <i class="bi bi-clock-history display-1 mb-3 d-block"></i>
                                                <h5 class="fw-bold">Belum Ada Laporan</h5>
                                                <p class="small">Belum ada nota yang diunggah oleh Sarpras atau data tidak ditemukan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .nav-pills .nav-link { color: #64748b; }
    .nav-pills .nav-link.active { background-color: #0dcaf0; color: white !important; }
    .nav-pills .nav-link:hover:not(.active) { background-color: #f1f5f9; }
    
    .btn-premium {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        font-weight: 600;
    }
    .btn-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .fw-extrabold { font-weight: 900; }
    .hover-zoom { transition: transform 0.2s; cursor: zoom-in; }
    .hover-zoom:hover { transform: scale(1.02); }
</style>
@endpush

<!-- Modal Upload Nota -->
<div class="modal fade" id="uploadNotaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom-0">
                <h5 class="fw-bold mb-0">Upload Bukti Nota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadNotaForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kode Pengeluaran</label>
                        <input type="text" class="form-control" id="uploadProofCode" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Foto Nota / Bukti Bayar <span class="text-danger">*</span></label>
                        <input type="file" name="nota" class="form-control" accept="image/*" required>
                        <div class="form-text small">Wajib diunggah sebagai bukti transaksi utama.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Foto Kegiatan / Barang (Opsional)</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        <div class="form-text small">Unggah foto saat serah terima barang atau kegiatan berlangsung.</div>
                    </div>
                    <div class="alert alert-info small d-flex align-items-center mb-0">
                        <i class="bi bi-info-circle me-2 fs-5"></i>
                        <div>Pastikan nominal pada nota sesuai dengan pengeluaran yang dicatat.</div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-pill fw-bold px-4">Upload Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Verifikasi Laporan -->
<div class="modal fade" id="verifyReportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0" style="background: #f8fafc;">
            <div class="modal-header border-bottom-0 bg-white py-3">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Verifikasi Laporan Realisasi</h5>
                    <p class="text-muted small mb-0">Tinjau bukti nota, foto kegiatan, dan daftar item sebelum menyetujui.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('finance.realization.verify') }}" method="POST" id="verifyForm">
                    @csrf
                    <input type="hidden" name="request_code" id="verifyRequestCode">
                    
                    <div class="row g-4">
                        <!-- Left Column: Evidence -->
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <h6 class="fw-bold text-uppercase text-muted small mb-3">Bukti & Dokumentasi</h6>
                                    
                                    <div class="mb-4">
                                        <label class="small fw-bold text-dark d-block mb-2">Foto Nota / Bukti Bayar</label>
                                        <div class="position-relative bg-light rounded-3 overflow-hidden border" style="min-height: 200px;">
                                            <a id="verifyNotaLink" href="#" target="_blank" class="d-block text-decoration-none">
                                                <img id="verifyNotaImg" src="" class="img-fluid w-100 hover-zoom" style="object-fit: contain; min-height: 200px; max-height: 300px; display: none;">
                                                <div id="verifyNotaPlaceholder" class="position-absolute top-50 start-50 translate-middle text-center w-100 text-muted small">
                                                    <i class="bi bi-file-earmark-x fs-1 d-block mb-2 opacity-50"></i>
                                                    Tidak ada bukti nota
                                                </div>
                                            </a>
                                            <div class="position-absolute bottom-0 end-0 p-2">
                                                <span class="badge bg-dark bg-opacity-75 rounded-pill"><i class="bi bi-zoom-in me-1"></i>Klik untuk perbesar</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="small fw-bold text-dark d-block mb-2">Foto Kegiatan (Opsional)</label>
                                        <div class="position-relative bg-light rounded-3 overflow-hidden border" style="min-height: 200px;">
                                            <a id="verifyPhotoLink" href="#" target="_blank" class="d-block text-decoration-none">
                                                <img id="verifyPhotoImg" src="" class="img-fluid w-100 hover-zoom" style="object-fit: cover; min-height: 200px; max-height: 300px; display: none;">
                                                <div id="verifyPhotoPlaceholder" class="position-absolute top-50 start-50 translate-middle text-center w-100 text-muted small">
                                                    <i class="bi bi-camera-video-off fs-1 d-block mb-2 opacity-50"></i>
                                                    Tidak ada foto kegiatan
                                                </div>
                                            </a>
                                            <div class="position-absolute bottom-0 end-0 p-2">
                                                <span class="badge bg-dark bg-opacity-75 rounded-pill"><i class="bi bi-zoom-in me-1"></i>Klik untuk perbesar</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Details & Items -->
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body p-4 border-bottom">
                                    <h6 class="fw-bold mb-1 fs-5" id="verifyActivityName">-</h6>
                                    <div class="text-muted small">
                                        <span class="badge bg-light text-dark border me-2" id="verifyCodeDisplay">#CODE</span>
                                        <i class="bi bi-chat-left-text me-1"></i> Catatan: <span class="fst-italic" id="verifyNote">-</span>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light small text-secondary">
                                                <tr>
                                                    <th class="ps-4 py-2">Item Pengajuan</th>
                                                    <th class="text-center">Qty / Satuan</th>
                                                    <th class="text-end">Harga Satuan</th>
                                                    <th class="text-end pe-4">Total Realisasi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="verifyItemsTableBody">
                                                <!-- Items rendered by JS -->
                                            </tbody>
                                            <tfoot class="bg-light fw-bold border-top">
                                                <tr>
                                                    <td colspan="3" class="text-end py-3 text-uppercase small text-muted">Total Keseluruhan</td>
                                                    <td class="text-end pe-4 py-3 fs-6 text-dark" id="verifyItemsTotal">Rp 0</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Form -->
                            <div class="card border-0 shadow-sm bg-white">
                                <div class="card-body p-4">
                                    <h6 class="fw-bold text-uppercase text-muted small mb-3">Keputusan Verifikasi</h6>
                                    
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small">Catatan Keuangan (Opsional)</label>
                                        <textarea name="finance_note" class="form-control bg-light" rows="2" placeholder="Tulis catatan untuk Sarpras jika ada revisi atau info tambahan..."></textarea>
                                    </div>

                                    <div class="d-flex gap-3 align-items-center p-3 bg-light rounded-3">
                                        <div class="flex-grow-1">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="status" id="statusVerified" value="Verified" checked>
                                                <label class="form-check-label fw-bold text-success" for="statusVerified">
                                                    <i class="bi bi-check-circle-fill me-1"></i> Setujui Laporan
                                                </label>
                                                <div class="small text-muted ps-4">Laporan valid, bukti sesuai, dan nominal benar.</div>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="status" id="statusRejected" value="Rejected">
                                                <label class="form-check-label fw-bold text-danger" for="statusRejected">
                                                    <i class="bi bi-x-circle-fill me-1"></i> Tolak / Perlu Revisi
                                                </label>
                                                <div class="small text-muted ps-4">Laporan tidak sesuai atau butuh perbaikan nota.</div>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary rounded-pill fw-bold px-4 py-2 shadow-sm">
                                                Simpan Keputusan <i class="bi bi-arrow-right ms-2"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function openUploadModal(id, code) {
        document.getElementById('uploadProofCode').value = code;
        const form = document.getElementById('uploadNotaForm');
        form.action = `/finance/expense/${id}/upload-proof`;
        
        var modal = new bootstrap.Modal(document.getElementById('uploadNotaModal'));
        modal.show();
    }

    function openVerifyModal(btn, code, activity, nota, photo, note) {
        document.getElementById('verifyRequestCode').value = code;
        document.getElementById('verifyCodeDisplay').innerText = '#' + code;
        document.getElementById('verifyActivityName').innerText = activity;
        document.getElementById('verifyNote').innerText = note || 'Tidak ada catatan';
        
        // Items parsing
        var items = [];
        try {
            items = JSON.parse(btn.getAttribute('data-items'));
        } catch(e) { console.error('Error parsing items', e); }

        var tbody = document.getElementById('verifyItemsTableBody');
        tbody.innerHTML = '';
        var grandTotal = 0;

        if (items && items.length > 0) {
            items.forEach(function(item) {
                var qty = item.approved_quantity || item.quantity || item.qty || 0;
                var price = item.approved_price || item.estimated_price || item.price || 0;
                var total = qty * price;
                grandTotal += total;

                var row = `<tr>
                    <td class="ps-4">
                        <div class="fw-bold text-dark">${item.item_name || item.name}</div>
                        <div class="small text-muted">${item.category_name || '-'}</div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-light text-dark border">${qty} ${item.unit_name || item.unit || ''}</span>
                    </td>
                    <td class="text-end">Rp ${formatNumber(price)}</td>
                    <td class="text-end pe-4 fw-bold">Rp ${formatNumber(total)}</td>
                </tr>`;
                tbody.innerHTML += row;
            });
        } else {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-muted small">Tidak ada data item rincian.</td></tr>`;
        }
        document.getElementById('verifyItemsTotal').innerText = 'Rp ' + formatNumber(grandTotal);
        
        // Handle Images
        var notaImg = document.getElementById('verifyNotaImg');
        var notaLink = document.getElementById('verifyNotaLink');
        var notaPlaceholder = document.getElementById('verifyNotaPlaceholder');
        
        if (nota) {
            var notaSrc = '/storage/' + nota;
            notaImg.src = notaSrc;
            notaLink.href = notaSrc;
            notaImg.style.display = 'block';
            notaPlaceholder.style.display = 'none';
        } else {
            notaLink.href = '#';
            notaImg.style.display = 'none';
            notaPlaceholder.style.display = 'block';
        }

        var photoImg = document.getElementById('verifyPhotoImg');
        var photoLink = document.getElementById('verifyPhotoLink');
        var photoPlaceholder = document.getElementById('verifyPhotoPlaceholder');
        
        if (photo) {
            var photoSrc = '/storage/' + photo;
            photoImg.src = photoSrc;
            photoLink.href = photoSrc;
            photoImg.style.display = 'block';
            photoPlaceholder.style.display = 'none';
        } else {
            photoLink.href = '#';
            photoImg.style.display = 'none';
            photoPlaceholder.style.display = 'block';
        }

        var modal = new bootstrap.Modal(document.getElementById('verifyReportModal'));
        modal.show();
    }
</script>
@endpush
@endsection
