@extends('layouts.app')

@section('title', 'Data Kelas')

@section('content')
<div class="container-fluid">
    {{-- HEADER & ACTIONS --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-1"><i class="bi bi-bank me-2"></i>Data Kelas</h3>
            <p class="text-muted mb-0">Kelola data kelas, wali kelas, dan pembagian siswa.</p>
        </div>
        <div class="d-flex gap-2">
            @if(auth()->user()->role === 'administrator')
            <a href="{{ route('classes.mass-edit') }}" class="btn btn-outline-primary px-4 shadow-sm">
                <i class="bi bi-pencil-square me-2"></i>Update Massal
            </a>
            @endif
            @if(Auth::user()->role === 'administrator' || Auth::user()->isManajemenSekolah())
                @if(isset($activeYear) && $activeYear)
                    <a href="{{ route('classes.create') }}" class="btn btn-primary px-4 shadow-sm">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Kelas Baru
                    </a>
                @else
                    <button class="btn btn-secondary px-4 shadow-sm" disabled title="Aktifkan Tahun Ajaran terlebih dahulu">
                        <i class="bi bi-slash-circle me-2"></i>Tambah Kelas (Tahun Ajaran Tidak Aktif)
                    </button>
                @endif
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-octagon-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- FILTER SECTION --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light rounded-3">
            <form action="{{ route('classes.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold text-secondary small text-uppercase">Filter Unit</label>
                    <select name="unit_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                        <option value="">Semua Unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-secondary small text-uppercase">Tahun Ajaran</label>
                    <select name="academic_year_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                        <option value="">Tahun Aktif</option>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->status == 'active' ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 text-md-end text-muted small">
                    Menampilkan <strong>{{ $classes->count() }}</strong> kelas
                </div>
            </form>
        </div>
    </div>

    {{-- CLASSES GRID --}}
    <div class="row">
        @forelse($classes as $class)
            <div class="col-md-4 mb-4">
                <div class="card check-card h-100 border-0 shadow-sm position-relative overflow-hidden hover-shadow transition-all" style="border-radius: 12px;">
                    <div class="card-body p-4 position-relative z-1">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill small">
                                {{ $class->unit->name }}
                            </span>
                            @if($class->academicYear)
                            <span class="badge bg-info-subtle text-info fw-bold px-3 py-2 rounded-pill small ms-1">
                                {{ $class->academicYear->name }}
                            </span>
                            @endif
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('classes.show', $class->id) }}">
                                            <i class="bi bi-eye me-2 text-primary"></i> Lihat Isi (Siswa)
                                        </a>
                                    </li>
                                    @if(Auth::user()->role === 'administrator' || Auth::user()->isManajemenSekolah())
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        @if($class->academicYear && $class->academicYear->status == 'active')
                                            <a class="dropdown-item" href="{{ route('classes.edit', $class->id) }}">
                                                <i class="bi bi-pencil me-2 text-warning"></i> Edit Kelas
                                            </a>
                                        @else
                                            <span class="dropdown-item text-muted">
                                                <i class="bi bi-lock me-2"></i> Edit Terkunci
                                            </span>
                                        @endif
                                    </li>
                                    @endif
                                    @if(auth()->user()->role === 'administrator')
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        @if($class->academicYear && $class->academicYear->status == 'active')
                                            <form action="{{ route('classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kelas {{ $class->name }}? Data siswa di dalamnya akan di-reset.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i> Hapus
                                                </button>
                                            </form>
                                        @else
                                            <button class="dropdown-item text-muted" disabled>
                                                <i class="bi bi-trash me-2"></i> Hapus Terkunci
                                            </button>
                                        @endif
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <h4 class="fw-bold mb-1 text-dark">
                            {{ $class->name }}
                            @if($class->academicYear && $class->academicYear->status != 'active')
                                <span class="badge bg-secondary ms-2" style="font-size: 0.6em;">ARSIP</span>
                            @endif
                        </h4>
                        <div class="d-flex align-items-center mb-4">
                             @if($class->teacher)
                                <div class="badge bg-warning-subtle text-warning me-2 p-1 rounded-circle">
                                     <i class="bi bi-person theme-icon-active"></i>
                                </div>
                                <span class="small text-muted fw-bold">{{ $class->teacher->name }}</span>
                             @else
                                <span class="badge bg-danger-subtle text-danger small">
                                    <i class="bi bi-exclamation-circle me-1"></i> Belum ada Walas
                                </span>
                             @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top border-light">
                            <div>
                                <h2 class="mb-0 fw-bold text-primary">{{ $class->student_history_count }}</h2>
                                <small class="text-muted text-uppercase" style="font-size: 10px; letter-spacing: 1px;">Total Siswa</small>
                            </div>
                            <a href="{{ route('classes.show', $class->id) }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-primary">
                                <i class="bi bi-eye me-1"></i> Detail Isi Kelas
                            </a>
                        </div>
                    </div>
                    
                    {{-- Decorative Background Circle --}}
                    <div class="position-absolute bg-primary opacity-10 rounded-circle" style="width: 150px; height: 150px; top: -50px; right: -50px; z-index: 0;"></div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-bank display-1 text-muted opacity-25"></i>
                </div>
                <h5 class="text-muted fw-bold">Belum ada data kelas</h5>
                <p class="text-secondary small mb-4">Silakan tambahkan kelas baru untuk memulai.</p>
                <a href="{{ route('classes.create') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Kelas
                </a>
            </div>
        @endforelse
    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endsection
