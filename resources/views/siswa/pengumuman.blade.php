@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
    }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
    }

    .hero-section {
        background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
        border-radius: 2rem;
        padding: 3rem 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        top: -20%;
        right: -5%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        border-radius: 1.5rem;
    }

    .announcement-card {
        border-left: 5px solid #3b82f6;
        transition: transform 0.3s ease;
    }

    .announcement-card:hover {
        transform: translateY(-5px);
    }
    
    .ls-1 { letter-spacing: 1px; }
</style>
@endpush

@section('content')
<div class="app-content pt-4">
    <div class="container-xl">
        {{-- Hero Section --}}
        <div class="hero-section shadow-lg">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h6 class="text-uppercase fw-bold ls-1 opacity-75 mb-2">Papan Pengumuman Kelas</h6>
                    <h1 class="display-5 fw-extrabold mb-0">Informasi Terkini untuk <br> {{ $studentClass->name ?? 'Kelas Anda' }}</h1>
                </div>
                <div class="col-md-3 text-md-end d-none d-md-block">
                    <i class="bi bi-megaphone-fill display-1 text-white opacity-25"></i>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="row g-4 justify-content-center">
            <div class="col-lg-10">
                @forelse($announcements as $announcement)
                    <div class="card glass-card announcement-card border-0 mb-4">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 me-3">
                                    <i class="bi bi-calendar-check me-2"></i> {{ $announcement->created_at->translatedFormat('d F Y') }}
                                </span>
                                <small class="text-muted fw-bold">Diposting oleh: {{ $announcement->author->name ?? 'Wali Kelas' }}</small>
                            </div>
                            <h3 class="fw-bold text-dark mb-3">{{ $announcement->title }}</h3>
                            <div class="text-muted mb-4" style="white-space: pre-line; line-height: 1.8;">{!! nl2br(e($announcement->content)) !!}</div>

                            @if($announcement->attachment)
                                <div class="mt-4 p-3 rounded-4 bg-light d-flex align-items-center justify-content-between border border-primary border-opacity-10">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                                            <i class="bi bi-file-earmark-arrow-down fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small">{{ $announcement->original_filename }}</div>
                                            <div class="text-muted smaller">Lampiran tersedia untuk diunduh</div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" 
                                                class="btn btn-outline-primary rounded-pill px-4 btn-view-doc" 
                                                data-url="{{ asset('storage/' . $announcement->attachment) }}"
                                                data-filename="{{ $announcement->original_filename }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewDocModal">
                                            <i class="bi bi-eye me-2"></i> Lihat
                                        </button>
                                        <a href="{{ asset('storage/' . $announcement->attachment) }}" download="{{ $announcement->original_filename }}" class="btn btn-primary rounded-pill px-4">
                                            <i class="bi bi-download me-2"></i> Download
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 glass-card">
                        <i class="bi bi-inbox display-1 text-primary opacity-25"></i>
                        <h4 class="fw-bold mt-4 text-dark mb-2">Belum Ada Pengumuman</h4>
                        <p class="text-muted">Semua informasi penting dari Wali Kelas akan muncul di sini.</p>
                    </div>
                @endforelse

                @if($announcements instanceof \Illuminate\Pagination\LengthAwarePaginator && $announcements->hasPages())
                    <div class="mt-4">
                        {{ $announcements->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal View Document --}}
<div class="modal fade" id="viewDocModal" tabindex="-1" aria-labelledby="viewDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="viewDocModalLabel">Preview Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="docPreviewContainer" class="rounded-4 overflow-hidden border bg-white" style="min-height: 500px;">
                    <div id="previewLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Membuka dokumen...</p>
                    </div>
                    {{-- Iframe or Image will be injected here --}}
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <span id="modalFilename" class="text-muted me-auto small fw-bold"></span>
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                <a href="" id="modalDownloadBtn" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-download me-2"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const viewDocModal = document.getElementById('viewDocModal');
        const previewContainer = document.getElementById('docPreviewContainer');
        const modalFilename = document.getElementById('modalFilename');
        const modalDownloadBtn = document.getElementById('modalDownloadBtn');
        const loading = document.getElementById('previewLoading');

        document.querySelectorAll('.btn-view-doc').forEach(button => {
            button.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                const filename = this.getAttribute('data-filename');
                const extension = filename.split('.').pop().toLowerCase();
                
                modalFilename.textContent = filename;
                modalDownloadBtn.href = url;
                modalDownloadBtn.setAttribute('download', filename);
                
                // Clear previous preview
                const existingPreview = previewContainer.querySelector('iframe, img');
                if (existingPreview) existingPreview.remove();
                
                loading.classList.remove('d-none');

                let previewElement;
                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
                    previewElement = document.createElement('img');
                    previewElement.src = url;
                    previewElement.className = 'img-fluid d-block mx-auto';
                    previewElement.style.maxHeight = '80vh';
                } else {
                    // Assume PDF or other browser-supported docs
                    previewElement = document.createElement('iframe');
                    previewElement.src = url;
                    previewElement.style.width = '100%';
                    previewElement.style.height = '70vh';
                    previewElement.style.border = 'none';
                }

                previewElement.onload = function() {
                    loading.classList.add('d-none');
                };

                previewContainer.appendChild(previewElement);
            });
        });

        // Cleanup on modal hide
        viewDocModal.addEventListener('hidden.bs.modal', function () {
            const existingPreview = previewContainer.querySelector('iframe, img');
            if (existingPreview) existingPreview.remove();
        });
    });
</script>
@endpush
@endsection
