@extends('layouts.app')

@section('title', 'Buat Permintaan Dokumen')

@section('content')
<div class="container-fluid">
    <div class="card col-md-10 mx-auto">
        <div class="card-header">
            <h3 class="card-title">Buat Permintaan Dokumen Baru</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('curriculum.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Dokumen / Folder</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Contoh: Perangkat Ajar Matematika XI IPA" required>
                </div>

                <!-- Basic Info -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="academic_year_id" class="form-label">Tahun Ajaran</label>
                        <select name="academic_year_id" id="academic_year_id" class="form-select" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $year->status == 'active' ? 'selected' : '' }}>
                                    {{ $year->name }} {{ $year->status == 'active' ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select name="semester" id="semester" class="form-select" required>
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="due_date" class="form-label">Batas Pengumpulan (Deadline)</label>
                        <input type="date" name="due_date" id="due_date" class="form-control" required>
                    </div>
                </div>

                <!-- Filters and Teacher Selection -->
                <div class="card bg-light border-0 mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-funnel"></i> Target Spesifik</h6>
                        
                        <!-- Row 1: Filters -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="unit_id" class="form-label">Unit Pendidikan</label>
                                <select name="target_units[]" id="unit_id" class="form-select filter-trigger" multiple size="3">
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="subject_id" class="form-label">Mata Pelajaran</label>
                                <select name="target_subjects[]" id="subject_id" class="form-select filter-trigger" multiple size="3">
                                    <option value="" disabled>-- Pilih Unit --</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="grade_level" class="form-label">Tingkat/Grade</label>
                                <select name="target_grades[]" id="grade_level" class="form-select filter-trigger" multiple size="3">
                                    <option value="" disabled>-- Pilih Unit --</option>
                                </select>
                            </div>
                        </div>

                        <hr>

                        <!-- Row 2: Dual Listbox for Teachers -->
                        <div class="row align-items-center">
                            <!-- Left: Source -->
                            <div class="col-md-5">
                                <label class="form-label fw-bold">Guru Tersedia (Hasil Filter & Cari)</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                    <input type="text" id="teacher_search" class="form-control border-start-0 ps-0" placeholder="Ketik nama guru...">
                                </div>
                                <select id="source_teachers" class="form-select shadow-sm" multiple style="height: 300px;">
                                    <option value="" disabled>-- Kriteria Belum Dipilih --</option>
                                </select>
                                <div class="mt-1">
                                    <small class="text-muted" id="teacher-hint">Pilih filter di atas untuk memunculkan guru.</small>
                                </div>
                            </div>
                            
                            <!-- Center: Buttons -->
                            <div class="col-md-2">
                                <div class="d-flex flex-column align-items-center gap-2 py-3">
                                    <button type="button" id="btn-add" class="btn btn-primary w-100" title="Tambahkan yang dipilih">
                                        <i class="bi bi-chevron-right"></i> Tambah
                                    </button>
                                    <button type="button" id="btn-remove" class="btn btn-outline-danger w-100" title="Hapus yang dipilih">
                                        <i class="bi bi-chevron-left"></i> Hapus
                                    </button>
                                    
                                    <div class="w-100 border-top my-2"></div>
                                    
                                    <button type="button" id="btn-add-all" class="btn btn-sm btn-outline-secondary w-100" title="Tambahkan Semua">
                                        <i class="bi bi-chevron-double-right"></i> Semua
                                    </button>
                                    <button type="button" id="btn-remove-all" class="btn btn-sm btn-outline-secondary w-100" title="Reset Pilihan">
                                        <i class="bi bi-chevron-double-left"></i> Reset
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Right: Target -->
                            <div class="col-md-5">
                                <label class="form-label fw-bold text-success">Guru Terpilih (Penerima)</label>
                                <!-- Spacer to align with the input box on the left -->
                                <div class="d-none d-md-block" style="height: 42px;"></div> 
                                
                                <select name="target_users[]" id="target_users" class="form-select shadow-sm border-success" multiple style="height: 300px;">
                                    <!-- Selected Teachers go here -->
                                </select>
                                <div class="mt-1">
                                    <small class="text-muted"><i class="bi bi-info-circle"></i> Guru di kolom ini akan menerima notifikasi.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi / Instruksi</label>
                    <textarea name="description" id="description" class="form-control" rows="4" placeholder="Jelaskan detail dokumen yang harus dikumpulkan..."></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('curriculum.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Buat Permintaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const form = document.querySelector('form');
        const unitSelect = document.getElementById('unit_id');
        const subjectSelect = document.getElementById('subject_id');
        const gradeSelect = document.getElementById('grade_level');
        const teacherSearchInput = document.getElementById('teacher_search');
        
        const sourceSelect = document.getElementById('source_teachers');
        const targetSelect = document.getElementById('target_users');
        
        const btnAdd = document.getElementById('btn-add');
        const btnRemove = document.getElementById('btn-remove');
        const btnAddAll = document.getElementById('btn-add-all');
        const btnRemoveAll = document.getElementById('btn-remove-all');
        const teacherHint = document.getElementById('teacher-hint');

        // Initial Data (PHP to JS)
        // If strict Edit support needed, Controller must pass hydrated users.
        const initialUsers = @json($selectedTeachers ?? []); 

        // Initialize Target List
        if (initialUsers && initialUsers.length > 0) {
            initialUsers.forEach(u => {
                const opt = new Option(u.name, u.id, true, true);
                targetSelect.appendChild(opt);
            });
        }

        // Event Listeners for Filters
        const triggers = document.querySelectorAll('.filter-trigger');
        triggers.forEach(trigger => {
            trigger.addEventListener('change', function() {
                // If Unit changes, update Subject/Grade
                if (trigger.id === 'unit_id') {
                    updateSubjectsAndGrades();
                }
                // Always fetch teachers when any filter changes
                fetchTeachers();
            });
        });

        // Search Input Listener
        let debounceTimer;
        if (teacherSearchInput) {
            teacherSearchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    fetchTeachers(true); // true = force manual search mode if input exists
                }, 500);
            });
        }

        // Helper: Get Selected Values from Multi-Select
        function getSelectValues(select) {
            return Array.from(select.selectedOptions).map(option => option.value);
        }

        // Update Subjects and Grades based on Unit
        function updateSubjectsAndGrades() {
            const unitIds = getSelectValues(unitSelect);
            
            // Clear
            subjectSelect.innerHTML = '<option value="" disabled>Loading...</option>';
            gradeSelect.innerHTML = '<option value="" disabled>Loading...</option>';

            if (unitIds.length === 0) {
                subjectSelect.innerHTML = '<option value="" disabled>-- Pilih Unit --</option>';
                gradeSelect.innerHTML = '<option value="" disabled>-- Pilih Unit --</option>';
                return;
            }

            const params = new URLSearchParams();
            params.append('unit_id', unitIds.join(','));

            // Fetch Subjects
            fetch("{{ route('curriculum.get-subjects') }}?" + params.toString())
                .then(r => r.json())
                .then(data => {
                    subjectSelect.innerHTML = '';
                    data.forEach(item => {
                        subjectSelect.appendChild(new Option(item.name, item.id));
                    });
                });

            // Fetch Grades
            fetch("{{ route('curriculum.get-grades') }}?" + params.toString())
                .then(r => r.json())
                .then(data => {
                    gradeSelect.innerHTML = '';
                    data.forEach(grade => {
                        gradeSelect.appendChild(new Option(grade, grade));
                    });
                });
        }

        // Main Teacher Fetcher
        function fetchTeachers(manualSearch = false) {
            sourceSelect.innerHTML = '<option disabled>Loading...</option>';
            teacherHint.innerText = 'Memuat...';

            const params = new URLSearchParams();
            
            // Check manual search
            const query = teacherSearchInput ? teacherSearchInput.value.trim() : '';
            if (query.length > 0) {
                params.append('name', query); 
            } else {
                // Filter Mode
                const unitIds = getSelectValues(unitSelect);
                const subjectIds = getSelectValues(subjectSelect);
                const grades = getSelectValues(gradeSelect);

                if (unitIds.length === 0 && subjectIds.length === 0 && grades.length === 0) {
                    sourceSelect.innerHTML = '<option disabled>-- Kriteria Belum Dipilih --</option>';
                    teacherHint.innerText = 'Pilih filter di atas.';
                    return;
                }

                if (unitIds.length > 0) params.append('unit_id', unitIds.join(','));
                if (subjectIds.length > 0) params.append('subject_id', subjectIds.join(','));
                if (grades.length > 0) params.append('grade', grades.join(','));
            }
            
            fetch("{{ route('curriculum.get-teachers') }}?" + params.toString())
                .then(response => response.json())
                .then(data => {
                    sourceSelect.innerHTML = '';
                    if (data.length === 0) {
                        sourceSelect.innerHTML = '<option disabled>-- Tidak Ditemukan --</option>';
                        teacherHint.innerText = 'Tidak ada hasil.';
                    } else {
                        // Filter out teachers already in Target List
                        const existingIds = Array.from(targetSelect.options).map(o => o.value);
                        let count = 0;
                        
                        data.forEach(teacher => {
                            if (!existingIds.includes(String(teacher.id))) {
                                sourceSelect.appendChild(new Option(teacher.name, teacher.id));
                                count++;
                            }
                        });
                        
                        if (count === 0 && data.length > 0) {
                             teacherHint.innerText = 'Semua hasil sudah dipilih.';
                        } else {
                             teacherHint.innerText = count + ' guru ditemukan.';
                        }
                    }
                })
                .catch(err => {
                    console.error(err);
                    sourceSelect.innerHTML = '<option disabled>Error loading</option>';
                });
        }

        // Button Actions
        function moveOptions(from, to) {
            const selected = Array.from(from.selectedOptions);
            selected.forEach(opt => {
                // Check if duplicate in target
                let exists = false;
                for (let i=0; i < to.options.length; i++) {
                    if (to.options[i].value === opt.value) {
                         exists = true; break;
                    }
                }
                if (!exists) {
                    to.appendChild(opt); 
                } else {
                    opt.remove(); 
                }
            });
        }

        if (btnAdd) btnAdd.addEventListener('click', () => moveOptions(sourceSelect, targetSelect));
        if (btnRemove) btnRemove.addEventListener('click', () => {
             Array.from(targetSelect.selectedOptions).forEach(o => o.remove());
        });
        
        if (btnAddAll) btnAddAll.addEventListener('click', () => {
             Array.from(sourceSelect.options).forEach(opt => {
                 if (!opt.disabled) targetSelect.appendChild(opt);
             });
        });

        if (btnRemoveAll) btnRemoveAll.addEventListener('click', () => {
            targetSelect.innerHTML = '';
        });

        // Form Submission
        form.addEventListener('submit', function() {
            // Select all in Target Selection so they are sent
            Array.from(targetSelect.options).forEach(option => option.selected = true);
        });

    });
</script>
@endpush
@endsection
