@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
{{-- Custom Premium CSS --}}
@push('styles')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
        border-radius: 24px;
        padding: 40px;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(13, 110, 253, 0.2);
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
        pointer-events: none; /* Prevent blocking clicks */
    }
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    .welcome-avatar {
        width: 64px;
        height: 64px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 800;
        backdrop-filter: blur(10px);
        margin-right: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .welcome-avatar .inner {
        width: 40px;
        height: 40px;
        background: white;
        color: #0d6efd;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .date-pill {
        background: rgba(0, 0, 0, 0.25);
        backdrop-filter: blur(15px);
        border-radius: 50px;
        padding: 10px 22px;
        display: flex;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, 0.15);
        transition: all 0.3s ease;
    }
    .action-pill {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        padding: 6px 18px;
        display: flex;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        height: 46px;
    }
    .action-pill:hover {
        background: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    .icon-box {
        width: 36px;
        height: 36px;
        background: #ffc107;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        color: #000;
        font-size: 1.1rem;
        box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3);
    }
    #toggle-monitoring-btn {
        white-space: nowrap;
        height: 46px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    #toggle-monitoring-btn.btn-warning {
        background: #ffc107;
        color: #000;
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
    }
    #toggle-monitoring-btn.btn-warning:hover {
        background: #ffca2c;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4);
    }
    #toggle-monitoring-btn.btn-light {
        background: rgba(255, 255, 255, 0.95);
        color: #0d6efd;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    #toggle-monitoring-btn.btn-light:hover {
        background: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    /* Metric Card Styles */
    .metric-card {
        border-radius: 20px;
        overflow: hidden;
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .metric-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 20px 40px -15px rgba(0,0,0,0.3);
    }
    .metric-icon-bg {
        position: absolute;
        right: -10px;
        bottom: -20px;
        font-size: 8rem;
        opacity: 0.15;
        transform: rotate(-15deg);
        transition: transform 0.5s ease;
    }
    .metric-card:hover .metric-icon-bg {
        transform: rotate(0deg) scale(1.1);
        opacity: 0.2;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-lg-4">
    <!-- Modern Header Banner -->
    <div class="welcome-banner mt-3">
        <div class="row align-items-center">
            <div class="col-md-8 d-flex align-items-center">
                <div class="welcome-avatar shadow-sm">
                    @if(auth()->user()->photo)
                        <img src="{{ asset('photos/' . auth()->user()->photo) }}" alt="Profile" class="w-100 h-100 object-fit-cover shadow-sm" style="border-radius: 16px;">
                    @else
                        <div class="inner">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                    @endif
                </div>
                <div>
                    <span class="badge bg-white text-dark fw-bold text-uppercase mb-2 ls-1" style="letter-spacing: 1px;">Administrator Console</span>
                    <h2 class="fw-extrabold mb-1 text-white">Halo, {{ Auth::user()->name }}!</h2>
                    <p class="mb-0 text-white-50" id="welcome-text">Kelola operasional dan pantau grafik pertumbuhan sekolah Anda hari ini.</p>
                </div>
            </div>
            <div class="col-md-4 d-flex flex-column align-items-md-end gap-3 mt-3 mt-md-0">
                <div class="date-pill shadow-sm">
                    <div class="icon-box">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div>
                        <div class="fw-bold lh-1 text-white">{{ now()->translatedFormat('d F Y') }}</div>
                        <div class="small fw-bold text-white-50">{{ now()->translatedFormat('l') }}</div>
                    </div>
                </div>
                
                <div class="d-flex align-items-center gap-3">
                    {{-- Unit Selector --}}
                    <form action="{{ route('dashboard') }}" method="GET" class="action-pill m-0">
                        <i class="bi bi-building text-primary me-2"></i>
                        <select name="unit_id" class="form-select border-0 bg-transparent text-dark shadow-none fw-bold p-0" style="min-width: 130px; cursor: pointer; font-size: 0.85rem;" onchange="this.form.submit()">
                            <option value="all" {{ $selectedUnitId == 'all' ? 'selected' : '' }} class="text-dark">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }} class="text-dark">
                                    Unit: {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <button type="button" id="toggle-monitoring-btn" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2 hover-lift">
                        <i class="bi bi-display fs-5"></i>
                        <span class="monitoring-text">Mode Monitoring</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

<section class="content" style="padding-top: 1rem;">
    <div class="container-fluid px-4">
        
        {{-- WRAPPER FOR TOGGLE --}}
        <div id="dashboard-main-view">
            {{-- SECTION 1: KEY METRICS --}}
            <div class="row g-4 mb-5" style="margin-top: -3.5rem;">
                {{-- Card 1: Siswa --}}
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card metric-card h-100 bg-white border-0 shadow-sm overflow-hidden">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="p-3 rounded-4 bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-people-fill fs-3"></i>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold small">
                                    <i class="bi bi-graph-up-arrow me-1"></i> Aktif
                                </span>
                            </div>
                            <h6 class="text-muted text-uppercase fw-bold small mb-1 ls-1">Total Siswa</h6>
                            <h2 class="mb-0 fw-bold display-6 text-dark">{{ number_format($totalStudents) }}</h2>
                            <i class="bi bi-people-fill metric-icon-bg text-primary opacity-05"></i>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Guru --}}
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card metric-card h-100 bg-white border-0 shadow-sm overflow-hidden">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="p-3 rounded-4 bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-person-badge-fill fs-3"></i>
                                </div>
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 fw-bold small">
                                    {{ $activeTeachers }} Online
                                </span>
                            </div>
                            <h6 class="text-muted text-uppercase fw-bold small mb-1 ls-1">Guru & Staff</h6>
                            <h2 class="mb-0 fw-bold display-6 text-dark">{{ number_format($totalTeachers) }}</h2>
                            <i class="bi bi-person-badge metric-icon-bg text-success opacity-05"></i>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Kelas --}}
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card metric-card h-100 bg-white border-0 shadow-sm overflow-hidden">
                        <div class="card-body p-4 position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="p-3 rounded-4 bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-building fs-3"></i>
                                </div>
                            </div>
                            <h6 class="text-muted text-uppercase fw-bold small mb-1 ls-1">Total Kelas</h6>
                            <h2 class="mb-0 fw-bold display-6 text-dark">{{ $totalClasses }}</h2>
                            <p class="text-muted small mb-0 mt-2">{{ $studentsPerUnit->count() }} Unit Pendidikan</p>
                            <i class="bi bi-building metric-icon-bg text-warning opacity-05"></i>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Tahun Ajaran --}}
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card metric-card h-100 border-0 shadow-sm bg-dark overflow-hidden">
                        <div class="card-body p-4 position-relative z-1 text-white">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="p-3 rounded-4 bg-white bg-opacity-10 text-warning">
                                    <i class="bi bi-calendar2-check-fill fs-3"></i>
                                </div>
                            </div>
                            <h6 class="text-white-50 text-uppercase fw-bold small mb-1 ls-1">Tahun Ajaran</h6>
                            <h3 class="mb-0 fw-bold text-warning">{{ $activeYear->name ?? 'Belum Aktif' }}</h3>
                            <a href="{{ route('academic-years.index') }}" class="btn btn-sm btn-outline-warning rounded-pill px-3 mt-3 fw-bold border-0 bg-white bg-opacity-10">
                                Kelola TA <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </div>
                        <i class="bi bi-calendar2-check metric-icon-bg text-white opacity-05"></i>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                {{-- LEFT COLUMN: Charts & Distribution --}}
                <div class="col-lg-5">
                    <div class="glass-card h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">Distribusi Siswa</h5>
                                <small class="text-muted">Perbandingan jumlah siswa per unit.</small>
                            </div>
                            <div class="btn btn-icon btn-sm btn-light rounded-circle shadow-sm">
                                <i class="bi bi-bar-chart-fill text-primary"></i>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column justify-content-center">
                            {{-- Visual Chart --}}
                            <div class="d-flex align-items-end justify-content-around px-2 pb-0" style="height: 220px;">
                                @if($studentsPerUnit->isEmpty())
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted opacity-50">
                                        <i class="bi bi-bar-chart fs-1 mb-2"></i>
                                        <small>Belum ada data unit/siswa</small>
                                    </div>
                                @else

                                    @php
                                        // 1. Find Maximum value for scaling
                                        $maxCount = $studentsPerUnit->max('students_count');
                                        // Avoid division by zero
                                        if ($maxCount == 0) $maxCount = 1;
                                    @endphp

                                    @foreach($studentsPerUnit as $unit)
                                        @php
                                            $count = $unit->students_count;
                                            
                                            // 2. Calculate height relative to Max (not Total)
                                            // Max height in pixels = 150px (container is 220px, leave space for labels)
                                            $maxBarHeight = 150; 
                                            $percent = ($count / $maxCount);
                                            $pixelHeight = round($percent * $maxBarHeight);
                                            
                                            // Ensure minimal visual height (2px) unless 0
                                            if ($count > 0 && $pixelHeight < 4) $pixelHeight = 4;
                                            if ($count == 0) $pixelHeight = 2; // Flat line for 0
                                            
                                            $colors = ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
                                            $color = $colors[$loop->index % count($colors)];
                                        @endphp
                                        <div class="text-center d-flex flex-column align-items-center chart-group flex-fill" style="max-width: 60px;">
                                            <div class="fw-bold text-dark small mb-1">{{ $count }}</div>
                                            <div class="chart-bar rounded-top w-100 shadow-sm" 
                                                 data-bs-toggle="tooltip" 
                                                 title="{{ $unit->name }}: {{ $count }} Siswa"
                                                 style="height: {{ $pixelHeight }}px; background-color: {{ $color }}; min-height: 2px;">
                                            </div>
                                            <div class="mt-2 text-muted fw-bold" style="font-size: 0.7rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">
                                                {{ substr($unit->name, 0, 8) }}
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            
                            {{-- Legend List --}}
                            <div class="mt-4 pt-3 border-top">
                                <div class="row g-2">
                                    @foreach($studentsPerUnit as $unit)
                                        @php 
                                            $colors = ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
                                            $color = $colors[$loop->index % count($colors)];
                                        @endphp
                                        <div class="col-6">
                                            <div class="d-flex align-items-center p-2 rounded bg-light border border-light">
                                                <span class="rounded-circle me-2" style="width: 8px; height: 8px; background-color: {{ $color }};"></span>
                                                <span class="small fw-bold text-dark me-auto">{{ $unit->name }}</span>
                                                <span class="small fw-bold text-secondary">{{ $unit->students_count }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Recent Students --}}
                <div class="col-lg-7">
                    <div class="glass-card h-100">
                        <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">Siswa Terbaru</h5>
                                <small class="text-muted">5 pendaftar terakhir di sistem.</small>
                            </div>
                            <a href="{{ route('admin-students.index') }}" class="btn btn-sm btn-light text-primary fw-bold px-3 rounded-pill shadow-sm hover-lift">
                                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-premium mb-0 w-100">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 text-uppercase small fw-bold text-muted border-0">Profil Siswa</th>
                                            <th class="text-uppercase small fw-bold text-muted border-0">Penempatan</th>
                                            <th class="pe-4 text-uppercase small fw-bold text-muted border-0 text-end">Terdaftar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentStudents as $student)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-initial me-3 shadow-sm">
                                                        {{ substr($student->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <span class="d-block fw-bold text-dark">{{ $student->name }}</span>
                                                        <small class="text-muted d-flex align-items-center">
                                                            <i class="bi bi-upc-scan me-1" style="font-size: 0.7rem;"></i> 
                                                            {{ $student->nis ?? 'Belum ada NIS' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="badge bg-primary bg-opacity-10 text-primary mb-1 align-self-start border border-primary border-opacity-10">
                                                        {{ $student->unit->name ?? 'Umum' }}
                                                    </span>
                                                    <small class="text-dark fw-bold">
                                                        Kelas {{ $student->schoolClass->first()->name ?? '-' }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <div class="d-flex flex-column align-items-end">
                                                    <span class="fw-bold text-dark small">{{ $student->created_at->format('d M Y') }}</span>
                                                    <small class="text-muted">{{ $student->created_at->diffForHumans() }}</small>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center opacity-50">
                                                    <i class="bi bi-inbox fs-1 mb-2"></i>
                                                    <span>Belum ada data siswa.</span>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 p-4 rounded-bottom-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('audit.report') }}" class="btn btn-outline-danger rounded-pill px-4 btn-sm">
                                    <i class="fas fa-shield-alt me-2"></i> Audit & Keamanan
                                </a>
                                <button class="btn btn-outline-secondary rounded-pill px-4 btn-sm" onclick="window.location='{{ route('settings.index') }}'">
                                    <i class="bi bi-sliders me-2"></i> Pengaturan Aplikasi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MONITORING VIEW (Hidden by default) --}}
        <div id="monitoring-view" class="d-none">
            <div class="row">
                <div class="col-12">
                    @include('partials.monitoring_kelas', ['monitoringData' => $monitoringData])
                </div>
            </div>
        </div>

    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggle-monitoring-btn');
        const dashboardMain = document.getElementById('dashboard-main-view');
        const monitoringView = document.getElementById('monitoring-view');
        const welcomeText = document.getElementById('welcome-text');
        
        let isMonitoring = localStorage.getItem('admin_dashboard_mode') === 'monitoring';

        function applyMode() {
            if (isMonitoring) {
                // Switch to Monitoring
                dashboardMain.classList.add('d-none');
                monitoringView.classList.remove('d-none');
                
                toggleBtn.innerHTML = '<i class="bi bi-grid-fill fs-5"></i><span class="monitoring-text">Dashboard Utama</span>';
                toggleBtn.classList.remove('btn-warning');
                toggleBtn.classList.add('btn-light');
                
                welcomeText.textContent = 'Pantau aktivitas kelas dan kehadiran guru secara real-time.';
            } else {
                // Switch back to Dashboard
                dashboardMain.classList.remove('d-none');
                monitoringView.classList.add('d-none');
                
                toggleBtn.innerHTML = '<i class="bi bi-display fs-5"></i><span class="monitoring-text">Mode Monitoring</span>';
                toggleBtn.classList.remove('btn-light');
                toggleBtn.classList.add('btn-warning');
                
                welcomeText.textContent = 'Kelola operasional dan pantau grafik pertumbuhan sekolah Anda hari ini.';
            }
        }

        // Apply initial state
        applyMode();

        toggleBtn.addEventListener('click', function() {
            isMonitoring = !isMonitoring;
            localStorage.setItem('admin_dashboard_mode', isMonitoring ? 'monitoring' : 'dashboard');
            applyMode();
        });
    });
</script>
@endpush
@endsection
