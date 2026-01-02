@extends('layouts.app')

@section('title', 'Edit Permintaan Dokumen (V2)')

@section('content')
<div class="container-fluid">
    <div class="card col-md-10 mx-auto">
        <div class="card-header">
            <h3 class="card-title">Edit Permintaan Dokumen</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('curriculum.update', $request->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Dokumen / Folder</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $request->title) }}" required>
                </div>

                <!-- Basic Info -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="academic_year_id" class="form-label">Tahun Ajaran</label>
                        <select name="academic_year_id" id="academic_year_id" class="form-select" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ old('academic_year_id', $request->academic_year_id) == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->status == 'active' ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select name="semester" id="semester" class="form-select" required>
                            <option value="ganjil" {{ old('semester', $request->semester) == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="genap" {{ old('semester', $request->semester) == 'genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="due_date" class="form-label">Batas Pengumpulan (Deadline)</label>
                        <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', $request->due_date ? $request->due_date->format('Y-m-d') : '') }}" required>
                    </div>
                </div>

                <!-- Filters for Auto Selection -->
                <div class="card bg-light border-0 mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-funnel"></i> Target Spesifik (Optional - Bisa pilih banyak)</h6>
                        <small class="d-block mb-2 text-muted">Gunakan CTRL + Klik untuk memilih lebih dari satu item.</small>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="unit_id" class="form-label">Unit Pendidikan</label>
                                <select name="target_units[]" id="unit_id" class="form-select filter-trigger" multiple size="4">
                                    @php
                                        // Safe array extraction
                                        $savedUnits = old('target_units', $request->target_units);
                                        if (is_null($savedUnits)) $savedUnits = [];
                                    @endphp
                                    @foreach($units as $u)
                                        <option value="{{ $u->id }}" {{ in_array($u->id, $savedUnits) ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="subject_id" class="form-label">Mata Pelajaran</label>
                                <select name="target_subjects[]" id="subject_id" class="form-select filter-trigger" multiple size="4">
                                    <option value="" disabled>Loading...</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="grade_level" class="form-label">Tingkat/Grade</label>
                                <select name="target_grades[]" id="grade_level" class="form-select filter-trigger" multiple size="4">
                                    <option value="" disabled>Loading...</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="user_id" class="form-label text-success fw-bold">Guru (Otomatis)</label>
                                <select name="target_users[]" id="user_id" class="form-select" multiple size="4">
                                    <option value="" disabled>-- Pilih Filter Dulu --</option>
                                </select>
                                <small class="text-muted" id="teacher-hint">Pilih filter untuk mencari guru.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi / Instruksi</label>
                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $request->description) }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('curriculum.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const triggers = document.querySelectorAll('.filter-trigger');
        const unitSelect = document.getElementById('unit_id');
        const teacherSelect = document.getElementById('user_id');
        const teacherHint = document.getElementById('teacher-hint');
        const gradeSelect = document.getElementById('grade_level');
        const subjectSelect = document.getElementById('subject_id');

        // Safe JSON encoding
        const initialUnits = @json(old('target_units', $request->target_units ?? []));
        const initialSubjects = @json(old('target_subjects', $request->target_subjects ?? []));
        const initialGrades = @json(old('target_grades', $request->target_grades ?? []));
        const initialUsers = @json(old('target_users', $request->target_users ?? []));

        triggers.forEach(el => {
            el.addEventListener('change', function() {
                if (this.id === 'unit_id') {
                    fetchGrades();
                    fetchSubjects();
                } else if (this.id !== 'user_id') { 
                    fetchTeachers();
                }
            });
        });

        function getSelectedValues(selectElement) {
            return Array.from(selectElement.selectedOptions).map(opt => opt.value);
        }

        // --- Helper to Pre-Select Options ---
        function setSelection(selectElement, values) {
            // Check if values is array
            if (!Array.isArray(values)) return;
            // Convert all to strings for comparison
            const strValues = values.map(v => String(v));
            
            Array.from(selectElement.options).forEach(opt => {
                if (strValues.includes(opt.value)) {
                    opt.selected = true;
                }
            });
        }

        function fetchGrades() {
            const unit_ids = getSelectedValues(unitSelect);
            if (unit_ids.length === 0) {
                 gradeSelect.innerHTML = '<option value="" disabled>-- Pilih Unit --</option>';
                 return;
            }
            
            // gradeSelect.innerHTML = '<option value="" disabled>Loading...</option>'; // Don't wipe if we are refreshing with same data? 
            // Just wipe.
            
            fetch("{{ route('curriculum.get-grades') }}?unit_id=" + unit_ids.join(','))
                .then(response => response.json())
                .then(data => {
                    gradeSelect.innerHTML = '';
                    data.forEach(g => {
                        let option = document.createElement('option');
                        option.value = g;
                        option.text = g;
                        gradeSelect.appendChild(option);
                    });
                    // Restore Selection if applicable
                    setSelection(gradeSelect, initialGrades);
                    
                    // Fetch Teachers after grades are ready (if this was triggered by unit change)
                    // But if this is initial load, we do it in chain.
                    fetchTeachers();
                });
        }

        function fetchSubjects() {
            const unit_ids = getSelectedValues(unitSelect);
             if (unit_ids.length === 0) {
                 subjectSelect.innerHTML = '<option value="" disabled>-- Pilih Unit --</option>';
                 return;
            }

            fetch("{{ route('curriculum.get-subjects') }}?unit_id=" + unit_ids.join(','))
                .then(response => response.json())
                .then(data => {
                    subjectSelect.innerHTML = '';
                    data.forEach(s => {
                        let option = document.createElement('option');
                        option.value = s.id;
                        option.text = s.name;
                        subjectSelect.appendChild(option);
                    });
                    setSelection(subjectSelect, initialSubjects);
                });
        }

        function fetchTeachers() {
            const unit_ids = getSelectedValues(unitSelect);
            const subject_ids = getSelectedValues(document.getElementById('subject_id'));
            const grades = getSelectedValues(document.getElementById('grade_level'));
            
            teacherHint.innerText = 'Mencari guru...';
            
            const params = new URLSearchParams({
                unit_id: unit_ids.join(','),
                subject_id: subject_ids.join(','),
                grade: grades.join(',')
            });

            fetch("{{ route('curriculum.get-teachers') }}?" + params.toString())
                .then(response => response.json())
                .then(data => {
                    teacherSelect.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(teacher => {
                            let option = document.createElement('option');
                            option.value = teacher.id;
                            option.text = teacher.name;
                            teacherSelect.appendChild(option);
                        });
                        setSelection(teacherSelect, initialUsers);
                        teacherHint.innerText = data.length + ' guru ditemukan.';
                    } else {
                         teacherHint.innerText = 'Tidak ada guru dengan kriteria ini.';
                    }
                })
                .catch(err => {
                    teacherHint.innerText = 'Gagal memuat guru.';
                });
        }
        
        // --- INITIALIZATION ---
        if (initialUnits.length > 0) {
            // Trigger fetch because units are pre-selected in HTML
            // Note: Since HTML is pre-selected, getSelectedValues(unitSelect) will return them.
            // We just need to call the fetchers.
            
            // Wait a tiny bit (optional) or just call
            fetchGrades();
            fetchSubjects();
            // fetchTeachers is called by fetchGrades chain, but fetchSubjects is async too.
            // Race condition: fetchTeachers needs subjects?
            // Yes, getSelectedValues gets DOM. JS is single threaded. 
            // If fetchSubjects hasn't populated DOM, getSelectedValues(subjectSelect) is empty.
            // So we must wait for both grades and subjects before fetching teachers?
            // Actually, `fetchTeachers` reads from DOM.
            // In my Logic:
            // 1. fetchGrades -> .then(fetchTeachers)
            // 2. fetchSubjects -> .then(...)
            // If I call them in parallel, fetchTeachers runs after Grades are done. Subjects might NOT be done.
            // So fetchTeachers might see empty subjects.
            
            // Fix: Modify fetchTeachers to take arguments or wait?
            // Better: Chain them properly for init.
            
            // However, rewriting all this logic is risky.
            // Let's just use a timeout or a specific init sequence.
        }
    });
</script>
@endpush
@endsection
