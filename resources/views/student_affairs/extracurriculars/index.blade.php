@extends('layouts.app')

@section('title', 'Daftar Ekstrakurikuler')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header Title Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Ekstrakurikuler</h1>
            <p class="text-muted small mb-0">Wadah kreativitas, bakat, dan pengembangan diri siswa.</p>
        </div>
        <div class="d-flex gap-2">
            @if($isViewingActiveYear)
            <button type="button" class="btn btn-primary shadow-sm px-3 py-2" data-bs-toggle="modal" data-bs-target="#addExtraModal">
                <i class="bi bi-plus-circle me-1"></i> <strong>Tambah Ekskul</strong>
            </button>
            @endif
        </div>
    </div>

    <!-- Stats Dashboard Section -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="stats-card border-0 shadow-sm h-100 bg-gradient-brand">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="d-flex justify-content-between align-items-center position-relative z-1">
                        <div>
                            <div class="stats-label">Total Ekstrakurikuler</div>
                            <div class="stats-value">{{ $extracurriculars->total() }}</div>
                            <div class="stats-footer mt-2"><i class="bi bi-collection me-1"></i> Ragam Kegiatan</div>
                        </div>
                        <div class="stats-icon-bg">
                            <i class="bi bi-activity"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="stats-card border-0 shadow-sm h-100 bg-gradient-success">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="d-flex justify-content-between align-items-center position-relative z-1">
                        <div>
                            <div class="stats-label">Siswa Terdaftar</div>
                            @php 
                                $totalMembers = $extracurriculars->sum('member_count');
                            @endphp
                            <div class="stats-value">{{ $totalMembers }}</div>
                            <div class="stats-footer mt-2"><i class="bi bi-people me-1"></i> Partisipasi Aktif</div>
                        </div>
                        <div class="stats-icon-bg">
                            <i class="bi bi-person-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="stats-card border-0 shadow-sm h-100 bg-gradient-warning">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="d-flex justify-content-between align-items-center position-relative z-1">
                        <div>
                            <div class="stats-label">TP Aktif</div>
                            <div class="stats-value" style="font-size: 1.5rem;">{{ $academicYearId ? \App\Models\AcademicYear::find($academicYearId)->name : '-' }}</div>
                            <div class="stats-footer mt-2"><i class="bi bi-calendar3 me-1"></i> Periode Berjalan</div>
                        </div>
                        <div class="stats-icon-bg">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="card shadow-sm border-0 bg-white" style="border-radius: 15px;">
        <div class="card-body p-4">
            <!-- Filter Section Card -->
            <div class="filter-panel mb-4 p-3 bg-light" style="border-radius: 12px;">
                <form action="{{ route('student-affairs.extracurriculars.index') }}" method="GET" id="extraFilter">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Filter Unit</label>
                            <select name="unit_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                                <option value="">Semua Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">Tahun Pelajaran</label>
                            <select name="academic_year_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                                <option value="">Tahun Pelajaran</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ $academicYearId == $ay->id ? 'selected' : '' }}>
                                        TP {{ $ay->name }} {{ ucfirst($ay->semester) }} {{ $ay->status == 'active' ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Cari Ekstrakurikuler</label>
                            <div class="input-group shadow-sm rounded">
                                <input type="text" name="search" class="form-control border-0" placeholder="Ketik nama ekskul..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Table Section -->
            <div class="table-responsive mt-2">
                <table class="table table-hover align-middle custom-table">
                    <thead>
                        <tr>
                            <th class="text-center" width="60">No</th>
                            <th>Nama Ekstrakurikuler</th>
                            <th>Unit</th>
                            <th>Pembina</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Anggota & Capaian</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($extracurriculars as $extra)
                        <tr>
                            <td class="text-center fw-semibold text-muted">{{ $loop->iteration + $extracurriculars->firstItem() - 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $extra->name }}</div>
                                @if($extra->description)
                                    <small class="text-muted d-block text-truncate" style="max-width: 250px;">{{ $extra->description }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-soft-primary px-2 py-1">{{ $extra->unit->name }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle-sm bg-light-info text-info me-2">
                                        {{ substr($extra->coach_name ?? 'P', 0, 1) }}
                                    </div>
                                    <span class="text-dark">{{ $extra->coach_name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($extra->status == 'active')
                                    <span class="badge rounded-pill bg-success-soft text-success px-3">
                                        <i class="bi bi-check-circle me-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-light text-muted px-3">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('student-affairs.extracurriculars.members', ['extracurricular' => $extra->id, 'academic_year_id' => $academicYearId]) }}" 
                                       class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-none border-primary-light" 
                                       title="Kelola Anggota">
                                        <i class="bi bi-people-fill me-1"></i> {{ $extra->member_count }} Siswa
                                    </a>
                                    <a href="{{ route('student-affairs.extracurriculars.achievements', ['extracurricular' => $extra->id, 'academic_year_id' => $academicYearId]) }}" 
                                       class="btn btn-soft-success btn-sm rounded-pill px-2 shadow-none" 
                                       title="Laporan & Capaian">
                                        <i class="bi bi-trophy-fill"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden bg-white border">
                                    @if($isViewingActiveYear)
                                    <button type="button" class="btn btn-white btn-sm px-3 border-0" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editExtraModal"
                                        data-bs-id="{{ $extra->id }}"
                                        data-bs-name="{{ $extra->name }}"
                                        data-bs-unit="{{ $extra->unit_id }}"
                                        data-bs-coach="{{ $extra->coach_name }}"
                                        data-bs-status="{{ $extra->status }}"
                                        data-bs-desc="{{ $extra->description }}"
                                        title="Edit">
                                        <i class="bi bi-pencil-square text-warning"></i>
                                    </button>
                                    <form action="{{ route('student-affairs.extracurriculars.destroy', $extra->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus ekskul ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-white btn-sm px-3 border-0 border-start" title="Hapus">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </form>
                                    @else
                                    <span class="px-3 py-1 bg-light text-muted small"><i class="bi bi-archive-fill"></i></span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state text-muted">
                                    <i class="bi bi-activity fs-1 mb-3 d-block text-light"></i>
                                    <p class="mb-0">Tidak ada data ekstrakurikuler ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 px-2">
                <div class="text-muted small">
                    Memuat {{ $extracurriculars->count() }} dari total {{ $extracurriculars->total() }} data
                </div>
                <div>
                    {{ $extracurriculars->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addExtraModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-primary text-white py-4" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle-fill me-2"></i> Tambah Ekstrakurikuler</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('student-affairs.extracurriculars.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Tahun Pelajaran</label>
                        <input type="hidden" name="academic_year_id" value="{{ $academicYearId }}">
                        <div class="p-2 bg-light rounded text-dark fw-bold border">
                             TP {{ $academicYearId ? \App\Models\AcademicYear::find($academicYearId)->name : '-' }}
                        </div>
                        <small class="text-muted" style="font-size: 0.7rem;">Data akan otomatis terhubung ke tahun pelajaran aktif ini.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Grup Unit <span class="text-danger">*</span></label>
                        <select name="unit_id" class="form-select border-primary-soft" required>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nama Ekstrakurikuler <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Pramuka, PMR, Futsal">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nama Pembina/Pelatih</label>
                        <input type="text" name="coach_name" class="form-control" placeholder="Nama Guru atau Instruktur">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active">Aktif</option>
                            <option value="inactive">Non-Aktif</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small text-uppercase">Keterangan Tambahan</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Opsional..."></textarea>
                    </div>
                </div>
                <div class="modal-footer p-3 border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Ekskul</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editExtraModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-warning py-4" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
                <h5 class="modal-title fw-bold text-white"><i class="bi bi-pencil-fill me-2"></i> Perbarui Ekstrakurikuler</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editExtraForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Grup Unit <span class="text-danger">*</span></label>
                        <select name="unit_id" id="edit_unit_id" class="form-select border-primary-soft" required>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nama Ekstrakurikuler <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nama Pembina</label>
                        <input type="text" name="coach_name" id="edit_coach" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Status <span class="text-danger">*</span></label>
                        <select name="status" id="edit_status" class="form-select" required>
                            <option value="active">Aktif</option>
                            <option value="inactive">Non-Aktif</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small text-uppercase">Keterangan Tambahan</label>
                        <textarea name="description" id="edit_desc" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer p-3 border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 text-white">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Premium Gradients */
    .bg-gradient-brand { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: white; }
    .bg-gradient-success { background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); color: white; }
    .bg-gradient-warning { background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%); color: white; }

    /* Card Styling */
    .stats-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .stats-label { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; opacity: 0.8; letter-spacing: 1px; }
    .stats-value { font-size: 2rem; font-weight: 800; }
    .stats-footer { font-size: 0.75rem; opacity: 0.7; }
    
    .stats-icon-bg {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 5rem;
        opacity: 0.15;
    }

    /* Table Styling */
    .custom-table thead th {
        background-color: #f8f9fc;
        border-top: none;
        border-bottom: 2px solid #e3e6f0;
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        color: #4e73df;
        padding: 15px;
    }
    .custom-table tbody td {
        padding: 18px 15px;
        border-bottom: 1px solid #f1f1f1;
    }
    .avatar-circle-sm {
        width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 0.8rem;
    }
    .bg-light-info { background-color: #e0f2fe; }
    
    /* Badges */
    .bg-soft-primary { background-color: #eef2ff; color: #4e73df; }
    .bg-success-soft { background-color: #dcfce7; color: #166534; }
    .btn-soft-success { background-color: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
    .btn-soft-success:hover { background-color: #16a34a; color: white; }
    
    .btn-white { background: white; color: #333; }
    .btn-white:hover { background: #f8f9fa; }
    .border-primary-light { border-color: #dae1f3 !important; }
    .border-primary-soft { border: 1px solid #dae1f3; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editModal = document.getElementById('editExtraModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-bs-id');
            var name = button.getAttribute('data-bs-name');
            var unit = button.getAttribute('data-bs-unit');
            var coach = button.getAttribute('data-bs-coach');
            var status = button.getAttribute('data-bs-status');
            var desc = button.getAttribute('data-bs-desc');

            var form = editModal.querySelector('#editExtraForm');
            form.action = "{{ route('student-affairs.extracurriculars.index') }}/" + id;

            editModal.querySelector('#edit_name').value = name;
            editModal.querySelector('#edit_unit_id').value = unit;
            editModal.querySelector('#edit_coach').value = coach;
            editModal.querySelector('#edit_status').value = status;
            editModal.querySelector('#edit_desc').value = desc;
        });
    });
</script>
@endpush
@endsection
