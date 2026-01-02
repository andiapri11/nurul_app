@extends('layouts.app')

@section('title', 'Kelola Kalender Bulanan')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h3>Kelola Kalender Akademik Bulanan</h3>
            <a href="{{ route('academic-calendars.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body bg-light">
            <form action="{{ route('academic-calendars.manage') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Unit Sekolah</label>
                    <select name="unit_id" class="form-select" onchange="this.form.submit()">
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ $currentUnit->id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Bulan</label>
                    <select name="month" class="form-select" onchange="this.form.submit()">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Tahun</label>
                    <select name="year" class="form-select" onchange="this.form.submit()">
                        @foreach(range(date('Y')-1, date('Y')+1) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <form action="{{ route('academic-calendars.update-month') }}" method="POST">
        @csrf
        <input type="hidden" name="unit_id" value="{{ $currentUnit->id }}">
        <input type="hidden" name="month" value="{{ $month }}">
        <input type="hidden" name="year" value="{{ $year }}">

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Setting Tanggal: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }} - Unit {{ $currentUnit->name }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th width="150" class="text-center">Tanggal</th>
                                <th width="100" class="text-center">Hari</th>
                                <th width="250">Status</th>
                                <th>Keterangan <small class="text-muted fw-normal">(Wajib jika Libur/Kegiatan)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dates as $date)
                                @php
                                    $dateStr = $date->format('Y-m-d');
                                    $record = $calendarData[$dateStr] ?? null;
                                    
                                    $status = 'effective';
                                    $description = '';
                                    if ($record) {
                                        if ($record->is_holiday) $status = 'holiday';
                                        else $status = 'activity';
                                        $description = $record->description;
                                    }
                                    
                                    // Highlight Sundays and Saturdays (Weekend)
                                    $isWeekend = ($date->dayOfWeek === \Carbon\Carbon::SUNDAY || $date->dayOfWeek === \Carbon\Carbon::SATURDAY);
                                    $rowClass = $isWeekend ? 'table-secondary' : '';
                                    
                                    if ($isWeekend && !$record) {
                                         // Auto-select Libur for Weekend if no record exists
                                         $status = 'holiday';
                                         if(!$description) {
                                             $description = ($date->dayOfWeek === \Carbon\Carbon::SUNDAY) ? 'Libur Hari Minggu' : 'Libur Hari Sabtu';
                                         }
                                    }
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td class="text-center fw-bold">{{ $date->format('d/m/Y') }}</td>
                                    <td class="text-center">{{ $date->translatedFormat('l') }}</td>
                                    <td>
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" class="btn-check" name="days[{{ $dateStr }}][status]" id="eff_{{ $dateStr }}" value="effective" {{ $status == 'effective' ? 'checked' : '' }} onchange="toggleDesc('{{ $dateStr }}')">
                                            <label class="btn btn-outline-success btn-sm" for="eff_{{ $dateStr }}">Efektif</label>

                                            <input type="radio" class="btn-check" name="days[{{ $dateStr }}][status]" id="act_{{ $dateStr }}" value="activity" {{ $status == 'activity' ? 'checked' : '' }} onchange="toggleDesc('{{ $dateStr }}')">
                                            <label class="btn btn-outline-primary btn-sm" for="act_{{ $dateStr }}">Kegiatan</label>

                                            <input type="radio" class="btn-check" name="days[{{ $dateStr }}][status]" id="hol_{{ $dateStr }}" value="holiday" {{ $status == 'holiday' ? 'checked' : '' }} onchange="toggleDesc('{{ $dateStr }}')">
                                            <label class="btn btn-outline-danger btn-sm" for="hol_{{ $dateStr }}">Libur</label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="days[{{ $dateStr }}][description]" id="desc_{{ $dateStr }}" class="form-control form-control-sm" value="{{ $description }}" {{ $status == 'effective' ? 'disabled' : '' }} placeholder="Contoh: Maulid Nabi, Class Meeting...">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end p-3 sticky-bottom bg-white border-top">
                <button type="submit" class="btn btn-primary btn-lg shadow"><i class="bi bi-save-fill me-2"></i> SIMPAN PERUBAHAN BULAN INI</button>
            </div>
        </div>
    </form>
</div>

<script>
    function toggleDesc(dateStr) {
        const status = document.querySelector(`input[name="days[${dateStr}][status]"]:checked`).value;
        const descInput = document.getElementById(`desc_${dateStr}`);
        
        if (status === 'effective') {
            descInput.disabled = true;
            descInput.value = ''; // Auto clear? Or keep? Let's clear to avoid confusion
        } else {
            descInput.disabled = false;
        }
    }
</script>
@endsection
