@extends('layouts.app')

@section('title', 'Kalender Akademik')

@section('content')
<style>
    body { background-color: #f8f9fa !important; }
    .content-wrapper { background-color: #f8f9fa !important; }

    .calendar-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .mini-cal-day {
        width: 14.28%;
        height: 42px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 4px;
        font-size: 0.9rem;
        transition: all 0.2s;
        cursor: default;
        position: relative;
    }
    
    .mini-cal-day:hover:not(.empty) {
        transform: scale(1.1);
        z-index: 5;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .day-eff { background-color: #fff; color: #444; border: 1px solid #eee; }
    .day-hol { background-color: #fee2e2; color: #dc2626; font-weight: bold; border: 1px solid #fecaca; }
    .day-act { background-color: #e0f2fe; color: #0284c7; font-weight: bold; border: 1px solid #bae6fd; }
    .day-mix { background-color: #f3e8ff; color: #7c3aed; font-weight: bold; border: 1px solid #e9d5ff; }
    .day-weekend { color: #dc2626; font-weight: bold; }
    
    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        position: absolute;
        bottom: 4px;
    }
    .dot-hol { background-color: #dc2626; }
    .dot-act { background-color: #2563eb; }

    .popover {
        border: none;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
    }
    .popover-header {
        background-color: #fff;
        border-bottom: 1px solid #f3f4f6;
        font-weight: bold;
        padding: 12px 16px;
        border-radius: 12px 12px 0 0;
    }
    .popover-body {
        padding: 12px 16px;
    }
</style>

<div class="calendar-container px-3 py-4">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Kalender Akademik</h3>
            <p class="text-muted mb-0">Unit {{ $currentUnit->name }} • Semester {{ ucfirst($semester) }}</p>
        </div>
        <div class="d-flex gap-2">
            @if(!$isGuruReadOnly)
                <a href="{{ route('academic-calendars.manage', ['unit_id' => $unit_id]) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="bi bi-calendar-range me-2"></i> KELOLA KALENDER
                </a>
            @endif
        </div>
    </div>

    {{-- FILTER CARD --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-body p-3">
            <form action="{{ route('academic-calendars.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="small fw-bold text-uppercase text-muted mb-1 ls-1">Unit Sekolah</label>
                    <select name="unit_id" class="form-select border-0 bg-light" onchange="this.form.submit()">
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ $unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-uppercase text-muted mb-1 ls-1">Tahun Pelajaran</label>
                    <select name="academic_year_id" class="form-select border-0 bg-light" onchange="this.form.submit()">
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}" {{ $academic_year_id == $ay->id ? 'selected' : '' }}>
                                {{ $ay->start_year }}/{{ $ay->end_year }} {{ $ay->status == 'active' ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold text-uppercase text-muted mb-1 ls-1">Periode Semester</label>
                    <select name="semester" class="form-select border-0 bg-light" onchange="this.form.submit()">
                        <option value="ganjil" {{ $semester == 'ganjil' ? 'selected' : '' }}>Semester Ganjil (Juli - Desember)</option>
                        <option value="genap" {{ $semester == 'genap' ? 'selected' : '' }}>Semester Genap (Januari - Juni)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('academic-calendars.index') }}" class="btn btn-light w-100 fw-bold border">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- REKAP SEMESTER --}}
    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3 text-success">
                        <i class="bi bi-journal-check fs-3"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">{{ $semesterStats['effective'] }}</h2>
                        <div class="text-muted small fw-bold text-uppercase">Hari Efektif KBM</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3 text-primary">
                        <i class="bi bi-flag fs-3"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">{{ $semesterStats['activity'] }}</h2>
                        <div class="text-muted small fw-bold text-uppercase">Kegiatan Sekolah</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3 text-danger">
                        <i class="bi bi-emoji-sunglasses fs-3"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">{{ $semesterStats['holiday'] }}</h2>
                        <div class="text-muted small fw-bold text-uppercase">Total Hari Libur</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 6 MONTH CALENDAR GRID --}}
    <div class="row g-4">
        @for($m = 0; $m < 6; $m++)
            @php
                $currMonthDate = $semStartDate->copy()->addMonths($m);
                $currMonth = $currMonthDate->month;
                $currYear = $currMonthDate->year;
                $daysInMonth = $currMonthDate->daysInMonth;
                $firstDayOfWeek = $currMonthDate->copy()->startOfMonth()->dayOfWeek;
            @endphp
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                    <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">{{ $currMonthDate->translatedFormat('F Y') }}</h5>
                        @if(!$isGuruReadOnly)
                            <a href="{{ route('academic-calendars.manage', ['unit_id' => $unit_id, 'month' => $currMonth, 'year' => $currYear]) }}" class="btn btn-sm btn-light rounded-circle shadow-sm" title="Edit Bulan Ini">
                                <i class="bi bi-pencil-fill text-muted" style="font-size: 0.7rem;"></i>
                            </a>
                        @endif
                    </div>
                    <div class="card-body p-4 pt-1">
                        <div class="d-flex text-center mb-2 small fw-bold text-muted opacity-50 text-uppercase">
                            <div style="width: 14.28%">Mg</div>
                            <div style="width: 14.28%">Sn</div>
                            <div style="width: 14.28%">Sl</div>
                            <div style="width: 14.28%">Rb</div>
                            <div style="width: 14.28%">Km</div>
                            <div style="width: 14.28%">Jm</div>
                            <div style="width: 14.28%">Sb</div>
                        </div>
                        
                        <div class="d-flex flex-wrap">
                            @for($k = 0; $k < $firstDayOfWeek; $k++)
                                <div class="mini-cal-day empty"></div>
                            @endfor

                            @for($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $dt = \Carbon\Carbon::createFromDate($currYear, $currMonth, $day);
                                    $dStr = $dt->format('Y-m-d');
                                    $evts = $semEvents[$dStr] ?? collect();
                                    $isWeekend = ($dt->dayOfWeek == 0 || $dt->dayOfWeek == 6);
                                    
                                    $bgClass = 'day-eff';
                                    if ($isWeekend) $bgClass .= ' day-weekend';
                                    
                                    $hasHol = $evts->contains('is_holiday', true);
                                    $hasAct = $evts->contains('is_holiday', false);
                                    
                                    if ($hasHol && $hasAct) $bgClass = 'day-mix';
                                    elseif ($hasHol) $bgClass = 'day-hol';
                                    elseif ($hasAct) $bgClass = 'day-act';

                                    // Build Popover Content
                                    $popTitle = $dt->translatedFormat('d F Y');
                                    $popContent = '';
                                    if ($evts->isNotEmpty()) {
                                        foreach ($evts as $e) {
                                            $icon = $e->is_holiday ? '🔴' : '🔵';
                                            $type = $e->is_holiday ? 'Libur' : 'Kegiatan';
                                            $popContent .= "<div class='mb-2 pb-2 border-bottom last-border-0'>";
                                            $popContent .= "<div class='fw-bold small'>$icon $type: $e->description</div>";
                                            if (!empty($e->affected_classes)) {
                                                $classNames = \App\Models\SchoolClass::whereIn('id', $e->affected_classes)->pluck('name')->implode(', ');
                                                $popContent .= "<div class='text-muted' style='font-size:0.7rem;'>Target: $classNames</div>";
                                            } else {
                                                $popContent .= "<div class='text-muted' style='font-size:0.7rem;'>Target: Seluruh Unit</div>";
                                            }
                                            $popContent .= "</div>";
                                        }
                                    } elseif ($isWeekend) {
                                        $popContent = "<div class='small'>🔴 Libur Akhir Pekan</div>";
                                    } else {
                                        $popContent = "<div class='small text-success'>🟢 Hari Efektif Belajar</div>";
                                    }
                                @endphp
                                <div class="mini-cal-day {{ $bgClass }}" 
                                     data-bs-toggle="popover" 
                                     data-bs-trigger="hover" 
                                     data-bs-html="true"
                                     title="{{ $popTitle }}"
                                     data-bs-content="{{ $popContent }}">
                                    {{ $day }}
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    </div>

    <div class="mt-5 d-flex justify-content-center gap-4 text-muted small fw-bold">
        <div class="d-flex align-items-center"><span class="mini-cal-day day-eff me-2" style="width:20px; height:20px;"></span> Efektif</div>
        <div class="d-flex align-items-center"><span class="mini-cal-day day-hol me-2" style="width:20px; height:20px;"></span> Libur</div>
        <div class="d-flex align-items-center"><span class="mini-cal-day day-act me-2" style="width:20px; height:20px;"></span> Kegiatan</div>
        <div class="d-flex align-items-center"><span class="mini-cal-day day-mix me-2" style="width:20px; height:20px;"></span> Mixed</div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
          return new bootstrap.Popover(popoverTriggerEl)
        })
    });
</script>
@endsection
