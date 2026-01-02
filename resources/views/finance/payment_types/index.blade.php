@extends('layouts.app')

@section('title', 'Atur Jenis Pembayaran')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Jenis Pembayaran</h3>
            <p class="text-muted small mb-0">Kelola master data pos pembayaran untuk setiap unit sekolah.</p>
        </div>
        <div class="d-none d-md-block">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Keuangan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Jenis Pembayaran</li>
                </ol>
            </nav>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show rounded-3 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Modern Form Section -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                            <i class="bi bi-plus-lg fs-4 fw-bold"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Buat Baru</h5>
                    </div>

                    <form action="{{ route('finance.payments.settings.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-floating mb-3">
                            <input type="text" name="code" class="form-control bg-light border-0" id="floatingCode" placeholder="Kode">
                            <label for="floatingCode">Kode (Opsional)</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control bg-light border-0" id="floatingName" placeholder="Nama" required>
                            <label for="floatingName">Nama Pembayaran</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select name="unit_id" class="form-select bg-light border-0" id="floatingUnit">
                                <option value="">Semua Unit (Global)</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                            <label for="floatingUnit">Unit Sekolah</label>
                        </div>

                        <div class="form-floating mb-4">
                            <select name="type" class="form-select bg-light border-0" id="floatingType" required>
                                <option value="monthly">Bulanan (SPP)</option>
                                <option value="one_time">Sekali Bayar (Tahunan/Insidental)</option>
                            </select>
                            <label for="floatingType">Tipe Pembayaran</label>
                        </div>

                        <div class="alert alert-primary bg-primary bg-opacity-10 border-0 rounded-3 small text-primary mb-4 p-3">
                            <div class="d-flex">
                                <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                                <div>
                                    <strong>Catatan:</strong><br>
                                    Nominal tarif (harga) diatur secara spesifik pada menu 
                                    <a href="{{ route('finance.student-fees.index') }}" class="fw-bold text-decoration-underline text-primary">Atur Pembayaran Siswa</a>.
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm transition-hover">
                            <i class="bi bi-plus-circle me-2"></i> SIMPAN JENIS
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Modern List Section -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom border-light py-3 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold m-0 text-dark">Data Jenis Pembayaran</h6>
                    
                    <!-- Integrated Filter -->
                    <form action="{{ route('finance.payment-types.index') }}" method="GET" class="d-flex align-items-center">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted ps-3 py-2 rounded-start-pill"><i class="bi bi-funnel"></i></span>
                            <select name="unit_id" class="form-select border-start-0 py-2 pe-4 rounded-end-pill form-select-sm" onchange="this.form.submit()" style="min-width: 150px; box-shadow: none;">
                                <option value="">Semua Unit</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}" {{ $selectedUnitId == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3" width="15%">Kode</th>
                                    <th class="py-3" width="35%">Nama Pembayaran</th>
                                    <th class="py-3" width="20%">Unit</th>
                                    <th class="py-3" width="20%">Tipe</th>
                                    <th class="pe-4 py-3 text-end" width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentTypes as $t)
                                    <tr>
                                        <td class="ps-4">
                                            @if($t->code)
                                                <span class="badge bg-dark bg-opacity-10 text-dark font-monospace rounded px-2 py-1 border border-secondary border-opacity-25">{{ $t->code }}</span>
                                            @else
                                                <span class="text-muted small fst-italic">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $t->name }}</div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill {{ $t->unit_id ? 'bg-light text-secondary border' : 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25' }} px-3 py-2 fw-normal">
                                                <i class="bi {{ $t->unit_id ? 'bi-building' : 'bi-globe' }} me-1"></i> {{ $t->unit->name ?? 'Semua Unit' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($t->type == 'monthly') 
                                                <span class="badge rounded-pill bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2">
                                                    <i class="bi bi-calendar-event me-1"></i> Bulanan
                                                </span>
                                            @else 
                                                <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2">
                                                    <i class="bi bi-lightning-charge me-1"></i> Sekali Bayar
                                                </span> 
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border-0 rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                                    <li>
                                                        <a class="dropdown-item py-2 small" href="{{ route('finance.payment-types.edit', $t->id) }}">
                                                            <i class="bi bi-pencil me-2 text-primary"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('finance.payment-types.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus jenis pembayaran {{ $t->name }}?');">
                                                            @csrf @method('DELETE')
                                                            <button class="dropdown-item py-2 small text-danger">
                                                                <i class="bi bi-trash me-2"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="mb-3">
                                                <div class="bg-light rounded-circle d-inline-flex p-4">
                                                    <i class="bi bi-inbox fs-1 text-muted opacity-50"></i>
                                                </div>
                                            </div>
                                            <h6 class="text-secondary fw-normal">Belum ada data jenis pembayaran.</h6>
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

<style>
    .transition-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .form-floating > .form-control:focus, .form-floating > .form-control:not(:placeholder-shown) {
        padding-top: 1.625rem;
        padding-bottom: .625rem;
    }
    .form-floating > .form-control:-webkit-autofill {
        padding-top: 1.625rem;
        padding-bottom: .625rem;
    }
</style>
@endsection
