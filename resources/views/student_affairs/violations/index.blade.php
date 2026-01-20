@extends('layouts.app')

@section('title', 'Daftar Pelanggaran Siswa')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header Title Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Manajemen Pelanggaran</h1>
            <p class="text-muted small mb-0">Pantau dan kelola kedisiplinan siswa dalam satu panel.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('student-affairs.violations.export-pdf', request()->query()) }}" target="_blank" class="btn btn-white shadow-sm border-0 px-3 py-2">
                <i class="bi bi-file-earmark-pdf text-danger me-2"></i><strong>Cetak Laporan</strong>
            </a>
            <a href="{{ route('student-affairs.violations.export-excel', request()->query()) }}" target="_blank" class="btn btn-white shadow-sm border-0 px-3 py-2">
                <i class="bi bi-file-earmark-excel text-success me-2"></i><strong>Export Excel</strong>
            </a>
            @if($isViewingActiveYear)
            <a href="{{ route('student-affairs.violations.create') }}" class="btn btn-primary shadow-sm px-3 py-2">
                <i class="bi bi-plus-circle me-1"></i> <strong>Catat Pelanggaran</strong>
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Dashboard Section -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="stats-card-link">
                <div class="stats-card border-0 shadow-sm h-100 bg-gradient-brand">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center position-relative z-1">
                            <div>
                                <div class="stats-label">Total Kasus</div>
                                <div class="stats-value">{{ $stats['total'] ?? 0 }}</div>
                                <div class="stats-footer mt-2"><i class="bi bi-graph-up me-1"></i> Semua Pelanggaran</div>
                            </div>
                            <div class="stats-icon-bg">
                                <i class="bi bi-clipboard2-pulse"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="stats-card-link">
                <div class="stats-card border-0 shadow-sm h-100 bg-gradient-pending">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center position-relative z-1">
                            <div>
                                <div class="stats-label">Menunggu</div>
                                <div class="stats-value">{{ $stats['pending'] ?? 0 }}</div>
                                <div class="stats-footer mt-2"><i class="bi bi-clock-history me-1"></i> Perlu Review</div>
                            </div>
                            <div class="stats-icon-bg">
                                <i class="bi bi-exclamation-square"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{ request()->fullUrlWithQuery(['status' => 'process']) }}" class="stats-card-link">
                <div class="stats-card border-0 shadow-sm h-100 bg-gradient-process">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center position-relative z-1">
                            <div>
                                <div class="stats-label">Sedang Diproses</div>
                                <div class="stats-value">{{ $stats['process'] ?? 0 }}</div>
                                <div class="stats-footer mt-2"><i class="bi bi-arrow-repeat me-1"></i> Tindak Lanjut</div>
                            </div>
                            <div class="stats-icon-bg">
                                <i class="bi bi-lightning-charge"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{ request()->fullUrlWithQuery(['status' => 'done']) }}" class="stats-card-link">
                <div class="stats-card border-0 shadow-sm h-100 bg-gradient-success">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center position-relative z-1">
                            <div>
                                <div class="stats-label">Selesai</div>
                                <div class="stats-value">{{ $stats['done'] ?? 0 }}</div>
                                <div class="stats-footer mt-2"><i class="bi bi-check2-circle me-1"></i> Masalah Tuntas</div>
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
                <form action="{{ route('student-affairs.violations.index') }}" method="GET" id="violationFilter">
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
                            <label class="form-label small fw-bold text-muted">Jenis Pelanggaran</label>
                            <select name="type" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                                <option value="">Semua Jenis</option>
                                <option value="Ringan" {{ request('type') == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                                <option value="Sedang" {{ request('type') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="Berat" {{ request('type') == 'Berat' ? 'selected' : '' }}>Berat</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Cari Siswa</label>
                            <div class="input-group shadow-sm rounded">
                                <input type="text" name="search" class="form-control border-0" placeholder="Ketik nama atau NIS..." value="{{ request('search') }}">
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
                            <th>Jenis</th>
                            <th>Detail Pelanggaran</th>
                            <th>Tindak Lanjut & Bukti</th>
                            <th class="text-center">Poin</th>
                            <th class="text-center">Pencatat</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($violations as $violation)
                        <tr>
                            <td class="text-center fw-semibold text-muted">{{ $loop->iteration + $violations->firstItem() - 1 }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ $violation->date->translatedFormat('d M Y') }}</span>
                                    <small class="text-muted">{{ $violation->date->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle-sm bg-light-primary text-primary me-3">
                                        {{ substr($violation->student->nama_lengkap ?? 'S', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $violation->student->nama_lengkap ?? '-' }}</div>
                                        @php
                                            $currClass = $violation->student->classInYear($academicYearId)->first();
                                        @endphp
                                        <div class="small text-muted">
                                            {{ $violation->student->nis ?? '-' }} | 
                                            <span class="text-primary fw-bold">{{ $currClass->name ?? ($violation->student->unit->name ?? '-') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $badgeType = 'bg-soft-warning';
                                    if($violation->violation_type == 'Sedang') $badgeType = 'bg-soft-primary';
                                    if($violation->violation_type == 'Berat') $badgeType = 'bg-soft-danger';
                                @endphp
                                <span class="badge {{ $badgeType }}">{{ $violation->violation_type }}</span>
                            </td>
                            <td>
                                <div class="violation-description">
                                    <strong>{{ $violation->description }}</strong>
                                </div>
                            </td>
                            <td>
                                <div class="follow-up-detail">
                                    @if($violation->follow_up)
                                        <div class="mb-1">
                                            <span class="badge bg-light text-info border small fw-normal">Rencana:</span>
                                            <span class="small d-block">{{ $violation->follow_up }}</span>
                                        </div>
                                    @endif
                                    @if($violation->follow_up_result)
                                        <div class="mb-1">
                                            <span class="badge bg-light text-success border small fw-normal">Hasil:</span>
                                            <span class="small d-block text-muted">{{ Str::limit($violation->follow_up_result, 100) }}</span>
                                        </div>
                                    @endif
                                    @if(!$violation->follow_up && !$violation->follow_up_result && !$violation->proof)
                                        <span class="text-muted small italic">-</span>
                                    @endif

                                    @if($violation->proof)
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-xs py-0 px-2 rounded-pill" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#proofModal" 
                                                    data-bs-img="{{ asset('storage/' . $violation->proof) }}">
                                                <i class="bi bi-image me-1"></i> Bukti
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-danger">{{ $violation->points }}</span>
                            </td>
                            <td class="text-center">
                                <span class="small text-muted">{{ $violation->recorder->name ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $statusBadge = 'bg-secondary';
                                    $statusText = 'Pending';
                                    if($violation->follow_up_status == 'process') { $statusBadge = 'bg-info text-white'; $statusText = 'Proses'; }
                                    if($violation->follow_up_status == 'done') { $statusBadge = 'bg-success'; $statusText = 'Selesai'; }
                                @endphp
                                <span class="badge rounded-pill {{ $statusBadge }} px-3">{{ $statusText }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                    @if($violation->academicYear && $violation->academicYear->status === 'active')
                                    <button type="button" class="btn btn-white btn-sm px-3" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#followUpModal" 
                                            data-bs-id="{{ $violation->id }}"
                                            data-bs-action="{{ $violation->follow_up }}"
                                            data-bs-result="{{ $violation->follow_up_result }}"
                                            data-bs-status="{{ $violation->follow_up_status }}"
                                            data-bs-attachment="{{ $violation->follow_up_attachment ? asset('storage/' . $violation->follow_up_attachment) : '' }}"
                                            title="Update Status">
                                        <i class="bi bi-gear text-primary"></i>
                                    </button>
                                    <a href="{{ route('student-affairs.violations.edit', $violation->id) }}" class="btn btn-white btn-sm px-3" title="Edit">
                                        <i class="bi bi-pencil-square text-warning"></i>
                                    </a>
                                    <form action="{{ route('student-affairs.violations.destroy', $violation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
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
                            <td colspan="10" class="text-center py-5">
                                <div class="empty-state text-muted">
                                    <i class="bi bi-emoji-smile fs-1 mb-3 d-block"></i>
                                    <p>Tidak ada data pelanggaran ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 px-2">
                <div class="text-muted small">
                    Memuat {{ $violations->count() }} dari total {{ $violations->total() }} data
                </div>
                <div>
                    {{ $violations->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- All your existing Modals remain here -->
<!-- No logic changes, only visual wrapping if needed -->

<!-- Follow Up Modal -->
<div class="modal fade" id="followUpModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-primary text-white py-4" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
                <h5 class="modal-title fw-bold"><i class="bi bi-arrow-right-circle me-2"></i> Update Progres Penanganan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('student-affairs.violations.update-follow-up') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="modal-body p-4">
                    <input type="hidden" name="violation_id" id="followUpId">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Rencana Tindak Lanjut</label>
                        <input type="text" name="follow_up" id="followUpAction" class="form-control" placeholder="Contoh: Pemanggilan Orang Tua">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Realisasi / Hasil</label>
                        <textarea name="follow_up_result" id="followUpResult" class="form-control" rows="4" placeholder="Detail hasil pertemuan..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Status Terkini</label>
                        <select name="follow_up_status" id="followUpStatus" class="form-select border-primary-soft">
                            <option value="pending">Menunggu (Pending)</option>
                            <option value="process">Sedang Diproses</option>
                            <option value="done">Selesai / Tuntas</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small text-uppercase">Lampiran Pendukung</label>
                        <input type="file" name="follow_up_attachment" class="form-control" accept="image/*,application/pdf">
                        <div id="existingAttachment" class="mt-2 d-none">
                            <a href="#" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill"><i class="bi bi-paperclip me-1"></i> Lihat Arsip Current</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3 border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Proof Modal -->
<div class="modal fade" id="proofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Bukti Pelanggaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0 overflow-hidden" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <img src="" id="proofImage" class="img-fluid" alt="Bukti Pelanggaran">
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Premium Gradients */
    .bg-gradient-brand { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: white; }
    .bg-gradient-pending { background: linear-gradient(135deg, #858796 0%, #60616f 100%); color: white; }
    .bg-gradient-process { background: linear-gradient(135deg, #36b9cc 0%, #258391 100%); color: white; }
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
    .bg-light-primary { background-color: #e8f0fe; }

    /* Badges */
    .bg-soft-warning { background-color: #fff4e5; color: #ff9800; border: 1px solid #ffe8cc; }
    .bg-soft-primary { background-color: #eef2ff; color: #4e73df; border: 1px solid #e0e7ff; }
    .bg-soft-danger { background-color: #fff2f2; color: #e74a3b; border: 1px solid #ffebeb; }

    .btn-white { background: white; color: #333; }
    .btn-white:hover { background: #f8f9fa; }
    
    .border-primary-soft { border: 1px solid #dae1f3; }
    
    .nav-pills .nav-link.active {
        box-shadow: 0 4px 6px rgba(78, 115, 223, 0.25);
    }
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
            modalImg.src = imgSrc;
        });

        // Follow Up Modal Logic
        var followUpModal = document.getElementById('followUpModal');
        followUpModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-bs-id');
            var action = button.getAttribute('data-bs-action');
            var result = button.getAttribute('data-bs-result');
            var status = button.getAttribute('data-bs-status');
            var attachment = button.getAttribute('data-bs-attachment');

            followUpModal.querySelector('#followUpId').value = id;
            followUpModal.querySelector('#followUpAction').value = action;
            followUpModal.querySelector('#followUpResult').value = result;
            followUpModal.querySelector('#followUpStatus').value = status || 'pending';

            var attachmentDiv = followUpModal.querySelector('#existingAttachment');
            var attachmentLink = attachmentDiv.querySelector('a');
            if (attachment) {
                attachmentLink.href = attachment;
                attachmentDiv.classList.remove('d-none');
            } else {
                attachmentDiv.classList.add('d-none');
            }
        });
    });
</script>
@endpush

@endsection
