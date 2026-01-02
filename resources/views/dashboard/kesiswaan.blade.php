@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard Kesiswaan</h1>

    <div class="row">
        <!-- Pending Violations Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $pendingViolationsCount }}</h3>
                    <p>Pelanggaran Menunggu Tindak Lanjut</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="{{ route('student-affairs.violations.index', ['status' => 'pending']) }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Processing Violations Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $processingViolationsCount }}</h3>
                    <p>Pelanggaran Sedang Diproses</p>
                </div>
                <div class="icon">
                    <i class="fas fa-spinner"></i>
                </div>
                <a href="{{ route('student-affairs.violations.index', ['status' => 'process']) }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Create Violation Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="small-box bg-danger">
                <div class="inner text-white">
                    <h3>Input</h3>
                    <p>Catat Pelanggaran Baru</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="{{ route('student-affairs.violations.create') }}" class="small-box-footer">
                    Input Sekarang <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Violations Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pelanggaran Terbaru</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Pelanggaran</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentViolations as $violation)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($violation->date)->format('d/m/Y') }}</td>
                            <td>{{ $violation->student->nama_lengkap }}</td>
                            <td>{{ $violation->student->schoolClass->first()->name ?? '-' }}</td>
                            <td>{{ Str::limit($violation->description, 50) }}</td>
                            <td>
                                @if($violation->follow_up_status == 'pending')
                                    <span class="badge bg-secondary">Pending</span>
                                @elseif($violation->follow_up_status == 'process')
                                    <span class="badge bg-info text-dark">Proses</span>
                                @elseif($violation->follow_up_status == 'done')
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data pelanggaran terbaru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('student-affairs.violations.index') }}" class="btn btn-primary btn-sm">Lihat Semua Pelanggaran</a>
            </div>
        </div>
    </div>
</div>
@endsection
