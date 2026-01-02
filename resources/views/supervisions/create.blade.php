@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Buat Jadwal Supervisi Baru</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('principal.supervisions.store') }}" method="POST">
                        @csrf
                        
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-calendar-event"></i> Tahun Pelajaran Aktif: <strong>{{ isset($activeYear) && $activeYear ? $activeYear->name . ' (' . ucfirst($activeYear->semester) . ')' : 'Belum diatur' }}</strong>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Unit Pendidikan</label>
                            @if($managedUnits->count() == 1)
                                <input type="text" class="form-control" value="{{ $managedUnits->first()->name }}" readonly>
                                <input type="hidden" name="unit_id" value="{{ $managedUnits->first()->id }}">
                            @else
                                <select name="unit_id" class="form-control" required>
                                    @foreach($managedUnits as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pilih Guru</label>
                            <select name="teacher_id" class="form-control" required>
                                <option value="">-- Cari Guru --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="row">
                             <div class="col-md-6 mb-3">
                                <label class="form-label">Mata Pelajaran</label>
                                <select name="subject_id" class="form-control">
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sasaran Kelas</label>
                                <select name="school_class_id" class="form-control">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Waktu (Opsional)</label>
                                <input type="time" name="time" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan Awal</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('principal.supervisions.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const teacherSelect = document.querySelector('select[name="teacher_id"]');
        const subjectSelect = document.querySelector('select[name="subject_id"]');
        const classSelect = document.querySelector('select[name="school_class_id"]');
        
        teacherSelect.addEventListener('change', function() {
            const teacherId = this.value;
            if(!teacherId) return;

            // Show loading state
            subjectSelect.innerHTML = '<option>Loading...</option>';
            classSelect.innerHTML = '<option>Loading...</option>';

            fetch(`{{ url('principal/supervisions/teacher-info') }}/${teacherId}`)
                .then(response => response.json())
                .then(data => {
                    // Update Subjects
                    subjectSelect.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
                    if(data.subjects.length > 0) {
                        data.subjects.forEach(subject => {
                            subjectSelect.innerHTML += `<option value="${subject.id}">${subject.name} (${subject.code})</option>`;
                        });
                    } else {
                         subjectSelect.innerHTML += '<option value="" disabled>Guru ini belum memiliki jadwal mengajar</option>';
                    }

                    // Update Classes (Optional: only filter if needed, otherwise keep all or just the unit's classes)
                    // Currently the Controller returns all classes in the unit.
                    classSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
                    data.classes.forEach(cls => {
                        classSelect.innerHTML += `<option value="${cls.id}">${cls.name}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    subjectSelect.innerHTML = '<option value="">Error fetching data</option>';
                    classSelect.innerHTML = '<option value="">Error fetching data</option>';
                });
        });
    });
</script>
@endpush
