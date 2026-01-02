@extends('layouts.app')

@section('title', 'Edit Unit Sekolah')

@push('styles')
<style>
    :root {
        --unit-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    }
    .card-premium {
        border-radius: 24px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .btn-premium {
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    .btn-premium-primary {
        background: var(--unit-gradient);
        border: none;
        color: white;
    }
    .form-control-premium {
        border-radius: 12px;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-lg-4 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-premium shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-pencil-square fs-1"></i>
                        </div>
                        <h3 class="fw-extrabold">Ubah Unit Sekolah</h3>
                        <p class="text-muted">Perbarui informasi unit pendidikan.</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('units.update', $unit->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nama Unit Sekolah</label>
                            <input type="text" name="name" class="form-control form-control-premium" value="{{ old('name', $unit->name) }}" required autofocus>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-premium btn-premium-primary">
                                <i class="bi bi-arrow-repeat me-2"></i> Perbarui Unit
                            </button>
                            <a href="{{ route('units.index') }}" class="btn btn-premium btn-light border mt-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
