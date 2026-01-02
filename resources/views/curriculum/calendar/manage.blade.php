@extends('layouts.app')

@section('title', 'Kelola Bulanan Kalender Akademik')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h3 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Editor Bulanan (Kurikulum)</h3>
            <p class="text-muted">Edit detail harian untuk bulan tertentu.</p>
        </div>
    </div>

    <!-- Toolbar: Unit & Month Selection -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('curriculum.calendar.manage') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Pilih Unit Sekolah</label>
                    <select name="unit_id" class="form-select" onchange="this.form.submit()">
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ $currentUnit->id == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Bulan</label>
                    <select name="month" class="form-select" onchange="this.form.submit()">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Tahun</label>
                    <select name="year" class="form-select" onchange="this.form.submit()">
                        @foreach(range(now()->year - 1, now()->year + 2) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5 text-end">
                    <a href="{{ route('curriculum.calendar.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard Semester
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Calendar Grid Editor -->
    <form action="{{ route('curriculum.calendar.store') }}" method="POST">
        @csrf
        <input type="hidden" name="unit_id" value="{{ $currentUnit->id }}">
        
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-grid-3x3"></i> Editor Bulan {{ \Carbon\Carbon::create()->month($month)->isoFormat('MMMM') }} {{ $year }}
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th width="10%">Tanggal</th>
                                <th width="10%">Hari</th>
                                <th width="20%">Status</th>
                                <th width="60%">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dates as $date)
                                @php
                                    $dateStr = $date->format('Y-m-d');
                                    $record = $existingRecords[$dateStr] ?? null;
                                    $isWeekend = $date->isWeekend();
                                    
                                    $status = 'effective'; // default
                                    if ($record) {
                                        $status = $record->is_holiday ? 'holiday' : 'activity';
                                    } elseif ($isWeekend) {
                                        $status = 'holiday'; // default visual suggestion
                                    }
                                    
                                    $bgColor = $isWeekend ? 'bg-light' : '';
                                    if ($status == 'holiday') $bgColor = 'table-danger';
                                    if ($status == 'activity') $bgColor = 'table-info';
                                @endphp
                                <tr class="{{ $bgColor }}">
                                    <td class="text-center fw-bold">{{ $date->format('d') }}</td>
                                    <td class="text-center">{{ $date->isoFormat('dddd') }}</td>
                                    <td>
                                        <select name="days[{{ $dateStr }}][type]" class="form-select form-select-sm status-selector" data-date="{{ $dateStr }}">
                                            <option value="effective" {{ $status == 'effective' && !$isWeekend ? 'selected' : '' }}>Hari Efektif</option>
                                            <option value="holiday" {{ $status == 'holiday' ? 'selected' : '' }}>Libur</option>
                                            <option value="activity" {{ $status == 'activity' ? 'selected' : '' }}>Kegiatan Sekolah</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" 
                                               name="days[{{ $dateStr }}][description]" 
                                               class="form-control form-control-sm" 
                                               placeholder="Keterangan (Wajib jika Libur/Kegiatan)"
                                               value="{{ $record->description ?? ($isWeekend && $status=='holiday' ? 'Libur Akhir Pekan' : '') }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light d-flex justify-content-between">
                <small class="text-muted align-self-center">* Perubahan akan disimpan untuk seluruh bulan ini.</small>
                <button type="submit" class="btn btn-success fw-bold px-4">
                    <i class="bi bi-save me-2"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.status-selector').forEach(sel => {
        sel.addEventListener('change', function() {
            const tr = this.closest('tr');
            tr.classList.remove('table-danger', 'table-info', 'bg-light');
            if (this.value === 'holiday') tr.classList.add('table-danger');
            else if (this.value === 'activity') tr.classList.add('table-info');
        });
    });
</script>
@endpush
@endsection
