@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-7">
            <h3 class="mb-1 text-primary fw-bold"><i class="bi bi-calendar-week me-2"></i>Jadwal Pelajaran</h3>
            <div class="d-flex align-items-center gap-3">
                <p class="text-muted mb-0">Kelola dan lihat jadwal mingguan siswa dengan mudah</p>
                <div class="badge bg-dark rounded-pill px-3 py-2 shadow-sm d-flex align-items-center gap-2" id="liveClock" style="font-family: 'Courier New', Courier, monospace;">
                    <i class="bi bi-clock-fill text-warning"></i>
                    <div class="text-start" style="line-height: 1;">
                        <div id="clockDisplay" class="fw-bold" style="font-size: 1.1rem; letter-spacing: 1px;">00:00</div>
                        <div id="dateDisplay" class="text-light opacity-75" style="font-size: 0.7rem; font-family: sans-serif;">Memuat tanggal...</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-md-end">
            @if($selectedClassId && auth()->user()->isLearningManagerForUnit($selectedUnitId))
                @if(isset($currentClass) && $currentClass->academicYear && $currentClass->academicYear->status == 'active')
                    <div class="btn-group shadow-sm">
                        <a href="{{ route('schedules.print', ['class_id' => $selectedClassId]) }}" target="_blank" class="btn btn-outline-secondary bg-white">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Download / Cetak PDF
                        </a>
                        <a href="{{ route('schedules.create', ['class_id' => $selectedClassId]) }}" class="btn btn-outline-primary bg-white">
                            <i class="bi bi-plus-circle me-1"></i> Satuan
                        </a>
                        <a href="{{ route('schedules.mass-update', ['class_id' => $selectedClassId]) }}" class="btn btn-primary">
                            <i class="bi bi-grid-3x3 me-1"></i> Input / Edit Massal
                        </a>
                    </div>
                @elseif(isset($currentClass) && $currentClass->academicYear)
                     <button class="btn btn-secondary shadow-sm" disabled title="Tahun Ajaran Tidak Aktif">
                        <i class="bi bi-lock me-1"></i> Terkunci ({{ $currentClass->academicYear->name ?? 'Arsip' }})
                    </button>
                @endif
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- FILTER SECTION --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('schedules.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Unit Sekolah</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-building"></i></span>
                        <select name="unit_id" class="form-select border-0 bg-light shadow-none" onchange="this.form.submit()">
                            <option value="">-- Pilih Unit --</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Tahun Ajaran</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-calendar-event"></i></span>
                        <select name="academic_year_id" class="form-select border-0 bg-light shadow-none" onchange="this.form.submit()">
                            <option value="">Semua Tahun</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $selectedAcademicYearId == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->status == 'active' ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Kelas</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-door-closed"></i></span>
                        <select name="class_id" class="form-select border-0 bg-light shadow-none" {{ !$selectedUnitId ? 'disabled' : '' }} onchange="this.form.submit()">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($classes as $kls)
                                <option value="{{ $kls->id }}" {{ $selectedClassId == $kls->id ? 'selected' : '' }}>
                                    {{ $kls->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- SCHEDULE GRID --}}
    @if($selectedClassId)
        <div class="row row-cols-1 row-cols-lg-5 g-3">
            @php
                $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                $colors = ['primary', 'success', 'info', 'warning', 'danger'];
            @endphp

            @foreach($days as $index => $day)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-{{ $colors[$index] }} bg-opacity-10 border-0 py-3">
                            <h6 class="fw-bold text-center text-uppercase text-{{ $colors[$index] }} mb-0">
                                {{ $day }}
                            </h6>
                        </div>
                        <div class="card-body p-2 bg-white">
                            @if(isset($schedules[$day]) && $schedules[$day]->count() > 0)
                                @foreach($schedules[$day] as $schedule)
                                    <div class="schedule-item mb-2 p-2 rounded-3 border-start border-4 border-{{ $schedule->is_break ? 'danger' : 'primary' }} bg-light position-relative">
                                        <div class="d-flex justify-content-between">
                                            <div class="pe-4">
                                                @if($schedule->is_break || !$schedule->subject)
                                                    <span class="fw-bold text-danger d-block mb-1" style="font-size: 0.85rem;">
                                                        <i class="bi bi-cup-hot me-1"></i> {{ $schedule->break_name ?? 'ISTIRAHAT' }}
                                                    </span>
                                                @else
                                                    <span class="fw-bold text-primary d-block mb-1" style="font-size: 0.85rem;">
                                                        {{ $schedule->subject->name }}
                                                    </span>
                                                @endif
                                                
                                                <div class="text-muted" style="font-size: 0.75rem;">
                                                    <i class="bi bi-clock me-1"></i> 
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                </div>

                                                @if(!$schedule->is_break && $schedule->teacher)
                                                    <div class="text-dark mt-1" style="font-size: 0.75rem;">
                                                        <i class="bi bi-person me-1"></i> {{ Str::limit($schedule->teacher->name, 20) }}
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="position-absolute top-0 end-0 p-1">
                                                @if(auth()->user()->isLearningManagerForUnit($selectedUnitId) && isset($currentClass) && $currentClass->academicYear && $currentClass->academicYear->status == 'active')
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm text-muted p-0 border-0" data-bs-toggle="dropdown">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 small">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('schedules.edit', $schedule->id) }}">
                                                                    <i class="bi bi-pencil me-2 text-warning"></i> Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        <i class="bi bi-trash me-2"></i> Hapus
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x opacity-25 display-6 d-block mb-2"></i>
                                    <p class="small mb-0">Tidak ada jadwal</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @elseif($selectedUnitId)
        <div class="card border-0 shadow-sm rounded-4 text-center p-5">
            <div class="card-body">
                <i class="bi bi-arrow-up-circle display-1 text-primary opacity-25 mb-4"></i>
                <h4 class="fw-bold">Lihat Jadwal Pelajaran</h4>
                <p class="text-muted">Silakan pilih unit dan kelas terlebih dahulu pada filter di atas.</p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 text-center p-5">
            <div class="card-body">
                <i class="bi bi-calendar-range display-1 text-primary opacity-25 mb-4"></i>
                <h4 class="fw-bold">Selamat Datang di Portal Jadwal</h4>
                <p class="text-muted">Gunakan panel navigasi di atas untuk mulai melihat data jadwal pelajaran sekolah.</p>
            </div>
        </div>
    @endif
</div>

<style>
    .schedule-item { transition: all 0.2s; }
    .schedule-item:hover { transform: translateY(-2px); box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; }
    .input-group-text { color: #6c757d; }
    .form-select:focus { background-color: #f8f9fa; border-color: #dee2e6; }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initial server time from PHP (Unix timestamp in milliseconds)
        let serverTime = {{ now()->getTimestamp() * 1000 }};
        
        function updateClock() {
            const clockEl = document.getElementById('clockDisplay');
            const dateEl = document.getElementById('dateDisplay');
            if (!clockEl || !dateEl) return;
            
            // Create date object based on incremented server time
            const now = new Date(serverTime);
            
            // Time Formatting
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            clockEl.textContent = `${hours}:${minutes}`;

            // Date Formatting (Indonesian)
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[now.getDay()];
            const dayNum = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();
            
            dateEl.textContent = `${dayName}, ${dayNum} ${monthName} ${year}`;
            
            // Increment by 1 second for next call
            serverTime += 1000;
        }
        
        // Update every second
        setInterval(updateClock, 1000);
        // Initial call
        updateClock();
    });
</script>
@endpush
