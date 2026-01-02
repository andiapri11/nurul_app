@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Histori Login User</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Histori Login</li>
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
                        <h3 class="card-title">Daftar Riwayat Login</h3>
                        <div class="card-tools">
                            <form action="{{ route('login-history.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus SEMUA riwayat login? Tindakan ini tidak dapat dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Bersihkan Histori
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="mb-3">
                            <form action="{{ route('login-history.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari nama user atau email..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                                    @if(request('search'))
                                        <a href="{{ route('login-history.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Reset</a>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Waktu Login</th>
                                    <th>User</th>
                                    <th>Role</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($histories as $history)
                                    <tr>
                                        <td>{{ $history->login_at->format('d M Y H:i:s') }}</td>
                                        <td>
                                            @if($history->user)
                                                <strong>{{ $history->user->name }}</strong>
                                                <br><small class="text-muted">{{ $history->user->email }}</small>
                                            @elseif($history->siswa)
                                                <strong>{{ $history->siswa->name }}</strong>
                                                <br><small class="text-muted">{{ $history->siswa->email }} <span class="badge bg-light text-dark">Siswa</span></small>
                                            @else
                                                <span class="text-danger">User Deleted</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($history->user)
                                                <span class="badge bg-info">{{ ucfirst($history->user->role) }}</span>
                                            @elseif($history->siswa)
                                                <span class="badge bg-success">Siswa</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $history->ip_address }}</td>
                                        <td class="small text-muted">{{ Str::limit($history->user_agent, 80) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada data histori login.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $histories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
