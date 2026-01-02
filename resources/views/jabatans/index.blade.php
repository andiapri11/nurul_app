@extends('layouts.app')

@section('title', 'Daftar Jabatan')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1 class="m-0 fw-bold"><i class="bi bi-briefcase me-2 text-primary"></i>Daftar Jabatan Master</h1>
            <p class="text-muted small mb-0">Kelola master data jabatan guru dan karyawan (Global/Berlaku Semua Tahun).</p>
        </div>
        <div class="col-sm-6 text-end">
            <a class="btn btn-primary btn-sm px-3 shadow-sm" href="{{ route('jabatans.create') }}"> 
                <i class="bi bi-plus-lg me-1"></i> Tambah Jabatan Baru
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @foreach($jabatans as $unitName => $items)
        <div class="card shadow-sm border-0 mb-4 overflow-hidden">
            <div class="card-header bg-white py-3">
                <h5 class="m-0 fw-bold text-primary">
                    <i class="bi bi-building me-2"></i>{{ $unitName }}
                    <span class="badge bg-light text-primary border ms-2 small">{{ count($items) }} Jabatan</span>
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light text-uppercase">
                        <tr class="small fw-bold">
                            <th width="60px" class="text-center">No</th>
                            <th>Kode Jabatan</th>
                            <th>Nama Jabatan</th>
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th width="150px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $jabatan)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td><code>{{ $jabatan->kode_jabatan }}</code></td>
                                <td class="fw-bold">{{ $jabatan->nama_jabatan }}</td>
                                <td>
                                    @php
                                        $katColor = [
                                            'guru' => 'success',
                                            'tambahan' => 'info',
                                            'staff' => 'secondary'
                                        ][$jabatan->kategori] ?? 'light';
                                    @endphp
                                    <span class="badge bg-{{ $katColor }}-subtle text-{{ $katColor }} border border-{{ $katColor }}">
                                        {{ ucfirst($jabatan->kategori) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $tipeColor = [
                                            'struktural' => 'warning',
                                            'tambahan' => 'primary',
                                            'fungsional' => 'light'
                                        ][$jabatan->tipe] ?? 'light';
                                    @endphp
                                    <span class="badge bg-{{ $tipeColor == 'light' ? 'light text-dark border' : $tipeColor }}">
                                        {{ ucfirst($jabatan->tipe) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm">
                                        <a class="btn btn-white btn-sm border" href="{{ route('jabatans.edit',$jabatan->id) }}" title="Edit">
                                            <i class="bi bi-pencil-square text-primary"></i>
                                        </a>
                                        <form action="{{ route('jabatans.destroy',$jabatan->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-white btn-sm border" onclick="return confirm('Hapus jabatan ini?')" title="Hapus">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection
