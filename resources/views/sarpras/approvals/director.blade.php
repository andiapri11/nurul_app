@use('Illuminate\Support\Str')
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Persetujuan Pimpinan Lembaga</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <!-- Filter Unit -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('sarpras.director.approvals') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Filter Unit Pendidikan</label>
                    <select name="unit_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('sarpras.director.approvals') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabs Container -->
    <div class="card shadow border-left-success">
        <div class="card-header p-0">
            <ul class="nav nav-tabs border-bottom-0" id="approvalTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active py-3 px-4 fw-bold text-success" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">
                        <i class="bi bi-shield-check me-2"></i> Laporan (Tervalidasi KS)
                        <span class="badge bg-success ms-2">{{ $reports->where('director_status', 'Pending')->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 px-4 fw-bold text-primary" id="procurements-tab" data-bs-toggle="tab" data-bs-target="#procurements" type="button" role="tab">
                        <i class="bi bi-shield-lock me-2"></i> Pengadaan (Tervalidasi KS)
                        <span class="badge bg-primary ms-2">{{ $procurements->where('director_status', 'Pending')->count() }}</span>
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content">
                <!-- Tab Laporan Kerusakan -->
                <div class="tab-pane fade show active" id="reports" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th>Unit / Item</th>
                                    <th>Tindakan Usulan</th>
                                    <th>Catatan KS</th>
                                    <th class="text-center">Status Final</th>
                                    <th class="text-end px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                <tr>
                                    <td class="small">
                                        <div class="fw-bold">{{ $report->inventory->name }}</div>
                                        <div>{{ $report->inventory->room->unit->name }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger">{{ $report->follow_up_action }}</span>
                                        <div class="small text-muted mt-1">{{ Str::limit($report->follow_up_description, 40) }}</div>
                                    </td>
                                    <td class="small italic text-success">
                                        "{{ $report->principal_note ?: 'Tanpa catatan' }}"
                                    </td>
                                    <td class="text-center">
                                        @if($report->director_status === 'Pending')
                                            <span class="badge bg-warning text-dark">Menunggu Pimpinan</span>
                                        @elseif($report->director_status === 'Approved')
                                            <span class="badge bg-success">Setuju</span>
                                        @else
                                            <span class="badge bg-danger">Tolak</span>
                                        @endif
                                    </td>
                                    <td class="text-end px-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if($report->director_status !== 'Pending')
                                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#reportModal{{ $report->id }}">
                                                <i class="bi bi-eye"></i> Lihat
                                            </button>
                                            <form action="{{ route('sarpras.reports.reset-director', $report->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan keputusan pimpinan ini? Status laporan akan kembali menjadi Menunggu.')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan Keputusan">
                                                    <i class="bi bi-x-circle"></i> Batal
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#reportModal{{ $report->id }}">
                                                <i class="bi bi-shield-check me-1"></i> Periksa
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-5 text-muted">Tidak ada laporan yang menunggu approval pimpinan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Pengadaan Barang -->
                <div class="tab-pane fade" id="procurements" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th class="ps-4">Kode / Kegiatan</th>
                                    <th>Unit Pendidikan</th>
                                    <th class="text-center">Total Item</th>
                                    <th>Total Estimasi</th>
                                    <th class="text-center">Status Final</th>
                                    <th class="text-end px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-dark">
                                @forelse($procurements as $req)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-primary">{{ $req->request_code }}</div>
                                        <small class="text-muted">{{ $req->activity_name }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $req->unit->name }}</div>
                                        <small class="text-muted">Oleh: {{ $req->user->name }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark">{{ $req->total_items }} Item</span>
                                    </td>
                                    <td class="fw-bold text-success">
                                        Rp {{ number_format($req->total_batch_price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if($req->director_status === 'Pending')
                                            <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Menunggu</span>
                                        @elseif($req->director_status === 'Approved')
                                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Setuju</span>
                                        @else
                                            <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Tolak</span>
                                        @endif
                                    </td>
                                    <td class="text-end px-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if($req->director_status !== 'Pending')
                                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#procModal{{ $req->id }}">
                                                <i class="bi bi-eye"></i> Lihat
                                            </button>
                                            <form action="{{ route('sarpras.procurements.reset-director', $req->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan keputusan pimpinan ini? Status pengadaan akan kembali menjadi Menunggu untuk dicheck.')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan Keputusan">
                                                    <i class="bi bi-x-circle"></i> Batal
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#procModal{{ $req->id }}">
                                                <i class="bi bi-shield-lock me-1"></i> Periksa
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada pengajuan barang untuk divalidasi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals for Reports -->
@foreach($reports as $report)
<div class="modal fade" id="reportModal{{ $report->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('reports.approve-director', $report->id) }}" method="POST">
            @csrf
            <div class="modal-content text-dark text-start">
                <div class="modal-header bg-success text-white shadow-sm">
                    <h5 class="modal-title FW-BOLD">Approval Laporan Kerusakan #{{ $report->id }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="card shadow-sm border-0 mb-3 overflow-hidden">
                        <div class="card-body p-0">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    @if($report->photo)
                                        <img src="{{ asset('storage/' . $report->photo) }}" class="img-fluid h-100 object-fit-cover" style="min-height: 150px;">
                                    @else
                                        <div class="bg-secondary-subtle d-flex align-items-center justify-content-center h-100" style="min-height: 150px;">
                                            <i class="bi bi-image text-muted fs-1"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-8 p-3 bg-white">
                                    <label class="small text-muted d-block Uppercase fw-bold mb-1" style="font-size: 0.7rem">Informasi Barang</label>
                                    <h5 class="fw-bold text-success mb-1">{{ $report->inventory->name }}</h5>
                                    <p class="small text-muted mb-3">
                                        {{ $report->inventory->room->unit->name }} | {{ $report->inventory->room->room_name }}
                                    </p>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="small text-muted d-block Uppercase fw-bold" style="font-size: 0.7rem">Tipe Kerusakan</label>
                                            <div class="fw-bold">{{ $report->type }}</div>
                                        </div>
                                        <div class="col-6">
                                            <label class="small text-muted d-block Uppercase fw-bold" style="font-size: 0.7rem">Usulan Tindak Lanjut</label>
                                            <span class="badge bg-danger fs-xs">{{ $report->follow_up_action }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-white py-2 fw-bold small border-bottom-0">REKOMENDASI KEPALA SEKOLAH</div>
                        <div class="card-body py-2 bg-success bg-opacity-10 border-start border-success border-4 italic text-success rounded-end">
                            "{{ $report->principal_note ?: 'Disetujui untuk diproses lebih lanjut sesuai usulan.' }}"
                        </div>
                    </div>

                    @if($report->director_status === 'Pending')
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Keputusan Pimpinan Lembaga</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="director_status" id="d_app{{ $report->id }}" value="Approved" checked>
                                        <label class="form-check-label text-success fw-bold" for="d_app{{ $report->id }}">SETUJUI TINDAK LANJUT</label>
                                    </div>
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="director_status" id="d_rej{{ $report->id }}" value="Rejected">
                                        <label class="form-check-label text-danger fw-bold" for="d_rej{{ $report->id }}">TOLAK / TANGGUHKAN</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-bold">Catatan Pimpinan <span class="small text-muted fw-normal">(Opsional)</span></label>
                                <textarea name="director_note" class="form-control" rows="2" placeholder="Instruksi pimpinan terkait laporan ini...">{{ $report->director_note }}</textarea>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-{{ $report->director_status === 'Approved' ? 'success' : 'danger' }} bg-white border-left-{{ $report->director_status === 'Approved' ? 'success' : 'danger' }} shadow-sm">
                        <div class="d-flex align-items-center">
                            <div class="fs-2 me-3"><i class="bi bi-{{ $report->director_status === 'Approved' ? 'check-circle' : 'x-circle' }}"></i></div>
                            <div>
                                <div class="fw-bold text-uppercase fs-5">KEPUTUSAN: {{ $report->director_status === 'Approved' ? 'DISETUJUI' : 'DITOLAK' }}</div>
                                <div class="mt-1">Catatan Pimpinan: <span class="italic text-muted">"{{ $report->director_note ?: '-' }}"</span></div>
                                <small class="text-muted">Status: {{ $report->status }} | Diputuskan: {{ $report->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                    @if($report->director_status === 'Pending')
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Simpan Keputusan Final</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

<!-- Modals for Procurements -->
@foreach($procurements as $req)
<div class="modal fade" id="procModal{{ $req->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form action="{{ route('sarpras.procurements.approve-director', $req->id) }}" method="POST">
            @csrf
            <div class="modal-content text-dark">
                <div class="modal-header bg-primary text-white shadow-sm">
                    <div class="modal-title h5 mb-0">Approval Pengadaan: {{ $req->request_code }}</div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <!-- Summary Card -->
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-md-3 border-end">
                                    <label class="small text-muted d-block uppercase fs-xs">Unit / Pengaju</label>
                                    <div class="fw-bold text-primary">{{ $req->unit->name }}</div>
                                    <div class="small">Oleh: {{ $req->user->name }}</div>
                                </div>
                                <div class="col-md-4 border-end ps-md-4">
                                    <label class="small text-muted d-block uppercase fs-xs">Nama Kegiatan</label>
                                    <div class="fw-bold">{{ $req->activity_name }}</div>
                                    <div class="small text-muted italic">{{ $req->activity_description ?: '-' }}</div>
                                </div>
                                <div class="col-md-5 ps-md-4">
                                    <label class="small text-muted d-block uppercase fs-xs mb-1">Validasi Kepala Sekolah</label>
                                    <div class="p-2 border rounded bg-white small text-success italic">
                                        <i class="bi bi-quote me-1"></i>{{ $req->principal_note ?: 'Disetujui untuk diteruskan.' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="card shadow-sm border-0 mb-3 overflow-hidden">
                        <div class="card-header bg-white py-2">
                             <h6 class="mb-0 fw-bold border-left-primary ps-2">Daftar Barang Tervalidasi</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle mb-0">
                                <thead class="bg-light text-center small fw-bold">
                                    <tr>
                                        <th width="40">No</th>
                                        <th>Nama Barang & Spesifikasi</th>
                                        <th width="110">Est. Harga</th>
                                        <th width="80">Est. Qty</th>
                                        <th width="120">Est. Subtotal</th>
                                        <th width="140">Realisasi Harga</th>
                                        <th width="120">Realisasi Qty</th>
                                        <th width="120">Subtotal Real.</th>
                                        <th width="90">Keputusan</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach($req->batch_items as $index => $bi)
                                    @php 
                                        $isRejected = $bi->director_status === 'Rejected';
                                        $isProcessed = $req->director_status !== 'Pending';
                                    @endphp
                                    <tr id="row_item_{{ $bi->id }}" class="{{ $isRejected ? 'table-light opacity-75' : '' }}">
                                        <td class="text-center bg-light">{{ $index + 1 }}</td>
                                        <td class="ps-3">
                                            <div class="fw-bold">{{ $bi->item_name }}</div>
                                            <div class="small text-muted">{{ $bi->category->name }} | {{ $bi->type }}</div>
                                        </td>
                                        <td class="text-end text-muted">
                                            <small>Rp {{ number_format($bi->estimated_price, 0, ',', '.') }}</small>
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ $bi->quantity }} <small>{{ $bi->unit_name }}</small>
                                        </td>
                                        <td class="text-end text-muted">
                                            <small>Rp {{ number_format($bi->quantity * $bi->estimated_price, 0, ',', '.') }}</small>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text bg-light text-xs">Rp</span>
                                                <input type="text" name="items[{{ $bi->id }}][approved_price]" 
                                                       id="price_{{ $bi->id }}"
                                                       class="form-control text-end fw-bold price-input" 
                                                       value="{{ number_format($bi->approved_price ?: $bi->estimated_price, 0, ',', '.') }}" 
                                                       onkeyup="formatRupiah(this)"
                                                       {{ ($isProcessed || $isRejected) ? 'disabled' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="number" name="items[{{ $bi->id }}][approved_quantity]" 
                                                       id="qty_{{ $bi->id }}"
                                                       class="form-control text-center fw-bold" 
                                                       value="{{ $bi->approved_quantity ?: $bi->quantity }}" 
                                                       min="1"
                                                       {{ ($isProcessed || $isRejected) ? 'disabled' : '' }}>
                                                <span class="input-group-text bg-light text-xs px-1" style="font-size: 0.65rem">{{ $bi->unit_name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold text-primary">
                                            <span id="subtotal_{{ $bi->id }}">
                                                Rp {{ number_format(($bi->approved_quantity ?: $bi->quantity) * ($bi->approved_price ?: $bi->estimated_price), 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="text-center bg-white">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input type="hidden" name="items[{{ $bi->id }}][status]" value="Rejected">
                                                <input class="form-check-input approval-toggle" type="checkbox" name="items[{{ $bi->id }}][status]" 
                                                       value="Approved" id="app_item_{{ $bi->id }}" 
                                                       data-id="{{ $bi->id }}"
                                                       {{ $bi->director_status === 'Approved' || $bi->director_status === 'Pending' ? 'checked' : '' }}
                                                       {{ $isProcessed ? 'disabled' : '' }}
                                                       style="width: 2.2em; height: 1.1em; cursor: pointer;">
                                            </div>
                                            <div class="small mt-1 {{ $isRejected ? 'text-danger' : 'text-success' }} fw-bold" id="lbl_item_{{ $bi->id }}">
                                                {{ $isRejected ? 'Tolak' : 'Setuju' }}
                                            </div>
                                            <script>
                                                document.getElementById('app_item_{{ $bi->id }}').addEventListener('change', function() {
                                                    let id = this.getAttribute('data-id');
                                                    let lbl = document.getElementById('lbl_item_' + id);
                                                    let priceInp = document.getElementById('price_' + id);
                                                    let qtyInp = document.getElementById('qty_' + id);
                                                    let row = document.getElementById('row_item_' + id);
                                                    
                                                    if(this.checked) {
                                                        lbl.textContent = 'Setuju';
                                                        lbl.className = 'small mt-1 fw-bold text-success';
                                                        priceInp.disabled = false;
                                                        qtyInp.disabled = false;
                                                        row.classList.remove('table-light', 'opacity-75');
                                                    } else {
                                                        lbl.textContent = 'Tolak';
                                                        lbl.className = 'small mt-1 fw-bold text-danger';
                                                        priceInp.disabled = true;
                                                        qtyInp.disabled = true;
                                                        row.classList.add('table-light', 'opacity-75');
                                                    }
                                                });
                                            </script>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light fw-bold text-dark">
                                    <tr>
                                        <td colspan="4" class="text-end py-2">TOTAL ESTIMASI ORIGINAL:</td>
                                        <td class="text-end py-2">Rp {{ number_format($req->total_original_price, 0, ',', '.') }}</td>
                                        <td colspan="2" class="text-end py-2 border-start">TOTAL REALISASI:</td>
                                        <td class="text-end text-success fs-6 py-2" id="batch_total_{{ $req->id }}">
                                            Rp {{ number_format($req->total_approved_price, 0, ',', '.') }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Decision Section -->
                    @if($req->director_status === 'Pending')
                    <div class="card shadow-sm border-left-primary bg-white">
                        <div class="card-body py-3">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold text-dark mb-1">Catatan / Instruksi Pimpinan <span class="small text-muted fw-normal">(Opsional)</span></label>
                                    <textarea name="note" class="form-control" rows="2" placeholder="Catatan untuk keseluruhan pengajuan ini...">{{ $req->director_note }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-{{ $req->director_status === 'Approved' ? 'success' : 'danger' }} bg-white border-left-{{ $req->director_status === 'Approved' ? 'success' : 'danger' }} shadow-sm d-flex align-items-center mb-0">
                        <div class="fs-1 me-4"><i class="bi bi-{{ $req->director_status === 'Approved' ? 'check-circle' : 'x-circle' }}"></i></div>
                        <div>
                            <div class="fw-bold fs-5 text-uppercase">KEPUTUSAN: {{ $req->director_status === 'Approved' ? 'DISETUJUI' : 'DITOLAK' }}</div>
                            <div class="mt-1">Catatan Pimpinan: <span class="italic text-muted">"{{ $req->director_note ?: '-' }}"</span></div>
                            <small class="text-muted">Waktu: {{ $req->approved_at ? $req->approved_at->format('d/m/Y H:i') : '-' }}</small>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                    @if($req->director_status === 'Pending')
                        <button type="button" class="btn btn-outline-danger px-4 shadow-sm me-2" onclick="rejectWholeBatch('{{ $req->id }}')">
                            <i class="bi bi-x-circle me-1"></i> Tolak Seluruh Pengajuan
                        </button>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm">Simpan Keputusan Final</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>
function formatRupiah(angka, prefix) {
    var number_string = angka.value.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    angka.value = prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    
    // Auto update subtotal if data-id exists
    let id = angka.getAttribute('id')?.split('_')[1];
    if(id) {
        updateSubtotal(id);
        updateBatchTotal(angka.closest('form'));
    }
}

function updateSubtotal(id) {
    let priceElem = document.getElementById('price_' + id);
    let qtyElem = document.getElementById('qty_' + id);
    let subtotalElem = document.getElementById('subtotal_' + id);
    
    if(!priceElem || !qtyElem || !subtotalElem) return;
    
    let price = parseInt(priceElem.value.replace(/\./g, '')) || 0;
    let qty = parseInt(qtyElem.value) || 0;
    let subtotal = price * qty;
    
    subtotalElem.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
}

function updateBatchTotal(form) {
    let total = 0;
    form.querySelectorAll('tbody tr').forEach(row => {
        let toggle = row.querySelector('.approval-toggle');
        if(toggle && toggle.checked) {
            let id = toggle.getAttribute('data-id');
            let priceVal = document.getElementById('price_' + id).value.replace(/\./g, '');
            let qtyVal = document.getElementById('qty_' + id).value;
            total += (parseInt(priceVal) || 0) * (parseInt(qtyVal) || 0);
        }
    });
    
    // Find the total display in this specific modal
    let totalDisplay = form.querySelector('tfoot [id^="batch_total_"]');
    if(totalDisplay) {
        totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
}

// Attach listeners to Qty inputs
document.querySelectorAll('input[name*="[approved_quantity]"]').forEach(input => {
    input.addEventListener('input', function() {
        let id = this.getAttribute('id')?.split('_')[1];
        if(id) {
            updateSubtotal(id);
            updateBatchTotal(this.closest('form'));
        }
    });
});

document.querySelectorAll('.approval-toggle').forEach(chk => {
    chk.addEventListener('change', function() {
        updateBatchTotal(this.closest('form'));
    });
});

function rejectWholeBatch(reqId) {
    if(confirm('Apakah Anda yakin ingin MENOLAK SELURUH item dalam pengajuan ini?')) {
        let modal = document.getElementById('procModal' + reqId);
        let toggles = modal.querySelectorAll('.approval-toggle');
        toggles.forEach(t => {
            t.checked = false;
            // Manual trigger labels update
            let id = t.getAttribute('data-id');
            let lbl = document.getElementById('lbl_item_' + id);
            let priceInp = document.getElementById('price_' + id);
            let qtyInp = document.getElementById('qty_' + id);
            let row = document.getElementById('row_item_' + id);
            
            if(lbl) {
                lbl.textContent = 'Tolak';
                lbl.className = 'small mt-1 fw-bold text-danger';
            }
            if(priceInp) priceInp.disabled = true;
            if(qtyInp) qtyInp.disabled = true;
            if(row) row.classList.add('table-light', 'opacity-75');
        });
        
        // Update batch total (should be 0)
        let totalDisplay = document.getElementById('batch_total_' + reqId);
        if(totalDisplay) totalDisplay.textContent = 'Rp 0';
        
        modal.querySelector('form').submit();
    }
}
</script>

@endsection
