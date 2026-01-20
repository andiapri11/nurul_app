@extends('layouts.app')

@section('title', 'Administrasi Guru')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Administrasi & Dokumen</h1>
            <p class="text-muted mb-0">Kelola dan upload dokumen administrasi pembelajaran Anda.</p>
        </div>
        <div>
           <div class="d-inline-flex bg-white rounded-pill px-3 py-2 shadow-sm border">
                <i class="bi bi-calendar-check text-primary me-2"></i>
                <span class="fw-medium">{{ $requests->count() }} Dokumen Aktif</span>
           </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 rounded-3 d-flex align-items-center">
            <i class="bi bi-check-circle-fill fs-4 me-3"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="row g-4">
        @forelse($requests as $req)
            @php
                $submission = $req->submissions->where('user_id', Auth::id())->first();
                $status = $submission ? $submission->status : 'missing';
                
                // Deadline Logic
                $daysLeft = null;
                $deadlineClass = 'text-muted';
                $deadlineIcon = 'bi-calendar';
                $deadlineText = '-';

                if($req->due_date) {
                    $daysLeft = now()->startOfDay()->diffInDays($req->due_date, false);
                    $formattedDate = $req->due_date->format('d M Y');
                    
                    if($daysLeft > 0) {
                        $deadlineText = "Sisa $daysLeft hari ($formattedDate)";
                        $deadlineClass = 'text-primary';
                        $deadlineIcon = 'bi-hourglass-split';
                    } elseif($daysLeft == 0) {
                        $deadlineText = "Hari ini terakhir! ($formattedDate)";
                        $deadlineClass = 'text-warning';
                        $deadlineIcon = 'bi-exclamation-circle';
                    } else {
                        $deadlineText = "Terlambat " . abs($daysLeft) . " hari ($formattedDate)";
                        $deadlineClass = 'text-danger';
                        $deadlineIcon = 'bi-exclamation-triangle';
                    }
                }

                // Status Badge Logic
                $badgeClass = 'bg-secondary';
                $badgeText = 'Belum Dikumpul';
                
                if($status == 'pending') { 
                    $badgeClass = 'bg-warning text-dark'; $badgeText = 'Menunggu Validasi'; 
                } elseif($status == 'validated') { 
                    $badgeClass = 'bg-info text-dark'; $badgeText = 'Tervalidasi (Proses Approval)'; 
                } elseif($status == 'approved') { 
                    $badgeClass = 'bg-success'; $badgeText = 'Diterima'; 
                } elseif($status == 'rejected') { 
                    $badgeClass = 'bg-danger'; $badgeText = 'Revisi Diperlukan'; 
                }
            @endphp

            <div class="col-12 col-md-6 col-lg-4 d-flex">
                <div class="card border-0 shadow-sm w-100 rounded-4 overflow-hidden position-relative h-100 doc-card">
                    <!-- Status Strip -->
                    <div class="position-absolute top-0 start-0 w-100 py-1 {{ $status == 'approved' ? 'bg-success' : ($status == 'rejected' ? 'bg-danger' : 'bg-light') }}" style="height: 6px;"></div>

                    <div class="card-body d-flex flex-column p-4 pt-4">
                        <div class="d-flex justify-content-between align-items-start mb-3 mt-2">
                            <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2 fw-normal" style="font-size: 0.75rem;">{{ $badgeText }}</span>
                            @if($req->academicYear)
                                <span class="badge bg-light text-secondary border rounded-pill">{{ $req->academicYear->name }}</span>
                            @endif
                        </div>

                        <h5 class="card-title fw-bold text-dark mb-2">{{ $req->title }}</h5>
                        
                        {{-- Task Giver & Unit Info --}}
                        <div class="mb-3" style="font-size: 0.8rem;">
                            <div class="d-flex align-items-center text-secondary mb-1">
                                <i class="bi bi-person-circle me-2 text-primary opacity-50"></i>
                                <span class="fw-medium text-dark">{{ $req->creator->name ?? 'Administrator' }}</span>
                            </div>
                            <div class="d-flex align-items-start text-secondary">
                                <i class="bi bi-buildings-fill me-2 text-primary opacity-50 mt-1"></i>
                                <span class="text-truncate-2">
                                    @if(empty($req->target_units))
                                        Semua Unit
                                    @else
                                        {{ \App\Models\Unit::whereIn('id', $req->target_units)->pluck('name')->implode(', ') }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <p class="text-muted small flex-grow-1" style="line-height: 1.6; white-space: pre-line;">{{ $req->description ?: 'Tidak ada deskripsi tambahan.' }}</p>

                        <div class="border-top pt-3 mt-2">
                            <div class="d-flex align-items-center mb-3 text-sm">
                                <div class="icon-square bg-light text-dark rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi {{ $deadlineIcon }} {{ $status == 'missing' ? $deadlineClass : 'text-muted' }}"></i>
                                </div>
                                <div style="line-height:1.2;">
                                    <span class="d-block text-muted small text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Batas Waktu</span>
                                    <span class="{{ $status == 'missing' ? $deadlineClass : 'text-dark' }} fw-medium small">{{ $deadlineText }}</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            @if($status == 'approved' || $status == 'validated')
                                <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-outline-success w-100 rounded-pill py-2">
                                    <i class="bi bi-file-earmark-check me-2"></i> Lihat Dokumen
                                </a>
                            @else
                                @php
                                    $isClosed = $req->due_date && $req->due_date < now()->startOfDay();
                                @endphp

                                @if($isClosed)
                                    <button class="btn btn-light text-muted w-100 rounded-pill py-2" disabled>
                                        <i class="bi bi-lock-fill me-2"></i> Ditutup
                                    </button>
                                @else
                                    <form action="{{ route('teacher-docs.upload', $req->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="upload-btn-wrapper w-100">
                                            @if($submission && $submission->feedback)
                                                <div class="alert alert-danger p-2 small mb-2 rounded bg-danger bg-opacity-10 border-danger border-opacity-25">
                                                    <strong>Note:</strong> {{ $submission->feedback }}
                                                </div>
                                            @endif
                                            
                                            <label class="btn btn-primary w-100 rounded-pill py-2 shadow-sm d-flex justify-content-center align-items-center cursor-pointer hover-lift relative-input-wrapper">
                                                <i class="bi bi-cloud-arrow-up me-2"></i> {{ $submission ? 'Upload Ulang (PDF)' : 'Upload PDF' }}
                                                <input type="file" name="file" class="d-none" required accept=".pdf" onchange="this.form.submit(); showLoading();">
                                            </label>
                                            <div class="text-center mt-1">
                                                <small class="text-muted" style="font-size: 0.7rem;">Maks 5MB .pdf</small>
                                            </div>
                                        </div>
                                    </form>
                                    @if($submission)
                                        <div class="text-center mt-2">
                                            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="text-decoration-none small text-muted">
                                                <i class="bi bi-paperclip"></i> Lihat yang dikirim
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-folder-check fs-1 text-muted"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Tidak ada tugas aktif</h5>
                    <p class="text-muted">Semua administrasi Anda sudah aman.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .doc-card {
        transition: all 0.2s ease;
    }
    .doc-card:hover {
        transform: translateY(-2px);
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
    function showLoading() {
        const loader = document.getElementById('loading-overlay');
        if(loader) loader.style.display = 'flex';
    }
</script>
@endpush
@endsection
