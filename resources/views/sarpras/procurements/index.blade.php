@extends('layouts.app')

@section('content')
<style>
    .proc-table-input {
        min-width: 150px;
    }
    #procurementItemsBody td {
        padding: 10px;
    }
    .modal-xl {
        max-width: 95% !important;
    }
</style>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Pengajuan Pengadaan Barang</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProcurementModal">
            <i class="bi bi-plus-lg"></i> Buat Pengajuan Baru
        </button>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    @endif

    <!-- Information & Filter -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tahun Pelajaran Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $activeAcademicYear ? $activeAcademicYear->name : 'N/A' }}
                            </div>
                            <div class="text-xs text-muted mt-1">
                                <i class="bi bi-info-circle"></i> Setiap pengajuan baru akan otomatis dicatat pada tahun pelajaran ini.
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-event fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow h-100">
                <div class="card-body">
                    <form method="GET" action="{{ route('sarpras.procurements.index') }}" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Unit Pendidikan</label>
                            <select name="unit_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Tahun Pelajaran</label>
                            <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Tahun</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ ($academic_year_id ?? '') == $ay->id ? 'selected' : '' }}>{{ $ay->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                            <a href="{{ route('sarpras.procurements.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- More Filters -->
    <div class="card shadow mb-4">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('sarpras.procurements.index') }}" class="row g-3">
                <input type="hidden" name="unit_id" value="{{ $unit_id }}">
                <input type="hidden" name="academic_year_id" value="{{ $academic_year_id }}">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 small fw-bold">Tipe:</span>
                        <select name="type" class="form-select border-start-0" onchange="this.form.submit()">
                            <option value="">Semua Tipe</option>
                            <option value="Asset" {{ request('type') == 'Asset' ? 'selected' : '' }}>Aset / Inventaris</option>
                            <option value="Consumable" {{ request('type') == 'Consumable' ? 'selected' : '' }}>Barang Habis Pakai</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 small fw-bold">Status:</span>
                        <select name="status" class="form-select border-start-0" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Menunggu Validasi</option>
                            <option value="Validated" {{ request('status') == 'Validated' ? 'selected' : '' }}>Tervalidasi (Kepsek)</option>
                            <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Disetujui (Pimpinan)</option>
                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>Kode / Kegiatan</th>
                            <th>Unit / Tipe</th>
                            <th class="text-center">Total Barang</th>
                            <th>Total Est. Biaya</th>
                            <th>Status (Kepsek/Pimp)</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-dark">
                        @forelse($procurements as $item)
                        <tr>
                            <td>
                                <div class="small text-muted mb-0">{{ $item->request_code }}</div>
                                <div class="fw-bold text-primary">{{ $item->activity_name }}</div>
                                <div class="small text-muted">{{ $item->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $item->unit->name }}</div>
                                <div class="small badge bg-info">{{ $item->type }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary rounded-pill px-3">{{ $item->total_items }} Item</span>
                            </td>
                            <td>
                                <div class="fw-bold text-success">Rp {{ number_format($item->total_batch_price, 0, ',', '.') }}</div>
                            </td>
                            <td>
                                <!-- Principal Status -->
                                <div class="mb-1">
                                    <small class="text-muted d-block" style="font-size: 0.7rem">KEPSEK:</small>
                                    @if($item->principal_status === 'Pending')
                                        <span class="badge bg-secondary p-1">Menunggu</span>
                                    @elseif($item->principal_status === 'Validated')
                                        <span class="badge bg-success p-1">Valid</span>
                                    @else
                                        <span class="badge bg-danger p-1">Ditolak</span>
                                    @endif
                                </div>
                                <!-- Director Status -->
                                <div class="mb-1">
                                    <small class="text-muted d-block" style="font-size: 0.7rem">PIMPINAN:</small>
                                    @if($item->director_status === 'Pending')
                                        <span class="badge bg-secondary p-1">Menunggu</span>
                                    @elseif($item->director_status === 'Approved')
                                        <span class="badge bg-success p-1">Setuju</span>
                                    @else
                                        <span class="badge bg-danger p-1">Ditolak</span>
                                    @endif
                                </div>
                                <!-- Report Status -->
                                @if($item->director_status === 'Approved')
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem">LAPORAN:</small>
                                    @if($item->report_status === 'Pending')
                                        <span class="badge bg-secondary p-1">Belum Cair</span>
                                    @elseif($item->report_status === 'Recorded')
                                        <span class="badge bg-info text-dark p-1">Siap Lapor</span>
                                    @elseif($item->report_status === 'Reported')
                                        <span class="badge bg-primary p-1">Menunggu Verif</span>
                                    @elseif($item->report_status === 'Approved')
                                        <span class="badge bg-success p-1">Diterima</span>
                                    @else
                                        <span class="badge bg-danger p-1">Ditolak</span>
                                    @endif
                                </div>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    @if($item->director_status === 'Approved' && ($item->report_status === 'Recorded' || $item->report_status === 'Rejected'))
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#reportModal{{ $item->id }}" title="Lapor Realisasi (Upload Nota)">
                                        <i class="bi bi-upload"></i> Lapor
                                    </button>
                                    @endif
                                    
                                    @if($item->director_status !== 'Pending')
                                    <a href="{{ route('sarpras.procurements.print', $item->request_code) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                    @endif

                                    {{-- Edit is locked if already validated --}}
                                    @if($item->status === 'Pending' && (Auth::id() === $item->user_id || Auth::user()->role === 'administrator'))
                                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    @endif

                                    {{-- Delete/Force Delete --}}
                                    @php 
                                        $isAdmin = Auth::user()->role === 'administrator';
                                        $isOwner = Auth::id() === $item->user_id;
                                        $isPending = $item->report_status === 'Pending' && $item->status === 'Pending';
                                    @endphp
                                    @if($isAdmin || ($isPending && $isOwner))
                                    <button type="button" 
                                        class="btn btn-sm {{ $isAdmin && !$isPending ? 'btn-danger shadow-sm' : 'btn-outline-danger' }}" 
                                        onclick="confirmDeleteProcurement('{{ route('sarpras.procurements.destroy', $item->id) }}', {{ $isAdmin && !$isPending ? 'true' : 'false' }})"
                                        title="{{ $isAdmin && !$isPending ? 'Hapus Paksa (Hapus Permanen & Kas)' : 'Hapus Pengajuan' }}">
                                        <i class="bi {{ $isAdmin && !$isPending ? 'bi-trash-fill' : 'bi-trash' }}"></i>
                                        @if($isAdmin && !$isPending) <span class="ms-1 small fw-bold">HAPUS PAKSA</span> @endif
                                    </button>
                                    @endif
                                </div>

                                <!-- Detail Modal -->
                                <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content text-start">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i>Detail Pengajuan: {{ $item->request_code }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body bg-light">
                                                <div class="card shadow-sm mb-3">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6 border-end">
                                                                <label class="small text-muted d-block">Nama Kegiatan / Urgensi:</label>
                                                                <h5 class="fw-bold text-primary">{{ $item->activity_name }}</h5>
                                                                <p class="small text-secondary mb-0">{{ $item->activity_description ?: 'Tidak ada deskripsi kegiatan.' }}</p>
                                                            </div>
                                                            <div class="col-md-6 ps-md-4">
                                                                <div class="row mb-2">
                                                                    <div class="col-5 small text-muted">Unit:</div>
                                                                    <div class="col-7 fw-bold">{{ $item->unit->name }}</div>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-5 small text-muted">Diajukan Oleh:</div>
                                                                    <div class="col-7 fw-bold">{{ $item->user->name }}</div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-5 small text-muted">Tanggal:</div>
                                                                    <div class="col-7 fw-bold">{{ $item->created_at->format('d/m/Y H:i') }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card shadow-sm border-0">
                                                    <div class="card-header bg-white py-2 fw-bold small text-muted uppercase">Daftar Barang ({{ $item->total_items }})</div>
                                                    <div class="card-body p-0">
                                                        <div class="table-responsive">
                                                                    <table class="table table-sm table-hover mb-0 align-middle">
                                                                        <thead class="bg-light small text-center fw-bold">
                                                                            <tr>
                                                                                <th width="40">No</th>
                                                                                <th class="text-start">Item / Spesifikasi</th>
                                                                                <th width="100">Est. Harga</th>
                                                                                <th width="60">Est. Qty</th>
                                                                                <th width="100">Est. Subtotal</th>
                                                                                <th width="110">Real. Harga</th>
                                                                                <th width="80">Real. Qty</th>
                                                                                <th width="110">Subtotal Real.</th>
                                                                                <th width="70">Status</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody class="small">
                                                                            @php $totalEst = 0; $totalReal = 0; @endphp
                                                                            @foreach($item->batch_items as $idx => $bi)
                                                                            @php 
                                                                                $subEst = $bi->quantity * $bi->estimated_price;
                                                                                $totalEst += $subEst;
                                                                                
                                                                                $isRejected = $bi->director_status === 'Rejected';
                                                                                // Showing realization for both Approved and Pending (since we reset to estimates)
                                                                                $priceReal = $bi->approved_price ?: $bi->estimated_price;
                                                                                $qtyReal = $bi->approved_quantity ?: $bi->quantity;
                                                                                $subReal = !$isRejected ? ($priceReal * $qtyReal) : 0;
                                                                                $totalReal += $subReal;
                                                                            @endphp
                                                                            <tr class="{{ $bi->director_status === 'Rejected' ? 'table-light opacity-75' : '' }}">
                                                                                <td class="text-center">{{ $idx + 1 }}</td>
                                                                                <td>
                                                                                    <div class="fw-bold">{{ $bi->item_name }}</div>
                                                                                    <div class="text-muted text-xs italic">{{ $bi->description ?: '-' }}</div>
                                                                                </td>
                                                                                <td class="text-end text-muted small">Rp {{ number_format($bi->estimated_price, 0, ',', '.') }}</td>
                                                                                <td class="text-center text-muted small">{{ $bi->quantity }}</td>
                                                                                <td class="text-end text-muted small">Rp {{ number_format($subEst, 0, ',', '.') }}</td>
                                                                                <td class="text-end fw-bold">
                                                                                    @if(!$isRejected)
                                                                                        Rp {{ number_format($priceReal, 0, ',', '.') }}
                                                                                    @else
                                                                                        -
                                                                                    @endif
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    @if(!$isRejected)
                                                                                        {{ $qtyReal }} <small>{{ $bi->unit_name }}</small>
                                                                                    @else
                                                                                        -
                                                                                    @endif
                                                                                </td>
                                                                                <td class="text-end fw-bold text-primary">
                                                                                    @if(!$isRejected)
                                                                                        Rp {{ number_format($subReal, 0, ',', '.') }}
                                                                                    @else
                                                                                        Rp 0
                                                                                    @endif
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    @if($bi->director_status === 'Pending')
                                                                                        <span class="badge bg-secondary text-xs">Pending</span>
                                                                                    @elseif($bi->director_status === 'Approved')
                                                                                        <span class="badge bg-success text-xs">Setuju</span>
                                                                                    @else
                                                                                        <span class="badge bg-danger text-xs">Tolak</span>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                        <tfoot class="bg-light fw-bold small">
                                                                            <tr>
                                                                                <td colspan="4" class="text-end py-2">TOTAL ESTIMASI ORIGINAL:</td>
                                                                                <td class="text-end py-2">Rp {{ number_format($totalEst, 0, ',', '.') }}</td>
                                                                                <td colspan="2" class="text-end py-2 border-start">TOTAL REALISASI:</td>
                                                                                <td class="text-end text-success fs-6 py-2">
                                                                                    Rp {{ number_format($totalReal, 0, ',', '.') }}
                                                                                </td>
                                                                                <td></td>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-md-6">
                                                        <div class="card h-100 shadow-sm border-0">
                                                            <div class="card-body text-center">
                                                                <small class="text-muted d-block mb-1">Status Kepala Sekolah</small>
                                                                @if($item->principal_status === 'Pending')
                                                                    <span class="badge bg-secondary px-3 py-2">Menunggu Validasi</span>
                                                                @elseif($item->principal_status === 'Validated')
                                                                    <span class="badge bg-success px-3 py-2">Tervalidasi</span>
                                                                    <div class="small mt-2 italic">"{{ $item->principal_note }}"</div>
                                                                @else
                                                                    <span class="badge bg-danger px-3 py-2">Ditolak</span>
                                                                    <div class="small mt-2 italic">"{{ $item->principal_note }}"</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="card h-100 shadow-sm border-0">
                                                            <div class="card-body text-center">
                                                                <small class="text-muted d-block mb-1">Status Pimpinan</small>
                                                                @if($item->director_status === 'Pending')
                                                                    <span class="badge bg-secondary px-3 py-2">Menunggu Persetujuan</span>
                                                                @elseif($item->director_status === 'Approved')
                                                                    <span class="badge bg-success px-3 py-2">Disetujui</span>
                                                                    <div class="small mt-2 italic">"{{ $item->director_note }}"</div>
                                                                @else
                                                                    <span class="badge bg-danger px-3 py-2">Ditolak</span>
                                                                    <div class="small mt-2 italic">"{{ $item->director_note }}"</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Modal -->
                        @if($item->status === 'Pending')
                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <form action="{{ route('sarpras.procurements.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="edit-proc-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content text-start text-dark">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title">Edit Pengajuan: {{ $item->request_code }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body bg-light">
                                            <div class="card shadow-sm mb-4">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label fw-bold text-primary"><i class="bi bi-building me-2"></i>Unit Pendidikan</label>
                                                            <select name="unit_id" class="form-select border-primary" required>
                                                                <option value="">-- Pilih Unit --</option>
                                                                @foreach($units as $u)
                                                                    <option value="{{ $u->id }}" {{ $item->unit_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label fw-bold text-primary"><i class="bi bi-flag-fill me-2"></i>Alasan / Kegiatan</label>
                                                            <input type="text" name="activity_name" class="form-control border-primary" value="{{ $item->activity_name }}" required>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="form-label fw-bold text-primary"><i class="bi bi-justify-left me-2"></i>Deskripsi Kegiatan</label>
                                                            <textarea name="activity_description" class="form-control border-primary" rows="2">{{ $item->activity_description }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card shadow-sm border-0">
                                                <div class="card-header bg-white py-3">
                                                    <h6 class="mb-0 fw-bold"><i class="bi bi-list-ul me-2"></i>Edit Daftar Barang</h6>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered mb-0 align-middle" style="min-width: 1200px">
                                                            <thead class="bg-light small fw-bold text-center">
                                                                <tr>
                                                                    <th width="50">#</th>
                                                                    <th style="min-width: 300px">Nama Barang & Spesifikasi</th>
                                                                    <th width="200">Kategori & Tipe</th>
                                                                    <th width="150">Jumlah & Satuan</th>
                                                                    <th width="180">Est. Harga Satuan</th>
                                                                    <th width="250">Keterangan / Foto</th>
                                                                    <th width="100">Status KS</th>
                                                                    <th width="50"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="edit-proc-body-{{ $item->id }}">
                                                                @foreach($item->batch_items as $index => $bi)
                                                                <tr class="edit-row">
                                                                    <td class="text-center fw-bold bg-light row-number">{{ $index + 1 }}</td>
                                                                    <td>
                                                                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $bi->id }}">
                                                                        <input type="text" name="items[{{ $index }}][name]" class="form-control form-control-sm mb-1 fw-bold" value="{{ $bi->item_name }}" required>
                                                                    </td>
                                                                    <td>
                                                                        <select name="items[{{ $index }}][category_id]" class="form-select form-select-sm mb-1" required>
                                                                            @foreach($categories as $cat)
                                                                                <option value="{{ $cat->id }}" {{ $bi->inventory_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <select name="items[{{ $index }}][type]" class="form-select form-select-sm" required>
                                                                            <option value="Asset" {{ $bi->type === 'Asset' ? 'selected' : '' }}>Aset</option>
                                                                            <option value="Consumable" {{ $bi->type === 'Consumable' ? 'selected' : '' }}>Habis Pakai</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <div class="input-group input-group-sm mb-1">
                                                                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control text-center" value="{{ $bi->quantity }}" min="1" required oninput="updateEditTotal('edit-proc-body-{{ $item->id }}')">
                                                                            <input type="text" name="items[{{ $index }}][unit]" class="form-control text-center" value="{{ $bi->unit_name }}" required>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="input-group input-group-sm">
                                                                            <span class="input-group-text">Rp</span>
                                                                            <input type="text" name="items[{{ $index }}][price]" class="form-control text-end price-input" value="{{ number_format($bi->estimated_price, 0, ',', '.') }}" onkeyup="formatRupiah(this); updateEditTotal('edit-proc-body-{{ $item->id }}')">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="items[{{ $index }}][description]" class="form-control form-control-sm mb-1" value="{{ $bi->description }}" placeholder="Ket tambahan...">
                                                                        <input type="file" name="items[{{ $index }}][photo]" class="form-control form-control-sm">
                                                                        @if($bi->photo)
                                                                            <small class="text-xs text-muted italic">Foto ada.</small>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if($bi->principal_status === 'Validated')
                                                                            <span class="badge bg-success text-xs">Valid</span>
                                                                        @elseif($bi->principal_status === 'Rejected')
                                                                            <span class="badge bg-danger text-xs">Tolak</span>
                                                                        @else
                                                                            <span class="badge bg-secondary text-xs">-</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="this.closest('tr').remove(); reorderEditNumbers('edit-proc-body-{{ $item->id }}'); updateEditTotal('edit-proc-body-{{ $item->id }}')"><i class="bi bi-trash-fill fs-5"></i></button>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="text-center mt-3 mb-2">
                                                <button type="button" class="btn btn-outline-warning px-4" onclick="addEditRow('edit-proc-body-{{ $item->id }}')">
                                                    <i class="bi bi-plus-circle-fill me-2"></i> Tambah Baris Barang
                                                </button>
                                            </div>
                                        </div>
                                        <div class="modal-footer d-flex justify-content-between">
                                            <div class="fw-bold fs-5 text-dark ms-3">
                                                Total Est: Rp <span id="editTotalDisplay-{{ $item->id }}">{{ number_format($item->total_batch_price, 0, ',', '.') }}</span>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning px-4 shadow">Simpan Perubahan</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif

                                 @if($item->director_status === 'Approved')
                                 <!-- Report Modal -->
                                 <div class="modal fade" id="reportModal{{ $item->id }}" tabindex="-1">
                                     <div class="modal-dialog">
                                         <form action="{{ route('sarpras.procurements.report', $item->id) }}" method="POST" enctype="multipart/form-data">
                                             @csrf
                                             <div class="modal-content text-start text-dark">
                                                 <div class="modal-header bg-primary text-white">
                                                     <h5 class="modal-title">Laporan Realisasi Pengadaan</h5>
                                                     <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                 </div>
                                                 <div class="modal-body">
                                                     <div class="alert alert-info small">
                                                         Silakan upload bukti nota/kuitansi dan foto barang yang telah dibeli untuk diverifikasi oleh bagian Keuangan.
                                                     </div>
                                                     <div class="mb-3">
                                                         <label class="form-label fw-bold small">Upload Nota / Kuitansi</label>
                                                         <input type="file" name="report_nota" class="form-control" accept="image/*" required>
                                                     </div>
                                                     <div class="mb-3">
                                                         <label class="form-label fw-bold small">Upload Foto Barang</label>
                                                         <input type="file" name="report_photo" class="form-control" accept="image/*" required>
                                                     </div>
                                                     <div class="mb-0">
                                                         <label class="form-label fw-bold small">Catatan (Opsional)</label>
                                                         <textarea name="report_note" class="form-control" rows="2" placeholder="Contoh: Barang dibeli di Toko Jaya, harga diskon."></textarea>
                                                     </div>
                                                 </div>
                                                 <div class="modal-footer">
                                                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                     <button type="submit" class="btn btn-primary">Kirim Laporan</button>
                                                 </div>
                                             </div>
                                         </form>
                                     </div>
                                 </div>
                                 @endif

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada pengajuan pengadaan barang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-light">
                {{ $procurements->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Procurement Modal -->
<div class="modal fade" id="addProcurementModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form action="{{ route('sarpras.procurements.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content text-start text-dark">
                <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="modal-title">Form Pengajuan Barang Baru</h5>
                    <div class="ms-auto me-3 h6 mb-0">
                        <span class="badge bg-white text-primary">
                            <i class="bi bi-calendar-check me-1"></i> TA: {{ $activeAcademicYear ? $activeAcademicYear->name : 'N/A' }}
                        </span>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-primary"><i class="bi bi-building me-2"></i>Unit Pendidikan</label>
                                    <select name="unit_id" class="form-select border-primary" required>
                                        <option value="">-- Pilih Unit Pendidikan --</option>
                                        @foreach($units as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-primary"><i class="bi bi-flag-fill me-2"></i>Alasan Pengajuan / Nama Kegiatan</label>
                                    <input type="text" name="activity_name" class="form-control border-primary" placeholder="Contoh: Pengadaan Fasilitas Kelas Baru / Kegiatan LDKS" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold text-primary"><i class="bi bi-justify-left me-2"></i>Deskripsi Kegiatan / Urgensi</label>
                                    <textarea name="activity_description" class="form-control border-primary" rows="2" placeholder="Jelaskan detail kegiatan atau mengapa barang-barang ini sangat diperlukan saat ini..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-list-ul me-2"></i>Daftar Barang yang Diajukan</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0 align-middle" style="min-width: 1200px">
                                    <thead class="bg-light small fw-bold text-center">
                                        <tr>
                                            <th width="50">#</th>
                                            <th style="min-width: 300px">Nama Barang & Spesifikasi</th>
                                            <th width="200">Kategori & Tipe</th>
                                            <th width="150">Jumlah & Satuan</th>
                                            <th width="180">Est. Harga Satuan</th>
                                            <th width="250">Keterangan / Foto</th>
                                            <th width="50"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="procurementItemsBody">
                                        <!-- Rows added via JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3 mb-2">
                        <button type="button" class="btn btn-outline-primary px-4" onclick="addProcurementRow()">
                            <i class="bi bi-plus-circle-fill me-2"></i> Tambah Baris Barang
                        </button>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <div class="fw-bold fs-5 text-primary ms-3">
                        Total Est: Rp <span id="addTotalDisplay">0</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                            Kirim (<span id="itemCount">0</span> Item) <i class="bi bi-send-fill ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let procRowCount = 0;
    const procBody = document.getElementById('procurementItemsBody');
    const procCategories = @json($categories);

    function addProcurementRow() {
        const itemIndex = procRowCount;
        const row = document.createElement('tr');
        row.id = `proc-row-${itemIndex}`;
        row.innerHTML = `
            <td class="text-center fw-bold bg-light row-number">${procBody.children.length + 1}</td>
            <td>
                <input type="text" name="items[${itemIndex}][name]" class="form-control form-control-sm mb-1 fw-bold" placeholder="Nama Barang / Spesifikasi" required>
            </td>
            <td>
                <select name="items[${itemIndex}][category_id]" class="form-select form-select-sm mb-1" required>
                    <option value="">-- Kategori --</option>
                    ${procCategories.map(c => `<option value="${c.id}">${c.name}</option>`).join('')}
                </select>
                <select name="items[${itemIndex}][type]" class="form-select form-select-sm" required>
                    <option value="Asset">Aset (Inventaris)</option>
                    <option value="Consumable">Habis Pakai</option>
                </select>
            </td>
            <td>
                <div class="input-group input-group-sm mb-1">
                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control text-center" value="1" min="1" required oninput="updateAddTotal()">
                    <input type="text" name="items[${itemIndex}][unit]" class="form-control text-center" placeholder="Satuan" required>
                </div>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="items[${itemIndex}][price]" class="form-control text-end price-input" placeholder="0" onkeyup="formatRupiah(this); updateAddTotal()">
                </div>
            </td>
            <td>
                <input type="text" name="items[${itemIndex}][description]" class="form-control form-control-sm mb-1" placeholder="Diskripsi singkat">
                <input type="file" name="items[${itemIndex}][photo]" class="form-control form-control-sm">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeProcurementRow(${itemIndex})"><i class="bi bi-trash-fill fs-5"></i></button>
            </td>
        `;
        procBody.appendChild(row);
        procRowCount++;
        updateItemCount();
        updateAddTotal();
    }

    function addEditRow(tbodyId) {
        const tbody = document.getElementById(tbodyId);
        const newIndex = 'new_' + Date.now() + Math.floor(Math.random() * 1000); // Unique index for new items
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="text-center fw-bold bg-light row-number">${tbody.children.length + 1}</td>
            <td>
                <input type="text" name="items[${newIndex}][name]" class="form-control form-control-sm mb-1 fw-bold" placeholder="Nama Barang" required>
            </td>
            <td>
                <select name="items[${newIndex}][category_id]" class="form-select form-select-sm mb-1" required>
                    <option value="">-- Kategori --</option>
                    ${procCategories.map(c => `<option value="${c.id}">${c.name}</option>`).join('')}
                </select>
                <select name="items[${newIndex}][type]" class="form-select form-select-sm" required>
                    <option value="Asset">Aset</option>
                    <option value="Consumable">Habis Pakai</option>
                </select>
            </td>
            <td>
                <div class="input-group input-group-sm mb-1">
                    <input type="number" name="items[${newIndex}][quantity]" class="form-control text-center" value="1" min="1" required oninput="updateEditTotal('${tbodyId}')">
                    <input type="text" name="items[${newIndex}][unit]" class="form-control text-center" placeholder="Satuan" required>
                </div>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="items[${newIndex}][price]" class="form-control text-end price-input" placeholder="0" onkeyup="formatRupiah(this); updateEditTotal('${tbodyId}')">
                </div>
            </td>
            <td>
                <input type="text" name="items[${newIndex}][description]" class="form-control form-control-sm mb-1">
                <input type="file" name="items[${newIndex}][photo]" class="form-control form-control-sm">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="this.closest('tr').remove(); reorderEditNumbers('${tbodyId}'); updateEditTotal('${tbodyId}')"><i class="bi bi-trash-fill fs-5"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    }

    function reorderEditNumbers(tbodyId) {
        const tbody = document.getElementById(tbodyId);
        const numbers = tbody.querySelectorAll('.row-number');
        numbers.forEach((num, i) => {
            num.innerText = i + 1;
        });
    }

    function formatRupiah(element) {
        let value = element.value.replace(/[^0-9]/g, '');
        if (value === "") {
            element.value = "";
            return;
        }
        element.value = new Intl.NumberFormat('id-ID').format(value);
    }

    function updateTotal(tbodyId, displayId) {
        const tbody = document.getElementById(tbodyId);
        const display = document.getElementById(displayId);
        if (!tbody || !display) return;

        let total = 0;
        const rows = tbody.querySelectorAll('tr');
        rows.forEach(row => {
            const qtyInput = row.querySelector('input[name*="[quantity]"]');
            const priceInput = row.querySelector('input[name*="[price]"]');
            
            if (qtyInput && priceInput) {
                const qty = parseInt(qtyInput.value) || 0;
                const price = parseInt(priceInput.value.replace(/\./g, '')) || 0;
                total += qty * price;
            }
        });

        display.innerText = new Intl.NumberFormat('id-ID').format(total);
    }

    function updateAddTotal() {
        updateTotal('procurementItemsBody', 'addTotalDisplay');
    }

    function updateEditTotal(tbodyId) {
        const displayId = tbodyId.replace('edit-proc-body-', 'editTotalDisplay-');
        updateTotal(tbodyId, displayId);
    }

    // Strip dots before form submission (Add Form)
    document.querySelector('#addProcurementModal form').addEventListener('submit', function(e) {
        const priceInputs = this.querySelectorAll('.price-input');
        priceInputs.forEach(input => {
            input.value = input.value.replace(/\./g, '');
        });
    });

    // Strip dots before form submission (Edit Forms)
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('edit-proc-form')) {
            const priceInputs = e.target.querySelectorAll('.price-input');
            priceInputs.forEach(input => {
                input.value = input.value.replace(/\./g, '');
            });
        }
    });

    function removeProcurementRow(index) {
        const row = document.getElementById(`proc-row-${index}`);
        if (row) {
            row.remove();
            reorderNumbers();
            updateItemCount();
            updateAddTotal();
        }
    }

    function reorderNumbers() {
        const numbers = procBody.querySelectorAll('.row-number');
        numbers.forEach((num, i) => {
            num.innerText = i + 1;
        });
    }

    function updateItemCount() {
        const count = procBody.children.length;
        const countEl = document.getElementById('itemCount');
        if (countEl) countEl.innerText = count;
    }

    // Initialize with 1 item
    document.addEventListener('DOMContentLoaded', () => {
        if (procBody && procBody.children.length === 0) {
            addProcurementRow();
        }
    });

    function confirmDeleteProcurement(url, isForce = false) {
        Swal.fire({
            title: isForce ? 'HAPUS PAKSA PENGAJUAN?' : 'Hapus Pengajuan?',
            text: isForce 
                ? "PERINGATAN: Seluruh data pengajuan ini akan dihapus PERMANEN dari sistem, termasuk rekaman pengeluaran kas yang sudah dicatat di bagian Keuangan. Data TIDAK dapat dikembalikan!"
                : "Seluruh item dalam pengajuan ini akan dihapus.",
            icon: isForce ? 'error' : 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: isForce ? 'Yakin, Hapus Permanen!' : 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = url;
                form.method = 'POST';
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        })
    }
</script>
@endpush
@endsection
