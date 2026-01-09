@extends('layouts.app')

@section('title', 'Data Absensi Siswa')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Monitoring Absensi Siswa</h1>
            <p class="text-muted small mb-0">Pantau rekapitulasi kehadiran siswa berdasarkan unit dan kelas.</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm border-0 bg-white mb-4" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form action="{{ route('student-affairs.attendance-data') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Filter Unit</label>
                        <select name="unit_id" class="form-select border-0 bg-light shadow-none" onchange="this.form.submit()">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted">Tahun Pelajaran</label>
                        <select name="academic_year_id" class="form-select border-0 bg-light shadow-none" onchange="this.form.submit()">
                            <option value="">Semua T.P</option>
                            @foreach($academicYears as $ay)
                                <option value="{{ $ay->id }}" {{ $academicYearId == $ay->id ? 'selected' : '' }}>
                                    {{ $ay->name }} {{ ucfirst($ay->semester) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Filter Kelas</label>
                        <select name="class_id" class="form-select border-0 bg-light shadow-none" onchange="this.form.submit()">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted">Tanggal</label>
                        <input type="date" name="date" class="form-control border-0 bg-light shadow-none" 
                               value="{{ $date }}" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 py-2"><i class="bi bi-search me-1"></i> Cari Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card shadow-sm border-0 bg-white" style="border-radius: 15px;">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle custom-table">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Siswa</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Sataus Kehadiran</th>
                            <th>Keterangan</th>
                            <th class="text-center">Diinput Oleh</th>
                            <th class="text-center">Waktu Input</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                        <tr>
                            <td class="text-center fw-semibold text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle-sm bg-light-primary text-primary me-3">
                                        {{ substr($attendance->student->nama_lengkap ?? 'S', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $attendance->student->nama_lengkap ?? '-' }}</div>
                                        <small class="text-muted">{{ $attendance->student->nis ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-primary border">{{ $attendance->schoolClass->name ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $statusBadge = 'bg-secondary';
                                    $statusText = $attendance->status;
                                    $icon = 'bi-question-circle';
                                    
                                    switch($attendance->status) {
                                        case 'present': $statusBadge = 'bg-soft-success'; $statusText = 'Hadir'; $icon = 'bi-check-circle-fill'; break;
                                        case 'sick': $statusBadge = 'bg-soft-warning'; $statusText = 'Sakit'; $icon = 'bi-plus-circle-fill'; break;
                                        case 'permission': $statusBadge = 'bg-soft-info'; $statusText = 'Izin'; $icon = 'bi-info-circle-fill'; break;
                                        case 'alpha': $statusBadge = 'bg-soft-danger'; $statusText = 'Alpha'; $icon = 'bi-x-circle-fill'; break;
                                        case 'late': $statusBadge = 'bg-soft-secondary'; $statusText = 'Terlambat'; $icon = 'bi-clock-fill'; break;
                                        case 'school_activity': $statusBadge = 'bg-soft-primary'; $statusText = 'Kegiatan Sekolah'; $icon = 'bi-flag-fill'; break;
                                    }
                                @endphp
                                <span class="badge {{ $statusBadge }} rounded-pill px-3 py-2">
                                    <i class="bi {{ $icon }} me-1"></i> {{ $statusText }}
                                </span>
                            </td>
                            <td>
                                <span class="small text-muted italic">{{ $attendance->notes ?: '-' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="small text-dark">{{ $attendance->creator->name ?? '-' }}</span>
                            </td>
                            <td class="text-center text-muted small">
                                {{ $attendance->created_at->format('d/m/y H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state text-muted">
                                    <i class="bi bi-calendar-x fs-1 mb-3 d-block"></i>
                                    <p>Tidak ada data absensi untuk filter terpilih.</p>
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

<style>
    .custom-table thead th {
        background-color: #f8f9fc;
        border-top: none;
        border-bottom: 2px solid #e3e6f0;
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        color: #4e73df;
        padding: 15px;
    }
    .custom-table tbody td {
        padding: 18px 15px;
        border-bottom: 1px solid #f1f1f1;
    }
    .avatar-circle-sm {
        width: 35px; height: 35px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 0.9rem;
    }
    .bg-light-primary { background-color: #e8f0fe; }
    
    .bg-soft-success { background-color: #d1fae5; color: #065f46; }
    .bg-soft-warning { background-color: #fef3c7; color: #92400e; }
    .bg-soft-info { background-color: #e0f2fe; color: #075985; }
    .bg-soft-danger { background-color: #fee2e2; color: #991b1b; }
    .bg-soft-primary { background-color: #e0e7ff; color: #3730a3; }
    .bg-soft-secondary { background-color: #f3f4f6; color: #374151; }
</style>
@endsection
