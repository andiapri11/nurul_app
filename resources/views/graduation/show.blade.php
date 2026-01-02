@extends('layouts.app')

@section('title', 'Kelola Pengumuman: ' . $announcement->title)

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('graduation.index') }}">Pengumuman</a></li>
                    <li class="breadcrumb-item active">{{ $announcement->title }}</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 font-weight-bold text-gray-800 mb-0">{{ $announcement->title }}</h1>
                <span class="badge {{ $announcement->is_active ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                    {{ $announcement->is_active ? 'Status: Aktif' : 'Status: Tidak Aktif' }}
                </span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- Pengaturan Pengumuman --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 fw-bold text-primary"><i class="bi bi-gear me-2"></i>Pengaturan</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('graduation.settings.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $announcement->id }}">
                        
                        <div class="mb-4">
                            <div class="form-check form-switch p-0 d-flex align-items-center justify-content-between">
                                <label class="fw-bold text-dark" for="is_active">Aktifkan Sekarang</label>
                                <input class="form-check-input ms-0" type="checkbox" name="is_active" id="is_active" value="1" {{ $announcement->is_active ? 'checked' : '' }} style="width: 3em; height: 1.5em; cursor: pointer;">
                            </div>
                            <small class="text-muted d-block mt-2">Hanya satu pengumuman yang dapat aktif dalam satu waktu di unit yang sama.</small>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Judul Pengumuman</label>
                            <input type="text" name="title" class="form-control" value="{{ $announcement->title }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Pesan Pembuka</label>
                            <textarea name="description" class="form-control" rows="4">{{ $announcement->description }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="small fw-bold text-muted mb-1">Tanggal Rilis</label>
                            <input type="datetime-local" name="announcement_date" class="form-control" value="{{ $announcement->announcement_date ? \Carbon\Carbon::parse($announcement->announcement_date)->format('Y-m-d\TH:i') : '' }}">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 shadow-sm rounded-pill">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Daftar Hasil Kelulusan --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold text-primary"><i class="bi bi-people me-2"></i>Daftar Siswa</h5>
                    <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" onclick="showAddModal()">
                        <i class="bi bi-person-plus me-1"></i> Pilih Siswa
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small fw-bold text-muted text-uppercase">
                                    <th class="px-4">Siswa</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                    <th>Pesan Individual</th>
                                    <th>SKL</th>
                                    <th class="text-end px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results as $res)
                                <tr>
                                    <td class="px-4">
                                        <div class="fw-bold">{{ $res->student->nama_lengkap }}</div>
                                        <div class="small text-muted">NIS: {{ $res->student->nis }}</div>
                                    </td>
                                    <td>{{ $res->student->schoolClass->first()->name ?? '-' }}</td>
                                    <td>
                                        @if($res->status == 'lulus')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3">LULUS</span>
                                        @elseif($res->status == 'tidak_lulus')
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3">TIDAK LULUS</span>
                                        @else
                                            <span class="badge bg-secondary px-3">PENDING</span>
                                        @endif
                                    </td>
                                    <td class="small">{{ $res->message ?? '-' }}</td>
                                    <td>
                                        @if($res->skl_file)
                                            <a href="{{ route('graduation.download-skl', $res->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                                <i class="bi bi-download me-1"></i> Unduh
                                            </a>
                                        @else
                                            <span class="text-muted small">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="text-end px-4">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-info" 
                                                    onclick="editResult('{{ $res->student_id }}', '{{ $res->student->nama_lengkap }}', '{{ $res->status }}', '{{ $res->message }}', '{{ $res->skl_file ? 1 : 0 }}')">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('graduation.result.delete', $res->id) }}" method="POST" onsubmit="return confirm('Hapus siswa dari daftar?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted opacity-50 mb-3">
                                            <i class="bi bi-person-bounding-box display-2"></i>
                                        </div>
                                        <h6 class="fw-bold">Belum Ada Siswa Dipilih</h6>
                                        <button class="btn btn-link text-primary" onclick="showAddModal()">Klik untuk mulai memilih siswa</button>
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
</div>

<!-- Modal Manual/Edit -->
<div class="modal fade" id="manualModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog shadow">
        <div class="modal-content border-0">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title" id="manualModalTitle">Pilih Siswa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('graduation.store-single') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="graduation_announcement_id" value="{{ $announcement->id }}">
                <input type="hidden" name="student_id" id="modal-student-id">

                <div class="modal-body p-4">
                    {{-- Section 1: Search & Add (Only for New Entry) --}}
                    <div id="manual-search-section">
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">1. Pilih Kelas</label>
                            <select id="select-class" class="form-select border-0 bg-light">
                                <option value="">Pilih Kelas...</option>
                                @foreach($classes as $cls)
                                    <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="small fw-bold text-muted mb-0">2. Pilih Siswa (Bisa Pilih Banyak)</label>
                                <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none" id="btn-select-all" style="display: none;">Pilih Semua</button>
                            </div>
                            <div id="student-list-container" class="bg-light rounded-3 p-3 overflow-auto" style="max-height: 250px; display: none;">
                                {{-- Checkboxes will be injected here --}}
                            </div>
                            <div id="student-list-empty" class="text-center py-4 bg-light rounded-3">
                                <span class="text-muted small">-- Pilih Kelas Terlebih Dahulu --</span>
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <p class="text-muted small">Siswa yang Anda centang akan ditambahkan ke daftar pengumuman.</p>
                        </div>
                    </div>

                    {{-- Container for hidden inputs in edit mode --}}
                    <div id="student-id-container"></div>

                    {{-- Section 2: Info (Only for Edit Mode) --}}
                    <div id="edit-info-section" style="display: none;" class="mb-4 text-center">
                        <div class="bg-primary bg-opacity-10 p-4 rounded-4">
                             <div class="small text-muted mb-1 text-uppercase">Input Hasil Untuk:</div>
                             <h4 class="fw-bold text-primary mb-0" id="modal-student-name">...</h4>
                        </div>
                    </div>

                    {{-- Section 3: Status & Message (Hidden when adding, shown when editing) --}}
                    <div id="status-input-section" style="display: none;">
                        <hr class="my-4 opacity-10">
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Status Kelulusan</label>
                            <select name="status" id="modal-status" class="form-select border-0 bg-light">
                                <option value="pending">PENDING (Belum Ditentukan)</option>
                                <option value="lulus">LULUS</option>
                                <option value="tidak_lulus">TIDAK LULUS</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Pesan Individual (Opsional)</label>
                            <textarea name="message" id="modal-message" class="form-control border-0 bg-light" rows="3" placeholder="Pesan khusus untuk siswa ini..."></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="small fw-bold text-muted mb-1">Upload SKL (PDF/Gambar)</label>
                            <input type="file" name="skl_file" class="form-control border-0 bg-light">
                            <div id="skl-exists-info" class="small text-success mt-1" style="display: none;">
                                <i class="bi bi-check-circle-fill"></i> SKL sudah terunggah. Pilih file baru untuk mengganti.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="modal-submit-btn" class="btn btn-dark rounded-pill px-4 shadow-sm">Tambahkan Siswa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let myModal;

function showAddModal() {
    const modalEl = document.getElementById('manualModal');
    if (!myModal) myModal = new bootstrap.Modal(modalEl);
    
    document.getElementById('manualModalTitle').innerText = 'Pilih & Tambah Siswa';
    document.getElementById('student-id-container').innerHTML = '';
    document.getElementById('modal-student-name').innerText = '';
    document.getElementById('modal-status').value = 'pending';
    document.getElementById('modal-message').value = '';
    document.getElementById('modal-submit-btn').innerText = 'Tambahkan Terpilih';
    
    document.getElementById('manual-search-section').style.display = 'block';
    document.getElementById('edit-info-section').style.display = 'none';
    document.getElementById('status-input-section').style.display = 'none';
    
    // Reset student list
    document.getElementById('student-list-container').innerHTML = '';
    document.getElementById('student-list-container').style.display = 'none';
    document.getElementById('student-list-empty').style.display = 'block';
    document.getElementById('btn-select-all').style.display = 'none';
    document.getElementById('select-class').value = '';
    
    myModal.show();
}

function editResult(id, name, status, message, hasSkl) {
    const modalEl = document.getElementById('manualModal');
    if (!myModal) myModal = new bootstrap.Modal(modalEl);
    
    document.getElementById('manualModalTitle').innerText = 'Set Status Kelulusan';
    // Use hidden input for single edit
    document.getElementById('student-id-container').innerHTML = `<input type="hidden" name="student_id[]" value="${id}">`;
    document.getElementById('modal-student-name').innerText = name;
    document.getElementById('modal-status').value = status;
    document.getElementById('modal-message').value = (message === '-' ? '' : message);
    document.getElementById('modal-submit-btn').innerText = 'Simpan Hasil';
    
    document.getElementById('skl-exists-info').style.display = (hasSkl == 1 ? 'block' : 'none');
    
    document.getElementById('manual-search-section').style.display = 'none';
    document.getElementById('edit-info-section').style.display = 'block';
    document.getElementById('status-input-section').style.display = 'block';
    
    myModal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('select-class');
    const listContainer = document.getElementById('student-list-container');
    const emptyState = document.getElementById('student-list-empty');
    const selectAllBtn = document.getElementById('btn-select-all');

    classSelect.addEventListener('change', function() {
        const classId = this.value;
        listContainer.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div><span class="ms-2 small">Memuat...</span></div>';
        listContainer.style.display = 'block';
        emptyState.style.display = 'none';
        selectAllBtn.style.display = 'none';

        if (!classId) {
            listContainer.style.display = 'none';
            emptyState.style.display = 'block';
            return;
        }

        fetch(`{{ url('/graduation/get-students') }}/${classId}`)
            .then(response => response.json())
            .then(students => {
                listContainer.innerHTML = '';
                if (students.length > 0) {
                    students.forEach(std => {
                        const div = document.createElement('div');
                        div.className = 'form-check mb-2';
                        div.innerHTML = `
                            <input class="form-check-input" type="checkbox" name="student_id[]" value="${std.id}" id="std-${std.id}">
                            <label class="form-check-label small" for="std-${std.id}">
                                <strong>${std.nama_lengkap}</strong> <span class="text-muted">(NIS: ${std.nis})</span>
                            </label>
                        `;
                        listContainer.appendChild(div);
                    });
                    selectAllBtn.style.display = 'block';
                    selectAllBtn.innerText = 'Pilih Semua';
                } else {
                    listContainer.innerHTML = '<div class="text-center py-3 text-muted small">Tidak ada siswa aktif di kelas ini.</div>';
                }
            });
    });

    selectAllBtn.addEventListener('click', function() {
        const checkboxes = listContainer.querySelectorAll('input[type="checkbox"]');
        const isAllSelected = Array.from(checkboxes).every(cb => cb.checked);
        
        checkboxes.forEach(cb => cb.checked = !isAllSelected);
        this.innerText = isAllSelected ? 'Pilih Semua' : 'Batal Pilih Semua';
    });
});
</script>
@endpush
