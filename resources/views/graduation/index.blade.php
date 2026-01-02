@extends('layouts.app')

@section('title', 'Daftar Pengumuman Kelulusan')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 font-weight-bold text-gray-800 mb-1">Manajemen Pengumuman Kelulusan</h1>
                <p class="text-muted mb-0">Tahun Pelajaran: <strong>{{ $activeYear->name }}</strong></p>
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('graduation.index') }}" method="GET" class="d-flex gap-2">
                    <select name="unit_id" class="form-select" onchange="this.form.submit()">
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ $selectedUnitId == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </form>
                <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
                    <i class="bi bi-plus-lg me-1"></i> Buat Pengumuman Baru
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @forelse($announcements as $ann)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden transition-all hover-shadow">
                @if($ann->is_active)
                    <div class="position-absolute top-0 end-0 bg-success text-white px-3 py-1 rounded-bl-lg small shadow-sm z-1">
                        <i class="bi bi-broadcast me-1"></i> AKTIF
                    </div>
                @endif
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                            <i class="bi bi-megaphone text-primary h4 mb-0"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0 text-truncate" style="max-width: 200px;">{{ $ann->title }}</h5>
                            <small class="text-muted">{{ $ann->announcement_date ? \Carbon\Carbon::parse($ann->announcement_date)->translatedFormat('d F Y, H:i') : 'Tanggal belum diatur' }}</small>
                        </div>
                    </div>
                    
                    <p class="text-muted small mb-4 line-clamp-2">
                        {{ $ann->description ?: 'Tidak ada deskripsi tambahan.' }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <div class="small fw-bold text-dark">
                            <i class="bi bi-people me-1"></i> {{ $ann->results->count() }} Siswa
                        </div>
                        <div class="d-flex gap-2">
                             <a href="{{ route('graduation.show', $ann->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                Kelola <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <form action="{{ route('graduation.destroy', $ann->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0 ms-2" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                <div class="display-1 text-muted opacity-25 mb-3">
                    <i class="bi bi-megaphone"></i>
                </div>
                <h4 class="fw-bold text-muted">Belum Ada Pengumuman</h4>
                <p class="text-muted">Klik tombol di kanan atas untuk membuat pengumuman kelulusan baru.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createAnnouncementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Buat Pengumuman Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('graduation.store') }}" method="POST">
                @csrf
                <input type="hidden" name="unit_id" value="{{ $selectedUnitId }}">
                <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Pengumuman</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Pengumuman Kelulusan SMA 2024" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi/Pesan Pembuka</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Pesan yang akan muncul di pop-up siswa..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal & Waktu Pengumuman</label>
                        <input type="datetime-local" name="announcement_date" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-light px-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan & Lanjutkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
}
.rounded-bl-lg {
    border-bottom-left-radius: 1rem;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
