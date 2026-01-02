@extends('layouts.app')

@php
    $title = 'Otoritas Keuangan';
@endphp

@section('title', $title)

@section('content')
<style>
    .premium-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .table-modern thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #64748b;
        border-top: none;
        padding: 1rem;
    }
    .table-modern td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }
    .avatar-initial {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 12px;
    }
    .badge-premium {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    .role-kepala { background: #eef2ff; color: #4338ca; }
    .role-admin { background: #f1f5f9; color: #475569; }
    .unit-badge { background: #ecfdf5; color: #059669; }
    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
    }
    .btn-edit { background: #fef3c7; color: #d97706; }
    .btn-delete { background: #fee2e2; color: #dc2626; }
    .btn-action:hover { transform: translateY(-2px); opacity: 0.9; }
</style>

<div class="app-content-header py-4">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="fw-bold m-0"><i class="bi bi-shield-lock me-2 text-primary"></i>Manajemen Otoritas Keuangan</h3>
                <p class="text-muted small mb-0">Kelola hak akses dan penugasan staf administrasi keuangan.</p>
            </div>
            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <a href="{{ route('financial-admins.create') }}" class="btn btn-primary px-4 py-2 shadow-sm fw-bold">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Otoritas
                </a>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
                        <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <div class="card premium-card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;" class="ps-4">No</th>
                                        <th>Pengguna</th>
                                        <th>Role & Otoritas</th>
                                        <th>Penugasan (Jabatan/Unit)</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($financialAdmins as $admin)
                                    <tr>
                                        <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-initial">
                                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $admin->name }}</div>
                                                    <div class="small text-muted">{{ $admin->username }} • {{ $admin->email }}</div>
                                                    @if($admin->nip) <div class="badge bg-light text-dark border py-1 px-2 mt-1" style="font-size: 0.65rem">NIP: {{ $admin->nip }}</div> @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($admin->role == 'kepala_keuangan')
                                                <span class="badge-premium role-kepala">
                                                    <i class="bi bi-star-fill me-1"></i> Kepala Keuangan
                                                </span>
                                            @else
                                                <span class="badge-premium role-admin">
                                                    <i class="bi bi-person-badge me-1"></i> Admin Keuangan
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($admin->jabatanUnits as $ju)
                                                    <div class="p-2 border rounded bg-light mb-1" style="min-width: 150px;">
                                                        <div class="small fw-bold">{{ $ju->jabatan->nama_jabatan ?? 'N/A' }}</div>
                                                        <div class="text-muted" style="font-size: 0.75rem;">
                                                            <i class="bi bi-building me-1"></i> {{ $ju->unit->name ?? 'Semua Unit (Yayasan)' }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('financial-admins.edit', $admin->id) }}" class="btn-action btn-edit" title="Edit">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <form action="{{ route('financial-admins.destroy', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus otoritas ini? Tindakan ini tidak dapat dibatalkan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action btn-delete" title="Hapus">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox display-1 opacity-25"></i>
                                                <p class="mt-3">Belum ada data otoritas keuangan yang terdaftar.</p>
                                                <a href="{{ route('financial-admins.create') }}" class="btn btn-sm btn-primary">Mulai Tambah Data</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
