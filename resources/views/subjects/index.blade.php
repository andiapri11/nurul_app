@extends('layouts.app')

@section('title', 'Manajemen Mata Pelajaran')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Manajemen Mata Pelajaran</h1>
            <p class="text-muted small mb-0">Kelola daftar kurikulum dan mata pelajaran untuk setiap unit.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('subjects.create') }}" class="btn btn-primary shadow-sm px-4 py-2 rounded-pill">
                <i class="bi bi-plus-circle me-1"></i> <strong>Tambah Mapel</strong>
            </a>
        </div>
    </div>

    <!-- Search Section -->
    <div class="card border-0 shadow-sm mb-4 bg-gradient-brand-light">
        <div class="card-body p-3">
            <form action="{{ route('subjects.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group input-group-modern shadow-sm border rounded-pill overflow-hidden bg-white">
                        <span class="input-group-text bg-white border-0 ps-3">
                            <i class="bi bi-search text-primary"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-0 px-2 py-2" placeholder="Cari Kode atau Nama Mapel..." value="{{ request('search') }}">
                        @if(request('search'))
                            <a href="{{ route('subjects.index') }}" class="btn bg-white border-0 text-muted px-3" title="Clear">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif
                        <button class="btn btn-primary px-4" type="submit">Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div>{{ $message }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Units Loop -->
    <div class="row g-4">
        @forelse($units as $unit)
            @if($unit->subjects->count() > 0 || request('search')) 
            <div class="col-12">
                <div class="card border-0 shadow-sm overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="unit-icon-box bg-soft-primary me-3">
                                <i class="bi bi-journal-bookmark-fill text-primary"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">{{ $unit->name }}</h5>
                                <span class="badge bg-light text-primary rounded-pill small border">{{ $unit->subjects->count() }} Mata Pelajaran</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover custom-table mb-0">
                                <thead>
                                    <tr>
                                        <th width="80px" class="text-center">No</th>
                                        <th width="150px">Kode Mapel</th>
                                        <th>Nama Mata Pelajaran</th>
                                        <th width="180px" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($unit->subjects as $index => $subject)
                                        <tr>
                                            <td class="text-center text-muted font-monospace">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="badge bg-soft-primary px-3 py-2 fw-bold text-uppercase" style="letter-spacing: 0.5px;">{{ $subject->code ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $subject->name }}</div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a class="btn btn-soft-primary btn-sm rounded-pill px-3" href="{{ route('subjects.edit', $subject->id) }}" title="Edit">
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </a>
                                                    <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-soft-danger btn-sm rounded-pill px-3" onclick="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <div class="mb-3">
                                                    <i class="bi bi-search fs-1 text-muted opacity-50"></i>
                                                </div>
                                                <p class="text-muted">Tidak ada mata pelajaran ditemukan untuk unit ini.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm py-5 text-center">
                    <div class="card-body">
                        <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                        <h4 class="fw-bold">Belum Ada Unit</h4>
                        <p class="text-muted">Tidak ditemukan unit yang terhubung dengan akun Anda.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .bg-gradient-brand-light {
        background: linear-gradient(135deg, #f8f9fc 0%, #edf2ff 100%);
    }

    .unit-icon-box {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .bg-soft-primary { background-color: #eef2ff; color: #4e73df; }
    .bg-soft-danger { background-color: #fff2f2; color: #e74a3b; }

    .btn-soft-primary {
        background-color: #eef2ff;
        color: #4e73df;
        border: none;
    }
    .btn-soft-primary:hover {
        background-color: #4e73df;
        color: white;
    }

    .btn-soft-danger {
        background-color: #fff2f2;
        color: #e74a3b;
        border: none;
    }
    .btn-soft-danger:hover {
        background-color: #e74a3b;
        color: white;
    }

    .input-group-modern:focus-within {
        border-color: #4e73df !important;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.1) !important;
    }

    .custom-table thead th {
        background-color: #f8f9fc;
        border-top: none;
        border-bottom: 2px solid #e3e6f0;
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        color: #4e73df;
        padding: 15px;
    }

    .custom-table tbody td {
        padding: 15px;
        vertical-align: middle;
    }

    .rounded-pill { border-radius: 50rem !important; }
</style>
@endpush
@endsection
