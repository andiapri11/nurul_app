@extends('layouts.app')

@section('title', 'Daftar Prestasi Siswa')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header Title Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Prestasi & Penghargaan</h1>
            <p class="text-muted small mb-0">Rayakan dan dokumentasikan setiap pencapaian luar biasa siswa.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('student-affairs.achievements.export-pdf', request()->query()) }}" target="_blank" class="btn btn-white shadow-sm border-0 px-3 py-2">
                <i class="bi bi-file-earmark-pdf text-danger me-2"></i><strong>Cetak Laporan</strong>
            </a>
            @if($isViewingActiveYear)
            <a href="{{ route('student-affairs.achievements.create') }}" class="btn btn-success shadow-sm px-3 py-2">
                <i class="bi bi-plus-circle me-1"></i> <strong>Catat Prestasi</strong>
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Dashboard Section -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('student-affairs.achievements.index', array_merge(request()->query(), ['level' => ''])) }}" class="stats-card-link">
                <div class="stats-card border-0 shadow-sm h-100 bg-gradient-brand">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center position-relative z-1">
                            <div>
                                <div class="stats-label">Total Prestasi</div>
                                <div class="stats-value">{{ $stats['total'] ?? 0 }}</div>
                                <div class="stats-footer mt-2"><i class="bi bi-award me-1"></i> Semua Tingkatan</div>
                            </div>
                            <div class="stats-icon-bg">
                                <i class="bi bi-trophy"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('student-affairs.achievements.index', array_merge(request()->query(), ['level' => 'Nasional'])) }}" class="stats-card-link">
                <div class="stats-card border-0 shadow-sm h-100 bg-gradient-danger">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center position-relative z-1">
                            <div>
                                <div class="stats-label">Nasional & Intl</div>
                                <div class="stats-value">{{ $stats['nasional'] ?? 0 }}</div>
                                <div class="stats-footer mt-2"><i class="bi bi-globe2 me-1"></i> Kebanggaan Sekolah</div>
                            </div>
                            <div class="stats-icon-bg">
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('student-affairs.achievements.index', array_merge(request()->query(), ['level' => 'Provinsi'])) }}" class="stats-card-link">
                <div class="stats-card border-0 shadow-sm h-100 bg-gradient-warning">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center position-relative z-1">
                            <div>
                                <div class="stats-label">Tingkat Provinsi</div>
                                <div class="stats-value">{{ $stats['provinsi'] ?? 0 }}</div>
                                <div class="stats-footer mt-2"><i class="bi bi-map me-1"></i> Prestasi Wilayah</div>
                            </div>
                            <div class="stats-icon-bg">
                                <i class="bi bi-flag-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('student-affairs.achievements.index', array_merge(request()->query(), ['level' => 'Kabupaten/Kota'])) }}" class="stats-card-link">
                <div class="stats-card border-0 shadow-sm h-100 bg-gradient-success">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center position-relative z-1">
                            <div>
                                <div class="stats-label">Kabupaten/Kota</div>
                                <div class="stats-value">{{ $stats['kabupaten'] ?? 0 }}</div>
                                <div class="stats-footer mt-2"><i class="bi bi-geo-alt me-1"></i> Prestasi Lokal</div>
                            </div>
                            <div class="stats-icon-bg">
                                <i class="bi bi-patch-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="card shadow-sm border-0 bg-white" style="border-radius: 15px;">
        <div class="card-body p-4">
            <!-- Filter Section Card -->
            <div class="filter-panel mb-4 p-3 bg-light" style="border-radius: 12px;">
                <form action="{{ route('student-affairs.achievements.index') }}" method="GET" id="achievementFilter">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted">Filter Unit</label>
                            <select name="unit_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                                <option value="">Semua Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted">Tahun Pelajaran</label>
                            <select name="academic_year_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                                <option value="">Tahun Pelajaran</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ $academicYearId == $ay->id ? 'selected' : '' }}>
                                        {{ $ay->name }} {{ ucfirst($ay->semester) }} {{ $ay->status == 'active' ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted">Filter Kelas</label>
                            <select name="class_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                                <option value="">Semua Kelas</option>
                                @foreach($classes as $cls)
                                    <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-muted">Tingkat Prestasi</label>
                            <select name="level" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                                <option value="">Semua Tingkat</option>
                                <option value="Sekolah" {{ request('level') == 'Sekolah' ? 'selected' : '' }}>Sekolah</option>
                                <option value="Kecamatan" {{ request('level') == 'Kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                                <option value="Kabupaten/Kota" {{ request('level') == 'Kabupaten/Kota' ? 'selected' : '' }}>Kabupaten/Kota</option>
                                <option value="Provinsi" {{ request('level') == 'Provinsi' ? 'selected' : '' }}>Provinsi</option>
                                <option value="Nasional" {{ request('level') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                                <option value="Internasional" {{ request('level') == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Cari Nama/Lomba</label>
                            <div class="input-group shadow-sm rounded">
                                <input type="text" name="search" class="form-control border-0" placeholder="Cari Siswa atau Kejuaraan..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Section -->
            <div class="table-responsive mt-2">
                <table class="table table-hover align-middle custom-table">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Tanggal</th>
                            <th>Siswa & Kelas</th>
                            <th>Nama Prestasi / Kegiatan</th>
                            <th class="text-center">Tingkat</th>
                            <th class="text-center">Juara/Peringkat</th>
                            <th class="text-center">Bukti</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($achievements as $ach)
                        <tr>
                            <td class="text-center fw-semibold text-muted">{{ $loop->iteration + $achievements->firstItem() - 1 }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ $ach->date->translatedFormat('d M Y') }}</span>
                                    <small class="text-muted">{{ $ach->date->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle-sm bg-light-success text-success me-3">
                                        {{ substr($ach->student->nama_lengkap ?? 'S', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $ach->student->nama_lengkap ?? '-' }}</div>
                                        @php
                                            $currClass = $ach->student->classInYear($academicYearId)->first();
                                        @endphp
                                        <div class="small text-muted">
                                            {{ $ach->student->nis ?? '-' }} | 
                                            <span class="text-success fw-bold">{{ $currClass->name ?? ($ach->student->unit->name ?? '-') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark achievement-name">{{ $ach->achievement_name }}</div>
                                <div class="small text-muted mt-1">{{ Str::limit($ach->description, 50) }}</div>
                            </td>
                            <td class="text-center">
                                @php
                                    $levelClass = 'bg-soft-info';
                                    if(in_array($ach->level, ['Nasional', 'Internasional'])) $levelClass = 'bg-soft-danger';
                                    elseif($ach->level == 'Provinsi') $levelClass = 'bg-soft-warning';
                                    elseif($ach->level == 'Kabupaten/Kota') $levelClass = 'bg-soft-success';
                                @endphp
                                <span class="badge {{ $levelClass }}">{{ $ach->level }}</span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-success"><i class="bi bi-trophy-fill me-1"></i> {{ $ach->rank ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                @if($ach->proof)
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#proofModal" 
                                            data-bs-img="{{ asset('storage/' . $ach->proof) }}" title="Lihat Bukti">
                                        <i class="bi bi-eye me-1"></i> Bukti
                                    </button>
                                @else
                                    <span class="text-muted small">- Tidak Ada -</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                    @if($ach->academicYear && $ach->academicYear->status === 'active')
                                    <a href="{{ route('student-affairs.achievements.edit', $ach->id) }}" class="btn btn-white btn-sm px-3" title="Edit">
                                        <i class="bi bi-pencil-square text-warning"></i>
                                    </a>
                                    <form action="{{ route('student-affairs.achievements.destroy', $ach->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-white btn-sm px-3" title="Hapus"><i class="bi bi-trash text-danger"></i></button>
                                    </form>
                                    @else
                                    <span class="px-3 py-1 bg-light text-muted small"><i class="bi bi-archive"></i></span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state text-muted">
                                    <i class="bi bi-trophy fs-1 mb-3 d-block text-light"></i>
                                    <p>Belum ada data prestasi yang tercatat.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 px-2">
                <div class="text-muted small">
                    Memuat {{ $achievements->count() }} dari total {{ $achievements->total() }} data
                </div>
                <div>
                    {{ $achievements->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Proof Modal -->
<div class="modal fade" id="proofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold px-3 pt-3">Dokumentasi Prestasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div id="proofContainer" class="bg-dark rounded overflow-hidden shadow-sm">
                    <img src="" id="proofImage" class="img-fluid d-none" alt="Bukti Prestasi">
                    <embed src="" id="proofPDF" type="application/pdf" width="100%" height="600px" class="d-none">
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Premium Gradients */
    .bg-gradient-brand { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: white; }
    .bg-gradient-danger { background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%); color: white; }
    .bg-gradient-warning { background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%); color: white; }
    .bg-gradient-success { background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); color: white; }

    /* Card Styling */
    .stats-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .stats-card-link { text-decoration: none !important; }
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
        width: 35px; height: 35px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 0.9rem;
    }
    .bg-light-success { background-color: #e6fffa; }
    .bg-light-primary { background-color: #e8f0fe; }

    /* Badges */
    .bg-soft-warning { background-color: #fff4e5; color: #ff9800; border: 1px solid #ffe8cc; }
    .bg-soft-primary { background-color: #eef2ff; color: #4e73df; border: 1px solid #e0e7ff; }
    .bg-soft-danger { background-color: #fff2f2; color: #e74a3b; border: 1px solid #ffebeb; }
    .bg-soft-success { background-color: #e6fffa; color: #38a169; border: 1px solid #c6f6d5; }
    .bg-soft-info { background-color: #ebf8ff; color: #3182ce; border: 1px solid #bee3f8; }

    .btn-white { background: white; color: #333; }
    .btn-white:hover { background: #f8f9fa; }
    
    .achievement-name { font-size: 0.95rem; line-height: 1.4; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Proof Modal Logic
        var proofModal = document.getElementById('proofModal');
        proofModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var imgSrc = button.getAttribute('data-bs-img');
            var modalImg = proofModal.querySelector('#proofImage');
            var modalPDF = proofModal.querySelector('#proofPDF');
            
            // Reset visibility
            modalImg.classList.add('d-none');
            modalPDF.classList.add('d-none');

            if (imgSrc.toLowerCase().endsWith('.pdf')) {
                modalPDF.src = imgSrc;
                modalPDF.classList.remove('d-none');
            } else {
                modalImg.src = imgSrc;
                modalImg.classList.remove('d-none');
            }
        });

        // Clear PDF src when modal is hidden to stop audio/video if any in some browsers
        proofModal.addEventListener('hidden.bs.modal', function () {
            proofModal.querySelector('#proofPDF').src = "";
        });
    });
</script>
@endpush

@endsection
