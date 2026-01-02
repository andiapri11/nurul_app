@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
        --modern-blue: #0A66C2;
        --modern-indigo: #4f46e5;
        --modern-orange: #f59e0b;
        --modern-success: #10b981;
    }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
    }

    .fw-extrabold { font-weight: 800; }

    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
    }

    /* Hero Section */
    .hero-gradient {
        background: linear-gradient(135deg, #4338ca 0%, #6366f1 50%, #818cf8 100%);
        position: relative;
        overflow: hidden;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 86c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm66-3c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM37 17c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm54 46c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
    }

    /* Modern Stat Cards */
    .stat-card-modern {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #f1f5f9;
        border-radius: 24px;
    }

    .stat-card-modern:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #e2e8f0;
    }

    .icon-box {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }

    .stat-card-modern:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    .bg-soft-primary { background: #eff6ff; color: #3b82f6; }
    .bg-soft-warning { background: #fffbeb; color: #f59e0b; }
    .bg-soft-success { background: #ecfdf5; color: #10b981; }

    .card-bg-decoration {
        position: absolute;
        right: -20px;
        bottom: -20px;
        font-size: 6rem;
        opacity: 0.03;
        transition: all 0.5s ease;
    }

    .stat-card-modern:hover .card-bg-decoration {
        transform: scale(1.2) rotate(-15deg);
        opacity: 0.07;
    }

    /* Status Indicator */
    .status-indicator {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pulse-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        position: relative;
    }

    .pulse-dot::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: inherit;
        border-radius: 50%;
        animation: pulse-ring 1.5s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite;
    }

    @keyframes pulse-ring {
        0% { transform: scale(0.8); opacity: 0.5; }
        80%, 100% { transform: scale(2.4); opacity: 0; }
    }

    /* Finance Cards */
    .finance-card {
        border-radius: 24px;
        transition: all 0.4s ease;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    }

    .bg-gradient-orange {
        background: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
    }

    .finance-card:hover {
        transform: translateY(-5px);
        filter: brightness(1.05);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
    }

    .glass-icon-box {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        width: 72px;
        height: 72px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .arrow-btn {
        opacity: 0.5;
        transition: opacity 0.3s ease;
    }

    .finance-card:hover .arrow-btn {
        opacity: 1;
    }

    .finance-decoration {
        position: absolute;
        right: 10px;
        bottom: -10px;
        font-size: 8rem;
        opacity: 0.1;
        transform: rotate(-10deg);
    }

    .timeline-pill {
        width: 6px;
        background: #f1f5f9;
        border-radius: 10px;
        position: relative;
    }

    .timeline-pill.active {
        background: #3b82f6;
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
    }

    .timeline-pill.active::after {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 12px;
        height: 12px;
        background: #3b82f6;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .nav-pills .nav-link {
        color: #64748b;
        font-weight: 600;
        padding: 10px 20px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .nav-pills .nav-link.active {
        background: #3b82f6 !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .ls-2 { letter-spacing: 2px; }

    @media (max-width: 768px) {
        .hero-gradient { padding: 1.5rem !important; }
        .display-6 { font-size: 1.75rem; }
        .icon-box { width: 50px; height: 50px; }
        .icon-box i { font-size: 1.5rem !important; }
        .glass-icon-box { width: 60px; height: 60px; }
        .glass-icon-box i { font-size: 1.5rem !important; }
    }

    .avatar-ring {
        padding: 5px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: inline-block;
        transition: transform 0.3s ease;
    }

    .avatar-ring:hover {
        transform: scale(1.05) rotate(5deg);
    }
    /* Graduation Modern Premium Styles */
    #graduationModal .modal-content {
        background: #fff;
        border: none;
    }

    .grad-header-success {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    }

    .grad-header-fail {
        background: linear-gradient(135deg, #4b5563 0%, #1f2937 100%);
    }

    .grad-header-pending {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    }

    .envelope-container {
        position: relative;
        width: 100%;
        max-width: 300px;
        margin: 0 auto;
        perspective: 1000px;
        cursor: pointer;
    }

    .envelope-card {
        transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .envelope-container:hover .envelope-card {
        transform: translateY(-10px) rotate(2deg);
    }

    .glow-success {
        box-shadow: 0 0 30px rgba(16, 185, 129, 0.4);
    }

    .glow-primary {
        box-shadow: 0 0 30px rgba(79, 70, 229, 0.4);
    }

    .result-badge {
        font-size: 3.5rem;
        font-weight: 900;
        letter-spacing: 4px;
        text-shadow: 0 4px 10px rgba(0,0,0,0.1);
        display: block;
        padding: 1rem 2rem;
        border-radius: 20px;
    }

    .message-container {
        border-left: 4px solid #e2e8f0;
        padding-left: 20px;
        text-align: left;
        margin-top: 2rem;
    }

    /* Floating caps effect */
    .floating-cap {
        position: absolute;
        font-size: 2rem;
        opacity: 0.1;
        pointer-events: none;
        z-index: 0;
        animation: float-cap 10s infinite ease-in-out;
    }

    @keyframes float-cap {
        0%, 100% { transform: translateY(0) rotate(0); }
        50% { transform: translateY(-30px) rotate(20deg); }
    }

    .countdown-unit {
        position: relative;
        overflow: hidden;
    }
    
    .countdown-unit::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
        transform: translateX(-100%);
        animation: shine-countdown 3s infinite;
    }

    @keyframes shine-countdown {
        100% { transform: translateX(100%); }
    }

    /* Glitch Animation for Drama */
    .glitch-text {
        animation: glitch-skew 1s infinite linear alternate-reverse;
        color: #ef4444;
        font-family: monospace;
        font-weight: bold;
    }

    @keyframes glitch-skew {
        0% { transform: skew(0deg); }
        20% { transform: skew(5deg); }
        40% { transform: skew(-5deg); }
        60% { transform: skew(2deg); }
        80% { transform: skew(-2deg); }
        100% { transform: skew(0deg); }
    }
</style>
@endpush

@section('content')
<div class="app-content pt-4">
    <div class="container-xl">
        {{-- Hero / Welcome Section --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="hero-gradient rounded-5 p-4 p-md-5 text-white shadow-lg position-relative">
                    <div class="hero-overlay"></div>
                    <div class="position-relative z-1">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <div class="d-flex flex-column flex-sm-row align-items-center text-center text-sm-start">
                                    <div class="avatar-ring mb-3 mb-sm-0 me-sm-4 shadow-lg">
                                        @if(Auth::user()->photo)
                                            <img src="{{ asset('photos/' . Auth::user()->photo) }}" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #fff;">
                                        @else
                                            <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                                <i class="bi bi-person-fill display-2"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mt-2">
                                        <div class="text-uppercase ls-2 mb-1 fw-bold opacity-75" style="font-size: 0.75rem; letter-spacing: 2px;">
                                            {{ Auth::user()->student->unit->name ?? '-' }}
                                        </div>
                                        <h6 class="text-uppercase ls-2 mb-2 opacity-75 fw-medium" style="font-size: 0.8rem;">
                                            <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                                        </h6>
                                        <h2 class="display-6 fw-bold mb-2">Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h2>
                                        <p class="lead mb-0 opacity-90 fw-medium">
                                            @php
                                                $hour = date('H');
                                                $greeting = "Semoga harimu menyenangkan!";
                                                if ($hour < 12) $greeting = "Semangat pagi! Mari mulai belajar dengan ceria.";
                                                elseif ($hour < 17) $greeting = "Selamat siang! Tetap fokus dan semangat.";
                                                else $greeting = "Selamat malam! Jangan lupa istirahat yang cukup.";
                                            @endphp
                                            {{ $greeting }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 mt-4 mt-lg-0 text-center">
                                <div class="glass-card rounded-4 p-3 d-flex justify-content-around text-center">
                                    <div class="px-2 border-end border-dark border-opacity-10 flex-fill d-flex flex-column justify-content-center">
                                        <div class="fw-bold text-dark mb-0" style="font-size: 1.1rem; line-height: 1.2;">{{ $studentClass ? $studentClass->name : '-' }}</div>
                                        <div class="fw-bold text-dark opacity-75" style="font-size: 0.9rem;">{{ $studentClass && $studentClass->academicYear ? $studentClass->academicYear->name : '' }}</div>
                                        <div class="small opacity-50 text-uppercase fw-extrabold ls-2 text-dark mt-1" style="font-size: 0.6rem;">Kelas</div>
                                    </div>
                                    <div class="px-2 flex-fill d-flex flex-column justify-content-center">
                                        <div class="h3 fw-bold mb-0 text-dark" style="font-size: 1.1rem;">{{ Auth::user()->student->nisn ?? '-' }}</div>
                                        <div class="small opacity-50 text-uppercase fw-extrabold ls-2 text-dark" style="font-size: 0.6rem;">NISN</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Main Content Column --}}
            <div class="col-lg-8">
                {{-- Quick Actions / Stats --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card stat-card-modern border-0 shadow-sm h-100 overflow-hidden bg-white">
                            <div class="card-body p-4 position-relative">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="icon-box bg-soft-primary text-primary">
                                        <i class="bi bi-calendar-check-fill h2 mb-0"></i>
                                    </div>
                                    <div class="status-indicator">
                                        <span class="pulse-dot bg-success"></span>
                                        <span class="small fw-bold text-success opacity-75">AKTIF</span>
                                    </div>
                                </div>
                                <h5 class="fw-extrabold text-dark mb-1">Kehadiran</h5>
                                <p class="small text-muted mb-0">Rekap absensi harian Anda</p>
                                
                                <div class="card-bg-decoration">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                            </div>
                            <a href="{{ route('siswa.absensi') }}" class="stretched-link"></a>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card stat-card-modern border-0 shadow-sm h-100 overflow-hidden bg-white">
                            <div class="card-body p-4 position-relative">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="icon-box bg-soft-warning text-warning">
                                        <i class="bi bi-trophy-fill h2 mb-0"></i>
                                    </div>
                                    <div class="status-indicator">
                                        <span class="badge bg-soft-warning text-warning rounded-pill px-3">PRESTASI</span>
                                    </div>
                                </div>
                                <h5 class="fw-extrabold text-dark mb-1">Poin & Prestasi</h5>
                                <p class="small text-muted mb-0">Cek rekam jejak perilaku</p>
                                
                                <div class="card-bg-decoration text-warning">
                                    <i class="bi bi-award"></i>
                                </div>
                            </div>
                            <a href="{{ route('siswa.nilai') }}" class="stretched-link"></a>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card stat-card-modern border-0 shadow-sm h-100 overflow-hidden bg-dark text-white">
                            <div class="card-body p-4 position-relative z-1">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="icon-box bg-white bg-opacity-10 text-white">
                                        <i class="bi bi-megaphone-fill h2 mb-0"></i>
                                    </div>
                                    @if($newAnnouncementsCount > 0)
                                        <span class="badge bg-danger rounded-pill px-3 animate__animated animate__pulse animate__infinite">{{ $newAnnouncementsCount }} BARU</span>
                                    @else
                                        <span class="badge bg-white bg-opacity-10 rounded-pill px-3">INFO</span>
                                    @endif
                                </div>
                                <h5 class="fw-extrabold mb-1">Informasi</h5>
                                <p class="small text-white-50 mb-0">Pengumuman & Mading</p>
                                
                                <div class="card-bg-decoration opacity-10">
                                    <i class="bi bi-stars"></i>
                                </div>
                            </div>
                            <a href="{{ route('siswa.pengumuman') }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
                
                {{-- Financial Overview --}}
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="card finance-card border-0 shadow-lg h-100 overflow-hidden bg-gradient-primary">
                            <div class="card-body p-4 text-white position-relative">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="glass-icon-box">
                                        <i class="bi bi-wallet2 h1 mb-0"></i>
                                    </div>
                                    <div class="arrow-btn">
                                        <i class="bi bi-arrow-up-right-circle h3"></i>
                                    </div>
                                </div>
                                <h4 class="fw-extrabold mb-1">Riwayat Pembayaran</h4>
                                <p class="small opacity-75 mb-0">Cek daftar transaksi & cetak kuitansi resmi</p>
                                
                                <div class="finance-decoration">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <a href="{{ route('siswa.payments.history') }}" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card finance-card border-0 shadow-lg h-100 overflow-hidden bg-gradient-orange">
                            <div class="card-body p-4 text-white position-relative">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="glass-icon-box">
                                        <i class="bi bi-exclamation-triangle-fill h1 mb-0 text-white"></i>
                                    </div>
                                    <div class="arrow-btn text-white">
                                        <i class="bi bi-arrow-up-right-circle h3"></i>
                                    </div>
                                </div>
                                <h4 class="fw-extrabold mb-1 text-white">Rincian Tunggakan</h4>
                                <p class="small opacity-75 mb-0 text-white">Lihat tagihan yang harus segera dilunasi</p>
                                
                                <div class="finance-decoration">
                                    <i class="bi bi-receipt-cutoff"></i>
                                </div>
                                <a href="{{ route('siswa.payments.arrears') }}" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Agenda Hari Ini --}}
                <div class="card border-0 shadow-sm rounded-5 mb-4">
                    <div class="card-header bg-white border-bottom-0 p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold text-dark mb-0">Agenda Hari Ini</h4>
                            <p class="text-muted small mb-0">{{ \Carbon\Carbon::now()->translatedFormat('l') }} • Jadwal Pelajaran</p>
                        </div>
                        <button class="btn btn-outline-dark rounded-pill px-4 fw-bold shadow-sm" style="font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#fullScheduleModal">
                            <i class="bi bi-grid-3x3-gap me-2"></i> MINGGU INI
                        </button>
                    </div>
                    <div class="card-body p-4">
                        @php
                            $today = \Carbon\Carbon::now()->translatedFormat('l');
                            $todaysSchedules = $schedules[$today] ?? collect();
                        @endphp

                        @if($todaysSchedules->count() > 0)
                            <div class="timeline px-2 py-3 position-relative">
                                @foreach($todaysSchedules as $schedule)
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $start = \Carbon\Carbon::parse($schedule->start_time);
                                        $end = \Carbon\Carbon::parse($schedule->end_time);
                                        $isNow = $now->between($start, $end);
                                        $isPast = $now->gt($end);
                                    @endphp
                                    <div class="d-flex mb-4">
                                        <div class="d-flex flex-column align-items-center me-4">
                                            <div class="timeline-pill h-100 {{ $isNow ? 'active' : '' }}"></div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="card border-0 rounded-4 {{ $isNow ? 'bg-primary text-white shadow-lg overflow-hidden' : ($isPast ? 'bg-light opacity-60' : 'bg-white border-light border shadow-sm') }}">
                                                @if($isNow)
                                                    <div class="position-absolute end-0 top-0 p-2 opacity-10">
                                                        <i class="bi bi-clock-history display-1"></i>
                                                    </div>
                                                @endif
                                                <div class="card-body p-4">
                                                    <div class="d-flex justify-content-between align-items-start position-relative z-1">
                                                        <div>
                                                            <div class="small text-uppercase fw-bold ls-2 mb-2 {{ $isNow ? 'text-white-50' : 'text-muted' }}">
                                                                <i class="bi bi-clock me-1"></i> {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                                                                @if($isNow) <span class="badge bg-white text-primary ms-2 rounded-pill animate-pulse">LIVE</span> @endif
                                                            </div>
                                                            <h5 class="fw-bold mb-1">{{ $schedule->is_break ? ($schedule->break_name ?? 'ISTIRAHAT') . ' ☕' : ($schedule->subject->name ?? 'Mata Pelajaran') }}</h5>
                                                            <p class="mb-0 {{ $isNow ? 'text-white-75' : 'text-muted' }} small">
                                                                <i class="bi bi-person-badge me-1"></i> {{ $schedule->teacher->name ?? '-' }}
                                                            </p>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="rounded-circle {{ $isNow ? 'bg-white bg-opacity-20' : 'bg-light' }} p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                                <i class="bi {{ $schedule->is_break ? 'bi-cup-hot' : 'bi-journal-bookmark' }} {{ $isNow ? 'text-white' : 'text-primary' }} h4 mb-0"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 border rounded-5 bg-white border-dashed">
                                <h5 class="fw-bold mt-4 text-dark">Tidak Ada Jadwal Hari Ini</h5>
                                <p class="text-muted">Nikmati waktu luangmu!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar Column --}}
            <div class="col-lg-4">
                {{-- Info Akademik --}}
                <div class="card border-0 shadow-sm rounded-5 mb-4 overflow-hidden">
                    <div class="card-header bg-dark text-white p-4 border-0">
                        <h6 class="fw-bold mb-0"><i class="bi bi-info-circle me-2"></i> Info Akademi</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="p-3 rounded-4 bg-light me-3 text-warning">
                                <i class="bi bi-award h3 mb-0"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Tahun Ajaran</div>
                                <div class="fw-bold">{{ $studentClass->academicYear->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <div class="p-3 rounded-4 bg-light me-3 text-primary">
                                <i class="bi bi-person-workspace h3 mb-0"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Wali Kelas</div>
                                <div class="fw-bold text-dark">{{ $studentClass->teacher->name ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quote --}}
                <div class="card border-0 shadow-lg rounded-5 bg-primary text-white p-4" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                    <i class="bi bi-quote display-2 opacity-25"></i>
                    <p class="fst-italic fs-5 mb-4 px-2">
                        "Pendidikan adalah kunci untuk membuka pintu kebebasan dunia."
                    </p>
                    <div class="d-flex align-items-center px-2">
                        <span class="small fw-bold">— George Washington Carver</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Full Schedule Modal --}}
<div class="modal fade" id="fullScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-5 border-0 shadow-lg">
            <div class="modal-header border-0 p-4 pb-0">
                <h4 class="modal-title fw-bold">Jadwal Mingguan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @php $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']; @endphp
                <ul class="nav nav-pills mb-4 nav-justified bg-light p-2 rounded-pill" id="pills-tab" role="tablist">
                    @foreach($days as $day)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill {{ $day == $today ? 'active' : '' }}" id="pills-{{ $day }}-tab" data-bs-toggle="pill" data-bs-target="#pills-{{ $day }}" type="button" role="tab" style="font-size: 0.8rem;">{{ strtoupper($day) }}</button>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content">
                    @foreach($days as $day)
                        <div class="tab-pane fade {{ $day == $today ? 'show active' : '' }}" id="pills-{{ $day }}">
                            @if(isset($schedules[$day]) && count($schedules[$day]) > 0)
                                @foreach($schedules[$day] as $sch)
                                    <div class="p-3 mb-3 rounded-4 border bg-white shadow-sm">
                                        <div class="row align-items-center">
                                            <div class="col-3 text-center border-end">
                                                <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }}</div>
                                            </div>
                                            <div class="col-9 ps-4">
                                                <h6 class="fw-bold mb-1">{{ $sch->is_break ? ($sch->break_name ?? 'ISTIRAHAT') : ($sch->subject->name ?? '-') }}</h6>
                                                <div class="small text-muted">{{ $sch->teacher->name ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5 opacity-40">
                                    <h6 class="fw-bold">Tidak Ada Jadwal</h6>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Graduation Popup (Modern Premium Design) --}}
@if($graduationAnnouncement)
    @php
        $isReleased = !$graduationAnnouncement->announcement_date || $graduationAnnouncement->announcement_date->isPast();
        $baseHeaderClass = $isReleased ? ($graduationResult ? 'bg-dark bg-gradient' : 'grad-header-pending text-dark') : 'bg-dark text-white';
    @endphp

    <div class="modal fade" id="graduationModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 overflow-hidden rounded-5 shadow-2xl">
                <div class="modal-body p-0">
                    {{-- Animated Header Section --}}
                    <div id="modal-header-section" class="py-4 px-3 text-center {{ $baseHeaderClass }} transition-all duration-1000 position-relative border-bottom border-white border-opacity-10">
                        {{-- Background Decoration --}}
                        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                        
                        <div class="position-relative z-1">
                            <h2 class="fw-extrabold text-white mb-0 ls-1">{{ $graduationAnnouncement->title }}</h2>
                        </div>
                    </div>

                    <div class="p-4 p-md-5 text-center bg-white position-relative overflow-hidden" style="min-height: 450px;">
                        @if($isReleased)
                            @if($graduationResult)
                                {{-- Envelope Reveal View --}}
                                <div id="envelope-view" class="py-4 animate__animated animate__fadeIn">
                                    <div class="envelope-container mb-4" onclick="openEnvelope()">
                                        <div class="envelope-card bg-light p-5 rounded-5 border border-2 border-primary border-opacity-10 shadow-lg glow-primary">
                                            <i class="bi bi-envelope-open-heart text-primary display-1 mb-3"></i>
                                            <p class="text-muted fw-bold mb-0">Klik Untuk Membuka Hasil Anda</p>
                                        </div>
                                    </div>
                                    <h3 class="fw-extrabold text-dark mb-2">Sebuah kabar penting menantimu...</h3>
                                    <p class="text-muted px-lg-5 mb-4">Harap buka pengumuman ini dengan bijak. Apapun hasilnya, itu adalah awal dari perjalanan barumu.</p>
                                    <button class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-extrabold shadow-lg transition-all hover-translate-y" onclick="openEnvelope()">
                                        <i class="bi bi-unlock-fill me-2"></i> BUKA PENGUMUMAN
                                    </button>
                                </div>

                                {{-- Main Result View --}}
                                <div id="result-view" style="display: none; opacity: 0;" class="animate__animated">
                                    <div class="py-2 z-1 position-relative">
                                        <div class="text-uppercase ls-2 text-muted fw-bold mb-1">Hasil Kelulusan Untuk:</div>
                                        <h2 class="fw-extrabold text-dark mb-4 ls-1" style="font-size: 2rem;">{{ strtoupper($graduationResult->student->nama_lengkap) }}</h2>
                                        
                                        <div class="my-5 scale-in">
                                            <div id="status-badge-container" class="result-badge d-inline-block bg-opacity-10" style="border: 4px solid currentColor;">
                                                <span id="dynamic-status-text" class="animate__animated"></span>
                                                <span id="loading-status" class="spinner-border" role="status"></span>
                                            </div>
                                        </div>

                                        {{-- Official Message Section (Hidden initially for drama) --}}
                                        <div id="school-message-section" style="{{ $graduationResult->status == 'lulus' ? 'display: none; opacity: 0;' : '' }}">
                                            @if($graduationAnnouncement->description)
                                            <div class="message-container bg-light p-4 rounded-4 mb-5 mx-auto" style="max-width: 600px;">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="bi bi-quote text-primary display-5 me-2 opacity-50"></i>
                                                    <div class="fw-extrabold text-dark small text-uppercase ls-1">Pesan Dari Sekolah</div>
                                                </div>
                                                <div class="text-muted fst-italic ps-3">
                                                    {!! nl2br(e($graduationAnnouncement->description)) !!}
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                        {{-- SKL Download Area (Drama Release) --}}
                                        <div id="skl-download-area" class="mb-4" style="{{ $graduationResult->status == 'lulus' ? 'display: none; opacity: 0;' : '' }}">
                                            @if($graduationResult->skl_file)
                                                <div class="text-center">
                                                    <a href="{{ route('graduation.download-skl', $graduationResult->id) }}" class="text-decoration-none group" target="_blank">
                                                        <div class="d-inline-flex flex-column align-items-center p-3 rounded-4 bg-success bg-opacity-10 border border-2 border-success border-opacity-20 transition-all hover-scale shadow-sm">
                                                            <div class="p-2 rounded-circle bg-success text-white mb-2 shadow-sm">
                                                                <i class="bi bi-file-earmark-arrow-down-fill h3 mb-0"></i>
                                                            </div>
                                                            <div class="fw-extrabold text-success text-uppercase ls-1 small">Unduh SKL Resmi</div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @else
                                                <div class="text-center p-4 rounded-5 bg-light border border-dashed mx-auto" style="max-width: 500px;">
                                                    <i class="bi bi-info-circle text-muted h3 mb-2 d-block"></i>
                                                    <div class="small text-muted fw-bold text-uppercase ls-1">SKL Belum Tersedia</div>
                                                    <p class="small text-muted mb-0">Dokumen SKL elektronik Anda sedang dalam proses finalisasi.</p>
                                                </div>
                                            @endif
                                        </div>

                                        <div id="action-buttons-section" class="text-center" style="{{ $graduationResult->status == 'lulus' ? 'display: none; opacity: 0;' : '' }}">
                                            <button type="button" class="btn btn-outline-dark btn-lg rounded-pill px-5 py-2 fw-extrabold" data-bs-dismiss="modal">
                                                SELESAI
                                            </button>
                                        </div>
                                    </div>
                                    
                                    {{-- Success Elements --}}
                                    @if($graduationResult->status == 'lulus')
                                        <i class="bi bi-mortarboard floating-cap" style="top: 10%; right: 10%; font-size: 4rem;"></i>
                                        <i class="bi bi-stars floating-cap" style="bottom: 20%; left: 5%; font-size: 3rem; color: gold;"></i>
                                        <i class="bi bi-journal-check floating-cap" style="top: 40%; left: 15%; font-size: 2.5rem;"></i>
                                    @endif
                                </div>
                            @else
                                <div class="py-5">
                                    <div class="bg-light p-5 rounded-5 border border-dashed text-muted">
                                        <i class="bi bi-clipboard-x display-1 mb-4"></i>
                                        <h3 class="fw-extrabold">Data Belum Tersedia</h3>
                                        <p class="mb-0">Silakan hubungi bagian kurikulum jika ini merupakan kesalahan.</p>
                                    </div>
                                    <button type="button" class="btn btn-dark rounded-pill px-5 mt-4 fw-bold" data-bs-dismiss="modal">TUTUP</button>
                                </div>
                            @endif
                        @else
                            {{-- Countdown View --}}
                            <div class="py-5">
                                <i class="bi bi-hourglass-split text-primary display-1 mb-4 animate-spin-slow"></i>
                                <h2 class="text-dark fw-extrabold mb-1">HITUNG MUNDUR</h2>
                                <p class="text-muted mb-5">Pengumuman akan dibuka secara otomatis pada waktu berikut:</p>
                                
                                <div class="row g-3 justify-content-center mb-5" id="countdown-timer">
                                    @foreach(['days' => 'HARI', 'hours' => 'JAM', 'minutes' => 'MENIT', 'seconds' => 'DETIK'] as $id => $label)
                                        <div class="col-3 col-md-2 px-1 px-md-2">
                                            <div class="p-3 bg-white rounded-5 border border-2 border-primary border-opacity-10 shadow-sm countdown-unit">
                                                <div class="h2 fw-extrabold mb-0 text-primary" id="{{ $id }}">00</div>
                                                <div class="fw-extrabold text-muted text-uppercase" style="font-size: 0.6rem; letter-spacing: 1px;">{{ $label }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-muted small">Waktu sekarang: <span class="fw-bold">{{ now()->format('H:i') }}</span> • Tetaplah di halaman ini.</div>
                                <div class="mt-5">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-5 fw-bold" data-bs-dismiss="modal">TUTUP PENGINTIP</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<style>
    @keyframes spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .animate-spin-slow { animation: spin-slow 8s linear infinite; }
    .scale-in { animation: scale-in 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    @keyframes scale-in { from { transform: scale(0.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .hover-scale:hover { transform: scale(1.05); }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    function openEnvelope() {
        const env = document.getElementById('envelope-view');
        const res = document.getElementById('result-view');
        const header = document.getElementById('modal-header-section');
        const statusText = document.getElementById('dynamic-status-text');
        const loadingStatus = document.getElementById('loading-status');
        const badgeContainer = document.getElementById('status-badge-container');
        const messageSection = document.getElementById('school-message-section');
        const actionSection = document.getElementById('action-buttons-section');
        const sklArea = document.getElementById('skl-download-area');

        env.classList.add('animate__fadeOutUp');
        setTimeout(() => {
            env.style.display = 'none';
            res.style.display = 'block';
            res.classList.add('animate__fadeInUp');
            
            setTimeout(() => {
                res.style.opacity = '1';
                @if($graduationResult)
                    const isLulus = "{{ $graduationResult->status }}" === 'lulus';
                    
                    if (isLulus) {
                        // Phase 1: The Fake Out (TIDAK LULUS) - 1s delay
                        setTimeout(() => {
                            loadingStatus.classList.add('d-none');
                            header.classList.remove('bg-dark');
                            header.classList.add('grad-header-fail');
                            badgeContainer.className = 'result-badge d-inline-block text-danger bg-danger bg-opacity-10';
                            statusText.innerText = 'TIDAK LULUS';
                            statusText.classList.add('animate__zoomIn');
                        }, 1000);

                        // Phase 2: The Glitch Error - 2.5s total
                        setTimeout(() => {
                            statusText.classList.remove('animate__zoomIn');
                            statusText.classList.add('glitch-text');
                            statusText.innerText = 'E̶R̷R̶O̴R̷:̷ ̴D̵B̴_̷V̷E̶R̴I̴F̶Y̸_̸F̸A̴I̸L̴';
                        }, 2500);

                        // Phase 3: The True Reveal (LULUS) - 4.5s total
                        setTimeout(() => {
                            statusText.classList.remove('glitch-text');
                            header.classList.remove('grad-header-fail');
                            header.classList.add('grad-header-success');
                            badgeContainer.className = 'result-badge d-inline-block text-success bg-success bg-opacity-10';
                            statusText.innerText = 'LULUS';
                            statusText.classList.add('animate__bounceIn');
                            
                            // FORCE SHOW all elements
                            [messageSection, actionSection, sklArea].forEach(el => {
                                el.style.display = 'block';
                                setTimeout(() => {
                                    el.style.opacity = '1';
                                    el.classList.add('animate__animated', 'animate__fadeInUp');
                                }, 50);
                            });

                            confetti({ particleCount: 200, spread: 80, origin: { y: 0.6 }, colors: ['#ffffff', '#10b981', '#fbbf24'] });
                            setTimeout(() => confetti({ particleCount: 150, spread: 120, origin: { y: 0.7 } }), 600);
                        }, 4500);

                    } else {
                        // Just the truth for those who didn't pass
                        setTimeout(() => {
                            loadingStatus.classList.add('d-none');
                            header.classList.remove('bg-dark');
                            header.classList.add('grad-header-fail');
                            badgeContainer.className = 'result-badge d-inline-block text-danger bg-danger bg-opacity-10';
                            statusText.innerText = 'TIDAK LULUS';
                            statusText.classList.add('animate__zoomIn');
                            
                            // Show message & finish button immediately for non-graduates
                            messageSection.style.display = 'block';
                            actionSection.style.display = 'block';
                            messageSection.style.opacity = '1';
                            actionSection.style.opacity = '1';
                        }, 1500);
                    }
                @endif
            }, 100);
        }, 800);
    }

    document.addEventListener('DOMContentLoaded', () => {
        @if($graduationAnnouncement)
            const gModal = new bootstrap.Modal(document.getElementById('graduationModal'));
            gModal.show();

            @if($graduationAnnouncement->announcement_date && $graduationAnnouncement->announcement_date->isFuture())
                const target = new Date("{{ $graduationAnnouncement->announcement_date->toIso8601String() }}").getTime();
                setInterval(() => {
                    const now = new Date().getTime();
                    const dist = target - now;
                    if (dist < 0) return window.location.reload();
                    document.getElementById('days').innerText = Math.floor(dist / 86400000).toString().padStart(2, '0');
                    document.getElementById('hours').innerText = Math.floor((dist % 86400000) / 3600000).toString().padStart(2, '0');
                    document.getElementById('minutes').innerText = Math.floor((dist % 3600000) / 60000).toString().padStart(2, '0');
                    document.getElementById('seconds').innerText = Math.floor((dist % 60000) / 1000).toString().padStart(2, '0');
                }, 1000);
            @endif
        @endif
    });
</script>
@endpush
