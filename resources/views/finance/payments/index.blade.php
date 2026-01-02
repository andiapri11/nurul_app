@extends('layouts.app')

@section('title', 'POS Pembayaran Siswa')

@section('content')
<div class="container-fluid py-3">
    <!-- POS Header -->
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-display text-primary me-2"></i> POS PEMBAYARAN SISWA</h4>
            <p class="text-muted small mb-0">Manajemen Penerimaan Iuran & Tagihan Siswa</p>
        </div>
        <div class="col-md-6 text-end">
            <!-- Space for future global actions -->
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-3 rounded-3 d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 h5 mb-0"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif


    <!-- Dynamic POS Filter & Search Section -->
    <div class="row justify-content-center mb-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-body p-4">
                    <form action="{{ route('finance.payments.index') }}" method="GET" id="posFilterForm">
                        <!-- Row 1: Filters (Academic Year, Unit, Class) -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="x-small fw-bold text-muted text-uppercase mb-2 d-block">TAHUN AJARAN</label>
                                <select name="academic_year_id" class="form-select border-0 bg-light fw-bold py-2 rounded-3 shadow-sm" onchange="this.form.submit()">
                                    @foreach($academicYears as $y)
                                        <option value="{{ $y->id }}" {{ $selectedYearId == $y->id ? 'selected' : '' }}>{{ $y->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="x-small fw-bold text-muted text-uppercase mb-2 d-block">UNIT SEKOLAH</label>
                                <select name="unit_id" class="form-select border-0 bg-light fw-bold py-2 rounded-3 shadow-sm" onchange="this.form.submit()">
                                    <option value="">-- SEMUA UNIT --</option>
                                    @foreach($units as $u)
                                        <option value="{{ $u->id }}" {{ $selectedUnitId == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="x-small fw-bold text-muted text-uppercase mb-2 d-block">KELAS</label>
                                <select name="class_id" class="form-select border-0 bg-light fw-bold py-2 rounded-3 shadow-sm" onchange="this.form.submit()">
                                    <option value="">-- SEMUA KELAS --</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Row 2: Large Search Student -->
                        <div class="row justify-content-center">
                            <div class="col-md-10">
                                <div class="input-group input-group-lg border rounded-pill overflow-hidden shadow-sm bg-light">
                                    <span class="input-group-text bg-transparent border-0 ps-4">
                                        <i class="bi bi-search text-primary h4 mb-0"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-0 bg-transparent fw-bold py-3" 
                                           placeholder="CARI NAMA SISWA ATAU NIS..." value="{{ $search }}"
                                           style="font-size: 1.1rem; letter-spacing: 0.5px;">
                                    <button type="submit" class="btn btn-primary px-5 fw-bold text-uppercase">
                                        CARI SISWA
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            @if($search || $selectedUnitId || $selectedClassId)
                <div class="text-center mt-3">
                    <a href="{{ route('finance.payments.index') }}" class="btn btn-link text-muted small fw-bold text-decoration-none">
                        <i class="bi bi-x-circle me-1"></i> RESET SEMUA FILTER & PENCARIAN
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-3">
        <!-- Student Grid -->
        <div class="col-12">
            @if($students->isNotEmpty())
                <div class="row g-3 row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4">
                    @foreach($students as $student)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm student-card transition-all" style="border-radius: 15px; overflow: hidden;">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-squircle bg-primary shadow-sm d-flex align-items-center justify-content-center overflow-hidden position-relative" 
                                                 style="width: 55px; height: 55px; border-radius: 18px; border: 2px solid #fff;">
                                                @if($student->user && $student->user->photo)
                                                    <img src="{{ asset('photos/' . $student->user->photo) }}" 
                                                         class="position-absolute w-100 h-100" 
                                                         style="object-fit: cover; top: 0; left: 0; z-index: 2;"
                                                         onerror="this.style.display='none'">
                                                    <i class="bi bi-person-fill text-white h3 mb-0 position-absolute" style="z-index: 1;"></i>
                                                @else
                                                    <i class="bi bi-person-fill text-white h3 mb-0"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="overflow-hidden">
                                            <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $student->nama_lengkap }}</h6>
                                            <div class="d-flex align-items-center">
                                                <p class="text-muted small mb-0 me-2">{{ $student->nis }}</p>
                                                @if($student->status == 'aktif')
                                                    <span class="badge bg-success-soft text-success x-small px-2 py-0" style="font-size: 0.6rem; background: rgba(25, 135, 84, 0.1);">AKTIF</span>
                                                @elseif($student->status == 'alumni')
                                                    <span class="badge bg-info-soft text-info x-small px-2 py-0" style="font-size: 0.6rem; background: rgba(13, 202, 240, 0.1);">ALUMNI</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-light p-2 rounded-3 mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="x-small text-muted fw-bold">UNIT</span>
                                            <span class="x-small fw-bold text-dark">{{ $student->unit->name ?? '-' }}</span>
                                        </div>
                                        @php
                                            $activeClass = $student->classes->where('pivot.academic_year_id', $selectedYearId)->first();
                                            $otherClasses = $student->classes->where('pivot.academic_year_id', '!=', $selectedYearId);
                                        @endphp
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="x-small text-muted fw-bold">KELAS</span>
                                            <span class="x-small fw-bold text-dark">{{ $activeClass->name ?? ($student->classes->first()->name ?? '-') }}</span>
                                        </div>
                                        @if($otherClasses->isNotEmpty())
                                        <div class="mt-2 pt-2 border-top border-secondary-subtle">
                                            <span class="x-small text-muted fw-bold d-block mb-1">HISTORI KELAS</span>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($otherClasses as $hc)
                                                    <span class="badge bg-secondary-soft text-secondary border x-small py-0 px-2" style="font-size: 0.55rem; background: rgba(108, 117, 125, 0.05); border-color: rgba(108, 117, 125, 0.2) !important;">
                                                        {{ $hc->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <a href="{{ route('finance.payments.show', ['student' => $student->id, 'academic_year_id' => $selectedYearId]) }}" class="btn btn-outline-primary w-100 btn-sm fw-bold rounded-pill py-2">
                                        <i class="bi bi-cash-stack me-1"></i> PILIH SISWA
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $students->links() }}
                </div>
            @else
                <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px;">
                    <div class="card-body">
                        <i class="bi bi-person-x display-3 text-light"></i>
                        <h5 class="fw-bold text-muted mt-3">Tidak Ada Siswa Ditemukan</h5>
                        <p class="text-muted small">Coba ubah filter atau kata kunci pencarian Anda.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .student-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important; border: 1px solid rgba(13, 110, 253, 0.2) !important; }
    .transition-all { transition: all 0.3s ease; }
    .x-small { font-size: 0.7rem; }
    .avatar-circle { transition: all 0.3s ease; }
    .student-card:hover .avatar-circle { background: #0d6efd !important; color: #fff !important; }
    .pagination { margin-bottom: 0; }
    .page-link { border-radius: 10px !important; margin: 0 3px; border: none; background: #fff; color: #6c757d; font-weight: 600; }
    .page-item.active .page-link { background: #0d6efd; color: #fff; }
</style>
@endsection
