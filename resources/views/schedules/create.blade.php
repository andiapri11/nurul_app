@extends('layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-plus me-2"></i>Tambah Jadwal Pelajaran</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Kelas:</strong> {{ $class->name }} <br>
                        <strong>Unit:</strong> {{ $class->unit->name }}
                    </div>

                    @if(session('error'))
                         <div class="alert alert-danger">
                             {{ session('error') }}
                         </div>
                    @endif

                    <form action="{{ route('schedules.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $class->id }}">

                        {{-- MAPEL & GURU SELECT --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mata Pelajaran & Guru Pengajar</label>
                            <select name="assignment_id" class="form-select @error('assignment_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Mapel --</option>
                                <option value="break" class="fw-bold text-danger">-- JAM ISTIRAHAT --</option>
                                @foreach($assignments as $assign)
                                    {{-- Value format: subject_id-teacher_id --}}
                                    <option value="{{ $assign->subject_id }}-{{ $assign->user_id }}">
                                        {{ $assign->subject->name }} (Guru: {{ $assign->user->name }})
                                    </option>
                                @endforeach
                            </select>
                            @if($assignments->isEmpty())
                                <small class="text-danger d-block mt-1">
                                    <i class="bi bi-exclamation-circle"></i> Belum ada Guru yang ditugaskan di kelas ini. 
                                    <a href="{{ route('gurukaryawans.index') }}">Atur Guru Mapel dulu</a>.
                                </small>
                            @endif
                            @error('assignment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- HARI --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hari</label>
                            <select name="day" class="form-select @error('day') is-invalid @enderror" required>
                                <option value="">-- Pilih Hari --</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                            </select>
                            @error('day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- BREAK NAME --}}
                        <div class="mb-3 d-none" id="break_name_container">
                            <label class="form-label fw-bold">Nama Istirahat</label>
                            <input type="text" name="break_name" id="break_name" class="form-control" placeholder="Contoh: Istirahat 1, Makan Siang, Sholat Dzuhur">
                            <small class="text-muted italic">Opsional: Jika dikosongkan akan tampil "ISTIRAHAT"</small>
                        </div>

                        {{-- SLOT PRESET --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Slot Waktu (Optional)</label>
                            <select id="slot" class="form-select">
                                <option value="" data-name="" data-is-break="0">-- Manual / Pilih Slot --</option>
                                @if(isset($timeSlots) && $timeSlots->count() > 0)
                                    @foreach($timeSlots as $slot)
                                        <option value="{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}"
                                                data-name="{{ $slot->name }}"
                                                data-is-break="{{ $slot->is_break ? 1 : 0 }}">
                                            {{ $slot->name }} ({{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}) {{ $slot->is_break ? '[ISTIRAHAT]' : '' }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Belum ada setting slot waktu untuk unit ini</option>
                                @endif
                            </select>
                        </div>

                        {{-- JAM --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jam Mulai</label>
                                <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jam Selesai</label>
                                <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <script>
                            const assignmentSelect = document.querySelector('select[name="assignment_id"]');
                            const breakNameContainer = document.getElementById('break_name_container');
                            const breakNameInput = document.getElementById('break_name');
                            const slotSelect = document.getElementById('slot');

                            function toggleBreakName() {
                                if (assignmentSelect.value === 'break') {
                                    breakNameContainer.classList.remove('d-none');
                                } else {
                                    breakNameContainer.classList.add('d-none');
                                    breakNameInput.value = '';
                                }
                            }

                            assignmentSelect.addEventListener('change', toggleBreakName);

                            slotSelect.addEventListener('change', function(){
                                const selectedOption = this.options[this.selectedIndex];
                                const val = selectedOption.value;
                                if(val){
                                    const [start, end] = val.split('-');
                                    document.getElementById('start_time').value = start;
                                    document.getElementById('end_time').value = end;
                                    
                                    const isBreakSlot = selectedOption.getAttribute('data-is-break') == '1';
                                    const slotName = selectedOption.getAttribute('data-name');
                                    
                                    if(isBreakSlot) {
                                        assignmentSelect.value = 'break';
                                        toggleBreakName();
                                        breakNameInput.value = slotName;
                                    }
                                }
                            });
                        </script>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('schedules.index', ['unit_id' => $class->unit_id, 'class_id' => $class->id]) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save me-1"></i> Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Force clock icon in time inputs to be black */
    input[type="time"]::-webkit-calendar-picker-indicator {
        filter: brightness(0) !important; 
        cursor: pointer;
    }
</style>
@endpush
