@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold text-warning"><i class="bi bi-pencil-square me-2"></i>Edit Konten Mading</h3>
                <p class="text-muted small mb-0">Perbarui informasi, tipe, atau status publikasi konten mading.</p>
            </div>
            <div class="col-sm-6 text-end">
                <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mading-admin.index') }}" class="text-decoration-none">Mading</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                    <div class="card-header bg-white py-3 border-bottom border-light">
                        <h5 class="card-title fw-bold mb-0 text-dark">Formulir Pembaruan Konten</h5>
                    </div>
                    <form action="{{ route('mading-admin.update', $mading_admin->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body p-4">
                            <!-- Judul & Tipe -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <label for="title" class="form-label fw-bold small text-muted text-uppercase">Judul Konten <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg border-2" id="title" name="title" value="{{ $mading_admin->title }}" placeholder="Masukkan judul..." required>
                                </div>
                                <div class="col-md-4">
                                    <label for="type" class="form-label fw-bold small text-muted text-uppercase">Tipe Konten <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg border-2" id="type" name="type" required onchange="toggleImageNote()">
                                        <option value="news" {{ $mading_admin->type == 'news' ? 'selected' : '' }}>Berita / Info</option>
                                        <option value="poster" {{ $mading_admin->type == 'poster' ? 'selected' : '' }}>Poster Gambar</option>
                                        <option value="running_text" {{ $mading_admin->type == 'running_text' ? 'selected' : '' }}>Running Text</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Target Unit -->
                            <div class="mb-4">
                                <label for="unit_id" class="form-label fw-bold small text-muted text-uppercase">Target Unit</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-2 border-end-0"><i class="bi bi-geo-alt"></i></span>
                                    <select class="form-select border-2 border-start-0" id="unit_id" name="unit_id">
                                        <option value="">Semua Unit (Tampil di Seluruh Mading)</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ $mading_admin->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Isi Konten -->
                            <div class="mb-4">
                                <label for="content" class="form-label fw-bold small text-muted text-uppercase">Isi Konten / Deskripsi <span class="text-danger">*</span></label>
                                <textarea class="form-control border-2" id="content" name="content" rows="6" placeholder="Tuliskan detail pengumuman..." required>{{ $mading_admin->content }}</textarea>
                            </div>

                            <!-- Gambar & Image Preview -->
                            <div class="row align-items-start mb-4">
                                <div class="col-md-6">
                                    <label for="image" class="form-label fw-bold small text-muted text-uppercase">Ganti Gambar (Opsional)</label>
                                    <input type="file" class="form-control border-2" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                                    <div id="imageNote" class="form-text mt-2 small">Biarkan kosong jika tidak ingin mengubah gambar.</div>
                                </div>
                                <div class="col-md-6">
                                    <div id="previewWrapper" class="p-2 border rounded-3 bg-light text-center">
                                        <p class="small text-muted fw-bold mb-2">PRATINJAU GAMBAR:</p>
                                        @if($mading_admin->image)
                                            <img id="img-preview" src="{{ asset('storage/' . $mading_admin->image) }}" alt="Preview" class="img-fluid rounded-3 shadow-sm" style="max-height: 180px;">
                                        @else
                                            <div id="empty-preview" class="py-4 text-muted"> <i class="bi bi-image" style="font-size: 2rem;"></i> <p class="mb-0 small">Belum ada gambar</p> </div>
                                            <img id="img-preview" src="#" alt="Preview" class="img-fluid rounded-3 shadow-sm d-none" style="max-height: 180px;">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-check form-switch p-3 border rounded-3 bg-light mt-2">
                                <input class="form-check-input ms-0 me-3" type="checkbox" id="is_active" name="is_active" {{ $mading_admin->is_active ? 'checked' : '' }} style="width: 2.5rem; height: 1.25rem;">
                                <label class="form-check-label fw-bold" for="is_active">
                                    Publikasikan Konten
                                    <span class="d-block small text-muted fw-normal">Konten akan {{ $mading_admin->is_active ? 'tetap' : 'mulai' }} tampil di display mading setelah diperbarui.</span>
                                </label>
                            </div>
                        </div>
                        <div class="card-footer bg-light p-4 d-flex justify-content-between align-items-center">
                            <a href="{{ route('mading-admin.index') }}" class="btn btn-outline-secondary border-0 fw-bold px-4">
                                <i class="bi bi-arrow-left me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-warning px-5 py-2 fw-bold shadow-sm rounded-pill text-dark">
                                <i class="bi bi-save-fill me-2"></i>Perbarui Konten
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const output = document.getElementById('img-preview');
        const empty = document.getElementById('empty-preview');
        output.src = reader.result;
        output.classList.remove('d-none');
        if (empty) empty.classList.add('d-none');
    }
    reader.readAsDataURL(event.target.files[0]);
}

function toggleImageNote() {
    const type = document.getElementById('type').value;
    const note = document.getElementById('imageNote');
    if (type === 'running_text') {
        note.innerHTML = '<span class="text-warning fw-bold"><i class="bi bi-exclamation-triangle me-1"></i>Peringatan:</span> Running text biasanya tidak menggunakan gambar.';
    } else {
        note.innerHTML = 'Biarkan kosong jika tidak ingin mengubah gambar.';
    }
}
</script>

<style>
    .form-control:focus, .form-select:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.1);
    }
</style>
@endsection

