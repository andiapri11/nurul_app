@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
        --modern-indigo: #4361ee;
    }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
    }

    .profile-hero {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        height: 180px;
        border-radius: 2rem;
        position: relative;
        margin-bottom: 70px;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
    }

    .profile-avatar-container {
        position: absolute;
        bottom: -50px;
        left: 50%;
        transform: translateX(-50%);
    }

    .profile-avatar-wrapper {
        position: relative;
    }

    .profile-avatar {
        width: 140px;
        height: 140px;
        border: 5px solid #fff;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        object-fit: cover;
        background: #fff;
    }

    .edit-avatar-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: var(--modern-indigo);
        color: white;
        border: 3px solid white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .edit-avatar-btn:hover {
        transform: scale(1.1);
        background: #3a0ca3;
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        border-radius: 1.5rem;
    }

    .nav-pills-custom .nav-link {
        color: #64748b;
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 12px;
        transition: all 0.3s ease;
        margin-bottom: 8px;
        border: 1px solid transparent;
        text-align: left;
        width: 100%;
    }

    .nav-pills-custom .nav-link:hover {
        background-color: rgba(67, 97, 238, 0.05);
        color: var(--modern-indigo);
    }

    .nav-pills-custom .nav-link.active {
        background: var(--modern-indigo);
        color: #fff;
        box-shadow: 0 10px 15px -3px rgba(67, 97, 238, 0.3);
    }

    .info-label {
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 6px;
    }

    .info-value {
        color: #1e293b;
        font-weight: 600;
        font-size: 1rem;
    }

    .assignment-card {
        border-left: 4px solid var(--modern-indigo);
        background: rgba(67, 97, 238, 0.03);
        transition: all 0.2s;
    }

    .assignment-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .jabatan-badge {
        background: rgba(67, 97, 238, 0.1);
        color: var(--modern-indigo);
        font-weight: 700;
        font-size: 0.75rem;
        padding: 6px 14px;
        border-radius: 8px;
        display: inline-block;
        margin-bottom: 4px;
    }

    /* Modal Styling */
    .modal-content {
        border: none;
        border-radius: 1.5rem;
    }
    .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.5rem;
    }
    .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="app-content pt-4">
    <div class="container-xl">
        {{-- Profile Header --}}
        <div class="profile-hero">
            <div class="profile-avatar-container">
                <div class="profile-avatar-wrapper">
                    <img src="{{ $user->photo ? asset('photos/' . $user->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&size=150' }}" 
                         class="rounded-circle profile-avatar" id="avatarImage">
                    <label for="photoInput" class="edit-avatar-btn shadow-sm" title="Ubah Foto Profil">
                        <i class="bi bi-camera-fill"></i>
                    </label>
                    <form id="photoForm" action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                        @csrf
                        <input type="file" name="photo" id="photoInput" accept="image/*" onchange="this.form.submit()">
                    </form>
                </div>
            </div>
        </div>

        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark mb-1">{{ $user->name }}</h2>
            <div class="d-flex align-items-center justify-content-center gap-2">
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold small">
                    <i class="bi bi-person-badge me-1"></i> {{ strtoupper($user->role) }}
                </span>
                <span class="text-muted fw-medium small">•</span>
                <span class="text-muted fw-medium small">NIP: {{ $user->nip ?? '-' }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center animate__animated animate__fadeIn">
                <i class="bi bi-check-circle-fill me-2 h4 mb-0 text-success"></i>
                <div class="fw-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center animate__animated animate__shakeX">
                <i class="bi bi-exclamation-triangle-fill me-2 h4 mb-0 text-danger"></i>
                <div class="fw-medium">{{ session('error') }}</div>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-3">
                <div class="nav-pills-custom d-flex flex-column nav" id="v-pills-tab" role="tablist">
                    <button class="nav-link active" id="v-pills-info-tab" data-bs-toggle="pill" data-bs-target="#v-pills-info" type="button" role="tab">
                        <i class="bi bi-person-lines-fill me-2"></i> Biodata Pegawai
                    </button>
                    <button class="nav-link" id="v-pills-jabatan-tab" data-bs-toggle="pill" data-bs-target="#v-pills-jabatan" type="button" role="tab">
                        <i class="bi bi-briefcase-fill me-2"></i> Jabatan & Unit
                    </button>
                    <button class="nav-link" id="v-pills-teaching-tab" data-bs-toggle="pill" data-bs-target="#v-pills-teaching" type="button" role="tab">
                        <i class="bi bi-journal-bookmark-fill me-2"></i> Tugas Mengajar
                    </button>
                    <button class="nav-link text-danger" id="v-pills-security-tab" data-bs-toggle="pill" data-bs-target="#v-pills-security" type="button" role="tab">
                        <i class="bi bi-shield-lock-fill me-2"></i> Keamanan Akun
                    </button>
                </div>

                <div class="glass-card p-4 mt-4">
                    <h6 class="fw-bold mb-3 small text-uppercase ls-1">Informasi Akun</h6>
                    <div class="mb-3">
                        <div class="info-label">Username</div>
                        <div class="info-value">{{ $user->username }}</div>
                    </div>
                    <div>
                        <div class="info-label">Email</div>
                        <div class="info-value small">{{ $user->email }}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="glass-card p-4 h-100">
                    <div class="tab-content" id="v-pills-tabContent">
                        {{-- Biodata Pegawai --}}
                        <div class="tab-pane fade show active" id="v-pills-info" role="tabpanel">
                            <h5 class="fw-bold mb-4 d-flex align-items-center">
                                <span class="bg-primary text-white p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                Biodata Lengkap
                            </h5>
                            <form action="{{ route('profile.update-info') }}" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="info-label">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control rounded-3 border-light bg-light p-3" value="{{ old('name', $user->name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="info-label">NIP / NUPTK</label>
                                            <input type="text" class="form-control rounded-3 border-light bg-light p-3" value="{{ $user->nip ?: '-' }}" disabled>
                                            <small class="text-muted">NIP hanya dapat diubah oleh Admin.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="info-label">Jenis Kelamin</label>
                                            <select name="gender" class="form-select rounded-3 border-light bg-light p-3">
                                                <option value="">- Pilih -</option>
                                                <option value="L" {{ old('gender', $user->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="P" {{ old('gender', $user->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="info-label">Tempat Lahir</label>
                                            <input type="text" name="birth_place" class="form-control rounded-3 border-light bg-light p-3" value="{{ old('birth_place', $user->birth_place) }}" placeholder="Contoh: Palembang">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="info-label">Tanggal Lahir</label>
                                            <input type="date" name="birth_date" class="form-control rounded-3 border-light bg-light p-3" value="{{ old('birth_date', $user->birth_date) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="info-label">No. Telepon / WA</label>
                                            <input type="text" name="phone" class="form-control rounded-3 border-light bg-light p-3" value="{{ old('phone', $user->phone) }}" placeholder="0812...">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="info-label">Alamat Lengkap</label>
                                            <textarea name="address" class="form-control rounded-3 border-light bg-light p-3" rows="3" placeholder="Alamat domisili saat ini">{{ old('address', $user->address) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 pt-2">
                                        <button type="submit" class="btn btn-primary px-5 py-3 rounded-4 fw-bold shadow-sm">
                                            <i class="bi bi-check-circle me-2"></i> Simpan Perubahan Info
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {{-- Jabatan & Unit --}}
                        <div class="tab-pane fade" id="v-pills-jabatan" role="tabpanel">
                            <h5 class="fw-bold mb-4 d-flex align-items-center">
                                <span class="bg-success text-white p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-briefcase-fill"></i>
                                </span>
                                Jabatan & Unit Kerja
                            </h5>
                            <div class="row g-3">
                                @forelse($user->jabatanUnits as $ju)
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light p-3 rounded-4 h-100">
                                            <div class="jabatan-badge">
                                                {{ $ju->unit->name }}
                                            </div>
                                            <h6 class="fw-bold text-dark mt-2 mb-0">{{ $ju->jabatan->nama_jabatan }}</h6>
                                            <p class="text-muted small mb-0 mt-1">Status: Aktif</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <i class="bi bi-briefcase display-4 text-muted opacity-25"></i>
                                        <p class="text-muted mt-3">Tidak ada data jabatan tercatat.</p>
                                    </div>
                                @endforelse
                                
                                @if($user->unit_id)
                                    <div class="col-12">
                                        <div class="alert bg-primary bg-opacity-10 border-0 rounded-4 mt-2">
                                            <div class="d-flex">
                                                <i class="bi bi-house-door-fill text-primary me-3 fs-4"></i>
                                                <div>
                                                    <div class="fw-bold text-primary">Unit Homebase</div>
                                                    <div class="small text-muted">Anda terdaftar berada di unit utama: <strong>{{ \App\Models\Unit::find($user->unit_id)->name ?? '-' }}</strong></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Tugas Mengajar --}}
                        <div class="tab-pane fade" id="v-pills-teaching" role="tabpanel">
                            <h5 class="fw-bold mb-4 d-flex align-items-center">
                                <span class="bg-info text-white p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-journal-bookmark-fill"></i>
                                </span>
                                Tugas Mengajar
                            </h5>
                            <div class="row g-3">
                                @forelse($user->teachingAssignments as $assign)
                                    <div class="col-md-6">
                                        <div class="card assignment-card border-0 p-3 rounded-4 h-100">
                                            <div class="d-flex align-items-start justify-content-between mb-2">
                                                <h6 class="fw-bold text-primary mb-0">{{ $assign->subject->name }}</h6>
                                                <span class="badge bg-white shadow-sm text-dark border rounded-pill">{{ $assign->schoolClass->name }}</span>
                                            </div>
                                            <div class="small text-muted">
                                                <i class="bi bi-building me-1"></i> {{ $assign->schoolClass->unit->name }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <i class="bi bi-journal-x display-4 text-muted opacity-25"></i>
                                        <p class="text-muted mt-3">Tidak ada tugas mengajar yang tercatat.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Keamanan Akun --}}
                        <div class="tab-pane fade" id="v-pills-security" role="tabpanel">
                            <h5 class="fw-bold mb-1">Keamanan Akun</h5>
                            <p class="text-muted small mb-4">
                                @if(in_array($user->role, ['administrator', 'direktur', 'admin_keuangan', 'kepala_keuangan']))
                                    Kelola kata sandi dan PIN keamanan Anda.
                                @else
                                    Kelola kata sandi akun Anda.
                                @endif
                            </p>
                            
                            {{-- Password Update --}}
                            <div class="mb-5 pb-4 border-bottom">
                                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-key me-2"></i>Ubah Kata Sandi</h6>
                                <form action="{{ route('profile.update-password') }}" method="POST">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="info-label">Kata Sandi Saat Ini</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0"><i class="bi bi-shield-lock"></i></span>
                                                    <input type="password" name="current_password" class="form-control rounded-3 border-light bg-light p-3" placeholder="Masukkan kata sandi lama" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="info-label">Kata Sandi Baru</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0"><i class="bi bi-key"></i></span>
                                                    <input type="password" name="password" class="form-control rounded-3 border-light bg-light p-3" placeholder="Minimal 8 karakter" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="info-label">Konfirmasi Kata Sandi Baru</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0"><i class="bi bi-key-fill"></i></span>
                                                    <input type="password" name="password_confirmation" class="form-control rounded-3 border-light bg-light p-3" placeholder="Ulangi kata sandi baru" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 pt-2">
                                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-4 fw-bold shadow-sm">
                                                <i class="bi bi-save2 me-2"></i> Perbarui Kata Sandi
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            @if(in_array($user->role, ['administrator', 'direktur', 'admin_keuangan', 'kepala_keuangan']))
                            {{-- Security PIN Update --}}
                            <div>
                                <h6 class="fw-bold mb-1 text-dark"><i class="bi bi-shield-lock me-2"></i>PIN Keamanan (Financial)</h6>
                                <p class="text-muted small mb-4">PIN 6-digit ini akan digunakan untuk menyetujui transaksi dan perubahan data keuangan sensitif.</p>
                                
                                <form action="{{ route('profile.update-pin') }}" method="POST">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="info-label">Verifikasi Kata Sandi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0"><i class="bi bi-lock"></i></span>
                                                    <input type="password" name="password" class="form-control rounded-3 border-light bg-light p-3" placeholder="Masukkan kata sandi akun Anda" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="info-label">PIN Baru (6 Digit)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0"><i class="bi bi-hash"></i></span>
                                                    <input type="password" name="security_pin" class="form-control rounded-3 border-light bg-light p-3" placeholder="Angka 6 digit" required maxlength="6" pattern="\d{6}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="info-label">Konfirmasi PIN Baru</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light border-0"><i class="bi bi-hash"></i></span>
                                                    <input type="password" name="security_pin_confirmation" class="form-control rounded-3 border-light bg-light p-3" placeholder="Ulangi PIN baru" required maxlength="6" pattern="\d{6}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 pt-2">
                                            <button type="submit" class="btn btn-dark px-5 py-3 rounded-4 fw-bold shadow-sm">
                                                <i class="bi bi-check-circle me-2"></i> Simpan PIN Keamanan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
