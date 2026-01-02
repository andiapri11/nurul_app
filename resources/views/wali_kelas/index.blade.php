@extends('layouts.app')

@section('title', 'Dashboard Wali Kelas')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">Kelas: {{ $myClass->name }}</h3>
                        <p class="mb-0">{{ $myClass->unit->name }} | {{ $myClass->academicYear ? $myClass->academicYear->name : 'Tahun ?' }}</p>
                    </div>
                    <div class="text-end">
                        <i class="bi bi-person-workspace fs-1 d-block"></i>
                        @if(Auth::user()->role === 'administrator')
                        <a href="{{ route('wali-kelas.index', ['reset' => 1]) }}" class="btn btn-sm btn-light text-primary fw-bold mt-2">
                            <i class="bi bi-arrow-repeat"></i> Ganti Kelas
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($notInputted == $totalStudents && $totalStudents > 0)
    <div class="alert alert-warning border-start border-warning border-4 shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
            <div>
                <h5 class="alert-heading fw-bold mb-1">Perhatian! Absensi Belum Diisi</h5>
                <p class="mb-0">Anda belum melakukan input absensi untuk tanggal hari ini ({{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}). 
                <a href="{{ route('wali-kelas.attendance') }}" class="alert-link">Klik di sini untuk mengisi absensi.</a></p>
            </div>
        </div>
    </div>
    @elseif($notInputted > 0)
    <div class="alert alert-info border-start border-info border-4 shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-info-circle-fill fs-3 me-3"></i>
            <div>
                <h5 class="alert-heading fw-bold mb-1">Absensi Belum Lengkap</h5>
                <p class="mb-0">Masih ada {{ $notInputted }} siswa yang belum diabsen hari ini. 
                <a href="{{ route('wali-kelas.attendance') }}" class="alert-link">Lengkapi sekarang.</a></p>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Ringkasan Hari Ini</h5>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</small>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Siswa
                            <span class="badge bg-primary rounded-pill">{{ $totalStudents }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Hadir (Present)
                            <span class="badge bg-success rounded-pill">{{ $attendanceStats['present'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Kegiatan (Activity)
                            <span class="badge bg-primary rounded-pill">{{ $attendanceStats['school_activity'] ?? 0 }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Sakit (Sick)
                            <span class="badge bg-info rounded-pill">{{ $attendanceStats['sick'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Izin (Permission)
                            <span class="badge bg-warning rounded-pill">{{ $attendanceStats['permission'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Alpa (Alpha)
                            <span class="badge bg-danger rounded-pill">{{ $attendanceStats['alpha'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            Belum Diabsen
                            <span class="badge bg-secondary rounded-pill">{{ $notInputted }}</span>
                        </li>
                    </ul>
                    <div class="mt-3 d-grid gap-2">
                        <a href="{{ route('wali-kelas.attendance') }}" class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i> Input Absensi Hari Ini
                        </a>
                        <a href="{{ route('wali-kelas.report') }}" class="btn btn-outline-primary">
                            <i class="bi bi-journal-text"></i> Laporan Absensi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Placeholder for chart or list of recent activities -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Informasi Kelas</h5>
                </div>
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-graph-up fs-1"></i>
                    <p class="mt-2">Grafik kehadiran mingguan akan muncul di sini.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
