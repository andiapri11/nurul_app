@extends('layouts.app')

@section('title', 'Edit Jenis Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h3><i class="bi bi-pencil-square"></i> Edit Jenis Pembayaran</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form action="{{ route('finance.payment-types.update', $type->id) }}" method="POST">
                        @csrf 
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="fw-bold small">Kode (Opsional)</label>
                            <input type="text" name="code" class="form-control" value="{{ old('code', $type->code) }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold small">Nama Pembayaran</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $type->name) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold small">Unit Sekolah</label>
                            <select name="unit_id" class="form-select">
                                <option value="" {{ is_null($type->unit_id) ? 'selected' : '' }}>Semua Unit (Global)</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}" {{ $type->unit_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold small">Tipe</label>
                            <select name="type" class="form-select" required>
                                <option value="monthly" {{ $type->type == 'monthly' ? 'selected' : '' }}>Bulanan (SPP)</option>
                                <option value="one_time" {{ $type->type == 'one_time' ? 'selected' : '' }}>Sekali Bayar (Tahunan/Insidental)</option>
                            </select>
                        </div>
                        
                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-1"></i> Nominal tarif diatur pada menu <b>Atur Pembayaran Siswa</b>.
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('finance.payment-types.index') }}" class="btn btn-secondary">Batal</a>
                            <button class="btn btn-primary fw-bold">Update Jenis Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
