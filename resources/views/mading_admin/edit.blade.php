@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Edit Konten Mading</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('mading-admin.index') }}">Mading</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                        <h3 class="card-title">Form Edit Konten</h3>
                    </div>
                    <form action="{{ route('mading-admin.update', $mading_admin->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ $mading_admin->title }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="type" class="form-label">Tipe Konten</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="news" {{ $mading_admin->type == 'news' ? 'selected' : '' }}>Berita / Info</option>
                                    <option value="poster" {{ $mading_admin->type == 'poster' ? 'selected' : '' }}>Poster Gambar</option>
                                    <option value="running_text" {{ $mading_admin->type == 'running_text' ? 'selected' : '' }}>Running Text (Teks Berjalan)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="unit_id" class="form-label">Target Unit</label>
                                <select class="form-select" id="unit_id" name="unit_id">
                                    <option value="" {{ !$mading_admin->unit_id ? 'selected' : '' }}>Semua Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ $mading_admin->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Isi Konten / Teks</label>
                                <textarea class="form-control" id="content" name="content" rows="4" required>{{ $mading_admin->content }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar (Biarkan kosong jika tidak diubah)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                @if($mading_admin->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $mading_admin->image) }}" alt="Preview" style="max-height: 100px;">
                                    </div>
                                @endif
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ $mading_admin->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif
                                </label>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('mading-admin.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
