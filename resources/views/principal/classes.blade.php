@extends('layouts.app')

@section('title', 'Statistik Kelas')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 fw-bold text-dark">Manajemen Kelas Unit</h1>
                <p class="text-muted mb-0">Overview kapasitas dan wali kelas.</p>
            </div>
            <div class="col-sm-6">
                <form action="{{ route('principal.class-stats') }}" method="GET" class="float-sm-end mt-2 mt-sm-0 d-flex gap-2">
                    <select name="academic_year_id" class="form-select border-0 shadow-sm px-3 py-2" style="border-radius: 12px; min-width: 200px;" onchange="this.form.submit()">
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->status == 'active' ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <select name="unit_id" class="form-select border-0 shadow-sm px-3 py-2" style="border-radius: 12px; min-width: 200px;" onchange="this.form.submit()">
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row g-4">
            @forelse($classes as $class)
                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm" style="border-radius: 20px; transition: transform 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='none'">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="p-3 bg-primary-subtle text-primary rounded-4" style="background: #eef2ff;">
                                    <i class="bi bi-door-open fs-4"></i>
                                </div>
                                <span class="badge bg-light text-primary border border-primary-subtle rounded-pill px-3">Grade {{ $class->grade_code }}</span>
                            </div>
                            <h4 class="fw-bold mb-1">{{ $class->name }}</h4>
                            <p class="text-muted small mb-4">Kode Kelas: {{ $class->code }}</p>
                            
                            <div class="bg-light rounded-4 p-3 mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-people-fill text-muted me-2"></i>
                                        <span class="small text-muted">Jumlah Siswa</span>
                                    </div>
                                    <span class="badge bg-white text-dark shadow-sm border rounded-pill">{{ $class->students_count }} Siswa</span>
                                </div>
                            </div>
                            
                            {{-- Student List (Internal Scroll) --}}
                            <div class="mb-3">
                                <h6 class="text-muted fw-bold small mb-2 text-uppercase">Daftar Siswa ({{ $class->students_count }})</h6>
                                <div class="bg-white border rounded ps-2 pe-1 py-2" style="max-height: 150px; overflow-y: auto;">
                                    @if($class->students->isEmpty())
                                        <div class="text-center text-muted small fst-italic py-2">Belum ada siswa</div>
                                    @else
                                        <ul class="list-unstyled mb-0 px-2">
                                        @foreach($class->students as $student)
                                            <li class="d-flex align-items-center mb-2 border-bottom pb-1 small">
                                                <i class="bi bi-person-fill text-secondary me-2"></i>
                                                <span class="text-dark">{{ $student->nama_lengkap }}</span>
                                            </li>
                                        @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex align-items-center border-top pt-3">
                                <div class="avatar-xs bg-secondary-subtle rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; background: #f8fafc;">
                                    <i class="bi bi-person text-secondary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">Wali Kelas</small>
                                    <span class="small fw-bold">{{ $class->teacher->name ?? 'Belum Ditentukan' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-white rounded-5 shadow-sm d-inline-block">
                        <i class="bi bi-inbox text-muted fs-1 d-block mb-3"></i>
                        <h5 class="text-muted">Tidak ada data kelas ditemukan</h5>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
