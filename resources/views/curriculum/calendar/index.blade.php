@extends('layouts.app')

@section('title', 'Kalender Akademik')

@section('content')
<style>
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
    }
    .calendar-day-header {
        text-align: center;
        font-weight: bold;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
        text-transform: uppercase;
        font-size: 0.8rem;
    }
    .calendar-day {
        min-height: 120px;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 10px;
        position: relative;
        transition: transform 0.2s;
    }
    .calendar-day:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
        z-index: 10;
    }
    .day-number {
        font-weight: bold;
        font-size: 1.2rem;
        margin-bottom: 5px;
        display: block;
    }
    .day-event {
        font-size: 0.75rem;
        padding: 4px 6px;
        border-radius: 4px;
        margin-top: 4px;
        color: white;
        display: block;
        line-height: 1.2;
    }
    
    /* Specific Colors */
    .bg-holiday { background-color: #ffe6e6; border-color: #ffcccc; color: #cc0000; }
    .bg-activity { background-color: #e6f3ff; border-color: #b8daff; color: #004085; }
    .bg-effective { background-color: #ffffff; }
    
    .event-holiday { background-color: #dc3545; }
    .event-activity { background-color: #0d6efd; }
    .event-weekend { color: #dc3545; } /* Red text for weekend numbers */
    
    .text-weekend { color: #dc3545; }
</style>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0 fw-bold"><i class="bi bi-calendar-week me-2"></i>Kalender Akademik (Kurikulum)</h3>
                <div class="d-flex gap-2">
                    <!-- Manage Monthly Button -->
                    <a href="{{ route('curriculum.calendar.manage', ['unit_id' => $unit_id]) }}" class="btn btn-info text-white shadow-sm">
                        <i class="bi bi-calendar-range me-1"></i> Kelola Bulanan
                    </a>
                </div>
            </div>
            
            <!-- Filter Bar -->
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body bg-light rounded">
                    <form action="{{ route('curriculum.calendar.index') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-md-3">
                            <label class="small fw-bold text-muted mb-1">Unit</label>
                            <select name="unit_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold text-muted mb-1">Tahun Pelajaran</label>
                            <select name="academic_year_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ $academic_year_id == $ay->id ? 'selected' : '' }}>{{ $ay->start_year }}/{{ $ay->end_year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold text-muted mb-1">Semester</label>
                            <select name="semester" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="ganjil" {{ $semester == 'ganjil' ? 'selected' : '' }}>Semester Ganjil (Juli - Des)</option>
                                <option value="genap" {{ $semester == 'genap' ? 'selected' : '' }}>Semester Genap (Jan - Juni)</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('curriculum.calendar.index') }}" class="btn btn-sm btn-outline-secondary w-100"><i class="bi bi-arrow-clockwise"></i> Reset Default</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stats Recap (Semester Level) -->
            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <h5 class="fw-bold text-secondary border-bottom pb-2">
                        Rekap Semester {{ ucfirst($semester) }}
                    </h5>
                </div>
                <!-- Stats Cards -->
                <div class="col-md-4">
                     <div class="d-flex align-items-center p-3 bg-white border rounded shadow-sm h-100">
                         <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3 text-success">
                             <i class="bi bi-check-circle-fill fs-4"></i>
                         </div>
                         <div>
                             <h3 class="fw-bold mb-0">{{ $semesterStats['effective'] ?? 0 }}</h3>
                             <div class="small text-muted">Hari Efektif KBM</div>
                         </div>
                     </div>
                </div>
                <div class="col-md-4">
                     <div class="d-flex align-items-center p-3 bg-white border rounded shadow-sm h-100">
                         <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3 text-danger">
                             <i class="bi bi-calendar-x-fill fs-4"></i>
                         </div>
                         <div>
                             <h3 class="fw-bold mb-0">{{ $semesterStats['holiday'] ?? 0 }}</h3>
                             <div class="small text-muted">Hari Libur</div>
                         </div>
                     </div>
                </div>
                <div class="col-md-4">
                     <div class="d-flex align-items-center p-3 bg-white border rounded shadow-sm h-100">
                         <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3 text-primary">
                             <i class="bi bi-flag-fill fs-4"></i>
                         </div>
                         <div>
                             <h3 class="fw-bold mb-0">{{ $semesterStats['activity'] ?? 0 }}</h3>
                             <div class="small text-muted">Kegiatan Sekolah</div>
                         </div>
                     </div>
                </div>
            </div>

            <!-- 6 Month Grid -->
            <div class="row g-4">
                @for($m = 0; $m < 6; $m++)
                    @php
                        $currMonthDate = $semStartDate->copy()->addMonths($m);
                        $currMonth = $currMonthDate->month;
                        $currYear = $currMonthDate->year;
                        
                        // Fetch days for this specific month
                        $daysInMonth = $currMonthDate->daysInMonth;
                        $firstDayOfWeek = $currMonthDate->copy()->startOfMonth()->dayOfWeek;
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0 text-uppercase">{{ $currMonthDate->translatedFormat('F Y') }}</h6>
                                <a href="{{ route('curriculum.calendar.manage', ['unit_id' => $unit_id, 'month' => $currMonth, 'year' => $currYear]) }}" class="btn btn-xs btn-outline-secondary" title="Edit Bulan Ini">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                            <div class="card-body p-2">
                                <!-- Mini Calendar Header -->
                                <div class="d-flex text-center mb-1 small fw-bold text-muted">
                                    <div style="width: 14.28%">M</div>
                                    <div style="width: 14.28%">S</div>
                                    <div style="width: 14.28%">S</div>
                                    <div style="width: 14.28%">R</div>
                                    <div style="width: 14.28%">K</div>
                                    <div style="width: 14.28%">J</div>
                                    <div style="width: 14.28%">S</div>
                                </div>
                                
                                <!-- Mini Calendar Grid -->
                                <div class="d-flex flex-wrap">
                                    <!-- Empty slots -->
                                    @for($k = 0; $k < $firstDayOfWeek; $k++)
                                        <div style="width: 14.28%; height: 35px;"></div>
                                    @endfor

                                    <!-- Days -->
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            $dt = \Carbon\Carbon::createFromDate($currYear, $currMonth, $day);
                                            $dStr = $dt->format('Y-m-d');
                                            $evt = $semEvents[$dStr] ?? null;
                                            $isWeekend = ($dt->dayOfWeek == 0 || $dt->dayOfWeek == 6);
                                            
                                            $bgClass = '';
                                            $textClass = 'text-dark';
                                            $tooltip = '';

                                            if ($evt) {
                                                $tooltip = $evt->description;
                                                if ($evt->is_holiday) {
                                                    $bgClass = 'bg-danger text-white';
                                                    $textClass = 'text-white';
                                                } else {
                                                    $bgClass = 'bg-primary text-white';
                                                    $textClass = 'text-white';
                                                }
                                            } elseif ($isWeekend) {
                                                $textClass = 'text-danger fw-bold';
                                            }
                                        @endphp
                                        <div style="width: 14.28%; height: 35px; border-radius: 4px;" 
                                             class="d-flex align-items-center justify-content-center p-1 {{ $bgClass }} position-relative"
                                             title="{{ $tooltip }}">
                                            <span class="small {{ $textClass }}">{{ $day }}</span>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
            
            <div class="mt-4 text-center text-muted small">
                <i class="bi bi-square-fill text-danger me-1"></i> Libur / Akhir Pekan &nbsp;&nbsp;
                <i class="bi bi-square-fill text-primary me-1"></i> Kegiatan Sekolah &nbsp;&nbsp;
                <span class="text-dark">Angka Biasa</span> : Hari Efektif
            </div>
            
        </div>
    </div>
</div>
@endsection
