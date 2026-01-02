@extends('layouts.app')

@section('title', 'Pemasukan Lainnya')

@push('styles')
<style>
    :root {
        --income-gradient: linear-gradient(135deg, #059669 0%, #10b981 100%);
        --premium-shadow: 0 10px 30px rgba(16, 185, 129, 0.15);
    }

    .welcome-banner {
        background: var(--income-gradient);
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
    .date-pill {
        background: rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(15px);
        border-radius: 50px;
        padding: 12px 25px;
        display: flex;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .icon-box {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        color: #10b981;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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
    .table-premium tbody tr:last-child td {
        border-bottom: none;
    }

    /* Badges */
    .badge-soft-success {
        background-color: #ecfdf5;
        color: #059669;
        font-weight: 600;
        padding: 6px 12px;
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
    .btn-premium-primary {
        background: var(--income-gradient);
        border: none;
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }
    .btn-premium-primary:hover {
        background: linear-gradient(135deg, #047857 0%, #059669 100%);
        transform: scale(1.02);
        color: white;
    }

    .btn-premium-outline {
        background: white;
        border: 2px solid #e2e8f0;
        color: #64748b;
    }
    .btn-premium-outline:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #475569;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 24px;
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .modal-header {
        padding: 30px 30px 10px;
        border: none;
    }
    .modal-body {
        padding: 30px;
    }
    .form-label-premium {
        font-weight: 700;
        color: #475569;
        margin-bottom: 8px;
        font-size: 0.85rem;
    }
    .form-control-premium {
        border-radius: 12px;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
        transition: all 0.2s;
    }
    .form-control-premium:focus {
        background-color: #fff;
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }

    /* PIN MODAL STYLES */
    .pin-input {
        border: 2px solid #e2e8f0;
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }
    .pin-input:focus {
        border-color: #10b981;
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        transform: translateY(-2px);
    }
    .x-small { font-size: 0.75rem; }
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
                        <i class="bi bi-wallet2 text-white"></i>
                    </div>
                    <div>
                        <h2 class="fw-extrabold mb-1">Pemasukan Lainnya</h2>
                        <p class="mb-0 text-white-50">Kelola sumber pendapatan sekolah di luar pembayaran rutin siswa.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5 d-flex justify-content-md-end mt-4 mt-md-0 gap-2">
                <button type="button" class="btn btn-premium btn-premium-outline" data-bs-toggle="modal" data-bs-target="#manageCategoriesModal">
                    <i class="bi bi-tags me-2"></i> Kategori
                </button>
                <button type="button" class="btn btn-premium btn-premium-primary" id="btn-add-income" data-bs-toggle="modal" data-bs-target="#addIncomeModal">
                    <i class="bi bi-plus-lg me-2"></i> Tambah Pemasukan
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card-premium">
                <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                    <i class="bi bi-cash-stack fs-4"></i>
                </div>
                <div class="text-muted fw-bold small text-uppercase ls-1 mb-1">Total (Sesuai Filter)</div>
                <h3 class="fw-extrabold text-dark mb-0">Rp {{ number_format($stats['total'], 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-premium">
                <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-calendar-check fs-4"></i>
                </div>
                <div class="text-muted fw-bold small text-uppercase ls-1 mb-1">Pemasukan Bulan Ini</div>
                <h3 class="fw-extrabold text-dark mb-0 text-primary">Rp {{ number_format($stats['this_month'], 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-premium">
                <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-lightning-charge fs-4"></i>
                </div>
                <div class="text-muted fw-bold small text-uppercase ls-1 mb-1">Pemasukan Hari Ini</div>
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
                    <i class="bi bi-filter me-2"></i> Filter
                </button>
                <a href="{{ route('finance.income.index') }}" class="btn btn-premium btn-light">
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
                        <th class="ps-4">No. Bukti / Transaksi</th>
                        <th>Rincian</th>
                        <th>Sumber / Metode</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-center">Penerima</th>
                        <th class="pe-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incomes as $income)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-light text-secondary rounded-3 p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-receipt"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">INC-{{ str_pad($income->id, 5, '0', STR_PAD_LEFT) }}</div>
                                        <div class="small text-muted">{{ $income->transaction_date->translatedFormat('d F Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="badge badge-soft-success rounded-pill mb-1">{{ $income->category }}</div>
                                <div class="small text-muted text-truncate" style="max-width: 250px;">{{ $income->description ?: 'Tanpa keterangan' }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $income->payer_name ?: 'Internal/Umum' }}</div>
                                <div class="small">
                                    @if($income->payment_method == 'transfer')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2">
                                            <i class="bi bi-bank me-1"></i> Transfer
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-2">
                                            <i class="bi bi-cash-stack me-1"></i> Tunai
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="fw-extrabold text-success" style="font-size: 1rem;">Rp {{ number_format($income->amount, 0, ',', '.') }}</div>
                            </td>
                            <td class="text-center">
                                <div class="avatar-sm d-inline-block p-1 bg-light rounded-circle mb-1" title="{{ $income->user->name ?? 'Admin' }}" style="width: 30px; height: 30px; line-height: 22px; font-size: 0.7rem; font-weight: 700;">
                                    {{ strtoupper(substr($income->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="small text-muted" style="font-size: 0.65rem;">{{ explode(' ', $income->user->name ?? 'Admin')[0] }}</div>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('finance.income.print', $income->id) }}" target="_blank" class="btn btn-sm btn-light rounded-pill px-3 shadow-none border" title="Cetak Kwitansi">
                                        <i class="bi bi-printer text-primary"></i>
                                    </a>
                                    @if(in_array(auth()->user()->role, ['administrator', 'kepala_keuangan']))
                                        <form action="{{ route('finance.income.destroy', $income->id) }}" method="POST" class="d-inline">
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
                                    <i class="bi bi-inbox display-1 text-light mb-3 d-block"></i>
                                    <h5 class="text-muted fw-bold">Belum Ada Data Pemasukan</h5>
                                    <p class="text-muted small">Coba sesuaikan filter atau tambahkan data baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($incomes->hasPages())
        <div class="card-footer bg-white py-3 border-0">
            {{ $incomes->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Add Income -->
<div class="modal fade" id="addIncomeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4 class="fw-extrabold text-dark mb-0">Catat Pemasukan</h4>
                    <p class="text-muted small mb-0">Input data penerimaan dana sekolah baru.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('finance.income.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                            <ul class="mb-0 small fw-bold">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label-premium">Diterima Dari (Sumber)</label>
                            <input type="text" name="payer_name" class="form-control form-control-premium" placeholder="Contoh: Yayasan, Donatur, atau Wali Murid" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-premium">Tanggal Penerimaan</label>
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
                            <select name="payment_method" id="payment_method" class="form-select form-control-premium" required>
                                <option value="tunai">Tunai / Cash</option>
                                <option value="transfer">Transfer Bank</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-none" id="bank_account_wrapper">
                            <label class="form-label-premium">Tujuan Rekening</label>
                            <select name="bank_account_id" class="form-select form-control-premium">
                                <option value="">Pilih Rekening...</option>
                                @foreach($bankAccounts as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->account_name }} ({{ $bank->bank_name }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-premium">Jumlah Dana (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-4 fw-bold text-muted">Rp</span>
                            <input type="text" id="amount_display" class="form-control form-control-premium rounded-start-0" placeholder="0" style="font-size: 1.5rem; font-weight: 800;" required>
                            <input type="hidden" name="amount" id="amount_raw">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label-premium text-uppercase ls-1">Keterangan / Detail</label>
                        <textarea name="description" class="form-control form-control-premium" rows="2" placeholder="Contoh: Bantuan Operasional Sekolah Tahap I atau Hibah Alat Tulis"></textarea>
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
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn fw-bold text-muted me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-premium btn-premium-primary px-5 shadow">
                        <i class="bi bi-save me-2"></i> Simpan Pemasukan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Manage Categories -->
<div class="modal fade" id="manageCategoriesModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4 class="fw-extrabold text-dark mb-0">Kelola Kategori</h4>
                    <p class="text-muted small mb-0">Tambah atau hapus kategori pemasukan.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="bg-light p-4 border-bottom">
                    <form action="{{ route('finance.income.categories.store') }}" method="POST">
                        @csrf
                        <div class="row g-2">
                            <div class="col-10">
                                <input type="text" name="name" class="form-control form-control-premium" placeholder="Ketik nama kategori baru..." required>
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-success w-100 h-100 rounded-3 shadow-sm"><i class="bi bi-plus-lg"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="p-2" style="max-height: 400px; overflow-y: auto;">
                    @foreach($categories as $cat)
                        <div class="d-flex justify-content-between align-items-center p-3 hover-bg-light rounded-3 transition-all">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="bi bi-tag-fill" style="font-size: 0.8rem;"></i>
                                </div>
                                <span class="fw-bold text-dark">{{ $cat->name }}</span>
                            </div>
                            <form action="{{ route('finance.income.categories.destroy', $cat->id) }}" method="POST">
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Simple Delete Confirmation
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
                confirmButtonColor: '#ef4444',
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

        $('.btn-delete-cat').click(function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Kategori?',
                text: "Kategori ini akan dihapus. Pastikan tidak ada data yang menggunakan kategori ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger px-4',
                    cancelButton: 'btn btn-light px-4'
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
        // Toggle Bank Account Field
        $('#payment_method').change(function() {
            if ($(this).val() === 'transfer') {
                $('#bank_account_wrapper').removeClass('d-none');
                $('#bank_account_wrapper select').attr('required', true);
            } else {
                $('#bank_account_wrapper').addClass('d-none');
                $('#bank_account_wrapper select').attr('required', false).val('');
            }
        });

        // Initial call
        $('#payment_method').trigger('change');

        // Automatic Thousand Separator (Ribuan Otomatis)
        const amountDisplay = document.getElementById('amount_display');
        const amountRaw = document.getElementById('amount_raw');

        if (amountDisplay) {
            amountDisplay.addEventListener('keyup', function(e) {
                // Formatting for display
                let cursorPosition = this.selectionStart;
                let originalLength = this.value.length;
                
                let val = this.value.replace(/\D/g, "");
                amountRaw.value = val; // Set raw value for server
                
                if (val === "") {
                    this.value = "";
                } else {
                    this.value = formatNumber(val);
                }
                
                // Adjust cursor position
                let newLength = this.value.length;
                cursorPosition = cursorPosition + (newLength - originalLength);
                this.setSelectionRange(cursorPosition, cursorPosition);
            });
        }

        function formatNumber(n) {
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // PIN Handling
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
                        showConfirmButton: false
                    });
                }
            });
        });
    });
</script>
@endpush
