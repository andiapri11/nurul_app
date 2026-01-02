@extends('layouts.app')

@section('title', 'Daftar Guru & Kehadiran')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 fw-bold text-dark">Data Guru Unit</h1>
                <p class="text-muted mb-0">Manajemen Guru dan Staff pada unit ini.</p>
            </div>
            <div class="col-sm-6">
                <form action="{{ route('principal.teacher-attendance') }}" method="GET" class="float-sm-end mt-2 mt-sm-0">
                    <select name="unit_id" class="form-select border-0 shadow-sm px-4 py-2" style="border-radius: 12px; min-width: 250px;" onchange="this.form.submit()">
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-4 py-3 small fw-bold">NIP & NAMA</th>
                                <th class="border-0 py-3 small fw-bold">JABATAN</th>
                                <th class="border-0 py-3 small fw-bold">EMAIL</th>
                                <th class="border-0 py-3 small fw-bold">STATISTIK MENGAJAR</th>
                                <th class="border-0 px-4 py-3 small fw-bold text-center">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                                <tr>
                                    <td class="px-4 border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-md me-3 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #eef2ff;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $teacher->name }}</div>
                                                <small class="text-muted">{{ $teacher->nip ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        @foreach($teacher->jabatanUnits->where('unit_id', $selectedUnitId) as $ju)
                                            <span class="badge bg-light text-dark fw-normal border">{{ $ju->jabatan->nama_jabatan }}</span>
                                        @endforeach
                                    </td>
                                    <td class="border-0 small text-muted">
                                        {{ $teacher->email }}
                                    </td>
                                    <td class="border-0">
                                        <div class="d-flex flex-column" style="width: 150px;">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small class="text-muted extra-small">Jadwal/Minggu</small>
                                                <small class="fw-bold text-dark extra-small">{{ $teacher->weekly_schedules }} Sesi</small>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <small class="text-muted extra-small">Hadir ({{ date('M') }})</small>
                                                <small class="fw-bold text-primary extra-small">{{ $teacher->monthly_checkins }} Kali</small>
                                            </div>
                                            @if($teacher->today_checkins > 0)
                                                <div class="mt-1">
                                                    <span class="badge bg-success py-1 px-2" style="font-size: 0.7rem;">Hadir Hari Ini</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 border-0 text-center">
                                        <span class="badge {{ $teacher->status == 'aktif' ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-1">
                                            {{ ucfirst($teacher->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Tidak ada data guru untuk unit ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
