@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Catatan Pelanggaran - Kelas {{ $myClass->name ?? 'Tidak Ada' }}</h1>
        <a href="{{ route('wali-kelas.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    @if(!$myClass)
        <div class="alert alert-warning">Anda tidak terdaftar sebagai Wali Kelas aktif.</div>
    @else

        <!-- Filters (Optional, matching controller logic) -->
        @if(Auth::user()->role === 'administrator' || count($availableClasses) > 1)
        <div class="card mb-4">
            <div class="card-body py-2">
                <form method="GET" action="{{ route('wali-kelas.violations') }}" class="row g-2 align-items-center">
                    @if(Auth::user()->role === 'administrator')
                    <div class="col-auto">
                        <select name="unit_id" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-auto">
                         <select name="academic_year_id" class="form-control form-control-sm" onchange="this.form.submit()">
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ (request('academic_year_id') == $year->id || $myClass->academic_year_id == $year->id) ? 'selected' : '' }}>
                                    {{ $year->name }} ({{ $year->status }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Pelanggaran Siswa</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Siswa</th>
                                <th>Jenis</th>
                                <th>Deskripsi</th>
                                <th>Poin</th>
                                <th>Tindak Lanjut</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($violations as $violation)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($violation->date)->format('d/m/Y') }}</td>
                                <td class="fw-bold">{{ $violation->student->nama_lengkap }}</td>
                                <td>
                                    @if($violation->violation_type == 'Ringan')
                                        <span class="badge bg-warning text-dark">Ringan</span>
                                    @elseif($violation->violation_type == 'Sedang')
                                        <span class="badge bg-orange text-white" style="background-color: #fd7e14;">Sedang</span>
                                    @else
                                        <span class="badge bg-danger">Berat</span>
                                    @endif
                                </td>
                                <td>{{ $violation->description }}</td>
                                <td class="text-center">{{ $violation->points }}</td>
                                <td>
                                    @if($violation->follow_up)
                                        <div class="small text-muted mb-1">Aksi:</div>
                                        {{ $violation->follow_up }}
                                    @endif
                                    @if($violation->follow_up_result)
                                        <div class="small text-muted mt-2 mb-1">Hasil:</div>
                                        <span class="text-success">{{ $violation->follow_up_result }}</span>
                                    @endif
                                    @if(!$violation->follow_up && !$violation->follow_up_result)
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($violation->follow_up_status == 'pending')
                                        <span class="badge bg-secondary">Pending</span>
                                    @elseif($violation->follow_up_status == 'process')
                                        <span class="badge bg-info text-dark">Proses</span>
                                    @elseif($violation->follow_up_status == 'done')
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada catatan pelanggaran untuk kelas ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $violations->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
