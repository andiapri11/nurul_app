@extends('layouts.app')

@section('title', 'Dashboard Kepala Sekolah')

@section('content')
{{-- Google Fonts - Outfit for modern look --}}
@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        --surface-card: #ffffff;
        --text-main: #1e293b;
        --text-muted: #64748b;
    }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
    }

    /* Header Background with premium glassmorphism */
    .dashboard-header {
        background: radial-gradient(circle at top right, #6366f1, #4f46e5);
        color: white;
        padding: 3rem 1rem 6rem 1rem;
        border-radius: 0 0 40px 40px;
        position: relative;
        z-index: 10; /* Lower z-index so content can float over background */
        box-shadow: 0 20px 50px -20px rgba(79, 70, 229, 0.5);
    }

    .dashboard-header::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 86c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm66-3c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm-40-39c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zm50 38c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zM21 39c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zm56-38c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zM33 15c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zm21 2c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zm-7 80c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zm43-33c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zm-3-41c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zM1 19c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zm14 75c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zm17-69c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zm70 20c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zm-12-32c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zM71 98c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zM7 93c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zM86 52c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1zM28 67c.553 0 1-.447 1-1s-.447-1-1-1-1 .447-1 1 .447 1 1 1z' fill='%23ffffff' fill-opacity='0.15' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.5;
        pointer-events: none;
    }

    .header-content-inner {
        position: relative;
        z-index: 60; /* Higher than everthing else in header/content to ensure clickability */
    }

    .hover-lift {
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1);
    }

    /* Content Area Shift */
    .dashboard-content-shift {
        margin-top: -4rem; /* Reduced slightly to ensure text isn't visually crowded by upper curve */
        position: relative;
        z-index: 20; /* Higher than header background but lower than header interactive content */
    }

    .premium-card {
        background: #ffffff;
        border: none;
        box-shadow: 0 4px 25px rgba(0,0,0,0.03);
        border-radius: 24px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .premium-card:hover {
        box-shadow: 0 15px 45px rgba(0,0,0,0.08);
    }

    .stat-badge {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        padding: 0.4em 0.8em;
        text-transform: uppercase;
        border-radius: 8px;
    }

    .icon-box {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        font-size: 1.6rem;
        transition: all 0.3s ease;
    }

    .premium-card:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    .glass-input {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        border-radius: 14px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s;
    }

    .glass-input:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    .timeline-container {
        position: relative;
        padding-left: 0;
    }

    .timeline-item {
        position: relative;
        padding-left: 3rem;
        padding-bottom: 2.5rem;
    }

    .timeline-line {
        position: absolute;
        left: 17px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #f1f5f9;
        z-index: 1;
    }

    .timeline-dot {
        position: absolute;
        left: 8px;
        top: 4px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: white;
        border: 4px solid #4f46e5;
        z-index: 2;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .activity-card {
        border-radius: 18px;
        border: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .activity-card:hover {
        border-color: #cbd5e1;
        background-color: #f8fafc;
        transform: translateX(4px);
    }

    .ls-2 { letter-spacing: 0.1em; }
    
    .text-indigo-600 { color: #4f46e5; }
    .bg-indigo-50 { background-color: #eef2ff; }
    .bg-amber-50 { background-color: #fffbeb; }
    .text-amber-600 { color: #d97706; }
    .bg-emerald-50 { background-color: #ecfdf5; }
    .text-emerald-600 { color: #059669; }
    .bg-rose-50 { background-color: #fff1f2; }
    .text-rose-600 { color: #e11d48; }

    .btn-premium-warning {
        background: #fbbf24;
        color: #92400e;
        border: none;
        box-shadow: 0 4px 15px rgba(251, 191, 36, 0.3);
    }
    
    .btn-premium-warning:hover {
        background: #f59e0b;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(251, 191, 36, 0.4);
    }

    .pulse {
        animation: pulse-animation 2s infinite;
    }

    @keyframes pulse-animation {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(79, 70, 229, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
    }
</style>
@endpush

<div class="dashboard-header">
    <div class="container-fluid px-4 header-content-inner">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-white text-dark stat-badge ls-2">Monitoring Akademik</span>
                    <span class="text-white-50 small">•</span>
                    <span class="text-white small fw-medium">{{ now()->translatedFormat('d F Y') }}</span>
                </div>
                <h1 class="display-5 fw-bold mb-1" id="header-title">Halo, Kepala Sekolah</h1>
                <p class="lead text-white-50 mb-0" id="header-subtitle">Berikut adalah ringkasan operasional unit hari ini.</p>
            </div>
            
            <div class="col-lg-6">
                <div class="d-flex flex-wrap justify-content-lg-end align-items-center gap-3">
                    {{-- Unit Selector --}}
                    <div class="glass-input d-flex align-items-center bg-white">
                        <i class="bi bi-building me-2 text-dark opacity-50"></i>
                        <form action="{{ route('principal.index') }}" method="GET" class="m-0">
                            <select name="unit_id" class="border-0 bg-transparent text-dark shadow-none fw-bold p-0 pe-4" style="cursor: pointer; outline: none; -webkit-appearance: none;" onchange="this.form.submit()">
                                <option value="all" {{ $selectedUnitId == 'all' ? 'selected' : '' }} class="text-dark">Semua Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }} class="text-dark">
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                        <i class="bi bi-chevron-down small opacity-50 ms-n2"></i>
                    </div>

                    <button type="button" id="toggle-monitoring-btn" class="btn btn-premium-warning rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 hover-lift">
                        <i class="bi bi-display"></i>
                        <span>Mode Monitoring</span>
                    </button>
                </div>
            </div>
        </div>
        
        {{-- Floating Notification --}}
        @if(isset($pendingDocumentsCount) && $pendingDocumentsCount > 0)
            <div id="pending-docs-alert" class="premium-card mt-5 p-3 border-start border-warning border-5 animate__animated animate__fadeInUp" style="background: rgba(255,255,255,0.9); backdrop-filter: blur(10px);">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning me-3" style="width: 42px; height: 42px; border-radius: 12px;">
                             <i class="bi bi-file-earmark-check fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Dokumen Menunggu Validasi</h6>
                            <p class="text-muted small mb-0">Terdapat <strong>{{ $pendingDocumentsCount }}</strong> pengajuan perangkat ajar yang memerlukan review Anda.</p>
                        </div>
                    </div>
                    <a href="{{ route('principal.documents') }}" class="btn btn-dark rounded-pill px-4 fw-bold btn-sm shadow-sm">
                        Review Sekarang
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<section class="content px-4 pb-5 dashboard-content-shift">
    <div class="container-fluid p-0">
        
        {{-- MONITORING VIEW --}}
        <div id="monitoring-view" class="d-none">
            <div class="row">
                <div class="col-12">
                    @include('partials.monitoring_kelas', ['monitoringData' => $monitoringData])
                </div>
            </div>
        </div>

        {{-- MAIN DASHBOARD VIEW --}}
        <div id="dashboard-main-view">
            {{-- STATS ROW --}}
            <div class="row g-4 mb-5">
                <div class="col-6 col-lg-3">
                    <div class="premium-card p-4 hover-lift">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="icon-box bg-indigo-50 text-indigo-600">
                                <i class="bi bi-person-video3"></i>
                            </div>
                            <span class="badge bg-indigo-50 text-indigo-600 stat-badge">Total</span>
                        </div>
                        <h2 class="fw-bold mb-1">{{ number_format($stats['total_teachers']) }}</h2>
                        <p class="text-muted small fw-medium mb-0">Guru Aktif</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="premium-card p-4 hover-lift">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="icon-box bg-amber-50 text-amber-600">
                                <i class="bi bi-building"></i>
                            </div>
                            <span class="badge bg-amber-50 text-amber-600 stat-badge">Kelas</span>
                        </div>
                        <h2 class="fw-bold mb-1">{{ number_format($stats['total_classes']) }}</h2>
                        <p class="text-muted small fw-medium mb-0">Ruang Kelas</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="premium-card p-4 hover-lift">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="icon-box bg-emerald-50 text-emerald-600">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                            <span class="badge bg-emerald-50 text-emerald-600 stat-badge">Today</span>
                        </div>
                        <h2 class="fw-bold mb-1">{{ number_format($stats['today_checkins']) }}</h2>
                        <p class="text-muted small fw-medium mb-0">Hadir di Kelas</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="premium-card p-4 hover-lift">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="icon-box bg-rose-50 text-rose-600">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <span class="badge bg-rose-50 text-rose-600 stat-badge">Waspada</span>
                        </div>
                        <h2 class="fw-bold mb-1">{{ number_format($stats['today_late']) }}</h2>
                        <p class="text-muted small fw-medium mb-0">Terlambat</p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                {{-- ACTIVITY SECTION --}}
                <div class="col-xl-8">
                    <div class="premium-card h-100">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fw-bold mb-1">Aktivitas Mengajar</h4>
                                <p class="text-muted small mb-0">Log kehadiran guru secara real-time.</p>
                            </div>
                            <a href="{{ route('principal.teacher-attendance') }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-bold">Detail</a>
                        </div>
                        <div class="card-body px-4 py-4">
                            <div class="timeline-container">
                                @forelse($recentCheckins as $checkin)
                                    @php
                                        $colorClass = match($checkin->status) {
                                            'ontime' => 'emerald',
                                            'late' => 'amber',
                                            'absent' => 'rose',
                                            default => 'indigo'
                                        };
                                        $statusText = match($checkin->status) {
                                            'ontime' => 'Hadir Tepat Waktu',
                                            'late' => 'Hadir Terlambat',
                                            'absent' => 'Tidak Hadir',
                                            default => ucfirst($checkin->status)
                                        };
                                    @endphp
                                    <div class="timeline-item">
                                        <div class="timeline-line"></div>
                                        <div class="timeline-dot" style="border-color: var(--{{ $colorClass == 'emerald' ? 'emerald-600' : ($colorClass == 'amber' ? 'amber-600' : 'rose-600') }});"></div>
                                        <div class="activity-card p-3 d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center overflow-hidden">
                                                <div class="flex-shrink-0 avatar-sm me-3">
                                                    <div class="bg-{{ $colorClass }}-50 text-{{ $colorClass }}-600 fw-bold rounded-pill d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                        {{ strtoupper(substr($checkin->user->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="overflow-hidden">
                                                    <h6 class="fw-bold mb-0 text-truncate">{{ $checkin->user->name }}</h6>
                                                    <p class="text-muted small mb-0 text-truncate">
                                                        <span class="fw-medium text-dark">{{ $checkin->schedule->schoolClass->name }}</span>
                                                        <span class="mx-1">•</span>
                                                        {{ $checkin->schedule->subject->name }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-end flex-shrink-0 ms-3">
                                                <span class="badge bg-{{ $colorClass }}-50 text-{{ $colorClass }}-600 rounded-pill px-2 py-1 small mb-1" style="font-size: 0.7rem;">
                                                    {{ $statusText }}
                                                </span>
                                                <div class="fw-bold small text-dark">{{ $checkin->checkin_time->format('H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <div class="icon-box bg-light text-muted mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 40px; font-size: 2.5rem;">
                                            <i class="bi bi-clock"></i>
                                        </div>
                                        <h5 class="fw-bold">Belum Ada Aktivitas</h5>
                                        <p class="text-muted">Data akan muncul setelah guru melakukan presensi di kelas.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SIDEBAR BOLTED TOOLS --}}
                <div class="col-xl-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="premium-card p-4">
                                <h5 class="fw-bold mb-4">Navigasi Cepat</h5>
                                <div class="d-grid gap-3">
                                    <a href="{{ route('principal.teacher-attendance') }}" class="btn btn-outline-light border text-start p-3 rounded-4 hover-lift d-flex align-items-center">
                                        <div class="icon-box bg-indigo-50 text-indigo-600 me-3" style="width: 44px; height: 44px; border-radius: 12px; font-size: 1.2rem;">
                                            <i class="bi bi-calendar-check"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0">Presensi Guru</h6>
                                            <small class="text-muted">Pantau kedisiplinan pengajar</small>
                                        </div>
                                        <i class="bi bi-arrow-right ms-auto text-muted opacity-50"></i>
                                    </a>
                                    <a href="{{ route('principal.class-stats') }}" class="btn btn-outline-light border text-start p-3 rounded-4 hover-lift d-flex align-items-center">
                                        <div class="icon-box bg-emerald-50 text-emerald-600 me-3" style="width: 44px; height: 44px; border-radius: 12px; font-size: 1.2rem;">
                                            <i class="bi bi-bar-chart-line"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0">Statistik Kelas</h6>
                                            <small class="text-muted">Data populasi & wali kelas</small>
                                        </div>
                                        <i class="bi bi-arrow-right ms-auto text-muted opacity-50"></i>
                                    </a>
                                    <a href="{{ route('principal.documents') }}" class="btn btn-outline-light border text-start p-3 rounded-4 hover-lift d-flex align-items-center">
                                        <div class="icon-box bg-amber-50 text-amber-600 me-3" style="width: 44px; height: 44px; border-radius: 12px; font-size: 1.2rem;">
                                            <i class="bi bi-files"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0">Administrasi Guru</h6>
                                            <small class="text-muted">Validasi RPP & Perangkat Ajar</small>
                                        </div>
                                        <i class="bi bi-arrow-right ms-auto text-muted opacity-50"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="premium-card p-4 bg-primary text-white overflow-hidden shadow-primary border-0" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">
                                <div style="position: absolute; right: -20px; bottom: -20px; font-size: 10rem; opacity: 0.1; transform: rotate(-15deg);">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Bantuan Teknis</h5>
                                <p class="text-white-50 small mb-4">Butuh bantuan terkait data akademik atau sistem? Tim IT kami siap membantu Anda.</p>
                                <a href="#" class="btn btn-white bg-white text-primary fw-bold rounded-pill px-4 shadow-sm">Hubungi Admin</a>
                            </div>
                        </div>
                    </div>
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
        const headerTitle = document.getElementById('header-title');
        const headerSubtitle = document.getElementById('header-subtitle');
        const pendingDocsAlert = document.getElementById('pending-docs-alert');
        
        let isMonitoring = localStorage.getItem('principal_dashboard_mode') === 'monitoring';

        function applyMode() {
            if (isMonitoring) {
                // Switch to Monitoring
                dashboardMain.classList.add('d-none');
                monitoringView.classList.remove('d-none');
                if (pendingDocsAlert) pendingDocsAlert.classList.add('d-none');
                
                toggleBtn.innerHTML = '<i class="bi bi-grid-fill"></i><span>Kembali ke Dashboard</span>';
                toggleBtn.classList.remove('btn-premium-warning');
                toggleBtn.classList.add('btn-light', 'border');
                
                headerTitle.textContent = 'Monitoring Real-time';
                headerSubtitle.textContent = 'Pantau kondisi aktif belajar mengajar saat ini.';
                
                // Active Pulse to button in monitoring mode
                toggleBtn.classList.add('pulse');
            } else {
                // Switch back to Dashboard
                dashboardMain.classList.remove('d-none');
                monitoringView.classList.add('d-none');
                if (pendingDocsAlert) pendingDocsAlert.classList.remove('d-none');
                
                toggleBtn.innerHTML = '<i class="bi bi-display"></i><span>Mode Monitoring</span>';
                toggleBtn.classList.remove('btn-light', 'border', 'pulse');
                toggleBtn.classList.add('btn-premium-warning');
                
                headerTitle.textContent = 'Halo, Kepala Sekolah';
                headerSubtitle.textContent = 'Berikut adalah ringkasan operasional unit hari ini.';
            }
        }

        // Apply initial state
        applyMode();

        toggleBtn.addEventListener('click', function() {
            isMonitoring = !isMonitoring;
            localStorage.setItem('principal_dashboard_mode', isMonitoring ? 'monitoring' : 'dashboard');
            applyMode();
        });
    });
</script>
@endpush
@endsection
