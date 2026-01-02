@extends('layouts.app')



@section('content')
<style>
    .teacher-card {
        border: none;
        border-top: 3px solid #007bff; /* Blue top border */
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
        border-radius: 6px;
        background: #fff;
        height: 100%;
        transition: transform 0.2s;
    }
    .teacher-card:hover {
        /* transform: translateY(-3px);  Removed to prevent scroll jumping */
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .teacher-photo-container {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #e9ecef;
    }
    .teacher-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .teacher-info {
        padding-left: 15px;
    }
    .teacher-nip {
        font-size: 0.85rem;
        color: #888;
        margin-bottom: 2px;
    }
    .teacher-name {
        font-weight: 600;
        font-size: 1.1rem;
        color: #333;
        margin-bottom: 0;
    }
    .teacher-role {
        font-size: 0.9rem;
        color: #666;
    }
    .badge-status-aktif {
        background-color: #28a745;
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .badge-status-nonaktif {
        background-color: #dc3545;
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .section-title {
        font-size: 0.9rem;
        color: #555;
        font-weight: 600;
        margin-top: 15px;
        margin-bottom: 10px;
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
    }
    .pengampu-table {
        width: 100%;
        font-size: 0.85rem;
    }
    .pengampu-table th {
        color: #555;
        font-weight: 600;
        padding: 5px;
        border-bottom: 1px solid #eee;
    }
    .pengampu-table td {
        padding: 5px;
        color: #666;
        vertical-align: middle;
    }
    .card-actions {
        margin-top: 20px;
        display: flex;
        gap: 10px;
        justify-content: flex-start;
        align-items: center;
    }
    .btn-action {
        border-radius: 4px;
        font-size: 0.85rem;
        padding: 5px 12px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-outline-custom {
        border: 1px solid #007bff;
        color: #007bff;
        background: white;
    }
    .btn-outline-custom:hover {
        background: #007bff;
        color: white;
    }
    .delete-btn {
        margin-left: auto;
        border: 1px solid #dc3545;
        color: #dc3545;
        background: #fff;
        padding: 5px 10px;
        border-radius: 4px;
    }
    .delete-btn:hover {
        background: #dc3545;
        color: #fff;
    }
    .card-grid-container {
        padding: 20px;
        background: #f4f6f9;
        min-height: 85vh; /* Ensure full height */
    }
    .header-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        border: 1px solid rgba(0,0,0,0.05);
    }
    .filter-section {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }
    .modern-input-group {
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .modern-input-group .input-group-text {
        background: #f8f9fa;
        color: #6c757d;
        border-right: none;
    }
    .modern-input-group .form-select, 
    .modern-input-group .form-control {
        border-left: none;
    }
    .modern-input-group .form-select:focus, 
    .modern-input-group .form-control:focus {
        border-color: #dee2e6;
        box-shadow: none;
    }
</style>

<div class="card-grid-container">
    <div class="header-controls">
        <div>
            <h4 class="m-0 text-dark fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 1.25rem;">
                <i class="bi bi-people-fill text-primary me-2"></i> Master Data Guru & Karyawan
            </h4>
            <p class="text-muted small mb-0 mt-1">Kelola data pengajar dan staf institusi</p>
        </div>
        
        <div class="filter-section">
            <form action="{{ route('gurukaryawans.index') }}" method="GET" class="d-flex flex-wrap gap-2">
                {{-- Academic Year Filter --}}
                @if(isset($academicYears) && count($academicYears) > 0)
                <div class="input-group input-group-sm modern-input-group" style="width: auto; min-width: 180px;">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                    <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($academicYears as $year)
                            @php
                                $displayName = $year->name;
                                if ($year->semester) {
                                    $displayName .= ' (' . $year->semester . ')';
                                }
                                if ($year->status == 'active') {
                                    $displayName .= ' (Aktif)';
                                }
                            @endphp
                            <option value="{{ $year->id }}" {{ (isset($filterYearId) && $filterYearId == $year->id) ? 'selected' : '' }}>
                                {{ $displayName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Unit Filter --}}
                @if(isset($allowedUnits) && count($allowedUnits) > 0)
                <div class="input-group input-group-sm modern-input-group" style="width: auto; min-width: 140px;">
                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                    <select name="unit_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Unit</option>
                        @foreach($allowedUnits as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Search --}}
                <div class="input-group input-group-sm modern-input-group" style="width: auto; min-width: 200px;">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama/NIP..." value="{{ request('search') }}">
                    @if(request('search'))
                        <a href="{{ route('gurukaryawans.index') }}" class="btn btn-outline-secondary d-flex align-items-center" title="Clear Search">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>

            <div class="vr mx-2 d-none d-lg-block"></div>

            <div class="d-flex gap-1">
                <button class="btn btn-light border btn-sm" onclick="window.location.reload()" title="Muat Ulang">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i> Opsi
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('gurukaryawans.download-template') }}">
                                <i class="bi bi-file-earmark-spreadsheet me-2"></i> Download Template CSV
                            </a>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="bi bi-upload me-2"></i> Import Data CSV
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#copyDataModal">
                                <i class="bi bi-copy me-2"></i> Salin Data Antar Tahun
                            </button>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('gurukaryawans.create') }}" class="btn btn-primary btn-sm px-3">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Guru
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p class="m-0">{{ $message }}</p>
        </div>
    @endif
    
    {{-- Grid Layout start --}}
    <div class="row">
        @foreach ($gurukaryawans as $user)
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            {{-- Card Content --}}
            <div class="teacher-card p-3">
                <div class="d-flex align-items-center">
                    <div class="teacher-photo-container">
                        @if($user->photo)
                            <img src="{{ asset('photos/' . $user->photo) }}" class="teacher-photo" alt="{{ $user->name }}" loading="lazy">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" class="teacher-photo" alt="Default" loading="lazy">
                        @endif
                    </div>
                    <div class="teacher-info">
                        <div class="teacher-nip text-uppercase fw-bold" style="letter-spacing: 0.5px;">{{ $user->nip ?? 'NIP: -' }}</div>
                        <h5 class="teacher-name">{{ $user->name }}</h5>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="badge bg-light text-dark border small fw-normal">{{ ucfirst($user->role) }}</span>
                            @if($user->status == 'aktif')
                                <span class="badge bg-success-subtle text-success border border-success-subtle small fw-medium">Aktif</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle small fw-medium">Non-Aktif</span>
                            @endif
                        </div>
                        
                        {{-- Tampilkan Wali Kelas jika ada --}}
                        @if($user->waliKelasOf)
                            <div class="mt-2 text-primary" style="font-size: 0.8rem;">
                                <i class="bi bi-star-fill me-1"></i> Wali Kelas: <strong>{{ $user->waliKelasOf->name }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Pengampu Section --}}
                <div class="mt-3 border-top pt-2">
                    
                    {{-- Detail Jabatan --}}
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-briefcase text-secondary me-2"></i>
                            <span class="fw-bold small text-uppercase text-muted" style="font-size: 0.75rem;">Jabatan & Unit</span>
                        </div>
                        <div style="max-height: 80px; overflow-y: auto; scrollbar-width: thin;" class="bg-light rounded p-2 border">
                            @forelse($user->jabatanUnits as $ju)
                                <div class="mb-1 lh-sm">
                                    <span class="fw-bold text-dark small">{{ $ju->jabatan->nama_jabatan ?? '-' }}</span>
                                    <small class="d-block text-muted" style="font-size: 0.7rem;">@ {{ $ju->unit->name ?? '-' }}</small>
                                </div>
                            @empty
                                {{-- Fallback ke legacy jabatans jika tabel baru kosong --}}
                                @forelse($user->jabatans as $oldJab)
                                    <div class="mb-1 lh-sm">
                                        <span class="fw-bold text-dark small">{{ $oldJab->nama_jabatan }}</span>
                                        <small class="d-block text-muted" style="font-size: 0.7rem;">{{ ucfirst($oldJab->kategori) }}</small>
                                    </div>
                                @empty
                                    <div class="small text-muted fst-italic">- Tidak ada jabatan -</div>
                                @endforelse
                            @endforelse
                        </div>
                    </div>

                    {{-- Detail Mengajar --}}
                    <div>
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-journal-bookmark text-info me-2"></i>
                            <span class="fw-bold small text-uppercase text-muted" style="font-size: 0.75rem;">Mengajar Pelajaran</span>
                        </div>
                        <div style="max-height: 100px; overflow-y: auto; scrollbar-width: thin;" class="bg-light rounded p-2 border">
                            @forelse($user->teachingAssignments as $assign)
                                <div class="d-flex justify-content-between align-items-start mb-1 pb-1 border-bottom border-light">
                                    <div class="lh-sm">
                                        <span class="d-block fw-semibold text-dark small">{{ $assign->subject->name ?? 'Mapel ?' }}</span>
                                        <small class="text-secondary" style="font-size: 0.7rem;">
                                            {{ $assign->subject->code ?? '' }}
                                        </small>
                                    </div>
                                    <span class="badge bg-white text-primary border shadow-sm" style="font-size: 0.7rem;">
                                        {{ $assign->schoolClass->name ?? '?' }}
                                    </span>
                                </div>
                            @empty
                                <div class="small text-muted fst-italic py-1">- Belum ada jam mengajar -</div>
                            @endforelse
                        </div>
                    </div>

                </div>

                {{-- Actions --}}
                <div class="card-actions">
                    <a href="{{ route('gurukaryawans.edit', array_merge(['gurukaryawan' => $user->id], request()->query())) }}" class="btn-action btn-outline-custom">
                        <i class="bi bi-pencil-square"></i> Profile
                    </a>
                    
                    <form action="{{ route('gurukaryawans.toggle-status', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-action btn-outline-custom" title="Toggle Status">
                            <i class="bi bi-arrow-repeat"></i> Status
                        </button>
                    </form>

                    <form action="{{ route('gurukaryawans.destroy', $user->id) }}" method="POST" class="ml-auto" style="margin-left: auto;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn border-0" onclick="return confirm('Are you sure you want to delete this user?')" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $gurukaryawans->links('pagination::bootstrap-4') }}
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Guru/Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('gurukaryawans.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning mb-3" style="font-size: 0.85rem;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Petunjuk Penting:</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                <li>Gunakan format <strong>CSV (Comma Delimited)</strong>.</li>
                                <li>Jika menggunakan Excel, pilih <strong>Save As</strong> lalu format <strong>CSV (Comma delimited) (*.csv)</strong>.</li>
                                <li>Pastikan header (baris pertama) TIDAK diubah.</li>
                                <li>Unit ID disesuaikan dengan daftar di bawah.</li>
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label fw-bold">Pilih File CSV</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".csv" required>
                            <div class="form-text text-muted">Hanya file dengan ekstensi .csv yang diizinkan.</div>
                        </div>

                        <hr>
                        <h6>Referensi Unit ID</h6>
                        <div class="table-responsive" style="max-height: 150px; overflow-y: auto;">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">ID</th>
                                        <th>Nama Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allowedUnits as $unit)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $unit->id }}</td>
                                        <td>{{ $unit->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted fst-italic">Gunakan ID di atas untuk kolom 'Unit ID' di Excel.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Copy Data Modal -->
    <div class="modal fade" id="copyDataModal" tabindex="-1" aria-labelledby="copyDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="copyDataModalLabel">
                        <i class="bi bi-copy me-2"></i> Salin Data Antar Tahun
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('gurukaryawans.copy-data') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4" style="font-size: 0.85rem;">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <strong>Fungsi:</strong> Fitur ini akan menyalin seluruh <b>Jabatan Unit</b> dan <b>Tugas Mengajar</b> dari tahun sumber ke tahun tujuan.
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">TAHUN SUMBER (Pindahkan Dari)</label>
                            <select name="from_academic_year_id" class="form-select border-2 p-2" required>
                                <option value="">Pilih Tahun Ajaran...</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }} {{ $year->status == 'active' ? '(Aktif)' : '' }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Data akan diambil dari tahun ini.</small>
                        </div>

                        <div class="text-center mb-4">
                            <div class="bg-light d-inline-block rounded-circle p-2 border">
                                <i class="bi bi-arrow-down fs-4 text-primary"></i>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">TAHUN TUJUAN (Salin Ke)</label>
                            <select name="to_academic_year_id" class="form-select border-2 p-2 border-primary" required>
                                <option value="">Pilih Tahun Ajaran...</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }} {{ $year->status == 'active' ? '(Aktif)' : '' }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Data akan disalin ke tahun ini.</small>
                        </div>

                        <div class="bg-light p-3 rounded-3 border">
                            <h6 class="fw-bold mb-2 small text-uppercase" style="letter-spacing: 0.5px;">Aturan Penyalinan:</h6>
                            <ul class="mb-0 small ps-3">
                                <li class="mb-1 text-danger fw-bold">Data yang sudah ada di tahun tujuan tidak akan ditimpa (Skip).</li>
                                <li class="mb-1">Jabatan Struktural akan dilewati jika sudah ada yang menjabat di tahun tujuan.</li>
                                <li class="mb-0">Mata pelajaran per kelas akan dilewati jika sudah ada gurunya di tahun tujuan.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light p-3 rounded-bottom-4">
                        <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menyalin ribuan data mengajar & jabatan? Proses ini dilakukan satu kali per tahun.')">
                            <i class="bi bi-check-lg me-1"></i> Mulai Salin Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
