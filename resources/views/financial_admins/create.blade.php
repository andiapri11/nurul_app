@extends('layouts.app')

@php
    $title = 'Tambah Otoritas Keuangan';
@endphp

@section('title', $title)

@section('content')
<style>
    :root {
        --finance-primary: #2563eb;
        --finance-secondary: #475569;
        --finance-accent: #f59e0b;
        --finance-success: #10b981;
    }

    .premium-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        background: #fff;
    }

    .header-gradient {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        padding: 40px 30px;
        color: white;
    }

    .form-section-title {
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--finance-secondary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-section-title::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .finance-input {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 0.625rem 1rem;
        transition: all 0.2s;
        font-size: 0.9375rem;
    }

    .finance-input:focus {
        border-color: var(--finance-primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
    }

    .role-badge-select {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .role-option {
        position: relative;
        cursor: pointer;
    }

    .role-option input {
        position: absolute;
        opacity: 0;
    }

    .role-card {
        padding: 20px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        text-align: center;
        transition: all 0.3s;
    }

    .role-option input:checked + .role-card {
        border-color: var(--finance-primary);
        background: rgba(37, 99, 235, 0.05);
        transform: translateY(-2px);
    }

    .role-icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: var(--finance-secondary);
    }

    .role-option input:checked + .role-card .role-icon {
        color: var(--finance-primary);
    }

    .assignment-row {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid #e2e8f0;
        position: relative;
    }

    .pin-input-group {
        letter-spacing: 0.5em;
        font-family: monospace;
        text-align: center;
        font-size: 1.25rem;
        font-weight: bold;
    }

    .btn-finance-primary {
        background: var(--finance-primary);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-finance-primary:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }
</style>

<div class="app-content-header py-4">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 text-white text-dark">
                <h3 class="fw-bold m-0"><i class="bi bi-person-lock me-2"></i>Tambah Akses Manajemen</h3>
            </div>
        </div>
    </div>
</div>

<div class="app-content pb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10">
                <form action="{{ route('financial-admins.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="premium-card">
                        <div class="header-gradient">
                            <h4 class="m-0 fw-bold">Registrasi Akun Keuangan Baru</h4>
                            <p class="m-0 opacity-75 small mt-1">Gunakan formulir ini untuk membuat akses otoritas ke modul keuangan.</p>
                        </div>

                        <div class="card-body p-4 p-lg-5">
                            @if(session('error'))
                                <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4">
                                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                                    <div>{{ session('error') }}</div>
                                </div>
                            @endif

                            {{-- Section 1: Identitas --}}
                            <div class="form-section-title">
                                <i class="bi bi-person-circle text-primary"></i> Identitas Personal
                            </div>
                            
                            <div class="row g-4 mb-5">
                                <div class="col-md-7">
                                    <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" class="form-control finance-input @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama (beserta gelar jika ada)" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-5">
                                    <label for="nip" class="form-label fw-semibold">NIP (Nomor Induk Pegawai)</label>
                                    <input type="text" class="form-control finance-input @error('nip') is-invalid @enderror" 
                                           id="nip" name="nip" value="{{ old('nip') }}" placeholder="Induk Pegawai (Opsional)">
                                    @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="username" class="form-label fw-semibold">Username Login</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-at"></i></span>
                                        <input type="text" class="form-control finance-input @error('username') is-invalid @enderror" 
                                               id="username" name="username" value="{{ old('username') }}" placeholder="user.finance" required>
                                    </div>
                                    @error('username') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">Alamat Email Resmi</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control finance-input @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}" placeholder="admin.keu@sekolah.id" required>
                                    </div>
                                    @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Section 2: Role & Otoritas --}}
                            <div class="form-section-title">
                                <i class="bi bi-shield-lock-fill text-accent" style="color: var(--finance-accent)"></i> Otoritas & Jabatan
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold mb-3">Pilih Role Pengguna</label>
                                <div class="role-badge-select">
                                    <label class="role-option">
                                        <input type="radio" name="role" value="admin_keuangan" {{ old('role', 'admin_keuangan') == 'admin_keuangan' ? 'checked' : '' }}>
                                        <div class="role-card">
                                            <div class="role-icon"><i class="bi bi-cash-stack"></i></div>
                                            <div class="fw-bold">Admin Keuangan</div>
                                            <small class="text-muted">Operator input & kasir harian</small>
                                        </div>
                                    </label>
                                    <label class="role-option">
                                        <input type="radio" name="role" value="kepala_keuangan" {{ old('role') == 'kepala_keuangan' ? 'checked' : '' }}>
                                        <div class="role-card">
                                            <div class="role-icon"><i class="bi bi-briefcase-fill"></i></div>
                                            <div class="fw-bold">Kepala Keuangan</div>
                                            <small class="text-muted">Manajerial & Otoritas Verifikasi</small>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="form-label fw-semibold mb-2">Penugasan Jabatan & Unit</label>
                                <div id="assignments-container">
                                    <div class="assignment-row">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="small fw-bold text-muted mb-1">JABATAN</label>
                                                <select class="form-select finance-input" name="jabatan_ids[]" required>
                                                    <option value="">-- Pilih Jabatan --</option>
                                                    @foreach($jabatans as $jabatan)
                                                        <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="small fw-bold text-muted mb-1">UNIT TUGAS</label>
                                                <select class="form-select finance-input" name="unit_ids[]">
                                                    <option value="">-- Semua Unit (Yayasan) --</option>
                                                    @foreach($units as $unit)
                                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end justify-content-center">
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-row" style="display:none; height: 42px; width: 42px; border-radius: 8px;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm mt-1 border-0 fw-bold" id="btn-add-row">
                                    <i class="bi bi-plus-circle-fill me-1"></i> Tambah Penugasan Lain
                                </button>
                            </div>

                            {{-- Section 3: Keamanan --}}
                            <div class="form-section-title">
                                <i class="bi bi-key-fill text-success" style="color: var(--finance-success)"></i> Keamanan & Akses
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-semibold">Kata Sandi Login</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control finance-input @error('password') is-invalid @enderror" 
                                               id="password" name="password" required>
                                    </div>
                                    @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Sandi</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-shield-check"></i></span>
                                        <input type="password" class="form-control finance-input" 
                                               id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="bg-light p-4 rounded-4 border">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="fw-bold mb-1"><i class="bi bi-123 me-2"></i>Security PIN (6 Digit)</h6>
                                                <p class="text-muted small m-0">PIN ini akan digunakan untuk konfirmasi transaksi penting dan verifikasi laporan keuangan.</p>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control finance-input pin-input-group @error('security_pin') is-invalid @enderror" 
                                                       name="security_pin" maxlength="6" value="{{ old('security_pin') }}" 
                                                       placeholder="000000" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                                @error('security_pin') <div class="invalid-feedback d-block text-center">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer bg-light p-4 border-top-0 d-flex justify-content-between align-items-center">
                            <a href="{{ route('financial-admins.index') }}" class="btn btn-link text-decoration-none text-muted fw-semibold">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                            </a>
                            <div class="d-flex gap-2">
                                <button type="reset" class="btn btn-outline-secondary px-4 fw-bold">Reset Form</button>
                                <button type="submit" class="btn-finance-primary px-5 shadow-sm">
                                    <i class="bi bi-check2-circle me-1"></i> Simpan Akses
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('assignments-container');
        const addButton = document.getElementById('btn-add-row');

        function updateRowButtons() {
            const rows = container.querySelectorAll('.assignment-row');
            rows.forEach((row) => {
                const removeBtn = row.querySelector('.btn-remove-row');
                removeBtn.style.display = rows.length === 1 ? 'none' : 'block';
            });
        }

        addButton.addEventListener('click', function() {
            const firstRow = container.querySelector('.assignment-row');
            const newRow = firstRow.cloneNode(true);
            
            newRow.querySelectorAll('select').forEach(select => {
                select.value = '';
                select.querySelectorAll('option').forEach(opt => opt.removeAttribute('selected'));
            });

            newRow.querySelector('.btn-remove-row').addEventListener('click', function() {
                newRow.remove();
                updateRowButtons();
            });

            container.appendChild(newRow);
            updateRowButtons();
        });

        // Initial setup for the first row's remove button if multiple ever existed
        container.querySelectorAll('.btn-remove-row').forEach(btn => {
            btn.addEventListener('click', function() {
                if (container.querySelectorAll('.assignment-row').length > 1) {
                    btn.closest('.assignment-row').remove();
                    updateRowButtons();
                }
            });
        });
    });
</script>
@endpush
