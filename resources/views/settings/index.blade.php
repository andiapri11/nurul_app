@extends('layouts.app')

@section('title', 'Atur Aplikasi')

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        --accent-color: #4cc9f0;
    }

    .settings-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
        background: #fff;
    }

    .settings-header {
        background: var(--primary-gradient);
        padding: 2rem;
        color: #fff;
        position: relative;
    }

    .settings-header h2 {
        font-weight: 800;
        margin: 0;
        font-size: 1.5rem;
        letter-spacing: -0.5px;
    }

    .settings-header p {
        opacity: 0.8;
        margin: 0.5rem 0 0;
        font-size: 0.9rem;
    }

    .upload-zone {
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
        padding: 2rem;
        transition: all 0.3s ease;
        background: #f8fafc;
        cursor: pointer;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .upload-zone:hover {
        border-color: #4361ee;
        background: #f1f5f9;
        transform: translateY(-2px);
    }

    .preview-container {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        border: 3px solid #fff;
    }

    .preview-container img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .preview-favicon {
        width: 64px !important;
        height: 64px !important;
    }

    .btn-save {
        background: var(--primary-gradient);
        border: none;
        padding: 0.8rem 2.5rem;
        border-radius: 12px;
        font-weight: 700;
        color: #fff;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        color: #fff;
    }

    .form-label {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .form-control-premium {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control-premium:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .decoration-blob {
        position: absolute;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        top: -50px;
        right: -50px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 15px;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="settings-card">
                <div class="settings-header">
                    <div class="decoration-blob"></div>
                    <h2>Atur Identitas Aplikasi</h2>
                    <p>Sesuaikan nama, logo, dan ikon untuk personalisasi sistem Anda.</p>
                </div>

                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body p-4 p-md-5">
                        <div class="row g-4 mb-5">
                            {{-- Logo Section --}}
                            <div class="col-md-6">
                                <label class="form-label d-block text-center mb-3">Logo Utama</label>
                                <div class="upload-zone" onclick="document.getElementById('logoInput').click()">
                                    <div class="preview-container" id="logoPreview">
                                        @if(isset($appSettings['app_logo']))
                                            <img src="{{ asset('storage/' . $appSettings['app_logo']) }}" alt="Logo">
                                        @else
                                            <i class="bi bi-image text-muted fs-1"></i>
                                        @endif
                                    </div>
                                    <div class="text-center">
                                        <span class="d-block fw-bold text-primary">Klik untuk Ganti Logo</span>
                                        <small class="text-muted">Rekomendasi: PNG Transparan</small>
                                    </div>
                                    <input type="file" name="app_logo" id="logoInput" class="d-none" onchange="previewImage(this, 'logoPreview')">
                                </div>
                                @error('app_logo')
                                    <div class="text-danger small mt-2 text-center">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Favicon Section --}}
                            <div class="col-md-6">
                                <label class="form-label d-block text-center mb-3">Favicon (Ikon Tab)</label>
                                <div class="upload-zone" onclick="document.getElementById('faviconInput').click()">
                                    <div class="preview-container preview-favicon" id="faviconPreview">
                                        @if(isset($appSettings['app_favicon']))
                                            <img src="{{ asset('storage/' . $appSettings['app_favicon']) }}" alt="Favicon">
                                        @else
                                            <i class="bi bi-app-indicator text-muted fs-2"></i>
                                        @endif
                                    </div>
                                    <div class="text-center">
                                        <span class="d-block fw-bold text-primary">Klik untuk Ganti Ikon</span>
                                        <small class="text-muted">Ukuran: 1:1 (Max 512KB)</small>
                                    </div>
                                    <input type="file" name="app_favicon" id="faviconInput" class="d-none" onchange="previewImage(this, 'faviconPreview')">
                                </div>
                                @error('app_favicon')
                                    <div class="text-danger small mt-2 text-center">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="app_name" class="form-label">Nama Aplikasi (System Title)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px; border: 2px solid #e2e8f0;">
                                            <i class="bi bi-display text-primary"></i>
                                        </span>
                                        <input type="text" name="app_name" id="app_name" 
                                               class="form-control form-control-premium border-start-0 @error('app_name') is-invalid @enderror" 
                                               value="{{ $settings['app_name'] ?? ($appSettings['app_name'] ?? 'LPT Nurul Ilmi') }}" 
                                               placeholder="Contoh: Nurul Ilmi Management System"
                                               style="border-radius: 0 12px 12px 0;">
                                    </div>
                                    @error('app_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text mt-2">Nama ini muncul di judul browser dan sidebar.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label for="school_name" class="form-label">Nama Sekolah / Instansi (Hanya untuk KOP)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px; border: 2px solid #e2e8f0;">
                                            <i class="bi bi-building text-success"></i>
                                        </span>
                                        <input type="text" name="school_name" id="school_name" 
                                               class="form-control form-control-premium border-start-0 @error('school_name') is-invalid @enderror" 
                                               value="{{ $settings['school_name'] ?? ($appSettings['school_name'] ?? 'LPT Nurul Ilmi') }}" 
                                               placeholder="Contoh: LPT Nurul Ilmi"
                                               style="border-radius: 0 12px 12px 0;">
                                    </div>
                                    @error('school_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text mt-2">Nama ini khusus digunakan pada KOP surat & laporan PDF.</div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label for="app_address" class="form-label">Alamat Sekolah / Instansi</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0" style="border-radius: 12px 0 0 12px; border: 2px solid #e2e8f0;">
                                            <i class="bi bi-geo-alt text-danger"></i>
                                        </span>
                                        <textarea name="app_address" id="app_address" rows="2"
                                               class="form-control form-control-premium border-start-0 @error('app_address') is-invalid @enderror" 
                                               placeholder="Contoh: Jl. Raya Serang Km. 15, Cikupa, Tangerang, Banten"
                                               style="border-radius: 0 12px 12px 0;">{{ $settings['app_address'] ?? ($appSettings['app_address'] ?? '') }}</textarea>
                                    </div>
                                    @error('app_address')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text mt-2">Alamat ini akan muncul di kop surat laporan.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light p-4 text-center border-0">
                        <button type="submit" class="btn btn-save">
                            <i class="bi bi-check2-circle me-2"></i> Simpan Konfigurasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input, previewId) {
        const container = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                container.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
