@extends('layouts.app')

@section('title', 'Manajemen Kurikulum')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="bi bi-journal-bookmark-fill"></i> Manajemen Dokumen Guru</h3>
            <div>

                <a href="{{ route('curriculum.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Buat Permintaan Dokumen
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Unit Info Banner -->
            @if(isset($units) && $units->count() > 0)
                <div class="alert alert-info border-start border-4 border-info shadow-sm mb-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-building-fill-gear fs-3 me-3 text-info-emphasis"></i>
                        <div>
                            <h6 class="fw-bold text-info-emphasis mb-1">Area Tanggung Jawab Kurikulum</h6>
                            <p class="mb-0 small text-muted">
                                Anda terdaftar sebagai Wakil Kepala Sekolah Bidang Kurikulum untuk unit:
                                @foreach($units as $u)
                                    <span class="badge bg-white text-info border border-info-subtle ms-1">{{ $u->name }}</span>
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Filter Section -->
            <form action="{{ route('curriculum.index') }}" method="GET" class="row g-2 mb-4 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Tahun Pelajaran</label>
                    <select name="academic_year_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Tahun --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ $yearId == $year->id ? 'selected' : '' }}>
                                {{ $year->name }} {{ $year->status == 'active' ? 'AKTIF' : 'TIDAK AKTIF' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Unit Pendidikan</label>
                    <select name="unit_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Unit --</option>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}" {{ $unitId == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('curriculum.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </form>

            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Judul Permintaan</th>
                        <th>Tahun Ajaran</th>
                        <th>Tenggat Waktu</th>
                        <th>Progres</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $req->title }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($req->description, 50) }}</small>
                            </td>
                            <td>{{ $req->academicYear ? $req->academicYear->name . ' (' . ucfirst($req->semester) . ')' : '-' }}</td>
                            <td>
                                {{ $req->due_date ? $req->due_date->format('d M Y') : '-' }}
                                @if($req->due_date && $req->due_date->isPast())
                                    <span class="badge bg-danger">Lewat</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-info text-dark me-2 fs-6">{{ $req->submissions_count }}</span>
                                    <span class="small text-muted">Guru</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $req->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $req->is_active ? 'Aktif' : 'Arsip' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear"></i> Menu
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('curriculum.show', $req->id) }}">
                                                <i class="bi bi-eye text-info"></i> Detail
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('curriculum.edit', $req->id) }}">
                                                <i class="bi bi-pencil text-warning"></i> Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('curriculum.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Hapus permintaan ini? Data submission terkait juga akan terhapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="submit">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada permintaan dokumen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
