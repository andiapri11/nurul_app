@extends('layouts.app')

@section('title', 'Pengaturan Batas Waktu Absensi')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Pengaturan Batas Waktu Absensi</h1>
            <p class="text-muted small mb-0">Atur jendela waktu input absensi untuk wali kelas di setiap unit.</p>
        </div>
    </div>

    <div class="row">
        @foreach($units as $unit)
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 border-0" style="border-radius: 15px 15px 0 0;">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle-sm bg-primary text-white me-3">
                            <i class="bi bi-building"></i>
                        </div>
                        <h5 class="mb-0 fw-bold text-dark">{{ $unit->name }}</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('student-affairs.attendance-settings.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Waktu Mulai</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-clock"></i></span>
                                    <input type="time" name="attendance_start" class="form-control border-0 bg-light" 
                                           value="{{ $unit->attendance_start ? \Carbon\Carbon::parse($unit->attendance_start)->format('H:i') : '' }}">
                                </div>
                                <small class="text-muted mt-1 d-block">Waktu awal walas boleh input absen.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Waktu Berakhir</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-clock-fill"></i></span>
                                    <input type="time" name="attendance_end" class="form-control border-0 bg-light" 
                                           value="{{ $unit->attendance_end ? \Carbon\Carbon::parse($unit->attendance_end)->format('H:i') : '' }}">
                                </div>
                                <small class="text-muted mt-1 d-block">Waktu akhir walas boleh input absen.</small>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 shadow-none mb-4" style="border-radius: 10px; background-color: #f0f7ff;">
                            <div class="d-flex">
                                <i class="bi bi-info-circle-fill text-primary me-2"></i>
                                <p class="small mb-0 text-dark">
                                    Jika dikosongkan, maka <strong>tidak ada batasan waktu</strong> (walas bebas input kapan saja).
                                </p>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary shadow-sm py-2" style="border-radius: 10px;">
                                <i class="bi bi-save me-1"></i> Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    .avatar-circle-sm {
        width: 35px; height: 35px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 0.9rem;
    }
    .form-control:focus {
        box-shadow: none;
        background-color: #eef2ff !important;
    }
</style>
@endsection
