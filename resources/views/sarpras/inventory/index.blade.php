@extends('layouts.app')

@section('content')
<style>
    .modal-vw-95 {
        max-width: 95vw !important;
        margin-left: auto;
        margin-right: auto;
    }
    #inventoryTable {
        min-width: 1300px;
    }
    .stats-card {
        background: #ffffff;
        border: 1px solid #eaedf3;
        border-radius: 12px;
        padding: 1.25rem;
        position: relative;
        transition: all 0.25s ease-in-out;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .stats-card:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        border-color: #d1d9e6;
        transform: translateY(-3px);
    }
    .stats-card .stats-label {
        color: #8a94a6;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
    }
    .stats-card .stats-value {
        color: #1e293b;
        font-size: 1.35rem;
        font-weight: 800;
        line-height: 1.2;
    }
    .stats-card .stats-sub {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 2px;
    }
    .stats-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .stats-icon-box i {
        font-size: 1.15rem;
    }
    /* Colors */
    .bg-soft-primary { background: #eef2ff; color: #4f46e5; }
    .bg-soft-success { background: #ecfdf5; color: #10b981; }
    .bg-soft-warning { background: #fffbeb; color: #f59e0b; }
    .bg-soft-danger { background: #fef2f2; color: #ef4444; }

    .progress-slim {
        height: 5px;
        border-radius: 10px;
        background: #f1f5f9;
        margin-top: 12px;
        overflow: hidden;
    }
    
    .nav-tabs-custom {
        border-bottom: 1px solid #e2e8f0;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        padding: 10px 16px;
        color: #64748b;
        font-weight: 600;
        font-size: 0.875rem;
        position: relative;
        background: transparent;
        transition: color 0.2s ease;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
    }
    .nav-tabs-custom .nav-link:hover {
        color: #1e293b;
    }
    .nav-tabs-custom .nav-link.active {
        color: #3b82f6;
        background: transparent;
        border-bottom: 2px solid #3b82f6;
    }

    .card-search-filter {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }
    .form-filter-select {
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        font-size: 0.85rem;
        padding: 0.45rem 0.75rem;
        border-radius: 8px;
        font-weight: 500;
    }
    .form-filter-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Grid View Style */
    .inventory-grid-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        transition: all 0.2s ease;
        height: 100%;
        overflow: hidden;
    }
    .inventory-grid-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.08);
        border-color: #cbd5e1;
    }
    .grid-img-container {
        position: relative;
        padding-top: 75%;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .grid-img-container img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .inventory-grid-card:hover .grid-img-container img {
        transform: scale(1.1);
    }
    .grid-badge-condition {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 700;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .grid-content {
        padding: 0.75rem;
    }
    .grid-item-name {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.85rem;
        line-height: 1.3;
        margin-bottom: 4px;
        height: 2.3rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .grid-item-code {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.7rem;
        color: #3b82f6;
        font-weight: 600;
    }
    .grid-footer {
        padding: 0.5rem 0.75rem;
        background: #fafafa;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .btn-view-toggle {
        padding: 0.35rem 0.6rem;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        font-size: 0.8rem;
        transition: all 0.2s;
    }
    .btn-view-toggle.active {
        background: #3b82f6;
        color: #fff;
        border-color: #3b82f6;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
    }
    .pulse-warning {
        animation: pulse-yellow 2s infinite;
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
        color: #000 !important;
    }
    @keyframes pulse-yellow {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
        70% { transform: scale(1.02); box-shadow: 0 0 0 6px rgba(255, 193, 7, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }
</style>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800 fw-bold">Manajemen Inventaris</h1>
            <p class="text-muted small mb-0">Kelola dan pantau seluruh aset sekolah secara terpusat.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('sarpras.inventory.disposed') }}" class="btn btn-outline-danger btn-sm border-0 shadow-sm">
                <i class="bi bi-trash-fill"></i> Arsip Penghapusan
            </a>
            <div class="vr mx-1"></div>
            <a href="{{ route('sarpras.inventory.print', request()->query()) }}" target="_blank" class="btn btn-white btn-sm shadow-sm">
                <i class="bi bi-printer"></i> Cetak Data
            </a>
            <button type="button" class="btn btn-white btn-sm shadow-sm" id="btnPrintBarcodes">
                <i class="bi bi-qr-code"></i> Barcode
            </button>
            @php
                $userRole = strtolower(Auth::user()->role);
                $isManagement = in_array($userRole, ['administrator', 'direktur', 'wakil kepala sekolah', 'kepala sekolah', 'staff']);
                $isActiveYear = (empty(request('academic_year_id')) || ($activeYear && request('academic_year_id') == $activeYear->id));
                $canEdit = $isManagement || $isActiveYear;
            @endphp

            <button type="button" class="btn btn-warning btn-sm shadow-sm px-3" id="btnBulkEdit" disabled title="Pilih barang terlebih dahulu">
                <i class="bi bi-pencil-square"></i> Edit Masal
            </button>
            <button type="button" class="btn btn-success btn-sm shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#importExcelModal" {{ $canEdit ? '' : 'disabled' }}>
                <i class="bi bi-file-earmark-excel"></i> Import
            </button>
            <button type="button" class="btn btn-primary btn-sm shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#addInventoryModal" {{ $canEdit ? '' : 'disabled' }}>
                <i class="bi bi-plus-lg"></i> Tambah Barang
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <div class="stats-label text-primary">Total Aset</div>
                        <div class="stats-value text-dark">{{ number_format($stats['total']) }}</div>
                    </div>
                    <div class="stats-icon-box bg-soft-primary">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
                <div class="stats-sub">Seluruh barang di unit saat ini.</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <div class="stats-label text-success">Estimasi Nilai</div>
                        <div class="stats-value text-dark">Rp {{ number_format($totalValue, 0, ',', '.') }}</div>
                    </div>
                    <div class="stats-icon-box bg-soft-success">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
                <div class="stats-sub">Total pengeluaran aset.</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stats-label text-warning">Kondisi Aman</div>
                        <div class="stats-value text-dark">{{ number_format($stats['good']) }} <small class="text-muted fw-normal" style="font-size: 0.8rem">Item</small></div>
                    </div>
                    <div class="stats-icon-box bg-soft-warning">
                        <i class="bi bi-shield-check"></i>
                    </div>
                </div>
                <div class="progress-slim">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $stats['total'] > 0 ? ($stats['good'] / $stats['total'] * 100) : 0 }}%"></div>
                </div>
                <div class="stats-sub mt-2">Status inventaris kondisi baik.</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <div class="stats-label text-danger">Perhatian</div>
                        <div class="stats-value text-dark">{{ number_format($needsAttention) }}</div>
                    </div>
                    <div class="stats-icon-box bg-soft-danger">
                        <i class="bi bi-exclamation-octagon"></i>
                    </div>
                </div>
                <div class="stats-sub">Aset rusak atau perlu perbaikan.</div>
            </div>
        </div>
    </div>

    <!-- Segment Tabs -->
    <div class="mb-4">
        <ul class="nav nav-tabs nav-tabs-custom mb-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ empty(request('condition')) ? 'active' : '' }}" href="{{ route('sarpras.inventory.index', request()->except('condition')) }}">
                    Semua Barang
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('condition') == 'urgent' ? 'active' : '' }}" 
                   href="{{ route('sarpras.inventory.index', array_merge(request()->query(), ['condition' => 'urgent'])) }}">
                    Perlu Perbaikan
                </a>
            </li>
        </ul>

        <div class="card card-search-filter border-0 shadow-sm">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('sarpras.inventory.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-secondary mb-1">Tahun Pelajaran</label>
                        <select name="academic_year_id" class="form-select form-filter-select" onchange="this.form.submit()">
                            <option value="">Semua Tahun</option>
                            @foreach($academicYears as $year)
                               <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                   {{ $year->name }} {{ $year->status == 'active' ? '(Aktif)' : '' }}
                               </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-secondary mb-1">Unit</label>
                        <select name="unit_id" class="form-select form-filter-select" onchange="this.form.submit()">
                            @if($units->count() > 1 && (Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur'))
                                <option value="">Semua Unit</option>
                            @endif
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label small fw-bold text-secondary mb-1">Kategori</label>
                        <select name="category_id" class="form-select form-filter-select" onchange="this.form.submit()">
                            <option value="">Semua</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-secondary mb-1">Lokasi / Ruang</label>
                        <select name="room_id" class="form-select form-filter-select" onchange="this.form.submit()">
                            <option value="">Semua Ruangan</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-secondary mb-1">Cari Nama / Kode</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-filter-select" value="{{ request('search') }}" placeholder="Cari aset...">
                            <button class="btn btn-primary btn-sm px-2" type="submit" style="border-radius: 0 8px 8px 0;"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                    <div class="col-md-3">
                         <div class="row g-2 align-items-end">
                             <div class="col-5">
                                 <label class="form-label small fw-bold text-secondary mb-1">Items</label>
                                 <select name="per_page" class="form-select form-filter-select" onchange="this.form.submit()">
                                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                </select>
                             </div>
                             <div class="col-7">
                                <label class="form-label d-block small fw-bold text-secondary mb-1">Tampilan</label>
                                <div class="btn-group shadow-sm w-100" role="group">
                                    <a href="{{ route('sarpras.inventory.index', array_merge(request()->query(), ['view' => 'table'])) }}" 
                                       class="btn btn-view-toggle {{ $view == 'table' ? 'active' : '' }}" title="Table View">
                                        <i class="bi bi-list-task"></i>
                                    </a>
                                    <a href="{{ route('sarpras.inventory.index', array_merge(request()->query(), ['view' => 'grid'])) }}" 
                                       class="btn btn-view-toggle {{ $view == 'grid' ? 'active' : '' }}" title="Grid View">
                                        <i class="bi bi-grid-3x3-gap-fill"></i>
                                    </a>
                                </div>
                                <input type="hidden" name="view" value="{{ $view }}">
                             </div>
                         </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Terjadi Kesalahan:</div>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($view == 'table')
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="bg-light text-secondary">
                        <tr style="font-size: 0.85rem;">
                            <th width="30" class="text-center px-3">
                                <input type="checkbox" id="checkAll" class="form-check-input">
                            </th>
                            <th width="50" class="text-center">No</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Kondisi</th>
                            <th class="text-end">Harga</th>
                            <th>Kode Item</th>
                            <th width="100" class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventories as $item)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input item-checkbox" value="{{ $item->id }}">
                            </td>
                            <td class="text-center text-muted small">{{ ($inventories->currentPage() - 1) * $inventories->perPage() + $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($item->photo)
                                        <div class="flex-shrink-0">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#viewPhotoModal{{ $item->id }}">
                                                <img src="{{ asset('storage/' . $item->photo) }}" 
                                                     class="rounded shadow-sm border" 
                                                     style="width: 45px; height: 45px; object-fit: cover;"
                                                     alt="{{ $item->name }}">
                                            </a>
                                        </div>
                                    @else
                                        <div class="flex-shrink-0">
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center border" 
                                                 style="width: 45px; height: 45px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $item->name }}</div>
                                        <div class="small text-muted">Beli: {{ $item->purchase_date ? $item->purchase_date->format('d/m/Y') : '-' }}</div>
                                    </div>
                                </div>

                                <!-- Photo Preview Modal -->
                                @if($item->photo)
                                <div class="modal fade" id="viewPhotoModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content bg-transparent border-0">
                                            <div class="modal-body p-0 text-center">
                                                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                                                <img src="{{ asset('storage/' . $item->photo) }}" class="img-fluid rounded shadow-lg">
                                                <div class="mt-2 text-white fw-bold">{{ $item->name }} ({{ $item->code }})</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                            <td>{{ $item->category->name }}</td>
                             <td>
                                 <div class="fw-bold">{{ $item->room->name ?? '-' }}</div>
                                 <div class="small text-muted text-uppercase">{{ $item->room->unit->name ?? 'N/A' }}</div>
                                 @if($item->person_in_charge)
                                     <div class="mt-1 small"><i class="bi bi-person-badge"></i> PJ: <span class="fw-bold text-dark">{{ $item->person_in_charge }}</span></div>
                                 @endif
                             </td>
                            <td>
                                @php
                                    $conditions = [
                                        'Good' => ['label' => 'Baik', 'color' => 'success'],
                                        'Repairing' => ['label' => 'Perbaikan', 'color' => 'info'],
                                        'Damaged' => ['label' => 'Rusak Ringan', 'color' => 'warning'],
                                        'Broken' => ['label' => 'Rusak Berat', 'color' => 'danger'],
                                    ];
                                    $cond = $conditions[$item->condition] ?? ['label' => $item->condition, 'color' => 'secondary'];
                                @endphp
                                <span class="badge bg-{{ $cond['color'] }}">{{ $cond['label'] }}</span>
                            </td>
                            <td class="text-end">
                                <div class="fw-bold text-dark">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                @if($item->is_grant)
                                    <span class="badge bg-warning text-dark small"><i class="bi bi-gift"></i> Bantuan</span>
                                @endif
                                @if($item->source)
                                    <div class="small text-muted">Ket: {{ $item->source }}</div>
                                @endif
                            </td>
                            <td><code class="text-primary fw-bold">{{ $item->code }}</code></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="showHistory('{{ $item->id }}')" title="Histori">
                                        <i class="bi bi-clock-history"></i>
                                    </button>
                                    @if(Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur' || (empty(request('academic_year_id')) || ($activeYear && request('academic_year_id') == $activeYear->id)))
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editInventory{{ $item->id }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reportItem{{ $item->id }}" title="Laporkan Kerusakan / Ajukan Penghapusan">
                                        <i class="bi bi-megaphone-fill"></i>
                                    </button>
                                    @if(Auth::user()->role === 'administrator')
                                    <button type="button" class="btn btn-sm btn-outline-dark" onclick="confirmDelete('{{ route('sarpras.inventory.destroy', $item->id) }}')" title="Hapus Manual (Admin Only)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Report/Disposal Proposal Modal -->
                        <div class="modal fade" id="reportItem{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form onsubmit="submitDamageReport(event, '{{ $item->code }}')">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title font-weight-bold"><i class="bi bi-megaphone me-2"></i>Buat Laporan / Usulan</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start mt-2">
                                            <div class="p-2 mb-3 bg-light border rounded">
                                                <strong>{{ $item->name }}</strong><br>
                                                <code class="small text-primary">{{ $item->code }}</code>
                                            </div>

                                            <input type="hidden" name="code" value="{{ $item->code }}">
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Jenis Kejadian</label>
                                                <select name="type" class="form-select" required>
                                                    <option value="Damaged">Kerusakan</option>
                                                    <option value="Lost">Kehilangan</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Detail Kerusakan / Masalah</label>
                                                <textarea name="description" class="form-control" rows="2" placeholder="Jelaskan kondisi barang saat ini..." required></textarea>
                                            </div>

                                            <hr>
                                            <div class="p-2 mb-3 border-start border-4 border-info bg-light">
                                                <label class="form-label fw-bold text-info"><i class="bi bi-lightbulb me-1"></i>Saran Tindak Lanjut (Sarpras)</label>
                                                <select name="follow_up_action" class="form-select mb-2" required>
                                                    <option value="Repair">Perbaikan</option>
                                                    <option value="Replacement">Pengajuan Pembelian Baru</option>
                                                    <option value="Disposal">Pemusnahan (Hapus dari Daftar)</option>
                                                </select>
                                                <textarea name="follow_up_description" class="form-control" rows="2" placeholder="Alasan saran ini diberikan..." required></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Skala Prioritas</label>
                                                <select name="priority" class="form-select" required>
                                                    <option value="Low">Rendah</option>
                                                    <option value="Medium" selected>Sedang</option>
                                                    <option value="High">Tinggi</option>
                                                    <option value="Urgent">Mendesak</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Foto Bukti (Opsional)</label>
                                                <input type="file" name="photo" class="form-control" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger fw-bold">Kirim Laporan & Usulan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editInventory{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <form action="{{ route('sarpras.inventory.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf @method('PUT')
                                    <div class="modal-content text-dark">
                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Barang: <span class="text-primary">{{ $item->code }}</span></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4 text-start">
                                            <div class="row g-3 mb-4">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Kode Barang</label>
                                                    <input type="text" name="code" class="form-control bg-light fw-bold text-primary" value="{{ $item->code }}" required>
                                                    <small class="text-muted">Pastikan kode barang unik.</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Nama Barang</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                                                </div>
                                            </div>

                                            <div class="row g-3 mb-4">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Lokasi / Ruangan</label>
                                                    <select name="room_id" class="form-select" required>
                                                        @foreach($modalRooms as $room)
                                                            @if($room->unit_id == ($item->room->unit_id ?? null))
                                                                <option value="{{ $room->id }}" {{ $item->room_id == $room->id ? 'selected' : '' }}>
                                                                    {{ $room->name }} ({{ $room->unit->name }})
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Kategori</label>
                                                    <select name="inventory_category_id" class="form-select" required>
                                                        @foreach($modalCategories as $cat)
                                                            @if($cat->unit_id == ($item->room->unit_id ?? null) || is_null($cat->unit_id))
                                                                <option value="{{ $cat->id }}" {{ $item->inventory_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row g-3 mb-4">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Kondisi</label>
                                                    <select name="condition" class="form-select" required>
                                                        <option value="Good" {{ $item->condition == 'Good' ? 'selected' : '' }}>Baik</option>
                                                        <option value="Repairing" {{ $item->condition == 'Repairing' ? 'selected' : '' }}>Perbaikan</option>
                                                        <option value="Damaged" {{ $item->condition == 'Damaged' ? 'selected' : '' }}>Rusak Ringan</option>
                                                        <option value="Broken" {{ $item->condition == 'Broken' ? 'selected' : '' }}>Rusak Berat</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Penanggung Jawab</label>
                                                    <input type="text" name="person_in_charge" class="form-control" value="{{ $item->person_in_charge }}" placeholder="Nama Guru/Staff...">
                                                </div>
                                            </div>

                                            <div class="row g-3 mb-4">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Harga Beli (Rp)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light text-muted small">Rp</span>
                                                        <input type="text" name="price" class="form-control currency-input" value="{{ number_format($item->price, 0, ',', '.') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Tanggal Beli</label>
                                                    <input type="date" name="purchase_date" class="form-control" value="{{ $item->purchase_date ? $item->purchase_date->format('Y-m-d') : '' }}">
                                                </div>
                                            </div>

                                            <div class="row g-3 mb-4">
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold">Sumber / Keterangan</label>
                                                    <input type="text" name="source" class="form-control" value="{{ $item->source }}" placeholder="Contoh: BOS, Yayasan, Bantuan Pemerintah">
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" name="is_grant" value="1" id="is_grant_edit{{ $item->id }}" {{ $item->is_grant ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bold text-muted small" for="is_grant_edit{{ $item->id }}">
                                                            Barang ini merupakan Bantuan / Hibah
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="my-4 opacity-50">

                                            <div class="mb-0">
                                                <label class="form-label fw-bold"><i class="bi bi-camera me-1"></i>Foto Barang</label>
                                                <div class="row align-items-center">
                                                    @if($item->photo)
                                                        <div class="col-auto">
                                                            <img src="{{ asset('storage/' . $item->photo) }}" class="rounded shadow-sm border p-1" style="height: 120px; width: 120px; object-fit: cover;">
                                                            <div class="small text-muted mt-1 text-center">Foto saat ini</div>
                                                        </div>
                                                    @endif
                                                    <div class="col">
                                                        <div class="upload-box border rounded-3 p-3 bg-light">
                                                            <input type="file" name="photo" class="form-control" accept="image/*">
                                                            <div class="mt-2 small text-muted">
                                                                <i class="bi bi-info-circle-fill text-info me-1"></i>Biarkan kosong jika tidak ingin mengubah foto. 
                                                                <br>Sistem akan otomatis memotong foto menjadi persegi (300x300px).
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @if($item->photo)
                        <!-- Photo Modal -->
                        <div class="modal fade" id="viewPhotoModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-dark text-white border-0 py-2">
                                        <h6 class="modal-title fs-6">{{ $item->name }} ({{ $item->code }})</h6>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-0 bg-dark text-center">
                                        <img src="{{ asset('storage/' . $item->photo) }}" class="img-fluid" alt="{{ $item->name }}" style="max-height: 85vh;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">Barang tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <!-- Grid View -->
    <div class="row g-3 mb-4">
        @forelse($inventories as $item)
        @php
            $conditions = [
                'Good' => ['label' => 'Baik', 'color' => 'success'],
                'Repairing' => ['label' => 'Perbaikan', 'color' => 'info'],
                'Damaged' => ['label' => 'Rusak', 'color' => 'warning'],
                'Broken' => ['label' => 'Rusak Berat', 'color' => 'danger'],
            ];
            $cond = $conditions[$item->condition] ?? ['label' => $item->condition, 'color' => 'secondary'];
        @endphp
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
            <div class="inventory-grid-card h-100 d-flex flex-column">
                <div class="grid-img-container">
                    <span class="grid-badge-condition bg-{{ $cond['color'] }}">{{ $cond['label'] }}</span>
                    @if($item->photo)
                        <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" loading="lazy">
                    @else
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted" style="background: #f8fafc;">
                            <i class="bi bi-image display-6 mb-2 opacity-50"></i>
                            <span class="small fw-bold">NO PHOTO</span>
                        </div>
                    @endif
                </div>
                <div class="grid-content flex-grow-1">
                    <div class="grid-item-code mb-1">{{ $item->code }}</div>
                    <div class="grid-item-name" title="{{ $item->name }}">{{ $item->name }}</div>
                    
                    <div class="d-flex align-items-center justify-content-between mt-3 px-1">
                        <div>
                            <div class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Lokasi</div>
                            <div class="fw-bold text-dark small">{{ $item->room->name ?? '-' }}</div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Harga</div>
                            <div class="fw-bold text-primary small">Rp{{ number_format($item->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                <div class="grid-footer">
                    <div class="form-check m-0">
                        <input type="checkbox" class="form-check-input item-checkbox" value="{{ $item->id }}">
                    </div>
                    <div class="btn-group shadow-sm border" style="border-radius: 6px; overflow: hidden;">
                        <button type="button" class="btn btn-xs btn-white text-info px-2 border-end" onclick="showHistory('{{ $item->id }}')" title="Histori">
                            <i class="bi bi-clock-history"></i>
                        </button>
                        @if(Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur' || (empty(request('academic_year_id')) || ($activeYear && request('academic_year_id') == $activeYear->id)))
                        <button type="button" class="btn btn-xs btn-white text-primary px-2 border-end" data-bs-toggle="modal" data-bs-target="#editInventory{{ $item->id }}" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        @endif
                        <button type="button" class="btn btn-xs btn-white text-danger px-2" data-bs-toggle="modal" data-bs-target="#reportItem{{ $item->id }}" title="Laporan">
                            <i class="bi bi-megaphone"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 py-5 text-center">
            <div class="bg-light rounded-4 py-5 border">
                <i class="bi bi-box-seam display-1 text-muted opacity-25 mb-3"></i>
                <h5 class="text-muted">Oops! Tidak ada barang ditemukan.</h5>
                <p class="text-muted small">Coba ubah filter atau pencarian Anda.</p>
            </div>
        </div>
        @endforelse
    </div>
    @endif

    <div class="mb-5 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div class="small text-muted fw-500">
            Menampilkan <span class="text-dark fw-bold">{{ $inventories->firstItem() ?? 0 }}</span> - <span class="text-dark fw-bold">{{ $inventories->lastItem() ?? 0 }}</span> dari <span class="text-dark fw-bold">{{ $inventories->total() }}</span> aset
        </div>
        <div>
            {{ $inventories->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Add Multiple Inventory Modal -->
<div class="modal fade" id="addInventoryModal" tabindex="-1">
    <div class="modal-dialog modal-vw-95">
        <form action="{{ route('sarpras.inventory.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content text-start text-dark">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-box-seam"></i> Tambah Inventaris (Input Banyak)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row mb-4 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary mb-2 d-block"><i class="bi bi-building me-1"></i>Pilih Unit Sekolah</label>
                            <select id="modal_unit_filter" class="form-select border-primary shadow-sm fw-bold py-2">
                                <option value="">-- PILIH UNIT SEKOLAH TERLEBIH DAHULU --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text small text-muted mt-2">
                                <i class="bi bi-info-circle me-1 text-info"></i>Daftar <strong>Kategori</strong> dan <strong>Lokasi/Ruangan</strong> akan otomatis menyaring berdasarkan unit yang dipilih.
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="inventoryTable">
                            <thead class="bg-light">
                                <tr>
                                    <th width="220">Nama Barang</th>
                                    <th width="200">Lokasi / Ruangan</th>
                                    <th width="150">Kategori</th>
                                    <th width="130">Kondisi</th>
                                    <th width="140">Harga (Rp)</th>
                                    <th width="200">Sumber / Bantuan</th>
                                    <th width="180">Penanggung Jawab</th>
                                    <th width="140">Tanggal Beli</th>
                                    <th width="140">Foto</th>
                                    <th width="180">Kode Item</th>
                                    <th width="50" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="inventoryBody">
                                <tr class="inventory-row">
                                    <td>
                                        <input type="text" name="items[0][name]" class="form-control form-control-sm" placeholder="Nama..." required>
                                    </td>
                                    <td>
                                        <select name="items[0][room_id]" class="form-select form-select-sm room-select">
                                            <option value="" data-unit="GUDANG" data-pj="">Pilih Lokasi...</option>
                                            @foreach($modalRooms as $room)
                                                <option value="{{ $room->id }}" data-unit-id="{{ $room->unit_id }}" data-unit="{{ $room->unit->name }}" data-pj="{{ $room->person_in_charge }}">
                                                    {{ $room->name }} ({{ $room->unit->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="items[0][inventory_category_id]" class="form-select form-select-sm category-select" required>
                                            <option value="">Pilih...</option>
                                            @foreach($modalCategories as $cat)
                                                <option value="{{ $cat->id }}" data-unit-id="{{ $cat->unit_id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="items[0][condition]" class="form-select form-select-sm">
                                            <option value="Good">Baik</option>
                                            <option value="Damaged">Rusak Ringan</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" name="items[0][price]" class="form-control currency-input" placeholder="0">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="items[0][source]" class="form-control form-control-sm mb-1" placeholder="Sumber...">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="items[0][is_grant]" value="1" id="is_grant_0">
                                            <label class="form-check-label small" for="is_grant_0">Bantuan</label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="items[0][person_in_charge]" class="form-control form-control-sm" placeholder="Nama PJ...">
                                    </td>
                                    <td>
                                        <input type="date" name="items[0][purchase_date]" class="form-control form-control-sm date-input" value="{{ date('Y-m-d') }}">
                                    </td>
                                    <td>
                                        <input type="file" name="items[0][photo]" class="form-control form-control-sm" accept="image/*">
                                    </td>
                                    <td>
                                        <input type="text" name="items[0][code]" class="form-control form-control-sm inventory-code" value="GUDANG/IVN-{{ $nextCode }}" placeholder="Kode..." required>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-row" disabled><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-sm btn-outline-success" id="addRow">
                            <i class="bi bi-plus-circle"></i> Tambah Baris
                        </button>
                        <p class="small text-muted mt-2 mb-0"><em>* Semua field bertanda bintang wajib diisi. Kode barang harus unik.</em></p>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow">Simpan Semua Barang</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Format Rupiah function
    function formatInputRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

    // Unit Filter Logic for Add Modal
    const modalUnitFilter = document.getElementById('modal_unit_filter');
    const categorySelects = document.querySelectorAll('.category-select');
    const roomSelects = document.querySelectorAll('.room-select');

    if (modalUnitFilter) {
        const triggerFilter = () => {
            const unitId = modalUnitFilter.value;
            const rows = document.querySelectorAll('.inventory-row');
            
            rows.forEach(row => {
                const catSelect = row.querySelector('.category-select');
                const roomSelect = row.querySelector('.room-select');
                
                if (catSelect) {
                    Array.from(catSelect.options).forEach(opt => {
                        if (opt.value === "") {
                            opt.style.display = 'block';
                        } else {
                            const optUnitId = opt.getAttribute('data-unit-id');
                            opt.style.display = (optUnitId == unitId || !optUnitId) ? 'block' : 'none';
                        }
                    });
                }

                if (roomSelect) {
                    Array.from(roomSelect.options).forEach(opt => {
                        if (opt.value === "") {
                            opt.style.display = 'block';
                        } else {
                            const optUnitId = opt.getAttribute('data-unit-id');
                            opt.style.display = (optUnitId == unitId) ? 'block' : 'none';
                        }
                    });
                }
            });
        };

        modalUnitFilter.addEventListener('change', function() {
            triggerFilter();
            // Additionally reset values on change
            const rows = document.querySelectorAll('.inventory-row');
            rows.forEach(row => {
                const catSelect = row.querySelector('.category-select');
                const roomSelect = row.querySelector('.room-select');
                if (catSelect) catSelect.value = "";
                if (roomSelect) roomSelect.value = "";
                const pjInput = row.querySelector('[name*="[person_in_charge]"]');
                if (pjInput) pjInput.value = "";
            });
        });

        // Trigger on load if unit is pre-selected
        if (modalUnitFilter.value) {
            triggerFilter();
        }
    }

    // Attach listener for price inputs
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('currency-input')) {
            e.target.value = formatInputRupiah(e.target.value);
        }
    });

    let rowCount = 1;
    let baseNextNum = parseInt("{{ $nextCode }}") || 1;
    
    function updateRowCode(row, index) {
        const roomSelect = row.querySelector('.room-select');
        const codeInput = row.querySelector('.inventory-code');
        
        if (!roomSelect || !codeInput) return;
        
        let unit = roomSelect.options[roomSelect.selectedIndex]?.dataset.unit || 'GUDANG';
        // Remove all spaces for unit
        unit = unit.replace(/\s+/g, '').toUpperCase();
        
        const itemNumber = String(baseNextNum + index).padStart(5, '0');
        const newCode = `${unit}/IVN-${itemNumber}`;
        codeInput.value = newCode;
    }

    // Listener for dynamic updates
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('room-select') || e.target.classList.contains('date-input')) {
            const row = e.target.closest('tr');
            if (row) {
                // Determine index
                let index = 0;
                const rows = Array.from(document.querySelectorAll('.inventory-row'));
                index = rows.indexOf(row);
                updateRowCode(row, index);

                // Auto-fill Penanggung Jawab from Room
                if (e.target.classList.contains('room-select')) {
                    const pj = e.target.options[e.target.selectedIndex]?.dataset.pj || '';
                    const pjInput = row.querySelector('input[name*="[person_in_charge]"]');
                    if (pjInput) pjInput.value = pj;
                }
            }
        }
    });

    const inventoryBody = document.getElementById('inventoryBody');
    const addRowBtn = document.getElementById('addRow');

    if (addRowBtn && inventoryBody) {
        addRowBtn.addEventListener('click', function() {
            const firstRow = document.querySelector('.inventory-row');
            if (!firstRow) return;

            const newRow = firstRow.cloneNode(true);
            
            // Update input names and values
            newRow.querySelectorAll('[name]').forEach(input => {
                const name = input.getAttribute('name');
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${rowCount}]`));
                
                if (input.classList.contains('inventory-code')) {
                    // Will be updated by updateRowCode
                } else if (input.tagName === 'SELECT') {
                    if (input.name.includes('condition')) {
                        input.value = firstRow.querySelector('[name*="condition"]').value || 'Good';
                    } else if (input.name.includes('inventory_category_id')) {
                         input.value = firstRow.querySelector('[name*="inventory_category_id"]').value;
                    } else if (input.name.includes('room_id')) {
                         input.value = firstRow.querySelector('[name*="room_id"]').value;
                    }
                } else if (input.name.includes('[name]')) {
                    input.value = firstRow.querySelector('[name*="[name]"]').value;
                } else if (input.name.includes('[price]')) {
                    input.value = firstRow.querySelector('[name*="[price]"]').value;
                } else if (input.name.includes('[source]')) {
                    input.value = firstRow.querySelector('[name*="[source]"]').value;
                } else if (input.name.includes('[person_in_charge]')) {
                    input.value = firstRow.querySelector('[name*="[person_in_charge]"]').value;
                } else if (input.name.includes('[is_grant]')) {
                    input.checked = firstRow.querySelector('[name*="[is_grant]"]').checked;
                    // Update ID and For to keep checkbox working
                    const newId = `is_grant_${rowCount}`;
                    input.id = newId;
                    const label = input.nextElementSibling;
                    if (label && label.tagName === 'LABEL') label.setAttribute('for', newId);
                } else if (input.name.includes('[purchase_date]')) {
                     input.value = firstRow.querySelector('[name*="[purchase_date]"]').value;
                } else if (input.type === 'file') {
                    input.value = ''; 
                } else {
                    input.value = ''; 
                }
            });

            // Re-apply unit filter to new row
            if (modalUnitFilter && modalUnitFilter.value) {
                const unitId = modalUnitFilter.value;
                const catSelect = newRow.querySelector('.category-select');
                const roomSelect = newRow.querySelector('.room-select');
                
                if (catSelect) {
                    Array.from(catSelect.options).forEach(opt => {
                        if (opt.value === "") {
                            opt.style.display = 'block';
                        } else {
                            const optUnitId = opt.getAttribute('data-unit-id');
                            opt.style.display = (optUnitId == unitId || !optUnitId) ? 'block' : 'none';
                        }
                    });
                }
                
                if (roomSelect) {
                    Array.from(roomSelect.options).forEach(opt => {
                        if (opt.value === "") {
                            opt.style.display = 'block';
                        } else {
                            const optUnitId = opt.getAttribute('data-unit-id');
                            opt.style.display = (optUnitId == unitId) ? 'block' : 'none';
                        }
                    });
                }
            }

            // Enable delete button for new rows
            const removeBtn = newRow.querySelector('.remove-row');
            if (removeBtn) {
                removeBtn.disabled = false;
                removeBtn.addEventListener('click', function() {
                    newRow.remove();
                });
            }

            inventoryBody.appendChild(newRow);
            rowCount++;
        });
    }

    // Handle delete for existing dynamically added rows (just in case)
    document.querySelectorAll('.remove-row').forEach(btn => {
        if (!btn.disabled) {
            btn.addEventListener('click', function() {
                const row = btn.closest('tr');
                if (row) row.remove();
            });
        }
    });

    // Barcode Selection Logic
    const checkAll = document.getElementById('checkAll');
    const btnPrintBarcodes = document.getElementById('btnPrintBarcodes');

    if (checkAll) {
        checkAll.addEventListener('change', function() {
            document.querySelectorAll('.item-checkbox').forEach(cb => {
                cb.checked = checkAll.checked;
            });
            toggleBulkEditBtn();
        });
    }

    if (btnPrintBarcodes) {
        btnPrintBarcodes.addEventListener('click', function() {
            const selected = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb.value);
            if (selected.length === 0) {
                Swal.fire('Info', 'Pilih minimal satu barang untuk mencetak barcode.', 'info');
                return;
            }
            
            const url = "{{ route('sarpras.inventory.print-barcodes') }}?ids=" + selected.join(',');
            window.open(url, '_blank');
        });
    }

    // Bulk Edit Selection Logic
    const btnBulkEdit = document.getElementById('btnBulkEdit');
    const bulkEditModalEl = document.getElementById('bulkEditModal');
    const bulkEditModal = bulkEditModalEl ? new bootstrap.Modal(bulkEditModalEl) : null;
    const bulkEditBody = document.getElementById('bulkEditBody');

    function toggleBulkEditBtn() {
        if (!btnBulkEdit) return;
        const selectedCount = document.querySelectorAll('.item-checkbox:checked').length;
        if (selectedCount > 0) {
            btnBulkEdit.disabled = false;
            btnBulkEdit.classList.add('pulse-warning'); // Subtle hint
        } else {
            btnBulkEdit.disabled = true;
            btnBulkEdit.classList.remove('pulse-warning');
        }
    }

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-checkbox') || e.target.id === 'checkAll') {
            toggleBulkEditBtn();
        }
    });

    if (btnBulkEdit) {
        btnBulkEdit.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb.value);
            if (selectedIds.length === 0) return;

            Swal.fire({
                title: 'Memuat Data...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`{{ route('sarpras.inventory.get-multiple') }}?ids=${selectedIds.join(',')}`)
                .then(res => res.json())
                .then(response => {
                    Swal.close();
                    if (response.success) {
                        populateBulkEditModal(response.data);
                        if (bulkEditModal) bulkEditModal.show();
                    } else {
                        Swal.fire('Error', 'Gagal memuat data inventaris.', 'error');
                    }
                })
                .catch(err => {
                    Swal.close();
                    console.error(err);
                    Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
                });
        });
    }

    function populateBulkEditModal(items) {
        bulkEditBody.innerHTML = '';
        const catSelectSrc = document.querySelector('select[name="items[0][inventory_category_id]"]');
        const roomSelectSrc = document.querySelector('select[name="items[0][room_id]"]');
        
        const categoriesHtml = catSelectSrc ? catSelectSrc.innerHTML : '';
        const roomsHtml = roomSelectSrc ? roomSelectSrc.innerHTML : '';

        items.forEach((item, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <input type="hidden" name="items[${index}][id]" value="${item.id}">
                <td>
                    <input type="text" name="items[${index}][name]" class="form-control form-control-sm" value="${item.name}" required>
                </td>
                <td>
                    <select name="items[${index}][room_id]" class="form-select form-select-sm bulk-room-select" required>
                        ${roomsHtml}
                    </select>
                </td>
                <td>
                    <select name="items[${index}][inventory_category_id]" class="form-select form-select-sm bulk-category-select" required>
                        ${categoriesHtml}
                    </select>
                </td>
                <td>
                    <select name="items[${index}][condition]" class="form-select form-select-sm" required>
                        <option value="Good" ${item.condition === 'Good' ? 'selected' : ''}>Baik</option>
                        <option value="Repairing" ${item.condition === 'Repairing' ? 'selected' : ''}>Perbaikan</option>
                        <option value="Damaged" ${item.condition === 'Damaged' ? 'selected' : ''}>Rusak Ringan</option>
                        <option value="Broken" ${item.condition === 'Broken' ? 'selected' : ''}>Rusak Berat</option>
                    </select>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="text" name="items[${index}][price]" class="form-control currency-input" value="${formatInputRupiah(String(item.price))}">
                    </div>
                </td>
                <td>
                    <input type="text" name="items[${index}][source]" class="form-control form-control-sm mb-1" value="${item.source || ''}" placeholder="Sumber...">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="items[${index}][is_grant]" value="1" id="bulk_is_grant_${index}" ${item.is_grant ? 'checked' : ''}>
                        <label class="form-check-label small" for="bulk_is_grant_${index}">Bantuan</label>
                    </div>
                </td>
                <td>
                    <input type="text" name="items[${index}][person_in_charge]" class="form-control form-control-sm" value="${item.person_in_charge || ''}" placeholder="Nama PJ...">
                </td>
                <td>
                    <input type="date" name="items[${index}][purchase_date]" class="form-control form-control-sm" value="${item.purchase_date ? item.purchase_date.split('T')[0] : ''}">
                </td>
                <td>
                    <input type="file" name="items[${index}][photo]" class="form-control form-control-sm" accept="image/*">
                    ${item.photo ? `<div class="mt-1 small text-success"><i class="bi bi-check-circle"></i> Ada Foto</div>` : ''}
                </td>
                <td class="small fw-bold text-primary">
                    <input type="text" name="items[${index}][code]" class="form-control form-control-sm font-monospace" value="${item.code}" required>
                </td>
            `;
            
            bulkEditBody.appendChild(row);

            // Filter room/category specific to this item's unit
            const catSelect = row.querySelector('.bulk-category-select');
            const roomSelect = row.querySelector('.bulk-room-select');
            const itemUnitId = item.room ? item.room.unit_id : null;

            if (catSelect) {
                Array.from(catSelect.options).forEach(opt => {
                    if (opt.value === "") {
                        opt.style.display = 'block';
                    } else {
                        const optUnitId = opt.getAttribute('data-unit-id');
                        opt.style.display = (optUnitId == itemUnitId || optUnitId === "" || optUnitId === "null" || !optUnitId) ? 'block' : 'none';
                    }
                });
                catSelect.value = item.inventory_category_id;
            }

            if (roomSelect) {
                Array.from(roomSelect.options).forEach(opt => {
                    if (opt.value === "") {
                        opt.style.display = 'block';
                    } else {
                        const optUnitId = opt.getAttribute('data-unit-id');
                        opt.style.display = (optUnitId == itemUnitId) ? 'block' : 'none';
                    }
                });
                roomSelect.value = item.room_id;
            }
        });
    }

    function confirmDelete(url) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data barang ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = url;
                form.method = 'POST';
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        })
    }

    function showHistory(id) {
        const modal = new bootstrap.Modal(document.getElementById('historyModal'));
        const body = document.getElementById('historyContent');
        body.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2">Memuat histori...</p></div>';
        modal.show();

        fetch(`/sarpras/inventory/${id}/history`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) throw new Error(data.message);
                
                document.getElementById('historyTitle').innerText = `Histori: ${data.inventory.name}`;
                
                let photoHtml = data.inventory.photo ? 
                    `<img src="/storage/${data.inventory.photo}" class="rounded shadow-sm border mb-3" style="width: 100%; max-height: 200px; object-fit: cover;">` : 
                    `<div class="bg-white rounded border mb-3 p-4 text-center text-muted"><i class="bi bi-image display-4"></i><br>Tidak ada foto</div>`;

                let html = `
                <div class="item-info-card p-3 bg-white rounded shadow-sm border mb-4">
                    ${photoHtml}
                    <div class="row small g-2">
                        <div class="col-6"><strong>Kode:</strong><br>${data.inventory.code}</div>
                        <div class="col-6"><strong>Kondisi:</strong><br>${data.inventory.condition}</div>
                        <div class="col-12 mt-2"><strong>Penanggung Jawab:</strong><br>${data.inventory.person_in_charge ?? '-'}</div>
                    </div>
                </div>
                <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-1"></i> Linimasa Aktivitas</h6>
                <div class="timeline-v2">`;
                
                if (data.history.length === 0) {
                    html += '<div class="alert alert-light text-center">Belum ada catatan histori untuk barang ini.</div>';
                } else {
                    data.history.forEach(item => {
                        const dateStr = new Date(item.date).toLocaleString('id-ID', {
                            day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
                        });
                        const isReport = item.type === 'report';
                        const badgeClass = isReport ? 'text-bg-danger' : 'text-bg-primary';
                        
                        html += `
                        <div class="timeline-item mb-3 pb-3 border-bottom position-relative ps-3">
                            <div class="timeline-dot position-absolute start-0 top-0 mt-1 rounded-circle ${isReport ? 'bg-danger' : 'bg-primary'}" style="width: 8px; height: 8px;"></div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="badge ${badgeClass} border-0">${item.action}</span>
                                <small class="text-muted" style="font-size: 10px;">${dateStr}</small>
                            </div>
                            <div class="text-dark small fw-medium">${item.details}</div>
                            <div class="mt-1 text-muted" style="font-size: 10px;"><i class="bi bi-person"></i> Oleh: ${item.user}</div>
                        </div>`;
                    });
                }
                html += '</div>';
                body.innerHTML = html;
            })
            .catch(err => {
                body.innerHTML = `<div class="alert alert-danger">Gagal memuat histori: ${err.message}</div>`;
            });
    }
    function submitDamageReport(event, code) {
        event.preventDefault();
        const form = event.target;
        
        Swal.fire({
            title: 'Kirim Laporan?',
            text: "Laporan akan diteruskan ke Kepala Sekolah untuk validasi.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading();
                const formData = new FormData(form);

                fetch(`{{ route('sarpras.inventory.report-damage-by-code') }}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil', data.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
                });
            }
        });
    }

    // Dynamic Filter for Import Modal Guide
    document.addEventListener('DOMContentLoaded', function() {
        const unitSelect = document.getElementById('import_unit_id');
        const importModal = document.getElementById('importExcelModal');

        function filterGuide() {
            if (!unitSelect) return;
            const selectedUnitId = unitSelect.value;
            
            // Perform selection inside function to ensure we get current elements
            const categoryItems = document.querySelectorAll('.category-guide-item');
            const roomItems = document.querySelectorAll('.room-guide-item');

            categoryItems.forEach(item => {
                const unitId = item.getAttribute('data-unit-id');
                // Show if unit matches OR if it has no unit (shared categories)
                if (unitId == selectedUnitId || unitId === "" || unitId === "null" || !unitId) {
                    item.style.setProperty('display', 'flex', 'important');
                } else {
                    item.style.setProperty('display', 'none', 'important');
                }
            });

            roomItems.forEach(item => {
                const unitId = item.getAttribute('data-unit-id');
                if (unitId == selectedUnitId) {
                    item.style.setProperty('display', 'flex', 'important');
                } else {
                    item.style.setProperty('display', 'none', 'important');
                }
            });
        }

        if (unitSelect) {
            unitSelect.addEventListener('change', filterGuide);
        }

        if (importModal) {
            importModal.addEventListener('shown.bs.modal', filterGuide);
        }
        
        filterGuide();
    });
</script>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content overflow-hidden">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fs-6" id="historyTitle">Histori Barang</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light" id="historyContent" style="max-height: 450px; overflow-y: auto;">
                <!-- Content via JS -->
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Edit Inventory Modal -->
<div class="modal fade" id="bulkEditModal" tabindex="-1">
    <div class="modal-dialog modal-vw-95">
        <form action="{{ route('sarpras.inventory.bulk-update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content text-start text-dark">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square"></i> Edit Inventaris (Massal)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="bulkEditTable">
                            <thead class="bg-light">
                                <tr>
                                    <th width="220">Nama Barang</th>
                                    <th width="200">Lokasi / Ruangan</th>
                                    <th width="150">Kategori</th>
                                    <th width="130">Kondisi</th>
                                    <th width="140">Harga (Rp)</th>
                                    <th width="200">Sumber / Bantuan</th>
                                    <th width="180">Penanggung Jawab</th>
                                    <th width="140">Tanggal Beli</th>
                                    <th width="140">Foto</th>
                                    <th width="120">Kode Item</th>
                                </tr>
                            </thead>
                            <tbody id="bulkEditBody">
                                <!-- Populated via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4 shadow">Update Semua Terpilih</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Import Excel Modal -->
<div class="modal fade" id="importExcelModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-excel me-2"></i>Import Data via Excel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('sarpras.inventory.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
                        <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                        <div class="small">
                            <strong>Instruksi:</strong> Gunakan template resmi untuk menghindari error. 
                            Nama <strong>Kategori</strong> dan <strong>Ruangan</strong> harus persis seperti daftar panduan di bawah.
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">1. DOWNLOAD TEMPLATE</label>
                            <a href="{{ route('sarpras.inventory.template') }}" class="btn btn-outline-success w-100 py-2 fw-bold">
                                <i class="bi bi-download me-1"></i> Template_Inventaris.xlsx
                            </a>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">2. PILIH FILE EXCEL (.XLSX)</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">UNIT TUJUAN IMPORT</label>
                            <select name="unit_id" id="import_unit_id" class="form-select" required>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">TAHUN PELAJARAN</label>
                            <select name="academic_year_id" class="form-select bg-light fw-bold" required>
                                @if($activeYear)
                                    <option value="{{ $activeYear->id }}" selected>{{ $activeYear->name }} (Aktif Sekarang)</option>
                                @else
                                    <option value="" disabled selected>Tidak ada tahun aktif!</option>
                                @endif
                            </select>
                            <div class="form-text mt-1 small" style="font-size: 10px;">Data akan dimasukkan ke tahun aktif saat ini.</div>
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-book me-2"></i>Panduan Nama (Copas tulisannya)</h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded border">
                                <label class="small fw-bold text-primary mb-2 d-block border-bottom pb-1">KATEGORI TERDAFTAR</label>
                                <div style="max-height: 200px; overflow-y: auto;" class="pe-2">
                                    <ul class="small mb-0 list-unstyled" id="guide-categories">
                                        @foreach($categories->sortBy('name') as $cat)
                                            <li class="mb-1 d-flex justify-content-between category-guide-item" data-unit-id="{{ $cat->unit_id }}">
                                                <span>{{ $cat->name }}</span>
                                                <span class="badge bg-secondary-subtle text-dark border-0" style="font-size: 8px;">{{ $cat->unit->name ?? 'ALL' }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded border">
                                <label class="small fw-bold text-primary mb-2 d-block border-bottom pb-1">RUANGAN TERSEDIA</label>
                                <div style="max-height: 200px; overflow-y: auto;" class="pe-2">
                                    <ul class="small mb-0 list-unstyled" id="guide-rooms">
                                        @foreach($activeRooms->sortBy('name') as $room)
                                            <li class="mb-1 d-flex justify-content-between room-guide-item" data-unit-id="{{ $room->unit_id }}">
                                                <span>{{ $room->name }}</span>
                                                <span class="badge bg-info-subtle text-dark border-0" style="font-size: 8px;">{{ $room->unit->name }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-warning-subtle p-3 rounded border border-warning-subtle">
                        <h6 class="fw-bold small mb-2 text-warning-emphasis"><i class="bi bi-exclamation-triangle-fill me-2"></i>Jika Muncul Error Saat Import:</h6>
                        <ul class="small mb-0 ps-3 text-dark">
                            <li><strong>Kode Barang sudah terdaftar</strong>: Pastikan kode di Excel belum pernah dipakai untuk barang lain.</li>
                            <li><strong>Nama Kategori/Ruangan Salah</strong>: Pastikan tulisannya sama persis (huruf besar/kecil) dengan panduan di atas.</li>
                            <li><strong>Format Tanggal Beli</strong>: Gunakan format YYYY-MM-DD (contoh: 2024-01-25) atau format sel tanggal di Excel.</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">
                        <i class="bi bi-cloud-arrow-up me-1"></i> Mulai Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
@endsection
