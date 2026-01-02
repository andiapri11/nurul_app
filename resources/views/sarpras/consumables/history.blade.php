@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Histori Mutasi Barang Habis Pakai</h1>
        <a href="{{ route('sarpras.consumables.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Filter -->
    <div class="card shadow mb-4 text-dark text-start">
        <div class="card-body">
            <form method="GET" action="{{ route('sarpras.consumables.history') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Jenis Mutasi</label>
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Barang Masuk (+)</option>
                        <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Barang Keluar (-)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Cari Nama Barang</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Ketik nama barang...">
                        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('sarpras.consumables.history') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-clockwise"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- History Table -->
    <div class="card shadow mb-4 text-dark text-start">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="150">Tanggal</th>
                            <th>Nama Barang</th>
                            <th class="text-center">Tipe</th>
                            <th class="text-center">Jumlah</th>
                            <th>Oleh</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                        <tr>
                            <td class="small">
                                {{ $trx->created_at->format('d/m/Y H:i') }}
                                <div class="text-muted small text-nowrap">{{ $trx->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $trx->consumable->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $trx->consumable->category->name ?? '-' }}</small>
                            </td>
                            <td class="text-center">
                                @if($trx->type === 'in')
                                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1">
                                        <i class="bi bi-plus-lg"></i> MASUK
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1">
                                        <i class="bi bi-dash-lg"></i> KELUAR
                                    </span>
                                @endif
                            </td>
                            <td class="text-center fw-bold {{ $trx->type === 'in' ? 'text-success' : 'text-danger' }}">
                                {{ $trx->type === 'in' ? '+' : '-' }}{{ $trx->quantity }} {{ $trx->consumable->unit_name ?? '' }}
                            </td>
                            <td class="small">
                                {{ $trx->user->name ?? 'Sistem' }}
                            </td>
                            <td class="small">
                                {{ $trx->note ?: '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat mutasi barang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-light border-top">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
