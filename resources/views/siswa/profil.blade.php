@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
        --modern-blue: #0A66C2;
    }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8fafc;
    }

    .profile-hero {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        height: 200px;
        border-radius: 2rem;
        position: relative;
        margin-bottom: 80px;
    }

    .profile-avatar-container {
        position: absolute;
        bottom: -60px;
        left: 50%;
        transform: translateX(-50%);
    }

    .profile-avatar {
        width: 150px;
        height: 150px;
        border: 6px solid #f8fafc;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        object-fit: cover;
        background: #fff;
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
        margin-right: 8px;
    }

    .nav-pills-custom .nav-link.active {
        background: #4f46e5;
        color: #fff;
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    }

    .info-label {
        color: #94a3b8;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .info-value {
        color: #1e293b;
        font-weight: 600;
        font-size: 1.05rem;
    }

    .history-card {
        border-left: 4px solid #4f46e5;
        transition: transform 0.2s;
    }

    .history-card:hover {
        transform: translateX(5px);
    }
</style>
@endpush

@section('content')
<div class="app-content pt-4">
    <div class="container-xl">
        {{-- Profile Header --}}
        <div class="profile-hero shadow-lg">
            <div class="profile-avatar-container">
                @if($user->photo)
                    <img src="{{ asset('photos/' . $user->photo) }}" class="rounded-circle profile-avatar">
                @else
                    <div class="rounded-circle profile-avatar d-flex align-items-center justify-content-center bg-white">
                        <i class="bi bi-person-fill text-primary display-2"></i>
                    </div>
                @endif
            </div>
        </div>

        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark mb-1">{{ $user->name }}</h2>
            <p class="text-muted fw-medium">{{ $student ? $student->nis : 'No NIS' }} • {{ $student && $student->unit ? $student->unit->name : 'No Unit' }}</p>
        </div>

        <div class="row">
            <div class="col-lg-3">
                <div class="glass-card p-4 mb-4">
                    <h6 class="fw-bold mb-4 border-bottom pb-2">Status Akademik</h6>
                    <div class="mb-3">
                        <div class="info-label">Kelas Aktif</div>
                        <div class="info-value">{{ $student && $student->schoolClass->first() ? $student->schoolClass->first()->name : '-' }}</div>
                        <div class="small text-muted">{{ $student && $student->schoolClass->first() && $student->schoolClass->first()->academicYear ? $student->schoolClass->first()->academicYear->name : '-' }}</div>
                    </div>
                    <div class="mb-0">
                        <div class="info-label">NISN</div>
                        <div class="info-value">{{ $student ? $student->nisn : '-' }}</div>
                    </div>
                </div>

                <div class="nav-pills-custom d-flex flex-column nav" id="v-pills-tab" role="tablist">
                    <button class="nav-link active text-start mb-2" id="v-pills-bio-tab" data-bs-toggle="pill" data-bs-target="#v-pills-bio" type="button" role="tab"><i class="bi bi-person-lines-fill me-2"></i> Biodata Pribadi</button>
                    <button class="nav-link text-start mb-2" id="v-pills-wali-tab" data-bs-toggle="pill" data-bs-target="#v-pills-wali" type="button" role="tab"><i class="bi bi-people-fill me-2"></i> Data Keluarga/Wali</button>
                    <button class="nav-link text-start mb-2" id="v-pills-history-tab" data-bs-toggle="pill" data-bs-target="#v-pills-history" type="button" role="tab"><i class="bi bi-clock-history me-2"></i> Histori Kelas</button>
                    <button class="nav-link text-start mb-2 text-danger" id="v-pills-password-tab" data-bs-toggle="pill" data-bs-target="#v-pills-password" type="button" role="tab"><i class="bi bi-key-fill me-2"></i> Ubah Password</button>
                </div>
            </div>

            <div class="col-lg-9">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 h4 mb-0"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2 h4 mb-0"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="glass-card p-4">
                    <div class="tab-content" id="v-pills-tabContent">
                        {{-- Biodata Pribadi --}}
                        <div class="tab-pane fade show active" id="v-pills-bio" role="tabpanel">
                            <h5 class="fw-bold mb-4">Biodata Lengkap</h5>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-label">Nama Lengkap</div>
                                    <div class="info-value">{{ $student ? $student->nama_lengkap : '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-label">Jenis Kelamin</div>
                                    <div class="info-value">{{ $student ? ($student->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan') : '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-label">Tempat, Tanggal Lahir</div>
                                    <div class="info-value">{{ $student ? $student->tempat_lahir . ', ' . \Carbon\Carbon::parse($student->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-label">Agama</div>
                                    <div class="info-value">{{ $student ? $student->agama : '-' }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="info-label">Alamat Lengkap</div>
                                    <div class="info-value">{{ $student ? $student->alamat : '-' }}</div>
                                    <div class="small text-muted mt-1">
                                        RT {{ $student->alamat_rt ?? '-' }} / RW {{ $student->alamat_rw ?? '-' }},
                                        Desa {{ $student->desa ?? '-' }}, Kec. {{ $student->kecamatan ?? '-' }},
                                        {{ $student->kota ?? '-' }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-label">No. Telepon / HP</div>
                                    <div class="info-value">{{ $student ? $student->no_hp : '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-label">Status Boarding</div>
                                    <div class="info-value">
                                        @if($student && $student->is_boarding)
                                            <span class="badge bg-primary rounded-pill">Boarding (Asrama)</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill">Non-Boarding</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Data Wali --}}
                        <div class="tab-pane fade" id="v-pills-wali" role="tabpanel">
                            <h5 class="fw-bold mb-4">Data Orang Tua / Wali</h5>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-label">Nama Wali</div>
                                    <div class="info-value">{{ $student ? $student->nama_wali : '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-label">No. HP Wali</div>
                                    <div class="info-value">{{ $student ? $student->no_hp_wali : '-' }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Histori Kelas --}}
                        <div class="tab-pane fade" id="v-pills-history" role="tabpanel">
                            <h5 class="fw-bold mb-4">Rekam Jejak Kelas</h5>
                            @if($student && $student->classes->count() > 0)
                                <div class="row g-3">
                                    @foreach($student->classes->sortByDesc('academicYear.name') as $historyClass)
                                        <div class="col-12">
                                            <div class="card history-card border-0 bg-light p-3 rounded-4">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="fw-bold mb-1">{{ $historyClass->name }}</h6>
                                                        <p class="mb-0 small text-muted">
                                                            Tahun Ajaran: {{ $historyClass->academicYear->name ?? '-' }} | 
                                                            Unit: {{ $historyClass->unit->name ?? '-' }}
                                                        </p>
                                                    </div>
                                                    @if($student->class_id == $historyClass->id)
                                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Kelas Saat Ini</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-journal-x display-1 text-muted opacity-25"></i>
                                    <p class="text-muted mt-3">Tidak ada data histori kelas yang ditemukan.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Ubah Password --}}
                        <div class="tab-pane fade" id="v-pills-password" role="tabpanel">
                            <h5 class="fw-bold mb-1">Ubah Kata Sandi</h5>
                            <p class="text-muted small mb-4">Pastikan Anda menggunakan kombinasi karakter yang kuat untuk keamanan akun Anda.</p>
                            
                            <form action="{{ route('siswa.profil.update-password') }}" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <label class="info-label">Kata Sandi Saat Ini</label>
                                        <input type="password" name="current_password" class="form-control rounded-3 border-light bg-light p-3" placeholder="Masukkan kata sandi lama" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="info-label">Kata Sandi Baru</label>
                                        <input type="password" name="password" class="form-control rounded-3 border-light bg-light p-3" placeholder="Minimal 8 karakter" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="info-label">Konfirmasi Kata Sandi Baru</label>
                                        <input type="password" name="password_confirmation" class="form-control rounded-3 border-light bg-light p-3" placeholder="Ulangi kata sandi baru" required>
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary px-5 py-3 rounded-4 fw-bold shadow-sm">
                                            <i class="bi bi-shield-lock me-2"></i> Perbarui Kata Sandi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
