@extends('layouts.app')

@section('title', 'Kelola Pengumuman Kelas')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">Pengumuman Kelas: {{ $myClass->name }}</h3>
                        <p class="mb-0">{{ $myClass->unit->name }} | {{ $myClass->academicYear ? $myClass->academicYear->name : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        {{-- Form Create --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title fw-bold mb-0">Buat Pengumuman Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('wali-kelas.announcements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Pengumuman</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required value="{{ old('title') }}" placeholder="Contoh: Jadwal UTS, Libur Nasional">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Isi Pengumuman</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="6" required placeholder="Tulis detail pengumuman di sini...">{{ old('content') }}</textarea>
                            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="attachment" class="form-label">Lampiran File (Opsional)</label>
                            <input type="file" class="form-control @error('attachment') is-invalid @enderror" id="attachment" name="attachment">
                            <div class="form-text text-muted">Maksimal 5MB (PDF, DOCX, JPG, PNG).</div>
                            @error('attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send-fill me-2"></i> Terbitkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- List Announcements --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0">Daftar Pengumuman</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 20%;">Tanggal</th>
                                    <th>Judul & Isi</th>
                                    <th style="width: 15%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($announcements as $index => $announcement)
                                <tr>
                                    <td class="text-center">{{ $announcements->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $announcement->created_at->translatedFormat('d F Y') }}</div>
                                        <small class="text-muted">{{ $announcement->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary">{{ $announcement->title }}</div>
                                        <p class="mb-1 text-muted small text-truncate" style="max-width: 400px;">
                                            {{ Str::limit($announcement->content, 80) }}
                                        </p>
                                        @if($announcement->attachment)
                                            <a href="{{ asset('storage/' . $announcement->attachment) }}" target="_blank" class="badge bg-info text-white text-decoration-none">
                                                <i class="bi bi-paperclip me-1"></i> {{ $announcement->original_filename }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('wali-kelas.announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Hapus">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-megaphone-fill display-4 d-block mb-3 opacity-25"></i>
                                        Belum ada pengumuman yang dibuat.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($announcements->hasPages())
                <div class="card-footer">
                    {{ $announcements->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
