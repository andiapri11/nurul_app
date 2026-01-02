@extends('layouts.app')

@section('title', 'Detail Kelas ' . $class->name)

@section('content')
<div class="container-fluid">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-1"><i class="bi bi-bank me-2"></i>Detail Kelas: {{ $class->name }}</h3>
            <p class="text-muted mb-0">Tahun Pelajaran: {{ $class->academicYear ? $class->academicYear->name : '-' }} 
                @if($class->academicYear && $class->academicYear->status != 'active')
                    <span class="badge bg-secondary ms-2 small">ARSIP</span>
                @else
                    <span class="badge bg-success ms-2 small">AKTIF</span>
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
            @if((Auth::user()->role === 'administrator' || Auth::user()->isManajemenSekolah()) && ($class->academicYear && $class->academicYear->status == 'active'))
                <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-warning px-4 shadow-sm text-white">
                    <i class="bi bi-pencil me-2"></i>Edit Data Kelas
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        {{-- Class Info Card --}}
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-primary py-3">
                    <h6 class="m-0 font-weight-bold text-white"><i class="bi bi-info-circle me-2"></i>Informasi Umum</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted small text-uppercase fw-bold">Unit</span>
                            <span class="fw-bold text-dark">{{ $class->unit->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted small text-uppercase fw-bold">Level</span>
                            <span class="fw-bold text-dark">{{ $class->grade_code }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted small text-uppercase fw-bold">Kode</span>
                            <span class="fw-bold text-dark">{{ $class->code ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <span class="text-muted small text-uppercase fw-bold">Wali Kelas</span>
                            <span class="fw-bold text-primary">{{ $class->teacher ? $class->teacher->name : 'Belum ditentukan' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 bg-light">
                            <span class="text-muted small text-uppercase fw-bold">Total Siswa</span>
                            <span class="h4 mb-0 fw-black text-primary">{{ $class->studentHistory->count() }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Structure Card --}}
            <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-warning py-3">
                    <h6 class="m-0 font-weight-bold text-white"><i class="bi bi-person-badge me-2"></i>Struktur Kelas</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary-subtle p-3 rounded-circle me-3">
                            <i class="bi bi-person-workspace text-primary h4 mb-0"></i>
                        </div>
                        <div>
                            <small class="text-muted text-uppercase fw-bold ls-1" style="font-size: 0.7rem;">Wali Kelas</small>
                            <h6 class="fw-bold mb-0 text-dark">{{ $class->teacher ? $class->teacher->name : '-' }}</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-success-subtle p-3 rounded-circle me-3">
                            <i class="bi bi-megaphone text-success h4 mb-0"></i>
                        </div>
                        <div>
                            <small class="text-muted text-uppercase fw-bold ls-1" style="font-size: 0.7rem;">Ketua Kelas</small>
                            <h6 class="fw-bold mb-0 text-dark">{{ $class->leader ? $class->leader->nama_lengkap : 'Belum ditentukan' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Student List Card --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold text-dark"><i class="bi bi-people me-2"></i>Daftar Siswa</h5>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3">{{ $class->studentHistory->count() }} Siswa</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 text-muted small text-uppercase border-0" style="width: 80px">No</th>
                                    <th class="py-3 text-muted small text-uppercase border-0" style="width: 150px">NIS</th>
                                    <th class="py-3 text-muted small text-uppercase border-0">Nama Lengkap</th>
                                    <th class="py-3 text-muted small text-uppercase border-0 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($class->studentHistory as $index => $student)
                                <tr>
                                    <td class="px-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td><span class="badge bg-light text-dark fw-bold border">{{ $student->nis }}</span></td>
                                    <td class="fw-bold text-dark">{{ $student->nama_lengkap }}</td>
                                    <td class="text-center">
                                        @if($student->status == 'aktif')
                                            <span class="badge bg-success-subtle text-success border border-success px-3 py-1 small rounded-pill">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary px-3 py-1 small rounded-pill">{{ ucfirst($student->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-people display-4 opacity-25 d-block mb-3"></i>
                                            Belum ada siswa yang terdaftar di kelas ini.
                                        </div>
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
    .ls-1 { letter-spacing: 1px; }
    .bg-primary-subtle { background-color: #e7f1ff; }
    .bg-success-subtle { background-color: #e1f7ec; }
    .fw-black { font-weight: 900; }
</style>
@endsection
