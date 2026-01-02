@extends('layouts.app')

@section('title', 'Jadwal Pelajaran Saya')

@section('content')
<div class="container-fluid py-4">
    {{-- TOP BAR --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-7">
            <h3 class="fw-bold text-primary mb-1">
                <i class="bi bi-calendar-check-fill me-2"></i>Jadwal Pelajaran
            </h3>
            <p class="text-muted mb-0">Kelas {{ $studentClass->name ?? '-' }} • TA {{ $studentClass->academicYear->name ?? '-' }}</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <div class="d-inline-flex align-items-center gap-3 bg-white shadow-sm rounded-pill px-4 py-2 border">
                <div class="text-end">
                    <div id="clockDisplay" class="fw-bold text-primary" style="font-size: 1.2rem; line-height: 1; font-family: 'Courier New', Courier, monospace;">00:00</div>
                    <div id="dateDisplay" class="text-muted small" style="font-size: 0.7rem;">Memuat...</div>
                </div>
                <div class="vr"></div>
                <i class="bi bi-clock-fill text-primary fs-4"></i>
            </div>
        </div>
    </div>

    @if(!$studentClass)
        <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 text-center">
            <i class="bi bi-exclamation-triangle display-4 d-block mb-3"></i>
            <h5 class="fw-bold">Data Kelas Tidak Ditemukan</h5>
            <p class="mb-0">Silakan hubungi admin atau wali kelas untuk memastikan Anda sudah terdaftar di kelas yang aktif.</p>
        </div>
    @else
        <div class="row g-4">
            {{-- DAY NAVIGATION (Horizontal on Mobile, Vertical on Laptop) --}}
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 sticky-top" style="top: 1.5rem; z-index: 10;">
                    <h6 class="fw-bold text-muted small text-uppercase ls-1 mb-3 px-2">Pilih Hari</h6>
                    <div class="nav flex-column nav-pills gap-2" id="v-pills-tab" role="tablist">
                        @php 
                            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']; 
                            $today = \Carbon\Carbon::now()->translatedFormat('l');
                            // Ensure $today matches Indonesian days
                            if($today == 'Wednesday') $today = 'Rabu'; // Fallback logic if Carbon isn't translated
                            // Actually Carbon should be translated by Laravel locale
                        @endphp
                        @foreach($days as $day)
                            @php $hasSchedules = isset($schedules[$day]) && count($schedules[$day]) > 0; @endphp
                            <button class="nav-link w-100 text-start d-flex justify-content-between align-items-center py-3 px-3 rounded-4 {{ $day == $today ? 'active shadow-primary' : 'bg-light' }}" 
                                    id="v-pills-{{ $day }}-tab" data-bs-toggle="pill" data-bs-target="#v-pills-{{ $day }}" type="button" role="tab">
                                <span class="fw-bold">{{ $day }}</span>
                                @if($hasSchedules)
                                    <span class="badge {{ $day == $today ? 'bg-white text-primary' : 'bg-primary bg-opacity-10 text-primary' }} rounded-pill">
                                        {{ count($schedules[$day]) }} Sesi
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted rounded-pill small">Libur</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                    
                    <hr class="my-4 opacity-50">
                    
                    <a href="{{ route('schedules.print', ['class_id' => $studentClass->id]) }}" target="_blank" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm mb-3">
                        <i class="bi bi-file-earmark-pdf-fill me-2"></i> Download Jadwal (PDF)
                    </a>
                </div>
            </div>

            {{-- SCHEDULE CONTENT --}}
            <div class="col-lg-9">
                <div class="tab-content" id="v-pills-tabContent">
                    @foreach($days as $day)
                        <div class="tab-pane fade {{ $day == $today ? 'show active' : '' }}" id="v-pills-{{ $day }}" role="tabpanel">
                            <div class="mb-4 d-flex align-items-center justify-content-between">
                                <h4 class="fw-bold text-dark mb-0">
                                    {{ $day == $today ? 'Jadwal Hari Ini' : 'Jadwal Hari ' . $day }}
                                </h4>

                            </div>

                            {{-- Academic Calendar Status --}}
                            @if(isset($calendarEvents[$day]))
                                <div class="alert {{ $calendarEvents[$day]->is_holiday ? 'alert-danger' : 'alert-info' }} border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center">
                                    <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                                        <i class="bi {{ $calendarEvents[$day]->is_holiday ? 'bi-calendar-x-fill' : 'bi-calendar-event-fill' }} fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $calendarEvents[$day]->is_holiday ? 'Hari Libur / Off' : 'Agenda Akademik' }}</h6>
                                        <p class="mb-0 small opacity-75">{{ $calendarEvents[$day]->description }}</p>
                                    </div>
                                </div>
                            @endif

                            @if(isset($schedules[$day]) && $schedules[$day]->count() > 0)
                                <div class="schedule-timeline">
                                    @foreach($schedules[$day] as $schedule)
                                        @php
                                            $startTime = \Carbon\Carbon::parse($schedule->start_time);
                                            $endTime = \Carbon\Carbon::parse($schedule->end_time);
                                            // Live detection logic in JS will handle the "Now" highlight, but we can do a static check too
                                            $isNow = ($day == $today) && \Carbon\Carbon::now()->between($startTime, $endTime);
                                        @endphp
                                        <div class="card schedule-card mb-3 border-0 shadow-sm rounded-4 overflow-hidden {{ $isNow ? 'border-start border-4 border-primary is-active' : '' }} {{ $schedule->is_break ? 'is-break' : '' }}">
                                            <div class="card-body p-4">
                                                <div class="row align-items-center">
                                                    {{-- Time Column --}}
                                                    <div class="col-md-3 border-end-md">
                                                        <div class="text-center text-md-start mb-3 mb-md-0">
                                                            <div class="fw-bold text-primary fs-5 mb-0" style="letter-spacing: -0.5px;">
                                                                {{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}
                                                            </div>
                                                            <span class="text-muted small fw-medium text-uppercase ls-1">Waktu Belajar</span>
                                                        </div>
                                                    </div>
                                                    
                                                    {{-- Subject Column --}}
                                                    <div class="col-md-6 ps-md-4">
                                                        <div class="mb-3 mb-md-0 d-flex align-items-center">
                                                            @if($schedule->is_break)
                                                                <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle me-3">
                                                                    <i class="bi bi-cup-hot fs-4"></i>
                                                                </div>
                                                                <div>
                                                                    <h5 class="fw-bold text-dark mb-0">{{ $schedule->break_name ?? 'ISTIRAHAT' }}</h5>
                                                                    <p class="text-muted mb-0 small">Bebas dari aktivitas belajar</p>
                                                                </div>
                                                            @else
                                                                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4 me-3">
                                                                    <i class="bi bi-book-half fs-4"></i>
                                                                </div>
                                                                <div>
                                                                    <h5 class="fw-bold text-dark mb-1">{{ $schedule->subject->name ?? 'Tanpa Nama Pelajaran' }}</h5>
                                                                    <div class="d-flex align-items-center text-muted small">
                                                                        <i class="bi bi-person-fill me-1"></i>
                                                                        <span class="fw-semibold">{{ $schedule->teacher->name ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Status Column --}}
                                                    <div class="col-md-3 text-md-end mt-2 mt-md-0">
                                                        @if($isNow)
                                                            <span class="badge bg-primary rounded-pill px-3 py-2 animate-pulse">
                                                                <i class="bi bi-play-circle-fill me-1"></i> SEDANG BERLANGSUNG
                                                            </span>
                                                        @elseif(\Carbon\Carbon::now()->gt($endTime) && $day == $today)
                                                            <span class="badge bg-light text-muted rounded-pill px-3 py-2">
                                                                <i class="bi bi-check-circle me-1"></i> SELESAI
                                                            </span>
                                                        @elseif($schedule->is_break)
                                                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2">
                                                                REST TIME
                                                            </span>
                                                        @else
                                                            <div class="text-muted small">
                                                                <i class="bi bi-dot fs-1 text-primary align-middle"></i> Menunggu
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="card border-0 shadow-sm rounded-4 text-center p-5 bg-white">
                                    <div class="card-body">
                                        <div class="bg-light d-inline-block p-4 rounded-circle mb-4">
                                            <i class="bi bi-calendar-x display-4 text-muted"></i>
                                        </div>
                                        <h5 class="fw-bold">Tidak Ada Jadwal</h5>
                                        <p class="text-muted mx-auto" style="max-width: 350px;">Hari ini adalah hari libur atau belum ada jadwal yang dimasukkan oleh kurikulum untuk kelas Anda.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .nav-pills .nav-link { 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
    }
    .nav-pills .nav-link.active {
        background: #2563eb !important;
        transform: translateX(8px);
    }
    .shadow-primary {
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.25) !important;
    }
    .schedule-card { transition: all 0.3s; }
    .schedule-card:hover { transform: translateY(-4px); box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important; }
    .is-active { background-color: #f0f7ff !important; }
    .is-break { background-color: #fffdf0 !important; }
    .animate-pulse { animation: pulse 2s infinite; }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(0.98); }
    }
    .border-dashed { border-style: dashed !important; }
    
    @media (min-width: 768px) {
        .border-end-md { border-right: 1px solid #edf2f7 !important; }
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initial server time from PHP
        let serverTime = {{ now()->getTimestamp() * 1000 }};
        
        function updateClock() {
            const clockEl = document.getElementById('clockDisplay');
            const dateEl = document.getElementById('dateDisplay');
            if (!clockEl || !dateEl) return;
            
            const now = new Date(serverTime);
            
            // Time
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            clockEl.textContent = `${hours}:${minutes}`;

            // Date (Indonesian)
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            dateEl.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]}`;
            
            serverTime += 1000;
        }
        
        setInterval(updateClock, 1000);
        updateClock();
    });
</script>
@endpush
@endsection
