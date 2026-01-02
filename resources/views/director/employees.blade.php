@extends('layouts.app')

@section('title', 'Direktori Pegawai')

@section('content')
<div class="container-fluid p-4">
    <!-- Header Section -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-1">Direktori Pegawai</h3>
            <p class="text-muted mb-0">Kelola dan pantau data seluruh pegawai sekolah.</p>
        </div>
        <div class="col-md-6">
            <form action="{{ route('director.employees') }}" method="GET" class="d-flex gap-2 justify-content-md-end mt-3 mt-md-0">
                <input type="text" name="search" class="form-control" placeholder="Cari Nama / NIP..." value="{{ request('search') }}" style="max-width: 250px; border-radius: 20px;">
                <select name="unit_id" class="form-select" onchange="this.form.submit()" style="max-width: 200px; border-radius: 20px;">
                    <option value="">-- Semua Unit --</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}" {{ request('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Summary (Optional Mini-dashboard) -->
    <!-- Could add here, but user focused on "Tampilan Bagus" list -->

    <!-- Employee Grid -->
    <div class="row g-4">
        @forelse($employees as $emp)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm card-hover-effect" style="border-radius: 15px; overflow: hidden;">
                <!-- Card Header / Gradient Banner -->
                <div style="height: 80px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); position: relative;">
                    <!-- Status Badge -->
                    <span class="badge position-absolute top-0 end-0 m-3 {{ $emp->status == 'aktif' ? 'bg-success' : 'bg-danger' }} rounded-pill">
                        {{ ucfirst($emp->status) }}
                    </span>
                </div>
                
                <!-- Avatar & Info -->
                <div class="card-body text-center pt-0 position-relative">
                    <div class="avatar-container position-relative mx-auto" style="width: 80px; height: 80px; margin-top: -40px; margin-bottom: 1rem;">
                        @if($emp->photo)
                            <img src="{{ asset('photos/' . $emp->photo) }}" class="rounded-circle border border-4 border-white shadow-sm" style="width: 100%; height: 100%; object-fit: cover;" alt="{{ $emp->name }}">
                        @else
                            <div class="rounded-circle border border-4 border-white shadow-sm bg-light d-flex align-items-center justify-content-center text-primary fw-bold fs-3" style="width: 100%; height: 100%;">
                                {{ strtoupper(substr($emp->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <h5 class="fw-bold mb-1 text-dark text-truncate" title="{{ $emp->name }}">{{ $emp->name }}</h5>
                    <p class="text-muted small mb-2">{{ $emp->nip ?? $emp->username }}</p>

                    <!-- Role Badge -->
                    <div class="mb-3">
                         <span class="badge bg-light text-primary border border-primary-subtle rounded-pill px-3">{{ ucfirst($emp->role) }}</span>
                    </div>

                    <hr class="my-3 opacity-10">

                    <!-- Info Details -->
                    <div class="text-start">
                        <small class="text-secondary fw-bold text-uppercase d-block mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Jabatan & Unit</small>
                        <div class="d-flex flex-column gap-1">
                            @forelse($emp->jabatanUnits as $ju)
                                <div class="d-flex align-items-center p-2 rounded bg-light">
                                    <div class="flex-shrink-0 text-primary me-2">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="text-truncate fw-medium small text-dark">{{ $ju->unit->name ?? 'Unit ?' }}</div>
                                        <div class="text-truncate small text-muted" style="font-size: 0.75rem;">{{ $ju->jabatan->nama_jabatan ?? 'Jabatan ?' }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted small fst-italic">Belum ada penugasan unit.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="card-footer bg-white border-top-0 pb-3 pt-0">
                    <div class="d-grid">
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#empModal{{ $emp->id }}">
                            <i class="bi bi-eye me-1"></i> Detail Lengkap
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detail (Optional, keeps UI clean) -->
        <div class="modal fade" id="empModal{{ $emp->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0 bg-primary text-white">
                        <h5 class="modal-title fw-bold">{{ $emp->name }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="text-center mb-4">
                             @if($emp->photo)
                                <img src="{{ asset('photos/' . $emp->photo) }}" class="rounded-circle shadow-sm mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="mx-auto rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold fs-2 shadow-sm mb-2" style="width: 100px; height: 100px;">
                                    {{ strtoupper(substr($emp->name, 0, 1)) }}
                                </div>
                            @endif
                            <h5>{{ $emp->role }}</h5>
                            <span class="badge {{ $emp->status == 'aktif' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($emp->status) }}</span>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">NIP/Username</span>
                                <span class="fw-medium">{{ $emp->nip ?? $emp->username }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Email</span>
                                <span class="fw-medium">{{ $emp->email }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">No. Telepon</span>
                                <span class="fw-medium">{{ $emp->phone ?? '-' }}</span>
                            </li>
                             <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Jenis Kelamin</span>
                                <span class="fw-medium">{{ $emp->gender ?? '-' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light text-center py-5 border-0 shadow-sm">
                    <i class="bi bi-search fs-1 text-muted opacity-50 mb-3"></i>
                    <h5 class="text-muted">Tidak ada data pegawai ditemukan.</h5>
                    <p class="text-black-50 mb-0">Coba ubah filter atau kata kunci pencarian Anda.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $employees->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    .card-hover-effect {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .pagination .page-item .page-link {
        border-radius: 50%;
        margin: 0 3px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        color: #6c757d;
    }
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        color: white;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }
    .text-truncate {
        max-width: 100%;
    }
</style>
@endsection
