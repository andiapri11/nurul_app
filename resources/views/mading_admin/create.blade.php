@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Tambah Konten Mading</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mading-admin.index') }}">Mading</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form Tambah Konten</h3>
                    </div>
                    <form action="{{ route('mading-admin.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="type" class="form-label">Tipe Konten</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="news">Berita / Info</option>
                                    <option value="poster">Poster Gambar</option>
                                    <option value="running_text">Running Text (Teks Berjalan)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="unit_id" class="form-label">Target Unit</label>
                                <select class="form-select" id="unit_id" name="unit_id">
                                    <option value="">Semua Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Kosongkan jika ingin tampil di semua unit.</div>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Isi Konten / Teks</label>
                                <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar (Opsional)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="form-text">Hanya untuk tipe News atau Poster.</div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    Aktifkan Langsung
                                </label>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('mading-admin.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
