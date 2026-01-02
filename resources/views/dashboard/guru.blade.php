@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<style>
    /* Pulse Button Animation for Urgent Actions */
    .pulse-button {
        animation: pulse-animation 2s infinite;
    }

    @keyframes pulse-animation {
        0% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
        }
    }
</style>
<div class="container-fluid">
    {{-- WELCOME BANNER --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white shadow-lg overflow-hidden" style="border-radius: 15px;">
                <div class="card-body p-4 position-relative">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-1">Selamat Datang, {{ $user->name }}! 👋</h2>
                            <p class="mb-2 opacity-75">Semoga hari ini menyenangkan dan penuh inspirasi bagi para siswa.</p>
                            
                            <div class="mt-2">
                                @foreach($assignments as $assign)
                                    <span class="badge bg-white text-primary me-2 mb-1 shadow-sm px-3 py-2 border border-light-subtle">
                                        <i class="bi bi-person-badge-fill me-1"></i> 
                                        {{ $assign->jabatan->nama_jabatan }} 
                                        @if($assign->unit) <small class="text-secondary fw-normal">({{ $assign->unit->name }})</small> @endif
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-4 text-end d-none d-md-block">
                            <i class="bi bi-journal-bookmark-fill display-1 opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($attendanceMissing) && $attendanceMissing)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning border-start border-warning border-4 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-3 me-3 text-warning-emphasis"></i>
                    <div>
                        <h5 class="alert-heading fw-bold mb-1 text-warning-emphasis">⚠️ Absensi Kelas {{ $waliKelas->name ?? '' }} Belum Diisi</h5>
                        <p class="mb-0">Mohon segera lakukan input absensi siswa untuk hari ini.
                        <a href="{{ route('wali-kelas.attendance') }}" class="btn btn-warning btn-sm fw-bold ms-2 shadow-sm"><i class="bi bi-pencil-square"></i> Isi Absensi Sekarang</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- CURRENT TEACHING STATUS (Highlight) --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold text-dark"><i class="bi bi-calendar-event me-2 text-primary"></i>Jadwal Mengajar Hari Ini ({{ $today }})</h5>
                </div>
                <div class="card-body">
                    {{-- Global Holiday Check --}}
                    @if(isset($isHoliday) && $isHoliday)
                         <div class="text-center py-5">
                            <i class="bi bi-emoji-sunglasses fs-1 text-danger d-block mb-3"></i>
                            <h3 class="text-danger fw-bold">HARI LIBUR</h3>
                            <p class="fs-5 text-muted">{{ $calendarDescription }}</p>
                            <div class="alert alert-warning d-inline-block mt-3 border-0 bg-warning-subtle text-warning-emphasis">
                                <i class="bi bi-info-circle-fill me-1"></i> Tidak ada kegiatan belajar mengajar hari ini.
                            </div>
                            
                            {{-- Show detailed breakdown if multiple units --}}
                            @if(isset($unitStatuses) && count($unitStatuses) > 1)
                                <div class="mt-4 text-start d-inline-block bg-light p-3 rounded">
                                    <h6 class="small fw-bold text-muted mb-2">Status Unit:</h6>
                                    @foreach($unitStatuses as $status)
                                        <div class="badge {{ $status['status'] == 'holiday' ? 'bg-secondary' : 'bg-success' }} mb-1 me-1">
                                            {{ $status['unit'] }}: {{ $status['description'] }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        {{-- NOT Global Holiday (Mixed or All Effective) --}}
                        
                        {{-- Show Status Banners for Specific Units (e.g. Activity or Holiday in just one unit) --}}
                        @if(isset($unitStatuses) && count($unitStatuses) > 0)
                            <div class="row g-2 mb-3">
                                @foreach($unitStatuses as $status)
                                    @if($status['status'] !== 'effective')
                                         <div class="col-md-6">
                                             <div class="alert {{ $status['status'] == 'holiday' ? 'alert-warning text-warning-emphasis' : 'alert-info text-info-emphasis' }} d-flex align-items-center py-2 px-3 mb-0 shadow-sm border-0 h-100">
                                                 <i class="bi {{ $status['status'] == 'holiday' ? 'bi-emoji-sunglasses' : 'bi-flag-fill' }} fs-4 me-3"></i>
                                                 <div>
                                                     <strong class="d-block small">{{ $status['unit'] }}</strong>
                                                     <span class="small lh-1">{{ $status['description'] }}</span>
                                                 </div>
                                             </div>
                                         </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        
                        @if($currentSchedule)
                        <div class="alert {{ $currentSchedule->todayCheckin ? 'alert-success' : 'alert-danger' }} border-0 shadow-sm mb-4">
                            <div class="d-flex align-items-center">
                                <div class="display-4 me-3"><i class="bi {{ $currentSchedule->todayCheckin ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' }}"></i></div>
                                <div class="flex-grow-1">
                                    @if($currentSchedule->todayCheckin)
                                        <h5 class="fw-bold mb-0">Anda Sudah Check-in!</h5>
                                        <p class="mb-0">Status Kehadiran: <strong>{{ ucfirst($currentSchedule->todayCheckin->status) }}</strong> ({{ $currentSchedule->todayCheckin->checkin_time->format('H:i') }})</p>
                                    @else
                                        <h5 class="fw-bold mb-0">Anda Belum Check-in!</h5>
                                        <p class="mb-0">Saat ini Anda jadwal mengajar di <strong>{{ $currentSchedule->schoolClass->name }} - {{ $currentSchedule->subject->name }}</strong>.</p>
                                    @endif
                                </div>
                                @if(!$currentSchedule->todayCheckin)
                                    <div>
                                        <a href="{{ route('class-checkins.create') }}" class="btn btn-danger fw-bold pulse-button">
                                            <i class="bi bi-qr-code-scan me-1"></i> Check-in Sekarang
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($schedules->isNotEmpty())
                        <div class="list-group list-group-flush">
                            @foreach($schedules as $sch)
                                @php
                                    $isNow = $currentSchedule && $currentSchedule->id == $sch->id;
                                    $isPast = \Carbon\Carbon::now()->format('H:i:s') > $sch->end_time;
                                @endphp
                                <div class="list-group-item d-flex align-items-center py-3 {{ $isNow ? 'bg-success-subtle rounded mb-2' : '' }} {{ $isPast ? 'opacity-50' : '' }}" style="border-bottom: 1px dashed #eee;">
                                    <div class="me-3 text-center" style="width: 60px;">
                                        <span class="d-block fw-bold {{ $isNow ? 'text-success' : 'text-primary' }}">{{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }}</span>
                                        <span class="d-block small text-muted">{{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 fw-bold">{{ $sch->subject->name }}</h6>
                                        <small class="text-muted"><i class="bi bi-geo-alt-fill me-1"></i> Kelas {{ $sch->schoolClass->name }}</small>
                                    </div>
                                    <div>
                                        @if($isNow)
                                            <span class="badge bg-success">Sedang Mengajar</span>
                                        @elseif($isPast)
                                            <span class="badge bg-secondary">Selesai</span>
                                        @else
                                            <span class="badge bg-primary">Akan Datang</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" alt="Relax" width="100" class="mb-3 opacity-50">
                            <h5 class="text-muted">Tidak ada jadwal mengajar hari ini.</h5>
                            <p class="text-secondary small">Nikmati waktu luang Anda untuk mempersiapkan materi besok!</p>
                        </div>
                    @endif
                @endif
                </div>
            </div>
        </div>

        {{-- SIDEBAR STATS --}}
        <div class="col-md-4">
            {{-- WALI KELAS CARD --}}
            @if($waliKelas)
            <div class="card mb-3 shadow-sm border-0 border-top border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <small class="text-uppercase text-muted fw-bold">Wali Kelas</small>
                            <h4 class="fw-bold mb-0">Kelas {{ $waliKelas->name }}</h4>
                        </div>
                        <div class="icon-box bg-warning-subtle text-warning rounded-circle p-2">
                            <i class="bi bi-star-fill fs-4"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-end mt-3">
                        <h2 class="mb-0 me-2">{{ $totalSiswaWalas }}</h2>
                        <span class="text-muted mb-1">Total Siswa Binaan</span>
                    </div>
                    <hr>
                    <a href="{{ route('dashboard.my-class') }}" class="btn btn-outline-warning w-100 btn-sm">Lihat Data Kelas</a>
                </div>
            </div>
            @endif

            {{-- TEACHING STATS --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold">Statistik Anda</div>
                <div class="card-body">
                    <div class="text-center py-3">
                        <h1 class="fw-bold text-primary display-4">{{ $totalKelasAjar }}</h1>
                        <p class="text-muted mb-0">Total Kelas yang Anda Ajar (Semua Unit)</p>
                    </div>
                </div>
            </div>

            {{-- PENDING DOCUMENTS --}}
            @if(isset($pendingDocuments) && $pendingDocuments->isNotEmpty())
            <div class="card border-primary shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-bell me-2"></i> Anda mendapatkan tugas dari Wakil Kurikulum</h6>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($pendingDocuments as $doc)
                        @php
                            $dueDate = \Carbon\Carbon::parse($doc->due_date);
                            $isOverdue = $dueDate->isPast() && !$dueDate->isToday();
                            $status = $doc->user_status ?? 'missing';
                            
                            // Map Status to UI
                            $badgeColor = 'secondary';
                            $badgeText = 'Status Unknown';
                            
                            switch($status) {
                                case 'missing':
                                    $badgeColor = $isOverdue ? 'danger' : 'warning text-dark';
                                    $badgeText = $isOverdue ? 'Terlambat Upload' : 'Belum Upload';
                                    break;
                                case 'pending':
                                    $badgeColor = 'info text-dark';
                                    $badgeText = 'Menunggu Validasi Wakil';
                                    break;
                                case 'validated':
                                    $badgeColor = 'primary';
                                    $badgeText = 'Menunggu Approval KS';
                                    break;
                                case 'rejected':
                                    $badgeColor = 'danger';
                                    $badgeText = 'Perlu Revisi';
                                    break;
                            }
                        @endphp
                        <a href="{{ route('teacher-docs.index') }}" class="list-group-item list-group-item-action p-3">
                            <div class="d-flex w-100 justify-content-between mb-1 align-items-start">
                                <h6 class="mb-0 fw-bold text-dark w-75">{{ Str::limit($doc->title, 40) }}</h6>
                                <span class="badge bg-{{ $badgeColor }}" style="font-size: 0.7rem;">{{ $badgeText }}</span>
                            </div>
                            <!-- Show Deadline only if Missing or Rejected -->
                            @if(in_array($status, ['missing', 'rejected']))
                                <small class="text-{{ $isOverdue ? 'danger' : 'muted' }} fw-bold d-block mt-1" style="font-size: 0.8rem;">
                                    @php
                                        $daysLeft = \Carbon\Carbon::now()->startOfDay()->diffInDays($dueDate, false);
                                    @endphp
                                    <i class="bi bi-calendar-event"></i> Batas: {{ $dueDate->format('d M Y') }}
                                    
                                    @if($daysLeft > 0)
                                        <span class="text-primary ms-1">(Sisa {{ $daysLeft }} hari)</span>
                                    @elseif($daysLeft == 0)
                                        <span class="text-warning text-dark ms-1">(Hari ini terakhir!)</span>
                                    @else
                                        <span class="text-danger ms-1">(Terlambat {{ abs($daysLeft) }} hari)</span>
                                    @endif
                                </small>
                            @else
                                <small class="text-secondary d-block mt-1" style="font-size: 0.8rem;">
                                    <i class="bi bi-clock-history"></i> Sedang diproses...
                                </small>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
