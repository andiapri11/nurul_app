@extends('layouts.app')

@section('title', 'Pengeluaran Operasional')

@push('styles')
<style>
    :root {
        --expense-gradient: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        --premium-shadow: 0 10px 30px rgba(239, 68, 68, 0.15);
    }

    .welcome-banner {
        background: var(--expense-gradient);
        border-radius: 24px;
        padding: 40px;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: var(--premium-shadow);
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        filter: blur(50px);
        pointer-events: none;
    }
    .welcome-icon {
        width: 64px;
        height: 64px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        backdrop-filter: blur(10px);
        margin-right: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Procurement Alert Modern */
    .alert-procurement {
        background: #fff;
        border: 2px dashed #f59e0b;
        border-radius: 24px;
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }
    .alert-procurement:hover {
        border-style: solid;
        box-shadow: 0 10px 20px rgba(245, 158, 11, 0.1);
    }

    /* Stats Card Refined */
    .stat-card-premium {
        border: none;
        border-radius: 24px;
        padding: 24px;
        transition: all 0.3s ease;
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .stat-card-premium:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.08);
    }
    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }

    /* Table Design */
    .table-premium {
        background: white;
        border-radius: 20px;
        overflow: hidden;
    }
    .table-premium thead th {
        background-color: #f8fafc;
        padding: 18px 20px;
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1px;
        color: #64748b;
        border: none;
    }
    .table-premium tbody td {
        padding: 18px 20px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        font-size: 0.9rem;
    }

    /* Search/Filter Bar */
    .filter-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 24px;
        border: 1px solid #f1f5f9;
    }

    .btn-premium {
        border-radius: 50px;
        padding: 10px 24px;
        font-weight: 700;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.8rem;
    }
    .btn-premium-danger {
        background: var(--expense-gradient);
        border: none;
        color: white;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }
    .btn-premium-danger:hover {
        background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%);
        transform: scale(1.02);
        color: white;
    }
    .btn-premium-outline {
        background: white;
        border: 2px solid #e2e8f0;
        color: #64748b;
    }

    /* Modal Styling */
    .form-control-premium {
        border-radius: 12px;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
    }
    .form-label-premium {
        font-weight: 700;
        color: #475569;
        margin-bottom: 8px;
        font-size: 0.85rem;
    }

    /* PIN MODAL STYLES */
    .pin-input {
        border: 2px solid #e2e8f0;
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }
    .pin-input:focus {
        border-color: #dc2626;
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
        transform: translateY(-2px);
    }
    .x-small { font-size: 0.75rem; }
    .ls-1 { letter-spacing: 1px; }
</style>
@endpush

@section('content')
<div class="container-fluid px-lg-4 mt-3">
    <!-- Premium Header Banner -->
    <div class="welcome-banner">
        <div class="row align-items-center">
            <div class="col-md-7">
                <div class="d-flex align-items-center">
                    <div class="welcome-icon shadow-sm">
                        <i class="bi bi-cart-dash text-white"></i>
                    </div>
                    <div>
                        <h2 class="fw-extrabold mb-1">Pengeluaran Operasional</h2>
                        <p class="mb-0 text-white-50">Kelola dan monitor seluruh alokasi dana operasional sekolah.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5 d-flex justify-content-md-end mt-4 mt-md-0 gap-2">
                <button type="button" class="btn btn-premium btn-premium-outline" data-bs-toggle="modal" data-bs-target="#manageCategoriesModal">
                    <i class="bi bi-tags me-2"></i> Kategori
                </button>
                <button type="button" class="btn btn-premium btn-premium-danger" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                    <i class="bi bi-plus-lg me-2"></i> Tambah Pengeluaran
                </button>
            </div>
        </div>
    </div>



    @if($pendingDisbursements->count() > 0 || $pendingReports->count() > 0)
    <div class="row">
        @if($pendingDisbursements->count() > 0)
        <div class="col-12 mb-4">
            <div class="alert alert-procurement p-4 h-100 mb-0 shadow-sm border-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-circle p-2 me-3">
                            <i class="bi bi-wallet2 fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Pengajuan Siap Cair</h5>
                            <p class="small text-muted mb-0">Terdapat {{ $pendingDisbursements->count() }} pengajuan yang telah disetujui.</p>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-warning btn-sm rounded-pill px-4 shadow-sm dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-list-ul me-1"></i> Lihat Daftar
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-2 shadow-lg border-0 rounded-4 mt-2 bg-light" style="width: 800px; max-width: 90vw; max-height: 500px; overflow-y: auto;">
                            <div class="px-3 py-2 border-bottom mb-2 bg-white rounded-top-4">
                                <span class="small fw-bold text-uppercase text-muted">Daftar Tunggu Pencairan</span>
                            </div>
                            <div class="d-flex flex-column gap-2 p-2">
                                @foreach($pendingDisbursements as $pd)
                                <div class="bg-white p-3 rounded-4 border shadow-sm transition-hover">
                                    <div class="row align-items-center g-3">
                                        <div class="col-1 text-center">
                                            <div class="avatar-sm bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 35px; height: 35px;">
                                                <i class="bi bi-check-lg fw-bold"></i>
                                            </div>
                                        </div>
                                        <div class="col-7">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning me-2">#{{ $pd->request_code }}</span>
                                                <h6 class="fw-bold text-dark mb-0 text-truncate" title="{{ $pd->activity_name }}">{{ $pd->activity_name }}</h6>
                                            </div>
                                            <div class="small text-muted">
                                                <i class="bi bi-building me-1"></i> {{ $pd->unit_name }} 
                                                <span class="mx-2">&bull;</span> 
                                                <i class="bi bi-box-seam me-1"></i> {{ $pd->items_count }} Item
                                                <span class="mx-2">&bull;</span>
                                                <i class="bi bi-person me-1"></i> {{ $pd->user->name ?? 'User' }}
                                            </div>
                                        </div>
                                        <div class="col-2 text-end">
                                            <span class="fw-extrabold text-success fs-6">Rp {{ number_format($pd->total_amount, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="col-2 text-end">
                                            <button class="btn btn-warning btn-sm rounded-pill px-3 fw-bold shadow-sm w-100" onclick='openRecordModal(@json($pd))'>
                                                Cairkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($pendingReports->count() > 0)
        <div class="col-12 mb-4">
            <div class="alert alert-procurement p-4 h-100 mb-0 shadow-sm border-info" style="background-color: #f0f9ff; border-style: dashed;">
                 <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-info bg-opacity-10 text-info rounded-circle p-2 me-3">
                            <i class="bi bi-file-earmark-check fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Verifikasi Nota / Laporan</h5>
                            <p class="small text-muted mb-0">Terdapat {{ $pendingReports->count() }} laporan menunggu verifikasi.</p>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-info text-white btn-sm rounded-pill px-4 shadow-sm dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-list-check me-1"></i> Lihat Daftar
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-2 shadow-lg border-0 rounded-4 mt-2 bg-light" style="width: 800px; max-width: 90vw; max-height: 500px; overflow-y: auto;">
                            <div class="px-3 py-2 border-bottom mb-2 bg-white rounded-top-4">
                                <span class="small fw-bold text-uppercase text-muted">Daftar Verifikasi Nota</span>
                            </div>
                            <div class="d-flex flex-column gap-2 p-2">
                                @foreach($pendingReports as $pr)
                                <div class="bg-white p-3 rounded-4 border shadow-sm transition-hover">
                                    <div class="row align-items-center g-3">
                                        <div class="col-1 text-center">
                                            <div class="avatar-sm bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 35px; height: 35px;">
                                                <i class="bi bi-search fw-bold"></i>
                                            </div>
                                        </div>
                                        <div class="col-7">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info me-2">#{{ $pr->request_code }}</span>
                                                <h6 class="fw-bold text-dark mb-0 text-truncate" title="{{ $pr->activity_name }}">{{ $pr->activity_name }}</h6>
                                            </div>
                                            <div class="small text-muted">
                                                <i class="bi bi-calendar-check me-1"></i> {{ $pr->report_at ? \Carbon\Carbon::parse($pr->report_at)->isoFormat('D MMM Y') : '-' }}
                                                <span class="mx-2">&bull;</span>
                                                <i class="bi bi-building me-1"></i> {{ $pr->unit_name }}
                                            </div>
                                        </div>
                                        <div class="col-2 text-end">
                                            <span class="fw-extrabold text-dark fs-6">Rp {{ number_format($pr->total_amount, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="col-2 text-end">
                                            <button class="btn btn-info text-white btn-sm rounded-pill px-3 fw-bold shadow-sm w-100" onclick='openVerifyModal(@json($pr))'>
                                                Verifikasi
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card-premium">
                <div class="stat-icon-wrapper bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-graph-down-arrow fs-4"></i>
                </div>
                <div class="text-muted fw-bold small text-uppercase ls-1 mb-1">Total (Sesuai Filter)</div>
                <h3 class="fw-extrabold text-dark mb-0">Rp {{ number_format($stats['total'], 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-premium">
                <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-calendar-x fs-4"></i>
                </div>
                <div class="text-muted fw-bold small text-uppercase ls-1 mb-1">Pengeluaran Bulan Ini</div>
                <h3 class="fw-extrabold text-dark mb-0 text-danger">Rp {{ number_format($stats['this_month'], 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-premium">
                <div class="stat-icon-wrapper bg-dark bg-opacity-10 text-dark">
                    <i class="bi bi-hourglass-split fs-4"></i>
                </div>
                <div class="text-muted fw-bold small text-uppercase ls-1 mb-1">Pengeluaran Hari Ini</div>
                <h3 class="fw-extrabold text-dark mb-0">Rp {{ number_format($stats['today'], 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-card shadow-sm">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label-premium">Tahun Pelajaran</label>
                <select name="academic_year_id" class="form-select form-control-premium" onchange="this.form.submit()">
                    @foreach($academicYears as $ay)
                        <option value="{{ $ay->id }}" {{ $academic_year_id == $ay->id ? 'selected' : '' }}>TP {{ $ay->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label-premium">Unit Sekolah</label>
                <select name="unit_id" class="form-select form-control-premium" onchange="this.form.submit()">
                    <option value="all">Semua Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label-premium">Kategori</label>
                <select name="category" class="form-select form-control-premium" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label-premium">Rentang Tanggal</label>
                <div class="input-group">
                    <input type="date" name="date_start" class="form-control form-control-premium" value="{{ request('date_start') }}">
                    <span class="input-group-text bg-transparent border-0">ke</span>
                    <input type="date" name="date_end" class="form-control form-control-premium" value="{{ request('date_end') }}">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-premium btn-premium-outline flex-grow-1">
                    <i class="bi bi-search me-2"></i> Cari
                </button>
                <a href="{{ route('finance.expense.index') }}" class="btn btn-premium btn-light">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="table-premium shadow-sm mb-5">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No. Bukti / Tanggal</th>
                        <th>Rincian Pengeluaran</th>
                        <th>Penerima / Metode</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-center">PJ</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-light text-secondary rounded-3 p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-file-earmark-diff"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">EXP-{{ str_pad($expense->id, 5, '0', STR_PAD_LEFT) }}</div>
                                        <div class="small text-muted">{{ $expense->transaction_date->translatedFormat('d F Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-danger bg-opacity-10 text-danger border-0 px-2 py-1 small rounded-pill mb-1">{{ $expense->category }}</span>
                                <div class="fw-semibold text-dark small text-truncate" style="max-width: 250px;">{{ $expense->description ?: '-' }}</div>
                                @if($expense->procurement_request_code)
                                    <div class="small mt-1">
                                        <button type="button" class="btn btn-link p-0 text-primary small text-decoration-none" data-bs-toggle="modal" data-bs-target="#procDetailModal{{ $expense->id }}">
                                            <i class="bi bi-link-45deg"></i> Ref: {{ $expense->procurement_request_code }}
                                        </button>
                                    </div>

                                    <!-- Procurement Detail Modal -->
                                    <div class="modal fade" id="procDetailModal{{ $expense->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content text-start">
                                                <div class="modal-header bg-dark text-white">
                                                    <h5 class="modal-title">Detail Pengadaan: {{ $expense->procurement_request_code }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    @php
                                                        $procBatch = \App\Models\ProcurementRequest::where('request_code', $expense->procurement_request_code)->with('category')->get();
                                                        $firstProc = $procBatch->first();
                                                    @endphp
                                                    
                                                    @if($firstProc)
                                                        <div class="bg-light rounded-4 p-3 mb-4 border">
                                                            <div class="row">
                                                                <div class="col-md-6 border-end">
                                                                    <div class="small text-muted mb-1">Kegiatan / Keperluan:</div>
                                                                    <div class="fw-bold text-dark fs-5">{{ $firstProc->activity_name }}</div>
                                                                    <div class="small mt-2 text-secondary">{{ $firstProc->activity_description ?: 'Tidak ada deskripsi.' }}</div>
                                                                </div>
                                                                <div class="col-md-6 ps-4">
                                                                    <div class="row mb-2">
                                                                        <div class="col-5 small text-muted">Pelapor:</div>
                                                                        <div class="col-7 small fw-bold text-dark">{{ $firstProc->user->name ?? '-' }}</div>
                                                                    </div>
                                                                    <div class="row mb-2">
                                                                        <div class="col-5 small text-muted">Tanggal Lapor:</div>
                                                                        <div class="col-7 small fw-bold text-dark">{{ $firstProc->report_at ? $firstProc->report_at->translatedFormat('d F Y H:i') : '-' }}</div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-5 small text-muted">Catatan Sarpras:</div>
                                                                        <div class="col-7 small italic text-secondary">{{ $firstProc->report_note ?: '-' }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="table-responsive mb-4">
                                                            <table class="table table-sm table-bordered">
                                                                <thead class="bg-light small">
                                                                    <tr>
                                                                        <th>Item</th>
                                                                        <th class="text-center">Kategori</th>
                                                                        <th class="text-center">Jumlah</th>
                                                                        <th class="text-end">Harga Satuan (Approve)</th>
                                                                        <th class="text-end">Subtotal</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="small">
                                                                    @foreach($procBatch as $pb)
                                                                        @if($pb->director_status === 'Approved')
                                                                            <tr>
                                                                                <td>{{ $pb->item_name }}</td>
                                                                                <td class="text-center"><span class="badge bg-light text-dark">{{ $pb->category->name ?? '-' }}</span></td>
                                                                                <td class="text-center">{{ $pb->approved_quantity ?: $pb->quantity }} {{ $pb->unit_name }}</td>
                                                                                <td class="text-end">Rp {{ number_format($pb->approved_price ?: $pb->estimated_price, 0, ',', '.') }}</td>
                                                                                <td class="text-end fw-bold">Rp {{ number_format(($pb->approved_quantity ?: $pb->quantity) * ($pb->approved_price ?: $pb->estimated_price), 0, ',', '.') }}</td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot class="bg-light fw-bold">
                                                                    <tr>
                                                                        <td colspan="4" class="text-end small">TOTAL REALISASI:</td>
                                                                        <td class="text-end">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>

                                                        <div class="row g-3">
                                                            <div class="col-md-6 text-center">
                                                                <label class="form-label d-block small fw-bold mb-2">Bukti Nota:</label>
                                                                <div class="border rounded-4 p-2 bg-white">
                                                                    @if($expense->nota)
                                                                        <a href="/storage/{{ $expense->nota }}" target="_blank">
                                                                            <img src="/storage/{{ $expense->nota }}" class="img-fluid rounded-3" style="max-height: 200px" alt="Nota">
                                                                        </a>
                                                                    @else
                                                                        <div class="py-4 text-muted small">Tidak ada nota diupload.</div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 text-center">
                                                                <label class="form-label d-block small fw-bold mb-2">Foto Barang:</label>
                                                                <div class="border rounded-4 p-2 bg-white">
                                                                    @if($expense->photo)
                                                                        <a href="/storage/{{ $expense->photo }}" target="_blank">
                                                                            <img src="/storage/{{ $expense->photo }}" class="img-fluid rounded-3" style="max-height: 200px" alt="Foto Barang">
                                                                        </a>
                                                                    @else
                                                                        <div class="py-4 text-muted small">Tidak ada foto diupload.</div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="text-center py-5">
                                                            <i class="bi bi-exclamation-triangle display-4 text-warning"></i>
                                                            <p class="mt-3">Data pengadaan tidak ditemukan.</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark small">{{ $expense->payer_name ?: 'Umum/Internal' }}</div>
                                <div class="small mt-1">
                                    @if($expense->payment_method == 'transfer')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill px-2">
                                            <i class="bi bi-bank me-1"></i> Transfer
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 rounded-pill px-2">
                                            <i class="bi bi-cash-stack me-1"></i> Tunai
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end fw-extrabold text-danger">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <div class="avatar-sm d-inline-block p-1 bg-light rounded-circle mb-1" title="{{ $expense->user->name ?? 'Admin' }}" style="width: 25px; height: 25px; line-height: 18px; font-size: 0.6rem; font-weight: 700;">
                                    {{ strtoupper(substr($expense->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="small text-muted" style="font-size: 0.6rem;">{{ explode(' ', $expense->user->name ?? 'Admin')[0] }}</div>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('finance.expense.print', $expense->id) }}" target="_blank" class="btn btn-sm btn-light rounded-pill px-3 shadow-none border" title="Cetak Kuitansi">
                                        <i class="bi bi-printer text-primary"></i>
                                    </a>
                                    @if(in_array(auth()->user()->role, ['administrator', 'kepala_keuangan']))
                                        <form action="{{ route('finance.expense.destroy', $expense->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="security_pin" class="pin-hidden-field">
                                            <button type="button" class="btn btn-sm btn-light rounded-pill px-3 shadow-none border btn-delete" title="Hapus Data">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-inbox display-1 text-light mb-3 d-block opacity-50"></i>
                                    <h5 class="text-muted fw-bold">Belum Ada Riwayat Pengeluaran</h5>
                                    <p class="text-muted small">Mulai catat pengeluaran operasional hari ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $expenses->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Add Expense -->
<div class="modal fade" id="addExpenseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom-0">
                <div>
                    <h4 class="fw-extrabold mb-0">Input Pengeluaran</h4>
                    <p class="text-muted small mb-0">Catat transaksi pengeluaran dana sekolah.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('finance.expense.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                            <ul class="mb-0 small fw-bold">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label-premium">Dibayarkan Kepada (Penerima)</label>
                            <input type="text" name="payer_name" class="form-control form-control-premium" placeholder="Contoh: Nama Toko, Karyawan, atau Vendor" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-premium">Tanggal Transaksi</label>
                            <input type="date" name="transaction_date" class="form-control form-control-premium" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label-premium">Kategori</label>
                            <select name="category" class="form-select form-control-premium" required>
                                <option value="" disabled selected>Pilih Kategori...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-premium">Metode Pembayaran</label>
                            <select name="payment_method" class="form-select form-control-premium payment-method-toggle" required>
                                <option value="tunai">Tunai / Cash</option>
                                <option value="transfer">Transfer Bank</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-none bank-account-wrapper">
                            <label class="form-label-premium">Sumber Rekening</label>
                            <select name="bank_account_id" class="form-select form-control-premium">
                                <option value="">Pilih Rekening...</option>
                                @foreach($bankAccounts as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->account_name }} ({{ $bank->bank_name }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    <!-- Item Details Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label-premium mb-0">Rincian Item Pengajuan (Opsional)</label>
                            <button type="button" class="btn btn-sm btn-light border fw-bold rounded-pill px-3" id="btn_add_item">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Item
                            </button>
                        </div>
                        <div class="table-responsive border rounded-3 bg-white">
                            <table class="table table-sm table-borderless align-middle mb-0" id="expense_items_table">
                                <thead class="bg-light small text-muted text-uppercase fw-bold">
                                    <tr>
                                        <th class="ps-3 py-2" width="35%">Item</th>
                                        <th class="py-2" width="15%">Qty</th>
                                        <th class="py-2" width="15%">Satuan</th>
                                        <th class="py-2" width="25%">Harga @</th>
                                        <th class="text-end pe-3 py-2" width="10%">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-bold text-dark small">
                                    <tr class="item-row d-none" id="item_template">
                                        <td class="ps-3">
                                            <input type="text" name="items[INDEX][name]" class="form-control form-control-sm border-0 bg-light" placeholder="Nama Barang">
                                        </td>
                                        <td>
                                            <input type="number" name="items[INDEX][qty]" class="form-control form-control-sm border-0 bg-light item-qty text-center" placeholder="0" min="1">
                                        </td>
                                        <td>
                                            <input type="text" name="items[INDEX][unit]" class="form-control form-control-sm border-0 bg-light text-center" placeholder="Pcs">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm border-0 bg-light item-price text-end" placeholder="0">
                                            <input type="hidden" name="items[INDEX][price]" class="item-price-raw">
                                        </td>
                                        <td class="text-end pe-3">
                                            <button type="button" class="btn btn-sm text-danger btn-remove-item"><i class="bi bi-x-lg"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-center py-3 text-muted small fst-italic" id="empty_items_msg">
                                Belum ada item ditambahkan. Total manual di bawah.
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-premium">Total Pengeluaran (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 fw-bold text-muted ps-3 pe-2">Rp</span>
                            <input type="text" id="expense_amount_display" class="form-control form-control-premium rounded-start-0 border-start-0 fs-4 fw-extrabold text-danger" placeholder="0" required>
                            <input type="hidden" name="amount" id="expense_amount_raw">
                        </div>
                        <div class="form-text small text-end text-muted" id="auto_calc_note" style="display: none;">
                            <i class="bi bi-magic me-1"></i> Dihitung otomatis dari item di atas.
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label-premium">Keterangan / Tujuan Dana</label>
                        <textarea name="description" class="form-control form-control-premium" rows="2" placeholder="Contoh: Pembayaran listrik, air, dan internet bulan Desember."></textarea>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="p-3 bg-light rounded-4 border border-dashed">
                                <div class="form-check mb-3 p-3 bg-white rounded-4 shadow-sm border border-light">
                                    <input class="form-check-input ms-0 me-3" type="radio" name="proof_mode" id="proof_pending" value="pending" required style="width: 1.25rem; height: 1.25rem;">
                                    <label class="form-check-label small fw-bold text-dark pt-1" for="proof_pending">
                                        Perlu Bukti Nota Nanti? (Pending)
                                        <span class="d-block x-small text-muted fw-normal mt-1 opacity-75">Centang ini jika bukti belum ada dan akan diupload nanti.</span>
                                    </label>
                                </div>
                                
                                <div class="form-check mb-0 p-3 bg-white rounded-4 shadow-sm border border-light">
                                    <input class="form-check-input ms-0 me-3" type="radio" name="proof_mode" id="proof_now" value="now" style="width: 1.25rem; height: 1.25rem;">
                                    <label class="form-check-label small fw-bold text-dark pt-1" for="proof_now">
                                        Bukti Nota Sudah Ada?
                                        <span class="d-block x-small text-muted fw-normal mt-1 opacity-75">Centang ini untuk upload nota sekarang juga.</span>
                                    </label>
                                </div>

                                <div id="proof_upload_container" class="mt-4 d-none p-3 bg-white rounded-4 border border-primary border-opacity-25">
                                    <label class="form-label fw-bold small text-primary mb-2"><i class="bi bi-upload me-2 text-primary"></i>Upload Bukti Nota Sekarang</label>
                                    <input type="file" name="nota_file" id="nota_file_input" class="form-control form-control-sm border-0 bg-light" accept="image/*">
                                </div>
                                <input type="hidden" name="is_proof_needed" id="is_proof_needed_hidden" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-top text-center">
                        <label class="form-label-premium fw-bold text-dark mb-3 text-uppercase ls-1">Konfirmasi PIN Keamanan</label>
                        <div class="d-flex justify-content-center gap-2 mb-3 pin-container">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                        </div>
                        <input type="hidden" name="security_pin">
                        <p class="x-small text-muted mb-0"><i class="bi bi-shield-lock me-1"></i> Wajib memasukkan PIN untuk menyimpan transaksi.</p>
                    </div>

                    <div class="mt-3 p-3 bg-light rounded-4 border border-dashed text-center">
                        <p class="small text-muted mb-0"><i class="bi bi-info-circle me-1"></i> Data unit Pelaksana sekarang bersifat opsional.</p>
                        <select name="unit_id" class="form-select form-select-sm border-0 bg-transparent text-center fw-bold">
                            <option value="">-- Pilih Unit Pelaksana (Opsional) --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn fw-bold text-muted me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-premium btn-premium-danger px-4">Simpan Pengeluaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Manage Categories -->
<div class="modal fade" id="manageCategoriesModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom-0">
                <h5 class="fw-extrabold mb-0">Kelola Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="bg-light p-4 border-bottom">
                    <form action="{{ route('finance.expense.categories.store') }}" method="POST">
                        @csrf
                        <div class="row g-2">
                            <div class="col-10">
                                <input type="text" name="name" class="form-control form-control-premium" placeholder="Nama kategori baru..." required>
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-danger w-100 h-100 shadow-sm"><i class="bi bi-plus-lg"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="p-2" style="max-height: 400px; overflow-y: auto;">
                    @foreach($categories as $cat)
                        <div class="d-flex justify-content-between align-items-center p-3 hover-bg-light rounded-3 transition-all">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger bg-opacity-10 text-danger rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="bi bi-tag-fill" style="font-size: 0.8rem;"></i>
                                </div>
                                <span class="fw-bold text-dark">{{ $cat->name }}</span>
                            </div>
                            <form action="{{ route('finance.expense.categories.destroy', $cat->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-light btn-delete-cat text-danger border-0 shadow-none">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-premium btn-premium-outline w-100" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Record Procurement -->
<div class="modal fade" id="recordProcurementModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-success text-white rounded-top-4 p-4">
                <div>
                    <h4 class="fw-extrabold mb-0 text-white">Pencatatan Kas Realisasi</h4>
                    <p class="text-white-50 small mb-0">Input data pengeluaran berdasarkan pengajuan sarpras.</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('finance.expense.store-procurement') }}" method="POST">
                @csrf
                <input type="hidden" name="request_code" id="procurement_code">
                <div class="modal-body p-4">
                    <div class="mb-4 bg-light p-3 rounded-3 border">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="small text-muted mb-1">Kegiatan:</div>
                                <div id="procurement_activity" class="fw-bold text-dark fs-5"></div>
                                <div id="procurement_unit" class="small text-secondary mt-1"></div>
                            </div>
                            <div id="procurement_status_badge"></div>
                        </div>
                        
                        <div class="mt-3">
                            <label class="small fw-bold text-primary text-uppercase mb-2 d-block">Detail Barang Disetujui:</label>
                            <div class="table-responsive bg-white rounded border">
                                <table class="table table-sm mb-0">
                                    <thead class="bg-light small">
                                        <tr>
                                            <th>Item</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody id="procurement_items_list" class="small">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label-premium">Kategori Biaya</label>
                            <select name="category" class="form-select form-control-premium" required>
                                <option value="Sarpras / Inventaris">Sarpras / Inventaris</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">Tgl Transaksi (Cair)</label>
                            <input type="date" name="transaction_date" class="form-control form-control-premium" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-premium">Metode Pembayaran</label>
                        <select name="payment_method" id="payment_method_proc" class="form-select form-control-premium" onchange="toggleBankProc()" required>
                            <option value="tunai">Tunai / Kas Kecil</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>

                    <div class="mb-4 d-none" id="bank_select_proc">
                        <label class="form-label-premium">Pilih Rekening Sumber</label>
                        <select name="bank_account_id" class="form-select form-control-premium">
                            <option value="">-- Pilih Rekening --</option>
                            @foreach($bankAccounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->bank_name }} - {{ $acc->account_number }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-premium">Kepada / Penerima Dana</label>
                        <input type="text" name="payer_name" class="form-control form-control-premium" placeholder="Nama Toko atau Penanggung Jawab" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-premium">Total Setuju (Rp)</label>
                        <input type="text" id="procurement_amount_display" class="form-control form-control-premium fs-4 fw-extrabold text-success" readonly>
                        <input type="hidden" name="amount" id="procurement_amount_raw">
                    </div>

                    <div class="mt-4 pt-4 border-top text-center">
                        <label class="form-label-premium fw-bold text-dark mb-3 text-uppercase ls-1">Konfirmasi PIN Keamanan</label>
                        <div class="d-flex justify-content-center gap-2 mb-3 pin-container">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                            <input type="password" class="form-control pin-input text-center fw-bold fs-4 rounded-3" maxlength="1" pattern="\d*" inputmode="numeric" required style="width: 45px; height: 50px;">
                        </div>
                        <input type="hidden" name="security_pin">
                        <p class="x-small text-muted mb-0"><i class="bi bi-shield-lock me-1"></i> Wajib memasukkan PIN untuk mencairkan dana.</p>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn fw-bold text-muted me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-premium btn-success px-4">Cairkan & Catat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Verify Report -->
<div class="modal fade" id="verifyReportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark rounded-top-4 p-4">
                <div>
                    <h4 class="fw-extrabold mb-0">Verifikasi Laporan Bukti</h4>
                    <p class="text-dark-50 small mb-0">Periksa nota dan foto barang yang diunggah Sarpras.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('finance.realization.verify') }}" method="POST">
                @csrf
                <input type="hidden" name="request_code" id="verify_request_code">
                <div class="modal-body p-4 text-start">
                    <div id="verify_header_info">
                        <div class="bg-light rounded-4 p-3 mb-4 border">
                            <div class="row">
                                <div class="col-md-6 border-end">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="small text-muted mb-1">Kegiatan / Keperluan:</div>
                                            <div id="verify_activity_name" class="fw-bold text-dark fs-5"></div>
                                            <div id="verify_unit_name" class="small mt-1 text-secondary"></div>
                                        </div>
                                        <div id="verify_status_badge"></div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <div class="small fw-bold text-uppercase text-muted border-bottom mb-2 pb-1">Detail Persetujuan:</div>
                                        <div class="table-responsive bg-white rounded border">
                                            <table class="table table-sm table-borderless mb-0" style="font-size: 0.75rem;">
                                                <tbody id="verify_items_list">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 ps-4">
                                    <div class="row mb-2">
                                        <div class="col-5 small text-muted">Tanggal Lapor:</div>
                                        <div id="verify_report_at_display" class="col-7 small fw-bold text-dark"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5 small text-muted">Catatan Sarpras:</div>
                                        <div id="verify_report_note" class="col-7 small italic text-secondary border rounded p-1 bg-white"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6 text-center">
                            <label class="form-label-premium">Bukti Nota</label>
                            <a id="link_report_nota" href="#" target="_blank">
                                <img id="img_report_nota" src="" class="img-fluid rounded border shadow-sm" style="max-height: 200px" alt="Nota">
                            </a>
                        </div>
                        <div class="col-md-6 text-center">
                            <label class="form-label-premium">Foto Barang</label>
                            <a id="link_report_photo" href="#" target="_blank">
                                <img id="img_report_photo" src="" class="img-fluid rounded border shadow-sm" style="max-height: 200px" alt="Foto">
                            </a>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-premium">Keputusan</label>
                            <select name="status" class="form-select form-control-premium" required>
                                <option value="Approved">TERIMA & VERIFIKASI</option>
                                <option value="Rejected">TOLAK / PERBAIKI</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-premium">Catatan Finance</label>
                            <textarea name="finance_note" class="form-control form-control-premium" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn fw-bold text-muted me-auto" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-premium btn-warning px-4 shadow">Simpan Hasil Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatNumber(n) {
        if (!n) return "0";
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function toggleBankProc() {
        const method = document.getElementById('payment_method_proc').value;
        const bankSelect = document.getElementById('bank_select_proc');
        if (method === 'transfer') {
            bankSelect.classList.remove('d-none');
            bankSelect.querySelector('select').required = true;
        } else {
            bankSelect.classList.add('d-none');
            bankSelect.querySelector('select').required = false;
        }
    }

    function openRecordModal(data) {
        document.getElementById('procurement_code').value = data.request_code;
        document.getElementById('procurement_activity').innerText = data.activity_name;
        document.getElementById('procurement_unit').innerText = data.unit_name;
        document.getElementById('procurement_amount_display').value = formatNumber(data.total_amount);
        document.getElementById('procurement_amount_raw').value = data.total_amount;
        
        const badge = document.getElementById('procurement_status_badge');
        badge.innerHTML = '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Belum Cair</span>';
        
        const listBody = document.getElementById('procurement_items_list');
        listBody.innerHTML = '';
        if (data.items) {
            data.items.forEach(item => {
                const row = `<tr>
                    <td>${item.item_name}</td>
                    <td class="text-center">${item.quantity} ${item.unit_name}</td>
                    <td class="text-end">Rp ${formatNumber(item.price)}</td>
                </tr>`;
                listBody.insertAdjacentHTML('beforeend', row);
            });
        }
        
        const modal = new bootstrap.Modal(document.getElementById('recordProcurementModal'));
        modal.show();
    }

    function openVerifyModal(report) {
        document.getElementById('verify_request_code').value = report.request_code;
        document.getElementById('verify_activity_name').innerText = report.activity_name;
        document.getElementById('verify_unit_name').innerText = report.unit_name;
        document.getElementById('verify_report_note').innerText = report.report_note || '-';
        
        const badge = document.getElementById('verify_status_badge');
        badge.innerHTML = '<span class="badge bg-info text-dark"><i class="bi bi-file-earmark-check me-1"></i>Sudah Lapor</span>';

        const verifyListBody = document.getElementById('verify_items_list');
        verifyListBody.innerHTML = '';
        if (report.items) {
            report.items.forEach(item => {
                const row = `<tr>
                    <td>${item.item_name}</td>
                    <td class="text-center fw-bold">${item.quantity} ${item.unit_name}</td>
                    <td class="text-end">@ Rp ${formatNumber(item.price)}</td>
                </tr>`;
                verifyListBody.insertAdjacentHTML('beforeend', row);
            });
        }

        if (report.report_at) {
            const date = new Date(report.report_at);
            document.getElementById('verify_report_at_display').innerText = date.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
        } else {
            document.getElementById('verify_report_at_display').innerText = '-';
        }

        const storageUrl = "{{ asset('storage') }}/";
        document.getElementById('img_report_nota').src = report.report_nota ? storageUrl + report.report_nota : '';
        document.getElementById('link_report_nota').href = report.report_nota ? storageUrl + report.report_nota : '#';
        document.getElementById('img_report_photo').src = report.report_photo ? storageUrl + report.report_photo : '';
        document.getElementById('link_report_photo').href = report.report_photo ? storageUrl + report.report_photo : '#';

        const modal = new bootstrap.Modal(document.getElementById('verifyReportModal'));
        modal.show();
    }

    $(document).ready(function() {
        // Confirmation for Delete Expense
        $('.btn-delete').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Hapus data ini permanen? Masukkan 6 digit PIN Anda.",
                icon: 'warning',
                input: 'password',
                inputAttributes: {
                    autocapitalize: 'off',
                    autocorrect: 'off',
                    maxlength: 6,
                    pattern: '[0-9]*',
                    inputmode: 'numeric',
                    style: 'text-align: center; letter-spacing: 12px; font-size: 24px;'
                },
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Hapus Sekarang!',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger px-4 rounded-pill',
                    cancelButton: 'btn btn-light px-4 border ms-3 rounded-pill',
                    popup: 'rounded-4 shadow-lg'
                },
                preConfirm: (pin) => {
                    if (!pin || pin.length < 6) {
                        Swal.showValidationMessage('Silakan masukkan 6 digit PIN valid.');
                        return false;
                    }
                    return pin;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    form.find('.pin-hidden-field').val(result.value);
                    form.submit();
                }
            });
        });

        // Confirmation for Delete Category
        $('.btn-delete-cat').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Kategori?',
                text: "Kategori pengeluaran ini akan dihapus.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Hapus!',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger px-4',
                    cancelButton: 'btn btn-light px-4 border ms-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        function setupAutoDot(displayId, rawId) {
            const display = document.getElementById(displayId);
            const raw = document.getElementById(rawId);

            if (display && raw) {
                display.addEventListener('keyup', function() {
                    let val = this.value.replace(/\D/g, "");
                    raw.value = val;
                    this.value = val === "" ? "" : formatNumber(val);
                });
            }
        }

        setupAutoDot('expense_amount_display', 'expense_amount_raw');

        // Payment Method Toggle
        $('.payment-method-toggle').change(function() {
            const wrapper = $(this).closest('.modal-body').find('.bank-account-wrapper');
            if ($(this).val() === 'transfer') {
                wrapper.removeClass('d-none');
                wrapper.find('select').attr('required', true);
            } else {
                wrapper.addClass('d-none');
                wrapper.find('select').attr('required', false).val('');
            }
        });

        // Trigger initial state
        $('.payment-method-toggle').trigger('change');

        // Logic for Proof Radio Buttons
        const proofPendingRadio = document.getElementById('proof_pending');
        const proofNowRadio = document.getElementById('proof_now');
        const uploadContainer = document.getElementById('proof_upload_container');
        const fileInput = document.getElementById('nota_file_input');
        const isProofNeededHidden = document.getElementById('is_proof_needed_hidden');

        if(proofPendingRadio && proofNowRadio) {
            proofPendingRadio.addEventListener('change', function() {
                if(this.checked) {
                    uploadContainer.classList.add('d-none');
                    fileInput.value = ''; // Reset file
                    fileInput.required = false;
                    isProofNeededHidden.value = "1";
                }
            });

            proofNowRadio.addEventListener('change', function() {
                if(this.checked) {
                    uploadContainer.classList.remove('d-none');
                    fileInput.required = true;
                    isProofNeededHidden.value = "1";
                }
            });
        }

        // PIN Handling for all pin containers
        const pinContainers = document.querySelectorAll('.pin-container');
        pinContainers.forEach(container => {
            const inputs = container.querySelectorAll('.pin-input');
            const form = container.closest('form');
            const hiddenInput = form.querySelector('input[name="security_pin"]');

            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    if (e.target.value.length > 1) {
                        e.target.value = e.target.value.slice(0, 1);
                    }
                    if (e.target.value && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                    updateHiddenInput();
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });

            function updateHiddenInput() {
                let pin = "";
                inputs.forEach(input => pin += input.value);
                hiddenInput.value = pin;
            }

            form.addEventListener('submit', function(e) {
                updateHiddenInput();
                if (hiddenInput.value.length < 6) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'PIN Belum Lengkap',
                        text: 'Silakan masukkan 6 digit PIN Anda.',
                        timer: 2000,
                        showConfirmButton: false,
                        buttonsStyling: false,
                        customClass: {
                            popup: 'rounded-4'
                        }
                    });
                }
            });
        });

        // Logic for Dynamic Items
        let itemIndex = 0;
        const itemsTable = document.getElementById('expense_items_table').querySelector('tbody');
        const emptyMsg = document.getElementById('empty_items_msg');
        const totalDisplay = document.getElementById('expense_amount_display');
        const totalRaw = document.getElementById('expense_amount_raw');
        const autoCalcNote = document.getElementById('auto_calc_note');

        function updateTotal() {
            let grandTotal = 0;
            const rows = itemsTable.querySelectorAll('tr:not(.d-none)');
            
            if(rows.length > 0) {
                // Auto calc mode
                totalDisplay.readOnly = true;
                totalDisplay.classList.add('bg-light');
                autoCalcNote.style.display = 'block';

                rows.forEach(row => {
                    const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
                    const priceRaw = row.querySelector('.item-price-raw').value;
                    const price = parseFloat(priceRaw) || 0;
                    grandTotal += (qty * price);
                });

                totalRaw.value = grandTotal;
                totalDisplay.value = formatNumber(grandTotal);
            } else {
                // Manual mode
                totalDisplay.readOnly = false;
                totalDisplay.classList.remove('bg-light');
                autoCalcNote.style.display = 'none';
            }
        }

        document.getElementById('btn_add_item').addEventListener('click', function() {
            const template = document.getElementById('item_template');
            const clone = template.cloneNode(true);
            
            clone.classList.remove('d-none');
            clone.removeAttribute('id');
            clone.innerHTML = clone.innerHTML.replace(/INDEX/g, itemIndex++);
            
            itemsTable.appendChild(clone);
            emptyMsg.style.display = 'none';

            // Setup events for new row
            const qtyInput = clone.querySelector('.item-qty');
            const priceInput = clone.querySelector('.item-price');
            const priceRaw = clone.querySelector('.item-price-raw');
            const removeBtn = clone.querySelector('.btn-remove-item');

            qtyInput.addEventListener('input', updateTotal);
            
            priceInput.addEventListener('keyup', function() {
                let val = this.value.replace(/\D/g, "");
                priceRaw.value = val;
                this.value = val === "" ? "" : formatNumber(val);
                updateTotal();
            });

            removeBtn.addEventListener('click', function() {
                clone.remove();
                if(itemsTable.querySelectorAll('tr:not(.d-none)').length === 0) {
                    emptyMsg.style.display = 'block';
                }
                updateTotal();
            });

            updateTotal();
        });
    });
</script>
@endpush
