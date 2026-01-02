@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
    }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
    }

    .absensi-hero {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        border-radius: 2rem;
        padding: 3rem 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .absensi-hero::after {
        content: '';
        position: absolute;
        bottom: -20%;
        right: -5%;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        border-radius: 1.5rem;
    }

    .stat-box {
        padding: 1.5rem;
        border-radius: 1.25rem;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .stat-box:hover {
        transform: translateY(-5px);
    }

    .status-badge {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.4rem 0.8rem;
        border-radius: 0.5rem;
    }

    .table-custom thead th {
        background: transparent;
        border-bottom: 2px solid #f1f5f9;
        color: #64748b;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .table-custom tbody td {
        vertical-align: middle;
        padding: 1rem;
        color: #1e293b;
        font-weight: 500;
    }

    .pagination .page-link {
        border-radius: 0.5rem;
        margin: 0 2px;
        border: none;
        color: #64748b;
    }

    .pagination .page-item.active .page-link {
        background: #10b981;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }
</style>
@endpush

@section('content')
<div class="app-content pt-4">
    <div class="container-xl">
        {{-- Hero Header --}}
        <div class="absensi-hero shadow-lg">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6 class="text-uppercase fw-bold ls-1 opacity-75 mb-2">Presensi Mandiri</h6>
                    <h1 class="display-5 fw-extrabold mb-0">Disiplin adalah <br> Jembatan Menuju Sukses.</h1>
                </div>
                <div class="col-md-4 text-md-end d-none d-md-block">
                    <i class="bi bi-calendar-check-fill display-1 opacity-25"></i>
                </div>
            </div>
        </div>

        {{-- Filters & Summary row --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-12">
                <div class="glass-card p-4">
                    <form action="{{ route('siswa.absensi') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Tahun Ajaran</label>
                            <select name="academic_year_id" class="form-select border-0 bg-light rounded-3" onchange="this.form.submit()">
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ $academicYearId == $ay->id ? 'selected' : '' }}>{{ $ay->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Bulan</label>
                            <select name="month" class="form-select border-0 bg-light rounded-3" onchange="this.form.submit()">
                                @for($m=1; $m<=12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted text-uppercase">Tahun</label>
                            <select name="year" class="form-select border-0 bg-light rounded-3" onchange="this.form.submit()">
                                @for($y=date('Y'); $y>=date('Y')-2; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            {{-- Stat Cards --}}
            @php
                $stats = [
                    ['label' => 'Hadir', 'key' => 'present', 'color' => '#10b981', 'bg' => 'rgba(16, 185, 129, 0.1)', 'icon' => 'bi-check-circle-fill'],
                    ['label' => 'Sakit', 'key' => 'sick', 'color' => '#3b82f6', 'bg' => 'rgba(59, 130, 246, 0.1)', 'icon' => 'bi-plus-circle-fill'],
                    ['label' => 'Izin', 'key' => 'permission', 'color' => '#f59e0b', 'bg' => 'rgba(245, 158, 11, 0.1)', 'icon' => 'bi-info-circle-fill'],
                    ['label' => 'Alfa', 'key' => 'alpha', 'color' => '#ef4444', 'bg' => 'rgba(239, 68, 68, 0.1)', 'icon' => 'bi-x-circle-fill'],
                    ['label' => 'Terlambat', 'key' => 'late', 'color' => '#6366f1', 'bg' => 'rgba(99, 102, 241, 0.1)', 'icon' => 'bi-clock-fill']
                ];
            @endphp

            @foreach($stats as $stat)
            <div class="col-md col-6">
                <div class="stat-box" style="background: {{ $stat['bg'] }};">
                    <i class="bi {{ $stat['icon'] }} h3 d-block mb-2" style="color: {{ $stat['color'] }};"></i>
                    <h2 class="fw-bold mb-0" style="color: {{ $stat['color'] }};">{{ $summary[$stat['key']] }}</h2>
                    <span class="small fw-bold opacity-75 text-uppercase" style="color: {{ $stat['color'] }};">{{ $stat['label'] }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="glass-card p-4 overflow-hidden">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Riwayat Presensi - {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}</h5>
                        <span class="badge bg-light text-muted rounded-pill px-3">{{ $attendances->count() }} Records</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom border-0">
                            <thead>
                                <tr>
                                    <th class="border-0">Tanggal</th>
                                    <th class="border-0">Hari</th>
                                    <th class="border-0 text-center">Status</th>
                                    <th class="border-0">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                <tr>
                                    <td class="fw-bold">{{ $attendance->date->translatedFormat('d F Y') }}</td>
                                    <td class="text-muted">{{ $attendance->date->translatedFormat('l') }}</td>
                                    <td class="text-center">
                                        @php
                                            $badgeClass = 'bg-secondary';
                                            $label = $attendance->status;
                                            switch($attendance->status) {
                                                case 'present': $badgeClass = 'bg-success bg-opacity-10 text-success'; $label = 'Hadir'; break;
                                                case 'sick': $badgeClass = 'bg-primary bg-opacity-10 text-primary'; $label = 'Sakit'; break;
                                                case 'permission': $badgeClass = 'bg-warning bg-opacity-10 text-warning'; $label = 'Izin'; break;
                                                case 'alpha': $badgeClass = 'bg-danger bg-opacity-10 text-danger'; $label = 'Alfa'; break;
                                                case 'late': $badgeClass = 'bg-info bg-opacity-10 text-info'; $label = 'Terlambat'; break;
                                                case 'school_activity': $badgeClass = 'bg-dark bg-opacity-10 text-dark'; $label = 'Kegiatan'; break;
                                            }
                                        @endphp
                                        <span class="status-badge {{ $badgeClass }}">{{ $label }}</span>
                                    </td>
                                    <td class="text-muted small italic">{{ $attendance->notes ?: '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="opacity-25 pb-3">
                                            <i class="bi bi-calendar-x display-1"></i>
                                        </div>
                                        <h6 class="fw-bold text-muted">Belum ada data presensi untuk bulan ini.</h6>
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
@endsection
