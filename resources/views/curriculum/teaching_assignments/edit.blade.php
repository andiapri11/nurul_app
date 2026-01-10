@extends('layouts.app')

@section('content')
<style>
    .card-modern {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        background: #fff;
        margin-bottom: 24px;
        overflow: hidden;
    }
    .card-header-modern {
        background: linear-gradient(to right, #f8f9fa, #ffffff);
        padding: 20px 24px;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-header-modern h5 {
        margin: 0;
        font-weight: 700;
        color: #2d3748;
        font-size: 1.1rem;
    }
    .section-label {
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.75rem;
        font-weight: 700;
        margin-bottom: 12px;
        display: block;
    }
    .table-custom th {
        background-color: #f7fafc;
        color: #4a5568;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 12px 24px;
    }
    .table-custom td {
        vertical-align: middle;
        padding: 16px 24px;
        color: #2d3748;
    }
    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .btn-icon:hover {
        background-color: #edf2f7;
    }
    .tag-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        background-color: #ebf8ff;
        color: #3182ce;
        border: 1px solid #bee3f8;
    }
</style>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 font-weight-bold text-gray-800 mb-1">Edit Tugas Mengajar</h1>
                <p class="text-muted mb-0">Atur penempatan mengajar untuk <strong>{{ $user->name }}</strong></p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('curriculum.teaching-assignments.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" form="editForm" class="btn btn-primary px-4 shadow-sm">
                    <i class="bi bi-save-fill me-2"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-danger" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div>
                    <strong>Perhatian!</strong><br>
                    {{ session('error') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- Profile Info (Read Only) --}}
        <div class="col-lg-4">
            <div class="card card-modern">
                <div class="card-header-modern">
                    <h5><i class="bi bi-person-badge me-2 text-primary"></i> Data Guru</h5>
                </div>
                <div class="card-body p-4 text-center border-bottom bg-light bg-opacity-10">
                    <div class="position-relative d-inline-block mb-3">
                        <img src="{{ $user->photo ? asset('photos/' . $user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&size=128' }}" 
                             class="rounded-circle shadow-sm border border-3 border-white" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <h6 class="mb-0 fw-bold text-dark">{{ $user->name }}</h6>
                    <small class="text-muted text-uppercase ls-1" style="font-size: 0.7rem;">NIP: {{ $user->nip ?? '-' }}</small>
                    <div class="mt-2">
                        <span class="badge bg-primary rounded-pill px-3">{{ $user->unit->name ?? 'Lintas Unit' }}</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 shadow-sm" style="font-size: 0.85rem;">
                        <i class="bi bi-info-circle-fill me-2"></i> Anda sebagai <strong>Wakil Kurikulum</strong> memiliki akses untuk mendistribusikan beban mengajar bagi guru ini pada tahun akademik yang berlaku.
                    </div>
                    <div class="form-group mb-3">
                        <label class="section-label">Status Pegawai</label>
                        <p class="mb-0 fw-500 {{ $user->status == 'aktif' ? 'text-success' : 'text-danger' }}">
                            {{ strtoupper($user->status) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tugas Mengajar (Editable) --}}
        <div class="col-lg-8">
            <form action="{{ route('curriculum.teaching-assignments.update', $user->id) }}" method="POST" id="editForm">
                @csrf
                @method('PUT')

                <div class="card card-modern">
                    <div class="card-header-modern">
                        <h5><i class="bi bi-journal-plus me-2 text-success"></i> Penempatan Mengajar</h5>
                    </div>
                    
                    <div class="card-body p-4 bg-light border-bottom">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="section-label mb-2">Pilih Unit</label>
                                <select class="form-select border-0 shadow-sm" id="mapel-select-unit">
                                    <option value="">Pilih Unit...</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="section-label mb-2">Mata Pelajaran</label>
                                <select class="form-select border-0 shadow-sm" id="mapel-select-subject" disabled>
                                    <option value="">Pilih Unit Dulu...</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="section-label mb-2">Kelas</label>
                                <select class="form-select border-0 shadow-sm" id="mapel-select-class" disabled>
                                    <option value="">Pilih Unit Dulu...</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-success w-100 shadow-sm" id="btn-add-assignment" disabled>
                                    <i class="bi bi-plus-lg"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-custom table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Unit</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="assignments-body">
                                    @forelse($user->teachingAssignments as $i => $assign)
                                        <tr id="assign-row-{{ $i }}">
                                            <td>
                                                <div class="fw-bold">{{ $assign->subject->name ?? '-' }}</div>
                                                @if($assign->subject->code)
                                                    <small class="text-muted">{{ $assign->subject->code }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="tag-badge">{{ $assign->schoolClass->name ?? '-' }}</span>
                                            </td>
                                            <td>{{ $assign->schoolClass->unit->name ?? '-' }}</td>
                                            <td class="text-end">
                                                <input type="hidden" name="assignments[{{ $i }}][subject_id]" value="{{ $assign->subject_id }}">
                                                <input type="hidden" name="assignments[{{ $i }}][class_id]" value="{{ $assign->class_id }}">
                                                <button type="button" class="btn btn-icon text-danger" onclick="removeAssignRow('{{ $i }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="empty-row">
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="bi bi-inbox display-4 d-block mb-3 opacity-25"></i>
                                                Belum ada tugas mengajar assigned.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const allSubjects = @json($allSubjects);
    const allClasses = @json($allClasses);
    let assignIndex = {{ $user->teachingAssignments->count() }};

    document.addEventListener('DOMContentLoaded', function() {
        const unitSelect = document.getElementById('mapel-select-unit');
        const subjectSelect = document.getElementById('mapel-select-subject');
        const classSelect = document.getElementById('mapel-select-class');
        const btnAdd = document.getElementById('btn-add-assignment');

        unitSelect.addEventListener('change', function() {
            const unitId = this.value;
            subjectSelect.innerHTML = '<option value="">Pilih Mapel...</option>';
            classSelect.innerHTML = '<option value="">Pilih Kelas...</option>';
            
            if (!unitId) {
                subjectSelect.disabled = true;
                classSelect.disabled = true;
                btnAdd.disabled = true;
                return;
            }

            const subjects = allSubjects[unitId] || [];
            const classes = allClasses[unitId] || [];

            if (subjects.length > 0) {
                subjects.forEach(s => subjectSelect.add(new Option(s.name, s.id)));
                subjectSelect.disabled = false;
            } else {
                subjectSelect.innerHTML = '<option value="">-- No Subjects --</option>';
            }

            if (classes.length > 0) {
                classes.forEach(c => classSelect.add(new Option(c.name, c.id)));
                classSelect.disabled = false;
            } else {
                classSelect.innerHTML = '<option value="">-- No Classes --</option>';
            }
        });

        [subjectSelect, classSelect].forEach(el => {
            el.addEventListener('change', () => {
                btnAdd.disabled = !(subjectSelect.value && classSelect.value);
            });
        });

        btnAdd.addEventListener('click', function() {
            const subjectId = subjectSelect.value;
            const subjectName = subjectSelect.options[subjectSelect.selectedIndex].text;
            const classId = classSelect.value;
            const className = classSelect.options[classSelect.selectedIndex].text;
            const unitName = unitSelect.options[unitSelect.selectedIndex].text;

            if(!subjectId || !classId) return;

            const emptyRow = document.getElementById('empty-row');
            if(emptyRow) emptyRow.remove();

            const html = `
                <tr id="assign-row-${assignIndex}">
                    <td><div class="fw-bold">${subjectName}</div></td>
                    <td><span class="tag-badge">${className}</span></td>
                    <td>${unitName}</td>
                    <td class="text-end">
                        <input type="hidden" name="assignments[${assignIndex}][subject_id]" value="${subjectId}">
                        <input type="hidden" name="assignments[${assignIndex}][class_id]" value="${classId}">
                        <button type="button" class="btn btn-icon text-danger" onclick="removeAssignRow('${assignIndex}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            document.getElementById('assignments-body').insertAdjacentHTML('beforeend', html);
            assignIndex++;
        });
    });

    function removeAssignRow(idx) {
        document.getElementById(`assign-row-${idx}`).remove();
        if (document.querySelectorAll('#assignments-body tr').length === 0) {
            document.getElementById('assignments-body').innerHTML = `
                <tr id="empty-row">
                    <td colspan="4" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox display-4 d-block mb-3 opacity-25"></i>
                        Belum ada tugas mengajar assigned.
                    </td>
                </tr>
            `;
        }
    }
</script>
@endsection
