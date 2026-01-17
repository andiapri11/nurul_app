@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 animate__animated animate__fadeIn">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <div class="p-4 bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h4 mb-1 fw-bold">Capaian & Laporan: {{ $extracurricular->name }}</h2>
                            <p class="mb-0 opacity-75">Kelola nilai siswa dan unggah laporan kegiatan mingguan/bulanan.</p>
                        </div>
                        <div>
                            <span class="badge bg-white text-primary px-3 py-2" style="border-radius: 20px;">
                                <i class="bi bi-calendar3 me-1"></i> TP {{ $academicYearId ? \App\Models\AcademicYear::find($academicYearId)->name : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 h6 fw-bold">Pilih Periode</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('student-affairs.extracurriculars.achievements', $extracurricular->id) }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Tahun Pelajaran</label>
                            <select name="academic_year_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ $academicYearId == $ay->id ? 'selected' : '' }}>
                                        TP {{ $ay->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    <hr>
                    <div class="d-grid">
                        <a href="{{ route('student-affairs.extracurriculars.members', $extracurricular->id) }}" class="btn btn-outline-primary btn-sm mb-2">
                            <i class="bi bi-people me-1"></i> Kelola Anggota
                        </a>
                        <a href="{{ route('student-affairs.extracurriculars.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Upload Report Card -->
            @if($isViewingActiveYear)
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 h6 fw-bold">Unggah Laporan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('student-affairs.extracurriculars.store-report', $extracurricular->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="academic_year_id" value="{{ $academicYearId }}">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Judul Laporan</label>
                            <input type="text" name="title" class="form-control form-control-sm" placeholder="Contoh: Laporan November" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">File PDF</label>
                            <input type="file" name="file" class="form-control form-control-sm @error('file') is-invalid @enderror" required accept=".pdf">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <small class="text-muted" style="font-size: 0.75rem;">Maksimal 5MB (PDF)</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Keterangan (Opsional)</label>
                            <textarea name="description" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-upload me-1"></i> Unggah Sekarang
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Tabs Navigation -->
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-grades-tab" data-bs-toggle="pill" data-bs-target="#pills-grades" type="button" role="tab" aria-controls="pills-grades" aria-selected="true">
                        <i class="bi bi-trophy me-1"></i> Nilai Capaian Siswa
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-reports-tab" data-bs-toggle="pill" data-bs-target="#pills-reports" type="button" role="tab" aria-controls="pills-reports" aria-selected="false">
                        <i class="bi bi-file-earmark-text me-1"></i> Laporan Kegiatan
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <!-- Tab: Nilai Capaian -->
                <div class="tab-pane fade show active" id="pills-grades" role="tabpanel" aria-labelledby="pills-grades-tab">
                    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                        <form action="{{ route('student-affairs.extracurriculars.update-achievements', $extracurricular->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-header bg-white border-bottom py-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0 h6 fw-bold">Daftar Nilai Siswa</h5>
                                    <div class="d-flex gap-2">
                                        @if($members->count() > 0)
                                        <div class="dropdown">
                                            <button class="btn btn-outline-danger btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('student-affairs.extracurriculars.grades-export', ['extracurricular' => $extracurricular->id, 'semester' => 'ganjil', 'academic_year_id' => $academicYearId]) }}" target="_blank">
                                                        Semester Ganjil
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('student-affairs.extracurriculars.grades-export', ['extracurricular' => $extracurricular->id, 'semester' => 'genap', 'academic_year_id' => $academicYearId]) }}" target="_blank">
                                                        Semester Genap
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        @endif

                                        @if($isViewingActiveYear && $members->count() > 0)
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bi bi-save me-1"></i> Simpan Semuanya
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                {{-- Filters Row --}}
                                <div class="row g-2">
                                    {{-- Unit Filter --}}
                                    <div class="col-md-3">
                                        <select name="filter_unit_id" class="form-select form-select-sm" onchange="window.location.href='{{ route('student-affairs.extracurriculars.achievements', $extracurricular->id) }}?academic_year_id={{ $academicYearId }}&filter_unit_id=' + this.value">
                                            <option value="">- Semua Unit -</option>
                                            @foreach($allowedUnits as $unit)
                                                <option value="{{ $unit->id }}" {{ request('filter_unit_id') == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    {{-- Class Filter --}}
                                    <div class="col-md-3">
                                        <select name="filter_class_id" class="form-select form-select-sm" onchange="window.location.href='{{ route('student-affairs.extracurriculars.achievements', $extracurricular->id) }}?academic_year_id={{ $academicYearId }}&filter_unit_id={{ request('filter_unit_id') }}&filter_class_id=' + this.value">
                                            <option value="">- Semua Kelas -</option>
                                            @foreach($filterClasses as $class)
                                                @if(!request('filter_unit_id') || $class->unit_id == request('filter_unit_id'))
                                                <option value="{{ $class->id }}" {{ request('filter_class_id') == $class->id ? 'selected' : '' }}>
                                                    {{ $class->name }}
                                                </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Search --}}
                                    <div class="col-md-4">
                                        <div class="input-group input-group-sm">
                                            <input type="text" id="searchInput" class="form-control" placeholder="Cari Nama/NIS..." value="{{ request('search') }}">
                                            <button class="btn btn-outline-secondary" type="button" onclick="applySearch()"><i class="bi bi-search"></i></button>
                                        </div>
                                    </div>

                                     {{-- Per Page --}}
                                     <div class="col-md-2">
                                        <select name="per_page" class="form-select form-select-sm" onchange="window.location.href='{{ route('student-affairs.extracurriculars.achievements', $extracurricular->id) }}?academic_year_id={{ $academicYearId }}&filter_unit_id={{ request('filter_unit_id') }}&filter_class_id={{ request('filter_class_id') }}&search={{ request('search') }}&per_page=' + this.value">
                                            <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 Data</option>
                                            <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>20 Data</option>
                                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 Data</option>
                                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 Data</option>
                                        </select>
                                    </div>
                                </div>
                                @if(request()->anyFilled(['filter_unit_id', 'filter_class_id', 'search']))
                                <div class="mt-2">
                                     <a href="{{ route('student-affairs.extracurriculars.achievements', ['extracurricular' => $extracurricular->id, 'academic_year_id' => $academicYearId]) }}" class="text-decoration-none small text-danger">
                                        <i class="bi bi-x-circle"></i> Reset Filter
                                     </a>
                                </div>
                                @endif
                            </div>
                            <div class="card-body p-0">
                                @if($members->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th rowspan="2" width="50" class="text-center">No</th>
                                                <th rowspan="2">Nama Siswa</th>
                                                <th rowspan="2">Kelas</th>
                                                <th colspan="2" class="text-center bg-light border-bottom">Semester Ganjil</th>
                                                <th colspan="2" class="text-center bg-light border-bottom">Semester Genap</th>
                                            </tr>
                                            <tr>
                                                <th width="100">Nilai</th>
                                                <th>Capaian (Deskripsi)</th>
                                                <th width="100">Nilai</th>
                                                <th>Capaian (Deskripsi)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($members as $index => $member)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $member->student->nama_lengkap }}</div>
                                                    <div class="text-muted small">NIS: {{ $member->student->nis }}</div>
                                                </td>
                                                <td>{{ $member->student->schoolClass->first()->name ?? '-' }}</td>
                                                <!-- SEMESTER GANJIL -->
                                                <td>
                                                    <select name="achievements[{{ $member->id }}][grade_ganjil]" class="form-select form-select-sm" {{ !$isViewingActiveYear ? 'disabled' : '' }}>
                                                        <option value="">- Nilai -</option>
                                                        <option value="A" {{ $member->grade_ganjil == 'A' ? 'selected' : '' }}>A</option>
                                                        <option value="B" {{ $member->grade_ganjil == 'B' ? 'selected' : '' }}>B</option>
                                                        <option value="C" {{ $member->grade_ganjil == 'C' ? 'selected' : '' }}>C</option>
                                                        <option value="D" {{ $member->grade_ganjil == 'D' ? 'selected' : '' }}>D</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="achievements[{{ $member->id }}][description_ganjil]" class="form-control form-control-sm" value="{{ $member->description_ganjil }}" placeholder="Tulis capaian..." {{ !$isViewingActiveYear ? 'disabled' : '' }}>
                                                </td>
                                                <!-- SEMESTER GENAP -->
                                                <td>
                                                    <select name="achievements[{{ $member->id }}][grade_genap]" class="form-select form-select-sm" {{ !$isViewingActiveYear ? 'disabled' : '' }}>
                                                        <option value="">- Nilai -</option>
                                                        <option value="A" {{ $member->grade_genap == 'A' ? 'selected' : '' }}>A</option>
                                                        <option value="B" {{ $member->grade_genap == 'B' ? 'selected' : '' }}>B</option>
                                                        <option value="C" {{ $member->grade_genap == 'C' ? 'selected' : '' }}>C</option>
                                                        <option value="D" {{ $member->grade_genap == 'D' ? 'selected' : '' }}>D</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="achievements[{{ $member->id }}][description_genap]" class="form-control form-control-sm" value="{{ $member->description_genap }}" placeholder="Tulis capaian..." {{ !$isViewingActiveYear ? 'disabled' : '' }}>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer bg-white py-3">
                                    {{ $members->links() }}
                                </div>
                                @else
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-3"></i>
                                    Belum ada anggota di periode ini.
                                </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tab: Laporan Kegiatan -->
                <div class="tab-pane fade" id="pills-reports" role="tabpanel" aria-labelledby="pills-reports-tab">
                    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                        <span id="reports-content-marker"></span>
                        <div class="card-header bg-white border-bottom py-3">
                             <h5 class="card-title mb-0 h6 fw-bold">Arsip Laporan Kegiatan</h5>
                        </div>
                        <div class="card-body p-0">
                             @if($reports->count() > 0)
                             <div class="table-responsive">
                                 <table class="table table-hover align-middle mb-0">
                                     <thead class="table-light">
                                         <tr>
                                             <th width="50" class="text-center">No</th>
                                             <th>Judul Laporan</th>
                                             <th>Tanggal Unggah</th>
                                             <th>Keterangan</th>
                                             <th width="120" class="text-center">Aksi</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @foreach($reports as $report)
                                         <tr>
                                             <td class="text-center">{{ $loop->iteration }}</td>
                                             <td>
                                                 <div class="fw-bold text-dark">{{ $report->title }}</div>
                                             </td>
                                             <td>{{ $report->created_at->translatedFormat('d F Y') }}</td>
                                             <td>{{ $report->description ?: '-' }}</td>
                                             <td class="text-center">
                                                 <div class="btn-group">
                                                     <a href="{{ asset('storage/' . $report->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm" title="Lihat/Download">
                                                         <i class="bi bi-eye"></i>
                                                     </a>
                                                     @if($isViewingActiveYear)
                                                     <form action="{{ route('student-affairs.extracurriculars.delete-report', $report->id) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?')">
                                                         @csrf
                                                         @method('DELETE')
                                                         <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                                             <i class="bi bi-trash"></i>
                                                         </button>
                                                     </form>
                                                     @endif
                                                 </div>
                                             </td>
                                         </tr>
                                         @endforeach
                                     </tbody>
                                 </table>
                             </div>
                             @else
                             <div class="text-center py-5 text-muted">
                                 <i class="bi bi-file-earmark-x fs-1 d-block mb-3"></i>
                                 Belum ada laporan yang diunggah.
                             </div>
                             @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function applySearch() {
        const query = document.getElementById('searchInput').value;
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('search', query);
        window.location.search = urlParams.toString();
    }

    // Allow Enter key in search input
    document.getElementById('searchInput')?.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            applySearch();
        }
    });
</script>

<style>
    .nav-pills .nav-link {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        color: #6c757d;
        background: white;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-right: 10px;
    }
    .nav-pills .nav-link.active {
        background-color: var(--bs-primary);
        color: white;
        box-shadow: 0 4px 6px rgba(13, 110, 253, 0.2);
    }
    .table th {
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        color: #495057;
    }
</style>
@endsection
