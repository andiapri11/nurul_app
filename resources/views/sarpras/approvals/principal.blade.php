@use('Illuminate\Support\Str')
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Validasi Kepala Sekolah</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <!-- Filter Unit -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('sarpras.principal.approvals') }}" class="row g-3 align-items-end">
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
                    <a href="{{ route('sarpras.principal.approvals') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabs Container -->
    <div class="card shadow">
        <div class="card-header p-0">
            <ul class="nav nav-tabs border-bottom-0" id="approvalTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active py-3 px-4 fw-bold" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">
                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Laporan Kerusakan
                        <span class="badge bg-danger ms-2">{{ $reports->where('principal_approval_status', 'Pending')->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3 px-4 fw-bold" id="procurements-tab" data-bs-toggle="tab" data-bs-target="#procurements" type="button" role="tab">
                        <i class="bi bi-cart-plus-fill text-primary me-2"></i> Pengadaan Barang
                        <span class="badge bg-primary ms-2">{{ $procurements->where('principal_status', 'Pending')->count() }}</span>
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="approvalTabsContent">
                <!-- Tab Laporan Kerusakan -->
                <div class="tab-pane fade show active" id="reports" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th>Item / Kode</th>
                                    <th>Jenis Laporan</th>
                                    <th>Usulan Tindak Lanjut</th>
                                    <th class="text-center">Status KS</th>
                                    <th class="text-end px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $report->inventory->name }}</div>
                                        <small class="text-muted">{{ $report->inventory->code }}</small>
                                    </td>
                                    <td>
                                        @if($report->type === 'Damaged')
                                            <span class="badge bg-warning text-dark">RUSAK</span>
                                        @else
                                            <span class="badge bg-danger">HILANG</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small fw-bold">{{ $report->follow_up_action }}</div>
                                        <div class="small text-muted">{{ Str::limit($report->follow_up_description, 50) }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if($report->principal_approval_status === 'Pending')
                                            <span class="badge bg-secondary">Menunggu</span>
                                        @elseif($report->principal_approval_status === 'Approved')
                                            <span class="badge bg-success">Valid</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-end px-4">
                                        @if($report->director_status === 'Approved')
                                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#reportModal{{ $report->id }}">
                                                <i class="bi bi-check-all"></i> Terverifikasi Pimpinan
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#reportModal{{ $report->id }}">
                                                Periksa
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-5 text-muted">Tidak ada laporan kerusakan untuk divalidasi.</td></tr>
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
                                    <th>Tanggal</th>
                                    <th>Kode Pengajuan</th>
                                    <th>Kegiatan</th>
                                    <th>Total Item</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($procurements as $req)
                                <tr>
                                    <td class="small">{{ $req->created_at->format('d/m/y') }}</td>
                                    <td>
                                        <div class="fw-bold text-primary">{{ $req->request_code }}</div>
                                        <div class="small text-muted">{{ $req->unit->name }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $req->activity_name }}</div>
                                        <div class="small text-muted text-truncate" style="max-width: 200px;">{{ $req->activity_description }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $req->total_items }} Item</span>
                                        <div class="small text-muted mt-1">Rp {{ number_format($req->total_batch_price, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if($req->principal_status === 'Pending')
                                            <span class="badge bg-secondary">Menunggu</span>
                                        @elseif($req->principal_status === 'Validated')
                                            <span class="badge bg-success">Valid</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-end px-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if($req->director_status === 'Approved')
                                                <button type="button" class="btn btn-sm btn-outline-success border-0 fw-bold" disabled>
                                                    <i class="bi bi-lock-fill me-1"></i> Disetujui Pimpinan
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#procModal{{ $req->id }}">
                                                    <i class="bi bi-eye me-1"></i> Lihat
                                                </button>
                                            @else
                                                @if($req->principal_status !== 'Pending')
                                                <form action="{{ route('sarpras.procurements.cancel-principal', $req->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan validasi ini? Status akan kembali menjadi Menunggu.')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan Validasi">
                                                        <i class="bi bi-x-circle"></i> Batal
                                                    </button>
                                                </form>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#procModal{{ $req->id }}">
                                                    <i class="bi bi-file-check me-1"></i> {{ $req->principal_status === 'Pending' ? 'Periksa' : 'Edit' }}
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
        <form action="{{ route('sarpras.reports.approve-principal', $report->id) }}" method="POST">
            @csrf
            <div class="modal-content text-dark">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Validasi Laporan: {{ $report->inventory->name }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6 border-end">
                            <table class="table table-sm table-borderless small">
                                <tr><td>Kode</td><td class="fw-bold">: {{ $report->inventory->code }}</td></tr>
                                <tr><td>Unit</td><td>: {{ $report->inventory->room->unit->name }}</td></tr>
                                <tr><td>Pelapor</td><td>: {{ $report->user->name }}</td></tr>
                                <tr><td>Masalah</td><td class="text-danger fw-bold">: {{ $report->description }}</td></tr>
                            </table>
                            <div class="mt-2 p-2 bg-light border rounded small">
                                <div class="fw-bold text-primary">Usulan Tindak Lanjut:</div>
                                <div>{{ $report->follow_up_action }}: {{ $report->follow_up_description }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($report->photo)
                                <img src="{{ asset('storage/' . $report->photo) }}" class="img-fluid rounded border">
                            @else
                                <div class="h-100 d-flex align-items-center justify-content-center bg-light border rounded text-muted small">Tanpa Foto</div>
                            @endif
                        </div>
                    </div>
                    
                    @if($report->principal_approval_status === 'Pending')
                    <hr>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Keputusan Validasi</label>
                        <select name="principal_approval_status" class="form-select" required>
                            <option value="Approved">Validasi & Teruskan ke Pimpinan</option>
                            <option value="Rejected">Tolak Laporan / Perlu Revisi</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Catatan KS</label>
                        <textarea name="principal_note" class="form-control" rows="2"></textarea>
                    </div>
                    @else
                    <div class="alert alert-{{ $report->principal_approval_status === 'Approved' ? 'success' : 'danger' }} small">
                        <strong>KEPUTUSAN: {{ $report->principal_approval_status }}</strong><br>
                        Catatan: {{ $report->principal_note ?: '-' }}
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    @if($report->principal_approval_status === 'Pending' && $report->director_status !== 'Approved')
                        <button type="submit" class="btn btn-danger">Simpan Keputusan</button>
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
        <form action="{{ route('sarpras.procurements.validate-principal', $req->id) }}" method="POST">
            @csrf
            <div class="modal-content text-dark">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Validasi Pengajuan: {{ $req->request_code }} ({{ $req->total_items }} Item)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <!-- Activity Details -->
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
                                    <small class="text-muted d-block">Deskripsi / Urgensi</small>
                                    <div class="small fst-italic">{{ $req->activity_description ?: '-' }}</div>
                                </div>
                             </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-2">
                             <h6 class="mb-0 fw-bold"><i class="bi bi-checklist me-2"></i>Daftar Barang & Keputusan</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="bg-light text-center small fw-bold">
                                    <tr>
                                        <th width="40">No</th>
                                        <th>Nama Barang & Spesifikasi</th>
                                        <th>Kategori & Tipe</th>
                                        <th>Jml</th>
                                        <th>Est. Harga</th>
                                        <th>Foto / Ket</th>
                                        <th width="250" class="bg-primary text-white">Keputusan Validasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($req->batch_items as $index => $item)
                                    <tr>
                                        <td class="text-center bg-light">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-bold">{{ $item->item_name }}</div>
                                        </td>
                                        <td>
                                            <div class="small">{{ $item->category->name ?? '-' }}</div>
                                            <span class="badge bg-light text-dark border">{{ $item->type }}</span>
                                        </td>
                                        <td class="text-center">
                                            {{ $item->quantity }} {{ $item->unit_name }}
                                        </td>
                                        <td class="text-end">
                                            Rp {{ number_format($item->estimated_price, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            @if($item->photo)
                                                <a href="{{ asset('storage/' . $item->photo) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $item->photo) }}" class="rounded" style="height: 40px;">
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                            @if($item->description)
                                                <div class="small text-muted fst-italic mt-1" style="font-size: 0.75rem;">{{ Str::limit($item->description, 30) }}</div>
                                            @endif
                                        </td>
                                        <td class="bg-white">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input type="hidden" name="items[{{ $item->id }}]" value="Rejected">
                                                <input class="form-check-input" type="checkbox" name="items[{{ $item->id }}]" value="Validated" id="val_{{ $item->id }}" {{ $item->principal_status === 'Validated' || $item->principal_status === 'Pending' ? 'checked' : '' }} style="width: 3em; height: 1.5em; cursor: pointer;">
                                            </div>
                                            <div class="text-center mt-1">
                                                <small class="text-muted fw-bold" id="label_{{ $item->id }}">
                                                    {{ $item->principal_status === 'Validated' || $item->principal_status === 'Pending' ? 'Setuju' : 'Tolak' }}
                                                </small>
                                            </div>
                                            <script>
                                                document.getElementById('val_{{ $item->id }}').addEventListener('change', function() {
                                                    document.getElementById('label_{{ $item->id }}').textContent = this.checked ? 'Setuju' : 'Tolak';
                                                    document.getElementById('label_{{ $item->id }}').className = this.checked ? 'text-success fw-bold' : 'text-danger fw-bold';
                                                });
                                            </script>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label class="form-label fw-bold">Catatan Tambahan (Opsional)</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Tulis catatan untuk keseluruhan pengajuan ini...">{{ $req->principal_note }}</textarea>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                     <div class="fw-bold">
                        Total Est: Rp {{ number_format($req->total_batch_price, 0, ',', '.') }}
                     </div>
                     <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        @if($req->director_status !== 'Approved')
                            <button type="submit" class="btn btn-primary px-4 shadow">Simpan Validasi</button>
                        @endif
                     </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection
