@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i> Data Siswa Kelas {{ $schoolClass->name ?? '-' }}</h5>
                        <small class="text-white-50">Tahun Ajaran: {{ $schoolClass->academicYear->start_year ?? '-' }}/{{ $schoolClass->academicYear->end_year ?? '-' }}</small>
                    </div>
                </div>
                
                @if(auth()->user()->role === 'administrator' && isset($units))
                <div class="card-body bg-light border-bottom">
                    <form action="{{ route('wali-kelas.students.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Pilih Unit</label>
                            <select name="unit_id" class="form-select" onchange="this.form.submit()">
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Pilih Kelas</label>
                            <select name="class_id" class="form-select" onchange="this.form.submit()">
                                @forelse($classes as $cls)
                                    <option value="{{ $cls->id }}" {{ $selectedClassId == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                                @empty
                                    <option value="">Tidak ada kelas di unit ini</option>
                                @endforelse
                            </select>
                        </div>
                        {{-- Optional: Reset Button --}}
                        <div class="col-md-4 d-flex align-items-end">
                            <a href="{{ route('wali-kelas.students.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                        </div>
                    </form>
                </div>
                @endif
                <div class="card-body">
                    @if(isset($error))
                        <div class="alert alert-warning">{{ $error }}</div>
                    @else

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="5%">Foto</th>
                                    <th width="10%">NIS</th>
                                    <th>Nama Lengkap</th>
                                    <th>L/P</th>
                                    <th>No HP Orangtua</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $index => $student)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($student->user && $student->user->photo)
                                                <img src="{{ asset('photos/' . $student->user->photo) }}" class="rounded-circle border shadow-sm" width="40" height="40" style="object-fit:cover;">
                                            @else
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center border shadow-sm" style="width:40px; height:40px; font-size:0.8rem;">
                                                    {{ substr($student->nama_lengkap, 0, 1) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $student->nis }}</span></td>
                                        <td>
                                            <div class="fw-bold">{{ $student->nama_lengkap }}</div>
                                            <small class="text-muted">{{ $student->nisn }}</small>
                                        </td>
                                        <td>{{ $student->jenis_kelamin }}</td>
                                        <td>{{ $student->no_hp_wali ?? '-' }}</td>
                                        <td>
                                            @if($student->status == 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($student->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('wali-kelas.students.edit', $student->id) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil-square me-1"></i> Edit Data
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data siswa di kelas ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
