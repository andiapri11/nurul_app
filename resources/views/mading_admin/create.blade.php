@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold text-primary"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Konten Mading</h3>
                <p class="text-muted small mb-0">Buat pengumuman, berita, atau poster baru untuk publikasi mading.</p>
            </div>
            <div class="col-sm-6 text-end">
                <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mading-admin.index') }}" class="text-decoration-none">Mading</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
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
                        <h5 class="card-title fw-bold mb-0">Informasi Konten</h5>
                    </div>
                    <form action="{{ route('mading-admin.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body p-4">
                            <!-- Judul & Tipe -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <label for="title" class="form-label fw-bold small text-muted text-uppercase">Judul Konten <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg border-2" id="title" name="title" placeholder="Masukkan judul yang menarik..." required>
                                </div>
                                <div class="col-md-4">
                                    <label for="type" class="form-label fw-bold small text-muted text-uppercase">Tipe Konten <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg border-2" id="type" name="type" required onchange="toggleImageNote()">
                                        <option value="news">Berita / Info</option>
                                        <option value="poster">Poster Gambar</option>
                                        <option value="running_text">Running Text</option>
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
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-text mt-2 small text-muted italic">Pilih unit tertentu jika konten ini bersifat spesifik jenjang.</div>
                            </div>

                            <!-- Isi Konten -->
                            <div class="mb-4">
                                <label for="content" class="form-label fw-bold small text-muted text-uppercase">Isi Konten / Deskripsi <span class="text-danger">*</span></label>
                                <textarea class="form-control border-2" id="content" name="content" rows="5" placeholder="Tuliskan detail pengumuman di sini..." required></textarea>
                            </div>

                            <!-- Gambar & Image Preview -->
                            <div class="row align-items-start mb-4">
                                <div class="col-md-7">
                                    <label for="image" class="form-label fw-bold small text-muted text-uppercase">File Gambar (Opsional)</label>
                                    <input type="file" class="form-control border-2" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                                    <div id="imageNote" class="form-text mt-2 small">Rekomendasi ukuran: 1200x800px untuk berita, A4/Portrait untuk Poster.</div>
                                </div>
                                <div class="col-md-5">
                                    <div id="imagePreviewContainer" class="d-none">
                                        <p class="small text-muted fw-bold mb-2">PREVIEW GAMBAR:</p>
                                        <div class="position-relative">
                                            <img id="img-preview" src="#" alt="Preview" class="img-fluid rounded-3 shadow-sm border" style="max-height: 200px; width: 100%; object-fit: cover;">
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle shadow" onclick="removeImage()">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check form-switch p-3 border rounded-3 bg-light">
                                <input class="form-check-input ms-0 me-3" type="checkbox" id="is_active" name="is_active" checked style="width: 2.5rem; height: 1.25rem;">
                                <label class="form-check-label fw-bold" for="is_active">
                                    Publikasikan Langsung
                                    <span class="d-block small text-muted fw-normal">Konten akan langsung tampil di display mading setelah disimpan.</span>
                                </label>
                            </div>
                        </div>
                        <div class="card-footer bg-light p-4 d-flex justify-content-between align-items-center">
                            <a href="{{ route('mading-admin.index') }}" class="btn btn-outline-secondary border-0 fw-bold px-4">
                                <i class="bi bi-arrow-left me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-pill">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i>Simpan & Publikasikan
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
        const previewContainer = document.getElementById('imagePreviewContainer');
        const output = document.getElementById('img-preview');
        output.src = reader.result;
        previewContainer.classList.remove('d-none');
    }
    reader.readAsDataURL(event.target.files[0]);
}

function removeImage() {
    document.getElementById('image').value = "";
    document.getElementById('imagePreviewContainer').classList.add('d-none');
}

function toggleImageNote() {
    const type = document.getElementById('type').value;
    const note = document.getElementById('imageNote');
    if (type === 'running_text') {
        note.innerHTML = '<span class="text-warning fw-bold"><i class="bi bi-exclamation-triangle me-1"></i>Peringatan:</span> Running text biasanya tidak menggunakan gambar.';
    } else {
        note.innerHTML = 'Rekomendasi ukuran: 1200x800px untuk berita, A4/Portrait untuk Poster.';
    }
}
</script>

<style>
    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.1);
    }
    .input-group-text {
        border-color: #dee2e6;
    }
</style>
@endsection

