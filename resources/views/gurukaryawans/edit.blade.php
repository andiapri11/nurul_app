@extends('layouts.app')

@section('content')
<style>
    /* Modern UI Refined */
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
                <h1 class="h3 font-weight-bold text-gray-800 mb-1">Edit Data Guru</h1>
                <p class="text-muted mb-0">Manajemen data lengkap untuk {{ $gurukaryawan->name }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('gurukaryawans.index', request()->query()) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" form="editForm" class="btn btn-primary px-4 shadow-sm">
                    <i class="bi bi-save-fill me-2"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Ada kesalahan input:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('gurukaryawans.update', $gurukaryawan->id) }}" method="POST" id="editForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Hidden Inputs for returning to the correct page/filter --}}
        @foreach(request()->query() as $key => $value)
            @if(!is_array($value))
                <input type="hidden" name="return_params[{{ $key }}]" value="{{ $value }}">
            @endif
        @endforeach

        <div class="row">
            {{-- KOLOM KIRI: Identitas & Jabatan --}}
            <div class="col-lg-4">
                {{-- Data Diri --}}
                <div class="card card-modern">
                    <div class="card-header-modern">
                        <h5><i class="bi bi-person-badge me-2 text-primary"></i> Data Identitas</h5>
                    </div>
                    <div class="card-body p-4 text-center border-bottom bg-light bg-opacity-10">
                        <div class="position-relative d-inline-block mb-3">
                            <img src="{{ $gurukaryawan->photo ? asset('photos/' . $gurukaryawan->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($gurukaryawan->name) . '&background=random&size=128' }}" 
                                 class="rounded-circle shadow-sm border border-3 border-white" 
                                 style="width: 120px; height: 120px; object-fit: cover;" 
                                 id="photoPreview">
                            <label for="photoInput" class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0 shadow" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-camera-fill"></i>
                            </label>
                            <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*" onchange="previewImage(this)">
                        </div>
                        <h6 class="mb-0 fw-bold text-dark">{{ $gurukaryawan->name }}</h6>
                        <small class="text-muted text-uppercase ls-1" style="font-size: 0.7rem;">{{ $gurukaryawan->role }}</small>
                    </div>
                    <div class="card-body p-4">
                        <div class="form-group mb-3">
                            <label class="section-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ $gurukaryawan->name }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="section-label">NIP</label>
                            <input type="text" name="nip" class="form-control" value="{{ $gurukaryawan->nip }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="section-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $gurukaryawan->email }}">
                        </div>

                        <div class="form-group mb-3">
                            <label class="section-label">No. Telepon / WA</label>
                            <input type="text" name="phone" class="form-control" value="{{ $gurukaryawan->phone }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="section-label">Tempat Lahir</label>
                                <input type="text" name="birth_place" class="form-control" value="{{ $gurukaryawan->birth_place }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="section-label">Tanggal Lahir</label>
                                <input type="date" name="birth_date" class="form-control" value="{{ $gurukaryawan->birth_date }}">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="section-label">Jenis Kelamin</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="L" id="genderL" {{ $gurukaryawan->gender == 'L' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="genderL">Laki-laki</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="P" id="genderP" {{ $gurukaryawan->gender == 'P' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="genderP">Perempuan</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="section-label">Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="2">{{ $gurukaryawan->address }}</textarea>
                        </div>

                        <hr class="my-4" style="border-top: 1px dashed #e2e8f0;">

                        {{-- Section Akun Login --}}
                        <div class="mb-4">
                            <h6 class="text-primary fw-bold mb-3"><i class="bi bi-key-fill me-2"></i>Informasi Login</h6>
                            
                            <div class="form-group mb-3">
                                <label class="section-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                    <input type="text" name="username" class="form-control" value="{{ $gurukaryawan->username }}" placeholder="Username login">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="section-label">Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Biarkan kosong jika tidak diubah" autocomplete="new-password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-info-circle me-1"></i>Password saat ini terenkripsi & aman. Isi form di atas <strong>hanya jika</strong> ingin menggantinya.
                                </small>
                            </div>
                        </div>

                        {{-- Hidden Role (required by controller) --}}
                        <input type="hidden" name="role" value="{{ $gurukaryawan->role }}">
                        
                        <hr class="my-4" style="border-top: 1px dashed #e2e8f0;">

                        <div class="form-group mb-3">
                            <label class="section-label">Unit Homebase</label>
                            <select name="unit_id" class="form-select">
                                <option value="">- Pilih Homebase -</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $gurukaryawan->unit_id == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Unit utama tempat guru bernaung.</small>
                        </div>
                    </div>
                </div>

                {{-- Jabatan & Unit (New Structure) --}}
                <div class="card card-modern">
                    <div class="card-header-modern">
                        <h5><i class="bi bi-briefcase me-2 text-info"></i> Jabatan & Unit</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-light border mb-3">
                            <small><i class="bi bi-info-circle"></i> Tentukan jabatan guru di setiap unit. Contoh: Kepala Sekolah di SD, Guru di SMP.</small>
                        </div>

                        <div class="row g-2 mb-3 align-items-end">
                            <div class="col-5">
                                <label class="small text-muted fw-bold">1. Pilih Unit</label>
                                <select id="input-unit-id" class="form-select form-select-sm">
                                    <option value="">Pilih Unit...</option>
                                    <option value="global">Umum / Semua Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-5">
                                <label class="small text-muted fw-bold">2. Pilih Jabatan</label>
                                <select id="input-jabatan-id" class="form-select form-select-sm" disabled>
                                    <option value="">-- Pilih Unit Dulu --</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <button type="button" id="btn-add-jabatan" class="btn btn-info btn-sm w-100" disabled>
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>

                        <div id="list-jabatan-units">
                            @foreach($gurukaryawan->jabatanUnits as $ju)
                            <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2 bg-light list-item-jabatan" id="jab-row-{{ $loop->index }}">
                                <div>
                                    <div class="fw-bold text-dark">{{ $ju->jabatan->nama_jabatan ?? 'Unknown' }}</div>
                                    <div class="small text-muted">{{ $ju->unit->name ?? 'Unknown' }}</div>
                                    <input type="hidden" name="jabatan_units[{{ $loop->index }}][jabatan_id]" value="{{ $ju->jabatan_id }}">
                                    <input type="hidden" name="jabatan_units[{{ $loop->index }}][unit_id]" value="{{ $ju->unit_id }}">
                                </div>
                                <button type="button" class="btn btn-sm text-danger" onclick="removeJabatanRow('{{ $loop->index }}')"><i class="bi bi-x-circle-fill"></i></button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Tugas Mengajar --}}
            <div class="col-lg-8">
                <div class="card card-modern h-100">
                    <div class="card-header-modern">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary text-white rounded p-2">
                                <i class="bi bi-journal-bookmark-fill"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Tugas Mengajar</h5>
                                <small class="text-muted">Manajemen Mata Pelajaran & Kelas</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        {{-- Add Panel --}}
                        <div class="p-4 bg-light border-bottom">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="section-label">1. Pilih Unit</label>
                                    <select id="mapel-select-unit" class="form-select shadow-sm">
                                        <option value="">-- Pilih Unit Sekolah --</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="section-label">2. Pilih Mapel</label>
                                    <select id="mapel-select-subject" class="form-select shadow-sm" disabled>
                                        <option value="">-- Pilih Unit Dulu --</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="section-label">3. Pilih Kelas</label>
                                    <select id="mapel-select-class" class="form-select shadow-sm" disabled>
                                        <option value="">-- Pilih Unit Dulu --</option>
                                    </select>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" id="btn-add-mapel" class="btn btn-primary w-100 shadow-sm" disabled>
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Table List --}}
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
                                    @forelse($gurukaryawan->teachingAssignments as $i => $assign)
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
            </div>
        </div>
    </form>
</div>

{{-- DATA CACHE FOR CLIENT SIDE FILTERING (NO AJAX) --}}
<script>
    // Data dikirim langsung dari controller, dijamin ada!
    const allSubjects = @json($allSubjects);
    const allClasses = @json($allClasses);
    const jabatansByUnit = @json($jabatansByUnit);
    let jabIndex = {{ $gurukaryawan->jabatanUnits->count() }};
    let assignIndex = {{ $gurukaryawan->teachingAssignments->count() }};

    document.addEventListener('DOMContentLoaded', function() {
        
        // --- TOGGLE PASSWORD VISIBILITY ---
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#passwordInput');

        if(togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function (e) {
                // toggle the type attribute
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // toggle the eye icon
                const icon = this.querySelector('i');
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });
        }

        // --- LOGIC JABATAN & UNIT (Cascading Dropdown) ---
        const unitJabSelect = document.getElementById('input-unit-id');
        const jabSelect = document.getElementById('input-jabatan-id');
        const btnAddJabatan = document.getElementById('btn-add-jabatan');

        unitJabSelect.addEventListener('change', function() {
            const unitId = this.value;
            jabSelect.innerHTML = '<option value="">Pilih Jabatan...</option>';
            
            if (!unitId) {
                jabSelect.disabled = true;
                btnAddJabatan.disabled = true;
                return;
            }

            // If "global" is selected, unitId in DB for Jabatan model is usually NULL
            const filterKey = (unitId === 'global') ? "" : unitId;
            const jabatans = jabatansByUnit[filterKey] || [];

            if (jabatans.length > 0) {
                jabatans.forEach(jab => {
                    const option = new Option(jab.nama_jabatan, jab.id);
                    jabSelect.add(option);
                });
                jabSelect.disabled = false;
            } else {
                jabSelect.innerHTML = '<option value="">-- Tidak ada jabatan untuk unit ini --</option>';
                jabSelect.disabled = true;
            }
            btnAddJabatan.disabled = true;
        });

        jabSelect.addEventListener('change', function() {
            btnAddJabatan.disabled = !this.value;
        });

        btnAddJabatan.addEventListener('click', function() {
            const unitIdRaw = unitJabSelect.value;
            const unitName = (unitIdRaw === 'global') ? 'Umum / Semua Unit' : unitJabSelect.options[unitJabSelect.selectedIndex].text;
            const unitId = (unitIdRaw === 'global') ? null : unitIdRaw;
            
            const jabId = jabSelect.value;
            const jabName = jabSelect.options[jabSelect.selectedIndex].text;

            if(!jabId) return;

            const html = `
                <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2 bg-light list-item-jabatan" id="jab-row-${jabIndex}">
                    <div>
                        <div class="fw-bold text-dark">${jabName}</div>
                        <div class="small text-muted">${unitName}</div>
                        <input type="hidden" name="jabatan_units[${jabIndex}][jabatan_id]" value="${jabId}">
                        <input type="hidden" name="jabatan_units[${jabIndex}][unit_id]" value="${unitId || ''}">
                    </div>
                    <button type="button" class="btn btn-sm text-danger" onclick="removeJabatanRow('${jabIndex}')"><i class="bi bi-x-circle-fill"></i></button>
                </div>
            `;
            
            document.getElementById('list-jabatan-units').insertAdjacentHTML('beforeend', html);
            jabIndex++;
            
            // Reset selection
            // jabSelect.value = '';
            // btnAddJabatan.disabled = true;
        });

        // --- LOGIC TUGAS MENGAJAR (NO AJAX = STABLE) ---
        const unitSelect = document.getElementById('mapel-select-unit');
        const subjectSelect = document.getElementById('mapel-select-subject');
        const classSelect = document.getElementById('mapel-select-class');
        const btnAddMapel = document.getElementById('btn-add-mapel');

        unitSelect.addEventListener('change', function() {
            const unitId = this.value;
            
            // Clear dropdowns
            subjectSelect.innerHTML = '<option value="">-- Pilih Mapel --</option>';
            classSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
            
            if (!unitId) {
                subjectSelect.disabled = true;
                classSelect.disabled = true;
                btnAddMapel.disabled = true;
                return;
            }

            // Populate Subjects (from cached variable)
            const subjects = allSubjects[unitId] || [];
            if (subjects.length > 0) {
                subjects.forEach(sub => {
                    const code = sub.code ? ` (${sub.code})` : '';
                    const option = new Option(sub.name + code, sub.id);
                    subjectSelect.add(option);
                });
                subjectSelect.disabled = false;
            } else {
                subjectSelect.innerHTML = '<option value="">-- Tidak ada mapel --</option>';
                subjectSelect.disabled = true;
            }

            // Populate Classes (from cached variable)
            const classes = allClasses[unitId] || [];
            if (classes.length > 0) {
                classes.forEach(cls => {
                    const option = new Option(cls.name, cls.id);
                    // Store unit name inside dataset if needed, but we pull it from select
                    option.dataset.unitName = unitSelect.options[unitSelect.selectedIndex].text;
                    classSelect.add(option);
                });
                classSelect.disabled = false;
            } else {
                classSelect.innerHTML = '<option value="">-- Tidak ada kelas --</option>';
                classSelect.disabled = true;
            }
        });

        // Enable button logic
        [subjectSelect, classSelect].forEach(el => {
            el.addEventListener('change', () => {
                btnAddMapel.disabled = !(subjectSelect.value && classSelect.value);
            });
        });

        // Add to Table Logic
        btnAddMapel.addEventListener('click', function() {
            const subId = subjectSelect.value;
            const subName = subjectSelect.options[subjectSelect.selectedIndex].text;
            const clsId = classSelect.value;
            const clsName = classSelect.options[classSelect.selectedIndex].text;
            const unitName = unitSelect.options[unitSelect.selectedIndex].text;

            // Remove empty row if exists
            const emptyRow = document.getElementById('empty-row');
            if(emptyRow) emptyRow.remove();

            const html = `
                <tr id="assign-row-${assignIndex}">
                    <td><div class="fw-bold">${subName}</div></td>
                    <td><span class="tag-badge">${clsName}</span></td>
                    <td>${unitName}</td>
                    <td class="text-end">
                        <input type="hidden" name="assignments[${assignIndex}][subject_id]" value="${subId}">
                        <input type="hidden" name="assignments[${assignIndex}][class_id]" value="${clsId}">
                        <button type="button" class="btn btn-icon text-danger" onclick="removeAssignRow('${assignIndex}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            document.getElementById('assignments-body').insertAdjacentHTML('beforeend', html);
            assignIndex++;
            
            // Optional: Reset selections for convenience
            // classSelect.value = '';
            // btnAddMapel.disabled = true;
        });
    });

    // Global helper functions
    window.previewImage = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    window.removeJabatanRow = function(id) {
        document.getElementById('jab-row-' + id).remove();
    }
    window.removeAssignRow = function(id) {
        document.getElementById('assign-row-' + id).remove();
    }
</script>
@endsection
