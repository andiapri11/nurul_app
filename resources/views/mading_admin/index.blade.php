@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold text-primary"><i class="bi bi-megaphone-fill me-2"></i>Manajemen Mading</h3>
                <p class="text-muted small mb-0">Kelola informasi, berita, dan running text untuk display mading sekolah.</p>
            </div>
            <div class="col-sm-6 text-end">
                <a href="{{ route('mading-admin.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Konten Baru
                </a>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <!-- Dashboard Summary -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 bg-primary text-white overflow-hidden position-relative">
                    <div class="card-body p-4 position-relative" style="z-index: 2;">
                        <h4 class="fw-bold mb-1">{{ $announcements->count() }}</h4>
                        <p class="mb-0 opacity-75 small">Total Konten</p>
                    </div>
                    <i class="bi bi-collection-play position-absolute bottom-0 end-0 opacity-25 p-3" style="font-size: 4rem; transform: translate(10px, 10px);"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 bg-success text-white overflow-hidden position-relative">
                    <div class="card-body p-4 position-relative" style="z-index: 2;">
                        <h4 class="fw-bold mb-1">{{ $announcements->where('is_active', true)->count() }}</h4>
                        <p class="mb-0 opacity-75 small">Konten Aktif</p>
                    </div>
                    <i class="bi bi-check-circle position-absolute bottom-0 end-0 opacity-25 p-3" style="font-size: 4rem; transform: translate(10px, 10px);"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 bg-warning text-dark overflow-hidden position-relative">
                    <div class="card-body p-4 position-relative" style="z-index: 2;">
                        <h4 class="fw-bold mb-1">{{ $announcements->where('type', 'running_text')->count() }}</h4>
                        <p class="mb-0 opacity-75 small">Running Text</p>
                    </div>
                    <i class="bi bi-broadcast position-absolute bottom-0 end-0 opacity-25 p-3" style="font-size: 4rem; transform: translate(10px, 10px);"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 bg-info text-white overflow-hidden position-relative">
                    <div class="card-body p-4 position-relative" style="z-index: 2;">
                        <h4 class="fw-bold mb-1">{{ $announcements->where('type', 'news')->count() + $announcements->where('type', 'poster')->count() }}</h4>
                        <p class="mb-0 opacity-75 small">Informasi/Poster</p>
                    </div>
                    <i class="bi bi-images position-absolute bottom-0 end-0 opacity-25 p-3" style="font-size: 4rem; transform: translate(10px, 10px);"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom border-light">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title fw-bold mb-0">Daftar Konten Aktif</h5>
                            <button class="btn btn-light btn-sm text-primary rounded-pill px-3" onclick="window.location.reload()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if(session('success'))
                            <div class="alert alert-success border-0 rounded-0 mb-0 shadow-sm" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small">
                                    <tr>
                                        <th class="ps-4 py-3">INFO KONTEN</th>
                                        <th class="py-3">TIPE & TARGET</th>
                                        <th class="py-3">RINGKASAN</th>
                                        <th class="py-3">STATUS</th>
                                        <th class="pe-4 py-3 text-end">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($announcements as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                @if($item->image)
                                                    <div class="me-3 position-relative">
                                                        <img src="{{ asset('storage/' . $item->image) }}" class="rounded-3 shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                                        @if($item->type == 'poster')
                                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger p-1 border border-2 border-white">
                                                                <i class="bi bi-image" style="font-size: 0.6rem;"></i>
                                                            </span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="me-3 rounded-3 bg-light d-flex align-items-center justify-content-center text-primary shadow-sm" style="width: 45px; height: 45px;">
                                                        <i class="bi {{ $item->type == 'running_text' ? 'bi-broadcast' : 'bi-text-paragraph' }}"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $item->title }}</div>
                                                    <div class="text-muted small">ID: #{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="mb-1">
                                                @if($item->type == 'running_text')
                                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-1">
                                                        <i class="bi bi-broadcast me-1"></i>Running Text
                                                    </span>
                                                @elseif($item->type == 'news')
                                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-1">
                                                        <i class="bi bi-newspaper me-1"></i>Berita/Info
                                                    </span>
                                                @else
                                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">
                                                        <i class="bi bi-image me-1"></i>Poster
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="text-muted small">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $item->unit->name ?? 'Semua Unit' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-muted small text-truncate" style="max-width: 250px;">
                                                {{ Str::limit($item->content, 80) }}
                                            </div>
                                            <div class="mt-1 small fw-medium">
                                                <i class="bi bi-clock-history me-1"></i>{{ $item->created_at->translatedFormat('d M Y, H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($item->is_active)
                                                <div class="d-flex align-items-center text-success fw-medium small">
                                                    <span class="d-inline-block bg-success rounded-circle me-2 ripple-success" style="width: 8px; height: 8px;"></span>
                                                    Aktif
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center text-secondary fw-medium small">
                                                    <span class="d-inline-block bg-secondary rounded-circle me-2" style="width: 8px; height: 8px;"></span>
                                                    Non-Aktif
                                                </div>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                                <a href="{{ route('mading-admin.edit', $item->id) }}" class="btn btn-white btn-sm px-3 border-end" title="Edit">
                                                    <i class="bi bi-pencil-square text-warning"></i>
                                                </a>
                                                <form action="{{ route('mading-admin.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus konten ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-white btn-sm px-3" title="Hapus">
                                                        <i class="bi bi-trash3 text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="py-5 text-center">
                                            <div class="py-5">
                                                <i class="bi bi-inbox text-muted opacity-25" style="font-size: 5rem;"></i>
                                                <p class="mt-3 text-muted">Belum ada konten mading yang dibuat.</p>
                                                <a href="{{ route('mading-admin.create') }}" class="btn btn-primary rounded-pill px-4 mt-2">Buat Konten Pertama</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($announcements->hasPages())
                    <div class="card-footer bg-white py-3 border-top border-light">
                        {{ $announcements->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ripple-success {
        box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4);
        animation: ripple-green 2s infinite;
    }
    @keyframes ripple-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(25, 135, 84, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
    }
    .btn-white {
        background-color: #fff;
        border: 1px solid #f8f9fa;
    }
    .btn-white:hover {
        background-color: #f8f9fa;
    }
    .table > :not(caption) > * > * {
        background-color: transparent;
    }
</style>
@endsection
