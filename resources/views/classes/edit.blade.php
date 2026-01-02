@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
<style>
    /* Modern Custom CSS for Transfer List */
    .transfer-list-box {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        height: 300px;
        overflow-y: auto;
        padding: 0;
    }
    .transfer-list-item {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.15s;
    }
    .transfer-list-item:hover {
        background-color: #f8f9fa;
        color: #000;
    }
    .transfer-list-item.selected {
        background-color: #007bff;
        color: white;
    }
    .transfer-controls {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
        gap: 10px;
    }
    .card-modern-header {
        background-color: #fff;
        border-bottom: 1px solid #eaeaea;
        padding: 1rem 1.25rem;
    }
</style>

<div class="container-fluid">
    <div class="card bg-white shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="m-0 font-weight-bold ml-2">Edit Kelas</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('classes.index') }}" class="btn btn-secondary btn-sm mr-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" form="editClassForm" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-save"></i> Update
                </button>
            </div>
        </div>
        
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('classes.update', $class->id) }}" method="POST" id="editClassForm">
                @csrf
                @method('PUT')
                
                {{-- Top Row: Names and Level --}}
                <div class="row mb-3">
                     <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label font-weight-bold">Nama Kelas</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{ $class->name }}" placeholder="Nama Kelas" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="grade_code" class="form-label font-weight-bold">Level</label>
                            <select name="grade_code" class="form-control" id="grade_code">
                                <option value="">Select Level</option>
                                @foreach(['TK A', 'TK B', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'] as $level)
                                    <option value="{{ $level }}" {{ $class->grade_code == $level ? 'selected' : '' }}>{{ $level }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Second Row: Code and Unit --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code" class="form-label font-weight-bold">Kode Kelas</label>
                            <input type="text" name="code" class="form-control" id="code" value="{{ $class->code }}" placeholder="Kode Kelas (e.g. 7A, X-IPA-1)">
                        </div>
                    </div>
                    <div class="col-md-6">
                         <div class="form-group">
                            <label for="unit_id" class="form-label font-weight-bold">Unit Pendidikan</label>
                            <select name="unit_id" class="form-control" id="unit_id" required>
                                <option value="">-- Pilih Unit --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $class->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Left Section: Student Transfer List --}}
                    <div class="col-lg-8">
                        <div class="card shadow-none border">
                            <div class="card-header bg-light">
                                <h6 class="m-0 font-weight-bold text-dark">Daftar Siswa</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    {{-- Available Students (Students not in this class) --}}
                                    <div class="col-md-5">
                                        <label class="small text-muted mb-1">Siswa Tersedia (Belum masuk kelas ini)</label>
                                        <input type="text" id="search-available" class="form-control form-control-sm mb-2" placeholder="Cari siswa">
                                        <div class="transfer-list-box" id="list-available">
                                             @foreach($students as $student)
                                                 @if(!in_array($student->id, $currentStudentIds))
                                                     <div class="transfer-list-item" data-id="{{ $student->id }}" data-unit="{{ $student->unit_id }}">
                                                         {{ $student->nama_lengkap }} <small class="text-muted">({{ $student->nis }})</small>
                                                     </div>
                                                 @endif
                                             @endforeach
                                         </div>
                                    </div>

                                    {{-- Controls --}}
                                    <div class="col-md-2 d-flex flex-column justify-content-center align-items-center">
                                        <button type="button" class="btn btn-outline-secondary btn-sm mb-2" id="btn-move-right">
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-move-left">
                                            <i class="bi bi-chevron-left"></i>
                                        </button>
                                    </div>

                                    {{-- Selected Students (Students currently in this class) --}}
                                    <div class="col-md-5">
                                        <label class="small text-muted mb-1" id="count-selected">Siswa di Kelas Ini</label>
                                        <input type="text" id="search-selected" class="form-control form-control-sm mb-2" placeholder="Cari siswa">
                                        <div class="transfer-list-box" id="list-selected">
                                             @foreach($students as $student)
                                                 @if(in_array($student->id, $currentStudentIds))
                                                     <div class="transfer-list-item" data-id="{{ $student->id }}" data-unit="{{ $student->unit_id }}">
                                                         {{ $student->nama_lengkap }} <small class="text-muted">({{ $student->nis }})</small>
                                                     </div>
                                                 @endif
                                             @endforeach
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Section: Class Leader / Teacher --}}
                    <div class="col-lg-4">
                         <div class="card shadow-none border h-100">
                             <div class="card-header bg-light">
                                <h6 class="m-0 font-weight-bold text-dark">Ketua Kelas & Wali Kelas</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="student_leader_id" class="font-weight-bold small">Ketua Kelas</label>
                                    <select name="student_leader_id" id="student_leader_id" class="form-control">
                                        <option value="">Pilih Ketua Siswa</option>
                                        {{-- Initially populate with current class students --}}
                                        @foreach($students as $student)
                                            @if(in_array($student->id, $currentStudentIds))
                                                <option value="{{ $student->id }}" {{ $class->student_leader_id == $student->id ? 'selected' : '' }}>
                                                    {{ $student->nama_lengkap }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Pilih dari daftar siswa yang dipilih</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="teacher_id" class="font-weight-bold small">Wali Kelas</label>
                                     <select name="teacher_id" id="teacher_id" class="form-control">
                                        <option value="">-- Pilih Wali Kelas --</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ $class->teacher_id == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>

                {{-- Hidden Inputs for Students --}}
                <div id="hidden-students-container">
                    {{-- Pre-populate hidden inputs for current students --}}
                    @foreach($students as $student)
                        @if(in_array($student->id, $currentStudentIds))
                            <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
                        @endif
                    @endforeach
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const listAvailable = document.getElementById('list-available');
        const listSelected = document.getElementById('list-selected');
        const btnMoveRight = document.getElementById('btn-move-right');
        const btnMoveLeft = document.getElementById('btn-move-left');
        const searchAvailable = document.getElementById('search-available');
        const searchSelected = document.getElementById('search-selected');
        const hiddenContainer = document.getElementById('hidden-students-container');
        const countLabel = document.getElementById('count-selected');
        const leaderSelect = document.getElementById('student_leader_id');
        const unitSelect = document.getElementById('unit_id');
        
        // Initial Count
        updateCount();

        let availableItems = Array.from(listAvailable.children);
        
        // Item Selection Logic
        function toggleSelection(item) {
            item.classList.toggle('selected');
        }

        [listAvailable, listSelected].forEach(list => {
            list.addEventListener('click', e => {
                if(e.target.classList.contains('transfer-list-item')) {
                    toggleSelection(e.target);
                }
            });
        });

        // Filter by Unit Logic
        unitSelect.addEventListener('change', function() {
            const unitId = this.value;
            availableItems.forEach(item => {
                // If unitId is empty show all, else filter
                // Only filter available items? Usually yes, to prevent assigning cross-unit students if not desired
                if (!unitId || item.dataset.unit == unitId) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                    item.classList.remove('selected'); 
                }
            });
        });

        // Search Logic
        function filterList(input, list) {
            const term = input.value.toLowerCase();
            Array.from(list.children).forEach(item => {
                const text = item.textContent.toLowerCase();
                const unitId = unitSelect.value;
                
                if (list === listAvailable && unitId && item.dataset.unit != unitId) {
                    return; 
                }
                
                if(text.includes(term)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        searchAvailable.addEventListener('keyup', () => filterList(searchAvailable, listAvailable));
        searchSelected.addEventListener('keyup', () => filterList(searchSelected, listSelected));

        // Move Right
        btnMoveRight.addEventListener('click', () => {
            const selected = listAvailable.querySelectorAll('.transfer-list-item.selected');
            selected.forEach(item => {
                item.classList.remove('selected');
                listSelected.appendChild(item);
                
                // Add to Leader Dropdown
                const opt = document.createElement('option');
                opt.value = item.dataset.id;
                opt.textContent = item.textContent.trim();
                leaderSelect.appendChild(opt);
            });
            updateHiddenInputs();
            updateCount();
        });

        // Move Left
        btnMoveLeft.addEventListener('click', () => {
             const selected = listSelected.querySelectorAll('.transfer-list-item.selected');
            selected.forEach(item => {
                item.classList.remove('selected');
                listAvailable.appendChild(item);

                // Remove from Leader Dropdown
                const opt = leaderSelect.querySelector(`option[value="${item.dataset.id}"]`);
                if(opt) {
                    // Check if it was selected leader, if so reset
                    if(leaderSelect.value == item.dataset.id) leaderSelect.value = "";
                    opt.remove();
                }
            });
            updateHiddenInputs();
            updateCount();
            
            // Re-apply unit filter
             const unitId = unitSelect.value;
             // Only if returning to available list
             if (unitId && selected.length > 0) {
                 selected.forEach(item => {
                     if (item.dataset.unit != unitId) item.style.display = 'none';
                     else item.style.display = '';
                 });
             }
        });

        function updateHiddenInputs() {
            hiddenContainer.innerHTML = '';
            Array.from(listSelected.children).forEach(item => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'student_ids[]';
                input.value = item.dataset.id;
                hiddenContainer.appendChild(input);
            });
        }

        function updateCount() {
            countLabel.textContent = `Siswa di Kelas Ini: ${listSelected.children.length}`;
        }
        
        // Initial setup
        if(unitSelect.value) {
            // Trigger change manually if needed, but since server renders, maybe not needed immediately 
            // unless we want to hide incompatible available students immediately
            unitSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
