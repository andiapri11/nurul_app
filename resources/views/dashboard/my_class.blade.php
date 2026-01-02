@extends('layouts.app')

@section('title', 'Data Kelas Saya')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-1">
                <i class="bi bi-people-fill me-2"></i>Kelas {{ $kelas->name }}
            </h3>
            <p class="text-muted mb-0">Unit: {{ $kelas->unit->name }} | Wali Kelas: {{ $user->name }}</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="bg-primary text-white">
                        <tr class="align-middle">
                            <th width="5%" class="text-center">No</th>
                            <th width="10%" class="text-center">Foto</th>
                            <th width="15%">NIS / NISN</th>
                            <th width="40%">Nama Lengkap</th>
                            <th width="15%">Jenis Kelamin</th>
                            <th width="15%" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                <td class="text-center">
                                    @if($student->photo)
                                        <img src="{{ asset('storage/students/' . $student->photo) }}" 
                                             alt="Foto" 
                                             class="rounded-circle border"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary text-white d-inline-flex justify-content-center align-items-center" 
                                             style="width: 40px; height: 40px; font-size: 14px;">
                                            {{ substr($student->nama_lengkap, 0, 1) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $student->nis }}</div>
                                    <small class="text-muted">{{ $student->nisn ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="fs-6 fw-bold text-primary">{{ $student->nama_lengkap }}</span>
                                </td>
                                <td>
                                    @if($student->jenis_kelamin == 'L')
                                        <span class="badge bg-info text-dark">Laki-laki</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Perempuan</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $student->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-person-x display-4 d-block mb-3 opacity-25"></i>
                                    <h5>Belum ada siswa di kelas ini.</h5>
                                    <p class="small">Silakan hubungi admin jika data belum lengkap.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white text-muted small">
            Total Siswa: <strong>{{ $students->count() }}</strong>
        </div>
    </div>
</div>
@endsection
