@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="bi bi-database-fill-gear"></i> Backup Database</h3>
                    <form action="{{ route('backups.create') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg"></i> Buat Backup Baru
                        </button>
                    </form>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="50px">No.</th>
                                <th>Nama File</th>
                                <th>Ukuran</th>
                                <th>Tanggal Dibuat</th>
                                <th class="text-center" width="200px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($backups as $key => $backup)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <i class="bi bi-file-earmark-code text-secondary me-2"></i>
                                        {{ $backup['filename'] }}
                                    </td>
                                    <td>{{ $backup['size'] }}</td>
                                    <td>{{ $backup['date'] }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('backups.download', $backup['filename']) }}" class="btn btn-info btn-sm text-white" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        
                                        <form action="{{ route('backups.delete', $backup['filename']) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus backup ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada file backup.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
