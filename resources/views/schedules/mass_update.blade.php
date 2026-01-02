@extends('layouts.app')

@section('title', 'Input Jadwal Massal - ' . $class->name)

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('schedules.index') }}">Jadwal</a></li>
                    <li class="breadcrumb-item active">Input Massal</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0">Input Jadwal Massal</h2>
            <p class="text-muted mb-0">Kelas: <span class="badge bg-primary fs-6">{{ $class->name }}</span> | Unit: {{ $class->unit->name }} | TA: {{ $class->academicYear->name ?? '-' }}</p>
        </div>
        <div>
            <a href="{{ route('schedules.index', ['unit_id' => $class->unit_id, 'class_id' => $class->id]) }}" class="btn btn-light border rounded-pill px-4 fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <form action="{{ route('schedules.mass-store') }}" method="POST" id="massScheduleForm">
            @csrf
            <input type="hidden" name="class_id" value="{{ $class->id }}">
            
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-grid-3x3 me-2"></i>Matriks Jadwal Mingguan</h5>
            </div>

            <div class="card-body p-0 overflow-auto">
                <table class="table table-bordered table-striped mb-0 align-middle text-center" style="min-width: 1400px;" id="scheduleTable">
                    <thead class="bg-light text-uppercase small fw-bold">
                        <tr>
                            <th style="width: 50px;" class="py-3">No</th>
                            @foreach($days as $day)
                                <th class="py-3">{{ $day }}</th>
                            @endforeach
                            <th style="width: 50px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleTableBody">
                        @foreach($gridRows as $rowIndex => $rowData)
                            <tr class="schedule-row-item" data-index="{{ $rowIndex }}">
                                <td class="bg-light fw-bold text-muted">{{ $rowIndex + 1 }}</td>
                                @foreach($days as $day)
                                    @php
                                        $d = $rowData[$day];
                                        $currentVal = $d['assignment_id'];
                                        $currentBreakName = $d['break_name'];
                                        $currentStart = $d['start'];
                                        $currentEnd = $d['end'];
                                        $isBreak = $d['is_break'];
                                    @endphp
                                    <td class="p-2 {{ $isBreak ? 'bg-danger-subtle bg-opacity-10' : '' }}">
                                        <div class="d-flex flex-column gap-1">
                                            <select name="schedules[{{ $rowIndex }}][days][{{ $day }}][assignment_id]" 
                                                    class="form-select form-select-sm border-0 bg-light rounded-3 schedule-select"
                                                    onchange="handleAssignmentChange(this, {{ $rowIndex }}, '{{ $day }}')">
                                                <option value="">-- Kosong --</option>
                                                <optgroup label="TIPE ISTIRAHAT">
                                                    <option value="break" class="text-danger fw-bold" {{ $currentVal == 'break' ? 'selected' : '' }}>
                                                        -- ISTIRAHAT (Manual) --
                                                    </option>
                                                    @foreach($breakSlots as $bSlot)
                                                        @php
                                                            $bst = \Carbon\Carbon::parse($bSlot->start_time)->format('H:i');
                                                            $ben = \Carbon\Carbon::parse($bSlot->end_time)->format('H:i');
                                                        @endphp
                                                        <option value="break" 
                                                                data-name="{{ $bSlot->name }}" 
                                                                data-start="{{ $bst }}" 
                                                                data-end="{{ $ben }}"
                                                                class="text-danger"
                                                                {{ ($currentVal == 'break' && $currentBreakName == $bSlot->name) ? 'selected' : '' }}>
                                                            {{ $bSlot->name }} ({{ $bst }} - {{ $ben }})
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="MATA PELAJARAN">
                                                    @foreach($assignments as $assignment)
                                                        <option value="{{ $assignment->id }}" {{ $currentVal == $assignment->id ? 'selected' : '' }}>
                                                            {{ $assignment->subject->name }} ({{ $assignment->user->name }})
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            </select>

                                            {{-- Time Input --}}
                                            <div class="d-flex align-items-center gap-1">
                                                <input type="time" name="schedules[{{ $rowIndex }}][days][{{ $day }}][start_time]" 
                                                       class="form-control form-control-sm border-0 bg-white shadow-sm px-1 text-center" 
                                                       value="{{ $currentStart }}" style="font-size: 0.75rem;">
                                                <span class="text-muted small">-</span>
                                                <input type="time" name="schedules[{{ $rowIndex }}][days][{{ $day }}][end_time]" 
                                                       class="form-control form-control-sm border-0 bg-white shadow-sm px-1 text-center" 
                                                       value="{{ $currentEnd }}" style="font-size: 0.75rem;">
                                            </div>
                                            
                                            <div id="break_input_{{ $rowIndex }}_{{ $day }}" 
                                                 class="mt-1 {{ $currentVal == 'break' ? '' : 'd-none' }}">
                                                <input type="text" 
                                                       name="schedules[{{ $rowIndex }}][days][{{ $day }}][break_name]" 
                                                       class="form-control form-control-sm border-0 bg-white shadow-sm rounded-3" 
                                                       placeholder="Nama Istirahat..."
                                                       value="{{ $currentBreakName }}" style="font-size: 0.75rem;">
                                            </div>
                                        </div>
                                    </td>
                                @endforeach
                                <td class="bg-light">
                                    <button type="button" class="btn btn-outline-danger btn-sm border-0 p-1" onclick="removeRow(this)" title="Hapus Baris">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
                <p class="small text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i> Gunakan halaman ini untuk mengisi atau mengedit seluruh jadwal kelas sekaligus.
                </p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-success rounded-pill px-4 fw-bold" onclick="addNewRow()">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Baris
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        <i class="bi bi-save2 me-1"></i> Simpan Semua Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Template Baris Baru --}}
<template id="newRowTemplate">
    <tr class="schedule-row-item">
        <td class="bg-light fw-bold text-muted text-center small">#</td>
        @foreach($days as $day)
            <td class="p-2">
                <div class="d-flex flex-column gap-1">
                    <select name="schedules[INDEX][days][{{ $day }}][assignment_id]" 
                            class="form-select form-select-sm border-0 bg-light rounded-3 schedule-select"
                            onchange="handleAssignmentChange(this, INDEX, '{{ $day }}')">
                        <option value="">-- Kosong --</option>
                        <optgroup label="TIPE ISTIRAHAT">
                            <option value="break" class="text-danger fw-bold">-- ISTIRAHAT (Manual) --</option>
                            @foreach($breakSlots as $bSlot)
                                @php
                                    $bst = \Carbon\Carbon::parse($bSlot->start_time)->format('H:i');
                                    $ben = \Carbon\Carbon::parse($bSlot->end_time)->format('H:i');
                                @endphp
                                <option value="break" 
                                        data-name="{{ $bSlot->name }}" 
                                        data-start="{{ $bst }}" 
                                        data-end="{{ $ben }}"
                                        class="text-danger">
                                    {{ $bSlot->name }} ({{ $bst }} - {{ $ben }})
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="MATA PELAJARAN">
                            @foreach($assignments as $assignment)
                                <option value="{{ $assignment->id }}">
                                    {{ $assignment->subject->name }} ({{ $assignment->user->name }})
                                </option>
                            @endforeach
                        </optgroup>
                    </select>

                    <div class="d-flex align-items-center gap-1">
                        <input type="time" name="schedules[INDEX][days][{{ $day }}][start_time]" 
                               class="form-control form-control-sm border-0 bg-white shadow-sm px-1 text-center" style="font-size: 0.75rem;">
                        <span class="text-muted small">-</span>
                        <input type="time" name="schedules[INDEX][days][{{ $day }}][end_time]" 
                               class="form-control form-control-sm border-0 bg-white shadow-sm px-1 text-center" style="font-size: 0.75rem;">
                    </div>

                    <div id="break_input_INDEX_{{ $day }}" class="mt-1 d-none">
                        <input type="text" name="schedules[INDEX][days][{{ $day }}][break_name]" 
                               class="form-control form-control-sm border-0 bg-white shadow-sm rounded-3" 
                               placeholder="Nama Istirahat..." value="ISTIRAHAT" style="font-size: 0.75rem;">
                    </div>
                </div>
            </td>
        @endforeach
        <td class="bg-light">
            <button type="button" class="btn btn-outline-danger btn-sm border-0 p-1" onclick="removeRow(this)" title="Hapus Baris">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>
</template>

<style>
    .table-bordered th, .table-bordered td { border: 1px solid #dee2e6; }
    .table-striped tbody tr:nth-of-type(even) { background-color: #f1f5f9 !important; }
    .table-striped tbody tr:nth-of-type(odd) { background-color: #ffffff !important; }
    .schedule-row-item:hover { background-color: #e2e8f0 !important; transition: background 0.2s; }
    .schedule-select:focus { background-color: #fff !important; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1) !important; border: 1px solid #dee2e6 !important; }
    .bg-danger-subtle { background-color: #fff5f5; }
    .extra-small { font-size: 0.7rem; }
    input[type="time"]::-webkit-calendar-picker-indicator { background: none; display: none; }
    optgroup { font-weight: bold; color: #333; background: #f8f9fa; }
</style>

<script>
    let currentRowIndex = {{ count($gridRows) }};

    function handleAssignmentChange(select, index, day) {
        const inputDiv = document.getElementById(`break_input_${index}_${day}`);
        const breakNameInput = inputDiv.querySelector('input');
        const startTimeInput = document.getElementsByName(`schedules[${index}][days][${day}][start_time]`)[0];
        const endTimeInput = document.getElementsByName(`schedules[${index}][days][${day}][end_time]`)[0];

        const selectedOption = select.options[select.selectedIndex];
        
        if (select.value === 'break') {
            inputDiv.classList.remove('d-none');
            
            // Auto fill if using break preset
            const presetName = selectedOption.getAttribute('data-name');
            const presetStart = selectedOption.getAttribute('data-start');
            const presetEnd = selectedOption.getAttribute('data-end');

            if (presetName) breakNameInput.value = presetName;
            if (presetStart) startTimeInput.value = presetStart;
            if (presetEnd) endTimeInput.value = presetEnd;
            
        } else {
            inputDiv.classList.add('d-none');
        }
    }

    function removeRow(btn) {
        if(confirm('Hapus baris ini dari grid?')) {
            btn.closest('tr').remove();
        }
    }

    function addNewRow() {
        const template = document.getElementById('newRowTemplate').innerHTML;
        const html = template.replace(/INDEX/g, currentRowIndex);
        document.getElementById('scheduleTableBody').insertAdjacentHTML('beforeend', html);
        currentRowIndex++;
    }


</script>
@endsection
