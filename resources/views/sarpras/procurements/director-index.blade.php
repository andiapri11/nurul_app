@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Persetujuan Pengadaan Barang</h1>
            <p class="text-muted mb-0">Persetujuan akhir oleh Pimpinan Lembaga atas pengajuan yang telah divalidasi Kepala Sekolah.</p>
        </div>
    </div>

    <!-- Information & Filter -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tahun Pelajaran Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeAcademicYear ? $activeAcademicYear->name : 'N/A' }}</div>
                            <div class="text-xs text-muted mt-1 small">Persetujuan akhir difokuskan pada pengadaan di tahun aktif ini.</div>
                        </div>
                        <div class="col-auto"><i class="bi bi-calendar-check fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow h-100">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('sarpras.director.approvals') }}" class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Unit Pendidikan</label>
                            <select name="unit_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Tahun Pelajaran</label>
                            <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Tahun</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ ($academic_year_id ?? '') == $ay->id ? 'selected' : '' }}>{{ $ay->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('sarpras.director.approvals') }}" class="btn btn-outline-secondary w-100" title="Reset">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-left-success shadow-sm alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white d-flex align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-card-checklist me-2"></i>Daftar Pengajuan Tervalidasi</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Kode Pengajuan</th>
                            <th>Kegiatan</th>
                            <th class="text-center">Total Item</th>
                            <th>Status Pimpinan</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-dark">
                        @forelse($procurements as $req)
                        <tr>
                            <td class="ps-4">
                                <span class="small text-muted">{{ $req->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-primary">{{ $req->request_code }}</div>
                                <div class="small text-muted">{{ $req->unit->name }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $req->activity_name }}</div>
                                <div class="small text-muted text-truncate" style="max-width: 250px;">{{ $req->activity_description }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">{{ $req->total_items }} Item</span>
                                <div class="small text-muted mt-1">Rp {{ number_format($req->total_batch_price, 0, ',', '.') }}</div>
                            </td>
                            <td>
                                @if($req->director_status === 'Pending')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Menunggu</span>
                                @elseif($req->director_status === 'Approved')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Disetujui</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $req->id }}">
                                        <i class="bi bi-eye me-1"></i> {{ $req->director_status === 'Pending' ? 'Periksa' : 'Detail' }}
                                    </button>
                                    @if($req->director_status !== 'Pending')
                                    <form action="{{ route('sarpras.procurements.reset-director', $req->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan keputusan ini? Seluruh item dalam pengajuan ini akan dikembalikan ke status MENUNGGU.')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm" title="Batalkan Keputusan">
                                            <i class="bi bi-arrow-counterclockwise"></i> Batalkan
                                        </button>
                                    </form>
                                    <a href="{{ route('sarpras.procurements.print', $req->request_code) }}" target="_blank" class="btn btn-sm btn-success shadow-sm ms-1">
                                        <i class="bi bi-printer"></i> Cetak
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                Belum ada pengajuan tervalidasi yang menunggu persetujuan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Approval Modals (Placed outside table to fix layout) -->
            @foreach($procurements as $req)
            <div class="modal fade" id="approveModal{{ $req->id }}" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <form action="{{ route('sarpras.procurements.approve-director', $req->id) }}" method="POST">
                        @csrf
                        <div class="modal-content text-start text-dark">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Persetujuan Akhir: {{ $req->request_code }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body bg-light">
                                <!-- Batch Info Card -->
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body py-2">
                                            <div class="row">
                                            <div class="col-md-3 border-end">
                                                <small class="text-muted d-block">Unit & Pemohon</small>
                                                <div class="fw-bold text-primary">{{ $req->unit->name }}</div>
                                                <small>{{ $req->user->name }}</small>
                                            </div>
                                            <div class="col-md-4 border-end">
                                                <small class="text-muted d-block">Nama Kegiatan</small>
                                                <div class="fw-bold">{{ $req->activity_name }}</div>
                                            </div>
                                            <div class="col-md-5">
                                                <small class="text-muted d-block">Catatan Kepala Sekolah</small>
                                                <div class="p-2 border rounded bg-white small italic text-success">
                                                    "{{ $req->principal_note ?: 'Tidak ada catatan khusus.' }}"
                                                </div>
                                            </div>
                                            </div>
                                    </div>
                                </div>

                                <!-- Items to Approve -->
                                <div class="card shadow-sm border-0 mb-3">
                                    <div class="card-header bg-white py-2">
                                            <h6 class="mb-0 fw-bold border-left-primary ps-2">Daftar Barang Tervalidasi</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle mb-0">
                                            <thead class="bg-light text-center small fw-bold">
                                                <tr>
                                                    <th width="40">No</th>
                                                    <th>Nama Barang & Spesifikasi</th>
                                                    <th width="110">Est. Harga</th>
                                                    <th width="80">Est. Qty</th>
                                                    <th width="120">Est. Subtotal</th>
                                                    <th width="140">Real. Harga</th>
                                                    <th width="110">Realisasi Qty</th>
                                                    <th width="120">Real. Subtotal</th>
                                                    <th width="90">Keputusan</th>
                                                </tr>
                                            </thead>
                                            <tbody class="small">
                                                @foreach($req->batch_items as $index => $bi)
                                                @php 
                                                    $isRejected = $bi->director_status === 'Rejected';
                                                    $isProcessed = $req->director_status !== 'Pending';
                                                @endphp
                                                <tr id="idx_row_{{ $bi->id }}_{{ $req->id }}" class="{{ $isRejected ? 'table-light opacity-75' : '' }}">
                                                    <td class="text-center bg-light">{{ $index + 1 }}</td>
                                                    <td>
                                                        <div class="fw-bold">{{ $bi->item_name }}</div>
                                                        <div class="small text-muted">{{ $bi->category->name }} | {{ $bi->type }}</div>
                                                    </td>
                                                    <td class="text-end text-muted small">
                                                        Rp {{ number_format($bi->estimated_price, 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-center text-muted small">
                                                        {{ $bi->quantity }} <small>{{ $bi->unit_name }}</small>
                                                    </td>
                                                    <td class="text-end text-muted small">
                                                        Rp {{ number_format($bi->quantity * $bi->estimated_price, 0, ',', '.') }}
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text bg-light">Rp</span>
                                                            <input type="text" name="items[{{ $bi->id }}][approved_price]" 
                                                                    id="idx_price_{{ $bi->id }}_{{ $req->id }}"
                                                                    class="form-control text-end fw-bold price-input" 
                                                                    value="{{ number_format($bi->approved_price ?: $bi->estimated_price, 0, ',', '.') }}" 
                                                                    onkeyup="formatRupiah(this)"
                                                                    {{ ($isProcessed || $isRejected) ? 'disabled' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" name="items[{{ $bi->id }}][approved_quantity]" 
                                                                    id="idx_qty_{{ $bi->id }}_{{ $req->id }}"
                                                                    class="form-control text-center fw-bold" 
                                                                    value="{{ $bi->approved_quantity ?: $bi->quantity }}" 
                                                                    {{ ($isProcessed || $isRejected) ? 'disabled' : '' }}
                                                                    min="1">
                                                            <span class="input-group-text bg-light text-xs px-1" style="font-size: 0.6rem">{{ $bi->unit_name }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-end fw-bold text-primary">
                                                        <span id="idx_subtotal_{{ $bi->id }}_{{ $req->id }}">
                                                            Rp {{ number_format(($bi->approved_quantity ?: $bi->quantity) * ($bi->approved_price ?: $bi->estimated_price), 0, ',', '.') }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center bg-white">
                                                        <div class="form-check form-switch d-flex justify-content-center">
                                                            <input type="hidden" name="items[{{ $bi->id }}][status]" value="Rejected">
                                                            <input class="form-check-input" type="checkbox" name="items[{{ $bi->id }}][status]" 
                                                                    value="Approved" id="idx_app_{{ $bi->id }}_{{ $req->id }}" 
                                                                    data-id="{{ $bi->id }}"
                                                                    data-req="{{ $req->id }}"
                                                                    {{ $bi->director_status === 'Approved' || $bi->director_status === 'Pending' ? 'checked' : '' }}
                                                                    {{ $isProcessed ? 'disabled' : '' }}
                                                                    style="width: 2.2em; height: 1.1em; cursor: pointer;">
                                                        </div>
                                                        <div class="small mt-1 {{ $isRejected ? 'text-danger' : 'text-success' }} fw-bold" id="idx_lbl_{{ $bi->id }}_{{ $req->id }}">
                                                            {{ $isRejected ? 'Tolak' : 'Setuju' }}
                                                        </div>
                                                        <script>
                                                            document.getElementById('idx_app_{{ $bi->id }}_{{ $req->id }}').addEventListener('change', function() {
                                                                let id = this.getAttribute('data-id');
                                                                let reqId = this.getAttribute('data-req');
                                                                let lbl = document.getElementById('idx_lbl_' + id + '_' + reqId);
                                                                let priceInp = document.getElementById('idx_price_' + id + '_' + reqId);
                                                                let qtyInp = document.getElementById('idx_qty_' + id + '_' + reqId);
                                                                let row = document.getElementById('idx_row_' + id + '_' + reqId);
                                                                
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
                                                    <td class="text-end text-success fs-6 py-2" id="idx_batch_total_{{ $req->id }}">
                                                        Rp {{ number_format($req->total_approved_price, 0, ',', '.') }}
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                @if($req->director_status === 'Pending')
                                <div class="card shadow-sm border-left-primary bg-white">
                                    <div class="card-body py-3">
                                        <div class="mb-0">
                                            <label class="form-label fw-bold">Catatan Pimpinan (Opsional)</label>
                                            <textarea name="note" class="form-control" rows="2" placeholder="Berikan instruksi atau alasan umum...">{{ $req->director_note }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <hr>
                                <div class="p-3 bg-{{ $req->director_status === 'Approved' ? 'success' : 'danger' }}-subtle border border-{{ $req->director_status === 'Approved' ? 'success' : 'danger' }} rounded">
                                    <div class="fw-bold fs-5">KEPUTUSAN: {{ $req->director_status === 'Approved' ? 'DISETUJUI' : 'DITOLAK' }}</div>
                                    <div class="mt-1">Catatan Pimpinan: {{ $req->director_note ?: '-' }}</div>
                                    <div class="small mt-1 text-muted">Diputuskan pada: {{ $req->approved_at ? $req->approved_at->format('d/m/Y H:i') : '-' }}</div>
                                </div>
                                @endif
                            </div>
                            <div class="modal-footer justify-content-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                @if($req->director_status === 'Pending')
                                <button type="submit" class="btn btn-primary px-4 shadow">Simpan Keputusan Final</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
            <div class="card-footer bg-white border-top-0 px-3 py-3">
                <div class="d-flex justify-content-center">
                    {{ $procurements->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
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
    
    let id = angka.getAttribute('id')?.split('_')[2]; // idx_price_ID format
    if(id) {
        updateIndexSubtotal(id);
        updateIndexBatchTotal(angka.closest('form'));
    }
}

function updateIndexSubtotal(id) {
    let priceElem = document.getElementById('idx_price_' + id);
    let qtyElem = document.getElementById('idx_qty_' + id);
    let subtotalElem = document.getElementById('idx_subtotal_' + id);
    
    if(!priceElem || !qtyElem || !subtotalElem) return;
    
    let price = parseInt(priceElem.value.replace(/\./g, '')) || 0;
    let qty = parseInt(qtyElem.value) || 0;
    let subtotal = price * qty;
    
    subtotalElem.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
}

function updateIndexBatchTotal(form) {
    let total = 0;
    form.querySelectorAll('tbody tr').forEach(row => {
        let toggle = row.querySelector('.form-check-input');
        if(toggle && toggle.checked) {
            let id = toggle.getAttribute('data-id');
            let priceVal = document.getElementById('idx_price_' + id).value.replace(/\./g, '');
            let qtyVal = document.getElementById('idx_qty_' + id).value;
            total += (parseInt(priceVal) || 0) * (parseInt(qtyVal) || 0);
        }
    });
    
    let totalDisplay = form.querySelector('tfoot [id^="idx_batch_total_"]');
    if(totalDisplay) {
        totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
}

document.querySelectorAll('input[name*="[approved_quantity]"]').forEach(input => {
    input.addEventListener('input', function() {
        let id = this.getAttribute('id')?.split('_')[2];
        if(id) {
            updateIndexSubtotal(id);
            updateIndexBatchTotal(this.closest('form'));
        }
    });
});

document.querySelectorAll('.form-check-input').forEach(chk => {
    if(chk.getAttribute('data-id')) {
        chk.addEventListener('change', function() {
            updateIndexBatchTotal(this.closest('form'));
        });
    }
});
</script>
@endsection

