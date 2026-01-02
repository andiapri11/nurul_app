@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Manajemen Mading</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mading</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Konten Mading</h3>
                        <div class="card-tools">
                            <a href="{{ route('mading-admin.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg"></i> Tambah Konten
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Tipe</th>
                                    <th>Unit</th>
                                    <th>Konten</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($announcements as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>
                                        @if($item->type == 'running_text')
                                            <span class="badge text-bg-warning">Running Text</span>
                                        @elseif($item->type == 'news')
                                            <span class="badge text-bg-info">Berita</span>
                                        @else
                                            <span class="badge text-bg-primary">Poster</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->unit->name ?? 'Semua Unit' }}</td>
                                    <td>{{ Str::limit($item->content, 50) }}</td>
                                    <td>
                                        @if($item->is_active)
                                            <span class="badge text-bg-success">Aktif</span>
                                        @else
                                            <span class="badge text-bg-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('mading-admin.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('mading-admin.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus konten ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
