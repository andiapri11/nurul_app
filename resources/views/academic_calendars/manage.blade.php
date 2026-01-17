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

    <div class="card shadow-sm mb-4 border-0 bg-light text-dark" style="border-radius: 12px; border: 1px solid #e0e0e0 !important;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-info-circle-fill me-2"></i> Petunjuk Pengisian</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-success me-2">Masuk</span>
                        <span class="small fw-bold">Hari Sekolah Normal</span>
                    </div>
                    <p class="small mb-0 text-muted">Gunakan ini untuk hari KBM biasa. Semua kelas masuk seperti jadwal.</p>
                </div>
                <div class="col-md-3 border-start border-secondary border-opacity-25">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-primary me-2">Kegiatan</span>
                        <span class="small fw-bold">Agenda / Acara</span>
                    </div>
                    <p class="small mb-0 text-muted">KBM ditiadakan, tapi Guru/Siswa berkegiatan. Absensi tetap dilakukan.</p>
                </div>
                <div class="col-md-3 border-start border-secondary border-opacity-25">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-danger me-2">Libur</span>
                        <span class="small fw-bold">Libur Total</span>
                    </div>
                    <p class="small mb-0 text-muted">Sekolah tutup. Tidak ada KBM dan tidak ada absensi pada tanggal ini.</p>
                </div>
                <div class="col-md-3 border-start border-secondary border-opacity-25">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-purple me-2">Mix</span>
                        <span class="small fw-bold">Status Berbeda</span>
                    </div>
                    <p class="small mb-0 text-muted">Gunakan jika sebagian kelas Libur dan sebagian lainnya Kegiatan.</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('academic-calendars.update-month') }}" method="POST">
        @csrf
        <input type="hidden" name="unit_id" value="{{ $currentUnit->id }}">
        <input type="hidden" name="month" value="{{ $month }}">
        <input type="hidden" name="year" value="{{ $year }}">

        <div class="card shadow-sm mb-5">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark text-center">
                    <i class="bi bi-calendar3 me-2 text-primary"></i>
                    {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }} - Unit {{ $currentUnit->name }}
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($dates as $date)
                        @php
                            $dateStr = $date->format('Y-m-d');
                            $records = $calendarData->get($dateStr, collect());
                            
                            $actRecord = $records->firstWhere('is_holiday', false);
                            $holRecord = $records->firstWhere('is_holiday', true);
                            
                            $isWeekend = ($date->dayOfWeek === \Carbon\Carbon::SUNDAY || $date->dayOfWeek === \Carbon\Carbon::SATURDAY);
                            
                            // Determine Initial Mode
                            $mode = 'eff';
                            if ($actRecord && $holRecord) $mode = 'mix';
                            elseif ($holRecord) $mode = 'hol';
                            elseif ($actRecord) $mode = 'act';
                            elseif ($isWeekend) $mode = 'hol'; 

                            $actDesc = $actRecord ? $actRecord->description : 'Kegiatan Sekolah';
                            $actClasses = $actRecord ? ($actRecord->affected_classes ?? []) : [];
                            
                            $holDesc = $holRecord ? $holRecord->description : ($isWeekend ? (($date->dayOfWeek === \Carbon\Carbon::SUNDAY) ? 'Libur Hari Minggu' : 'Libur Hari Sabtu') : 'Libur');
                            $holClasses = $holRecord ? ($holRecord->affected_classes ?? []) : [];
                        @endphp
                        <div class="list-group-item p-3 {{ $isWeekend ? 'bg-light' : '' }} border-bottom" id="row_{{ $dateStr }}">
                            <div class="row g-3 align-items-center">
                                {{-- 1. TANGGAL --}}
                                <div class="col-md-2 text-center text-md-start">
                                    <div class="d-flex align-items-center px-2">
                                        <div class="me-3 text-center" style="min-width: 40px;">
                                            <span class="h4 fw-bold mb-0 {{ $isWeekend ? 'text-danger' : 'text-dark' }}">{{ $date->format('d') }}</span>
                                        </div>
                                        <div>
                                            <div class="small fw-bold text-uppercase text-muted lh-1 mb-1">{{ $date->translatedFormat('D') }}</div>
                                            <div class="small text-muted lh-1 text-nowrap">{{ $date->translatedFormat('M Y') }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. MODE SELECTOR (UTAMA) --}}
                                <div class="col-md-4">
                                    <div class="btn-group w-100 shadow-sm" role="group">
                                        <input type="radio" class="btn-check" name="days[{{ $dateStr }}][mode]" id="mode_eff_{{ $dateStr }}" value="eff" {{ $mode == 'eff' ? 'checked' : '' }} onchange="refreshRow('{{ $dateStr }}')">
                                        <label class="btn btn-outline-success btn-sm fw-bold py-2" for="mode_eff_{{ $dateStr }}"><i class="bi bi-check-circle me-1"></i> MASUK</label>

                                        <input type="radio" class="btn-check" name="days[{{ $dateStr }}][mode]" id="mode_act_{{ $dateStr }}" value="act" {{ $mode == 'act' ? 'checked' : '' }} onchange="refreshRow('{{ $dateStr }}')">
                                        <label class="btn btn-outline-primary btn-sm fw-bold py-2" for="mode_act_{{ $dateStr }}"><i class="bi bi-flag me-1"></i> KEGIATAN</label>

                                        <input type="radio" class="btn-check" name="days[{{ $dateStr }}][mode]" id="mode_hol_{{ $dateStr }}" value="hol" {{ $mode == 'hol' ? 'checked' : '' }} onchange="refreshRow('{{ $dateStr }}')">
                                        <label class="btn btn-outline-danger btn-sm fw-bold py-2" for="mode_hol_{{ $dateStr }}"><i class="bi bi-emoji-sunglasses me-1"></i> LIBUR</label>

                                        <input type="radio" class="btn-check" name="days[{{ $dateStr }}][mode]" id="mode_mix_{{ $dateStr }}" value="mix" {{ $mode == 'mix' ? 'checked' : '' }} onchange="refreshRow('{{ $dateStr }}')">
                                        <label class="btn btn-outline-purple btn-sm fw-bold py-2" for="mode_mix_{{ $dateStr }}"><i class="bi bi-shuffle me-1"></i> MIX</label>
                                    </div>
                                </div>

                                {{-- 3. KONFIGURASI DINAMIS --}}
                                <div class="col-md-6" id="config_{{ $dateStr }}">
                                    <!-- Bagian Kegiatan -->
                                    <div class="box-act mb-2 p-2 border rounded bg-white shadow-sm" style="display:none;">
                                        <div class="row g-2 align-items-center">
                                            <input type="hidden" name="days[{{ $dateStr }}][activity][active]" id="act_active_{{ $dateStr }}" value="0">
                                            <div class="col-md-5">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text bg-primary text-white border-primary"><i class="bi bi-info-circle"></i></span>
                                                    <input type="text" name="days[{{ $dateStr }}][activity][description]" class="form-control border-primary" value="{{ $actDesc }}" placeholder="Nama Kegiatan...">
                                                </div>
                                            </div>
                                            <div class="col-md-7 d-flex">
                                                <div class="form-check me-2 mt-1" title="Pilih Semua Kelas">
                                                    <input class="form-check-input select-all-sub border-primary" type="checkbox" data-target="act_cls_{{ $dateStr }}" id="all_act_{{ $dateStr }}">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <select name="days[{{ $dateStr }}][activity][classes][]" id="act_cls_{{ $dateStr }}" class="form-select form-select-sm select2" multiple data-placeholder="Seluruh Kelas (Default)">
                                                        @foreach($unitClasses as $cls)
                                                            <option value="{{ $cls->id }}" {{ in_array($cls->id, $actClasses) ? 'selected' : '' }}>{{ $cls->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bagian Libur -->
                                    <div class="box-hol p-2 border rounded bg-white shadow-sm d-none">
                                        <div class="row g-2 align-items-center">
                                            <input type="hidden" name="days[{{ $dateStr }}][holiday][active]" id="hol_active_{{ $dateStr }}" value="0">
                                            <div class="col-md-5">
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text bg-danger text-white border-danger"><i class="bi bi-info-circle"></i></span>
                                                    <input type="text" name="days[{{ $dateStr }}][holiday][description]" class="form-control border-danger" value="{{ $holDesc }}" placeholder="Alasan Libur...">
                                                </div>
                                            </div>
                                            <div class="col-md-7 d-flex">
                                                <div class="form-check me-2 mt-1" title="Pilih Semua Kelas">
                                                    <input class="form-check-input select-all-sub border-danger" type="checkbox" data-target="hol_cls_{{ $dateStr }}" id="all_hol_{{ $dateStr }}">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <select name="days[{{ $dateStr }}][holiday][classes][]" id="hol_cls_{{ $dateStr }}" class="form-select form-select-sm select2" multiple data-placeholder="Seluruh Kelas (Default)">
                                                        @foreach($unitClasses as $cls)
                                                            <option value="{{ $cls->id }}" {{ in_array($cls->id, $holClasses) ? 'selected' : '' }}>{{ $cls->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Label Hadir Efektif -->
                                    <div class="box-eff text-success fw-bold py-1 px-3 align-items-center d-none">
                                        <i class="bi bi-calendar-check-fill me-2 h5 mb-0"></i> 
                                        <span>Hari Efektif Belajar <small class="text-muted fw-normal fs-xs">(KBM Normal)</small></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer text-center p-4 sticky-bottom bg-white border-top shadow-lg" style="z-index: 1020;">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow rounded-pill fw-bold">
                    <i class="bi bi-save-fill me-2"></i> SIMPAN SEMUA PERUBAHAN
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    body { background-color: #f8f9fa !important; }
    .content-wrapper { background-color: #f8f9fa !important; }
    
    .fs-xs { font-size: 0.75rem; }
    .btn-group .btn { transition: all 0.2s; }
    .btn-check:checked + .btn-outline-success { background-color: #198754; color: white; }
    .btn-check:checked + .btn-outline-primary { background-color: #0d6efd; color: white; }
    .btn-check:checked + .btn-outline-danger { background-color: #dc3545; color: white; }
    
    .btn-outline-purple { color: #6f42c1; border-color: #6f42c1; }
    .btn-outline-purple:hover { background-color: #6f42c1; color: white; }
    .btn-check:checked + .btn-outline-purple { background-color: #6f42c1; color: white; }
    .bg-purple { background-color: #6f42c1 !important; color: white !important; }
    
    .list-group-item {
        transition: all 0.2s;
        border-left: 4px solid transparent;
    }
    .list-group-item:hover {
        background-color: #ffffff;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.02);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initial setup for all rows
        @foreach($dates as $date)
            refreshRow('{{ $date->format('Y-m-d') }}', true);
        @endforeach

        // Handle Select All
        document.querySelectorAll('.select-all-sub').forEach(cb => {
            cb.addEventListener('change', function() {
                const targetId = this.dataset.target;
                const selectEl = $(`#${targetId}`);
                selectEl.find('option:not(:disabled)').prop('selected', this.checked);
                selectEl.trigger('change');
            });
        });

        // Sync Select All Checkboxes & Mutual Exclusivity
        $('.select2').on('change', function() {
            const selectId = this.id;
            const parts = selectId.split('_'); 
            const type = parts[0]; // act or hol
            const dateStr = parts.slice(2).join('_');
            
            // 1. Sync Select All Checkbox
            const cbId = 'all_' + type + '_' + dateStr;
            const cb = document.getElementById(cbId);
            if (cb) {
                const total = $(this).find('option:not(:disabled)').length;
                const selected = $(this).find('option:selected').length;
                cb.checked = (total === selected && total > 0);
            }

            // 2. Mutual Exclusivity for MIX mode
            const otherType = (type === 'act') ? 'hol' : 'act';
            const otherSelectId = `${otherType}_cls_${dateStr}`;
            const otherSelect = $(`#${otherSelectId}`);
            
            if (otherSelect.length) {
                const selectedValues = $(this).val() || [];
                
                // Disable selected classes in the other dropdown
                otherSelect.find('option').each(function() {
                    const val = $(this).val();
                    if (selectedValues.includes(val)) {
                        $(this).prop('disabled', true);
                        $(this).prop('selected', false); // Deselect if it was selected
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
                
                // Refresh the other Select2 to show disabled state
                otherSelect.trigger('change.select2'); 
            }
        });
    });

    function refreshRow(dateStr, isInitial = false) {
        const mode = document.querySelector(`input[name="days[${dateStr}][mode]"]:checked`).value;
        const parent = document.getElementById(`config_${dateStr}`);
        const boxEff = parent.querySelector('.box-eff');
        const boxAct = parent.querySelector('.box-act');
        const boxHol = parent.querySelector('.box-hol');
        
        const actActive = document.getElementById(`act_active_${dateStr}`);
        const holActive = document.getElementById(`hol_active_${dateStr}`);

        // Helper function uses Bootstrap classes to avoid !important conflicts
        const setVisible = (el, visible, flex = false) => {
            if (visible) {
                el.classList.remove('d-none');
                if (flex) el.classList.add('d-flex');
                else el.classList.add('d-block');
            } else {
                el.classList.add('d-none');
                el.classList.remove('d-flex', 'd-block');
            }
        };

        // Hide all first
        setVisible(boxEff, false);
        setVisible(boxAct, false);
        setVisible(boxHol, false);
        
        actActive.value = "0";
        holActive.value = "0";

        if (mode === 'eff') {
            setVisible(boxEff, true, true);
        } else if (mode === 'act') {
            setVisible(boxAct, true);
            actActive.value = "1";
        } else if (mode === 'hol') {
            setVisible(boxHol, true);
            holActive.value = "1";
        } else if (mode === 'mix') {
            setVisible(boxAct, true);
            setVisible(boxHol, true);
            actActive.value = "1";
            holActive.value = "1";
        }
    }
</script>
@endsection
